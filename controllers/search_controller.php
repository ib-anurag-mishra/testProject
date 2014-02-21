<?php

/* File Name: homes_controller.php
  File Description: Displays the home page for each patron
  Author: Maycreate
 */

class SearchController extends AppController
{

    var $name = 'Search';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Song', 'Language', 'Album', 'Session', 'WishlistVideo', 'Mvideo', 'Search', 'Queue');
    var $components = array('Auth', 'Acl', 'RequestHandler', 'ValidatePatron', 'Downloads', 'PasswordHelper', 'Email', 'SuggestionSong', 'Cookie', 'Solr', 'Session');
    var $uses = array('Home', 'User', 'Featuredartist', 'Artist', 'Library', 'Download', 'Genre', 'Currentpatron', 'Page', 'Wishlist', 'Album', 'Song', 'Language', 'Searchrecord','LatestDownload','LatestVideodownload');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'autocomplete','ajaxcheckdownload','index_new');
    }

    function index($page = 1, $facetPage = 1)
    {
        //set_time_limit(0);
        //echo "<br>Started at ".date("Y-m-d H:i:s");
        // reset page parameters when serach keyword changes
        // to check if the search is made from search bar or click on search page
        $layout = $_GET['layout'];

        if (('' == trim($_GET['q'])) || ('' == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
        }// unset session when no params
        if ((isset($_SESSION['SearchReq'])) && ($_SESSION['SearchReq']['word'] != trim($_GET['q'])) && ($_SESSION['SearchReq']['type'] == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
            $this->redirect(array('controller' => 'search', 'action' => 'index?q=' . $_GET['q'] . '&type=' . $_GET['type']));
        }//reset session & redirect to 1st page
        if (('' != trim($_GET['q'])) && ('' != trim($_GET['type'])))
        {
            $_SESSION['SearchReq']['word'] = $_GET['q'];
            $_SESSION['SearchReq']['type'] = $_GET['type'];
        }//sets values in session

        $queryVar = null;
        $check_all = null;
        $sortVar = 'ArtistText';
        $sortOrder = 'asc';

        if (isset($_GET['q']))
        {
            $queryVar = $_GET['q']; // html_entity_decode();
        }
        if (isset($_GET['type']))
        {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer' || $_GET['type'] == 'video') ? $_GET['type'] : 'all');
        }
        else
        {
            $typeVar = 'all';
        }
        $this->set('type', $typeVar);

        if (isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
            $sort = (($sort == 'song' || $sort == 'album' || $sort == 'artist' || $sort == 'composer') ? $sort : 'artist');
            switch ($sort)
            {
                case 'song':
                    $sortVar = 'SongTitle';
                    break;
                case 'album':
                    $sortVar = 'Title';
                    break;
                case 'genre':
                    $sortVar = 'Genre';
                    break;
                case 'label':
                    $sortVar = 'Label';
                    break;
                case 'video':
                    $sortVar = 'VideoTitle';
                    break;
                case 'artist':
                    $sortVar = 'ArtistText';
                    break;
                case 'composer':
                    $sortVar = 'Composer';
                    break;
                default:
                    $sortVar = 'ArtistText';
                    break;
            }
        }
        else
        {
            $sort = 'artist';
        }

        $this->set('sort', $sort);

        if (isset($_GET['sortOrder']))
        {
            $sortOrder = $_GET['sortOrder'];
            $sortOrder = (($sortOrder == 'asc' || $sortOrder == 'desc') ? $sortOrder : 'asc');
        }
        else
        {
            $sortOrder = 'asc';
        }

        $this->set('sortOrder', $sortOrder);


        if (!empty($queryVar))
        {
            //Added code for log search data
            $insertArr[] = $this->searchrecords($typeVar, $queryVar);
            $this->Searchrecord->saveAll($insertArr);
            //End Added code for log search data

            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();

            $total = 0;
            $limit = 10;

            if (!isset($page) || $page < 1)
            {
                $page = 1;
            }
            else
            {
                $page = $page;
            }

            if (!isset($facetPage) || $facetPage < 1)
            {
                $facetPage = 1;
            }
            else
            {
                $facetPage = $facetPage;
            }

            $country = $this->Session->read('territory');
            //echo "<br>Search for Songs Started at ".date("Y-m-d H:i:s");
            $songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country);
            //echo "<br>Search for Songs Ended at ".date("Y-m-d H:i:s");

            $total = $this->Solr->total;
            $totalPages = ceil($total / $limit);

            if ($total != 0)
            {
                /* if($page > $totalPages){
                  $page = $totalPages;
                  $this->redirect();
                  } */
            }

            /* echo "Microtime : ".microtime();
              echo "Time : ".date('h:m:s'); */

            $songArray = array();
            foreach ($songs as $key => $song)
            {
                $songArray[] = $song->ProdID;
            }
            
            if($type == 'video'){
                $downloadsUsed = $this->LatestVideodownload->find('all', array('conditions' => array('LatestVideodownload.ProdID in (' . implode(',', $songArray) . ')', 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
            } else {
                $downloadsUsed = $this->LatestDownload->find('all', array('conditions' => array('LatestDownload.ProdID in (' . implode(',', $songArray) . ')', 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
            }
            foreach ($songs as $key => $song)
            {
                $set = 0;
                foreach ($downloadsUsed as $downloadKey => $downloadData)
                {
		   if($type == video)
                   {
                    if ($downloadData['LatestVideodownload']['ProdID'] == $song->ProdID)
                    {
                        $songs[$key]->status = 'avail';
                        $set = 1;
                        break;
                    } 
                   } else {
                   if ($downloadData['LatestDownload']['ProdID'] == $song->ProdID)
                    {
                        $songs[$key]->status = 'avail';
                        $set = 1;
                        break;
                    }
                   }
                }
                if ($set == 0)
                {
                    $songs[$key]->status = 'not';
                }
            }
            /* echo "Microtime : ".microtime();
              echo "Time : ".date('h:m:s'); */

            $this->set('songs', $songs);
            // print_r($songs);
            // Added code for all functionality
            // print_r($songs);

            if (!empty($type) && !($type == 'all'))
            {

                switch ($typeVar)
                {
                    case 'album':
                        $limit = 12;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'album');
                        // echo "Group Search for Albums Started at ".time();
                        $albums = $this->Solr->groupSearch($queryVar, 'album', $facetPage, $limit);

                        // echo "Group Search for Albums Ended at ".time();

                        $arr_albumStream = array();

                        foreach ($albums as $objKey => $objAlbum)
                        {
                            $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                                    array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                            );
                        }
                        //  echo "<pre>"; print_r($albums);
                        $this->set('albumData', $albums);
                        $this->set('arr_albumStream', $arr_albumStream);

                        break;

                    case 'genre':
                        $limit = 30;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'genre');
                        $genres = $this->Solr->groupSearch($queryVar, 'genre', $facetPage, $limit);
                        //print_r($genres); die;
                        $this->set('genres', $genres);
                        break;

                    case 'label':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'label');
                        $labels = $this->Solr->groupSearch($queryVar, 'label', $facetPage, $limit);
                        $this->set('labels', $labels);
                        break;

                    case 'artist':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'artist');
                        $artists = $this->Solr->groupSearch($queryVar, 'artist', $facetPage, $limit);
                        $this->set('artists', $artists);
                        break;

                    case 'composer':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'composer');
                        $composers = $this->Solr->groupSearch($queryVar, 'composer', $facetPage, $limit);
                        $this->set('composers', $composers);
                        break;
                }

                $this->set('totalFacetFound', $totalFacetCount);
                if (!empty($totalFacetCount))
                {
                    $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
                }
                else
                {
                    $this->set('totalFacetPages', 0);
                }
            }
            else
            {

                //echo "<br>Group Search for Albums Started at ".date("Y-m-d H:i:s");
                $albums = $this->Solr->groupSearch($queryVar, 'album', 1, 4);
                //echo "<br>Group Search for Albums Ended at ".date("Y-m-d H:i:s");
                $queryArr = null;
                $albumData = array();
                $albumsCheck = array_keys($albums);
                for ($i = 0; $i <= count($albumsCheck) - 1; $i++)
                {
                    $queryArr = $this->Solr->query('Title:"' . utf8_decode(str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $albumsCheck[$i])) . '"', 1);
                    $albumData[] = $queryArr[0];
                }
                

                $arr_albumStream = array();

                foreach ($albums as $objKey => $objAlbum)
                {
                    $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                    );
                }

                //echo "<br>Group Search for Artists Started at ".date("Y-m-d H:i:s");
                $artists = $this->Solr->groupSearch($queryVar, 'artist', 1, 5);
                //echo "<br>Group Search for Artists Ended at ".date("Y-m-d H:i:s");
                //echo "<br>Group Search for Genres Started at ".date("Y-m-d H:i:s");
                $genres = $this->Solr->groupSearch($queryVar, 'genre', 1, 5);
                //echo "<br>Group Search for Genres Ended at ".date("Y-m-d H:i:s");;
                //echo "<br>Group Search for Composers Started at ".date("Y-m-d H:i:s");
                $composers = $this->Solr->groupSearch($queryVar, 'composer', 1, 5);
                //echo "<br>Group Search for Composers Ended at ".date("Y-m-d H:i:s");
                // $labels = $this->Solr->groupSearch($queryVar, 'label', 1, 5);
                //echo "<br>Group Search for Video Started at ".date("Y-m-d H:i:s");
                $videos = $this->Solr->groupSearch($queryVar, 'video', 1, 5);
                //echo "<br>Group Search for Video ended at ".date("Y-m-d H:i:s");
                // print_r($videos); die;
                $this->set('albums', $albums);
                $this->set('arr_albumStream', $arr_albumStream);
                //$this->set('albumData',$albumData);
                $this->set('albumData', $albums);
                $this->set('artists', $artists);
                $this->set('genres', $genres);
                //print_r($genres);die;
                $this->set('composers', $composers);
                //$this->set('labels', $labels);
                $this->set('videos', $videos);
            }
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
            $this->set('total', $total);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('facetPage', $facetPage);
        }
        $this->set('keyword', htmlspecialchars($queryVar));
        //echo "<br>search end- ".date("Y-m-d H:i:s");

        if (isset($this->params['isAjax']) && $this->params['isAjax'] && $layout == 'ajax')
        {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo $this->render();
            die;
        }
        else
        {
            $this->layout = 'home';
        }
    }

    function searchrecords($type, $search_text)
    {
        $search_text = strtolower(trim($search_text));
        $search_text = preg_replace('/\s\s+/', ' ', $search_text);
        $insertArr['search_text'] = $search_text;
        $insertArr['type'] = $type;
        $genre_id_count_array = $this->Searchrecord->find('all', array('conditions' => array('search_text' => $search_text, 'type' => $type)));
        if (count($genre_id_count_array) > 0)
        {
            $insertArr['count'] = $genre_id_count_array[0]['Searchrecord']['count'] + 1;
            $insertArr['id'] = $genre_id_count_array[0]['Searchrecord']['id'];
        }
        else
        {
            $insertArr['count'] = 1;
        }

        return $insertArr;
    }

    function autocomplete()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (isset($_GET['q']))
        {
            $queryVar = $_GET['q'];
        }
        if (isset($_GET['type']))
        {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer' || $_GET['type'] == 'video') ? $_GET['type'] : 'all');
        }
        else
        {
            $typeVar = 'all';
        }
        if ($type != 'all')
        {
            $data = $this->Solr->getAutoCompleteData($queryVar, $type, 10);
        }
        $records = array();
        switch ($typeVar)
        {
            case 'all':
                $records = array();
                $data1 = array();
                $data2 = array();
                $data3 = array();
                $arr_data = $arr_records = array();

                // each indiviual filter call
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'album', 18, '1');
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'artist', 18, '1');
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'composer', 18, '1');
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'genre', 18, '1');
                // $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'label', 18, '1');
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'song', 18, '1');

                // formates array
                foreach ($arr_data as $key1 => $val1)
                {
                    foreach ($val1 as $key2 => $val2)
                    {
                        $arr_result[$key2] = $val2;
                    }
                }

                //sort ain decending order of match result count
                @krsort($arr_result);

                //get 3 elements of each filter
                $arr_show = $arr_result;
                $in_basket = 0;
                foreach ($arr_result AS $key1 => $val1)
                {
                    foreach ($val1 AS $key2 => $val2)
                    {

                        $val2 = array_slice($val2, 0, 3, true);
                        $in_basket = $in_basket + count($val2);
                        $arr_show[$key1][$key2] = $val2;
                    }
                }

                //get to be filled records count
                $to_be_in_basket = 18 - $in_basket;


                //get remaining elements from most revelant filter
                if (0 != $to_be_in_basket)
                {

                    foreach ($arr_result AS $key1 => $val1)
                    {
                        if (0 == $to_be_in_basket)
                            break;
                        foreach ($val1 AS $key2 => $val2)
                        {

                            $val2 = array_slice($val2, 3, $to_be_in_basket, true);
                            $to_be_in_basket = $to_be_in_basket - count($val2);
                            $arr_show[$key1][$key2] = array_merge($arr_show[$key1][$key2], $val2);
                            break;
                        }
                    }
                }

                $rank = 1;
                foreach ($arr_show as $key => $val)
                {
                    foreach ($val as $name => $value)
                    {
                        foreach ($value as $record => $count)
                        {
                            if ($name == 'album')
                            {
                                $keyword = str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $record);
                                $albumdocs = $this->Solr->query('Title:' . $keyword, 1);
                                //$imageUrl = shell_exec('perl files/tokengen ' . $albumdocs[0]->ACdnPath . "/" . $albumdocs[0]->ASourceURL);
                                //$image = Configure::read('App.Music_Path') . preg_replace(array("/\r\n/","/\r/","/\n/"), array('','',''), $imageUrl);
                                //$imageData = "<img src='".$image."' height='40px' width='40px' />";
                            }
                            else
                            {
                                $imageData = "";
                            }
                            //if(preg_match("/^".$queryVar."/i",$record)){
                            //$records[] = $record."|".$record;

                            if (isset($_GET['ufl']) && $_GET['ufl'] == 1)
                            {
                                $widthLeft = "75px";
                                $widthRight = "300px";
                            }
                            else
                            {
                                $widthLeft = "130px";
                                $widthRight = "130px";
                            }

                            $regex = "/^$queryVar/i";

                            if (preg_match($regex, $record))
                            {
                                $str = "<div style='width:$widthLeft;font-weight:bold;'>" . (!empty($imageData) ? $imageData . "<br/>" : "") . ucfirst($name) . "</div><div style='width:$widthRight;'>" . $record . "</div>|" . $record . "|" . $rank;
                                array_unshift($records, $str);
                            }
                            else
                            {
                                $records[] = "<div style='width:$widthLeft;font-weight:bold;'>" . (!empty($imageData) ? $imageData . "<br/>" : "") . ucfirst($name) . "</div><div style='width:$widthRight;'> " . $record . "</div>|" . $record . "|" . $rank;
                            }
                            $rank++;
                            //}
                        }
                    }
                }


                //echo '<pre>'; print_r($data1); print_r($records); die;
                //$records = array_slice($records,0,20);
                break;
            case 'artist':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'album':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $keyword = str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $record);
                        $albumdocs = $this->Solr->query('Title:' . $keyword, 1);
                        //$imageUrl = shell_exec('perl files/tokengen ' . $albumdocs[0]->ACdnPath . "/" . $albumdocs[0]->ASourceURL);
                        //$image = Configure::read('App.Music_Path') . preg_replace(array("/\r\n/","/\r/","/\n/"), array('','',''), $imageUrl);
                        //$imageData = "<img src='".$image."' height='40px' width='40px' />";
                        $imageData = "";
                        if (isset($_GET['ufl']) && $_GET['ufl'] == 1)
                        {
                            $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>" . (!empty($imageData) ? $imageData . "<br/>" : "") . ucfirst($name) . "</div><div style='float:right;width:300px;text-align:left;'> " . $record . "</div>|" . $record;
                        }
                        else
                        {
                            $records[] = "<div style='float:left;width:65px;text-align:left;font-weight:bold;'>" . (!empty($imageData) ? $imageData . "<br/>" : "") . ucfirst($name) . "</div><div style='float:right;width:180px;text-align:left;'> " . $record . "</div>|" . $record;
                            //$records[] = $record;
                        }
                    }
                }
                break;
            case 'composer':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'song':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'label':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'video':
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'genre':
                //echo '<pre>'; print_r($data); 
                foreach ($data as $record => $count)
                {
                    if (stripos($record, $queryVar) !== false)
                    {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
        }
        //print_r($typeVar); print_r($records); //die;
        $this->set('type', $typeVar);
        $this->set('records', $records);
    }
}
