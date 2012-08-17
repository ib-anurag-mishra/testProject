<?php
class SolrComponent extends Object {

    var $components = array('Session');

    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusic/');//108.166.39.24//192.168.100.24

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
    var $total = null;

    function initialize($config = array()){
        $settings = array_merge((array)$config,self::$_defaults);
        App::import("Vendor","solr",array('file' => "Apache".DS."Solr".DS."Service.php"));
        self::$solr = new Apache_Solr_Service( $settings['server'], $settings['port'], $settings['solrpath']);
        if ( ! self::$solr->ping() ) {
          throw new SolrException();
        }
    }

    function search($keyword, $type = 'song', $sort="SongTitle", $sortOrder="asc", $page = 1, $limit = 10, $perfect=false){
        $query = '';
        $docs = array();
        $country = $this->Session->read('territory');
        $cond="";

        if($this->Session->read('block') == 'yes') {
          $cond = "AND Advisory:F";
        }

        $searchkeyword = $this->escapeSpace($keyword);
        if(!empty($country)){
          if(!isset(self::$solr)){
              self::initialize(null);
          }
          if($perfect == false){
            switch($type){
              case 'song':
                $query = '(CSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:*'.$searchkeyword.'*)';
                break;
              case 'genre':
                $query = '(CGenre:(*'.strtolower($searchkeyword).'*) OR Genre:*'.$searchkeyword.'*)';
                break;
              case 'album':
                $query = '(CTitle:(*'.strtolower($searchkeyword).'*) OR Title:*'.$searchkeyword.'* OR CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'* OR CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
                break;
              case 'artist':
                $query = '(CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'*)';
                break;
              case 'label':
                $query = '(CLabel:(*'.strtolower($searchkeyword).'*) OR Label:*'.$searchkeyword.'*)';
                break;
              case 'composer':
                $query = '(CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
                break;
              case 'all':
                $query = '((CSongTitle:(*'.strtolower($searchkeyword).'*) OR CGenre:(*'.strtolower($searchkeyword).'*) OR CTitle:(*'.strtolower($searchkeyword).'*) OR CArtistText:(*'.strtolower($searchkeyword).'*) OR CLabel:(*'.strtolower($searchkeyword).'*) OR CComposer:(*'.strtolower($searchkeyword).'*)) OR (SongTitle:(*'.$searchkeyword.'*) OR Genre:(*'.$searchkeyword.'*) OR Title:(*'.$searchkeyword.'*) OR ArtistText:(*'.$searchkeyword.'*) OR Label:(*'.$searchkeyword.'*) OR Composer:(*'.$searchkeyword.'*)))';
                break;
              default:
                $query = '((CSongTitle:(*'.strtolower($searchkeyword).'*) OR CGenre:(*'.strtolower($searchkeyword).'*) OR CTitle:(*'.strtolower($searchkeyword).'*) OR CArtistText:(*'.strtolower($searchkeyword).'*) OR CLabel:(*'.strtolower($searchkeyword).'*) OR CComposer:(*'.strtolower($searchkeyword).'*)) OR (SongTitle:(*'.$searchkeyword.'*) OR Genre:(*'.$searchkeyword.'*) OR Title:(*'.$searchkeyword.'*) OR ArtistText:(*'.$searchkeyword.'*) OR Label:(*'.$searchkeyword.'*) OR Composer:(*'.$searchkeyword.'*)))';
                break;
            }
          } else {
            switch($type){
              case 'song':
                $query = 'SongTitle:'.$searchkeyword;
                break;
              case 'genre':
                $query = 'Genre:'.$searchkeyword;
                break;
              case 'album':
                $query = 'Title:'.$searchkeyword;
                break;
              case 'artist':
                $query = 'ArtistText:'.$searchkeyword;
                break;
              case 'label':
                $query = 'Label:'.$searchkeyword;
                break;
              case 'composer':
                $query = 'Composer:'.$searchkeyword;
                break;
              case 'all':
                $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                break;
              default:
                $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                break;
            }
          }

          $query = $query.' AND Territory:'.$country.$cond;

          //echo ($query); die;

          if($page == 1){
            $start = 0;
          } else {
            $start = (($page - 1) * $limit);
          }
          $additionalParams = array();

          $additionalParams = array(
            'sort' => array('provider_type desc, '.$sort." ".$sortOrder)
          );

          $response = self::$solr->search( $query, $start, $limit, $additionalParams);
          if ( $response->getHttpStatus() == 200 ) {
            if ( $response->response->numFound > 0 ) {
              $this->total = $response->response->numFound;
              foreach ( $response->response->docs as $doc ) {
                $docs[] = $doc;
              }
            } else {
              return array();
            }
          }
          else {
            return array();
          }
          return $docs;
        } else {
            return array();
        }
    }

    function facetSearch($keyword, $type='song', $page=1, $limit = 5){
      $query = '';
      $country = $this->Session->read('territory');
      $cond="";

      if($this->Session->read('block') == 'yes') {
        $cond = "AND Advisory:F";
      }

      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:*'.$searchkeyword.'*)';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:(*'.strtolower($searchkeyword).'*) OR Genre:*'.$searchkeyword.'*)';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:(*'.strtolower($searchkeyword).'*) OR Title:*'.$searchkeyword.'* OR CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'* OR CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
            $field = 'Title';
            break;
          case 'artist':
            $query = '(CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'*)';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:(*'.strtolower($searchkeyword).'*) OR Label:*'.$searchkeyword.'*)';
            $field = 'Label';
            break;
          case 'composer':
            $query = '(CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:*'.$searchkeyword.'*)';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country;

        if($page == 1){
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
          'facet.offset' => $start,
          'facet.limit' => $limit,
          'sort' => array('provider_type desc')
        );

        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          if (!empty($response->facet_counts->facet_fields->$field)) {
            //print_r($response->facet_counts->facet_fields->$field); die;
            return $response->facet_counts->facet_fields->$field;
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
      } else {
        return array();
      }
    }

    function getFacetSearchTotal($keyword, $type='song'){
      $query = '';
      $country = $this->Session->read('territory');
      $cond = "";

      if($this->Session->read('block') == 'yes') {
        $cond = "AND Advisory:F";
      }
      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:*'.$searchkeyword.'*)';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:(*'.strtolower($searchkeyword).'*) OR Genre:*'.$searchkeyword.'*)';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:(*'.strtolower($searchkeyword).'*) OR Title:*'.$searchkeyword.'* OR CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'* OR CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
            $field = 'Title';
            break;
          case 'artist':
            $query = '(CArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:*'.$searchkeyword.'*)';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:(*'.strtolower($searchkeyword).'*) OR Label:*'.$searchkeyword.'*)';
            $field = 'Label';
            break;
          case 'composer':
            $query = '(CComposer:(*'.strtolower($searchkeyword).'*) OR Composer:*'.$searchkeyword.'*)';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:*'.$searchkeyword.'*)';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country.$cond;

        if($page == 1){
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

        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          if (!empty($response->facet_counts->facet_fields->$field)) {
            return count($response->facet_counts->facet_fields->$field);
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
      } else {
        return array();
      }
    }

    function query($query, $limit){
      $response = self::$solr->search($query, 0, $limit);
      if ( $response->getHttpStatus() == 200 ) {
        if ( $response->response->numFound > 0 ) {
          foreach ( $response->response->docs as $doc ) {
            $docs[] = $doc;
          }
        } else {
          return array();
        }
      }
      else {
        return array();
      }
      return $docs;
    }

    function escapeSpace($keyword){
      //$keyword = mb_strtolower($keyword, 'UTF-8');
      $keyword = str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $keyword);
      $keyword = utf8_decode($keyword);
      //$keyword = utf8_decode(str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $keyword));
      return $keyword;
    }
}


class SolrException extends Exception  { }
?>