<?php

/*
  File Name : artists_controller.php
  File Description : Artist controller page
  Author : m68interactive
 */

Class ArtistsController extends AppController {

	var $name 		= 'Artists';
	var $uses		= array('Featuredartist', 'Artist', 'Newartist', 'Album', 'Song', 'Download', 'Video', 'Territory', 'Token', 'TopAlbum','TopSingles' ,'QueueList' , 'QueueDetail');
	var $layout 	= 'admin';
	var $helpers 	= array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'Language', 'Album', 'Song', 'Mvideo', 'Videodownload', 'Queue', 'Paginator', 'WishlistVideo', 'Genre', 'Token');
	var $components = array('Session', 'Auth', 'Downloads', 'CdnUpload', 'Streaming', 'Common','Solr', 'RequestHandler');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array( 'view', 'test', 'album', 'load_albums', 'album_ajax', 'album_ajax_view', 'admin_getAlbums', 'admin_getAutoArtist', 'getAlbumSongs', 'getAlbumData', 'getNationalAlbumData', 'getSongStreamUrl', 'featuredAjaxListing', 'composer','newAlbum', 'new_view', 'getFeaturedSongs','admin_getSongs') ;

		if(($this->Session->read('Auth.User.type_id')) && (($this->Session->read('Auth.User.type_id') == 1))){
                    $this->Auth->allow('admin_managetopalbums','admin_deletePlaylist','admin_addPlaylist','admin_managePlaylist','admin_addPlaylist','admin_insertplaylist','admin_getAlbumStreamSongs','admin_getAlbumsForDefaultQueues', 'admin_getPlaylistAutoArtist', 'admin_topalbumform','admin_inserttopalbum','admin_updatetopalbum','admin_topalbumdelete','admin_managetopsingles','admin_topsingleform','admin_inserttopsingle','admin_updatetopsingle','admin_topsingledelete');
                }
    }


	/*
      Function Name : manageTopsingles
      Desc : action for listing all the top albums
     */

    function admin_managetopsingles() {
		$userTypeId = $this->Session->read('Auth.User.type_id');
        $topSingles = $this->paginate( 'TopSingles', array( 'prod_id != ""' ) );
        $this->set( 'topSingles', $topSingles );
		$this->set('userTypeId',$userTypeId);
    }

	/*
      Function Name : admin_topsingleform
      Desc : action for displaying the add/edit featured artist form
     */

    function admin_topsingleform() {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $this->set("territories", $territoriesArray);
        if (!empty($this->params['named'])) { //gets the values from the url in form  of array
            $artistId = $this->params['named']['id'];
            if (trim($artistId) != '' && is_numeric($artistId)) {
                $this->set('formAction', 'admin_updatetopsingle/id:' . $artistId);
                $this->set('formHeader', 'Edit Top Single');
                $getTopSingleDataObj = new TopSingles();
                $getData = $getTopSingleDataObj->getartistdata($artistId);
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = $getData['TopSingles']['artist_name'];
                $country = $getData['TopSingles']['territory'];

                $getArtistData = array();
                $this->set('getArtistData', $getArtistData);
                $result = array();
                $allAlbum = $this->Album->find('all', array(
                    'fields' => array('Album.ProdID', 'Album.AlbumTitle'),
                    'conditions' => array('Album.ArtistText' => $getData['TopSingles']['artist_name'], 'Album.provider_type' => $getData['TopSingles']['provider_type']),
                    'recursive' => -1
                ));

                $val = '';
                $this->Song->Behaviors->attach('Containable');
                foreach ($allAlbum as $k => $v) {
                    $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'), 'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'], 'Song.DownloadStatus' => 1, 'TrackBundleCount' => 0, 'Country.Territory' => $getData['TopSingles']['territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
                    if (count($recordCount) > 0) {
                        $result[$v['Album']['ProdID']] = $v['Album']['AlbumTitle'];
                    }
                }
                $this->set('album', $result);
            }
        } else {
            $this->set('formAction', 'admin_inserttopsingle');
            $this->set('formHeader', 'Add Top Single');
            $getTopSingleDataObj = new TopSingles();
            $topSingletData = $getTopSingleDataObj->getallartists();
            $condition = 'add';
            $artistName = '';
        }

        
    }

	/*
      Function Name : admin_inserttopsingle
      Desc : inserts a featured artist
     */

    function admin_inserttopsingle() {
    	
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $errorMsg = '';
        $artist = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
	if (isset($this->params[$index]['songProdID'])) {
            $songID = $this->params[$index]['songProdID'];
	}
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory<br/>';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
        if ($songID == '') {
            $errorMsg .= 'Please select Song.<br/>';
        }
        if($album_provider_type == '') {
            $errorMsg .= 'Please select another album as this albums provider type is empty.<br/>';
        }
		$territory = $this->data['Artist']['territory'];
        $insertArr = array();
        $insertArr['artist_name'] = $artist;
        $insertArr['album'] = $album;
        $insertArr['territory'] = $this->data['Artist']['territory'];
        $insertArr['language'] = Configure::read('App.LANGUAGE');
	$insertArr['prod_id'] = $songID;
        if (!empty($album_provider_type)) {
            $insertArr['provider_type'] = $album_provider_type;
        }
        $insertObj = new TopSingles();
        if (empty($errorMsg)) {
            if ($insertObj->insert($insertArr)) {
                $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                Configure::write('Cache.disable', false);
                $this->Common->getTopSingles($territory);
                $this->redirect('managetopsingles');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('topsingleform');
        }
    }

	/*
      Function Name : admin_updatetopsingle
      Desc : Updates a featured artist
     */

    function admin_updatetopsingle() {
    	
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $errorMsg = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        $artistName = '';
        if (isset($this->params[$index]['artistName'])) {
            $artistName = $this->params[$index]['artistName'];
        }
        $artist = '';
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
        if (isset($this->params[$index]['songProdID'])) {
            $songID = $this->params[$index]['songProdID'];
        }
        if ($songID == '') {
            $errorMsg .= 'Please select Song.<br/>';
        }
        if($album_provider_type == '') {
            $errorMsg .= 'Please select another album as this albums provider type is empty.<br/>';
        }        
		$territory = $this->data['Artist']['territory'];
        $updateArr = array();
        $updateArr['id'] = $this->data['Artist']['id'];
        $updateArr['artist_name'] = $artist;
        $updateArr['territory'] = $this->data['Artist']['territory'];
        $updateArr['language'] = Configure::read('App.LANGUAGE');
        $updateArr['album'] = $album;
        $updateArr['prod_id'] = $songID;
        if (!empty($album_provider_type)) {
            $updateArr['provider_type'] = $album_provider_type;
        }
        $updateObj = new TopAlbum();
        if (empty($errorMsg)) {
            if ($updateObj->insert($updateArr)) {
                $this->Session->setFlash('Data has been updated successfully!', 'modal', array('class' => 'modal success'));    
                Configure::write('Cache.disable', false);                
		$this->Common->getTopSingles($territory);
                $this->redirect('managetopsingles');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('managetopsingles');
        }
    }

	/*
      Function Name : admin_topsingledelete
      Desc : For deleting a featured artist
     */

    function admin_topsingledelete() {
        $deleteTopSingleId = $this->params['named']['id'];
        $deleteObj = new TopSingles();
		$getData = $deleteObj->gettopsingledata($deleteTopSingleId);
		$territory = $getData['TopSingles']['territory'];
        if ($deleteObj->del($deleteTopSingleId)) {
			Configure::write('Cache.disable', false);
			$this->Common->getTopSingles($territory);
            $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('managetopsingles');
        } else {
            $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
            $this->redirect('managetopsingles');
        }
    }

    /*
      Function Name : manageTopAlbums
      Desc : action for listing all the top albums
     */

    function admin_managetopalbums() {
	$userTypeId = $this->Session->read('Auth.User.type_id');
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $territory = 'US';
        $topAlbumsList = $this->TopAlbum->getTopAlbumsList($territory);
        $this->set( 'topAlbums', $topAlbumsList );
	$this->set('userTypeId',$userTypeId);
        $this->set('default_territory',$territory);
        $this->set("territories", $territoriesArray); 
    }

	/*
      Function Name : admin_topalbumform
      Desc : action for displaying the add/edit featured artist form
     */

    function admin_topalbumform() {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $this->set("territories", $territoriesArray);
        if (!empty($this->params['named'])) { //gets the values from the url in form  of array
            $artistId = $this->params['named']['id'];
            if (trim($artistId) != '' && is_numeric($artistId)) {
                $this->set('formAction', 'admin_updatetopalbum/id:' . $artistId);
                $this->set('formHeader', 'Edit Top Album');
                $getTopAlbumDataObj = new TopAlbum();
                $getData = $getTopAlbumDataObj->getartistdata($artistId);
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = $getData['TopAlbum']['artist_name'];
                $country = $getData['TopAlbum']['territory'];

                $getArtistData = array();
                $this->set('getArtistData', $getArtistData);
                $result = array();
                $allAlbum = $this->Album->find('all', array(
                    'fields' => array('Album.ProdID', 'Album.AlbumTitle'),
                    'conditions' => array('Album.ArtistText' => $getData['TopAlbum']['artist_name'], 'Album.provider_type' => $getData['TopAlbum']['provider_type']),
                    'recursive' => -1
                ));

                $val = '';
                $this->Song->Behaviors->attach('Containable');
                foreach ($allAlbum as $k => $v) {
                    $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'), 'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'], 'Song.DownloadStatus' => 1, 'TrackBundleCount' => 0, 'Country.Territory' => $getData['topAlbum']['territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
                    if (count($recordCount) > 0) {
                        $result[$v['Album']['ProdID']] = $v['Album']['AlbumTitle'];
                    }
                }
                $this->set('album', $result);
            }
        } else {
            $this->set('formAction', 'admin_inserttopalbum');
            $this->set('formHeader', 'Add Top Album');
            $getTopAlbumDataObj = new TopAlbum();
            $topAlbumtData = $getTopAlbumDataObj->getallartists();
            $condition = 'add';
            $artistName = '';
        }

        
    }

	/*
      Function Name : admin_insertfeaturedartist
      Desc : inserts a featured artist
     */

    function admin_inserttopalbum() {
    	
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $errorMsg = '';
        $artist = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory<br/>';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
		$territory = $this->data['Artist']['territory'];
        $insertArr = array();
        $insertArr['artist_name'] = $artist;
        $insertArr['album'] = $album;
        $insertArr['territory'] = $this->data['Artist']['territory'];
        $insertArr['language'] = Configure::read('App.LANGUAGE');
        if (!empty($album_provider_type)) {
            $insertArr['provider_type'] = $album_provider_type;
        }
        $insertObj = new TopAlbum();
        if (empty($errorMsg)) {
            if ($insertObj->insert($insertArr)) {
                $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                Configure::write('Cache.disable', false);
                $this->Common->getTopAlbums($territory);
                $this->redirect('managetopalbums');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('topalbumform');
        }
    }

	/*
      Function Name : admin_updatefeaturedartist
      Desc : Updates a featured artist
     */

    function admin_updatetopalbum() {
    	
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $errorMsg = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $this->Featuredartist->id = $this->data['Artist']['id'];
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        $artistName = '';
        if (isset($this->params[$index]['artistName'])) {
            $artistName = $this->params[$index]['artistName'];
        }
        $artist = '';
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
		$territory = $this->data['Artist']['territory'];
        $updateArr = array();
        $updateArr['id'] = $this->data['Artist']['id'];
        $updateArr['artist_name'] = $artist;
        $updateArr['territory'] = $this->data['Artist']['territory'];
        $updateArr['language'] = Configure::read('App.LANGUAGE');
        $updateArr['album'] = $album;
        if (!empty($album_provider_type)) {
            $updateArr['provider_type'] = $album_provider_type;
        }
        $updateObj = new TopAlbum();
        if (empty($errorMsg)) {
            if ($updateObj->insert($updateArr)) {
                $this->Session->setFlash('Data has been updated successfully!', 'modal', array('class' => 'modal success'));    
                Configure::write('Cache.disable', false);                
				$this->Common->getTopAlbums($territory);
                $this->redirect('managetopalbums');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('managetopalbums');
        }
    }

	/*
      Function Name : admin_delete
      Desc : For deleting a featured artist
     */

    function admin_topalbumdelete() {
        $deleteArtistUserId = $this->params['named']['id'];
        $deleteObj = new TopAlbum();
		$getData = $deleteObj->getartistdata($deleteArtistUserId);
		$territory = $getData['TopAlbum']['territory'];
        if ($deleteObj->del($deleteArtistUserId)) {
			Configure::write('Cache.disable', false);
			$this->Common->getTopAlbums($territory);
            $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('managetopalbums');
        } else {
            $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
            $this->redirect('managetopalbums');
        }
    }

    /*
      Function Name : managefeaturedartist
      Desc : action for listing all the featured artists
     */

    function admin_managefeaturedartist() {
        $artists = $this->paginate( 'Featuredartist', array( 'album != ""', 'language' => Configure::read( 'App.LANGUAGE' ) ) );
        $this->set( 'artists', $artists );
    }
    
    /**
     * function name : admin_addplaylist
     * Description   : This is used to add default playlists
     */
    
    function admin_addplaylist() { 
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $this->layout = 'admin';
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $this->set("territories", $territoriesArray);
        if (!empty($this->params['named']['id'])) { //gets the values from the url in form  of array
            $queueId = $this->params['named']['id'];
            if (trim($queueId) != '' && is_numeric($queueId)) {
                $this->set('formAction', 'admin_insertplaylist/id:' . $queueId);
                $this->set('formHeader', 'Edit Play list');
                $queueName = $this->QueueList->find('first', array('fields' => array('queue_name'),'conditions' => array('queue_id' => $queueId)));
                $getData = $this->QueueDetail->find('all',
                                        array('fields' => array('Songs.SongTitle', 'Songs.ArtistText', 'Songs.ProdId','Songs.provider_type','Albums.ProdID as ALbumId','Albums.AlbumTitle'),
                                              'group' => array('Songs.ProdID', 'Songs.provider_type'),
                                              'joins' => array(
                                                              array(
                                                                              'type' => 'INNER',
                                                                              'table' => 'Songs',
                                                                              'alias' => 'Songs',
                                                                              'foreignKey' => false,
                                                                              'conditions' => array('QueueDetail.song_prodid=Songs.ProdID', 'QueueDetail.song_providertype=Songs.provider_type'),
                                                              ),
                                                              array(
                                                                              'type' => 'INNER',
                                                                              'table' => 'Albums',
                                                                              'alias' => 'Albums',
                                                                              'foreignKey' => false,
                                                                              'conditions' => array('Albums.ProdID = Songs.ReferenceID', 'Albums.provider_type = Songs.provider_type'),
                                                              ),
                                                         ),
                                              'conditions' => array('QueueDetail.queue_id' => $queueId)
                                            )
                                       );
                $queue_name = $queueName['QueueList']['queue_name'];
                $this->set('queue_name' , $queue_name);
                $this->set('getData', $getData);
                $this->set('queueId',$queueId);
                $condition = 'edit';
            }
        } else {
            $this->set('formAction', 'admin_insertplaylist');
            $this->set('formHeader', 'Add Playlist');
            $condition = 'add';
        }        
    }
    
    function admin_insertplaylist() {
        $songsList = $this->params['data']['Info'];
        if(!empty($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
            $playlistSongs = $this->QueueDetail->find('all',array('fields' => array('id','song_prodid','song_providertype','album_prodid'),'conditions' => array('queue_id' => $this->params['named']['id'])));
            $queueData = $this->QueueList->find('first', array('fields' => array('queue_name'),'conditions' => array('queue_id' => $this->params['named']['id'])));
            $queueName = $this->params['data']['Artist']['queue_name'];
            if(trim($queueName) != trim($queueData['QueueList']['queue_name'])) {
                $update = array('queue_id' => $this->params['named']['id'], 'queue_name' => trim($queueName));
                $this->QueueList->setDataSource('master');
                $this->QueueList->save($update);
                $this->QueueList->setDataSource('default');
            }
            if(empty($playlistSongs)) {
                if(!empty($songsList)) {
                    $detailArray = array();
                    foreach($songsList as $value) {
                        $data = explode('-',$value);
                        $detailArray[] = array('queue_id' => $this->params['named']['id'],'song_prodid' => $data[2],'song_providertype' => $data[1] , 'album_prodid' => $data[0], 'album_providertype' => $data[1]);
                    } 
                    $this->QueueDetail->setDataSource('master');
                    if($this->QueueDetail->saveAll($detailArray)) {
                        $this->QueueDetail->setDataSource('default');
                        $this->Common->refreshQueueSongs($this->params['named']['id']);
                        $this->Session->setFlash('Songs updated successfully in playlist!', 'modal', array('class' => 'modal success'));
                        $this->redirect('addplaylist/id:'.$this->params['named']['id']);                
                    } else {
                        $this->QueueDetail->setDataSource('default');
                        $this->Session->setFlash('Error occured while updating songs in playlist', 'modal', array('class' => 'modal problem'));
                        $this->redirect('addplaylist/id:'.$this->params['named']['id']);                    
                    }
                } else {
                    $this->Session->setFlash('There are no songs to save in the playlist', 'modal', array('class' => 'modal problem'));
                    $this->redirect('addplaylist/id:'.$this->params['named']['id']);                    
                }
            } else {
                if(empty($songsList)) {
                    $this->QueueDetail->setDataSource('master');
                    $this->QueueDetail->deleteAll(array('queue_id' => $this->params['named']['id']));
                    $this->QueueDetail->setDataSource('default');
                    $this->Common->refreshQueueSongs($this->params['named']['id']);
                    $this->Session->setFlash('Songs deleted successfully from playlist!', 'modal', array('class' => 'modal success'));
                    $this->redirect('addplaylist/id:'.$this->params['named']['id']);                    
                }
                $songsInDB = array();
                foreach($playlistSongs as $id => $val) {
                    $songsInDB[$val['QueueDetail']['id']] = trim($val['QueueDetail']['album_prodid']).'-'.trim($val['QueueDetail']['song_providertype']).'-'.trim($val['QueueDetail']['song_prodid']);
                }
                $songToAdd = array();
                foreach($songsList as $key => $value) {
                    if(!in_array($value,$songsInDB)) {
                        $songToAdd[] = $songsList[$key]; 
                    }
                }
                $songToDel = array();
                foreach($songsInDB as $k => $v) {
                    if(!in_array($v,$songsList)) {
                        $songToDel[] = $k;
                    }
                }
                
                if(!empty($songToDel)) {
                    $this->QueueDetail->setDataSource('master');
                    $this->QueueDetail->deleteAll(array('id' => $songToDel));
                    $this->QueueDetail->setDataSource('default');
                    $this->Common->refreshQueueSongs($this->params['named']['id']);
                    if(empty($songToAdd)) {
                        $this->Session->setFlash('Songs deleted successfully from playlist!', 'modal', array('class' => 'modal success'));
                        $this->redirect('addplaylist/id:'.$this->params['named']['id']);                         
                    }
                }
                if(!empty($songToAdd)) {
                    foreach($songToAdd as $value) {
                        $data = explode('-',$value);
                        $detailArray[] = array('queue_id' => $this->params['named']['id'],'song_prodid' => $data[2],'song_providertype' => $data[1] , 'album_prodid' => $data[0], 'album_providertype' => $data[1]);
                    }                 
                    $this->QueueDetail->setDataSource('master');
                    if($this->QueueDetail->saveAll($detailArray)) {
                        $this->QueueDetail->setDataSource('default');
                        $this->Common->refreshQueueSongs($this->params['named']['id']);
                        $this->Session->setFlash('Songs updated successfully in playlist!', 'modal', array('class' => 'modal success'));
                        $this->redirect('addplaylist/id:'.$this->params['named']['id']);                
                    } else {
                        $this->QueueDetail->setDataSource('default');
                        $this->Session->setFlash('Error occured while updating songs in playlist', 'modal', array('class' => 'modal problem'));
                        $this->redirect('addplaylist/id:'.$this->params['named']['id']);                    
                    }
                } else {
                    $this->Session->setFlash('There are no changes to be updated in playlist!', 'modal', array('class' => 'modal success'));
                    $this->redirect('addplaylist/id:'.$this->params['named']['id']);                    
                }
            }
            
        } else {
            $queueName = $this->params['data']['Artist']['queue_name'];
            $patronId = $this->Session->read('Auth.User.id');
            $this->data['QueueList']['queue_name'] = trim($queueName);
            $this->data['QueueList']['created'] = date('Y-m-d H:i:s');
            $this->data['QueueList']['patron_id'] = $patronId;
            $this->data['QueueList']['queue_type'] = 1;
            $this->QueueList->setDataSource('master');
            if ($this->QueueList->save($this->data['QueueList'])) {
                $this->QueueList->setDataSource('default');
                $this->Common->setAdminDefaultQueuesCache();
                $queueId = $this->QueueList->getLastInsertID();
                $detailArray = array();
                foreach($songsList as $value) {
                    $data = explode('-',$value);
                    $detailArray[] = array('queue_id' => $queueId,'song_prodid' => $data[2],'song_providertype' => $data[1] , 'album_prodid' => $data[0], 'album_providertype' => $data[1]);
                } 
                $this->QueueDetail->setDataSource('master');
                if($this->QueueDetail->saveAll($detailArray)) {
                    $this->QueueDetail->setDataSource('default');
                    $this->Common->refreshQueueSongs($queueId);
                    $this->Session->setFlash('Songs added successfully to playlist!', 'modal', array('class' => 'modal success'));
                    $this->redirect('addplaylist/id:'.$queueId);                
                } else {
                    $this->QueueDetail->setDataSource('default');
                    $this->Session->setFlash('Error occured while adding songs to playlist', 'modal', array('class' => 'modal problem'));
                    $this->redirect('addplaylist/id:'.$queueId);                    
                }
            } else {
                $this->QueueList->setDataSource('default');
                $this->Session->setFlash('Error occured while creating playlist', 'modal', array('class' => 'modal problem'));
                $this->redirect('addplaylist');            
            }

        }    

    }
    
    
    /**
     * Function Name : deletePlaylist
     * Description          : This function is used to delete defau;t playlists 
     */
    
    function admin_deletePlaylist() {
        $deleteQueueId = $this->params['named']['id'];
        $this->QueueDetail->setDataSource('master');
        Configure::write('Cache.disable', false);
        $this->Common->setAdminDefaultQueuesCache();
        $this->QueueDetail->deleteAll(array('queue_id' => $deleteQueueId,false));
        if($this->QueueList->deleteAll(array('queue_id' => $deleteQueueId,false))) {
            $this->QueueDetail->setDataSource('default');
            $this->Session->setFlash('Playlist deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('manageplaylist');
        } else {
            $this->QueueDetail->setDataSource('default');
            $this->Session->setFlash('Error occured while deleteting the Playlist', 'modal', array('class' => 'modal problem'));
            $this->redirect('manageplaylist');
        }
        $this->QueueDetail->setDataSource('default');
    }
    
    /**
     * function name : admin_managePlaylist
     * Description   : This is used to manage playlists
     */
    
    function admin_manageplaylist() {
        $queueLists = $this->paginate('QueueList', array( 'queue_type' => 1));
        $this->set('queueLists', $queueLists);        
    }    

    /*
      Function Name : admin_artistform
      Desc : action for displaying the add/edit featured artist form
     */

    function admin_artistform() {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $this->set("territories", $territoriesArray);
        if (!empty($this->params['named'])) { //gets the values from the url in form  of array
            $artistId = $this->params['named']['id'];
            if (trim($artistId) != '' && is_numeric($artistId)) {
                $this->set('formAction', 'admin_updatefeaturedartist/id:' . $artistId);
                $this->set('formHeader', 'Edit Featured Artist');
                $getArtistrDataObj = new Featuredartist();
                $getData = $getArtistrDataObj->getartistdata($artistId);
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = $getData['Featuredartist']['artist_name'];
                $country = $getData['Featuredartist']['territory'];

                $getArtistData = array();
                $this->set('getArtistData', $getArtistData);
                $result = array();
                $allAlbum = $this->Album->find('all', array(
                    'fields' => array('Album.ProdID', 'Album.AlbumTitle'),
                    'conditions' => array('Album.ArtistText' => $getData['Featuredartist']['artist_name'], 'Album.provider_type' => $getData['Featuredartist']['provider_type']),
                    'recursive' => -1
                ));

                $val = '';
                $this->Song->Behaviors->attach('Containable');
                foreach ($allAlbum as $k => $v) {
                    $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'), 'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'], 'Song.DownloadStatus' => 1, 'TrackBundleCount' => 0, 'Country.Territory' => $getData['Featuredartist']['territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
                    if (count($recordCount) > 0) {
                        $result[$v['Album']['ProdID']] = $v['Album']['AlbumTitle'];
                    }
                }
                $this->set('album', $result);
            }
        } else {
            $this->set('formAction', 'admin_insertfeaturedartist');
            $this->set('formHeader', 'Add Featured Artist');
            $getFeaturedDataObj = new Featuredartist();
            $featuredtData = $getFeaturedDataObj->getallartists();
            $condition = 'add';
            $artistName = '';
        }

        Configure::write('Cache.disable', false);
        Cache::delete("featuredUS");
        Cache::delete("featuredCA");
        Cache::delete("featuredIT");
        Cache::delete("featuredNZ");
        Cache::delete("featuredAU");
        Cache::delete("featuredIE");
        Cache::delete("featuredGB");
        Configure::write('Cache.disable', true);
    }

    /*
      Function Name : admin_insertfeaturedartist
      Desc : inserts a featured artist
     */

    function admin_insertfeaturedartist() {
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $errorMsg = '';
        $artist = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory<br/>';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
        $insertArr = array();
        $insertArr['artist_name'] = $artist;
        $insertArr['album'] = $album;
        $insertArr['territory'] = $this->data['Artist']['territory'];
        $insertArr['language'] = Configure::read('App.LANGUAGE');
        if (isset($album_provider_type)) {
            $insertArr['provider_type'] = $album_provider_type;
        }
        $insertObj = new Featuredartist();
        if (empty($errorMsg)) {
            if ($insertObj->insert($insertArr)) {
                $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));

                Configure::write('Cache.disable', false);
                Cache::delete("featuredUS");
                Cache::delete("featuredCA");
                Cache::delete("featuredIT");
                Cache::delete("featuredNZ");
                Cache::delete("featuredAU");
                Cache::delete("featuredIE");
                Cache::delete("featuredGB");
                Configure::write('Cache.disable', true);
                $this->redirect('managefeaturedartist');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('artistform');
        }
    }

    /*
      Function Name : admin_updatefeaturedartist
      Desc : Updates a featured artist
     */

    function admin_updatefeaturedartist() {
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $errorMsg = '';
        $album_provider_type = '';
        $album_prodid = 0;
        $this->Featuredartist->id = $this->data['Artist']['id'];
        $alb_det = explode('-', $this->params[$index]['album']);
        if (isset($alb_det[0])) {
            $album_prodid = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $album_provider_type = $alb_det[1];
        }
        $artistName = '';
        if (isset($this->params[$index]['artistName'])) {
            $artistName = $this->params[$index]['artistName'];
        }
        $artist = '';
        if (isset($this->params[$index]['artistName'])) {
            $artist = $this->params[$index]['artistName'];
        } else {
            $artist = $this->data['Artist']['artist_name'];
        }
        if (isset($this->params[$index]['album'])) {
            $album = $album_prodid;
        } else {
            $album = $this->data['Artist']['album'];
        }
        if ($artist == '') {
            $errorMsg .= 'Please select an Artist.<br/>';
        }
        if ($this->data['Artist']['territory'] == '') {
            $errorMsg .= 'Please Choose a Territory';
        }
        if ($album == '') {
            $errorMsg .= 'Please select an Album.<br/>';
        }
        $updateArr = array();
        $updateArr['id'] = $this->data['Artist']['id'];
        $updateArr['artist_name'] = $artist;
        $updateArr['territory'] = $this->data['Artist']['territory'];
        $updateArr['language'] = Configure::read('App.LANGUAGE');
        $updateArr['album'] = $album;
        if (isset($album_provider_type)) {
            $updateArr['provider_type'] = $album_provider_type;
        }
        $updateObj = new Featuredartist();
        if (empty($errorMsg)) {
            if ($updateObj->insert($updateArr)) {
                $this->Session->setFlash('Data has been updated successfully!', 'modal', array('class' => 'modal success'));
    
                Configure::write('Cache.disable', false);
                Cache::delete("featuredUS");
                Cache::delete("featuredCA");
                Cache::delete("featuredIT");
                Cache::delete("featuredNZ");
                Cache::delete("featuredAU");
                Cache::delete("featuredIE");
                Cache::delete("featuredGB");
                Configure::write('Cache.disable', true);
                $this->redirect('managefeaturedartist');
            }
        } else {
            $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
            $this->redirect('managefeaturedartist');
        }
    }

    /*
      Function Name : admin_delete
      Desc : For deleting a featured artist
     */

    function admin_delete() {
        $deleteArtistUserId = $this->params['named']['id'];
        $deleteObj = new Featuredartist();
        if ($deleteObj->del($deleteArtistUserId)) {
            $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('managefeaturedartist');
        } else {
            $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
            $this->redirect('managefeaturedartist');
        }
    }

    /*
      Function Name : admin_createartist
      Desc : assigns artists with images
     */

    function admin_createartist() {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $errorMsg = '';
        $territories = $this->Territory->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $territoriesArray[$territories[$m]['Territory']['Territory']] = $territories[$m]['Territory']['Territory'];
        }
        $this->set("territories", $territoriesArray);
        if (!empty($this->params['named']['id'])) { //gets the values from the url in form  of array
            $artistId = $this->params['named']['id'];
            if (trim($artistId) != '' && is_numeric($artistId)) {
                $this->set('formAction', 'admin_createartist/id:' . $artistId);
                $this->set('formHeader', 'Edit Artist');
                $getArtistrDataObj = new Artist();
                $getData = $getArtistrDataObj->getartistdata($artistId);
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = '';
                if (isset($this->params[$index]['artistName'])) {
                    $artistName = $this->params[$index]['artistName'];
                } else {
                    $artistName = $getData['Artist']['artist_name'];
                }
                $artist = '';
                if (isset($this->params[$index]['artistName'])) {
                    $artist = $this->params[$index]['artistName'];
                } else {
                    $artist = $this->data['Artist']['artist_name'];
                }
                if (isset($this->data)) {
                    $updateObj = new Artist();
                    $updateArr = array();
                    if ($artist == '') {
                        $errorMsg .= 'Please select Artist Name';
                    }
                    if ($this->data['Artist']['territory'] == '') {
                        $errorMsg .= 'Please Choose a Territory';
                    }
                    $updateArr['id'] = $this->data['Artist']['id'];
                    $updateArr['artist_name'] = $artist;
                    $updateArr['territory'] = $this->data['Artist']['territory'];
                    $updateArr['language'] = Configure::read('App.LANGUAGE');
                    if ($this->data['Artist']['artist_image']['name'] != '') {
                        $newPath = '../webroot/img/';
                        $fileName = $this->data['Artist']['artist_image']['name'];
                        $newPath = $newPath . $fileName;
                        move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);
                        $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH') . 'artistimg/' . $getData['Artist']['artist_image']);
                        $src = WWW_ROOT . 'img/' . $fileName;
                        $dst = Configure::read('App.CDN_PATH') . 'artistimg/' . $fileName;
                        $error = $this->CdnUpload->sendFile($src, $dst);
                        unlink($newPath);
                        $updateArr['artist_image'] = $this->data['Artist']['artist_image']['name'];
                    }
                    if (empty($errorMsg)) {
                        if ($updateObj->insert($updateArr)) {
                            Configure::write('Cache.disable', false);
                            $cacheKey = 'ssartists_' . $this->data['Artist']['territory'] . '_' . Configure::read('App.LANGUAGE');
                            if (Cache::delete($cacheKey) == true) {
                                Configure::write('Cache.disable', true);
                                $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                                $this->redirect('manageartist');
                            } else {
                                Configure::write('Cache.disable', true);
                                $this->Session->setFlash('Data has been saved successfully, but the cache is not cleared!', 'modal', array('class' => 'modal success'));
                                $this->redirect('manageartist');
                            }
                        }
                    } else {
                        $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
                    }
                }
                $country = $getData['Artist']['territory'];

                $getArtistData = array();
                $this->set('getArtistData', $getArtistData);
            }
        } else {
            $this->set('formAction', 'admin_createartist');
            $this->set('formHeader', 'Add  Artist');
            $condition = 'add';
            $artistName = '';
            if (isset($this->params[$index]['artistName'])) {
                $artist = $this->params[$index]['artistName'];
            } else {
                $artist = $this->data['Artist']['artist_name'];
            }

            if (isset($this->data)) {

                if ($this->data['Artist']['artist_image']['name'] == '') {
                    $errorMsg .= 'Please upload an image<br/>';
                }
                if ($artist == '') {
                    $errorMsg .= 'Please select an artist name<br/>';
                }
                if ($this->data['Artist']['territory'] == '') {
                    $errorMsg .= 'Please Choose a Territory<br/>';
                }
                $newPath = '../webroot/img/';
                $fileName = $this->data['Artist']['artist_image']['name'];
                $newPath = $newPath . $fileName;
                move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);
                $src = WWW_ROOT . 'img/' . $fileName;
                $dst = Configure::read('App.CDN_PATH') . 'artistimg/' . $fileName;
                $error = $this->CdnUpload->sendFile($src, $dst);
                unlink($newPath);
                $filePath = $this->data['Artist']['artist_image']['tmp_name'];
                $insertArr = array();
                $insertArr['territory'] = $this->data['Artist']['territory'];
                $insertArr['artist_name'] = $artist;
                ;
                $insertArr['artist_image'] = $this->data['Artist']['artist_image']['name'];
                $insertArr['language'] = Configure::read('App.LANGUAGE');
                $insertObj = new Artist();
                if (empty($errorMsg)) {
                    if ($insertObj->insert($insertArr)) {
                        $cacheKey = 'ssartists_' . $this->data['Artist']['territory'] . '_' . Configure::read('App.LANGUAGE');
                        Configure::write('Cache.disable', false);
                        if (Cache::delete($cacheKey) == true) {
                            Configure::write('Cache.disable', true);
                            $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                            $this->redirect('manageartist');
                        } else {
                            Configure::write('Cache.disable', true);
                            $this->Session->setFlash('Data has been saved successfully, but the cache is not cleared!', 'modal', array('class' => 'modal success'));
                            $this->redirect('manageartist');
                        }
                    }
                } else {
                    $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
                }
            }
        }
        $memcache = new Memcache;
        $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
        memcache_delete($memcache, Configure::read('App.memcache_key') . "_artists");
        memcache_close($memcache);
    }

    /*
      Function Name : admin_manageartist
      Desc : manages new artists with images
     */

    function admin_manageartist() {
		$userTypeId = $this->Session->read('Auth.User.type_id');
        $artists = $this->paginate('Artist', array('language' => Configure::read('App.LANGUAGE')));

        $this->set('artists', $artists);
		$this->set('userTypeId',$userTypeId);
    }

    /*
      Function Name : admin_deleteartists
      Desc : For deleting a new artist
     */

    function admin_deleteartists() {

        ob_start();
        $deleteArtistIdArray = $this->data['Info'];
        $deleteOption = $this->data['artist']['selectedOpt'];

        //if admin want to remove selected records then
        if ($deleteOption == 1) {
            if (count($deleteArtistIdArray) > 0) {
                for ($i = 0; $i < count($deleteArtistIdArray); $i++) {
                    $deleteArtistId = $deleteArtistIdArray[$i];
                    $deleteObj = new Artist();
                    $data = $this->Artist->find('all', array('conditions' => array('id' => $deleteArtistId)));
                    $fileName = $data[0]['Artist']['artist_image'];
                    $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH') . 'artistimg/' . $fileName);
                    $deleteObj->del($deleteArtistId);
                }

                $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
                $this->redirect('manageartist');
            }
        }

        //if admin want to remove all records then
        if ($deleteOption == 2) {
            $deleteObj = new Artist();
            $data = $this->Artist->find('all', array('conditions' => array('language' => Configure::read('App.LANGUAGE'))));
            for ($i = 0; $i < count($data); $i++) {
                $fileName = $data[$i]['Artist']['artist_image'];
                $deleteArtistId = $data[$i]['Artist']['id'];
                $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH') . 'artistimg/' . $fileName);
                $deleteObj->del($deleteArtistId);
            }
            $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('manageartist');
        }
        $memcache = new Memcache;
        $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
        memcache_delete($memcache, Configure::read('App.memcache_key') . "_artists");
        memcache_close($memcache);

        ob_flush();
        $this->redirect('manageartist');
    }

    /*
      Function Name : admin_addnewartist
      Desc : assigns artists with images
     */

    function admin_addnewartist() {
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $errorMsg = '';
        if (!empty($this->params['named']['id'])) { //gets the values from the url in form  of array
            $artistId = $this->params['named']['id'];
            if (trim($artistId) != '' && is_numeric($artistId)) {
                $this->set('formAction', 'admin_addnewartist/id:' . $artistId);
                $this->set('formHeader', 'Edit New Artsit');
                $getArtistrDataObj = new Newartist();
                $getData = $getArtistrDataObj->getartistdata($artistId);
                $this->set('getData', $getData);
                $condition = 'edit';
                $artistName = '';
                if (isset($this->params[$index]['artistName'])) {
                    $artistName = $this->params[$index]['artistName'];
                } else {
                    $artistName = $getData['Newartist']['artist_name'];
                }
                $artist = '';
                if (isset($this->params[$index]['artistName'])) {
                    $artist = $this->params[$index]['artistName'];
                } else {
                    $artist = $this->data['Artist']['artist_name'];
                }
                if (isset($this->data)) {
                    $updateObj = new Newartist();
                    $updateArr = array();
                    if ($artist == '') {
                        $errorMsg .= 'Please select Artist Name';
                    }
                    if ($this->data['Artist']['territory'] == '') {
                        $errorMsg .= 'Please Choose a Territory';
                    }
                    $updateArr['id'] = $this->data['Artist']['id'];
                    $updateArr['artist_name'] = $artist;
                    $updateArr['territory'] = $this->data['Artist']['territory'];
                    $updateArr['language'] = Configure::read('App.LANGUAGE');
                    if ($this->data['Artist']['artist_image']['name'] != '') {
                        $newPath = '../webroot/img/';
                        $fileName = $this->data['Artist']['artist_image']['name'];
                        $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH') . 'newartistimg/' . $getData['Newartist']['artist_image']);
                        $newPath = $newPath . $fileName;
                        move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);
                        $src = WWW_ROOT . 'img/' . $fileName;
                        $dst = Configure::read('App.CDN_PATH') . 'newartistimg/' . $fileName;
                        $error = $this->CdnUpload->sendFile($src, $dst);
                        unlink($newPath);
                        $updateArr['artist_image'] = $this->data['Artist']['artist_image']['name'];
                    }
                    if (empty($errorMsg)) {
                        if ($updateObj->insert($updateArr)) {
                            $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                            $this->redirect('managenewartist');
                        }
                    } else {
                        $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
                    }
                }
                $country = $getData['Newartist']['territory'];
                $getArtistDataObj = new Song();
                $getArtistData = $getArtistDataObj->getallartistname($condition, $artistName, $country);
                $this->set('getArtistData', $getArtistData);
            }
        } else {
            $this->set('formAction', 'admin_addnewartist');
            $this->set('formHeader', 'Add New Artist');
            $condition = 'add';
            $artistName = '';
            $artist = '';
            if (isset($this->params[$index]['artistName'])) {
                $artist = $this->params[$index]['artistName'];
            } else {
                $artist = $this->data['Artist']['artist_name'];
            }

            if (isset($this->data)) {
                if ($this->data['Artist']['artist_image']['name'] == '') {
                    $errorMsg .= 'Please upload an image<br/>';
                }
                if ($this->data['Artist']['territory'] == '') {
                    $errorMsg .= 'Please Choose a Territory<br/>';
                }
                if (trim($artist) == '') {
                    $errorMsg .= 'Please select an artist name<br/>';
                }
                $newPath = '../webroot/img/';
                $fileName = $this->data['Artist']['artist_image']['name'];
                $newPath = $newPath . $fileName;
                move_uploaded_file($this->data['Artist']['artist_image']['tmp_name'], $newPath);
                $src = WWW_ROOT . 'img/' . $fileName;
                $dst = Configure::read('App.CDN_PATH') . 'newartistimg/' . $fileName;
                $error = $this->CdnUpload->sendFile($src, $dst);
                unlink($newPath);
                $filePath = $this->data['Artist']['artist_image']['tmp_name'];
                $insertArr = array();
                $insertArr['territory'] = $this->data['Artist']['territory'];
                $insertArr['artist_image'] = $this->data['Artist']['artist_image']['name'];
                $insertArr['artist_name'] = $artist;
                $insertArr['language'] = Configure::read('App.LANGUAGE');
                $insertObj = new Newartist();
                if (empty($errorMsg)) {
                    if ($insertObj->insert($insertArr)) {
                        $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
                        $this->redirect('managenewartist');
                    }
                } else {
                    $this->Session->setFlash($errorMsg, 'modal', array('class' => 'modal problem'));
                }
            }
        }
        $memcache = new Memcache;
        $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
        memcache_delete($memcache, Configure::read('App.memcache_key') . "_newartists");
        memcache_close($memcache);
    }

    /*
      Function Name : admin_managenewartist
      Desc : manages artists with images
     */

    function admin_managenewartist() {
		$userTypeId = $this->Session->read('Auth.User.type_id');
        $artists = $this->paginate('Newartist', array('language' => Configure::read('App.LANGUAGE')));
        $this->set('artists', $artists);
		$this->set('userTypeId',$userTypeId);
    }

    /*
      Function Name : admin_deletenewartists
      Desc : For deleting a featured artist
     */

    function admin_deletenewartists() {
        $deleteArtistUserId = $this->params['named']['id'];
        $deleteObj = new Newartist();
        $data = $this->Newartist->find('all', array('conditions' => array('id' => $deleteArtistUserId)));
        $fileName = $data[0]['Newartist']['artist_image'];
        $error = $this->CdnUpload->deleteFile(Configure::read('App.CDN_PATH') . 'newartistimg/' . $fileName);
        if ($deleteObj->del($deleteArtistUserId)) {
            $this->Session->setFlash('Data deleted successfully!', 'modal', array('class' => 'modal success'));
            $this->redirect('managenewartist');
        } else {
            $this->Session->setFlash('Error occured while deleteting the record', 'modal', array('class' => 'modal problem'));
            $this->redirect('managenewartist');
        }
        $memcache = new Memcache;
        $memcache->addServer(Configure::read('App.memcache_ip'), 11211);
        memcache_delete($memcache, Configure::read('App.memcache_key') . "_newartists");
        memcache_close($memcache);
    }
    
    
    /**
     * Function Name : featuredAjaxListing
     * Desc          : This function is used to get featured artists which are called through ajax 
     */
    function featuredAjaxListing() {
        if (!empty($this->params['form']['page'])) {
            $page = $this->params['form']['page'];
            if (!empty($page)) {
                $territory = $this->Session->read('territory');
                $featuresArtists = Cache::read("featured_artists_" . $territory . '_' . $page);
                if ($featuresArtists === false) {
                    $featuresArtists = $this->Common->getFeaturedArtists($territory, $page);
                    if(!empty($featuresArtists)) {
                        Cache::write("featured_artists_" . $territory . '_' . $page, $featuresArtists);
                    }
                } 
                
                $this->set('featuredArtists', $featuresArtists);
                echo $this->render('/artists/feature_ajaxlisting');
            } else {
                
            }
        }
        die;
    }
    
    /*
      Function Name : view
      Desc : For artist view page
     */

    function view($id = null, $album = null, $provider = null) {

        $this->layout = 'home';

        //Reading the parameters from URL
        if (count($this->params['pass']) > 1) {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            for ($i = 1; $i < $count - 1; $i++) {
                if (!is_numeric($this->params['pass'][$i])) {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
            
            if (is_numeric($this->params['pass'][$count - 2])) {
                $album = $this->params['pass'][$count - 2];
                $provider = base64_decode($this->params['pass'][$count - 1]);
            } else {
                $album = "";
                $provider = "";
            }
        }
 
        //reading sessin vlaues
        $country = $this->Session->read('territory');
        $libType = $this->Session->read('library_type');
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        
        //checking the download status for the patron & library
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);

        //setting the values for view        
        $this->set('album', $album);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

                        
        $cond = "";
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        $val = '';
        $val_provider_type = '';
        $condition = array();
        
        //this flage will be use when $val_provider_type value found empty
        $checkProviderTypeFlage = 0;
      

        //check if album value is set in url
        if ($album != '') {
            
            $condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider);
        
        } else {
            
            $this->Song->Behaviors->attach('Containable');
            if ($libType != 2) {
                $songs = $this->Song->find('all', array(
                    'fields' => array(
                        'DISTINCT Song.ReferenceID',
                        'Song.provider_type'),
                    'conditions' => array(
                        'Song.ArtistText' => base64_decode($id),
                        'Country.DownloadStatus' => 1,
                        "Song.Sample_FileID != ''",
                        "Song.FullLength_FIleID != ''",
                        'Country.Territory' => $country, 
                        $cond),
                    'contain' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.Territory')
                        )
                    ),
                    'recursive' => 0,
                    'limit' => 1)
                );
            } else {
                
                $songs = $this->Song->find('all', array(
                    'fields' => array(
                        'DISTINCT Song.ReferenceID',
                        'Song.provider_type'),
                    'conditions' => array(
                        'Song.ArtistText' => base64_decode($id),
                        "Song.Sample_FileID != ''",
                        "Song.FullLength_FIleID != ''",
                        'Country.Territory' => $country,
                        'Country.DownloadStatus' => 1,
                        array('or' =>
                            array(
                                array('Country.StreamingStatus' => 1)
                            )),
                        $cond
                    ), 'contain' => array(
                        'Country' => array(
                            'fields' => array('Country.Territory')
                        )),
                    'recursive' => 0, 'limit' => 1)
                );
            }
            
            if(!empty($songs)){
                foreach ($songs as $k => $v) {        

                    $val = $val . $v['Song']['ReferenceID'] . ",";
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "'),";                
                } 
            }
            
            if($val_provider_type == '' || empty($songs)){
                $checkProviderTypeFlage = 1;
            }else{            
                $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");
            }
        }

        if ($this->Session->read('block') == 'yes') {            
            $cond = array('Album.Advisory' => 'F');            
        } else {            
            $cond = "";
        }

        $this->paginate = array('conditions' =>
            array('and' =>
                array(
                    $condition
                ),
                "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                        'Files.CdnPath',
                        'Files.SaveAsName',
                        'Files.SourceURL'
                    ),
                )
            ),
            'order' => array('Country.SalesDate' => 'desc'),
            'limit' => '3',
            'cache' => 'yes',
            'chk' => 2
        );

        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }
        $this->Album->recursive = 2;
       
       
        //check if provider types string is not empty
        if($checkProviderTypeFlage == 0) {
              $albumData = $this->paginate('Album'); //getting the Albums for the artist
        }       
        
        if (!empty($albumData)) {            
            if ($libType == 2) {
                $albumData[0]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[0]['Album']['ArtistText']), $albumData[0]['Album']['ProdID'], base64_encode($albumData[0]['Album']['provider_type']), 1);
            }
        }

        //check for provider if null
        if ($provider == "") {
            $provider = $albumData[0]['Album']['provider_type'];
        }

        //creating the Artist Url
        if (isset($albumData[0]['Song']['ArtistURL'])) {
            $this->set('artistUrl', $albumData[0]['Song']['ArtistURL']);
        } else {
            $this->set('artistUrl', "N/A");
        }

        if (isset($albumData['0']['Genre']['Genre'])) {
			$combineGenre = $this->Common->getGenreForSelection($albumData['0']['Genre']['Genre']);
            $this->set("genre", $albumData['0']['Genre']['Genre']);
			$this->set("combineGenre",$combineGenre);
        } else {
            $this->set("genre", '');
        }

        $this->set('albumData', $albumData);

        //getting the songs for album
        $albumSongs = array();
        if (!empty($albumData)) {
            foreach ($albumData as $album) {   
                if ($libType != 2) {
                    $albumSongs[$album['Album']['ProdID']] = $this->Song->find('all', array(
                        'conditions' =>
                        array('and' =>
                            array(
                                array('Song.ReferenceID' => $album['Album']['ProdID']),
                                array('Song.provider_type = Country.provider_type'),
                                array('Country.DownloadStatus' => 1),
                                array("Song.Sample_FileID != ''"),
                                array("Song.FullLength_FIleID != ''"),
                                array("Song.provider_type" => $provider),
                                array('Country.Territory' => $country),
                                $cond
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
                                    'Country.SalesDate',
                                    'Country.StreamingSalesDate',
                                    'Country.StreamingStatus',
                                    'Country.DownloadStatus'
                                )
                            ),
                            'Sample_Files' => array(
                                'fields' => array(
                                    'Sample_Files.CdnPath',
                                    'Sample_Files.SaveAsName'
                                )
                            ),
                            'Full_Files' => array(
                                'fields' => array(
                                    'Full_Files.CdnPath',
                                    'Full_Files.SaveAsName'
                                )
                            ),
                        ),
                        'group' => 'Song.ProdID, Song.provider_type',
                        'order' => array('Song.sequence_number', 'Song.ProdID')
                    ));
                } else {
                    $albumSongs[$album['Album']['ProdID']] = $this->Song->find('all', array(
                        'conditions' =>
                        array('and' =>
                            array(
                                array('Song.ReferenceID' => $album['Album']['ProdID']),
                                array('Song.provider_type = Country.provider_type'),
                                array("Song.Sample_FileID != ''"),
                                array("Song.FullLength_FIleID != ''"),
                                array("Song.provider_type" => $provider),
                                array('Country.Territory' => $country),
                                $cond
                            ),
                            'or' => array(array('and' => array(
                                        'Country.StreamingStatus' => 1,
                                        'Country.StreamingSalesDate <=' => date('Y-m-d')
                                    ))
                                ,
                                array('and' => array(
                                        'Country.DownloadStatus' => 1
                                    ))
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
                                    'Country.SalesDate',
                                    'Country.StreamingSalesDate',
                                    'Country.StreamingStatus',
                                    'Country.DownloadStatus',
                                )
                            ),
                            'Sample_Files' => array(
                                'fields' => array(
                                    'Sample_Files.CdnPath',
                                    'Sample_Files.SaveAsName'
                                )
                            ),
                            'Full_Files' => array(
                                'fields' => array(
                                    'Full_Files.CdnPath',
                                    'Full_Files.SaveAsName'
                                )
                            ),
                        ),
                        'group' => 'Song.ProdID, Song.provider_type',
                        'order' => array('Song.sequence_number', 'Song.ProdID')
                    ));
                }
            }
        }

        //if Artist name is not found in URL
        if ($id != "") {
            $id = str_replace('@', '/', $id);
            $this->set('artistName', base64_decode($id));
        } else {
            $this->set('artistName', $albumSongs[$album['Album']['ProdID']][0]['Song']['Artist']);
        }

        //checking the downlaod status for songs in Album
        if (!empty($albumSongs)) {
            $this->Download->recursive = -1;
            foreach ($albumSongs as $k => $albumSong) {
                foreach ($albumSong as $key => $value) {
                    $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $value['Song']['ProdID'], 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                    if (count($downloadsUsed) > 0) {
                        $albumSongs[$k][$key]['Song']['status'] = 'avail';
                    } else {
                        $albumSongs[$k][$key]['Song']['status'] = 'not';
                    }

                    if ($this->Session->read('library_type') == 2) {

                        $filePath = $this->Token->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
                        if (!empty($filePath)) {
                            $songPath = explode(':', $filePath);
                            $streamUrl = trim($songPath[1]);
                            $albumSongs[$k][$key]['streamUrl'] = $streamUrl;
                            $albumSongs[$k][$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                        }
                    }
                }
            }
        }
        
        $this->set('albumSongs', $albumSongs);

    }
    
    
    /*      Function Name : new_view
      Desc : For artist view page
     */

    function new_view($id = null, $album = null, $provider = null) {

        $this->layout = 'home';

        //Reading the parameters from URL
        if (count($this->params['pass']) > 1) {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            for ($i = 1; $i < $count - 1; $i++) {
                if (!is_numeric($this->params['pass'][$i])) {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
            
            if (is_numeric($this->params['pass'][$count - 2])) {
                $album = $this->params['pass'][$count - 2];
                $provider = base64_decode($this->params['pass'][$count - 1]);
            } else {
                $album = "";
                $provider = "";
            }
        }
 
        //reading session vlaues from app_controller.php        
        $country = $this->patron_country;
        $libType = $this->library_type;
        $patId = $this->patron_id;
        $libId = $this->library_id;
        
        //checking the download status for the patron & library
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);

        //setting the values for view        
        $this->set('album', $album);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        // Explicit songs
        $cond = "";
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        $val = '';
        $val_provider_type = '';
        $condition = array();

        //check if album value is set in url
        if ($album != '') {
            $condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider, 'Album.provider_type = Genre.provider_type');
        } else {
            $this->Song->Behaviors->attach('Containable');
            if ($libType != 2) {                                
                $songs = $this->Song->getArtistView($id , $country, $cond, 1) ;                
            } else {                  
                $songs = $this->Song->getArtistView($id , $country, $cond, 2) ;
            }
            
            $val_ref_prov = explode('&', $this->Common->getRefAndProviderCondString($songs) );
            $val = $val_ref_prov[0];
            $val_provider_type = $val_ref_prov[1];
            
            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");
        }

        $this->paginate = array('conditions' =>
            array('and' =>
                array(
                    $condition
                ),
                "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                        'Files.CdnPath',
                        'Files.SaveAsName',
                        'Files.SourceURL'
                    ),
                )
            ),
            'order' => array('Country.SalesDate' => 'desc'),
            'limit' => '3',
            'cache' => 'yes',
            'chk' => 2
        );
        
        $this->Album->recursive = 2;
        $albumData = $this->paginate('Album'); //getting the Albums for the artist
        
        if (!empty($albumData)) {            
            if ($libType == 2) {
                $albumData[0]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[0]['Album']['ArtistText']), $albumData[0]['Album']['ProdID'], base64_encode($albumData[0]['Album']['provider_type']), 1);
            }
        }

        //check for provider if null
        if ($provider == "") {
            $provider = $albumData[0]['Album']['provider_type'];
        }


        //creating the Artist Url
        if (isset($albumData[0]['Song']['ArtistURL'])) {
            $this->set('artistUrl', $albumData[0]['Song']['ArtistURL']);
        } else {
            $this->set('artistUrl', "N/A");
        }

        if (isset($albumData['0']['Genre']['Genre'])) {
            $this->set("genre", $albumData['0']['Genre']['Genre']);
        } else {
            $this->set("genre", '');
        }

        $this->set('albumData', $albumData);

        //getting the songs for album
        $albumSongs = array();
        if (!empty($albumData)) {
            foreach ($albumData as $album) {   
                if ($libType != 2) {                    
                    $albumSongs[$album['Album']['ProdID']] = $this->Song->getArtistSongs($album['Album']['ProdID'] , $provider, $country, $cond, 1) ;                    
                } else {
                    $albumSongs[$album['Album']['ProdID']] = $this->Song->getArtistSongs($album['Album']['ProdID'] , $provider, $country, $cond, 2) ;                                       
                }
            }
        }

        //if Artist name is not found in URL
        if ($id != "") {
            $id = str_replace('@', '/', $id);
            $this->set('artistName', base64_decode($id));
        } else {
            $this->set('artistName', $albumSongs[$album['Album']['ProdID']][0]['Song']['Artist']);
        }

        //checking the downlaod status for songs in Album
        if (!empty($albumSongs)) {
            $this->Download->recursive = -1;
            foreach ($albumSongs as $k => $albumSong) {
                foreach ($albumSong as $key => $value) {
                    $downloadsUsed = $this->Download->getDownloadStatus($value['Song']['ProdID'] , $libId, $patId) ;                    
                    if (count($downloadsUsed) > 0) {
                        $albumSongs[$k][$key]['Song']['status'] = 'avail';
                    } else {
                        $albumSongs[$k][$key]['Song']['status'] = 'not';
                    }

                    if ($this->Session->read('library_type') == 2) {

                        $filePath = $this->Token->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
                        if (!empty($filePath)) {
                            $songPath = explode(':', $filePath);
                            $streamUrl = trim($songPath[1]);
                            $albumSongs[$k][$key]['streamUrl'] = $streamUrl;
                            $albumSongs[$k][$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                        }
                    }
                }
            }
        }
        
        $this->set('albumSongs', $albumSongs);

    }
    

    /*
      Function Name : getAlbumSongs
      Desc : For getting songs related to an Album
     */

    function getAlbumSongs($id = null, $album = null, $provider = null, $ajax = null, $territory = null , $adminTerritory = null) {

        if (empty($ajax)) {
            if (count($this->params['pass']) > 1) {
                $count = count($this->params['pass']);
                $id = $this->params['pass'][0];
                for ($i = 1; $i < $count - 1; $i++) {
                    if (!is_numeric($this->params['pass'][$i])) {
                        $id .= "/" . $this->params['pass'][$i];
                    }
                }
                if (is_numeric($this->params['pass'][$count - 2])) {
                    $album = $this->params['pass'][$count - 2];
                    $provider = base64_decode($this->params['pass'][$count - 1]);
                } else {
                    $album = "";
                    $provider = "";
                }
            }
        } else {
            $provider = base64_decode($provider);
        }

        if(!empty($territory)) {
            $country = $territory;
            if(empty($adminTerritory)) {
                $album = $this->params['pass'][1];
                $provider = base64_decode($this->params['pass'][2]);
                $id = $this->params['pass'][0];

                $countryPrefix = $this->Common->getCountryPrefix($country);  // This is to add prefix to countries table when calling through cron
            }
        } else {
            $country = $this->Session->read('territory'); 
        }
        $libType = $this->Session->read('library_type');
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        if ($album != '') {
            $condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider, 'Album.provider_type = Genre.provider_type');
        } else {

            $this->Song->Behaviors->attach('Containable');
 
            $songs = $this->Song->find('all', array(
                'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
                'conditions' => array('Song.ArtistText' => base64_decode($id), "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", 'Country.Territory' => $country, 'Country.DownloadStatus' => 1,
                    array('or' =>
                        array(
                            array('Country.StreamingStatus' => 1)
                        )), $cond), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));

            $val = '';

            foreach ($songs as $k => $v) {
                $val = $val . $v['Song']['ReferenceID'] . ",";
                $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "'),";
            }
            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");
        }
        $id = str_replace('@', '/', $id);


        $this->Album->recursive = 2;
        $albumData = $this->Album->findSongs('all', array('conditions' =>
            array('and' =>
                array(
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
                        'Files.CdnPath',
                        'Files.SaveAsName',
                        'Files.SourceURL'
                    ),
                )
            ),
            'order' => array('Country.SalesDate' => 'desc'), 'cache' => 'yes'
                )
        );

        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        $albumSongs = array();
        if (!empty($albumData)) {
            foreach ($albumData as $album) {
                $albumSongs[$album['Album']['ProdID']] = $this->Song->find('all', array(
                    'conditions' =>
                    array('and' =>
                        array(
                            array('Song.ReferenceID' => $album['Album']['ProdID']),
                            array('Song.provider_type = Country.provider_type'),
                            array("Song.Sample_FileID != ''"),
                            array("Song.FullLength_FIleID != ''"),
                            array("Song.provider_type" => $provider),
                            array('Country.Territory' => $country),
                            array('Country.StreamingStatus' => 1),
                            array('Country.StreamingSalesDate <=' => date('Y-m-d')),
                            $cond
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
                                'Country.SalesDate',
                                'Country.StreamingSalesDate',
                                'Country.StreamingStatus',
                                'Country.DownloadStatus',
                            )
                        ),
                        'Sample_Files' => array(
                            'fields' => array(
                                'Sample_Files.CdnPath',
                                'Sample_Files.SaveAsName'
                            )
                        ),
                        'Full_Files' => array(
                            'fields' => array(
                                'Full_Files.CdnPath',
                                'Full_Files.SaveAsName'
                            )
                        ),
                    ), 'group' => 'Song.ProdID, Song.provider_type', 'order' => array('Song.sequence_number', 'Song.ProdID')
                ));
            }
        }


        $this->Download->recursive = -1;
        foreach ($albumSongs as $k => $albumSong) {
            foreach ($albumSong as $key => $value) {
                if (empty($ajax)) {
                    $filePath = $this->Token->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
                    
                    if (!empty($filePath)) {
                        $songPath = explode(':', $filePath);
                        $streamUrl = trim($songPath[1]);
                        $albumSongs[$k][$key]['streamUrl'] = $streamUrl;
                        $albumSongs[$k][$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                    }
                } else {
                    $albumSongs[$k][$key]['CdnPath'] = $value['Full_Files']['CdnPath'];
                    $albumSongs[$k][$key]['SaveAsName'] = $value['Full_Files']['SaveAsName'];
                    $albumSongs[$k][$key]['FullLength_Duration'] = $value['Song']['FullLength_Duration'];
                }  

                unset($albumSongs[$k][$key]['Song']['DownloadStatus']);
                unset($albumSongs[$k][$key]['Song']['Sample_Duration']);
                unset($albumSongs[$k][$key]['Song']['FullLength_Duration']);
                unset($albumSongs[$k][$key]['Song']['Sample_FileID']);
                unset($albumSongs[$k][$key]['Song']['FullLength_FIleID']);
                unset($albumSongs[$k][$key]['Song']['sequence_number']);
                unset($albumSongs[$k][$key]['Song']['Title']);
                unset($albumSongs[$k][$key]['Song']['Artist']);
                unset($albumSongs[$k][$key]['Genre']);
                unset($albumSongs[$k][$key]['Country']);
                unset($albumSongs[$k][$key]['Sample_Files']);
                unset($albumSongs[$k][$key]['Full_Files']);
            }
        }
        return $albumSongs;
    }

    /*
     * Function Name : getAlbumData
     * Description   : This function is used to get songs related to an album
     * 
     */

    function getAlbumData() {
        Configure::write('debug', 0);
        $albumSongs = json_decode(base64_decode($this->params['form']['albumtData']));
        if (!empty($albumSongs)) {
            foreach ($albumSongs as $value) {
                
                $filePath = $this->Token->streamingToken($value->CdnPath . "/" . $value->SaveAsName);
                if (!empty($filePath)) {
                    $songPath = explode(':', $filePath);
                    $streamUrl = trim($songPath[1]);
                    $value->streamUrl = $streamUrl;
                    $value->totalseconds = $this->Streaming->getSeconds($value->FullLength_Duration);
                }
                if (!empty($value->streamUrl) || !empty($value->Song->SongTitle)) {

                    if ($value->Song->Advisory == 'T') {
                        $value->Song->SongTitle = $value->Song->SongTitle . ' (Explicit)';
                    }

                    $playItem = array('playlistId' => 0, 'songId' => $value->Song->ProdID, 'providerType' => $value->Song->provider_type, 'label' => $value->Song->SongTitle, 'songTitle' => $value->Song->SongTitle, 'artistName' => $value->Song->ArtistText, 'songLength' => $value->totalseconds, 'data' => $value->streamUrl);
                    $jsonPlayItem = json_encode($playItem);
                    $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                    $playListData[] = $jsonPlayItem;
                }
            }
            if (!empty($playListData)) {
                $playList = implode(',', $playListData);
                if (!empty($playList)) {
                    $playList = base64_encode('[' . $playList . ']');
                }
            }
            $successData = array('success' => $playList);
            echo json_encode($successData);
            exit;
        } else {
            $errorData = array('error' => 'Required parameters are missing');
            echo json_encode($errorData);
            exit;
        }
    }
    
    
    /*
     * Function Name : getAlbumData
     * Description   : This function is used to get songs related to an featured artist or composer
     * 
     */

    function getFeaturedSongs() {
        Configure::write('debug', 0);
        $artistText = base64_decode($this->params['form']['artistText']);
        $providerType = base64_decode($this->params['form']['providerType']);
        $flag = $this->params['form']['flag'];        
        $territory = $this->Session->read('territory');
        $featuredComposerSongs = Cache::read("featured_artist_".$artistText.'_'.$flag.'_'.$territory);
        if ($featuredComposerSongs === false) {
            $featuredComposerSongs = $this->Common->getRandomSongs($artistText,$providerType,$flag,1,$territory);
            
            if (!empty($featuredComposerSongs)) {
                Cache::write("featured_artist_".$artistText.'_'.$flag.'_'.$territory, $featuredComposerSongs);
                $this->log("cache written for featured artist for $artistText with flag $flag for territory".$territory, "cache");
            }            
        } 
        if (!empty($featuredComposerSongs)) {
            foreach ($featuredComposerSongs as $value) {
                if (!empty($value['streamUrl']) || !empty($value['Song']['SongTitle'])) {
                    if ($value['Song']['Advisory'] == 'T') {
                        $value['Song']['SongTitle'] = $value['Song']['SongTitle'] . ' (Explicit)';
                    }
                    $playItem = array('playlistId' => 0, 'songId' => $value['Song']['ProdID'], 'providerType' => $value['Song']['provider_type'], 'label' => $value['Song']['SongTitle'], 'songTitle' => $value['Song']['SongTitle'], 'artistName' => $value['Song']['ArtistText'], 'songLength' => $value['totalseconds'], 'data' => $value['streamUrl']);
                    $jsonPlayItem = json_encode($playItem);
                    $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                    $playListData[] = $jsonPlayItem;
                }
            }
            if (!empty($playListData)) {
                $playList = implode(',', $playListData);
                if (!empty($playList)) {
                    $playList = base64_encode('[' . $playList . ']');
                }
            }
            $successData = array('success' => $playList);
            echo json_encode($successData);
            exit;
        } else {
            $errorData = array('error' => 'There are no songs available for streaming');
            echo json_encode($errorData);
            exit;
        }
    }    
    
    function getNationalAlbumData() {
        
        Configure::write('debug', 0);
        $artistText = $this->params['form']['artistText'];
        $prodId = $this->params['form']['prodId'];
        $providerType = $this->params['form']['providerType'];
        $territory = $this->Session->read('territory');
        $nationalAlbumSongs = Cache::read("nationaltopalbum_" . $territory.'_'.$prodId);
        if ($nationalAlbumSongs === false) {
            $nationalAlbumSongs = $this->requestAction(
                    array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($artistText), $prodId, base64_encode($providerType)))
            );
            
            if (!empty($nationalAlbumSongs[$prodId])) {
                Cache::write("nationaltopalbum_" . $territory.'_'.$prodId, $nationalAlbumSongs);
                $this->log("cache written for national top album for $territory".$prodId, "cache");
            }            
        }
        else
        {
            $nationalAlbumSongs = Cache::read("nationaltopalbum_" . $territory.'_'.$prodId);
        }                
        
        if (!empty($nationalAlbumSongs[$prodId])) {
            
            foreach ($nationalAlbumSongs[$prodId] as $value) {
                
                if (!empty($value['streamUrl']) || !empty($value['Song']['SongTitle'])) {

                    if ($value['Song']['Advisory'] == 'T') {
                        $value['Song']['SongTitle'] = $value['Song']['SongTitle'] . ' (Explicit)';
                    }

                    $playItem = array('playlistId' => 0, 'songId' => $value['Song']['ProdID'], 'providerType' => $value['Song']['provider_type'], 'label' => $value['Song']['SongTitle'], 'songTitle' => $value['Song']['SongTitle'], 'artistName' => $value['Song']['ArtistText'], 'songLength' => $value['totalseconds'], 'data' => $value['streamUrl']);
                    $jsonPlayItem = json_encode($playItem);
                    $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                    $playListData[] = $jsonPlayItem;
                }
            }
            if (!empty($playListData)) {
                $playList = implode(',', $playListData);
                if (!empty($playList)) {
                    $playList = base64_encode('[' . $playList . ']');
                }
            }
            $successData = array('success' => $playList);
            echo json_encode($successData);
            exit;
        } else {
            $errorData = array('error' => 'There are no songs available for streaming');
            echo json_encode($errorData);
            exit;
        }     
    }
    
    /*
     * Function Name : getSongStreamUrl
     * Description   : This function is used to get song stream Url 
     * 
     */    
    function getSongStreamUrl() {
        Configure::write('debug', 0);
        $cdnPath = $this->params['form']['cdnPath'];
        $sourceUrl = $this->params['form']['sourceUrl'];
        $songLength = $this->params['form']['songLength'];
        $songTitle = $this->params['form']['songTitle'];
        $providerType = $this->params['form']['providerType'];
        $playlistId = $this->params['form']['playlistId'];
        $prodId = $this->params['form']['prodId'];
        $artistName = $this->params['form']['artistName'];
        if (!empty($cdnPath) && !empty($sourceUrl) && !empty($songLength)) {
            $data = array();            
            $filePath = $this->Token->streamingToken($cdnPath . "/" . $sourceUrl);
            $songPath = explode(':', $filePath);
            $streamUrl = trim($songPath[1]);
            $songStreamUrl = $streamUrl;
            $totalseconds = $this->Streaming->getSeconds($songLength);
            $playItem = array('playlistId' => $playlistId, 'songId' => $prodId, 'providerType' => $providerType, 'label' => $songTitle, 'songTitle' => $songTitle, 'artistName' => $artistName, 'songLength' => $totalseconds, 'data' => $songStreamUrl);
            $jsonPlayItem = json_encode($playItem);
            $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
            $playListData[] = $jsonPlayItem;            
            if (!empty($playListData)) {
                $playList = implode(',', $playListData);
                if (!empty($playList)) {
                    $playList = base64_encode('[' . $playList . ']');
                }
            }
            $successData = array('success' => $playList);
            echo json_encode($successData);
            exit;
        } else {
            $errorData = array('error' => 'Required parameters are missing');
            echo json_encode($errorData);
            exit;
        }     
    }

    /*
      Function Name : album_ajax_view
      Desc : For artist view page
     */

    function album_ajax_view($id = null, $album = null, $provider = null) {

        $this->layout = 'ajax';

        if (count($this->params['pass']) > 1) {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            for ($i = 1; $i < $count - 1; $i++) {
                if (!is_numeric($this->params['pass'][$i])) {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
            if (is_numeric($this->params['pass'][$count - 2])) {
                $album = $this->params['pass'][$count - 2];
                $provider = base64_decode($this->params['pass'][$count - 1]);
            } else {
                $album = "";
                $provider = "";
            }
        }

        //for login redirect issue
        if ($album != '') {
            $this->Session->write('calledAlbum', $album);
            $this->Session->write('calledProvider', $provider);
        }
        $country = $this->Session->read('territory');
        $libType = $this->Session->read('library_type');
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }
        if ($album != '') {
            $condition = array("Album.ProdID" => $album, 'Album.provider_type' => $provider, 'Album.provider_type = Genre.provider_type');
        } else {
            $this->Song->Behaviors->attach('Containable');
            if ($libType != 2) {
                $songs = $this->Song->find('all', array(
                    'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
                    'conditions' => array('Song.ArtistText' => base64_decode($id), 'Country.DownloadStatus' => 1, "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", 'Country.Territory' => $country, $cond), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
            } else {
                $songs = $this->Song->find('all', array(
                    'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
                    'conditions' => array('Song.ArtistText' => base64_decode($id), "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", 'Country.Territory' => $country, 'Country.DownloadStatus' => 1,
                        array('or' =>
                            array(
                                array('Country.StreamingStatus' => 1)
                            )), $cond), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
            }

            $val = '';
            $val_provider_type = '';

            foreach ($songs as $k => $v) {
                $val = $val . $v['Song']['ReferenceID'] . ",";
                $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "'),";
            }
            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");
        }

        $id = str_replace('@', '/', $id);
        $this->set('artistName', base64_decode($id));
        $this->set('album', $album);
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Album.Advisory' => 'F');
        } else {
            $cond = "";
        }
        $this->paginate = array('conditions' =>
            array('and' =>
                array(
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
                        'Files.CdnPath',
                        'Files.SaveAsName',
                        'Files.SourceURL'
                    ),
                )
            ), 'order' => array('Country.SalesDate' => 'desc'), 'limit' => '3', 'cache' => 'yes', 'chk' => 2
        );
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }
        $this->Album->recursive = 2;
        $albumData = array();
        $albumData = $this->paginate('Album'); //getting the Albums for the artist
        $libType = $this->Session->read('library_type');
        if ($libType == 2) {
            $albumData[0]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[0]['Album']['ArtistText']), $albumData[0]['Album']['ProdID'], base64_encode($albumData[0]['Album']['provider_type']));
            $this->layout = 'ajax';
        }

        $albumSongs = array();
        if (!empty($albumData)) {
            foreach ($albumData as $album) {
                if ($libType != 2) {
                    $albumSongs[$album['Album']['ProdID']] = $this->Song->find('all', array(
                        'conditions' =>
                        array('and' =>
                            array(
                                array('Song.ReferenceID' => $album['Album']['ProdID']),
                                array('Song.provider_type = Country.provider_type'),
                                array('Country.DownloadStatus' => 1),
                                array("Song.Sample_FileID != ''"),
                                array("Song.FullLength_FIleID != ''"),
                                array("Song.provider_type" => $provider),
                                array('Country.Territory' => $country), $cond
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
                                    'Country.SalesDate',
                                    'Country.StreamingSalesDate',
                                    'Country.StreamingStatus',
                                    'Country.DownloadStatus'
                                )
                            ),
                            'Sample_Files' => array(
                                'fields' => array(
                                    'Sample_Files.CdnPath',
                                    'Sample_Files.SaveAsName'
                                )
                            ),
                            'Full_Files' => array(
                                'fields' => array(
                                    'Full_Files.CdnPath',
                                    'Full_Files.SaveAsName'
                                )
                            ),
                        ), 'group' => 'Song.ProdID, Song.provider_type', 'order' => array('Song.sequence_number', 'Song.ProdID')
                    ));
                } else {

                    $albumSongs[$album['Album']['ProdID']] = $this->Song->find('all', array(
                        'conditions' =>
                        array('and' =>
                            array(
                                array('Song.ReferenceID' => $album['Album']['ProdID']),
                                array('Song.provider_type = Country.provider_type'),
                                array("Song.Sample_FileID != ''"),
                                array("Song.FullLength_FIleID != ''"),
                                array("Song.provider_type" => $provider),
                                array('Country.Territory' => $country), $cond
                            ),
                            'or' => array(array('and' => array('Country.StreamingStatus' => 1, 'Country.StreamingSalesDate <=' => date('Y-m-d')))
                                , array('and' => array('Country.DownloadStatus' => 1, 'Country.SalesDate <=' => date('Y-m-d')))
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
                                    'Country.SalesDate',
                                    'Country.StreamingSalesDate',
                                    'Country.StreamingStatus',
                                    'Country.DownloadStatus',
                                )
                            ),
                            'Sample_Files' => array(
                                'fields' => array(
                                    'Sample_Files.CdnPath',
                                    'Sample_Files.SaveAsName'
                                )
                            ),
                            'Full_Files' => array(
                                'fields' => array(
                                    'Full_Files.CdnPath',
                                    'Full_Files.SaveAsName'
                                )
                            ),
                        ), 'group' => 'Song.ProdID, Song.provider_type', 'order' => array('Song.sequence_number', 'Song.ProdID')
                    ));
                }
            }
        }

        $this->Download->recursive = -1;
        foreach ($albumSongs as $k => $albumSong) {
            foreach ($albumSong as $key => $value) {
                $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $value['Song']['ProdID'], 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                if (count($downloadsUsed) > 0) {
                    $albumSongs[$k][$key]['Song']['status'] = 'avail';
                } else {
                    $albumSongs[$k][$key]['Song']['status'] = 'not';
                }
            }
        }
        $this->set('albumData', $albumData);
        /**
         * If user comes to genre page and earlier 
         * he has selected the Album from list
         * then it store the Album name in session
         * for setting the focus in the list on album
         */
        $this->Session->write('calledAlbumText', $album['Album']['ProdID']);
       

        if (isset($albumData[0]['Song']['ArtistURL'])) {
            $this->set('artistUrl', $albumData[0]['Song']['ArtistURL']);
        } else {
            $this->set('artistUrl', "N/A");
        }
        $array = array();
        $pre = '';
        $res = array();

        $this->set('albumSongs', $albumSongs);
        $this->set("genre", $albumData['0']['Genre']['Genre']);
    }

    function album($id = null, $album = null, $provider = null) {
    
        $country = $this->Session->read('territory');
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libType = $this->Session->read('library_type');

        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        if (count($this->params['pass']) > 1) {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            for ($i = 1; $i < $count - 1; $i++) {
                if (!is_numeric($this->params['pass'][$i])) {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
        }

        if (isset($this->params['named']['page'])) {
            $this->layout = 'ajax';
        } else {
            $this->layout = 'home';
        }

        $id = str_replace('@', '/', $id);
        $this->set('artisttext', base64_decode($id));
        $this->set('artisttitle', base64_decode($id));
        $this->set('genre', base64_decode($album));
        $combineGenre = $this->Common->getGenreForSelection(base64_decode($album));
		$this->set('combineGenre',$combineGenre);
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        $this->Song->Behaviors->attach('Containable');
        $songs = $this->Song->find('all', array(
            'fields' => array(
                'DISTINCT Song.ReferenceID',
                'Song.provider_type',
                'Country.SalesDate'),
            'conditions' => array('Song.ArtistText' => base64_decode($id),
                'Country.DownloadStatus' => 1, /* Changed on 16/01/2014 from Song.DownloadStatus to Country.DownloadStatus */
                "Song.Sample_FileID != ''",
                "Song.FullLength_FIleID != ''",
                'Country.Territory' => $country, $cond,
                'Song.provider_type = Country.provider_type'),
            'contain' => array(
                'Country' => array(
                    'fields' => array('Country.Territory')
                )),
            'recursive' => 0,
            'order' => array(
                'Country.SalesDate DESC'
            ))
        );

        $val = '';
        $val_provider_type = '';

        if (!empty($songs)) {
            foreach ($songs as $k => $v) {
                if (empty($val)) {
                    $val .= $v['Song']['ReferenceID'];
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                } else {
                    $val .= ',' . $v['Song']['ReferenceID'];
                    $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                }
            }

            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");

            $this->paginate =
                    array(
                        'conditions' =>
                        array(
                            'and' =>
                            array(
                                $condition
                            ),
                            "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                            'Album.provider_type',
                            'Files.CdnPath',
                            'Files.SaveAsName',
                            'Files.SourceURL',
                            'Genre.Genre'
                        ),
                        'contain' => array(
                            'Genre' => array(
                                'fields' => array(
                                    'Genre.Genre'
                                )
                            ),
                            'Files' => array(
                                'fields' => array(
                                    'Files.CdnPath',
                                    'Files.SaveAsName',
                                    'Files.SourceURL'
                                ),
                            )
                        ),
                        'order' => array('FIELD(Album.ProdID, ' . $val . ') ASC'),
                        'cache' => 'yes',
                        'chk' => 2
            );

            $this->paginate['limit'] = 50;
            $this->Album->recursive = 2;
            
            $albumData = $this->paginate('Album');

            if ($libType == 2) {
                foreach ($albumData as $key => $value) {
                    $albumData[$key]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[$key]['Album']['ArtistText']), $albumData[$key]['Album']['ProdID'], base64_encode($albumData[$key]['Album']['provider_type']), 1);
                }
            }

            foreach ($albumData as $key => $value) {
                    $albumData[$key]['combineGenre'] = $this->Common->getGenreForSelection($albumData[$key]['Genre']['Genre']);
                }
            $this->set('albumData', $albumData);

            if (isset($this->params['named']['page'])) {
                $this->autoLayout = false;
                $this->autoRender = false;

                echo $this->render('/artists/artist_album_ajax');
                die;
            }
        }

        // Videos Section
        $decodedId = trim(base64_decode($id));
        $artistVideoList = Cache::read("videolist_" . $country . "_" . $decodedId);
        if (!empty($country)) {
            if ($artistVideoList === false) {

                if (!empty($decodedId)) {
                    $artistVideoList = $this->Common->getAllVideoByArtist($country, $decodedId);
                    Cache::write("videolist_" . $country . "_" . $decodedId, $artistVideoList);
                }
            } 
            $this->set('artistVideoList', $artistVideoList);
        }
    }
    
    function load_albums($id = null,$page = 1) {
    
        $country = $this->Session->read('territory');
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libType = $this->Session->read('library_type');

        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        $this->layout = 'ajax';

        $id = str_replace('@', '/', $id);
        $this->set('artisttext', base64_decode($id));
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        $this->Song->Behaviors->attach('Containable');
        $songs = $this->Song->find('all', array(
            'fields' => array(
                'DISTINCT Song.ReferenceID',
                'Song.provider_type',
                'Country.SalesDate'),
            'conditions' => array('Song.ArtistText' => base64_decode($id),
                'Country.DownloadStatus' => 1, /* Changed on 16/01/2014 from Song.DownloadStatus to Country.DownloadStatus */
                "Song.Sample_FileID != ''",
                "Song.FullLength_FIleID != ''",
                'Country.Territory' => $country, $cond,
                'Song.provider_type = Country.provider_type'),
            'contain' => array(
                'Country' => array(
                    'fields' => array('Country.Territory')
                )),
            'recursive' => 0,
            'order' => array(
                'Country.SalesDate DESC'
            ))
        );

        $val = '';
        $val_provider_type = '';

        if (!empty($songs)) {
            foreach ($songs as $k => $v) {
                if (empty($val)) {
                    $val .= $v['Song']['ReferenceID'];
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                } else {
                    $val .= ',' . $v['Song']['ReferenceID'];
                    $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                }
            }

            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");

            $this->paginate =
                    array(
                        'conditions' =>
                        array(
                            'and' =>
                            array(
                                $condition
                            ),
                            "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                            'Album.provider_type',
                            'Files.CdnPath',
                            'Files.SaveAsName',
                            'Files.SourceURL',
                            'Genre.Genre'
                        ),
                        'contain' => array(
                            'Genre' => array(
                                'fields' => array(
                                    'Genre.Genre'
                                )
                            ),
                            'Files' => array(
                                'fields' => array(
                                    'Files.CdnPath',
                                    'Files.SaveAsName',
                                    'Files.SourceURL'
                                ),
                            )
                        ),
                        'order' => array('FIELD(Album.ProdID, ' . $val . ') ASC'),
                        'cache' => 'yes',
                        'chk' => 2
            );

            $this->paginate['limit'] = 50;
            $this->paginate['page'] = $page;
            $this->Album->recursive = 2;
            
            $albumData = $this->paginate('Album');

            if ($libType == 2) {
                foreach ($albumData as $key => $value) {
                    $albumData[$key]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[$key]['Album']['ArtistText']), $albumData[$key]['Album']['ProdID'], base64_encode($albumData[$key]['Album']['provider_type']), 1);
                }
            }
            $this->set('albumData', $albumData);
            $this->set('current_page', $page);
        }
    }    

    function newAlbum($id = null, $album = null)
    {
        if (count($this->params['pass']) > 1)
        {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            for ($i = 1; $i < $count - 1; $i++)
            {
                if (!is_numeric($this->params['pass'][$i]))
                {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
        }

        if (isset($this->params['named']['page']))
        {
            $this->layout = 'ajax';
        }
        else
        {
            $this->layout = 'home';
        }

        $id = str_replace('@', '/', $id);
        $this->set('artisttext', base64_decode($id));
        $this->set('artisttitle', base64_decode($id));
        $this->set('genre', base64_decode($album));

        $libraryDownload = $this->Downloads->checkLibraryDownload($this->library_id);
        $patronDownload = $this->Downloads->checkPatronDownload($this->patron_id, $this->library_id);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);

        $cond = $this->Common->getBlockCondition();
        $songs = $this->Song->getArtistAlbums($id, $this->patron_country, $cond);
        
        if (!empty($songs))
        {
            $val_ref_prov = explode('&', $this->Common->getRefAndProviderCondString($songs) );
            $val = $val_ref_prov[0];
            $val_provider_type = $val_ref_prov[1];
            

            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ") AND Album.provider_type = Genre.provider_type");

            $this->paginate =
                    array(
                        'conditions' =>
                        array(
                            'and' =>
                            array(
                                $condition
                            ),
                            "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                            'Album.provider_type',
                            'Files.CdnPath',
                            'Files.SaveAsName',
                            'Files.SourceURL',
                            'Genre.Genre'
                        ),
                        'contain' => array(
                            'Genre' => array(
                                'fields' => array(
                                    'Genre.Genre'
                                )
                            ),
                            'Files' => array(
                                'fields' => array(
                                    'Files.CdnPath',
                                    'Files.SaveAsName',
                                    'Files.SourceURL'
                                ),
                            )
                        ),
                        'order' => array('FIELD(Album.ProdID, ' . $val . ') ASC'),
                        'cache' => 'yes',
                        'chk' => 2
            );

            $this->paginate['limit'] = 25;
            $this->Album->recursive = 2;

            $albumData = $this->paginate('Album');

            if ($this->library_type == 2)
            {
                foreach ($albumData as $key => $value)
                {
                    $albumData[$key]['albumSongs'] = $this->getAlbumSongs(base64_encode($albumData[$key]['Album']['ArtistText']), $albumData[$key]['Album']['ProdID'], base64_encode($albumData[$key]['Album']['provider_type']), 1);
                }
            }

            $this->set('albumData', $albumData);

            if (isset($this->params['named']['page']))
            {
                $this->autoLayout = false;
                $this->autoRender = false;

                echo $this->render('/artists/artist_album_ajax');
                die;
            }
        }


        // Videos Section
        $decodedId = trim(base64_decode($id));
        
        if (!empty($this->patron_country))
        {
            $artistVideoList = Cache::read("videolist_" . $this->patron_country . "_" . $decodedId);
            if ($artistVideoList === false)
            {
                if (!empty($decodedId))
                {
                    $artistVideoList = $this->Common->getAllVideoByArtist($this->patron_country, $decodedId);
                    Cache::write("videolist_" . $this->patron_country . "_" . $decodedId, $artistVideoList);
                }
            }
            $this->set('artistVideoList', $artistVideoList);
        }
    }
    
    
    function album_ajax($id = null, $album = null, $provider = null) {

        $country = $this->Session->read('territory');
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');

        $this->layout = false;
        if (count($this->params['pass']) > 1) {
            $count = count($this->params['pass']);
            $id = $this->params['pass'][0];
            $this->Session->write('calledArtist', $id);
            for ($i = 1; $i < $count - 1; $i++) {
                if (!is_numeric($this->params['pass'][$i])) {
                    $id .= "/" . $this->params['pass'][$i];
                }
            }
        }


        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }

        //for login redirect we are storing the Genre and Artist in Session
        $this->Session->write('calledGenre', $album);
        
        $this->Session->delete('calledAlbum');
        $this->Session->delete('calledProvider');
        
        $id = str_replace('@', '/', $id);

        $this->Song->Behaviors->attach('Containable');
        $songs = $this->Song->find('all', array(
            'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type'),
            'conditions' => array('Song.ArtistText' => base64_decode($id), 'Country.DownloadStatus' => 1,
                "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", 'Country.Territory' => $country, $cond,
                'Song.provider_type = Country.provider_type'), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0,
            'order' => array('Song.provider_type DESC')));

        $val = '';
        $val_provider_type = '';

        foreach ($songs as $k => $v) {
            $val .= $v['Song']['ReferenceID'] . ",";
            $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "'),";
        }

        $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ") AND Album.provider_type = Genre.provider_type");

        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);

        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);
        $this->set('artisttext', base64_decode($id));
        $this->set('genre', base64_decode($album));
  
        if ($this->Session->read('block') == 'yes') {
            $cond = array('Album.Advisory' => 'F');
        } else {
            $cond = "";
        }

        $this->paginate = array('conditions' =>
            array('and' =>
                array(
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
                        'Files.CdnPath',
                        'Files.SaveAsName',
                        'Files.SourceURL'
                    ),
                )
            ), 'order' => array('Album.provider_type' => 'desc', 'Album.Title' => 'desc'), 'limit' => '100', 'cache' => 'yes', 'chk' => 2
        );

        if ($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
        } else {
            $cond = "";
        }
        $this->Album->recursive = 2;
        $albumData = array();
        if (!empty($songs)) {
            $albumData = $this->paginate('Album'); //getting the Albums for the artist    
        }

        $htmlContain = '<div class="album-list-shadow-container"><h3>Album</h3>
                <div class="album-list">';
        if (count($albumData) > 0) {
            foreach ($albumData as $album_key => $album) {
                //hide song if library block the explicit content
                if (($this->Session->read('block') == 'yes') && ($album['Album']['Advisory'] == 'T')) {
                    continue;
                }

                //get the album image
                if (empty($album['Files']['CdnPath'])) {
                    if (empty($album['Files']['SourceURL']))
                    { }
                    else
                    { }
                }
                
                $albumArtwork = $this->Token->regularToken($album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']);

                //get the album title
                $title_album_on_hover = $album['Album']['AlbumTitle'];
                if (strlen($album['Album']['AlbumTitle']) >= 40) {
                    $album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 40) . '...';
                }


                $copyrightString = '';
                if ($album['Album']['Advisory'] == 'T') {
                    $copyrightString .='<font class="explicit"> (Explicit)</font>';
                }


                if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {

                    $album['Album']['Copyright'] = '( ' . substr($album['Album']['Copyright'], 0, 5) . ' )';
                    $copyrightString .= $album['Album']['Copyright'];
                }

                //created the album url 
                $albumURL = "artists/album_ajax_view/" . base64_encode($album['Album']['ArtistText']) . "/" . $album['Album']['ProdID'] . "/" . base64_encode($album['Album']['provider_type']);

                $album['Album']['AlbumTitle'] = @iconv(mb_detect_encoding($album['Album']['AlbumTitle']), "WINDOWS-1252//IGNORE", $album['Album']['AlbumTitle']);
                $album['Album']['AlbumTitle'] = @iconv(mb_detect_encoding($album['Album']['AlbumTitle']), "UTF-8//IGNORE", $album['Album']['AlbumTitle']);
                $title_album_on_hover = @iconv(mb_detect_encoding($title_album_on_hover), "WINDOWS-1252//IGNORE", $title_album_on_hover);
                $title_album_on_hover = @iconv(mb_detect_encoding($title_album_on_hover), "UTF-8//IGNORE", $title_album_on_hover);

                $htmlContain .= '<div class="album-overview-container" id="'.$album['Album']['ProdID'].'">                                      
                                        <div class="album-image selected">
                                                <a href="javascript:void(0);" onclick="showAlbumDetails(\'' . $albumURL . '\')"><img src="' . Configure::read('App.Music_Path') . $albumArtwork . '" alt="album-cover-small" width="59" height="59" /></a>
                                        </div>
                                        <div class="album-title">
                                                <a href="javascript:void(0);" title="' . $title_album_on_hover . '" onclick="showAlbumDetails(\'' . $albumURL . '\')">' . $album['Album']['AlbumTitle'] . '</a>
                                        </div>
                                        <div class="album-year">
                                                <a href="javascript:void(0);" onclick="showAlbumDetails(\'' . $albumURL . '\')">' . $copyrightString . '</a>
                                        </div>
                                </div>';
            }
        } else {
            $htmlContain .= '<div style="color:#000000;font-weight:bold;font-size:9x;padding-left:10px;">No Results Found</div>';
        }
        $htmlContain .= '</div></div>';

        echo $htmlContain;

        exit;
    }

    /*
      Function Name : view
      Desc : For artist view page
     */

    function admin_getArtists() {
        Configure::write('debug', 0);
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $this->Song->recursive = 0;
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Genre')));
        $this->Song->unbindModel(array('hasOne' => array('Country')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files')));
        $this->Song->unbindModel(array('belongsTo' => array('Full_Files')));
        $artist = $this->Song->find('all', array(
            'conditions' =>
            array('and' =>
                array(
                    array("find_in_set('" . '"' . $this->params[$index]['Territory'] . '"' . "',Song.Territory)", 'Song.provider_type' => 'sony')
                )
            ),
            'fields' => array(
                'DISTINCT Song.ArtistText',
            ),
            'order' => 'Song.ArtistText'
        ));
        $data = "<option value=''>SELECT</option>";
        foreach ($artist as $k => $v) {
            $data = $data . "<option value='" . $v['Song']['ArtistText'] . "'>" . $v['Song']['ArtistText'] . "</option>";
        }
        print "<select class='select_fields' name='artistName' onchange='getAlbum()', id='artistName'>" . $data . "</select>";
        exit;
    }

    /**
     * @getAlbums
     *  return top 5 artist names with ajax call
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getAlbums() {
        Configure::write('debug', 0);

        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $result = array();
        $allAlbum = $this->Album->find('all', array('fields' => array('Album.ProdID', 'Album.AlbumTitle', 'Album.provider_type', 'Album.Advisory'), 'conditions' => array('Album.ArtistText = ' => urldecode($this->params[$index]['artist'])), 'recursive' => -1));
        $val = '';
        $this->Song->Behaviors->attach('Containable');
        $countryPrefix = strtolower($this->params[$index]['Territory']) . "_";
        $this->Country->setTablePrefix($countryPrefix);
        foreach ($allAlbum as $k => $v) {
            $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'), 'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'],'Song.provider_type = Country.provider_type','Country.SalesDate !=' => '' ,'Country.SalesDate <='  => date('Y-m-d'), 'Country.DownloadStatus' => 1, 'TrackBundleCount' => 0, 'Country.Territory' => $this->params[$index]['Territory']), 'contain' => array('Country' => array('fields' => array('Country.Territory'))), 'recursive' => 0, 'limit' => 1));
            if (count($recordCount) > 0) {
                $val = $val . $v['Album']['ProdID'] . ",";
                if ($v['Album']['Advisory'] == 'T') {
                    $result[$v['Album']['ProdID'] . '-' . $v['Album']['provider_type']] = $v['Album']['AlbumTitle'].'<span class="explicit"> (Explicit)</span>';
                } else {                
                    $result[$v['Album']['ProdID'] . '-' . $v['Album']['provider_type']] = $v['Album']['AlbumTitle'];
                }
            }
        }
        $data = "<option value=''>SELECT</option>";
        foreach ($result as $k => $v) {
            $data = $data . "<option value='" . $k . "'>" . $v . "</option>";
        }
        print "<select class='select_fields' id='ArtistAlbum' name='album'>" . $data . "</select>";
        exit;
    }
    
    
    /**
     * @admin_getAlbumsForDefaultQueues
     *  return streamed albums with ajax call
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getAlbumsForDefaultQueues() {
        Configure::write('debug', 0);

        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $result = array();
        $allAlbum = $this->Album->find('all', array('fields' => array('Album.ProdID', 'Album.AlbumTitle', 'Album.provider_type', 'Album.Advisory'), 'conditions' => array('Album.ArtistText = ' => urldecode($this->params[$index]['artist'])), 'recursive' => -1));
        $val = '';
        $this->Song->Behaviors->attach('Containable');
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Genre')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));  
        $countryPrefix = strtolower($this->params[$index]['Territory']) . "_";
        $this->Country->setTablePrefix($countryPrefix);
        foreach ($allAlbum as $k => $v) {
            $recordCount = $this->Song->find('all', array('fields' => array('DISTINCT Song.ProdID'), 'conditions' => array('Song.ReferenceID' => $v['Album']['ProdID'],'Song.provider_type = Country.provider_type','Country.StreamingSalesDate !=' => '' ,'Country.StreamingSalesDate <='  => date('Y-m-d'), 'Country.StreamingStatus' => 1, 'Country.Territory' => $this->params[$index]['Territory']),'limit' => 1));
            if (count($recordCount) > 0) {
                $val = $val . $v['Album']['ProdID'] . ",";
                if ($v['Album']['Advisory'] == 'T') {
                    $result[trim($v['Album']['ProdID']) . '-' . trim($v['Album']['provider_type'])] = $v['Album']['AlbumTitle'].'<span class="explicit"> (Explicit)</span>';
                } else {                
                    $result[trim($v['Album']['ProdID']) . '-' . trim($v['Album']['provider_type'])] = $v['Album']['AlbumTitle'];
                }
            }
        }
        $data = "<option value=''>SELECT</option>";
        foreach ($result as $k => $v) {
            $data = $data . "<option value='" . $k . "'>" . $v . "</option>";
        }
        print "<select class='select_fields' id='ArtistAlbum' name='album'>" . $data . "</select>";
        exit;
    }    

     /**
     * @getSongs
     *  return songs in the selected album
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getSongs() {
        Configure::write('debug', 0);

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }
		$alb_det = explode('-', $this->params[$index]['albumProdId']);
        if (isset($alb_det[0])) {
            $albumProdId = $alb_det[0];
        }
		if (isset($alb_det[1])) {
            $provider_type = $alb_det[1];
        }
		
		$territory   = $this->params[$index]['Territory'];
		$artist_name = $this->params[$index]['artist'];
        $result = array();
      
        $val = '';
        $this->Song->Behaviors->attach('Containable');
        $countryPrefix = strtolower($this->params[$index]['Territory']) . "_";
        $this->Country->setTablePrefix($countryPrefix);
 	$songs = $this->getAlbumSongs(base64_encode($artist_name), $albumProdId, base64_encode($provider_type), 1 , $territory , 1);
        $data = "<option value=''>SELECT</option>";
        foreach ($songs[$albumProdId] as $k => $v) {
			$result[$v['Song']['ProdID']] = $v['Song']['SongTitle'];
        }
		foreach ($result as $k => $v) {
		$data = $data . "<option value='" . $k. "'>" . $v . "</option>";
		}
        print "<select class='select_fields' id='ArtistSong' name='songProdID'>" . $data . "</select>";
        exit;
    }
    
    
     /**
     * @getAlbumStreamSongs
     *  return songs in the selected album
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getAlbumStreamSongs() {
        Configure::write('debug', 0);

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }
	$alb_det = explode('-', $this->params[$index]['albumProdId']);
        if (isset($alb_det[0])) {
            $albumProdId = $alb_det[0];
        }
        if (isset($alb_det[1])) {
            $provider_type = $alb_det[1];
        }
		
        $territory   = $this->params[$index]['Territory'];
        $result = array();
      
        $val = '';
        
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Genre')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));  
        $countryPrefix = strtolower($this->params[$index]['Territory']) . "_";
        $this->Country->setTablePrefix($countryPrefix);
        $songs = $this->Song->find('all', array('fields' => array('Song.ProdID','Song.SongTitle'), 'conditions' => array('Song.ReferenceID' => $albumProdId,'Song.provider_type' => $provider_type, 'Song.provider_type = Country.provider_type',"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",'Country.StreamingSalesDate !=' => '' ,'Country.StreamingSalesDate <='  => date('Y-m-d'), 'Country.StreamingStatus' => 1, 'Country.Territory' => $this->params[$index]['Territory'])));
        $data = "<option value=''>SELECT</option>";
        foreach ($songs as $k => $v) {
			$result[$v['Song']['ProdID']] = $v['Song']['SongTitle'];
        }
		foreach ($result as $k => $v) {
		$data = $data . "<option value='" . $k. "'>" . $v . "</option>";
		}
        print "<select class='select_fields' id='ArtistSong' name='songProdID'>" . $data . "</select>";
        exit;
    }    

    /**
     * @getAutoArtist
     *  return top 5 artist names with ajax call
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getAutoArtist() {
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }

        $artist = $this->Song->find('all', array(
            'conditions' =>
            array('and' =>
                array(
                    array(
                        "(find_in_set('" . '"' . $this->params[$index]['Territory'] . '"' . "',Song.Territory) or Song.Territory = '" . '"' . $this->params[$index]['Territory'] . '"' . "' )",
                        'Song.provider_type' => 'sony',
                        'Song.ArtistText LIKE' => $this->params[$index]['Name'] . "%",
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
        if (!empty($artist)) {

            foreach ($artist AS $key => $val) {
                $html .= '<li>' . $val['Song']['ArtistText'] . '</li>';
            }
        } else {
            $html .= '<li>No record found</li>';
        }
        $html .= '</ul>';

        print $html;
        exit;
    }

    /**
     * @admin_getPlaylistAutoArtist
     *  return artist names allowed to a particular territory with ajax call
     *
     * $name
     *  string to be searchedin atrist name
     *
     * @return
     *  
     * */
    function admin_getPlaylistAutoArtist() {
        
        if ( $this->RequestHandler->isPost() ) {
            $index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
            $index = 'url';
        }
        $countryPrefix = strtolower($this->params[$index]['Territory']) . "_";
        $this->Country->setTablePrefix($countryPrefix);
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Genre')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));        
        $artist = $this->Song->find('all', array(
            'conditions' =>
                array('Song.provider_type = Country.provider_type','Song.ProdID = Country.ProdID','Song.ArtistText LIKE' => $this->params[$index]['Name'] . "%",'Country.StreamingSalesDate !=' => '' ,'Country.StreamingSalesDate <='  => date('Y-m-d'), 'Country.StreamingStatus' => 1, 'Country.Territory' => $this->params[$index]['Territory']),
            'fields' => array(
                'DISTINCT Song.ArtistText',
            ),
            'limit' => '0,20',
            'order' => 'Song.ArtistText'
        ));
        
        $html = '<ul style="max-height: 180px; overflow: auto;">';
        if (!empty($artist)) {

            foreach ($artist AS $key => $val) {
                $html .= '<li>' . $val['Song']['ArtistText'] . '</li>';
            }
        } else {
            $html .= '<li>No record found</li>';
        }
        $html .= '</ul>';

        print $html;
        exit;
    }
    
    
    /**
     * Function Name : composer
     * Description   : This function is used to liast all albums related to a composer
     * 
     */
    
    function composer($composer_text,$facetPage = 1) {
        
        $this->layout = 'home';
        $composer_text = base64_decode($this->params['pass'][0]); 
        if(!empty($this->params['pass'][1])) {
            $facetPage = $this->params['pass'][1];
        }
        if(isset($composer_text)){
            $totalFacetCount = $this->Solr->getFacetSearchTotal('"'.$composer_text.'"', 'album',1);
            $limit = 12;
            $albums = $this->Solr->groupSearch('"'.$composer_text.'"', 'album', $facetPage, $limit , 0, null, 1);
            $arr_albumStream = array();
            foreach ($albums as $objKey => $objAlbum) {
                $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                        array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                );
            }
            if (!empty($totalFacetCount)) {
                $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
            } else {
                $this->set('totalFacetPages', 0);
            }            
            $this->set('albumData', $albums);
            $this->set('arr_albumStream', $arr_albumStream);
            $this->set('composertext', $composer_text);
            $this->set('facetPage',$facetPage);
        }
    }
}
?>
