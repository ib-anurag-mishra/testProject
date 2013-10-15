<?php
/*
 File Name : artists_controller.php
 File Description : Artist controller page
 Author : m68interactive
 */

Class ArtistsController extends AppController
{
	var $name = 'Artists';
	var $uses = array( 'Featuredartist', 'Artist', 'Newartist','Files','Album','Song','Download','Video', 'Territory' );
	var $layout = 'admin';
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Language', 'Album', 'Song', 'Mvideo','Videodownload');
	var $components = array('Session', 'Auth', 'Acl','RequestHandler','Downloads','ValidatePatron','CdnUpload');

	/*
	 Function Name : beforeFilter
	 Desc : actions that needed before other functions are getting called
        */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('view','test','album','album_ajax','album_ajax_view','admin_getAlbums','admin_getAutoArtist');
//		$libraryCheckArr = array("view");
//		if(in_array($this->action,$libraryCheckArr)) {
//			$validPatron = $this->ValidatePatron->validatepatron();
//			if($validPatron == '0') {
//				//$this->Session->destroy();
//				//$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
//				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
//			}
//			else if($validPatron == '2') {
//				//$this->Session->destroy();
//				$this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
//				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
//			$libraryCheckArr}
//		}
	}

	/*
	 Function Name : managefeaturedartist
	 Desc : action for listing all the featured artists
        */
	function admin_managefeaturedartist() {
		$artists = $this->paginate('Featuredartist',array('album != ""','language' => Configure::read('App.LANGUAGE')));
		$this -> set( 'artists', $artists );
	}

	/*
	 Function Name : admin_artistform
	 Desc : action for displaying the add/edit featured artist form
        */
	function admin_artistform() {
                ini_set('memory_limit','1024M');
		set_time_limit(0);
                $territories = $this->Territory->find("all");
                for($m=0;$m<count($territories);$m++)
                {
                    $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
                }
                $this->set("territories", $territoriesArray);
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

				
                                $getArtistData = array();
                                $this->set( 'getArtistData', $getArtistData );
                                                        $result = array();
                                                        $allAlbum = $this->Album->find('all', array(
                                'fields' => array('Album.ProdID','Album.AlbumTitle'),
                                'conditions' => array('Album.ArtistText' => $getData['Featuredartist']['artist_name'], 'Album.provider_type' => $getData['Featuredartist']['provider_type']),
                                'recursive' => -1
                                ));
                                                        
				$val = '';
				$this->Song->Behaviors->attach('Containable');
				foreach($allAlbum as $k => $v){
					$recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'),'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'],'Song.DownloadStatus' => 1,'TrackBundleCount' => 0,'Country.Territory' => $getData['Featuredartist']['territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0,'limit' => 1));
					if(count($recordCount) > 0){
						$result[$v['Album']['ProdID']] = $v['Album']['AlbumTitle'];
					}
				}
				$this->set( 'album', $result );
			}
		}
		else {
			$this->set( 'formAction', 'admin_insertfeaturedartist' );
			$this->set( 'formHeader', 'Add Featured Artist' );
			$getFeaturedDataObj = new Featuredartist();
			$featuredtData = $getFeaturedDataObj -> getallartists();
			$condition = 'add';
			$artistName = '';
		}
                $memcache = new Memcache;
		$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
                memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_u_s");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_c_a");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_t");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_n_z");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_a_u");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_e");
		memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_g_b");
		memcache_close($memcache);
	}

	/*
	 Function Name : admin_insertfeaturedartist
	 Desc : inserts a featured artist
        */
	function admin_insertfeaturedartist() {
		$errorMsg = '';
		$artist = '';
                $album_provider_type = '';
                $album_prodid = 0;
                $alb_det = explode('-',$_REQUEST[ 'album' ]);
                if(isset($alb_det[0])){
                    $album_prodid = $alb_det[0];
                }
                if(isset($alb_det[1])){
                    $album_provider_type = $alb_det[1];
                }
		if(isset($_REQUEST[ 'artistName' ])){
			$artist = $_REQUEST[ 'artistName' ];
		} else{
			$artist = $this->data[ 'Artist' ][ 'artist_name' ];
		}
		if(isset($_REQUEST[ 'album' ])){
			$album = $album_prodid;
		} else{
			$album = $this->data[ 'Artist' ][ 'album' ];
		}
		if( $artist == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
			$errorMsg .= 'Please Choose a Territory<br/>';
		}
		if( $album == '' ) {
			$errorMsg .= 'Please select an Album.<br/>';
		}
		$insertArr = array();
		$insertArr[ 'artist_name' ] = $artist;
		$insertArr[ 'album' ] = $album;
		$insertArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
		$insertArr[ 'language' ] = Configure::read('App.LANGUAGE');
                if(isset($album_provider_type)){
                   $insertArr[ 'provider_type' ] = $album_provider_type;
                }
                $insertObj = new Featuredartist();
		if( empty( $errorMsg ) ) {
			if( $insertObj -> insert( $insertArr ) ) {
                            $this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
/*                                
                            $memcache = new Memcache;
                            $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_u_s");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_c_a");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_t");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_n_z");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_a_u");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_e");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_g_b");
                            memcache_close($memcache);
*/
                            Configure::write('Cache.disable',false);
                            Cache::delete(Configure::read('App.memcache_key')."_featured_u_s");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_c_a");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_i_t");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_n_z");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_a_u");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_i_e");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_g_b");
                            Configure::write('Cache.disable',true);
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
                $album_provider_type = '';
                $album_prodid = 0;
		$this->Featuredartist->id = $this -> data[ 'Artist' ][ 'id' ];
                $alb_det = explode('-',$_REQUEST[ 'album' ]);
                if(isset($alb_det[0])){
                    $album_prodid = $alb_det[0];
                }
                if(isset($alb_det[1])){
                    $album_provider_type = $alb_det[1];
                }
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
		if(isset($_REQUEST[ 'album' ])){
			$album = $album_prodid;
		} else{
			$album = $this->data[ 'Artist' ][ 'album' ];
		}
		if( $artist == '' ) {
			$errorMsg .= 'Please select an Artist.<br/>';
		}
		if( $this -> data[ 'Artist' ][ 'territory' ] == '' ) {
			$errorMsg .= 'Please Choose a Territory';
		}
		if( $album == '' ) {
			$errorMsg .= 'Please select an Album.<br/>';
		}
		$updateArr = array();
		$updateArr[ 'id' ] = $this -> data[ 'Artist' ][ 'id' ];
		$updateArr[ 'artist_name' ] = $artist;
		$updateArr[ 'territory' ] = $this -> data[ 'Artist' ][ 'territory' ];
		$updateArr[ 'language' ] = Configure::read('App.LANGUAGE');
		$updateArr[ 'album' ] = $album;
                if(isset($album_provider_type)){
                    $updateArr[ 'provider_type' ] = $album_provider_type;
                }
		$updateObj = new Featuredartist();
		if( empty( $errorMsg ) ) {
			if( $updateObj -> insert( $updateArr ) ){
                            $this -> Session -> setFlash( 'Data has been updated successfully!', 'modal', array( 'class' => 'modal success' ) );
/*                                
                            $memcache = new Memcache;
                            $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_u_s");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_c_a");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_t");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_n_z");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_a_u");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_i_e");
                            memcache_delete($memcache, Configure::read('App.memcache_key')."_featured_g_b");
                            memcache_close($memcache);
 * 
 */
                            Configure::write('Cache.disable',false);    
                            Cache::delete(Configure::read('App.memcache_key')."_featured_u_s");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_c_a");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_i_t");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_n_z");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_a_u");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_i_e");
                            Cache::delete(Configure::read('App.memcache_key')."_featured_g_b");
                            Configure::write('Cache.disable',true);                                
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
    ini_set('memory_limit','1024M');
		set_time_limit(0);
		$errorMsg = '';
                $territories = $this->Territory->find("all");
                for($m=0;$m<count($territories);$m++)
                {
                    $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
                }
                $this->set("territories", $territoriesArray);
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
              Configure::write('Cache.disable',false);
              $cacheKey = 'ssartists_'.$this->data['Artist']['territory'].'_'.Configure::read('App.LANGUAGE');
              if(Cache::delete($cacheKey) == true){
              	Configure::write('Cache.disable',true);
                $this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
              	$this -> redirect( 'manageartist' );
              } else {
                Configure::write('Cache.disable',true);
                $this -> Session -> setFlash( 'Data has been saved successfully, but the cache is not cleared!', 'modal', array( 'class' => 'modal success' ) );
                $this -> redirect( 'manageartist' );
              }
            }
					}
					else {
						$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
					}
				}
				$country = $getData[ 'Artist' ][ 'territory' ];
				//$getArtistDataObj = new Song();
				//$getArtistData = $getArtistDataObj -> getallartistname( $condition, $artistName, $country );
				$getArtistData = array();
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
            $cacheKey = 'ssartists_'.$this->data['Artist']['territory'].'_'.Configure::read('App.LANGUAGE');
            Configure::write('Cache.disable',false);
            if(Cache::delete($cacheKey) == true){
              Configure::write('Cache.disable',true);
              $this -> Session -> setFlash( 'Data has been saved successfully!', 'modal', array( 'class' => 'modal success' ) );
              $this -> redirect( 'manageartist' );
            } else {
              Configure::write('Cache.disable',true);
              $this -> Session -> setFlash( 'Data has been saved successfully, but the cache is not cleared!', 'modal', array( 'class' => 'modal success' ) );
              $this -> redirect( 'manageartist' );
            }
					}
				}
				else {
					$this -> Session -> setFlash( $errorMsg, 'modal', array( 'class' => 'modal problem' ) );
				}
			}
		}
		$memcache = new Memcache;
		$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
		memcache_delete($memcache, Configure::read('App.memcache_key')."_artists");
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
            
            ob_start();
            $deleteArtistIdArray = $this -> data[ 'Info' ];          
            $deleteOption = $this -> data[ 'artist' ]['selectedOpt']; 
            
            //if admin want to remove selected records then
            if($deleteOption == 1){
                if( count($deleteArtistIdArray) > 0 ){                    
                    for( $i=0; $i < count($deleteArtistIdArray); $i++ ){                  
                        $deleteArtistId = $deleteArtistIdArray[$i];                      
                        $deleteObj = new Artist();
                        $data = $this->Artist->find('all', array('conditions' => array('id' => $deleteArtistId)));
                        $fileName = $data[0]['Artist']['artist_image'];
                        $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'artistimg/'.$fileName);
                        $deleteObj -> del( $deleteArtistId );
                    }
                    
                    $this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
                    $this -> redirect( 'manageartist' );
                }                
            }
            
            //if admin want to remove all records then
            if($deleteOption == 2){               
                $deleteObj = new Artist();
                $data = $this->Artist->find('all',array('conditions' => array('language' => Configure::read('App.LANGUAGE'))));
                for( $i=0; $i < count($data); $i++ ){
                    $fileName = $data[$i]['Artist']['artist_image'];
                    $deleteArtistId = $data[$i]['Artist']['id'];
                    $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH').'artistimg/'.$fileName);  
                    $deleteObj -> del( $deleteArtistId );
                }                
                $this -> Session -> setFlash( 'Data deleted successfully!', 'modal', array( 'class' => 'modal success' ) );
                $this -> redirect( 'manageartist' );
            }
            $memcache = new Memcache;
            $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
            memcache_delete($memcache, Configure::read('App.memcache_key')."_artists");
            memcache_close($memcache);           
            
            ob_flush();
            $this -> redirect( 'manageartist' ); 
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
		$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
		memcache_delete($memcache, Configure::read('App.memcache_key')."_newartists");
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
		$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
		memcache_delete($memcache, Configure::read('App.memcache_key')."_newartists");
		memcache_close($memcache);
	}

	/*
	 Function Name : view
	 Desc : For artist view page
	*/
	function view($id=null,$album=null, $provider=null)
	{
		
            
            
            if(count($this -> params['pass']) > 1) {
			$count = count($this -> params['pass']);
			$id = $this -> params['pass'][0];
			for($i=1;$i<$count-1;$i++) {
				if(!is_numeric($this -> params['pass'][$i])) {
				      $id .= "/".$this -> params['pass'][$i];
				}
			}
			if(is_numeric($this -> params['pass'][$count - 2])) {
				$album = $this -> params['pass'][$count - 2];
				$provider = base64_decode($this -> params['pass'][$count - 1]);
			}
			else {
				$album = "";
				$provider = "";
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
			$condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider, 'Album.provider_type = Genre.provider_type');
		}
		else{
			$this->Song->Behaviors->attach('Containable');
			$songs = $this->Song->find('all', array(
				'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
				'conditions' => array('Song.ArtistText' => base64_decode($id) ,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''" ,'Country.Territory' => $country, $cond),'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));

			$val = '';

			foreach($songs as $k => $v){
				$val = $val.$v['Song']['ReferenceID'].",";
				$val_provider_type .= "(" . $v['Song']['ReferenceID'].",'" . $v['Song']['provider_type'] . "')," ;
			}
			$condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").")");
		}
                $id = str_replace('@','/',$id);
		$this->layout = 'home';
		$this->set('artistName',base64_decode($id));
		$this->set('album',$album);
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
						//   array('Album.ArtistText' => base64_decode($id)),
						//	array('Album.provider_type = Genre.provider_type'),
						//	array('Album.provider_type = Country.provider_type'),
						    $condition
						), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
					),
					'fields' => array(
						'Album.ProdID',
						'Album.Title',
						'Album.ArtistText',
						'Album.AlbumTitle',
                        'Album.Advisory',
						'Album.Artist',
						'Album.ArtistURL',
						'Album.Label',
						'Album.Copyright',
						'Album.provider_type'
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
									//array('Song.provider_type = Genre.provider_type'),
									array('Song.provider_type = Country.provider_type'),
									array('Song.DownloadStatus' => 1),
								//	array('Song.TrackBundleCount' => 0),
									array("Song.Sample_FileID != ''"),
									array("Song.FullLength_FIleID != ''"),
									array("Song.provider_type" => $provider),
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
								'Song.FullLength_FIleID',
								'Song.provider_type',
                						'Song.sequence_number'

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

						),'group' => 'Song.ProdID, Song.provider_type','order' => array('Song.sequence_number','Song.ProdID')
						  ));
			}
		}

		// echo "<pre>";
		// print_r($albumSongs);
		// exit;

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
            $this->set("genre", $albumData['0']['Genre']['Genre']);
	}

        
        /*
	 Function Name : album_ajax_view
	 Desc : For artist view page
	*/
	function album_ajax_view($id=null,$album=null, $provider=null)
	{
		
            $this -> layout = 'ajax';
            
            if(count($this -> params['pass']) > 1) {
			$count = count($this -> params['pass']);
			$id = $this -> params['pass'][0];
			for($i=1;$i<$count-1;$i++) {
				if(!is_numeric($this -> params['pass'][$i])) {
				      $id .= "/".$this -> params['pass'][$i];
				}
			}
			if(is_numeric($this -> params['pass'][$count - 2])) {
				$album = $this -> params['pass'][$count - 2];
				$provider = base64_decode($this -> params['pass'][$count - 1]);
			}
			else {
				$album = "";
				$provider = "";
			}
		}

		// echo base64_decode($id) . $album;
		// exit;
		$country = $this->Session->read('territory');
		if($this->Session->read('block') == 'yes') {
			$cond = array('Song.Advisory' => 'F');
		}
		else{
			$cond = "";
		}
		if($album != '') {
			$condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider, 'Album.provider_type = Genre.provider_type');
		}
		else{
			$this->Song->Behaviors->attach('Containable');
			$songs = $this->Song->find('all', array(
				'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
				'conditions' => array('Song.ArtistText' => base64_decode($id) ,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''" ,'Country.Territory' => $country, $cond),'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));

			$val = '';

			foreach($songs as $k => $v){
				$val = $val.$v['Song']['ReferenceID'].",";
				$val_provider_type .= "(" . $v['Song']['ReferenceID'].",'" . $v['Song']['provider_type'] . "')," ;
			}
			$condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").")");
		}
                $id = str_replace('@','/',$id);		
		$this->set('artistName',base64_decode($id));
		$this->set('album',$album);
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
						//   array('Album.ArtistText' => base64_decode($id)),
						//	array('Album.provider_type = Genre.provider_type'),
						//	array('Album.provider_type = Country.provider_type'),
						    $condition
						), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
					),
					'fields' => array(
						'Album.ProdID',
						'Album.Title',
						'Album.ArtistText',
						'Album.AlbumTitle',
                        'Album.Advisory',
						'Album.Artist',
						'Album.ArtistURL',
						'Album.Label',
						'Album.Copyright',
						'Album.provider_type'
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
									//array('Song.provider_type = Genre.provider_type'),
									array('Song.provider_type = Country.provider_type'),
									array('Song.DownloadStatus' => 1),
								//	array('Song.TrackBundleCount' => 0),
									array("Song.Sample_FileID != ''"),
									array("Song.FullLength_FIleID != ''"),
									array("Song.provider_type" => $provider),
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
								'Song.FullLength_FIleID',
								'Song.provider_type',
                						'Song.sequence_number'

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

						),'group' => 'Song.ProdID, Song.provider_type','order' => array('Song.sequence_number','Song.ProdID')
						  ));
			}
		}

		// echo "<pre>";
		// print_r($albumSongs);
		// exit;

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
            $this->set("genre", $albumData['0']['Genre']['Genre']);
	}
        
	function album($id=null,$album=null,$provider=null)
	{
            if(count($this -> params['pass']) > 1) {
                    $count = count($this -> params['pass']);
                    $id = $this -> params['pass'][0];
                    for($i=1;$i<$count-1;$i++) {
                            if(!is_numeric($this -> params['pass'][$i])) {
                                    $id .= "/".$this -> params['pass'][$i];
                            }
                    }

            }

            $country = $this->Session->read('territory');
            if($this->Session->read('block') == 'yes') {
                    $cond = array('Song.Advisory' => 'F');
            }
            else{
                    $cond = "";
            }


            $id = str_replace('@','/',$id);
            $this->Song->Behaviors->attach('Containable');
            $songs = $this->Song->find('all', array(
                    'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
                    'conditions' => array('Song.ArtistText' => base64_decode($id) ,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''" ,'Country.Territory' => $country, $cond, 'Song.provider_type = Country.provider_type'),'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'order'=>array('Song.provider_type DESC')));

            $val = '';
            $val_provider_type = '';

            foreach($songs as $k => $v){
                    $val .= $v['Song']['ReferenceID'].",";
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'].",'" . $v['Song']['provider_type'] . "')," ;
            }


            $condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").") AND Album.provider_type = Genre.provider_type");

            $this->layout = 'home';
            $this->set('artisttext',base64_decode($id));
            $this->set('genre',base64_decode($album));
            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
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
                                                //array('Album.ArtistText' => base64_decode($id)),
                                                    //array('Album.provider_type = Genre.provider_type'),
                                                    //array('Album.provider_type = Country.provider_type'),
                                                $condition
                                            ), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
                                    ),
                                    'fields' => array(
                                            'Album.ProdID',
                                            'Album.Title',
                                            'Album.ArtistText',
                                            'Album.AlbumTitle',
                    'Album.Advisory',
                                            'Album.Artist',
                                            'Album.ArtistURL',
                                            'Album.Label',
                                            'Album.Copyright',
                                            'Album.provider_type'
                                            ),
                                    'contain' => array(
                                            'Genre' => array(
                                                    'fields' => array(
                                                            'Genre.Genre'
                                                            )
                                                    ),                                            
                                            'Files' => array(
                                                    'fields' => array(
                                                            'Files.CdnPath' ,
                                                            'Files.SaveAsName',
                                                            'Files.SourceURL'
                                                    ),                                                
                                            )
                                    ), 'order' => array('Album.provider_type'=>'desc'), 'cache' => 'yes', 'chk' => 2
                            );
            if($this->Session->read('block') == 'yes') {
                    $cond = array('Song.Advisory' => 'F');
            }else{
                    $cond = "";
            }
            $this->Album->recursive = 2;
            $albumData = array();
            $albumData = $this->paginate('Album'); //getting the Albums for the artist
            //$this->set('count_albums',count($albumData));        
            $albumSongs = array();
            $this->set('albumData', $albumData);
            if(isset($albumData[0]['Album']['Artist'])) {
                $this->set('artisttitle',$albumData[0]['Album']['Artist']);
            }            
            if(isset($albumData[0]['Song']['ArtistURL'])) {
                $this->set('artistUrl',$albumData[0]['Song']['ArtistURL']);
            }else {
                $this->set('artistUrl', "N/A");
            }
            echo "<br>Query2: ".$this->Album->lastQuery();
            $decodedId = trim(base64_decode($id));
                 $country = $this->Session->read('territory');
                 if(!empty($country)){
                 if ( ((Cache::read("videolist_".$decodedId)) === false)  || (Cache::read("videolist_".$decodedId) === null) ) { 
                 $countryPrefix = $this->Session->read('multiple_countries');                 
                 $sql_us_10_v =<<<STR
                SELECT 
                                Video.ProdID,
                                Video.ReferenceID,
                                Video.Title,
                                Video.ArtistText,
                                Video.DownloadStatus,
                                Video.VideoTitle,
                                Video.Artist,
                                Video.Advisory,
                                Video.Sample_Duration,
                                Video.FullLength_Duration,
                                Video.provider_type,
                                Video.video_label,
                                Genre.Genre,
                                Country.Territory,
                                Country.SalesDate,
                                Full_Files.CdnPath,
                                Full_Files.SaveAsName,
                                Full_Files.FileID,
                                Image_Files.FileID,
                                Image_Files.CdnPath,
                                Image_Files.SourceURL,
                                PRODUCT.pid
                FROM
                                video AS Video
                                                LEFT JOIN
                                File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
                                                LEFT JOIN
                                Genre AS Genre ON (Genre.ProdID = Video.ProdID)
                                                LEFT JOIN
         {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type)
                                                LEFT JOIN
                                PRODUCT ON (PRODUCT.ProdID = Video.ProdID)
                LEFT JOIN
                                File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
                WHERE
                                ( (Video.DownloadStatus = '1') AND ((Video.ArtistText) IN ('$decodedId')) AND (Video.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Video.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
                GROUP BY Video.ProdID
                ORDER BY FIELD(Video.ProdID, '$decodedId') ASC
                LIMIT 10 
                  
STR;
         
                    //echo $sql_national_100_v; die;
                    $artistVideoList = $this->Video->query($sql_us_10_v);
                    foreach($artistVideoList as $key => $value){
                        $albumArtwork = shell_exec('perl files/tokengen_artwork ' .$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                        $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                        $artistVideoList[$key]['videoAlbumImage'] = $videoAlbumImage;
                    }               
                    Cache::write("videolist_".$decodedId, $artistVideoList);
                    }else{
                        $artistVideoList = Cache::read("videolist_".$decodedId);
                    }
                    $this->set('artistVideoList',$artistVideoList);
                    
                    
                 }
	}
        
        function album_ajax($id=null,$album=null,$provider=null)
	{
            Configure::write('debug', 0);	
            $this->layout = false;
            if(count($this -> params['pass']) > 1) {
                    $count = count($this -> params['pass']);
                    $id = $this -> params['pass'][0];
                    for($i=1;$i<$count-1;$i++) {
                            if(!is_numeric($this -> params['pass'][$i])) {
                                    $id .= "/".$this -> params['pass'][$i];
                            }
                    }

            }

            $country = $this->Session->read('territory');
            if($this->Session->read('block') == 'yes') {
                    $cond = array('Song.Advisory' => 'F');
            }
            else{
                    $cond = "";
            }

			
            $id = str_replace('@','/',$id);
            $this->Song->Behaviors->attach('Containable');
            $songs = $this->Song->find('all', array(
                    'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
                    'conditions' => array('Song.ArtistText' => base64_decode($id) ,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''" ,'Country.Territory' => $country, $cond, 'Song.provider_type = Country.provider_type'),'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'order'=>array('Song.provider_type DESC')));

            $val = '';
            $val_provider_type = '';           
            
            foreach($songs as $k => $v){
                    $val .= $v['Song']['ReferenceID'].",";
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'].",'" . $v['Song']['provider_type'] . "')," ;
            }


            $condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").") AND Album.provider_type = Genre.provider_type");
                   
          
            $this->set('artisttext',base64_decode($id));
            $this->set('genre',base64_decode($album));
            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
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
                                                    //array('Album.provider_type = Country.provider_type'),
                                                $condition
                                            ), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
                                    ),
                                    'fields' => array(
                                            'Album.ProdID',
                                            'Album.Title',
                                            'Album.ArtistText',
                                            'Album.AlbumTitle',
                    'Album.Advisory',
                                            'Album.Artist',
                                            'Album.ArtistURL',
                                            'Album.Label',
                                            'Album.Copyright',
                                            'Album.provider_type'
                                            ),
                                    'contain' => array(
                                            'Genre' => array(
                                                    'fields' => array(
                                                            'Genre.Genre'
                                                            )
                                                    ),
                                         
                                            'Files' => array(
                                                    'fields' => array(
                                                            'Files.CdnPath' ,
                                                            'Files.SaveAsName',
                                                            'Files.SourceURL'
                                                    ),
                                            )
                                    ), 'order' => array('Album.provider_type'=>'desc'), 'limit' => '100','cache' => 'yes', 'chk' => 2
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
    

            
            $htmlContain ='<div class="album-list-shadow-container"><h3>Album</h3>
				<div class="album-list">';
            if(count($albumData) > 0 ){
                    foreach($albumData as $album_key => $album){             

                        
                        //hide song if library block the explicit content
                        if(($this->Session->read('block') == 'yes') && ($album['Album']['Advisory'] =='T')) {
                            continue;
                        } 




                        //get the album image
                        if(empty($album['Files']['CdnPath'])){
                            if(empty($album['Files']['SourceURL'])){
                            // mail(Configure::read('TO'),"Album Artwork","CdnPath and SourceURL missing for Album ".$album['Album']['AlbumTitle']." ProdID ".$album['Album']['ProdID']." Provider Type : ".$album['Album']['provider_type']." is missing",Configure::read('HEADERS'));
                            } else {
                            // mail(Configure::read('TO'),"Album Artwork","CdnPath missing for Album ".$album['Album']['AlbumTitle']." ProdID ".$album['Album']['ProdID']." Provider Type : ".$album['Album']['provider_type']." ProdID ".$album['Album']['provider_type']." is missing",Configure::read('HEADERS'));
                            }
                        }

                        $albumArtwork = shell_exec('perl files/tokengen ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']);   

                        //get the album title
                        $title_album_on_hover = $album['Album']['AlbumTitle'];
                        if(strlen($album['Album']['AlbumTitle']) >= 40){
                            $album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 40). '...';
                        }


                        $copyrightString='';				
                        if($album['Album']['Advisory'] == 'T'){
                                $copyrightString .='<font class="explicit"> (Explicit)</font>';                    
                        }


                        if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {

                            $album['Album']['Copyright'] = '( '.substr($album['Album']['Copyright'], 0, 5).' )';                    
                            $copyrightString .= $album['Album']['Copyright'];
                        }




                    //created the album url 
                    $albumURL = "artists/album_ajax_view/".base64_encode($album['Album']['ArtistText'])."/".$album['Album']['ProdID']."/".base64_encode($album['Album']['provider_type']);

                    $album['Album']['AlbumTitle'] = @iconv(mb_detect_encoding($album['Album']['AlbumTitle']), "WINDOWS-1252//IGNORE", $album['Album']['AlbumTitle']);
                    $album['Album']['AlbumTitle'] = @iconv(mb_detect_encoding($album['Album']['AlbumTitle']), "UTF-8//IGNORE", $album['Album']['AlbumTitle']);
                    $title_album_on_hover = @iconv(mb_detect_encoding($title_album_on_hover), "WINDOWS-1252//IGNORE", $title_album_on_hover);
                    $title_album_on_hover = @iconv(mb_detect_encoding($title_album_on_hover), "UTF-8//IGNORE", $title_album_on_hover);

                    $htmlContain .= '<div class="album-overview-container">
                                        <div class="album-image selected">
                                                <a href="javascript:void(0);" onclick="showAlbumDetails(\''.$albumURL.'\')"><img src="'. Configure::read('App.Music_Path').$albumArtwork.'" alt="album-cover-small" width="59" height="59" /></a>
                                        </div>
                                        <div class="album-title">
                                                <a href="javascript:void(0);" title="'.$title_album_on_hover.'" onclick="showAlbumDetails(\''.$albumURL.'\')">'.$album['Album']['AlbumTitle'].'</a>
                                        </div>
                                        <div class="album-year">
                                                <a href="javascript:void(0);" onclick="showAlbumDetails(\''.$albumURL.'\')">'.$copyrightString.'</a>
                                        </div>
                                </div>';   

                    }  
              }else{
                    $htmlContain .= '<div style="color:#000000;font-weight:bold;padding-left:10px;">No Results Found</div>';
              }
             $htmlContain .= '</div></div>';
             
             echo $htmlContain; 

             exit;
	}


	/*
	 Function Name : view
	 Desc : For artist view page
	*/
	function admin_getArtists(){
                Configure::write('debug', 0);
		$this->Song->recursive = 0;
		$this->Song->unbindModel(array('hasOne' => array('Participant')));
                $this->Song->unbindModel(array('hasOne' => array('Genre')));
                $this->Song->unbindModel(array('hasOne' => array('Country')));
                $this->Song->unbindModel(array('belongsTo' => array('Sample_Files')));
                $this->Song->unbindModel(array('belongsTo' => array('Full_Files')));
                $artist = $this->Song->find('all',array(
							'conditions' =>
								array('and' =>
									array(
										array("find_in_set('".'"'.$_REQUEST['Territory'].'"'."',Song.Territory)",'Song.provider_type' => 'sony')
                  )
								),
							'fields' => array(
									'DISTINCT Song.ArtistText',
									),
							'order' => 'Song.ArtistText'
						));
		$data = "<option value=''>SELECT</option>";
		foreach($artist as $k=>$v){
			$data = $data."<option value='".$v['Song']['ArtistText']."'>".$v['Song']['ArtistText']."</option>";
		}
		print "<select class='select_fields' name='artistName' onchange='getAlbum()', id='artistName'>".$data."</select>";exit;

	}
        
 /**
   *@getAlbums
   *  return top 5 artist names with ajax call
   *
   *$name
   *  string to be searchedin atrist name
   *
   *@return
   *  
   **/
        
        
	function admin_getAlbums(){
            Configure::write('debug', 0);
		$result = array();
		$allAlbum = $this->Album->find('all', array('fields' => array('Album.ProdID','Album.AlbumTitle','Album.provider_type'),'conditions' => array('Album.ArtistText = ' => urldecode($_REQUEST['artist'])), 'recursive' => -1));
                $val = '';
		$this->Song->Behaviors->attach('Containable');
                $countryPrefix = strtolower($_REQUEST['Territory'])."_";
                $this->Country->setTablePrefix($countryPrefix);
		foreach($allAlbum as $k => $v){
                    $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'),'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'],'Song.DownloadStatus' => 1,'TrackBundleCount' => 0,'Country.Territory' => $_REQUEST['Territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0,'limit' => 1));
                    if(count($recordCount) > 0){
                        $val = $val.$v['Album']['ProdID'].",";
                        $result[$v['Album']['ProdID'] . '-'. $v['Album']['provider_type']] = $v['Album']['AlbumTitle'];
                    }
                }
                $data = "<option value=''>SELECT</option>";
		foreach($result as $k=>$v){
			$data = $data."<option value='".$k."'>".$v."</option>";
		}
		print "<select class='select_fields' id='album' name='album'>".$data."</select>";exit;

	}
  
  
  /**
   *@getAutoArtist
   *  return top 5 artist names with ajax call
   *
   *$name
   *  string to be searchedin atrist name
   *
   *@return
   *  
   **/
   
  function admin_getAutoArtist() {
    
    $artist = $this->Song->find('all',array(
							'conditions' =>
								array('and' =>
									array(
										array(
                      "(find_in_set('".'"'.$_REQUEST['Territory'].'"'."',Song.Territory) or Song.Territory = '".'"'.$_REQUEST['Territory'].'"'."' )",
                      'Song.provider_type' => 'sony',
                      'Song.ArtistText LIKE' => $_REQUEST['Name']."%",
                      'Song.downloadstatus' => '1'
                    )
                  )
								),
							'fields' => array(
									'DISTINCT Song.ArtistText',
									),
              'recursive' => -1,
              'limit' => '0,20',
							'order' => 'Song.ArtistText'
						));
    
    
    $html = '<ul style="max-height: 180px; overflow: auto;">';
    if(!empty($artist)){
      
      
      foreach($artist AS $key => $val){
        $html .= '<li>' . $val['Song']['ArtistText'] . '</li>'; 
      }
      
    }else{
      $html .= '<li>No record found</li>';
    }    
    $html .= '</ul>';
    
   
    
    print $html; exit;
  }
  
}

?>
