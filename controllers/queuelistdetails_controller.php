<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueueListDetailsController extends AppController{
    
    var $name = 'QueuesListDetails';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session', 'Wishlist',);
    var $components = array('Session', 'Auth', 'Acl' ,'Queue', 'Downloads');
    var $uses = array( 'QueueDetail','User','Album','Song', 'Wishlist','QueueList');
    
    function beforeFilter(){
           
            parent::beforeFilter();
            $this->Auth->allow('now_streaming', 'queue_details', 'index');
    }
    
    
    
    function index(){
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
                    $delqueueDetail = $this->QueueDetail->deleteAll(array('id' => $_POST["dqPlid"]), false);
                    $delqueue = $this->QueueList->deleteAll(array('Plid' => $_POST["dqPlid"]), false);

                    if( (true === $delqueueDetail) && (true === $delqueue) ) {
                        $this->Session ->setFlash('Queue has been deleted successfully', 'modal', array( 'class' => 'queue success' ));
                        $this->redirect('/queues/savedQueuesList/'.$this->Session->read('patron'));
                    }else{
                        $this->Session ->setFlash('Error occured while deleteing queue', 'modal', array( 'class' => 'queue problem' ));
                        $this->redirect($this->referer());                    
                    }                     
              }   
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
        $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0], $patId);
        $this->set('queue_list_array',$queue_list_array); 
        $this->set('queue_id',$this->params['pass'][0]); 
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

        $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0], $patId);
       // echo 456;


        // Find Total Duration

        $total_seconds = 0;

        foreach($queue_list_array as $k => $v)
        {
            $full_length    =   $v['Songs']['FullLength_Duration'];
            $temp_arr       =   explode(":", $full_length);
            $minutes        =   $temp_arr[0];
            $seconds        =   $temp_arr[1];
            $total_seconds +=   $minutes*60+$seconds;
        }

        $total_duration     =    $total_seconds/60;
        $total_minutes      =    floor($total_duration);
        $total_seconds      =    $total_seconds%60;

        $this->set('queue_list_array',$queue_list_array); 
        $this->set('queue_id',$this->params['pass'][0]); 
        $this->set('queue_songs_count',count($queue_list_array)); 
        $this->set('total_time',$total_minutes.":".$total_seconds); 
    }
    
    
    
}


?>
