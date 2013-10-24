<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueuesController extends AppController{
    
    var $name = 'Queues';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session', 'Queue');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue', 'Streaming');
    var $uses = array( 'QueueList', 'QueueDetail','User','Album','Song',  'StreamingHistory');
    
    function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('getDefaultQueues','savedQueuesList','createQueue','addToQueue', 'my_streaming_history');
    }
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     */
    
    function savedQueuesList($patron_id){        
       
        $this->layout = 'home';
        $queueData = $this->Queue->getQueueList($patron_id);
        $this->set('queueData',$queueData);
    }
    
    /**
     * function name : createQueueList
     * Description   : This function is used to create a new queue list
     */    
    
    function createQueue(){
        if(isset($this->data)) {
            if($this->Session->read("Auth.User.type_id") == 1){
                $this->data['QueueList']['queue_type']  = 1;
            }else{
                $this->data['QueueList']['queue_type']  = 0;
            }
            $this->data['QueueList']['created']  = date('Y-m-d H:i:s');
            $this->data['QueueList']['patron_id'] = $this->Session->read('patron');
            $this->QueueList->setDataSource('master');
            if($this->QueueList->save($this->data['QueueList'])){
                    $this->Session ->setFlash('Queue has been Added successfully', 'modal', array( 'class' => 'queue success' ));
                    $this->redirect($this->referer());						
            }
            else{
                    $this->Session ->setFlash('Error occured while adding queue', 'modal', array( 'class' => 'queue problem' ));
                    $this->redirect($this->referer());					
            }
            $this->QueueList->setDataSource('default');
        }
                                
    }
    
    /**
     * Function Name : addToQueue
     * Description   : This function is used to add a song to a Queue
     */
    
    function addToQueue(){
        Configure::write('debug', 0);
          
        
        if( $this->Session->read('library') && $this->Session->read('patron') && !empty($_REQUEST['songProdId']) && !empty($_REQUEST['songProviderType'])&& !empty($_REQUEST['albumProdId'])&& !empty($_REQUEST['albumProviderType'])&& !empty($_REQUEST['queueId']) ){
            
            $song_details  =   $this->Streaming->getStreamingDetails($_REQUEST['songProdId'], $_REQUEST['songProviderType']); // Fetch Information related to Streaming
//            echo "<pre>";
//            print_r($song_details);
            
            
            if ($this->Session->read('library_type') == 2 && $song_details['Country']['StreamingSalesDate'] <= date('Y-m-d') && $song_details['Country']['StreamingStatus'] == 1)
            {            
                    $queuesongsCount =  $this->QueueDetail->find('count',array('conditions' => array('queue_id' => $_REQUEST['queueId'],'song_prodid' => $_REQUEST['songProdId'],'song_providertype' => $_REQUEST['songProviderType'],'album_prodid' => $_REQUEST['albumProdId'],'album_providertype' => $_REQUEST['albumProviderType'])));
                    if(!$queuesongsCount)
                    {
                        $insertArr = Array();
                        $insertArr['queue_id'] = $_REQUEST['queueId'];
                        $insertArr['song_prodid'] = $_REQUEST['songProdId'];
                        $insertArr['song_providertype'] = $_REQUEST['songProviderType'];
                        $insertArr['album_prodid'] = $_REQUEST['albumProdId'];
                        $insertArr['album_providertype'] = $_REQUEST['albumProviderType'];
                        //insert into queuedetail table
                        $this->QueueDetail->setDataSource('master');
                        $this->QueueDetail->save($insertArr);
                        $this->QueueDetail->setDataSource('default');
                        echo "Success";
                        exit;

                    }
                    else
                    {
                            echo 'error1';
                            exit; 
                    }     
                
            }
            else    // Song is not allowed for streaming
            {
                     echo 'invalid_for_stream';
                     exit; 
            }
                
        }else{
            echo 'error';
            exit;
        }        
        
    }
    
    
    
    /**
     * Function Name  :  getDefaultQueues
     * Description    :  This function is used to get default queues created by Admin
     */

    function getDefaultQueues(){
        $cond = array('queue_type' => 1, 'status' => '1');

        // Unbinded User model
        $this->QueueList->unbindModel(
            array('belongsTo' => array('User'),'hasMany' => array('QueueDetail'))
        );        

        if ( ((Cache::read('defaultqueuelist')) === false)  || (Cache::read('defaultqueuelist') === null) ) {
            $queueData = $this->QueueList->find('all', array(
                    'conditions' => $cond,
                    'fields' => array('queue_id','queue_name','queue_type'),
                    'order' => 'QueueList.created DESC',
                    'limit' => 100
                  ));
            Cache::write("defaultqueuelist", $queueData);
        }else{
            $queueData = Cache::read("defaultqueuelist");
        }        
        return $queueData;             

    } 
    
    
     /*
     Function Name : my_streaming_history
     Desc : To show songs user downloaded in last 2 weeks
    */
    function my_streaming_history() {
        
      //  Configure::write('debug', 2);
     
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        
        $countryPrefix = $this->Session->read('multiple_countries');
        
        $sortArray = array('date', 'artist', 'album');
        $sortOrderArray = array('asc','desc');
        

        
        
        if(isset($_POST)){
            $sort = $_POST['sort'];
            $sortOrder = $_POST['sortOrder'];
        }
        
        
//        echo "<br>sort: ".$sort;
//        echo "<br>sortOrder: ".$sortOrder;
        
        if(!in_array($sort, $sortArray)){
            $sort = 'date';
        }
        
        if(!in_array($sortOrder, $sortOrderArray)){
            $sortOrder = 'desc';
        }
        
        switch($sort){
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
            /*case 'song':
                $songSortBy = 'Download.track_title';
                $videoSortBy = 'Videodownload.track_title';
                $sortType = $sortOrder;
                break;            
            */
        }
        
        $countryTableName = $countryPrefix .'countries';
        $streamingResults = Array();
        $streamingResults =  $this->StreamingHistory->find('all',
                                                                array('joins'=>array(
                                                                                        array('table' => 'Songs',
                                                                                              'alias' => 'Song',
                                                                                              'type' => 'LEFT',
                                                                                              'conditions' => array('StreamingHistory.ProdID = Song.ProdID','StreamingHistory.provider_type = Song.provider_type')
                                                                                              ),
                                                                                        array('table' => $countryTableName,
                                                                                              'alias' => 'Country',
                                                                                              'type' => 'LEFT',
                                                                                              'conditions' => array('Country.ProdID = Song.ProdID','Country.provider_type = Song.provider_type')
                                                                                             ),
                                                                                        array('table' => 'Albums',
                                                                                              'alias' => 'Album',
                                                                                              'type' => 'LEFT',
                                                                                               'conditions' => array('Song.ReferenceID = Album.ProdID','Song.provider_type = Album.provider_type')
                                                                                              ),  
                                                                                        array('table' => 'queue_details',
                                                                                              'alias' => 'QueueDetail',
                                                                                              'type' => 'LEFT',
                                                                                               'conditions' => array('QueueDetail.song_prodid = Song.ProdID','QueueDetail.song_providertype = Song.provider_type')
                                                                                              ),
                                                                                        array('table' => 'queue_lists',
                                                                                              'alias' => 'QueueList',
                                                                                              'type' => 'LEFT',
                                                                                               'conditions' => array('QueueList.queue_id = QueueDetail.queue_id','QueueDetail.song_providertype = Song.provider_type')
                                                                                              ),
                                                                                        array('table' => 'File',
                                                                                              'alias' => 'File',
                                                                                              'type' => 'LEFT',
                                                                                              'conditions' => array('Song.Sample_FileID = File.FileID')
                                                                                             )
                                                                                    ),
                                                                    'group' => 'StreamingHistory.ProdID, StreamingHistory.provider_type, StreamingHistory.createdOn',
                                                                    'conditions' => array('StreamingHistory.library_id' => $libraryId,
                                                                                          'StreamingHistory.patron_id' => $patronId,                                                                                          
                                                                                          'StreamingHistory.createdOn BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'),
                                                                                          Configure::read('App.twoWeekEndDate'))
                                                                                         ),
                                                                    'fields'=>array('SUM(StreamingHistory.consumed_time) as StreamingTime', 'Country.StreamingSalesDate', 'Country.StreamingStatus', 'QueueList.queue_id', 'QueueList.queue_name','Song.Advisory', 'Song.FullLength_Duration','Song.ReferenceID', 'Song.SongTitle', 'Song.ArtistText', 'Song.provider_type',  'StreamingHistory.ProdID','StreamingHistory.provider_type','StreamingHistory.patron_id','StreamingHistory.library_id','StreamingHistory.consumed_time','StreamingHistory.createdOn', 'Album.ProdID', 'Album.provider_type', 'Album.AlbumTitle', 'File.CdnPath', 'File.SourceURL'),
                                                                    'order'=>"$songSortBy $sortType")); 
        
	
        
//        echo "<br>Query: ".$this->StreamingHistory->lastQuery();
     //  echo '<pre>'; print_r($streamingResults);
    
        $this->set('streamingData',$streamingResults);

        $this->set('sort',$sort);
        $this->set('sortOrder',$sortOrder);
    }
    
}


?>
