<?php

class SolrComponent extends Object {

    var $components = array('Session');

    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusic/'); //108.166.39.24//192.168.100.24//192.168.100.24

    /**
     * Used for runtime configuration of model
     */
    static $_defaults2 = array('server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusicvideos/'); //108.166.39.24//192.168.100.24//192.168.100.24

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    static $solr = null;

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    static $solr2 = null;

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    var $total = null;

    function initialize($config = array(), $config2 = array()) {
        $settings = array_merge((array) $config, self::$_defaults);
        $settings2 = array_merge((array) $config2, self::$_defaults2);
        App::import("Vendor", "solr", array('file' => "Apache" . DS . "Solr" . DS . "Service.php"));
        self::$solr = new Apache_Solr_Service($settings['server'], $settings['port'], $settings['solrpath']);
        //var_dump($solr);
        if (!self::$solr->ping()) {
            //echo "Not Connected";
            //die;
            throw new SolrException();
        }

        self::$solr2 = new Apache_Solr_Service($settings2['server'], $settings2['port'], $settings2['solrpath']);

        if (!self::$solr2->ping()) {
            //echo "Not Connected";
            //die;
            throw new SolrException();
        }
    }

    function search($keyword, $type = 'song', $sort="SongTitle", $sortOrder="asc", $page = 1, $limit = 10, $country, $perfect=false) {
        $query = '';
        $docs = array();
        $cond = " AND DownloadStatus:1";
        
        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }
        $searchkeyword = strtolower($this->escapeSpace($keyword));
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }
            if ($perfect == false) {
                switch ($type) {
                    case 'song':
                        //$query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
                        //$query = $keyword . ' OR ((CSongTitle:(' . $searchkeyword . ') OR CTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . ') OR CComposer:(' . $searchkeyword . ')))';
                        $query = $searchkeyword;
                        $queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
                        break;
                    case 'genre':
                        //$query = $keyword . ' OR (CGenre:(' . $searchkeyword . '))';
                        $query = $searchkeyword;
                        $queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
                        break;
                    case 'album':
                        //$query = $keyword . ' OR (CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CComposer:('.$searchkeyword.'))';
                        $query = $searchkeyword;
                        $queryFields = "CTitle^100 CArtistText^80 CComposer^60 CGenre^20 CSongTitle";
                        break;
                    case 'artist':
                        //$query = '(CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.')';
                        //$query = $keyword . ' OR (CArtistText:(' . $searchkeyword . '))';
                        $query = $searchkeyword;
                        $queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
                        break;
                    case 'label':
                        //$query = $keyword . ' OR (CLabel:(' . $searchkeyword . '))';
                        $query = $searchkeyword;
                        $queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
                        break;
                    case 'video':
                        //$query = $keyword . ' OR (CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
                        $query = $searchkeyword;
                        $queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
                        break;
                    case 'composer':
                        //$query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                        //$query = $keyword . ' OR (CComposer:(' . $searchkeyword . '))';
                        $query = $searchkeyword;
                        $queryFields = "CComposer^100 CArtistText^80 CTitle^60 CSongTitle^20 CGenre";
                        break;
                    case 'all':
                        //$query = $keyword . ' OR ((CSongTitle:('.$searchkeyword.') OR CGenre:('.$searchkeyword.') OR CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CLabel:('.$searchkeyword.') OR CComposer:('.$searchkeyword.')))';
                        $query = $searchkeyword;
                        $queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
                        break;
                    default:
                        //$query = $keyword . ' OR ((CSongTitle:('.$searchkeyword.') OR CGenre:('.$searchkeyword.') OR CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CLabel:('.$searchkeyword.') OR CComposer:('.$searchkeyword.')))';
                        $query = $searchkeyword;
                        $queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
                        break;
                }
            } /*else {
                switch ($type) {
                    case 'song':
                        //$query = 'SongTitle:'.$searchkeyword;
                        $query = '(SongTitle:' . $searchkeyword . ' OR Title:' . $searchkeyword . ' OR ArtistText:' . $searchkeyword . ' OR Composer:' . $searchkeyword . ')';
                        break;
                    case 'genre':
                        $query = 'Genre:*' . $searchkeyword . '*';
                        break;
                    case 'album':
                        $query = 'Title:' . $searchkeyword;
                        break;
                    case 'artist':
                        $query = 'ArtistText:' . $searchkeyword;
                        break;
                    case 'label':
                        $query = 'Label:' . $searchkeyword;
                        break;
                    case 'video':
                        $query = 'VideoTitle:' . $searchkeyword;
                        break;
                    case 'composer':
                        $query = 'Composer:' . $searchkeyword;
                        break;
                    case 'all':
                        $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword . ' OR Composer:' . $searchkeyword . ')';
                        break;
                    default:
                        $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword . ' OR Composer:' . $searchkeyword . ')';
                        break;
                }
            }*/

            $query = $query . ' AND Territory:' . $country . $cond;
            
            
            
            // echo '<br /> Rows :'.$query.'<br />'; die;

            if ($page == 1) {
                $start = 0;
            } else {
                $start = (($page - 1) * $limit);
            }
            //$additionalParams = array();

            $additionalParams = array(
                    //'sort' => 'provider_type desc, '.$sort." ".$sortOrder
                'defType' => 'edismax',
                'qf' => $queryFields
            );

            //-------------------------------------------------------------------------------------------------------------------------------------------------

            if (1 == $page) {
                unset($_SESSION['pagebreak']);
                unset($_SESSION['combine_page']);
                unset($_SESSION['ioda_cons']);
                unset($_SESSION['sony_total']);
            }
            if (!(isset($_SESSION['pagebreak']))) {
                $provider_query .= ' AND provider_type:sony';
                if ($type != 'video') {
                    $response = self::$solr->search($query . $provider_query, 0, 1, $additionalParams);
                } else {
                    $response = self::$solr2->search($query . $provider_query, 0, 1, $additionalParams);
                }
                $num_found = $response->response->numFound;

                //echo 'num_found :' . $num_found . '<br />';
                if (0 == $num_found) {
                    // ioda call
                    $_SESSION['pagebreak'] = 0;
                    $_SESSION['combine_page'] = 0;
                    $_SESSION['ioda_cons'] = 0;
                } else {
                    $tot_pages = $num_found / $limit;
                    if (is_float($tot_pages)) {
                        $_SESSION['pagebreak'] = intval($tot_pages) + 1;
                        $_SESSION['combine_page'] = 1;
                    } else {
                        $_SESSION['pagebreak'] = $tot_pages;
                        $_SESSION['combine_page'] = 0;
                    }
                }
            }


            if ($page < $_SESSION['pagebreak']) { //echo '<br />SONY<br />';
                $provider_query = ' AND provider_type:sony';
                $tmp_start = ($page - 1) * $limit;
                $start = $tmp_start;
                if ($type != 'video') {
                    $response = self::$solr->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                } else {
                    $response = self::$solr2->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                }
            }//sony
            if ($page == $_SESSION['pagebreak']) { //echo '<br />SONY & IODA<br />';
                $provider_query = ' AND provider_type:sony';
                $tmp_start = ($page - 1) * $limit;
                $start = $tmp_start;
                if ($type != 'video') {
                    $response = self::$solr->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                } else {
                    $response = self::$solr2->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                }

                $_SESSION['sony_total'] = $response->response->numFound;
                $fetched_result_count = count($response->response->docs);
                $_SESSION['ioda_cons'] = ($limit - $fetched_result_count);

                if (1 == $_SESSION['combine_page']) {
                    $provider_query = ' AND provider_type:ioda';
                    $start = 0;
                    if ($type != 'video') {
                        $sec_response = self::$solr->search($query . $provider_query, 0, ($limit - $fetched_result_count), $additionalParams);
                    } else {
                        $sec_response = self::$solr2->search($query . $provider_query, 0, ($limit - $fetched_result_count), $additionalParams);
                    }

                    if ($sec_response->response->numFound > 0) {
                        $sec_response->response->docs = array_merge($response->response->docs, $sec_response->response->docs);
                        $response = $sec_response;
                    }
                } else {
                    $provider_query = ' AND provider_type:ioda';
                    $start = 0;
                    if ($type != 'video') {
                        $sec_response = self::$solr->search($query . $provider_query, 0, 1, $additionalParams);
                    } else {
                        $sec_response = self::$solr2->search($query . $provider_query, 0, 1, $additionalParams);
                    }

                    if ($sec_response->response->numFound > 0) {
                        $response->response->numFound = $sec_response->response->numFound;
                    }
                }
            }//sony & ioda
            if ($page > $_SESSION['pagebreak']) { //echo '<br />IODA<br />';
                $provider_query = ' AND provider_type:ioda';
                $tmp_start = ((($page - $_SESSION['pagebreak']) - 1) * $limit) + $_SESSION['ioda_cons'];
                $start = $tmp_start;
                if ($type != 'video') {
                    $response = self::$solr->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                } else {
                    $response = self::$solr2->search($query . $provider_query, $tmp_start, $limit, $additionalParams);
                }

                $response->response->numFound = $response->response->numFound + $_SESSION['sony_total'];
            }//ioda
            //echo 'pagebreak : ' . $_SESSION['pagebreak'] . '<br />';
            //echo 'combine_page : ' . $_SESSION['combine_page'] . '<br />';
            //echo 'ioda_cons : ' . $_SESSION['ioda_cons'] . '<br />';
