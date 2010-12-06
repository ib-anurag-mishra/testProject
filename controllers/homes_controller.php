<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/
class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song');
    var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email', 'SuggestionSong');
    var $uses = array('Home','User','Featuredartist','Artist','Library','Download','Genre','Currentpatron','Page','Wishlist','Album','Song' );
    
    /*
     Function Name : beforeFilter
     Desc : actions that needed before other functions are getting called
    */
    function beforeFilter() {
	parent::beforeFilter();
        if(($this->action != 'aboutus') && ($this->action != 'admin_aboutusform') && ($this->action != 'admin_termsform') && ($this->action != 'admin_limitsform') && ($this->action != 'admin_loginform') && ($this->action != 'admin_wishlistform') && ($this->action != 'forgot_password') && ($this->action != 'admin_aboutus')) {
            $validPatron = $this->ValidatePatron->validatepatron();
			if($validPatron == '0') {
				//$this->Session->destroy();
				//$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
			}
			else if($validPatron == '2') {
				//$this->Session->destroy();
				$this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));			
			}			
        }
    }
    
    /*
     Function Name : index
     Desc : actions that is invoked when the user comes to the homes controller
    */
    function index() {
		$this->Song->recursive = 2;
        $this->Song->Behaviors->attach('Containable');
		$songDetails = $this->SuggestionSong->readSuggestionSongsXML();
		$this->set('songs',$songDetails);
        $this->Album->recursive = 2;
        $upcoming = $this->Album->find('all', array(
							    'conditions' => array(
								'Country.SalesDate >' => date('Y-m-d')
							    ),
							    'fields' => array(
									'Album.AlbumTitle',
									'Album.ArtistText'
							    ),
								'contain' => array(
									'Country' => array(
										'fields' => array(
											'Country.SalesDate'								
										)
									),
								),'cache' => 'yes'
							)
						);
						
        $this->set('upcoming', $upcoming);
		$country = $this->Session->read('territory');
		$this->Song->recursive = 2;
		if (($artists = Cache::read("artist".$country)) === false) {		
			$artist = $this->Song->find('all',array(
								'conditions' =>
									array('and' =>
										array(
											array('ArtistText LIKE' => 'A%'),
											array('Country.Territory' => $country),
											array('DownloadStatus' => 1),
											array('TrackBundleCount' => 0)
										)
									),
								'fields' => array(
										'Song.ArtistText','Song.DownloadStatus',
										),
								'contain' => array(
										'Country' => array(
												'fields' => array(
													'Country.Territory'								
												)
											),
									),	
								'order' => 'Song.ArtistText',
								'group' => 'Song.ArtistText',
							));
			Cache::write("artist".$country, $artist);
		}
		$artist = Cache::read("artist".$country);						
        $this->set('distinctArtists', $artist);
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
    
    /*
     Function Name : autoComplete
     Desc : actions that is needed for auto-completeing the search
    */
    function autoComplete() {
		Configure::write('debug', 0);
        $this->Album->recursive = 2;
		$country = $this->Session->read('territory');		
        $albumResults = $this->Album->find('all', array(
								'conditions'=>array('Album.AlbumTitle LIKE'=>$_GET['q'].'%',
													'Album.DownloadStatus' => 1,
													'Album.TrackBundleCount' => 0,
													'Country.Territory' => $country						
							    ),
								'contain' => array(
								'Country' => array(
									'fields' => array(
										'Country.Territory'								
										)
									)),
							    'fields' => array('AlbumTitle'), 
							    'group' => array('AlbumTitle',),
							    'limit' => '6'));
		$this->set('albumResults', $albumResults);
		$this->Song->recursive = 2;
		$artistResults = $this->Song->find('all', array(
								'conditions'=>array('Song.ArtistText LIKE'=>$_GET['q'].'%',
								'Song.DownloadStatus' => 1,
								'Song.TrackBundleCount' => 0,
								'Country.Territory' => $country),
								'fields' => array('ArtistText'),
								'contain' => array(
								'Country' => array(
									'fields' => array(
										'Country.Territory'								
										)
									)),								
								'group' => array('ArtistText',),
								'limit' => '6'));
		$this->set('artistResults', $artistResults);
        $this->Song->recursive = 2;
        $songResults = $this->Song->find('all', array(
							'conditions'=>array('Song.SongTitle LIKE'=>$_GET['q'].'%',
												'Song.DownloadStatus' => 1,
												'Song.TrackBundleCount' => 0,
												'Country.Territory' => $country
												),
							'contain' => array(
							'Country' => array(
								'fields' => array(
									'Country.Territory'								
									)
								)),												
							'fields' => array('SongTitle'), 
							'group' => array('SongTitle',),
							'limit' => '6'));
		$this->set('songResults', $songResults);
        $this->layout = 'ajax';
    }
    
    /*
     Function Name : artistSearch
     Desc : actions that is needed for auto-completeing the search
    */
    function artistSearch(){
		$country = $this->Session->read('territory');
		$this->Song->recursive = 2;
		$search = $_POST['search'];
		if($search == 'special'){
			$cond = array("ArtistText REGEXP '^[^A-Za-z]'");
		}else{
			$cond = array('ArtistText LIKE' => $search.'%');
		}		
		$artist = $this->Song->find('all',array(
							'conditions' =>
								array('and' =>
									array(
										$cond,
										array('Country.Territory' => $country),
										array('DownloadStatus' => 1),
										array('TrackBundleCount' => 0)
									)
								),
							'fields' => array(
									'Song.ArtistText','Song.DownloadStatus',
									),
							'contain' => array(
									'Country' => array(
											'fields' => array(
												'Country.Territory'								
											)
										),
								),
							'order' => 'Song.ArtistText',
							'group' => 'Song.ArtistText'
						));
	
		//$this->Song->recursive = -1;
		$this->set('distinctArtists', $artist);  	
    }
    
    /*
     Function Name : search
     Desc : actions that is needed for advanced search
    */
    function search(){
		$country = $this->Session->read('territory');
	//	$country = "'".$country."'";
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');        
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        if($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        }
        else {
            $cond = "";
        }
		if((isset($_REQUEST['artist']) && $_REQUEST['artist']!= '') || (isset($_REQUEST['composer']) && $_REQUEST['composer'] != '') || (isset($_REQUEST['song']) && $_REQUEST['song'] != '') || (isset($_REQUEST['album']) && $_REQUEST['album'] != '') || (isset($_REQUEST['genre_id']) &&  $_REQUEST['genre_id'] != '') || (isset($this->data['Home']['artist']) && $this->data['Home']['artist']!= '') || (isset($this->data['Home']['composer']) && $this->data['Home']['composer'] != '') || (isset($this->data['Home']['song']) && $this->data['Home']['song'] != '') || (isset($this->data['Home']['album']) && $this->data['Home']['album'] != '') || (isset($this->data['Home']['genre_id']) &&  $this->data['Home']['genre_id'] != '' || isset($_REQUEST['search']) && $_REQUEST['search'] != '')){
			if((isset($_REQUEST['match']) && $_REQUEST['match'] != '') || (isset($this->data['Home']['Match']) && $this->data['Home']['Match'] != '')) {
				if(isset($_REQUEST['match']) && $_REQUEST['match'] != '') {
					 if($_REQUEST['match'] == 'All') {			 
						$condition = "and";
						$preCondition1 = array('Song.DownloadStatus' => 1);
						$preCondition2 = array('Song.TrackBundleCount' => 0);
						$preCondition3 = array('Country.Territory' => $country);
						$sphinxCheckCondition = "&";
						
					}
					 else {
						$condition = "or";
						$preCondition1 =  "";
						$preCondition2 = "";
						$preCondition3 = "";
						$sphinxCheckCondition = "|";
					}
					$artist =  $_REQUEST['artist'];
					$composer =  $_REQUEST['composer'];
					$song =  $_REQUEST['song'];
					$album =  $_REQUEST['album'];
					$genre =  $_REQUEST['genre_id'];
				}
				if(isset($this->data['Home']['Match']) && $this->data['Home']['Match'] != '') {
					if($this->data['Home']['Match'] == 'All') {
						$condition = "and";
						$preCondition1 = array('Song.DownloadStatus' => 1);
						$preCondition2 = array('Song.TrackBundleCount' => 0);
						$preCondition3 = array('Country.Territory' => $country);
						$sphinxCheckCondition = "&";
					}
					else {
						$condition = "or";
						$preCondition1 =  "";
						$preCondition2 = "";
						$preCondition3 = "";
						$sphinxCheckCondition = "|";
					}
					$artist =  $this->data['Home']['artist'];
					$composer = $this->data['Home']['composer'];
					$song =  $this->data['Home']['song'];
					$album =  $this->data['Home']['album'];
					$genre =  $this->data['Home']['genre_id'];
				}            
				if($artist != '') {
					$artistSearch = array('match(Song.ArtistText) against ("+'.$artist.'*" in boolean mode)');
					$sphinxArtistSearch = "@ArtistText ^".$artist."$ ".$sphinxCheckCondition." ";
				}
				else {
					$artistSearch = '';
					$sphinxArtistSearch = '';
				}
				if($composer != '') {
					$composerSearch = array('match(Participant.Name) against ("+'.$composer.'*" in boolean mode) and Participant.role="Composer"');    
					$this->set('composer', $composer);
					$preCondition4 = array('Participant.Role' => 'Composer'); 
					$sphinxComposerSearch = "@Name ^".$composer."$ ".$sphinxCheckCondition." @role Composer ".$sphinxCheckCondition." ";
				}
				else {
					$composerSearch = '';
					$preCondition4 = "";
					$sphinxComposerSearch = '';
				}
				if($song != '') {
					$songSearch = array('match(Song.SongTitle) against ("+'.$song.'*" in boolean mode)');
					$sphinxSongSearch = "@SongTitle ^".$song."$ ".$sphinxCheckCondition." ";
				}
				else {
					$songSearch = '';
					$sphinxSongSearch = '';
				}
				if($album != '') {
					$albumSearch = array('match(Song.Title) against ("+'.$album.'*" in boolean mode)');
					$sphinxAlbumSearch = "@Title ^".$album."$ ".$sphinxCheckCondition." ";
				}
				else {
					$albumSearch = '';
					$sphinxAlbumSearch = '';
				}
				if($genre != '') {
					$genreSearch = array('match(Genre.Genre) against ("+'.$genre.'*" in boolean mode)'); 
					$sphinxGenreSearch = "@Genre ".$genre." ".$sphinxCheckCondition." ";					
				}
				else {
					$genreSearch = '';
					$sphinxGenreSearch = '';
				}
				
				$sphinxTempCondition = $sphinxArtistSearch."".$sphinxComposerSearch."".$sphinxSongSearch."".$sphinxAlbumSearch."".$sphinxGenreSearch;
				$sphinxFinalCondition = substr($sphinxTempCondition, 0, -2);
				
				App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
				$sphinx = array('matchMode' => SPH_MATCH_EXTENDED);
				$results = $this->Song->find('all', array('search' =>  $sphinxFinalCondition, 'limit' =>10, 'recursive' => -1, 'sphinx' => $sphinx));
				$this->set('searchResults', $results);
				print_r($results);
				$searchResults = $this->paginate('Song');
				print_r($searchResults);
				exit();
				
				$this->set('searchKey','match=All&artist='.urlencode($artist).'&composer='.urlencode($composer).'&song='.urlencode($song).'&album='.$album.'&genre_id='.$genre);
				/* if($composer == '') {
					$this->Song->unbindModel(array('hasOne' => array('Participant')));
				} */
				
				
				//$this->Song->Behaviors->attach('Containable');
				$pagination = array('Song' => array('conditions' =>
						array('and' =>
								array(
									array('Song.TrackBundleCount' => 0),
									array('Song.DownloadStatus' => 1),
									array('Country.Territory' => $country),
									$cond
									)
										),
										'fields' => array(
														'Song.ProdID',
														'Song.Title',
														'Song.ArtistText',
														'Song.ReferenceID',
														'Song.DownloadStatus',
														'Song.SongTitle',
														'Song.Artist',
														'Song.Advisory',
													),
										'contain' => array(
										'Participant' => array(
											'fields' => array(
													'Participant.Name'                                                   
													)
											),
										'Genre' => array(
												'fields' => array(
														'Genre.Genre'                                                   
														)
												),
										'Country' => array(
												'fields' => array(
														'Country.Territory',
														'Country.SalesDate'
														)
												),									
										'Sample_Files' => array(
												'fields' => array(
													'Sample_Files.CdnPath' ,
													'Sample_Files.SaveAsName'                                                   
														),
											),
										'Full_Files' => array(
												'fields' => array(
													'Full_Files.CdnPath' ,
													'Full_Files.SaveAsName'                                                   
														),
											)										
										 )
									));
				/* $this->Song->recursive = 2;
				if($composer == '') {
					$this->Song->unbindModel(array('hasOne' => array('Participant')));
				} */
				
				$pagination['Song']['sphinx']['matchMode'] = SPH_MATCH_EXTENDED; 
				$pagination['Song']['search'] = 'A R Rahman'; 
				$this->paginate = $pagination; 
				$songs = $this->paginate();

				$searchResults = $this->paginate('Song');
				$this->set('searchResults', $searchResults);
			}
			else {
				$searchKey = '';      
				if(isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
					$searchKey = $_REQUEST['search'];
				}
				if($searchKey == '') {
					$searchKey = $this->data['Home']['search'];
				}
				$searchText = $searchKey;
				$searchKey = '"'.addslashes($searchKey).'"';
				$this->set('searchKey','search='.urlencode($searchText));
				if(!isset($_REQUEST['composer'])) {
					$this->Song->unbindModel(array('hasOne' => array('Participant')));
				}			
				$this->Song->Behaviors->attach('Containable');
				$this -> paginate = array('conditions' =>
									array(	'and' =>
							array(
													array('Song.DownloadStatus' => 1),
													array('Song.TrackBundleCount' => 0),
													array('Country.Territory' => $country),$cond
												),
						'or' =>
													array(
															array("match(Song.ArtistText) against ('".$searchKey."' in boolean mode)"),
															array("match(Song.Title) against ('".$searchKey."' in boolean mode)"),
															array("match(Song.SongTitle) against ('".$searchKey."' in boolean mode)")
														),"1 = 1 GROUP BY Song.ProdID"
											),
										'fields' => array(
														'Song.ProdID',
														'Song.Title',
														'Song.ArtistText',
														'Song.ReferenceID',
														'Song.DownloadStatus',
														'Song.SongTitle',
														'Song.Artist',
														'Song.Advisory',
														),
										'contain' => array(
										'Participant' => array(
											'fields' => array(
													'Participant.Name'                                                   
													)
										),
										'Genre' => array(
												'fields' => array(
														'Genre.Genre'                                                   
														)
												),
										'Country' => array(
												'fields' => array(
														'Country.Territory',
														'Country.SalesDate'
														)
												),
										'Sample_Files' => array(
												'fields' => array(
														'Sample_Files.CdnPath',
														'Sample_Files.SaveAsName'
														),
												),
										'Full_Files' => array(
												'fields' => array(
														'Full_Files.CdnPath',
														'Full_Files.SaveAsName'
														),
												)  											
										), 'cache' => 'yes'
									);
				$this->Song->recursive = 2;
				if(!isset($_REQUEST['composer'])) {
					$this->Song->unbindModel(array('hasOne' => array('Participant')));
				}				
				$searchResults = $this->paginate('Song');
				$this->set('searchResults', $searchResults);
			}
		} else {
			$this->set('searchResults', array());
		}
        $this->layout = 'home';
    }
    
    /*
     Function Name : userDownload
     Desc : actions that is used for updating user download
    */
    function userDownload() {
        Configure::write('debug', 0);
        $this->layout = false;
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $prodId = $_REQUEST['prodId'];
		$downloadsDetail = array();
		$wk = date('W')-1;
        $startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        if($libraryDownload != '1' || $patronDownload != '1') {
            echo "error";
            exit;
        }
        $trackDetails = $this->Song->getdownloaddata($prodId);        
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;	
        $insertArr['ProdID'] = $prodId;     
        $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
        $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
        if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'referral_url';
        }
        elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative';
		}
        elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_https';
		}		
		elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_wo_pin';  
		}
		elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2';  
		}
		elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'sip';   
        }
		elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_wo_pin';  
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var';  
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';  
		}
		elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';  
		}		
		elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'ezproxy';  
		}
        else{
			$insertArr['email'] = $this->Session->read('Auth.User.email');
			$insertArr['user_login_type'] = 'user_account';   
         }
		$insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];	
		$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
        if($this->Download->save($insertArr)){
			$this->Library->setDataSource('master');
			$sql = "UPDATE `libraries` SET library_current_downloads=library_current_downloads+1,library_total_downloads=library_total_downloads+1,library_available_downloads=library_available_downloads-1 Where id=".$libId; 
			$this->Library->query($sql);
			$this->Library->setDataSource('default');
		}
        $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
		$this->Download->recursive = -1;
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        echo $downloadsUsed;
        exit;
    }
    
    /*
     Function Name : advance_search
     Desc : actions used for showing advanced search form
    */
	function advance_search() {
        $this->layout = 'home';
		$country = $this->Session->read('territory');
		$this->Genre->Behaviors->attach('Containable');
		$this->Genre->recursive = 2;
		if (($genre = Cache::read("genre".$country)) === false) {
			$genreAll = $this->Genre->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $country)
								)
							),
						'fields' => array(
								'Genre.Genre'
								),
						'contain' => array(
							'Country' => array(
									'fields' => array(
											'Country.Territory'								
										)
									),
						),'group' => 'Genre.Genre'
					));
			Cache::write("genre".$country, $genreAll);
		}
		$genreAll = Cache::read("genre".$country);
		$resultArr = array();
        foreach($genreAll as $genre) {
            $resultArr[$genre['Genre']['Genre']] = $genre['Genre']['Genre'];
        }
        $this->set('genres',$resultArr);
    } 

    /*
     Function Name : checkPatron
     Desc : actions used for validating patron access
    */
    function checkPatron() {
		Configure::write('debug', 0);
		$this->layout = false;
		$libid = $_REQUEST['libid'];       
		$patronid = $_REQUEST['patronid'];
		$currentPatron = $this->Currentpatron->find('all',array('conditions' => array('libid' => $libid,'patronid' => $patronid)));        
		if(count($currentPatron) > 0) {
			  $updateArr = array();
			  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
			  $updateArr['session_id'] = session_id();
			  $this->Currentpatron->save($updateArr);
		}
		echo "Success";
		exit;
    }
    
    /*
     Function Name : approvePatron
     Desc : actions used for approve terms access
    */
    function approvePatron() {
		Configure::write('debug', 0);
		$this->layout = false;
		$libid = $_REQUEST['libid'];       
		$patronid = $_REQUEST['patronid'];
		$currentPatron = $this->Currentpatron->find('all',array('conditions' => array('libid' => $libid,'patronid' => $patronid)));        
		if(count($currentPatron) > 0){
			  $updateArr = array();
			  $updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
			  $updateArr['is_approved'] = 'yes';          
			  $this->Currentpatron->save($updateArr);
			  $this->Session->write('approved', 'yes');
		}
		echo "Success";
		exit;
    }
    
    /*
     Function Name : admin_aboutusform
     Desc : actions used for admin about us form
    */
    function admin_aboutusform() {
		if(isset($this->data)) {
			if($this->data['Home']['id'] != "") {
				$this->Page->id = $this->data['Home']['id'];
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			else {
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
		}
		$this -> set( 'formAction', 'admin_aboutusform');
		$this -> set( 'formHeader', 'Manage About Us Page Content' );
		$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus')));
		if(count($getPageData) != 0) {
			$getData['Home']['id'] = $getPageData[0]['Page']['id'];
			$getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
			$this -> set( 'getData', $getData );
		}
		else {
			$arr = array();
			$this->set('getData',$arr);
		}
		$this->layout = 'admin';
    }
    
    /*
     Function Name : admin_termsform
     Desc : actions used for admin terms form
    */
    function admin_termsform() {
		if(isset($this->data)) {
			if($this->data['Home']['id'] != "") {
				$this->Page->id = $this->data['Home']['id'];
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			else {
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
		}
		$this -> set( 'formAction', 'admin_termsform');
		$this -> set( 'formHeader', 'Manage Terms & Condition Page Content' );
		$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms')));
		if(count($getPageData) != 0) {
			$getData['Home']['id'] = $getPageData[0]['Page']['id'];
			$getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
			$this -> set( 'getData', $getData );
		}
		else {
			$arr = array();
			$this->set('getData',$arr);
		}
		$this->layout = 'admin';
    }

	/*
     Function Name : admin_loginform
     Desc : actions used for admin login form
    */
    function admin_loginform() {
		if(isset($this->data)) {
			if($this->data['Home']['id'] != "") {
				$this->Page->id = $this->data['Home']['id'];
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			else {
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
		}
		$this -> set( 'formAction', 'admin_loginform');
		$this -> set( 'formHeader', 'Manage Login Page Text' );
		$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'login')));
		if(count($getPageData) != 0) {
			$getData['Home']['id'] = $getPageData[0]['Page']['id'];
			$getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
			$this -> set( 'getData', $getData );
		}
		else {
			$arr = array();
			$this->set('getData',$arr);
		}
		$this->layout = 'admin';
    }

	/*
     Function Name : admin_wishlistform
     Desc : actions used for admin wishlist form
    */
    function admin_wishlistform() {
		if(isset($this->data)) {
			if($this->data['Home']['id'] != "") {
				$this->Page->id = $this->data['Home']['id'];
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			else {
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
		}
		$this -> set( 'formAction', 'admin_wishlistform');
		$this -> set( 'formHeader', 'Manage Wishlist Page Text' );
		$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist')));
		if(count($getPageData) != 0) {
			$getData['Home']['id'] = $getPageData[0]['Page']['id'];
			$getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
			$this -> set( 'getData', $getData );
		}
		else {
			$arr = array();
			$this->set('getData',$arr);
		}
		$this->layout = 'admin';
    }


    /*
     Function Name : admin_limitsform
     Desc : actions used for admin limits form
    */
    function admin_limitsform(){
		if(isset($this->data)) {
			if($this->data['Home']['id'] != "") {
				$this->Page->id = $this->data['Home']['id'];
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
				  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			else {
				$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
					$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
					$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
		}
		$this -> set( 'formAction', 'admin_limitsform');
		$this -> set( 'formHeader', 'Manage Download Limits Page Content' );
		$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits')));
		if(count($getPageData) != 0) {
			$getData['Home']['id'] = $getPageData[0]['Page']['id'];
			$getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
			$this -> set( 'getData', $getData );
		}
		else {
			$arr = array();
			$this->set('getData',$arr);
		}
		$this->layout = 'admin';
    }
    
    /*
     Function Name : aboutus
     Desc : actions used for User end checking for cookie and javascript enable
    */
    function aboutus() {
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
			if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
				$url = $this->Session->read('referral_url');
			}
			elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
				$url = $this->webroot.'users/ilogin';
			}
			elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){            
				$url = $this->webroot.'users/inhlogin';
			}
			elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
				$url = $this->webroot.'users/inlogin';
			}
			elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
				$url = $this->webroot.'users/indlogin';
			}		
			elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){            
				$url = $this->webroot.'users/slogin';  
			}
			elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){            
				$url = $this->webroot.'users/snlogin';
			}
			elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){            
				$url = $this->webroot.'users/sdlogin';
			}
			elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){            
				$url = $this->webroot.'users/sndlogin';
			}			
			elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){            
				$url = $this->webroot.'users/sso';
			}			
			else {
			   $url = $this->webroot.'users/login';
			}
			$patronId = $this->Session->read('patron');
			$libraryId = $this->Session->read('library');
			$patronDetails = $this->Currentpatron->find('all',array('conditions' => array('patronid' => $patronId,'libid' => $libraryId)));
			if(count($patronDetails) > 0) {
			$updateTime = date( "Y-m-d H:i:s", time()-60 );
			$this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];
			$this->Currentpatron->saveField('modified',$updateTime, false);
			}
			$this->Session->destroy();
			$this -> Session -> setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='".$url."'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
		}
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
			$this->Session->destroy();
			$this -> Session -> setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
		}
		$this->layout = 'home';
    }
	
