<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueueListDetailsController extends AppController{
    
    var $name = 'QueuesListDetails';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session', 'Wishlist','Queue');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue', 'Downloads','Streaming');
    var $uses = array( 'QueueDetail','User','Album','Song', 'Wishlist','QueueList');
    
    function beforeFilter(){
           
            parent::beforeFilter();
            // $this->Auth->allow('now_streaming', 'queue_details', 'index','getPlaylistData','clearNowStreamingSession', 'ajaxQueueValidation');     
            if($this->Session->read('patron')!='')  //  After Login
            {
                    $this->Auth->allow('*');
            }
            else  //  Before Login
            {
                     $this->Auth->allow('');
            }
    }
    
    
    
    function index(){
        $patron_id = $this->Session->read('patron');
        if(!empty($patron_id)){
            $this->QueueDetail->setDataSource('master');
            $this->QueueList->setDataSource('master');
            if($_POST['hdn_remove_song']) 
            {
                if(!empty($_POST["Pdid"]))
                {       
                    if($this->QueueDetail->deleteAll(array('id' => $_POST["Pdid"]),false)){
                            $this->Session ->setFlash('Song has been deleted successfully from queue', 'modal', array( 'class' => 'queue success' ));
                            $this->redirect($this->referer());						
                    }
                    else{
                            $this->Session ->setFlash('Error occured while deleting song from queue', 'modal', array( 'class' => 'queue problem' ));
                            $this->redirect($this->referer());					
                    }               
                }

            }
            else if($_POST['hid_action']=='rename_queue') 
            {
                if(!empty($_POST["rqPlid"])){
                    $this->data['QueueList']['queue_id'] = $_POST["rqPlid"];
                    $this->QueueList->set($this->data['QueueList']);
                    if($this->QueueList->save()){
                                $this->Session ->setFlash('Queue has been renamed successfully', 'modal', array( 'class' => 'queue success' ));
                                $this->redirect($this->referer());						
                        }
                        else{
                                $this->Session ->setFlash('Error occured while renaming queue', 'modal', array( 'class' => 'queue problem' ));
                                $this->redirect($this->referer());					
                    }
                }   

            }
            else if($_POST['hid_action']=='delete_queue')
            {                         
                  if(!empty($_POST["dqPlid"])){
                        $delqueueDetail = $this->QueueDetail->deleteAll(array('queue_id' => $_POST["dqPlid"]), false);
                        $delqueue = $this->QueueList->deleteAll(array('queue_id' => $_POST["dqPlid"]), false);

                        if( (true === $delqueueDetail) && (true === $delqueue) ) {
                            $this->Session ->setFlash('Queue has been deleted successfully', 'modal', array( 'class' => 'queue success' ));
                            $this->redirect('/queues/savedQueuesList/'.$this->Session->read('patron'));
                        }else{
                            $this->Session ->setFlash('Error occured while deleteing queue', 'modal', array( 'class' => 'queue problem' ));
                            $this->redirect($this->referer());                    
                        }                     
                  }   
            }
            $this->QueueDetail->setDataSource('default');
            $this->QueueList->setDataSource('default');
        }
        
    }
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     * return        : List of queues 
     */
    
    function now_streaming()
    {   
        $this->layout = 'home';   
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $this->set('libraryDownload',$libraryDownload);
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('patronDownload',$patronDownload);
        $queueId = $this->Session->read('queuePlaying');
        $songPlaying = $this->Session->read('songPlaying');
        if(!empty($queueId)){
            $queue_list_array   =   $this->Queue->getQueueDetails($queueId);
            $total_seconds = 0;
            foreach($queue_list_array as $k => $v)
            {
                $full_length    =   $v['Songs']['FullLength_Duration'];
                $temp_arr       =   explode(":", $full_length);
                $minutes        =   $temp_arr[0];
                $seconds        =   $temp_arr[1];
                $total_seconds +=   $minutes*60+$seconds;
                $filePath = shell_exec('perl files/tokengen_streaming ' . $v['SongFile']['SCdnPath']."/".$v['SongFile']['SSaveAsName']);
                if(!empty($filePath)){
                    $songPath = explode(':',$filePath);
                    $streamUrl =  trim($songPath[1]);
                    $queue_list_array[$k]['streamUrl'] = $streamUrl;
                }
            }

            $total_duration     =    $total_seconds/60;
            $total_minutes      =    floor($total_duration);
            $total_seconds      =    $total_seconds%60;

            $this->set('queue_list_array',$queue_list_array); 
            $this->set('queue_id',$this->params['pass'][0]); 
            $this->set('queue_songs_count',count($queue_list_array)); 
            $this->set('total_time',$total_minutes.":".$total_seconds);             
        }else if(!empty($songPlaying)){
            $trackDetails = $this->Queue->getNowstreamingSongDetails($songPlaying['prodId'] , $songPlaying['providerType']);
            foreach($trackDetails as $k => $v)
             {
                 $filePath = shell_exec('perl files/tokengen_streaming '. $v['SongFile']['SCdnPath']."/".$v['SongFile']['SSaveAsName']);
                 if(!empty($filePath)){
                    $songPath = explode(':',$filePath);
                    $streamUrl =  trim($songPath[1]);
                    $trackDetails[$k]['streamUrl'] = $streamUrl;
                 }
             } 
            $this->set('trackDetails',$trackDetails);            
        }
    }
    
    function queue_details()
    {

        $this->layout = 'home';   
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $territory = $this->Session->read('territory');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $this->set('libraryDownload',$libraryDownload);
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('patronDownload',$patronDownload);
        
        //echo '<pre>'; print_r($this->params); die;
        
        if($this->params['pass'][1]=='1')   //  Default Queue
        {        
                if ($queue_list_array = Cache::read("defaultqueuelistdetails" . $this->params['pass'][0]) === false) {
                $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0]);
                 if (!empty($queue_list_array)) {
                    
                        Cache::write("defaultqueuelistdetails" . $this->params['pass'][0], $queue_list_array);
                    }
               }
                
                $queue_list_array = Cache::read("defaultqueuelistdetails" . $this->params['pass'][0]);
               // print_r($queue_list_array );die;
                $this->set('queueType', 'Default'); 
        }
        else        // Custom Queue
        {
            $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0]);
            $this->set('queueType', 'Custom'); 
        }
        
        
        
        //echo 456;
        //Find Total Duration
        $total_seconds = 0;
        foreach($queue_list_array as $k => $v)
        {
            $full_length    =   $v['Songs']['FullLength_Duration'];
            $temp_arr       =   explode(":", $full_length);
            $minutes        =   $temp_arr[0];
            $seconds        =   $temp_arr[1];
            $total_seconds +=   $minutes*60+$seconds;
            $filePath = shell_exec('perl files/tokengen_streaming ' . $v['SongFile']['SCdnPath']."/".$v['SongFile']['SSaveAsName']);
            if(!empty($filePath)){
                $songPath = explode(':',$filePath);
                $streamUrl =  trim($songPath[1]);
                $queue_list_array[$k]['streamUrl'] = $streamUrl;
            }
        }

        $total_duration     =    $total_seconds/60;
        $total_minutes      =    floor($total_duration);
        $total_seconds      =    $total_seconds%60;

        $this->set('queue_list_array',$queue_list_array); 
        $this->set('queue_id',$this->params['pass'][0]); 
        $this->set('queue_songs_count',count($queue_list_array)); 
        $this->set('total_time',$total_minutes.":".$total_seconds); 
    }
    
    function getPlaylistData(){
       Configure::write('debug', 0);
       $prodId = $_POST['prodId'];
       $provider = $_POST['providerType'];
       $eventType = $_POST['eventFired'];
       $userStreamedTime = $_POST['userStreamedTime'];
       $songDuration = $_POST['songLength'];
       $libId = $this->Session->read('library');
       $patId = $this->Session->read('patron');
       $this->Session->delete('queuePlaying');
       $this->Session->delete('songPlaying');
       $eventArray = array(5,12,13,14,15,16,17,18,19);
       
       $validationResponse = $this->Streaming->validateSongStreaming($libId,$patId,$prodId, $provider,$userStreamedTime,$eventType,'',$songDuration,"'".$_POST['queueId']."'");
       if(!empty($validationResponse)){
           if($validationResponse[0] == 0){
               //$error_message = array('error' => $validationResponse);
               echo json_encode($validationResponse);
               exit;
           }else if($validationResponse[0] == 1){
               if(!in_array($eventType,$eventArray)){
                    if(!empty($_POST['queueId'])){
                        $this->Session->write("queuePlaying", $_POST['queueId']);
                    }else{
                        if(!empty($prodId) && !empty($provider)){
                             $songDetails = array('prodId' => $prodId,'providerType' => $provider);
                             $this->Session->write("songPlaying", $songDetails);
                        }
                    }
               }
               //$success_message = array('success' => $validationResponse);
               echo json_encode($validationResponse);
               exit;
           }else{
               $this->log('Componenet gave some other value other than 0/1 in response','streaming'); 
               $error_message = array('error1' => $validationResponse);
               echo json_encode($validationResponse);
               exit;                 
               
           }
           
       }else{
               $this->log('Componenet gave empty response and the response is'.$validationResponse,'streaming'); 
               $error_message = array('error' => $validationResponse);
               echo json_encode($validationResponse);
               exit;           
       }
    } 
    
    function clearNowStreamingSession(){
       $this->Session->delete('queuePlaying');
       $this->Session->delete('songPlaying');
       echo "success";
       exit;
    }
    
    function ajaxQueueValidation() 
    {        
            $this -> layout = 'ajax';
             
            if($this->Session->read('patron')=='')
            {
                    echo 'Patron Not Login'; die;
            }
            elseif($this->data['QueueList']['queue_name']=='')
            {                
                    echo 'Queue Name is empty'; die;
            }   
            else
            {    
                if($this->Session->read("Auth.User.type_id") == 1)
                {
                    $queue_type  = 1;
                }
                else
                {
                    $queue_type  = 0;
                }
                                
                $cond = array('queue_type' => $queue_type, 'status' => '1', 'patron_id' => array($this->Session->read('patron')), 'queue_name' => $this->data['QueueList']['queue_name']);
                
                $queueData = $this->QueueList->find('all', array(
                    'conditions' => $cond,
                    'fields' => array('queue_id'),
                    'order' => 'QueueList.created DESC'                    
                  ));
                                                
                if(count($queueData)==0)
                {
                    echo 'Insertion Allowed'; 
                }
                else
                {
                    echo 'Queue Name you entered is already present. Please try different name.'; 
                }
                
                die;                
            }
       
            die;
    }
            
}


?>
