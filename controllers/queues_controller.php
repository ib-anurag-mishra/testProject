<?php

/*
  File Name : queues_controller.php
  File Description : Queues controller page
  Author : m68interactive
 */

class QueuesController extends AppController
{

    var $name = 'Queues';
    var $layout = 'home';
    var $helpers = array('Html', 'Form', 'Session', 'Queue', 'Token');
    var $components = array('Session', 'Auth', 'Acl', 'Queue', 'Streaming', 'Common', 'RequestHandler');
    var $uses = array('QueueList', 'QueueDetail', 'User', 'Album', 'Song', 'StreamingHistory','Territory');

    function beforeFilter()
    {
        parent::beforeFilter();
        if ($this->Session->read('patron') != '')  //  After Login
        {
            $this->Auth->allow('*');
        }
        else  //  Before Login
        {
            $this->Auth->allow('');
        }
        
        if(($this->Session->read('Auth.User.type_id')) && (($this->Session->read('Auth.User.type_id') == 1))){
            $this->Auth->allow('admin_addPlaylist','admin_managePlaylist','admin_addPlaylist');
        }        
    }

    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     */
    function savedQueuesList($patron_id)
    {

        $this->layout = 'home';
        $queueData = $this->Queue->getQueueList($patron_id);
        $this->set('queueData', $queueData);
    }

    /**
     * function name : createQueueList
     * Description   : This function is used to create a new queue list
     */
    function createQueue()
    {
        if (isset($this->data))
        {

            $patron_id = $this->Session->read('patron');
            if (!empty($patron_id))
            {
                if ($this->Session->read("Auth.User.type_id") == 1)
                {
                    $this->data['QueueList']['queue_type'] = 1;
                }
                else
                {
                    $this->data['QueueList']['queue_type'] = 0;
                }
                $this->data['QueueList']['created'] = date('Y-m-d H:i:s');
                $this->data['QueueList']['patron_id'] = $this->Session->read('patron');


                $queue_name = $this->data['QueueList']['queue_name'];

                if (empty($queue_name))
                {
                    $this->Session->setFlash('Playlist Name is empty', 'modal', array('class' => 'queue problem'));
                    $this->redirect($this->referer());
                }
                else
                {
                    $this->QueueList->setDataSource('master');
                    if ($this->QueueList->save($this->data['QueueList']))
                    {
                        $this->QueueList->setDataSource('default');
                        $this->layout = 'ajax';
                        echo "Playlist has been Added successfully.&" . $this->QueueList->getLastInsertID() . "&" . $this->data['QueueList']['queue_name'];
                        exit;			
                    }
                    else
                    {
                        $this->QueueList->setDataSource('default');
                        $this->layout = 'ajax';
                        echo "Error occured while adding playlist";					
                    }
                }
            }
        }
    }

    /**
     * Function Name : addToQueue
     * Description   : This function is used to add a song to a Queue
     */
    function addToQueue()
    {
        Configure::write('debug', 0);

        if ( $this->RequestHandler->isPost() ) {
        	$index = 'form';
        } else if ( $this->RequestHandler->isGet() ) {
        	$index = 'url';
        }

        if ($this->Session->read('library') && $this->Session->read('patron') && !empty($this->params[$index]['songProdId']) && !empty($this->params[$index]['songProviderType']) && !empty($this->params[$index]['albumProdId']) && !empty($this->params[$index]['albumProviderType']) && !empty($this->params[$index]['queueId']))
        {

            $song_details = $this->Streaming->getStreamingDetails($this->params[$index]['songProdId'], $this->params[$index]['songProviderType']); // Fetch Information related to Streaming

            if ($this->Session->read('library_type') == 2 && $song_details['Country']['StreamingSalesDate'] <= date('Y-m-d') && $song_details['Country']['StreamingStatus'] == 1)
            {
                $insertArr = Array();
                $insertArr['queue_id'] = $this->params[$index]['queueId'];
                $insertArr['song_prodid'] = $this->params[$index]['songProdId'];
                $insertArr['song_providertype'] = $this->params[$index]['songProviderType'];
                $insertArr['album_prodid'] = $this->params[$index]['albumProdId'];
                $insertArr['album_providertype'] = $this->params[$index]['albumProviderType'];
                //insert into queuedetail table
                $this->QueueDetail->setDataSource('master');
                $this->QueueDetail->save($insertArr);
                $this->QueueDetail->setDataSource('default');
                echo "Success";
                exit;
            }
            else    // Song is not allowed for streaming
            {
                echo 'invalid_for_stream';
                exit;
            }
        }
        else
        {
            echo 'error';
            exit;
        }
    }