/*
 Function Name : aboutus
 Desc : actions used for Admin end checking for cookie and javascript enable
*/	
    function admin_aboutus() {
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
			$url = $this->webroot.'admin/users/login';
			$this->Session->destroy();
			$this -> Session -> setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='".$url."'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
		}
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
			$this->Session->destroy();
			$this -> Session -> setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
		}
		$this->layout = 'admin';
    }
        
    /*
     Function Name : terms
     Desc : actions used for terms page
    */
    function terms(){
		$this->layout = 'home';
    }
    
    /*
     Function Name : limits
     Desc : actions used for limits page
    */
    function limits() {
        $this->layout = 'home';
    }
    
    /*
     Function Name : check_email
     Desc : check for a valid email
    */
    function check_email($email) {
        $email_regexp = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
        return eregi($email_regexp, $email);
    }
    
    /*
     Function Name : _sendForgotPasswordMail
     Desc : email function for forgot password
    */
    function _sendForgotPasswordMail($id, $password) {
        Configure::write('debug', 0);
        $this->Email->template = 'email/forgotPasswordEmail';
        $this->User->recursive = -1;
        $Patron = $this->User->read(null,$id);
        $this->set('Patron', $Patron);
        $this->set('password', $password);
        $this->Email->to = $Patron['User']['email'];
		$this->Email->from = Configure::read('App.adminEmail');
        $this->Email->fromName = Configure::read('App.fromName');
        $this->Email->subject = 'FreegalMusic - New Password information';
        $this->Email->smtpHostNames = Configure::read('App.SMTP');
		$this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
		$this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
		$this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
        $result = $this->Email->send(); 
    }
    
    /*
     Function Name : forgot_password
     Desc : To send mail to patrons with new password
    */
    function forgot_password() {
        $this->layout = 'login';
        $errorMsg ='';
        if($this->data){
            $email = $this->data['Home']['email'];
            if($email == ''){
                $errorMsg = "Please provide your email address.";
            }
            elseif(!($this->check_email($email))){
                $errorMsg = "This is not a valid email.";
            }
            else{
                $email_exists = $this->User->find('all',array('conditions' => array('email' => $email, 'type_id' => '5')));               
                if(count($email_exists) == 0){
                    $errorMsg = "This is not a valid patron email.";    
                }                
            }            
            if($errorMsg != ''){                
                $this->Session->setFlash($errorMsg);
                $this->redirect($this->webroot.'homes/forgot_password');
            }            
            else{
                $temp_password = $this->PasswordHelper->generatePassword(8);
                $this->User->id = $email_exists[0]['User']['id'];                
                $this->data['User']['email'] = $email;
                $this->data['User']['type_id'] = '5';
                $this->data['User']['password'] = Security::hash(Configure::read('Security.salt').$temp_password);               
                $this->User->set($this->data['User']);
                if($this->User->save()){
                    $this->_sendForgotPasswordMail($this->User->id, $temp_password);
                    $this->Session->setFlash("An email with your new password has been sent to your email account.");
                }
                $this->redirect($this->webroot.'homes/forgot_password');
            }            
        }        
    }   
    
    /*
     Function Name : addToWishlist
     Desc : To let the patron add songs to wishlist
    */
    function addToWishlist(){
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
		//check library download
		$libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);
		//check patron download
		$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);
        $this->Library->recursive = -1;
        $libraryDetails = $this->Library->find('all',array('conditions' => array('Library.id' => $libraryId),'fields' => 'library_user_download_limit'));
        //get patron limit per week
        $patronLimit = $libraryDetails[0]['Library']['library_user_download_limit'];        
        $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
        //get no of downloads for this week
        $wishlistCount =  $this->Wishlist->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        if($wishlistCount >= $patronLimit && $libraryDownload != '1' && $patronDownload != '0') {            
            echo "error";
            exit;
        }
        else {
            $prodId = $_REQUEST['prodId'];
			$downloadsDetail = array();
			$wk = date('W')-1;
			$startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
			$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
            //get song details
            $trackDetails = $this->Song->getdownloaddata($prodId);
            $insertArr = Array();
            $insertArr['library_id'] = $libraryId;
            $insertArr['patron_id'] = $patronId;
            $insertArr['ProdID'] = $prodId;
            $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
            $insertArr['album'] = $trackDetails['0']['Song']['Title'];
            $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
            $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
            $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];            
            //insert into wishlist table
            $this->Wishlist->save($insertArr);
            //update the libraries table
			$this->Library->setDataSource('master');
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads-1 Where id=".$libraryId;
            $this->Library->query($sql);
			$this->Library->setDataSource('default');
            echo "Success";
            exit;
        }
    }
    
    /*
     Function Name : my_wishlist
     Desc : To show songs present in wishlist
    */
    function my_wishlist() {        
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');        
        $libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);		
		$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
        $wishlistResults = Array();
        $wishlistResults =  $this->Wishlist->find('all',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        $this->set('wishlistResults',$wishlistResults);
    }

    /*
     Function Name : my_history
     Desc : To show songs user downloaded in last 2 weeks
    */
    function my_history() {        
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
		$wk = date('W')-1;
        $startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
        $downloadResults = Array();
        $downloadResults =  $this->Download->find('all',array('group' => 'Download.id','conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'history < 2','created BETWEEN ? AND ?' => array($startDate, $endDate))));
		$this->set('downloadResults',$downloadResults);
    }
    
    /*
     Function Name : removeWishlistSong
     Desc : For removing a song from wishlist page
    */
    function removeWishlistSong() {
        $deleteSongId = $this->params['named']['id'];
        $libraryId = $this->Session->read('library');
        if($this->Wishlist->delete($deleteSongId)) {
			$this->Library->setDataSource('master');
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads+1 Where id=".$libraryId;
            $this->Library->query($sql);
			$this->Library->setDataSource('default');
            $this->Session->setFlash('Data deleted successfully!');
            $this->redirect('my_wishlist');
        }
		else {
			$this->Session->setFlash('Error occured while deleteting the record');
			$this->redirect('my_wishlist');
		}
    }
   
    /*
     Function Name : wishlistDownload
     Desc : For downloading a song in wishlist page
    */
    function wishlistDownload() {
        Configure::write('debug', 0);
        $this->layout = false;
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
		$prodId = $_REQUEST['prodId'];
		$downloadsDetail = array();
		$wk = date('W')-1;
		$startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
		$endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
		
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);		
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        //check for download availability
        if($libraryDownload != '1' || $patronDownload != '1'){
            echo "error";
            exit;
        }
        $id = $_REQUEST['id'];
        //get details for this song
        $trackDetails = $this->Song->getdownloaddata($prodId);        
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;     
        $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
        $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
        if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'referral_url';
        }
        elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative';
		}
        elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_https';
		}		
		elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_wo_pin';  
		}
		elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2';  
		}
		elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'sip';   
        }
		elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_wo_pin';  
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var';  
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';  
		}
		elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';  
		}		
		elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'ezproxy';  
		}
        else{
			$insertArr['email'] = $this->Session->read('Auth.User.email');
			$insertArr['user_login_type'] = 'user_account';   
        }
		$insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];	
		$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];		
        //save to downloads table
        if($this->Download->save($insertArr)){
        //update library table
			$this->Library->setDataSource('master');
			$sql = "UPDATE `libraries` SET library_current_downloads=library_current_downloads+1,library_total_downloads=library_total_downloads+1 Where id=".$libId;	
			$this->Library->query($sql);
			$this->Library->setDataSource('default');
		}
        //delete from wishlist table
        $deleteSongId = $id;     
        $this->Wishlist->delete($deleteSongId);
        //get start and end day of the week
        $startDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
        //get no of downloads for this week
		$this->Download->recursive = -1;
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));        
        echo $downloadsUsed;
		exit;
    }
    /*
     Function Name : historyDownload
     Desc : For getting download count on My History 
    */
    function historyDownload() {
        Configure::write('debug', 0);
        $this->layout = false;
        $id = $_REQUEST['id'];
		$libId = $_REQUEST['libid'];
		$patId = $_REQUEST['patronid'];
		$wk = date('W')-1;
        $startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
        $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";
		$this->Download->recursive = -1;
        $downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $id,'library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate)),'limit' => '1'));
		$downloadCount =  $downloadsUsed[0]['Download']['history'];
		//check for download availability
		if($downloadCount < 2){
			$this->Download->setDataSource('master');
			$sql = "UPDATE `downloads` SET history=history+1 Where ProdID='".$id."' AND library_id = '".$libId."' AND patron_id = '".$patId."' AND history < 2 AND created BETWEEN '".$startDate."' AND '".$endDate."'";
			$this->Download->query($sql);
			$this->Download->setDataSource('default');
			$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $id,'library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate)),'limit' => '1'));
			$downloadCount =  $downloadsUsed[0]['Download']['history'];
            echo $downloadCount;			
        } else {
			echo "error";
		}
		exit;
    }
	/*
     Function Name : admin_historyform
     Desc : actions used for admin history form
    */

    function admin_historyform() {
	if(isset($this->data)) {
	    if($this->data['Home']['id'] != "") {
		$this->Page->id = $this->data['Home']['id'];
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save()){
		  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
	    }
	    else {
		$pageData['Page']['page_name'] = $this->data['Home']['page_name'];
		$pageData['Page']['page_content'] = $this->data['Home']['page_content'];;
		$this->Page->set($pageData['Page']);
		if($this->Page->save()) {
		    $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		}
		else {
		    $this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		}
	    }
	}
        $this -> set( 'formAction', 'admin_historyform');
        $this -> set( 'formHeader', 'Manage History Page Text' );
        $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'history')));
	if(count($getPageData) != 0) {
	    $getData['Home']['id'] = $getPageData[0]['Page']['id'];
	    $getData['Home']['page_name'] = $getPageData[0]['Page']['page_name'];
	    $getData['Home']['page_content'] = $getPageData[0]['Page']['page_content'];
	    $this -> set( 'getData', $getData );
	}
	else {
	    $arr = array();
	    $this->set('getData',$arr);
	}
	$this->layout = 'admin';
    }	
	
}
?>