<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron
   Author: Maycreate
*/
class SearchController extends AppController
{
    var $name = 'Search';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song', 'Language', 'Album');
    var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email', 'SuggestionSong','Cookie','Solr','Session');
    var $uses = array('Home','User','Featuredartist','Artist','Library','Download','Genre','Currentpatron','Page','Wishlist','Album','Song','Language' );

    /*
     Function Name : beforeFilter
     Desc : actions that needed before other functions are getting called
    */
    function beforeFilter() {
		parent::beforeFilter();
        if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform') && ($this->action != 'admin_limitsform') && ($this->action != 'admin_loginform') && ($this->action != 'admin_wishlistform') && ($this->action != 'admin_historyform') && ($this->action != 'forgot_password') && ($this->action != 'admin_aboutus') && ($this->action != 'language') && ($this->action != 'admin_language') && ($this->action != 'admin_language_activate') && ($this->action != 'admin_language_deactivate') && ($this->action != 'auto_check') && ($this->action != 'convertString')) {
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
		$this->Cookie->domain = 'freegalmusic.com';

    }


  function advanced_search($page=1) {
    $this->layout = 'home';
    $queryVar = null;
    $check_all = null;
    $sortVar = 'SongTitle';
    $sortOrder = 'asc';

    if(isset($_GET['q'])){
      $queryVar = $_GET['q'];
    }
    if(isset($_GET['type'])){
		$this->set('type', $_GET['type']);
		$type = $_GET['type'];
		$typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer')  ? $_GET['type'] : 'all');

    }

    if(isset($_GET['sort'])){
      $this->set('sort', $_GET['sort']);
      $sort = $_GET['sort'];
      $sort = (($sort == 'song' || $sort == 'album' || $sort == 'genre' || $sort == 'label' || $sort == 'artist' || $sort == 'composer')  ? $sort : 'song');

      switch($sort){
        case 'song':
        case 'album':
        case 'genre':
        case 'label':
        case 'artist':
        case 'composer':
        default:
      }
    }

    if(isset($_GET['sortOrder'])){
      $this->set('sortOrder', $_GET['sortOrder']);
      $sortOrder = $_GET['sortOrder'];
      $sortOrder = (($sortOrder=='asc' || $sortOrder=='desc')?$sortOrder:'asc');
    }

    if(!empty($queryVar)){
      $patId = $this->Session->read('patron');
      $libId = $this->Session->read('library');
      $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
      $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
      $docs = array();

			$patId = $this->Session->read('patron');
			$libId = $this->Session->read('library');
			$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
			$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
			$docs = array();
      $total = 0;
      $limit = 10;

      if(!isset($page) && $page < 1){
        $page = 1;
      } else {
        $page = $page;
      }

			$songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit);
      $total = $this->Solr->total;
      $totalPages = ceil($total/$limit);

      if($total != 0){
        /*if($page > $totalPages){
          $page = $totalPages;
          $this->redirect();
        }*/
      }

      foreach($songs as $key=>$song){
        $downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $song->ProdID,'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
				if(count($downloadsUsed) > 0){
					$songs[$key]->status = 'avail';
				} else{
					$songs[$key]->status = 'not';
				}
      }

			$this->set('songs', $songs);
			// Added code for all functionality

			if(!empty($type) && !($type == 'all')){

				switch($typeVar){
					case 'album':
						$from_limit = 1;
						$to_limit = 24;
						$albums = $this->Solr->facetSearch($queryVar, 'album', $from_limit, $to_limit);
						$queryArr = null;
						$albumData = array();
						$albumsCheck = array_keys($albums);
						for($i=0; $i<=count($albumsCheck) -1; $i++)
						{
						  $queryArr = $this->Solr->query('CTitle:"'.str_replace('-','\-',addslashes($albumsCheck[$i])).'"', 1);
						  $albumData[] = $queryArr[0];
						}
						$this->set('albums', $albums);
						$this->set('albumData',$albumData);

					break;
					case 'genre':
						$from_limit = 1;
						$to_limit = 32;
						$genres = $this->Solr->facetSearch($queryVar, 'genre', $from_limit, $to_limit);
						$this->set('genres', $genres);

					break;
					case 'label':
						$from_limit = 1;
						$to_limit = 16;
						$labels = $this->Solr->facetSearch($queryVar, 'label', $from_limit, $to_limit);
						$this->set('labels', $labels);
					break;
					case 'artist':
						$from_limit = 1;
						$to_limit = 16;
						$artists = $this->Solr->facetSearch($queryVar, 'artist', $from_limit, $to_limit);
						$this->set('artists', $artists);
					break;
					case 'composer':
						$from_limit = 1;
						$to_limit = 16;
						$composers = $this->Solr->facetSearch($queryVar, 'composer', $from_limit, $to_limit);
						$this->set('composers', $composers);
					break;
				}
			}
			else{
				$albums = $this->Solr->facetSearch($queryVar, 'album', 1, 4);
				$queryArr = null;
				$albumData = array();
				$albumsCheck = array_keys($albums);
				for($i=0;$i<=count($albumsCheck) -1;$i++)
				{
					$queryArr = $this->Solr->query('CTitle:"'.str_replace('-','\-',addslashes($albumsCheck[$i])).'"', 1);
					$albumData[] = $queryArr[0];
				}

				$artists = $this->Solr->facetSearch($queryVar, 'artist', 1, 5);
				$genres = $this->Solr->facetSearch($queryVar, 'genre', 1, 5);
				$composers = $this->Solr->facetSearch($queryVar, 'composer', 1, 5);
				$labels = $this->Solr->facetSearch($queryVar, 'label', 1, 5);


				$this->set('libraryDownload',$libraryDownload);
				$this->set('patronDownload',$patronDownload);
				$this->set('albums', $albums);
				$this->set('albumData',$albumData);
				$this->set('artists', $artists);
				$this->set('genres', $genres);
				$this->set('composers', $composers);
				$this->set('labels', $labels);
			}
			$this->set('total', $total);
      $this->set('totalPages', $totalPages);
      $this->set('currentPage', $page);
    }
		$this->set('keyword', $queryVar);
	}
}