    /**
     * Function Name  :  addAlbumSongsToQueue
     * Description    :  This function is used to add album songs to queue
     */
    function addAlbumSongsToQueue()
    {
    	if ( $this->RequestHandler->isPost() ) {
    		$index = 'form';
    	} else if ( $this->RequestHandler->isGet() ) {
    		$index = 'url';
    	}

        $albumSongs = json_decode($this->params[$index]['albumSongs'], true);
        Configure::write('debug', 0);
        if ($this->Session->read('library') && $this->Session->read('patron') && !empty($albumSongs))
        {
            if ($this->Session->read('library_type') == 2)
            {
                if (!empty($albumSongs))
                {
                    $this->QueueDetail->setDataSource('master');
                    $this->QueueDetail->saveAll($albumSongs);
                    $this->QueueDetail->setDataSource('default');
                    echo "Success";
                    exit;
                }
            }
            else    // Song is not allowed for streaming
            {
                echo 'invalid_for_stream';
                exit;
            }
        }
        else
        {
            echo 'error';
            exit;
        }
    }

    /**
     * Function Name  :  getDefaultQueues
     * Description    :  This function is used to get default queues created by Admin
     */
    function getDefaultQueues()
    {
        $cond = array('queue_type' => 1, 'status' => '1');

        // Unbinded User model
        $this->QueueList->unbindModel(
                array('belongsTo' => array('User'), 'hasMany' => array('QueueDetail'))
        );
        $queueData = Cache::read("defaultqueuelist");
        if ($queueData === false)
        {
            $queueData = $this->QueueList->find('all', array(
                'conditions' => $cond,
                'fields' => array('queue_id', 'queue_name', 'queue_type'),
                'order' => 'QueueList.created DESC',
                'limit' => 100
            ));
            Cache::write("defaultqueuelist", $queueData);
        }
        return $queueData;
    }

    /*
      Function Name : my_streaming_history
      Desc : To show songs user downloaded in last 2 weeks
     */

