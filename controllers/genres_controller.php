<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */

ini_set('memory_limit', '2048M');
Class GenresController extends AppController
{
	var $uses = array('Category','Files','Album','Song','Download');
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
	 Desc : actions on landing page
        */
	function index() {
		$country = $this->Session->read('territory');
		//$country = "'".$country."'";		
		$this->layout = 'home';
		$patId = $this->Session->read('patron');
		$libId = $this->Session->read('library');
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
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
		$this->set('genresAll', $genreAll);
		$category_ids = $this->Category->find('list', array('conditions' => array('Language' => Configure::read('App.LANGUAGE')),'fields' => 'id'));
		$rand_keys = array_rand($category_ids, 4);
		$rand_val = implode(",", $rand_keys);
		$categories = $this->Category->find('all', array(
								'conditions' => array('id IN ('.$rand_val.')'),
								'fields' => 'Genre'));
		$i = 0;
		$j = 0;
		foreach ($categories as $category) {
			$genreName = $category['Category']['Genre'];
			if($this->Session->read('block') == 'yes') {
				$cond = array('Song.Advisory' => 'F');
			}
			else {
				$cond = "";
			}
			if (($genres = Cache::read($genreName)) === false) {
				$this->Song->recursive = 2;
				$this->Song->Behaviors->attach('Containable');			
				$genreDetails = $this->Song->find('all',array('conditions' =>
											array('and' =>
												array(
													array('Genre.Genre' => $genreName),							
													array("Song.ReferenceID <> Song.ProdID"),
													array('Song.DownloadStatus' => 1),
												//	array('Song.TrackBundleCount' => 0),
													array("Song.Sample_FileID != ''"),
													array("Song.FullLength_FIleID != ''"),													
													array('Country.Territory' => $country),
													array("Song.UpdateOn >" => date('Y-m-d', strtotime("-7 week"))),$cond
												)
											),
											'fields' => array(
												'DISTINCT Song.ProdID',
												'Song.ReferenceID',
												'Song.Title',
												'Song.ArtistText',
												'Song.DownloadStatus',
												'Song.SongTitle',
												'Song.Artist',
												'Song.Advisory'
											),
											'contain' => array(
												'Genre' => array(
													'fields' => array(
														'Genre.Genre'								
													)
												),
												'Country' => array(
													'fields' => array(
														'Country.Territory',
														'Country.SalesDate',														
													)
												),												
												'Sample_Files' => array(
													'fields' => array(
														'Sample_Files.CdnPath',
														'Sample_Files.SaveAsName',
														'Sample_Files.SourceURL'
													),
												),
												'Full_Files' => array(
													'fields' => array(
														'Full_Files.CdnPath',
														'Full_Files.SaveAsName',
														'Full_Files.SourceURL'
													),
												),												
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
			$wk = date('W')-1;
			// $startDate = date('Y-m-d', strtotime(date('Y')."W".$wk."1"))." 00:00:00";
			// $endDate = date('Y-m-d', strtotime(date('Y')."W".date('W')."7"))." 23:59:59";	
			$startDate = date('Y-m-d', mktime(1, 0, 0, date('m'), date('d')-$wk, date('Y'))) . ' 00:00:00';
			$endDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d')-date('w'))+7, date('Y'))) . ' 23:59:59';
				
			$this->Download->recursive = -1;
			foreach($songArr as $genre) {
				$this->Song->recursive = 2;
				$this->Song->Behaviors->attach('Containable');
				$downloadData = $this->Album->find('all', array(
					'conditions'=>array('Album.ProdID' => $genre['Song']['ReferenceID']),
					'fields' => array(
						'Album.ProdID',
					),
					'contain' => array(										
						'Files' => array(
							'fields' => array(
								'Files.CdnPath',
								'Files.SaveAsName',
								'Files.SourceURL',
								),                             
						)
				)));
				$albumArtwork = shell_exec('perl files/tokengen ' . $downloadData[0]['Files']['CdnPath']."/".$downloadData[0]['Files']['SourceURL']);
				$sampleSongUrl = shell_exec('perl files/tokengen ' . $genre['Sample_Files']['CdnPath']."/".$genre['Sample_Files']['SaveAsName']);
				$songUrl = shell_exec('perl files/tokengen ' . $genre['Full_Files']['CdnPath']."/".$genre['Full_Files']['SaveAsName']);
				$finalArr[$i]['Album'] = $genre['Song']['Title'];
				$finalArr[$i]['Song'] = $genre['Song']['Title'];
				$finalArr[$i]['Artist'] = $genre['Song']['Artist'];
				$finalArr[$i]['ProdArtist'] = $genre['Song']['ArtistText'];
				$finalArr[$i]['Advisory'] = $genre['Song']['Advisory'];
				$finalArr[$i]['AlbumArtwork'] = $albumArtwork;
				$finalArr[$i]['SongUrl'] = $songUrl;
				$finalArr[$i]['ProdId'] = $genre['Song']['ProdID'];
				$finalArr[$i]['ReferenceId'] = $genre['Song']['ReferenceID'];
				$finalArr[$i]['SalesDate'] = $genre['Country']['SalesDate'];
				$finalArr[$i]['SampleSong'] = $sampleSongUrl;
				$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $genre['Song']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array($startDate, $endDate)),'limit' => '1'));
				if(count($downloadsUsed) > 0){
					$finalArr[$i]['status'] = 'avail';
				} else{
					$finalArr[$i]['status'] = 'not';
				}
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
		$patId = $this->Session->read('patron');
		$libId = $this->Session->read('library');
		$country = $this->Session->read('territory');
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
		if($Artist == '#') {
			$condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");			
		}
		elseif($Artist != '') {
			$condition = array('Song.ArtistText LIKE' => $Artist.'%');
		}
		else {
			$condition = "";
		}
		$this->Song->unbindModel(array('hasOne' => array('Participant')));		
		$this->Song->Behaviors->attach('Containable');
		$this->Song->recursive = 0;
		$genre = base64_decode($Genre);
		$genre = mysql_escape_string($genre);					
		$this->paginate = array(
		      'conditions' => array("Genre.Genre = '$genre'",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
		      'fields' => array('Song.ArtistText'),
			  'contain' => array(
				'Country' => array(
					'fields' => array(
						'Country.Territory'								
						)),
				'Genre' => array(
					'fields' => array(
							'Genre.Genre'								
						)),
			  ),
			  'extra' => array('chk' => 1),
		      'order' => 'Song.ArtistText ASC',		      
		      'limit' => '60', 'cache' => 'yes'
		      ); 
		$this->Song->unbindModel(array('hasOne' => array('Participant')));
		$allArtists = $this->paginate('Song');
		$this->set('genres', $allArtists);
		$this->set('genre',base64_decode($Genre));
	}
	
	/*
	 Function Name : admin_managegenre
	 Desc : actions for admin end manage genre to add/edit genres
    */
	function admin_managegenre() {
		if($this->data) {
			$this->Category->deleteAll(array('Language' => Configure::read('App.LANGUAGE')), false);
			$selectedGenres = Array();
			$i = 0;
			foreach ($this->data['Genre']['Genre'] as $k => $v) {
				if($i < '8') {
				      if($v != '0') {
					    $selectedGenres['Genre'] = $v;
					    $selectedGenres['Language'] = Configure::read('App.LANGUAGE');
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
		$selectedGenres = array();
		$selectedGenres = $this->Category->find('all',array('fields' => array('Genre'),'conditions' => array('Language' => Configure::read('App.LANGUAGE'))));
		foreach ($selectedGenres as $selectedGenre){
			$selArray[] = $selectedGenre['Category']['Genre'];
		}
		$this->set('selectedGenres', $selArray);
		$this->layout = 'admin';
	}
}
?>