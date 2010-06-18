<?php
/*
 File Name : artists_controller.php
 File Description : Artist controller page
 Author : maycreate
 */

Class ArtistsController extends AppController
{
	var $name = 'Artists';
	var $uses = array( 'Featuredartist', 'Physicalproduct', 'Artist', 'Newartist','Files' );
	var $layout = 'admin';
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist');
	var $components = array('Session', 'Auth', 'Acl','RequestHandler','Downloads','ValidatePatron');
	
	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('view','test');
		$libraryCheckArr = array("view");
		if(in_array($this->action,$libraryCheckArr)) {
			$validPatron = $this->ValidatePatron->validatepatron();
			if($validPatron == '0') {
				//$this->Session->destroy();
				$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
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
	 Function Name : managefeaturedartist
	 Desc : action for listing all the featured artists
        */
	function admin_managefeaturedartist() {
		$artists = $this->paginate('Featuredartist');
		$this -> set( 'artists', $artists );
	}
	
	/*
	 Function Name : admin_artistform
	 Desc : action for displaying the add/edit featured artist form
        */
	function admin_artistform() {
		if( !empty( $this -> params[ 'named' ] ) ) { //gets the values from the url in form  of array
			$artistId = $this -> params[ 'named' ][ 'id' ];
			if( trim( $artistId ) != '' && is_numeric( $artistId ) ) {
				$this -> set( 'formAction', 'admin_updatefeaturedartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit Featured Artist' );
				$getArtistrDataObj = new Featuredartist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Featuredartist' ][ 'artist_name' ];
			}
		}
		else {
			$this -> set( 'formAction', 'admin_insertfeaturedartist' );
			$this -> set( 'formHeader', 'Add Featured Artist' );
			$getFeaturedDataObj = new Featuredartist();
			$featuredtData = $getFeaturedDataObj -> getallartists();
			$condition = 'add';
			$artistName = '';
		}
		
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}
	
	/*
	 Function Name : admin_insertfeaturedartist
	 Desc : inserts a featured artist
        */
	function admin_insertfeaturedartist() {
		$errorMsg = '';
		$newPath = '../webroot/img/featuredimg/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		
		if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
			$errorMsg .= 'Please upload an image.<br/>';
		}
		$insertArr = array();
		$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
		$insertArr[ 'artist_image' ] = 'img/featuredimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$insertObj = new Featuredartist();
		if( empty( $errorMsg ) ) {
			if( $insertObj -> insert( $insertArr ) ) {
				$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
				$this -> redirect( 'managefeaturedartist' );
			}
		}
		else {
			$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'artistform' );
		}
	}
	
	/*
	 Function Name : admin_updatefeaturedartist
	 Desc : Updates a featured artist
        */
	function admin_updatefeaturedartist() {
		$errorMsg = '';
		$this -> Featuredartist -> id = $this -> data[ 'Artist' ][ 'id' ];
		$newPath = '../webroot/img/featuredimg/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		$updateArr = array();
		$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
		$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
			$updateArr[ 'artist_image' ] = 'img/featuredimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		}
		$updateObj = new Featuredartist();
		if( empty( $errorMsg ) ) {
			if( $updateObj -> insert( $updateArr ) ){
				$this -> Session -> setFlash( 'Data has been updated successfully!', 'modal', array( 'class' => 'modal success' ) );
				$this -> redirect( 'managefeaturedartist' );
			}
		}
		else {
			$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
	}
	
	/*
	 Function Name : admin_delete
	 Desc : For deleting a featured artist
        */
	function admin_delete() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Featuredartist();
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
	}
	
	/*
	 Function Name : admin_createartist
	 Desc : assigns artists with images
        */
	function admin_createartist() {
		$errorMsg = '';
		if( !empty( $this -> params[ 'named' ][ 'id' ] ) ) { //gets the values from the url in form  of array
			$artistId = $this -> params[ 'named' ][ 'id' ];
			if( trim( $artistId ) != '' && is_numeric( $artistId ) ) {
				$this -> set( 'formAction', 'admin_createartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit Artist' );
				$getArtistrDataObj = new Artist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Artist' ][ 'artist_name' ];
				if( isset( $this -> data ) ) {
					$updateObj = new Artist();
					$updateArr = array();
					if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' ) {
						$errorMsg .= 'Please select Artist Name';
					}
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
						$newPath = '../webroot/img/artistimg/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$updateArr[ 'artist_image' ] = 'img/artistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
					}
					if( empty( $errorMsg ) ) {
						if( $updateObj -> insert( $updateArr ) ) {
							$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
							$this -> redirect( 'manageartist' );
						}
					}
					else {
						$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
					}
				}
			}
		}
		else {
			$this -> set( 'formAction', 'admin_createartist' );
			$this -> set( 'formHeader', 'Add  Artist' );
			$condition = 'add';
			$artistName = '';
			if( isset( $this -> data ) ) {
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
					$errorMsg .= 'Please upload an image<br/>';
				}
				if( trim( $this -> data[ 'Artist' ][ 'artist_name' ] ) == '' ) {
					$errorMsg .= 'Please select an artist name<br/>';
				}
				$newPath = '../webroot/img/artistimg/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
				$insertArr[ 'artist_image' ] = 'img/artistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertObj = new Artist();
				if( empty( $errorMsg ) ) {
					if( $insertObj -> insert( $insertArr ) ) {
						$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( 'manageartist' );
					}
				}
				else {
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
		}
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> allartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}
	
	/*
	 Function Name : admin_manageartist
	 Desc : manages new artists with images
        */
	function admin_manageartist() {
		$artists = $this->paginate('Artist');
		$this -> set( 'artists', $artists );
	}
	
	/*
	 Function Name : admin_deleteartists
	 Desc : For deleting a new artist
        */
	function admin_deleteartists() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Artist();
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'manageartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'manageartist' );
		}
	}
	
	/*
	 Function Name : admin_addnewartist
	 Desc : assigns artists with images
        */
	function admin_addnewartist() {
		$errorMsg = '';
		if( !empty( $this -> params[ 'named' ][ 'id' ] ) ) { //gets the values from the url in form  of array
			$artistId = $this -> params[ 'named' ][ 'id' ];
			if( trim( $artistId ) != '' && is_numeric( $artistId ) ){
				$this -> set( 'formAction', 'admin_addnewartist/id:' . $artistId );
				$this -> set( 'formHeader', 'Edit New Artsit' );
				$getArtistrDataObj = new Newartist();
				$getData = $getArtistrDataObj -> getartistdata( $artistId );
				$this -> set( 'getData', $getData );
				$condition = 'edit';
				$artistName = $getData[ 'Newartist' ][ 'artist_name' ];
				if( isset( $this -> data ) ) {
					$updateObj = new Newartist();
					$updateArr = array();
					if( $this -> data[ 'Artist' ][ 'artist_name' ] == '' ) {
						$errorMsg .= 'Please select Artist Name';
					}
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
						$newPath = '../webroot/img/newartistimg/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$updateArr[ 'artist_image' ] = 'img/newartistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
					}
					if( empty( $errorMsg ) ) {
						if( $updateObj -> insert( $updateArr ) ) {
							$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
							$this -> redirect( 'managenewartist' );
						}
					}
					else {
						$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
					}
				}
			}
		}
		else {
			$this -> set( 'formAction', 'admin_addnewartist' );
			$this -> set( 'formHeader', 'Add New Artist' );
			$condition = 'add';
			$artistName = '';
			if( isset( $this -> data ) ){
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
					$errorMsg .= 'Please upload an image<br/>';
				}
				if( trim( $this -> data[ 'Artist' ][ 'artist_name' ] ) == '' ) {
					$errorMsg .= 'Please select an artist name<br/>';
				}
				$newPath = '../webroot/img/newartistimg/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'artist_name' ] = $this -> data[ 'Artist' ][ 'artist_name' ];
				$insertArr[ 'artist_image' ] = 'img/newartistimg/' . $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertObj = new Newartist();
				if( empty( $errorMsg ) ) {
					if( $insertObj -> insert( $insertArr ) ) {
						$this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
						$this -> redirect( 'managenewartist' );
					}
				}
				else {
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
		}		
		$getArtistDataObj = new Physicalproduct();
		$getArtistData = $getArtistDataObj -> allartistname( $condition, $artistName );
		$this -> set( 'getArtistData', $getArtistData );
	}
	
	/*
	 Function Name : admin_managenewartist
	 Desc : manages artists with images
        */
	function admin_managenewartist() {
		$artists = $this->paginate('Newartist');
		$this -> set( 'artists', $artists );
	}
	
	/*
	 Function Name : admin_deletenewartists
	 Desc : For deleting a featured artist
	*/
	function admin_deletenewartists() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Newartist();		
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managenewartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managenewartist' );
		}
	}
	
	/*
	 Function Name : view
	 Desc : For artist view page
	*/
	function view($id=null,$album=null) {	  	  
		if(count($this -> params['pass']) > 1) {
			$count = count($this -> params['pass']);	      
			$id = $this -> params['pass'][0];
			for($i=1;$i<$count;$i++) {
				if(!is_numeric($this -> params['pass'][$i])) {
				      $id .= "/".$this -> params['pass'][$i];
				}
			}
			if(is_numeric($this -> params['pass'][$count - 1])) {
				$album = $this -> params['pass'][$count - 1];
			}
			else {
				$album = "";
			}
		}
		if($album != '') {
			$condition = array("Physicalproduct.ReferenceID" => $album);
		}
		else{
			$condition = "";
		}
		$this->layout = 'home';
		$this->set('artistName',base64_decode($id));
		$patId = $this->Session->read('patron');
		$libId = $this->Session->read('library');
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		if($this->Session->read('block') == 'yes') {
			$cond = array('Metadata.Advisory' => 'F');
		}
		else{
			$cond = "";
		}	
		$this -> paginate =  array('conditions' =>
					array('and' =>
						array(
						    array('Physicalproduct.ArtistText' => base64_decode($id)),
						    array("Physicalproduct.ProdID = Physicalproduct.ReferenceID"),
						    array("Physicalproduct.ReferenceID IN(SELECT Distinct ReferenceID  FROM `PhysicalProduct` WHERE `DownloadStatus` = '1' and `ProdID` <> `ReferenceID`)"),
						    $condition
						)
					),
					'fields' => array(
						'Physicalproduct.ProdID',
						'Physicalproduct.Title',
						'Physicalproduct.ArtistText',
						'Physicalproduct.ReferenceID'							
						),
					'contain' => array(
					'Genre' => array(
						'fields' => array(
							'Genre.Genre'								
							)
						),						
					'Metadata' => array(
						'fields' => array(
							'Metadata.Title',
							'Metadata.Artist',
							'Metadata.ArtistURL',
							'Metadata.Label',
							'Metadata.Copyright',								
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
						)			                                
					),'limit' => '3','cache' => 'yes'
				);
		$this->Physicalproduct->recursive = 2;
		$albumData = $this->paginate('Physicalproduct'); //getting the Albums for the artist
		$albumSongs = array();
		foreach($albumData as $album) {
			$albumSongs[$album['Physicalproduct']['ReferenceID']] =  $this->Physicalproduct->find('all',array(
					'conditions' =>
						array('and' =>
							array(
								array( 'Physicalproduct.ReferenceID' => $album['Physicalproduct']['ReferenceID']),							
								array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),							
								array('Physicalproduct.DownloadStatus' => 1),$cond
							)
						),
					'fields' => array(
							'Physicalproduct.ProdID',
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
										'Audio.Duration'                                                    
									),
								'Files' => array(
										'fields' => array(
											'Files.CdnPath' ,
											'Files.SaveAsName'
										)
									)
								)
					),'order' => 'Physicalproduct.ReferenceID'
				      ));
	    }
	    $this->set('albumData', $albumData);
	    if(isset($albumData[0]['Metadata']['ArtistURL'])) {
	       $this->set('artistUrl',$albumData[0]['Metadata']['ArtistURL']);
	    }else {
	       $this->set('artistUrl', "N/A");
	    }
	    $this->set('albumSongs',$albumSongs);
	}
  }
?>