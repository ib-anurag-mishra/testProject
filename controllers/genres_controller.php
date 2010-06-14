<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */

ini_set('memory_limit', '1024M');
Class GenresController extends AppController
{
	var $uses = array('Metadata','Product','Category','Files','Physicalproduct');
	var $components = array('Session', 'Auth', 'Acl','RequestHandler','Downloads','ValidatePatron');
	var $helpers = array('Cache','Library','Page','Wishlist');
	
	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('view','index');
		$libraryCheckArr = array("view","index");
		if(in_array($this->action,$libraryCheckArr)) {
		  $validPatron = $this->ValidatePatron->validatepatron();
			if($validPatron == '0') {
				$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
			}
			else if($validPatron == '2') {
				$this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));			
			}
		}
	}
	
	/*
	 Function Name : index
	 Desc : actions on landing page
        */
	function index() {
		$this->layout = 'home';
		$patId = $_SESSION['patron'];
		$libId = $_SESSION['library'];
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		$this->Genre->recursive = -1;
		$this->set('genresAll', $this->Genre->find('all', array('fields' => 'DISTINCT Genre','order' => 'Genre')));
		$categories = $this->Category->find('all', array('fields' => 'Genre','order' => 'rand()','limit' => '4'));
		$i = 0;
		$j = 0;
		foreach ($categories as $category) {
			$genreName = $category['Category']['Genre'];
			if($_SESSION['block'] == 'yes') {
				$cond = array('Metadata.Advisory' => 'F');
			}
			else {
				$cond = "";
			}
			if (($genres = Cache::read($genreName)) === false) {
				$this->Physicalproduct->Behaviors->attach('Containable');			
				$genreDetails = $this->Physicalproduct->find('all',array('conditions' =>
											array('and' =>
												array(
													array('Genre.Genre' => $genreName),							
													array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
													array('Physicalproduct.DownloadStatus' => 1),
													array('Physicalproduct.TrackBundleCount' => 0),
													array("Physicalproduct.UpdateOn >" => date('Y-m-d', strtotime("-3 week"))),$cond
												)
											),
											'fields' => array(
												'Physicalproduct.ProdID',
												'Physicalproduct.ReferenceID',
												'Physicalproduct.Title',
												'Physicalproduct.ArtistText',
												'Physicalproduct.DownloadStatus',
												'Physicalproduct.SalesDate'
											),
											'contain' => array(
												'Genre' => array(
													'fields' => array(
														'Genre.Genre'								
													)
												),
												'Graphic' => array(
													'fields' => array(
														'Graphic.ProdID',
														'Graphic.FileID'
													),
													'Files' => array(
														'fields' => array(
															'Files.CdnPath' ,
															'Files.SaveAsName',
															'Files.SourceURL'
														)
													)
												),						
												'Metadata' => array(
													'fields' => array(
														'Metadata.Title',
														'Metadata.Artist',
														'Metadata.Advisory'
													)
												),
												'Audio' => array(
													'fields' => array(
														'Audio.FileID',                                                    
													),
													'Files' => array(
														'fields' => array(
															'Files.CdnPath' ,
															'Files.SaveAsName'
														)
													)
												)
											),'limit' => '50'));
				Cache::write($genreName, $genreDetails);
			}
			$genreDetails = Cache::read($genreName);
			$finalArr = Array();
			$songArr = Array();
			if(count($genreDetails) > 3) {
			  $rand_keys = array_rand($genreDetails,3);
			  $songArr[0] = $genreDetails[$rand_keys[0]];
			  $songArr[1] = $genreDetails[$rand_keys[1]];
			  $songArr[2] = $genreDetails[$rand_keys[2]];				
			}
			else {
			  $songArr = $genreDetails;
			}
			foreach($songArr as $genre) {
				$albumArtwork = shell_exec('perl files/tokengen ' . $genre['Graphic']['Files']['CdnPath']."/".$genre['Graphic']['Files']['SourceURL']);
				$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['1']['Files']['CdnPath']."/".$genre['Audio']['1']['Files']['SaveAsName']);
				$sampleSongUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['0']['Files']['CdnPath']."/".$genre['Audio']['0']['Files']['SaveAsName']);
				$finalArr[$i]['Album'] = $genre['Physicalproduct']['Title'];
				$finalArr[$i]['Song'] = $genre['Metadata']['Title'];
				$finalArr[$i]['Artist'] = $genre['Metadata']['Artist'];
				$finalArr[$i]['ProdArtist'] = $genre['Physicalproduct']['ArtistText'];
				$finalArr[$i]['Advisory'] = $genre['Metadata']['Advisory'];
				$finalArr[$i]['AlbumArtwork'] = $albumArtwork;
				$finalArr[$i]['SongUrl'] = $songUrl;
				$finalArr[$i]['ProdId'] = $genre['Physicalproduct']['ProdID'];
				$finalArr[$i]['ReferenceId'] = $genre['Physicalproduct']['ReferenceID'];
				$finalArr[$i]['SalesDate'] = $genre['Physicalproduct']['SalesDate'];
				$finalArr[$i]['SampleSong'] = $sampleSongUrl;
				$i++;
			}
			$finalArray[$j] = $finalArr;
			$finalArray[$j]['Genre'] = $genreName;
			$j++;
		}
		$this->set('categories',$finalArray);
	}
	
	/*
	 Function Name : view
	 Desc : actions on view all genres page
        */
	function view($Genre = null,$Artist = null) {
		$this -> layout = 'home';
		if( !base64_decode($Genre) ) {
			$this->Session ->setFlash( __( 'Invalid Genre.', true ) );
			$this->redirect( array( 'controller' => '/', 'action' => 'index' ) );
		}
		$patId = $_SESSION['patron'];
		$libId = $_SESSION['library'];
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		if($_SESSION['block'] == 'yes') {
		      $cond = array('Metadata.Advisory' => 'F');
		}
		else {
		      $cond = "";
		}
		if($Artist == '#') {
			$condition = array("Physicalproduct.ArtistText REGEXP '^[^A-Za-z]'");			
		}
		elseif($Artist != '') {
			$condition = array('Physicalproduct.ArtistText LIKE' => $Artist.'%');
		}
		else {
			$condition = "";
		}
		$this->Physicalproduct->unbindModel(array('hasOne' => array('Participant')));		
		$this->Physicalproduct->Behaviors->attach('Containable');
		$this->Physicalproduct->recursive = 0;
		$genre = base64_decode($Genre);
		$genre = mysql_escape_string($genre);					
		$this->paginate = array(
		      'conditions' => array("Genre.Genre = '$genre'",$condition,'1 = 1 GROUP BY Physicalproduct.ArtistText'),
		      'fields' => array('ArtistText'),
		      'order' => 'ArtistText ASC',		      
		      'limit' => '60', 'cache' => 'yes'
		      ); 
		$this->Physicalproduct->unbindModel(array('hasOne' => array('Participant')));
		$allArtists = $this->paginate('Physicalproduct');		
		$this->set('genres', $allArtists);
		$this->set('genre',base64_decode($Genre));
	}
	
	/*
	 Function Name : admin_managegenre
	 Desc : actions for admin end manage genre to add/edit genres
    */
	function admin_managegenre() {
		if($this->data) {
			$this->Category->deleteAll(array('1 = 1'), false);
			$selectedGenres = Array();
			$i = 0;
			foreach ($this->data['Genre']['Genre'] as $k => $v) {
				if($i < '8') {
				      if($v != '0') {
					    $selectedGenres['Genre'] = $v;			      
					    $this->Category->save($selectedGenres);
					    $this->Category->id = false ;
					    $i++;
				      }
				}
			}
			$this->Session -> setFlash( 'Your selection saved successfully!!', 'modal', array( 'class' => 'modal success' ) );
		}		
		$this->Genre->recursive = -1;
		$allGenres = $this->Genre->find('all', array(
							'fields' => 'DISTINCT Genre', 
							'order' => 'Genre')
						);
		$this->set('allGenres', $allGenres);
		$this->Category->recursive = -1;
		$selectedGenres = $this->Category->find('all',array('fields' => 'Genre'));
		foreach ($selectedGenres as $selectedGenre){
			$selArray[] = $selectedGenre['Category']['Genre'];
		}
		$this->set('selectedGenres', $selArray);
		$this->layout = 'admin';
	}
}
?>