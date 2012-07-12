<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron
   Author: Maycreate
*/
class SearchController extends AppController
{
    var $name = 'Search';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song', 'Language');
    var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email', 'SuggestionSong','Cookie');
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


  function advanced_search($arg1, $startFrom = null, $recordCount = null) {

  $this->layout = 'home';
  $docs = array();

  $queryVar = $_GET['q'];

  App::import("Vendor","solr",array('file' => "Apache".DS."Solr".DS."Service.php"));

  $solr = new Apache_Solr_Service( '192.168.2.178', '8080', '/solr/freegalmusic/' );

	if ( ! $solr->ping() ) {
		$error = 'Solr service not responding.';
    echo $error;
	}

  $offset = 0;
  $limit = 10;

  $query = 'SongTitle: '.$queryVar.'*';

  $response = $solr->search( $query, $offset, $limit );
  if ( $response->getHttpStatus() == 200 ) {
     if ( $response->response->numFound > 0 ) {
      foreach ( $response->response->docs as $doc ) {
       $docs[] = $doc;
      }
     }
    }
    else {
     $error = $response->getHttpStatusMessage();
     echo $error;
    }
    print_r($docs);
    die;
    $this->set('results', $docs);
  }
}