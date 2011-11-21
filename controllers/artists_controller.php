<?php
/*
 File Name : artists_controller.php
 File Description : Artist controller page
 Author : m68interactive
 */

Class ArtistsController extends AppController
{
	var $name = 'Artists';
	var $uses = array( 'Featuredartist', 'Artist', 'Newartist','Files','Album','Song','Download' );
	var $layout = 'admin';
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Language');
	var $components = array('Session', 'Auth', 'Acl','RequestHandler','Downloads','ValidatePatron','CdnUpload');
	
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
	 Function Name : managefeaturedartist
	 Desc : action for listing all the featured artists
        */
	function admin_managefeaturedartist() {
		$artists = $this->paginate('Featuredartist',array('language' => Configure::read('App.LANGUAGE')));		
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
				$country = $getData[ 'Featuredartist' ][ 'territory' ];
				$getArtistDataObj = new Song();
				$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName, $country );
				$this -> set( 'getArtistData', $getArtistData );
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
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_featured");
		memcache_close($memcache);
	}
	
	/*
	 Function Name : admin_insertfeaturedartist
	 Desc : inserts a featured artist
        */
	function admin_insertfeaturedartist() {
		$errorMsg = '';
		$newPath = '../webroot/img/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$src = WWW_ROOT.'img/'.$fileName;
		$dst = Configure::read('App.CDN_PATH').'featuredimg/'.$fileName;
		$error = $this->CdnUpload->sendFile($src, $dst);
		unlink($newPath);
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		$artist = '';
		if(isset($_REQUEST[ 'artistName' ])){
			$artist = $_REQUEST[ 'artistName' ];
		} else{
			$artist = $this->data[ 'Artist' ][ 'artist_name' ];
		}				
		
		if( $artist == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
			$errorMsg .= 'Please upload an image.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
			$errorMsg .= 'Please Choose a Territory<br/>';
		}
		
		$insertArr = array();
		$insertArr[ 'artist_name' ] = $artist;
		$insertArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$insertArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
		$insertArr[ 'language' ] = Configure::read('App.LANGUAGE');
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
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_featured");
		memcache_close($memcache);		
	}
	
	/*
	 Function Name : admin_updatefeaturedartist
	 Desc : Updates a featured artist
        */
	function admin_updatefeaturedartist() {
		$errorMsg = '';
		$this->Featuredartist->id = $this->data[ 'Artist' ][ 'id' ];
		$getArtistrDataObj = new Featuredartist();
		$getData = $getArtistrDataObj -> getartistdata($this->Featuredartist->id );
		$newPath = '../webroot/img/';
		$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
		$newPath = $newPath . $fileName;
		$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'artistimg/'.$getData[ 'Newartist' ]['artist_image']);		
		move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
		$src = WWW_ROOT.'img/'.$fileName;
		$dst = Configure::read('App.CDN_PATH').'featuredimg/'.$fileName;
		$error = $this->CdnUpload->sendFile($src, $dst);
		unlink($newPath);
		$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
		$artistName = '';
		if(isset($_REQUEST[ 'artistName' ])){
			$artistName = $_REQUEST[ 'artistName' ];
		}
		$artist = '';
		if(isset($_REQUEST[ 'artistName' ])){
			$artist = $_REQUEST[ 'artistName' ];
		} else{
			$artist = $this->data[ 'Artist' ][ 'artist_name' ];
		}				

		if( $artist == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
			$errorMsg .= 'Please Choose a Territory';
		}		
		$updateArr = array();
		$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
		$updateArr[ 'artist_name' ] = $artist;
		$updateArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
		$updateArr[ 'language' ] = Configure::read('App.LANGUAGE');
		if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
			$updateArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
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
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_featured");
		memcache_close($memcache);		
	}
	
	/*
	 Function Name : admin_delete
	 Desc : For deleting a featured artist
        */
	function admin_delete() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Featuredartist();
		$data = $this->Featuredartist->find('all', array('conditions' => array('id' => $deleteArtistUserId)));
		$fileName = $data[0]['Featuredartist']['artist_image'];
		$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'featuredimg/'.$fileName);
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managefeaturedartist' );
		}
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_featured");
		memcache_close($memcache);		
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
				$artistName = '';
				if(isset($_REQUEST[ 'artistName' ])){
					$artistName = $_REQUEST[ 'artistName' ];
				} else{
					$artistName = $getData[ 'Artist' ][ 'artist_name' ];
				}
				$artist = '';
				if(isset($_REQUEST[ 'artistName' ])){
					$artist = $_REQUEST[ 'artistName' ];
				} else{
					$artist = $this->data[ 'Artist' ][ 'artist_name' ];
				}
				if( isset( $this -> data ) ) {
					$updateObj = new Artist();
					$updateArr = array();
					if( $artist == '' ) {
						$errorMsg .= 'Please select Artist Name';
					}
					if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
						$errorMsg .= 'Please Choose a Territory';
					}					
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $artist;
					$updateArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
					$updateArr[ 'language' ] = Configure::read('App.LANGUAGE');
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
						$newPath = '../webroot/img/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'artistimg/'.$getData[ 'Artist' ][ 'artist_image' ]);
						$src = WWW_ROOT.'img/'.$fileName;
						$dst = Configure::read('App.CDN_PATH').'artistimg/'.$fileName;
						$error = $this->CdnUpload->sendFile($src, $dst);
						unlink($newPath);
						$updateArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
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
				$country = $getData[ 'Artist' ][ 'territory' ];
				$getArtistDataObj = new Song();
				$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName, $country );				
				$this -> set( 'getArtistData', $getArtistData );
			}
		}
		else {
			$this -> set( 'formAction', 'admin_createartist' );
			$this -> set( 'formHeader', 'Add  Artist' );
			$condition = 'add';
			$artistName = '';
			if(isset($_REQUEST[ 'artistName' ])){
				$artist = $_REQUEST[ 'artistName' ];
			} else{
				$artist = $this->data[ 'Artist' ][ 'artist_name' ];
			}			
			if( isset( $this -> data ) ) {
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
					$errorMsg .= 'Please upload an image<br/>';
				}
				if( $artist == '' ) {
					$errorMsg .= 'Please select an artist name<br/>';
				}
				if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
					$errorMsg .= 'Please Choose a Territory<br/>';
				}				
				$newPath = '../webroot/img/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$src = WWW_ROOT.'img/'.$fileName;
				$dst = Configure::read('App.CDN_PATH').'artistimg/'.$fileName;
				$error = $this->CdnUpload->sendFile($src, $dst);
				unlink($newPath);
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
				$insertArr[ 'artist_name' ] = $artist;;
				$insertArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertArr[ 'language' ] = Configure::read('App.LANGUAGE');
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
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_artists");
		memcache_close($memcache);		
	}
	
	/*
	 Function Name : admin_manageartist
	 Desc : manages new artists with images
        */
	function admin_manageartist() {
		$artists = $this->paginate('Artist',array('language' => Configure::read('App.LANGUAGE')));	
		$this -> set( 'artists', $artists );
	}
	
	/*
	 Function Name : admin_deleteartists
	 Desc : For deleting a new artist
        */
	function admin_deleteartists() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Artist();
		$data = $this->Artist->find('all', array('conditions' => array('id' => $deleteArtistUserId)));
		$fileName = $data[0]['Artist']['artist_image'];
		$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'artistimg/'.$fileName);
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'manageartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'manageartist' );
		}
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_artists");
		memcache_close($memcache);		
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
				$artistName = '';
				if(isset($_REQUEST[ 'artistName' ])){
					$artistName = $_REQUEST[ 'artistName' ];
				} else{
					$artistName = $getData[ 'Newartist' ][ 'artist_name' ];
				}
				$artist = '';
				if(isset($_REQUEST[ 'artistName' ])){
					$artist = $_REQUEST[ 'artistName' ];
				} else{
					$artist = $this->data[ 'Artist' ][ 'artist_name' ];
				}				
				if( isset( $this -> data ) ) {
					$updateObj = new Newartist();
					$updateArr = array();
					if( $artist == '' ) {
						$errorMsg .= 'Please select Artist Name';
					}
					if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
						$errorMsg .= 'Please Choose a Territory';
					}					
					$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
					$updateArr[ 'artist_name' ] = $artist;
					$updateArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
					$updateArr[ 'language' ] = Configure::read('App.LANGUAGE');
					if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] != '' ) {
						$newPath = '../webroot/img/';
						$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
						$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'newartistimg/'.$getData[ 'Newartist' ][ 'artist_image' ]);
						$newPath = $newPath . $fileName;
						move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
						$src = WWW_ROOT.'img/'.$fileName;
						$dst = Configure::read('App.CDN_PATH').'newartistimg/'.$fileName;
						$error = $this->CdnUpload->sendFile($src, $dst);
						unlink($newPath);
						$updateArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
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
				$country = $getData[ 'Newartist' ][ 'territory' ];
				$getArtistDataObj = new Song();
				$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName, $country );				
				$this -> set( 'getArtistData', $getArtistData );
			}
		}
		else {
			$this -> set( 'formAction', 'admin_addnewartist' );
			$this -> set( 'formHeader', 'Add New Artist' );
			$condition = 'add';
			$artistName = '';
			$artist = '';
			if(isset($_REQUEST[ 'artistName' ])){
				$artist = $_REQUEST[ 'artistName' ];
			} else{
				$artist = $this->data[ 'Artist' ][ 'artist_name' ];
			}				
			
			if( isset( $this -> data ) ){
				if( $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ] == '' ) {
					$errorMsg .= 'Please upload an image<br/>';
				}
				if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
					$errorMsg .= 'Please Choose a Territory<br/>';
				}
				if( trim( $artist ) == '' ) {
					$errorMsg .= 'Please select an artist name<br/>';
				}				
				$newPath = '../webroot/img/';
				$fileName = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$newPath = $newPath . $fileName;
				move_uploaded_file( $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ], $newPath );
				$src = WWW_ROOT.'img/'.$fileName;
				$dst = Configure::read('App.CDN_PATH').'newartistimg/'.$fileName;
				$error = $this->CdnUpload->sendFile($src, $dst);
				unlink($newPath);
				$filePath = $this -> data[ 'Artist' ][ 'artist_image' ][ 'tmp_name' ];
				$insertArr = array();
				$insertArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
				$insertArr[ 'artist_image' ] = $this -> data[ 'Artist' ][ 'artist_image' ][ 'name' ];
				$insertArr[ 'artist_name' ] = $artist;
				$insertArr[ 'language' ] = Configure::read('App.LANGUAGE');
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
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_newartists");
		memcache_close($memcache);		
	}
	
	/*
	 Function Name : admin_managenewartist
	 Desc : manages artists with images
        */
	function admin_managenewartist() {
		$artists = $this->paginate('Newartist',array('language' => Configure::read('App.LANGUAGE')));
		$this -> set( 'artists', $artists );
	}
	
	/*
	 Function Name : admin_deletenewartists
	 Desc : For deleting a featured artist
	*/
	function admin_deletenewartists() {
		$deleteArtistUserId = $this -> params[ 'named' ][ 'id' ];
		$deleteObj = new Newartist();
		$data = $this->Newartist->find('all', array('conditions' => array('id' => $deleteArtistUserId)));
		$fileName = $data[0]['Newartist']['artist_image'];
		$error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'newartistimg/'.$fileName);		
		if( $deleteObj -> del( $deleteArtistUserId ) ) {
			$this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
			$this -> redirect( 'managenewartist' );
		}
		else {
			$this -> Session -> setFlash( 'Error occured while deleteting the record', 'modal', array( 'class' => 'modal problem' ) );
			$this -> redirect( 'managenewartist' );
		}
		$memcache = new Memcache;
		$memcache->addServer('10.181.59.94', 11211);
		$memcache->addServer('10.181.59.64', 11211);
		memcache_delete($memcache, "app_prod_newartists");
		memcache_close($memcache);		
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
		$country = $this->Session->read('territory');
		if($this->Session->read('block') == 'yes') {
			$cond = array('Song.Advisory' => 'F');
		}
		else{
			$cond = "";
		}		
		if($album != '') {
			$condition = array("Album.ProdID" => $album);
		}
		else{
			$allAlbum = $this->Album->find('all', array('fields' => array('Album.ProdID'),'conditions' => array('Album.ArtistText' => base64_decode($id)), 'recursive' => -1));
			$val = '';
			$this->Song->Behaviors->attach('Containable');
			foreach($allAlbum as $k => $v){
				$recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'),'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'],'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",'Country.Territory' => $country, $cond), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0,'limit' => 1));
				if(count($recordCount) > 0){
					$val = $val.$v['Album']['ProdID'].",";
				}
			}
			$condition = array("Album.ProdID IN (".rtrim($val,",").")");
		}
		$this->layout = 'home';
		$this->set('artistName',base64_decode($id));
		$patId = $this->Session->read('patron');
		$libId = $this->Session->read('library');
		//$country = "'".$country."'";
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		if($this->Session->read('block') == 'yes') {
			$cond = array('Album.Advisory' => 'F');
		}
		else{
			$cond = "";
		}	
		$this->paginate =  array('conditions' =>
					array('and' =>
						array(
						    array('Album.ArtistText' => base64_decode($id)),
						    $condition
						), "1 = 1 GROUP BY Album.ProdID"
					),
					'fields' => array(
						'Album.ProdID',
						'Album.Title',
						'Album.ArtistText',
						'Album.AlbumTitle',
						'Album.Artist',
						'Album.ArtistURL',
						'Album.Label',
						'Album.Copyright',						
						),
					'contain' => array(
						'Genre' => array(
							'fields' => array(
								'Genre.Genre'								
								)
							),
						'Country' => array(
							'fields' => array(
								'Country.Territory'								
								)
							),												
						'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName',
								'Files.SourceURL'
							),
						)			                                
					), 'order' => array('Country.SalesDate' => 'desc'), 'limit' => '3','cache' => 'yes', 'chk' => 2
				);
		if($this->Session->read('block') == 'yes') {
			$cond = array('Song.Advisory' => 'F');
		}
		else{
			$cond = "";
		}
		$this->Album->recursive = 2;
		$albumData = array();
		$albumData = $this->paginate('Album'); //getting the Albums for the artist
		$albumSongs = array();
		if(!empty($albumData))
		{
			foreach($albumData as $album) {
				$albumSongs[$album['Album']['ProdID']] =  $this->Song->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Song.ReferenceID' => $album['Album']['ProdID']),							
									array('Song.DownloadStatus' => 1),
								//	array('Song.TrackBundleCount' => 0),
									array("Song.Sample_FileID != ''"),
									array("Song.FullLength_FIleID != ''"),
									array('Country.Territory' => $country),$cond
								)
							),
						'fields' => array(
								'Song.ProdID',
								'Song.Title',
								'Song.ArtistText',
								'Song.DownloadStatus',
								'Song.SongTitle',
								'Song.Artist',
								'Song.Advisory',
								'Song.Sample_Duration',
								'Song.FullLength_Duration',
								'Song.Sample_FileID',
								'Song.FullLength_FIleID'

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
											'Country.SalesDate'
										)
									),								
							'Sample_Files' => array(
									'fields' => array(
												'Sample_Files.CdnPath' ,
												'Sample_Files.SaveAsName'
										)
									),
							'Full_Files' => array(
									'fields' => array(
												'Full_Files.CdnPath' ,
												'Full_Files.SaveAsName'
										)
									),
									
						),'group' => 'Song.ProdID','order' => 'Song.ReferenceID'
						  ));
			}
		}
		$this->Download->recursive = -1;
		foreach($albumSongs as $k => $albumSong){
			foreach($albumSong as $key => $value){
					$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $value['Song']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
					if(count($downloadsUsed) > 0){
						$albumSongs[$k][$key]['Song']['status'] = 'avail';
					} else{
						$albumSongs[$k][$key]['Song']['status'] = 'not';
					}
			}
		}
	    $this->set('albumData', $albumData);
	    if(isset($albumData[0]['Song']['ArtistURL'])) {
	       $this->set('artistUrl',$albumData[0]['Song']['ArtistURL']);
	    }else {
	       $this->set('artistUrl', "N/A");
	    }
		$array = array();
		$pre = '';
		$res = array();
	    $this->set('albumSongs',$albumSongs);
	}
	/*
	 Function Name : view
	 Desc : For artist view page
	*/
	function admin_getArtists(){
        Configure::write('debug', 0);	
		$this->Song->recursive = 2;	
		$artist = $this->Song->find('all',array(
							'conditions' =>
								array('and' =>
									array(
										array('Country.Territory' => $_REQUEST['Territory'])
									)
								),
							'fields' => array(
									'DISTINCT Song.ArtistText',
									),
							'contain' => array(
									'Country' => array(
											'fields' => array(
												'Country.Territory'								
											)
										),
								),
							'order' => 'Song.ArtistText'
						));
		$data = "<option value=''>SELECT</option>";				
		foreach($artist as $k=>$v){
			$data = $data."<option value='".$v['Song']['ArtistText']."'>".$v['Song']['ArtistText']."</option>";
		}
		print "<select class='select_fields' name='artistName'>".$data."</select>";exit;
						
	}
  }
?>