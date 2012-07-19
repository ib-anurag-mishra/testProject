<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron
   Author: Maycreate
*/
class SearchController extends AppController
{
    var $name = 'Search';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song', 'Language');
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


  function advanced_search() {
    $this->layout = 'home';
    $queryVar = null;
    if(isset($_GET['q'])){
      $queryVar = $_GET['q'];
    }
    if(isset($_GET['type'])){
      $typeVar = (($_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer') ? $_GET['type'] : 'song');
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
			
			$songs = $this->Solr->search($queryVar, $typeVar);
			$this->set('songs', $songs);
			// Added code for all functionality
			$check_all = $_GET['check_all'];
			if('true' == $check_all){
				switch($typeVar){
					case 'album':
						$from_limit = 1;
						$to_limit = 4;
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
						$to_limit = 4;
						$genres = $this->Solr->facetSearch($queryVar, 'genre', $from_limit, $to_limit);
						$this->set('genres', $genres);
						
					break;		
					case 'label':
						$from_limit = 1;
						$to_limit = 4;
						$labels = $this->Solr->facetSearch($queryVar, 'label', $from_limit, $to_limit);
						$this->set('labels', $labels);
					break;		
					case 'artist':
						$from_limit = 1;
						$to_limit = 4;
						$artists = $this->Solr->facetSearch($queryVar, 'artist', $from_limit, $to_limit);
						$this->set('artists', $artists);
					break;		
				}			
			}
			else{			
				$albums = $this->Solr->facetSearch($queryVar, 'album', 1, 4);
				$queryArr = null;
				$albumData = array();
				$albumsCheck = array_keys($albums);
				for($i=0;$i<=3;$i++)
				{
					$queryArr = $this->Solr->query('CTitle:"'.str_replace('-','\-',addslashes($albumsCheck[$i])).'"', 1);
					$albumData[] = $queryArr[0];
				}

				$artists = $this->Solr->facetSearch($queryVar, 'artist', 1, 5);
				$genres = $this->Solr->facetSearch($queryVar, 'genre', 1, 5);
				$composers = $this->Solr->facetSearch($queryVar, 'composer', 1, 5);
				$labels = $this->Solr->facetSearch($queryVar, 'label', 1, 5);
				$total = 0;

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
		}
		$this->set('keyword', $queryVar);
	}
}