    function my_streaming_history()
    {

        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');

        $countryPrefix = $this->Session->read('multiple_countries');

        $sortArray = array('date', 'artist', 'album');
        $sortOrderArray = array('asc', 'desc');
        $sortOrder = '';
        $sort = '';
        if ( $this->RequestHandler->isPost() )
        {
            $sort = $this->params['form']['sort'];
            $sortOrder = $this->params['form']['sortOrder'];
        }

        if (!in_array($sort, $sortArray))
        {
            $sort = 'date';
        }

        if (!in_array($sortOrder, $sortOrderArray))
        {
            $sortOrder = 'desc';
        }

        switch ($sort)
        {
            case 'date':
                $songSortBy = 'StreamingHistory.createdOn';
                $sortType = $sortOrder;
                break;
            case 'artist':
                $songSortBy = 'Song.ArtistText';
                $sortType = $sortOrder;
                break;
            case 'album':
                $songSortBy = 'Album.AlbumTitle';
                $sortType = $sortOrder;
                break;
        }

        $countryTableName = $countryPrefix . 'countries';
        $streamingResults = Array();
        $streamingResults = $this->StreamingHistory->find('all', array('joins' => array(
                array('table' => 'Songs',
                    'alias' => 'Song',
                    'type' => 'LEFT',
                    'conditions' => array('StreamingHistory.ProdID = Song.ProdID', 'StreamingHistory.provider_type = Song.provider_type')
                ),
                array('table' => $countryTableName,
                    'alias' => 'Country',
                    'type' => 'LEFT',
                    'conditions' => array('Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type')
                ),
                array('table' => 'Albums',
                    'alias' => 'Album',
                    'type' => 'LEFT',
                    'conditions' => array('Song.ReferenceID = Album.ProdID', 'Song.provider_type = Album.provider_type')
                ),
                array('table' => 'queue_lists',
                    'alias' => 'QueueList',
                    'type' => 'LEFT',
                    'conditions' => array('QueueList.queue_id = StreamingHistory.songs_queue_id')
                ),
                array('table' => 'File',
                    'alias' => 'Full_Files',
                    'type' => 'LEFT',
                    'conditions' => array('Song.FullLength_FileID = Full_Files.FileID')
                )
            ),
            'group' => 'StreamingHistory.ProdID, StreamingHistory.provider_type, QueueList.queue_id',
            'conditions' => array('StreamingHistory.library_id' => $libraryId,
                'StreamingHistory.patron_id' => $patronId,
                'StreamingHistory.createdOn BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'),
                    Configure::read('App.twoWeekEndDate'))
            ),
            'fields' => array('SUM(StreamingHistory.consumed_time) as StreamingTime', 'Country.StreamingSalesDate', 'Country.StreamingStatus', 'QueueList.queue_id', 'QueueList.queue_name', 'QueueList.queue_type', 'Song.Advisory', 'Song.FullLength_Duration', 'Song.ReferenceID', 'Song.SongTitle', 'Song.ArtistText', 'Song.provider_type', 'StreamingHistory.ProdID', 'StreamingHistory.provider_type', 'StreamingHistory.patron_id', 'StreamingHistory.library_id', 'StreamingHistory.consumed_time', 'StreamingHistory.createdOn', 'Song.ProdID', 'Album.provider_type', 'Album.AlbumTitle', 'Full_Files.CdnPath', 'Full_Files.SaveAsName'),
            'order' => "$songSortBy $sortType"));

        $this->set('streamingData', $streamingResults);

        $this->set('sort', $sort);
        $this->set('sortOrder', $sortOrder);
    }

    /*
      Function Name : queueListAlbums
      Desc : This function is used to get mark up for add to queue for albums
     */

    function queueListAlbums()
    {
        $this->layout = 'ajax';
        if(empty($this->params['form']['prodID'])) {
            $this->params['form']['prodID'] = '';
        }
        
        if(empty($this->params['form']['type'])) {
            $this->params['form']['type'] = '';
        }        
        
        $prodID = $this->params['form']['prodID']; 
        $type = $this->params['form']['type'];
        $queueId = $this->params['form']['QueueID'];

        $patronID = $this->Session->read("patron");

        if ($type == 'album')
        {
            $albumDetails = array_pop(array_pop($this->Common->getQueueAlbumDetails($prodID)));


            $albumSongs = $this->requestAction(
                    array('controller' => 'artists', 'action' => 'getAlbumSongs'), array(
                'pass' => array(
                    base64_encode($albumDetails['ArtistText']),
                    $albumDetails['ProdID'],
                    base64_encode($albumDetails['provider_type'])
                )
                    )
            );

            $queueList = $this->Queue->getAlbumEncodeSongsList(
                    $patronID, $albumSongs[$albumDetails['ProdID']], $albumDetails['ProdID'], $albumDetails['provider_type'], $queueId);


            //adding album songs to queue
            $decodedAlbumSongs = json_decode($queueList, true);

            if ($this->Session->read('library') && $this->Session->read('patron') && !empty($decodedAlbumSongs))
            {
                if ($this->Session->read('library_type') == 2)
                {
                    if (!empty($decodedAlbumSongs))
                    {
                        $this->QueueDetail->setDataSource('master');
                        $this->QueueDetail->saveAll($decodedAlbumSongs);
                        $this->QueueDetail->setDataSource('default');
                        echo "Success|$type";
                        exit;
                    }
                }
                else    // Song is not allowed for streaming
                {
                    echo "invalid_for_stream|$type";
                    exit;
                }
            }
            else
            {
                echo "error|$type";
                exit;
            }
        }
        else if ($type == 'song')
        {            
            echo $this->addSongToPlaylist($prodID, $queueId, $type);                    
        }
        else if ($type == 'multi')
        {
            $message = '';

            foreach ($prodID as $song)
            {
                $song_detail = explode('&', $song);
                $message = $this->addSongToPlaylist($song_detail[0], $queueId, $song_detail[1]);          
            }

            echo $message;
        }
        exit;
    }
    
    /**
     * 
     * @param type $prodID
     * @param type $queueId
     * @param type $type
     * @return type
     */
    function addSongToPlaylist($prodID, $queueId, $type)
    {
        Configure::write('debug', 2);
        $songDetails = array_pop($this->Common->getSongsDetails($prodID));

        if ($this->Session->read('library') && $this->Session->read('patron') && !empty($prodID) && !empty($songDetails['Song']['provider_type']) && 
                !empty($songDetails['Albums']['ProdID']) && !empty($songDetails['Albums']['provider_type']) && !empty($queueId))
        {
            if ($this->Session->read('library_type') == 2 && $songDetails['Country']['StreamingSalesDate'] <= date('Y-m-d') && $songDetails['Country']['StreamingStatus'] == 1)
            {
                $insertArr = Array();
                $insertArr['queue_id'] = $queueId;
                $insertArr['song_prodid'] = $songDetails['Song']['ProdID'];
                $insertArr['song_providertype'] = $songDetails['Song']['provider_type'];
                $insertArr['album_prodid'] = $songDetails['Albums']['ProdID'];
                $insertArr['album_providertype'] = $songDetails['Albums']['provider_type'];

                //insert into queuedetail table
                $this->QueueDetail->setDataSource('master');
                $this->QueueDetail->create();      //Prepare model to save record
                $this->QueueDetail->save($insertArr);
                $this->QueueDetail->setDataSource('default');
                return "Success|$type";
            }
            else
            {
                // Song is not allowed for streaming
                return "invalid_for_stream|$type";
            }
        }
        else
        {
            return "error|$type";
        }
    }
    
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     */
    function ajaxSavedQueuesList()
    {
        $patron_id = $this->Session->read("patron");
        $this->layout = 'ajax';
        $queueData = $this->Queue->getQueueList($patron_id);
        $this->set('queueData', $queueData);
    }
    
    /**
     * function name : admin_addplaylist
     * Description   : This is used to add default playlists
     */
    
    function admin_addplaylist() { 
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
                $this->set('formAction', 'admin_insertplaylist/id:' . $artistId);
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
            $this->set('formAction', 'admin_insertplaylist');
            $this->set('formHeader', 'Add Playlist');
            $condition = 'add';
            $artistName = '';
        }        
    }
    
    function admin_insertplaylist() {
        
    }
    
    /**
     * function name : admin_managePlaylist
     * Description   : This is used to manage playlists
     */
    
    function admin_manageplaylist() {
        
    }    

}
?>