//---------------------------------------------------------------------------------------------------------------------------------------//
            //ho '<pre>';
            //ho '<br /> QUERY > '.$query.' START > '.$start.' LIMIT > '.$limit.' PAGE > '.$page.' PROVIDER QUERY > '.$provider_query .'<br />';
            //int_r($response->response);
            //ho '</pre>';
            // $response = self::$solr->search( $query, $start, $limit, $additionalParams);
            if ($response->getHttpStatus() == 200) {
                if ($response->response->numFound > 0) {
                    $this->total = $response->response->numFound;
                    foreach ($response->response->docs as $doc) {
                        $docs[] = $doc;
                    }
                } else {
                    return array();
                }
            } else {
                return array();
            }
            return $docs;
        } else {
            return array();
        }
    }

    function facetSearch($keyword, $type='song', $page=1, $limit = 5) {
        $query = '';
        $country = $this->Session->read('territory');
        $cond = " AND DownloadStatus:1";
        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }

        $searchkeyword = strtolower($this->escapeSpace($keyword));
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }

            switch ($type) {
                case 'song':
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
                case 'genre':
                    $query = '(CGenre:(' . $searchkeyword . '))';
                    $field = 'Genre';
                    break;
                case 'album':
                    $query = '(CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CComposer:(' . $searchkeyword . '))';
                    $field = 'Title';
                    break;
                case 'artist':
                    $query = '(CArtistText:(' . $searchkeyword . '))';
                    $field = 'ArtistText';
                    break;
                case 'label':
                    $query = '(CLabel:(' . $searchkeyword . '))';
                    $field = 'Label';
                    break;
                case 'video':
                    $query = '(CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
                    $field = 'VideoTitle';
                    break;
                case 'composer':
                    $query = '(CComposer:(' . $searchkeyword . '))';
                    $field = 'Composer';
                    break;
                default:
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
            }

            $query = $query . ' AND Territory:' . $country;
            //echo $query; die;

            if ($page == 1) {
                $start = 0;
            } else {
                $start = (($page - 1) * $limit);
            }

            $additionalParams = array(
                'facet' => 'true',
                'facet.field' => array(
                    $field,
                ),
                'facet.query' => $query,
                'facet.mincount' => 1,
                'facet.limit' => $limit,
            );
            if ($type != 'video') {
                $response = self::$solr->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->facet_counts->facet_fields->$field)) {
                        return $response->facet_counts->facet_fields->$field;
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            } else {
                $response = self::$solr2->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->facet_counts->facet_fields->$field)) {
                        return $response->facet_counts->facet_fields->$field;
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            }
        } else {
            return array();
        }
    }

    function getFacetSearchTotal($keyword, $type='song') {
        $query = '';
        $country = $this->Session->read('territory');
        $cond = " AND DownloadStatus:1";

        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }
        $searchkeyword = strtolower($this->escapeSpace($keyword));
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }

            switch ($type) {
                case 'song':
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
                case 'genre':
                    $query = '(CGenre:(' . $searchkeyword . '))';
                    $field = 'Genre';
                    break;
                case 'album':
                    $query = '(CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') CComposer:(' . $searchkeyword . '))';
                    $field = 'Title';
                    break;
                case 'artist':
                    $query = '(CArtistText:(' . $searchkeyword . '))';
                    $field = 'ArtistText';
                    break;
                case 'label':
                    $query = '(CLabel:(' . $searchkeyword . '))';
                    $field = 'Label';
                    break;
                case 'video':
                    $query = '(CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
                    $field = 'VideoTitle';
                    break;
                case 'composer':
                    $query = '(CComposer:(' . $searchkeyword . '))';
                    $field = 'Composer';
                    break;
                default:
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
            }

            $query = $query . ' AND Territory:' . $country . $cond;

            if ($page == 1) {
                $start = 0;
            } else {
                $start = (($page - 1) * $limit);
            }

            $additionalParams = array(
                'facet' => 'true',
                'facet.field' => array(
                    $field
                ),
                'facet.query' => $query,
                'facet.mincount' => 1,
                'facet.limit' => -1
            );

            if ($type != 'video') {
                $response = self::$solr->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->facet_counts->facet_fields->$field)) {
                        return count($response->facet_counts->facet_fields->$field);
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            } else {
                $response = self::$solr2->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->facet_counts->facet_fields->$field)) {
                        return count($response->facet_counts->facet_fields->$field);
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            }
        } else {
            return array();
        }
    }

    function groupSearch($keyword, $type='song', $page=1, $limit = 5) {
        $query = '';
        $country = $this->Session->read('territory');
        $cond = " AND DownloadStatus:1";

        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F"; 
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }

        $searchkeyword = strtolower($this->escapeSpace($keyword));
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }

            switch ($type) {
                case 'song':
                    //$query = $keyword . ' OR (CSongTitle:(' . $searchkeyword . '))';
                    $query = $searchkeyword;
                    $queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
                    $field = 'SongTitle';
                    break;
                case 'genre':
                    //$query = $keyword . ' OR (CGenre:(' . $searchkeyword . '))';
                    $queryFields = "CGenre^100 CTitle^80 CSongTitle^60 CArtistText^20 CComposer";
                    $query = $searchkeyword;
                    $field = 'Genre';
                    break;
                case 'album':
                    //$query = $keyword . ' OR (CTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.') OR CComposer:('.$searchkeyword.'))';
                    $queryFields = "CTitle^100 CArtistText^80 CComposer^60 CGenre^20 CSongTitle";
                    $query = $searchkeyword;
                    //$field = 'Title';
                    $field = 'rpjoin';
                    break;
                case 'artist':
                    //$query = $keyword . ' OR (CArtistText:(' . $searchkeyword . '))';
                    $queryFields = "CArtistText^100 CTitle^80 CSongTitle^60 CGenre^20 CComposer";
                    $query = $searchkeyword;
                    $field = 'ArtistText';
                    break;
                case 'label':
                    //$query = $keyword . ' OR (CLabel:(' . $searchkeyword . '))';
                    $queryFields = "CLabel^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
                    $query = $searchkeyword;
                    $field = 'Label';
                    break;
                case 'video':
                    //$query = $keyword . ' OR (CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
                    $query = $searchkeyword;
                    $queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
                    $field = 'VideoTitle';
                    break;
                case 'composer':
                    //$query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
                    //$query = $keyword . ' OR (CComposer:(' . $searchkeyword . '))';
                    $query = $searchkeyword;
                    $queryFields = "CComposer^100 CArtistText^80 CTitle^60 CSongTitle^20 CGenre";
                    $field = 'Composer';
                    break;
                default:
                    //$query = $keyword . '( OR CSongTitle:(' . $searchkeyword . '))';
                    $query = $searchkeyword;
                    $queryFields = "CSongTitle^100 CTitle^80 CArtistText^60 CComposer^20 CGenre";
                    $field = 'SongTitle';
                    break;
            }

            $query = $query . ' AND Territory:' . $country . $cond;
            // echo $query; // die;
            if ($page == 1) {
                $start = 0;
            } else {
                $start = (($page - 1) * $limit);
            }

            $additionalParams = array(
                'defType' => 'edismax',
                'qf' => $queryFields,
                'group' => 'true',
                'group.field' => $field,
                'group.query' => $query,
                'group.sort' => 'provider_type desc',
            );

            /* $query = '(
              CArtistText: (britney spears)  OR
              CArtistText: (britney spears*) OR
              CArtistText: (*britney spears) OR
              CArtistText: (*britney*)       OR
              CArtistText: (*spears*)        OR
              ArtistText:Britney\ spears
              ) AND Territory:US'; */
            //$query = '(CArtistText:(*britney* *spears*) OR ArtistText:Britney\ spears) AND Territory:US';
            // echo '<br /> Boxs : '.$query.'<br />';

            if ($type != 'video') {
                $response = self::$solr->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    //print_r($response->grouped); die;
                    if (!empty($response->grouped->$field->groups)) {
                        $docs = array();
                        foreach ($response->grouped->$field->groups as $group) {
                            $group->doclist->docs[0]->numFound = $group->doclist->numFound;
                            $docs[] = $group->doclist->docs[0];
                        } //echo '<pre>'; print_r($docs); echo '</pre>';
                        // print_r($docs); die;
                        return $docs;
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            } else {
                $response = self::$solr2->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    //print_r($response->grouped); die;
                    if (!empty($response->grouped->$field->groups)) {
                        $docs = array();
                        foreach ($response->grouped->$field->groups as $group) {
                            $group->doclist->docs[0]->numFound = $group->doclist->numFound;
                            $docs[] = $group->doclist->docs[0];
                        } //echo '<pre>'; print_r($docs); echo '</pre>';
                        // print_r($docs); die;
                        return $docs;
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            }
        } else {
            return array();
        }
    }

    function getGroupSearchTotal($keyword, $type='song') {
        $query = '';
        $country = $this->Session->read('territory');
        $cond = " AND DownloadStatus:1";

        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }

        $searchkeyword = strtolower($this->escapeSpace($keyword));
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }

            switch ($type) {
                case 'song':
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
                case 'genre':
                    $query = '(CGenre:(' . $searchkeyword . '))';
                    $field = 'Genre';
                    break;
                case 'album':
                    $query = '(CTitle:('.$searchkeyword.') OR CArtistText:('.strtolower($searchkeyword).') OR CComposer:(' . $searchkeyword . '))';
                    $field = 'Title';
                    break;
                case 'artist':
                    $query = '(CArtistText:(' . $searchkeyword . '))';
                    $field = 'ArtistText';
                    break;
                case 'label':
                    $query = '(CLabel:(' . $searchkeyword . '))';
                    $field = 'Label';
                    break;
                case 'video':
                    $query = '(CVideoTitle:(' . $searchkeyword . ') OR CArtistText:(' . $searchkeyword . '))';
                    $field = 'VideoTitle';
                    break;
                case 'composer':
                    $query = '(CComposer:(' . $searchkeyword . '))';
                    $field = 'Composer';
                    break;
                default:
                    $query = '(CSongTitle:(' . $searchkeyword . '))';
                    $field = 'SongTitle';
                    break;
            }

            $query = $query . ' AND Territory:' . $country . $cond;

            if ($page == 1) {
                $start = 0;
            } else {
                $start = (($page - 1) * $limit);
            }

            $additionalParams = array(
                'group' => 'true',
                'fl' => array(
                    $field
                ),
                'group.query' => $query,
            );

            if ($type != 'video') {
                $response = self::$solr->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->grouped->$query->doclist->numFound)) {
                        return count($response->grouped->$query->doclist->docs);
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            } else {
                $response = self::$solr2->search($query, $start, $limit, $additionalParams);
                if ($response->getHttpStatus() == 200) {
                    if (!empty($response->grouped->$query->doclist->numFound)) {
                        return count($response->grouped->$query->doclist->docs);
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
                return array();
            }
        } else {
            return array();
        }
    }

    function getAutoCompleteData($keyword, $type, $limit=10, $allmusic=0) {

        $query = '';
        $country = $this->Session->read('territory');
        $cond = " AND DownloadStatus:1";

        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }
        }
        $searchkeyword = strtolower($this->escapeSpace($keyword));
        $char = substr($keyword, 0, 1);
        if (!empty($country)) {
            if (!isset(self::$solr)) {
                self::initialize(null);
            }
            //echo '/'.$type.'/';
            if ($type != 'all') {
                switch ($type) {
                    case 'song':
                        //$query = '(CSongTitle:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CSongTitle";
                        $field = 'SongTitle';
                        break;
                    case 'genre':
                        //$query = '(CGenre:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CGenre";
                        $field = 'Genre';
                        break;
                    case 'album':
                        //$query = '(CTitle:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CTitle";
                        $field = 'Title';
                        break;
                    case 'artist':
                        //$query = '(CArtistText:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CArtistText";
                        $field = 'ArtistText';
                        break;
                    case 'label':
                        //$query = '(CLabel:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CLabel";
                        $field = 'Label';
                        break;
                    case 'video':
                        //$query = '(CVideoTitle:('.$searchkeyword.') OR CArtistText:('.$searchkeyword.'))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CVideoTitle^100 CArtistText^80 CTitle^60";
                        $field = 'VideoTitle';
                        break;
                    case 'composer':
                        //$query = '(CComposer:('.strtolower($searchkeyword).') OR TComposer:('.$searchkeyword.') OR Composer:('.$searchkeyword.'))';
                        //$query = '(CComposer:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CComposer";
                        $field = 'Composer';
                        break;
                    default:
                        //$query = '(CSongTitle:(' . $searchkeyword . '))';
                        $query = $searchkeyword.'*';
                        $queryFields = "CSongTitle";
                        $field = 'SongTitle';
                        break;
                }

                $query = $query . ' AND Territory:' . $country . $cond;

                //echo $query.'<br />'; //die;

                $additionalParams = array(
                    'defType' => 'edismax',
                    'qf' => $queryFields,
                    'facet' => 'true',
                    'facet.field' => array(
                        $field
                    ),
                    'facet.query' => $query,
                    'facet.mincount' => 1,
                    'facet.limit' => $limit
                );


                if ($type != 'video') {
                    $response = self::$solr->search($query, 0, 0, $additionalParams);
                    if ($response->getHttpStatus() == 200) {
                        if (!empty($response->facet_counts->facet_fields->$field)) {

                            if (1 == $allmusic) {
                                $arr_result = array();
                                $arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field;
                                return $arr_result;
                            } else {
                                return $response->facet_counts->facet_fields->$field;
                            }
                        } else {
                            return array();
                        }
                    } else {
                        return array();
                    }
                    return array();
                } else {
                    $response = self::$solr2->search($query, 0, 0, $additionalParams);
                    if ($response->getHttpStatus() == 200) {
                        if (!empty($response->facet_counts->facet_fields->$field)) {

                            if (1 == $allmusic) {
                                $arr_result = array();
                                $arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field;
                                return $arr_result;
                            } else {
                                return $response->facet_counts->facet_fields->$field;
                            }
                        } else {
                            return array();
                        }
                    } else {
                        return array();
                    }
                    return array();
                }
            } else {
                
            }
        } else {
            return array();
        }
    }

    function query($query, $limit) {
        
        $country = $this->Session->read('territory');
        
        $cond = " AND DownloadStatus:1";

        if ($this->Session->read('block') == 'yes') {
            $cond .= " AND Advisory:F";
            /*if($type != 'video'){
                $cond .= " AND AAdvisory:F";
            }*/
        }
        
        $query = $query . ' AND Territory:' . $country . $cond;
        
        $response = self::$solr->search($query, 0, $limit);
        if ($response->getHttpStatus() == 200) {
            if ($response->response->numFound > 0) {
                foreach ($response->response->docs as $doc) {
                    $docs[] = $doc;
                }
            } else {
                return array();
            }
        } else {
            return array();
        }
        return $docs;
    }

    function escapeSpace($keyword) {
        //$keyword = mb_strtolower($keyword, 'UTF-8');
        //$keyword = str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $keyword);
        $keyword = str_replace(array('(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $keyword); // for edismax
        //$keyword = utf8_decode($keyword);
        //$keyword = utf8_decode(str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[', '\]', '\^', '\~', '\*', '\?'), $keyword));
        return $keyword;
    }

}

class SolrException extends Exception {
    
}

?>
