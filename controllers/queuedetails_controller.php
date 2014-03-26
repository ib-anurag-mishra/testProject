<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueueDetailsController extends AppController{
    
    var $name = 'QueuesDetails';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session', 'Wishlist',);
    var $components = array('Session', 'Auth', 'Acl' ,'Queue', 'Downloads');
    var $uses = array( 'Queuelist','QueuelistDetails','User','Album','Song', 'Wishlist');
    
    function beforeFilter(){
       
            parent::beforeFilter();
            $this->Auth->allow('index');
    }
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     * return        : List of queues 
     */
    
    function index(){
        
         $this->layout = 'home';   

            if($_POST['hdn_remove_song']) 
            {
                if(!empty($_POST["Pdid"]))
                {
 
                    $conditions = array (
                                                        "Pdid" => $_POST["Pdid"]										
                                        );
                                                
                    $delete_reponse	= $this->QueuelistDetails->delete(array('Pdid' => $_POST["Pdid"])); 
                }
               
            }
       
                    $libId = $this->Session->read('library');
                    $patId = $this->Session->read('patron');
                    $territory = $this->Session->read('territory');
                    $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
                    $this->set('libraryDownload',$libraryDownload);
                    $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
                    $this->set('patronDownload',$patronDownload);

                    $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0]);
                    
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