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
    $docs = array();
    $queryVar = $_GET['q'];
    $typeVar = (($_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer') ? $_GET['type'] : 'song');

    $songs = $this->Solr->search($queryVar, $typeVar);
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

    $this->set('songs', $songs);
    $this->set('albums', $albums);
    $this->set('albumData',$albumData);
    $this->set('artists', $artists);
    $this->set('genres', $genres);
    $this->set('composers', $composers);
    $this->set('labels', $labels);

  }
}