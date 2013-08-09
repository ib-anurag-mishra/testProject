<?php

/* File Name: homes_controller.php
  File Description: Displays the home page for each patron
  Author: Maycreate
 */

class SearchController extends AppController {

    var $name = 'Search';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Song', 'Language', 'Album', 'Session','WishlistVideo');
    var $components = array('Auth', 'Acl', 'RequestHandler', 'ValidatePatron', 'Downloads', 'PasswordHelper', 'Email', 'SuggestionSong', 'Cookie', 'Solr', 'Session');
    var $uses = array('Home', 'User', 'Featuredartist', 'Artist', 'Library', 'Download', 'Genre', 'Currentpatron', 'Page', 'Wishlist', 'Album', 'Song', 'Language', 'Searchrecord');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'autocomplete');
        /* if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform') && ($this->action != 'admin_limitsform') && ($this->action != 'admin_loginform') && ($this->action != 'admin_wishlistform') && ($this->action != 'admin_historyform') && ($this->action != 'forgot_password') && ($this->action != 'admin_aboutus') && ($this->action != 'language') && ($this->action != 'admin_language') && ($this->action != 'admin_language_activate') && ($this->action != 'admin_language_deactivate') && ($this->action != 'auto_check') && ($this->action != 'convertString')) {
          $validPatron = $this->ValidatePatron->validatepatron();
          if($validPatron == '0') {
          $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
          }
          else if($validPatron == '2') {
          $this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
          $this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
          }
          }
          $this->Cookie->name = 'baker_id';
          $this->Cookie->time = 3600; // or '1 hour'
          $this->Cookie->path = '/';
          $this->Cookie->domain = 'freegalmusic.com'; */
    }

    function index($page = 1, $facetPage = 1) {
        //echo "<br>Started at ".date("Y-m-d H:i:s");
        // reset page parameters when serach keyword changes
        if (('' == trim($_GET['q'])) || ('' == trim($_GET['type']))) {
            unset($_SESSION['SearchReq']);
        }// unset session when no params
        if ((isset($_SESSION['SearchReq'])) && ($_SESSION['SearchReq']['word'] != trim($_GET['q'])) && ($_SESSION['SearchReq']['type'] == trim($_GET['type']))) {
            unset($_SESSION['SearchReq']);
            $this->redirect(array('controller' => 'search', 'action' => 'index?q=' . $_GET['q'] . '&type=' . $_GET['type']));
        }//reset session & redirect to 1st page
        if (('' != trim($_GET['q'])) && ('' != trim($_GET['type']))) {
            $_SESSION['SearchReq']['word'] = $_GET['q'];
            $_SESSION['SearchReq']['type'] = $_GET['type'];
        }//sets values in session


        $this->layout = 'home';
        $queryVar = null;
        $check_all = null;
        $sortVar = 'ArtistText';
        $sortOrder = 'asc';

        if (isset($_GET['q'])) {
            $queryVar = html_entity_decode($_GET['q']);
        }
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer' || $_GET['type'] == 'video') ? $_GET['type'] : 'all');
        } else {
            $typeVar = 'all';
        }
        $this->set('type', $typeVar);

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $sort = (($sort == 'song' || $sort == 'album' || $sort == 'artist' || $sort == 'composer') ? $sort : 'artist');
            switch ($sort) {
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
        } else {
            $sort = 'artist';
        }

        $this->set('sort', $sort);

        if (isset($_GET['sortOrder'])) {
            $sortOrder = $_GET['sortOrder'];
            $sortOrder = (($sortOrder == 'asc' || $sortOrder == 'desc') ? $sortOrder : 'asc');
        } else {
            $sortOrder = 'asc';
        }

        $this->set('sortOrder', $sortOrder);


        if (!empty($queryVar)) {
            //Added code for log search data
            $insertArr[] = $this->searchrecords($typeVar, $queryVar);
            $this->Searchrecord->saveAll($insertArr);
            //End Added code for log search data
            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();

            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();
            $total = 0;
            $limit = 10;

            if (!isset($page) || $page < 1) {
                $page = 1;
            } else {
                $page = $page;
            }

            if (!isset($facetPage) || $facetPage < 1) {
                $facetPage = 1;
            } else {
                $facetPage = $facetPage;
            }

            $country = $this->Session->read('territory');
            //echo "<br>Search for Songs Started at ".date("Y-m-d H:i:s");
            $songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country);
            //echo "<br>Search for Songs Ended at ".date("Y-m-d H:i:s");
            //print_r($songs); die;
            $total = $this->Solr->total;
            $totalPages = ceil($total / $limit);

            if ($total != 0) {
                /* if($page > $totalPages){
                  $page = $totalPages;
                  $this->redirect();
                  } */
            }

            foreach ($songs as $key => $song) {
                $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $song->ProdID, 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                if (count($downloadsUsed) > 0) {
                    $songs[$key]->status = 'avail';
                } else {
                    $songs[$key]->status = 'not';
                }
            }

            $this->set('songs', $songs);
            // Added code for all functionality

            if (!empty($type) && !($type == 'all')) {

                switch ($typeVar) {
                    case 'album':
                        $limit = 24;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'album');
                        // echo "Group Search for Albums Started at ".time();
                        $albums = $this->Solr->facetSearch($queryVar, 'album', $facetPage, $limit);
                        // echo "Group Search for Albums Ended at ".time();
                        /* $queryArr = null;
                          $albumData = array();
                          $albumsCheck = array_keys($albums);
                          for($i=0; $i<=count($albumsCheck) -1; $i++)
                          {
                          $queryArr = $this->Solr->query('Title:"'.utf8_decode(str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'),$albumsCheck[$i])).'"', 1);
                          $albumData[] = $queryArr[0];
                          }
                          $this->set('albums', $albums);
                          $this->set('albumData',$albumData); */
                        $this->set('albumData', $albums);

                        break;

                    case 'genre':
                        $limit = 30;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'genre');
                        $genres = $this->Solr->facetSearch($queryVar, 'genre', $facetPage, $limit);
                        //print_r($genres); die;
                        $this->set('genres', $genres);
                        break;

                    case 'label':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'label');
                        $labels = $this->Solr->facetSearch($queryVar, 'label', $facetPage, $limit);
                        $this->set('labels', $labels);
                        break;

                    case 'artist':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'artist');
                        $artists = $this->Solr->facetSearch($queryVar, 'artist', $facetPage, $limit);
                        $this->set('artists', $artists);
                        break;

                    case 'composer':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'composer');
                        $composers = $this->Solr->facetSearch($queryVar, 'composer', $facetPage, $limit);
                        $this->set('composers', $composers);
                        break;
                }

                $this->set('totalFacetFound', $totalFacetCount);
                if (!empty($totalFacetCount)) {
                    $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
                } else {
                    $this->set('totalFacetPages', 0);
                }
            } else {
                
                //$albums = $this->Solr->facetSearch($queryVar, 'album', 1, 4);
                //echo "<br>Group Search for Albums Started at ".date("Y-m-d H:i:s");
                $albums = $this->Solr->facetSearch($queryVar, 'album', 1, 4);
                //echo "<br>Group Search for Albums Ended at ".date("Y-m-d H:i:s");
                // print_r($albums); die;
                $queryArr = null;
                $albumData = array();
                $albumsCheck = array_keys($albums);
                for ($i = 0; $i <= count($albumsCheck) - 1; $i++) {
                    $queryArr = $this->Solr->query('Title:"' . utf8_decode(str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $albumsCheck[$i])) . '"', 1);
                    $albumData[] = $queryArr[0];
                }
                
                //echo "<br>Group Search for Artists Started at ".date("Y-m-d H:i:s");
                $artists = $this->Solr->facetSearch($queryVar, 'artist', 1, 5);
                //echo "<br>Group Search for Artists Ended at ".date("Y-m-d H:i:s");
                //echo "<br>Group Search for Genres Started at ".date("Y-m-d H:i:s");
                $genres = $this->Solr->facetSearch($queryVar, 'genre', 1, 5);
                //echo "<br>Group Search for Genres Ended at ".date("Y-m-d H:i:s");;
                //echo "<br>Group Search for Composers Started at ".date("Y-m-d H:i:s");
                $composers = $this->Solr->facetSearch($queryVar, 'composer', 1, 5);
                //echo "<br>Group Search for Composers Ended at ".date("Y-m-d H:i:s");
                // $labels = $this->Solr->facetSearch($queryVar, 'label', 1, 5);
                //echo "<br>Group Search for Video Started at ".date("Y-m-d H:i:s");
                $videos = $this->Solr->facetSearch($queryVar, 'video', 1, 5);
                //echo "<br>Group Search for Video ended at ".date("Y-m-d H:i:s");
                // print_r($videos); die;
                $this->set('albums', $albums);
                //$this->set('albumData',$albumData);
                $this->set('albumData', $albumData);
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
    }

    function advanced_search($page = 1, $facetPage = 1) {

        // reset page parameters when serach keyword changes
        if (('' == trim($_GET['q'])) || ('' == trim($_GET['type']))) {
            unset($_SESSION['SearchReq']);
        }// unset session when no params
        if ((isset($_SESSION['SearchReq'])) && ($_SESSION['SearchReq']['word'] != trim($_GET['q'])) && ($_SESSION['SearchReq']['type'] == trim($_GET['type']))) {
            unset($_SESSION['SearchReq']);
            $this->redirect(array('controller' => 'search', 'action' => 'advanced_search?q=' . $_GET['q'] . '&type=' . $_GET['type']));
        }//reset session & redirect to 1st page
        if (('' != trim($_GET['q'])) && ('' != trim($_GET['type']))) {
            $_SESSION['SearchReq']['word'] = $_GET['q'];
            $_SESSION['SearchReq']['type'] = $_GET['type'];
        }//sets values in session


        $this->layout = 'home';
        $queryVar = null;
        $check_all = null;
        $sortVar = 'ArtistText';
        $sortOrder = 'asc';

        if (isset($_GET['q'])) {
            $queryVar = html_entity_decode($_GET['q']);
        }
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer') ? $_GET['type'] : 'all');
        } else {
            $typeVar = 'all';
        }
        $this->set('type', $typeVar);

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $sort = (($sort == 'song' || $sort == 'album' || $sort == 'artist' || $sort == 'composer') ? $sort : 'artist');
            switch ($sort) {
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
        } else {
            $sort = 'artist';
        }

        $this->set('sort', $sort);

        if (isset($_GET['sortOrder'])) {
            $sortOrder = $_GET['sortOrder'];
            $sortOrder = (($sortOrder == 'asc' || $sortOrder == 'desc') ? $sortOrder : 'asc');
        } else {
            $sortOrder = 'asc';
        }

        $this->set('sortOrder', $sortOrder);


        if (!empty($queryVar)) {
            //Added code for log search data
            $insertArr[] = $this->searchrecords($typeVar, $queryVar);
            $this->Searchrecord->saveAll($insertArr);
            //End Added code for log search data
            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();

            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();
            $total = 0;
            $limit = 10;

            if (!isset($page) || $page < 1) {
                $page = 1;
            } else {
                $page = $page;
            }

            if (!isset($facetPage) || $facetPage < 1) {
                $facetPage = 1;
            } else {
                $facetPage = $facetPage;
            }

            $country = $this->Session->read('territory');
            $songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country);
            $total = $this->Solr->total;
            $totalPages = ceil($total / $limit);

            if ($total != 0) {
                /* if($page > $totalPages){
                  $page = $totalPages;
                  $this->redirect();
                  } */
            }

            foreach ($songs as $key => $song) {
                $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $song->ProdID, 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                if (count($downloadsUsed) > 0) {
                    $songs[$key]->status = 'avail';
                } else {
                    $songs[$key]->status = 'not';
                }
            }

            $this->set('songs', $songs);
            // Added code for all functionality

            if (!empty($type) && !($type == 'all')) {

                switch ($typeVar) {
                    case 'album':
                        $limit = 24;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'album');
                        $albums = $this->Solr->groupSearch($queryVar, 'album', $facetPage, $limit);
                        /* $queryArr = null;
                          $albumData = array();
                          $albumsCheck = array_keys($albums);
                          for($i=0; $i<=count($albumsCheck) -1; $i++)
                          {
                          $queryArr = $this->Solr->query('Title:"'.utf8_decode(str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'),$albumsCheck[$i])).'"', 1);
                          $albumData[] = $queryArr[0];
                          }
                          $this->set('albums', $albums);
                          $this->set('albumData',$albumData); */
                        $this->set('albumData', $albums);

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
                if (!empty($totalFacetCount)) {
                    $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
                } else {
                    $this->set('totalFacetPages', 0);
                }
            } else {
                //$albums = $this->Solr->facetSearch($queryVar, 'album', 1, 4);
                $albums = $this->Solr->groupSearch($queryVar, 'album', 1, 4);
                $queryArr = null;
                $albumData = array();
                $albumsCheck = array_keys($albums);
                for ($i = 0; $i <= count($albumsCheck) - 1; $i++) {
                    $queryArr = $this->Solr->query('Title:"' . utf8_decode(str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $albumsCheck[$i])) . '"', 1);
                    $albumData[] = $queryArr[0];
                }

                $artists = $this->Solr->groupSearch($queryVar, 'artist', 1, 5);
                $genres = $this->Solr->groupSearch($queryVar, 'genre', 1, 5);
                $composers = $this->Solr->groupSearch($queryVar, 'composer', 1, 5);
                $labels = $this->Solr->groupSearch($queryVar, 'label', 1, 5);
                $this->set('albums', $albums);
                //$this->set('albumData',$albumData);
                $this->set('albumData', $albums);
                $this->set('artists', $artists);
                $this->set('genres', $genres);
                //print_r($genres);die;
                $this->set('composers', $composers);
                $this->set('labels', $labels);
            }
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
            $this->set('total', $total);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('facetPage', $facetPage);
        }
        $this->set('keyword', htmlspecialchars($queryVar));
    }

    function searchrecords($type, $search_text) {
        $search_text = strtolower(trim($search_text));
        $search_text = preg_replace('/\s\s+/', ' ', $search_text);
        $insertArr['search_text'] = $search_text;
        $insertArr['type'] = $type;
        $genre_id_count_array = $this->Searchrecord->find('all', array('conditions' => array('search_text' => $search_text, 'type' => $type)));
        if (count($genre_id_count_array) > 0) {
            $insertArr['count'] = $genre_id_count_array[0]['Searchrecord']['count'] + 1;
            $insertArr['id'] = $genre_id_count_array[0]['Searchrecord']['id'];
        } else {
            $insertArr['count'] = 1;
        }

        return $insertArr;
    }

    /* function autocomplete() {
      Configure::write('debug', 0);
      $this->layout = 'ajax';
      if(isset($_GET['q'])){
      $queryVar = $_GET['q'];
      }
      if(isset($_GET['type'])){
      $type = $_GET['type'];
      $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer')  ? $_GET['type'] : 'all');
      } else {
      $typeVar = 'all';
      }
      if($type!='all'){
      $data = $this->Solr->getAutoCompleteData($queryVar, $type, 10);
      }
      $records = array();

      switch($typeVar){
      case 'all':
      $records = array();
      $data1 = array();
      $data2 = array();
      $data3 = array();
      $arr_data = $arr_records = array();

      // each indiviual filter call
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'album', 18, '1');
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'artist', 18, '1');
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'composer', 18, '1');
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'genre', 18, '1');
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'label', 18, '1');
      $arr_data[]  = $this->Solr->getAutoCompleteData($queryVar, 'song', 18, '1');

      // formates array
      foreach($arr_data as $key1 => $val1){
      foreach($val1 as $key2 => $val2){
      $arr_result[$key2] = $val2;
      }
      }

      //sort ain decending order of match result count
      krsort($arr_result);

      //get 3 elements of each filter
      $arr_show = $arr_result; $in_basket = 0;
      foreach($arr_result AS $key1 => $val1){
      foreach($val1 AS $key2 => $val2){

      $val2 = array_slice($val2, 0, 3, true); $in_basket = $in_basket + count($val2);
      $arr_show[$key1][$key2] = $val2;
      }
      }

      //get to be filled records count
      $to_be_in_basket = 18 - $in_basket;


      //get remaining elements from most revelant filter
      if( 0 != $to_be_in_basket ) {

      foreach($arr_result AS $key1 => $val1){
      if( 0 == $to_be_in_basket ) break;
      foreach($val1 AS $key2 => $val2){

      $val2 = array_slice($val2, 3, $to_be_in_basket, true);
      $to_be_in_basket = $to_be_in_basket - count($val2);
      $arr_show[$key1][$key2] = array_merge($arr_show[$key1][$key2], $val2);
      break;
      }
      }
      } */


    /* echo '<pre>';
      print_r($arr_result);
      var_dump($in_basket);
      var_dump($to_be_in_basket);
      print_r($arr_show);
      exit; */

    //$data1 = $this->Solr->getAutoCompleteData($queryVar, 'artist', 10);
    /* $data2  = $this->Solr->getAutoCompleteData($queryVar, 'album', 10);
      $data3   = $this->Solr->getAutoCompleteData($queryVar, 'song', 10);
      $data4   = $this->Solr->getAutoCompleteData($queryVar, 'genre', 10); */


    //die;
    /* foreach($data1 as $record=>$count){
      if(preg_match("/^".$queryVar."/i",$record)){
      //$records[] = $record."|".$record;
      $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>Artist</div><div style='float:right;width:300px;text-align:left;'>".$record."</div>|".$record."|1";
      }
      } */
    /* foreach($data2 as $record=>$count){
      if(preg_match("/^".$queryVar."/i",$record)){
      //$records[] = $record."|".$record;
      $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>Album</div><div style='float:right;width:300px;text-align:left;'> ".$record."</div>|".$record."|2";
      }
      }
      foreach($data3 as $record=>$count){
      if(preg_match("/^".$queryVar."/i",$record)){
      //$records[] = $record."|".$record;
      $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>Track</div><div style='float:right;width:300px;text-align:left;'> ".$record."</div>|".$record."|3";
      }
      }
      foreach($data4 as $record=>$count){
      if(preg_match("/^".$queryVar."/i",$record)){
      //$records[] = $record."|".$record;
      $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>Genre</div><div style='float:right;width:300px;text-align:left;'> ".$record."</div>|".$record."|3";
      }
      } */

    /* $rank = 1;
      foreach($arr_show as $key => $val){
      foreach($val as $name => $value){
      foreach($value as $record => $count){
      //if(preg_match("/^".$queryVar."/i",$record)){
      //$records[] = $record."|".$record;
      $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>".ucfirst($name)."</div><div style='float:right;width:300px;text-align:left;'> ".$record."</div>|".$record."|".$rank;
      $rank++;
      //}
      }
      }
      }


      //echo '<pre>'; print_r($data1); print_r($records); die;

      //$records = array_slice($records,0,20);
      break;
      case 'artist':
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      case 'album':
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      case 'composer':
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      case 'song':
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      case 'label':
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      case 'genre':
      //echo '<pre>'; print_r($data);
      foreach($data as $record=>$count){
      if(stripos($record,$queryVar) !== false){
      $record = trim($record, '"');
      $record = preg_replace("/\n/",'',$record);
      $records[] = $record;
      }
      }
      break;
      }
      //print_r($typeVar); print_r($records); //die;
      $this->set('type',$typeVar);
      $this->set('records',$records);
      } */

    function autocomplete() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (isset($_GET['q'])) {
            $queryVar = $_GET['q'];
        }
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer' || $_GET['type'] == 'video') ? $_GET['type'] : 'all');
        } else {
            $typeVar = 'all';
        }
        if ($type != 'all') {
            $data = $this->Solr->getAutoCompleteData($queryVar, $type, 10);
        }
        $records = array();
        switch ($typeVar) {
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
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'label', 18, '1');
                $arr_data[] = $this->Solr->getAutoCompleteData($queryVar, 'song', 18, '1');

                // formates array
                foreach ($arr_data as $key1 => $val1) {
                    foreach ($val1 as $key2 => $val2) {
                        $arr_result[$key2] = $val2;
                    }
                }

                //sort ain decending order of match result count
                krsort($arr_result);

                //get 3 elements of each filter
                $arr_show = $arr_result;
                $in_basket = 0;
                foreach ($arr_result AS $key1 => $val1) {
                    foreach ($val1 AS $key2 => $val2) {

                        $val2 = array_slice($val2, 0, 3, true);
                        $in_basket = $in_basket + count($val2);
                        $arr_show[$key1][$key2] = $val2;
                    }
                }

                //get to be filled records count
                $to_be_in_basket = 18 - $in_basket;


                //get remaining elements from most revelant filter
                if (0 != $to_be_in_basket) {

                    foreach ($arr_result AS $key1 => $val1) {
                        if (0 == $to_be_in_basket)
                            break;
                        foreach ($val1 AS $key2 => $val2) {

                            $val2 = array_slice($val2, 3, $to_be_in_basket, true);
                            $to_be_in_basket = $to_be_in_basket - count($val2);
                            $arr_show[$key1][$key2] = array_merge($arr_show[$key1][$key2], $val2);
                            break;
                        }
                    }
                }

                $rank = 1;
                foreach ($arr_show as $key => $val) {
                    foreach ($val as $name => $value) {
                        foreach ($value as $record => $count) {
                            if($name == 'album'){
                                $keyword = str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $record);
                                $albumdocs = $this->Solr->query('Title:'.$keyword,1);
                                //$imageUrl = shell_exec('perl files/tokengen ' . $albumdocs[0]->ACdnPath . "/" . $albumdocs[0]->ASourceURL);
                                //$image = Configure::read('App.Music_Path') . preg_replace(array("/\r\n/","/\r/","/\n/"), array('','',''), $imageUrl);
                                //$imageData = "<img src='".$image."' height='40px' width='40px' />";
                            } else {
                                $imageData = "";
                            }
                            //if(preg_match("/^".$queryVar."/i",$record)){
                            //$records[] = $record."|".$record;
                            
                            if(isset($_GET['ufl']) && $_GET['ufl'] == 1){
                                $widthLeft = "75px";
                                $widthRight = "300px";
                            } else {
                                $widthLeft = "65px";
                                $widthRight = "180px";
                            }
                            
                            $regex = "/^$queryVar/i";
                            
                            if(preg_match($regex,$record)){
                                $str = "<div style='float:left;width:$widthLeft;text-align:left;font-weight:bold;'>" . (!empty($imageData)?$imageData."<br/>":"") .ucfirst($name) . "</div><div style='float:right;width:$widthRight;text-align:left;'>" . $record . "</div>|" . $record . "|" . $rank;
                                array_unshift($records, $str);
                            } else {
                                $records[] = "<div style='float:left;width:$widthLeft;text-align:left;font-weight:bold;'>" . (!empty($imageData)?$imageData."<br/>":"") .ucfirst($name) . "</div><div style='float:right;width:$widthRight;text-align:left;'> " . $record . "</div>|" . $record . "|" . $rank;
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
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'album':
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $keyword = str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $record);
                        $albumdocs = $this->Solr->query('Title:'.$keyword,1);
                        //$imageUrl = shell_exec('perl files/tokengen ' . $albumdocs[0]->ACdnPath . "/" . $albumdocs[0]->ASourceURL);
                        //$image = Configure::read('App.Music_Path') . preg_replace(array("/\r\n/","/\r/","/\n/"), array('','',''), $imageUrl);
                        //$imageData = "<img src='".$image."' height='40px' width='40px' />";
                        $imageData = "";
                        if(isset($_GET['ufl']) && $_GET['ufl'] == 1){
                            $records[] = "<div style='float:left;width:75px;text-align:left;font-weight:bold;'>" . (!empty($imageData)?$imageData."<br/>":"") .ucfirst($name) . "</div><div style='float:right;width:300px;text-align:left;'> " . $record . "</div>|" . $record;
                        } else {
                            $records[] = "<div style='float:left;width:65px;text-align:left;font-weight:bold;'>" . (!empty($imageData)?$imageData."<br/>":"") .ucfirst($name) . "</div><div style='float:right;width:180px;text-align:left;'> " . $record . "</div>|" . $record;
                            //$records[] = $record;
                        }
                    }
                }
                break;
            case 'composer':
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'song':
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'label':
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'video':
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
                        $record = trim($record, '"');
                        $record = preg_replace("/\n/", '', $record);
                        $records[] = $record;
                    }
                }
                break;
            case 'genre':
                //echo '<pre>'; print_r($data); 
                foreach ($data as $record => $count) {
                    if (stripos($record, $queryVar) !== false) {
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
