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
    
    function index(){// echo 123; die;  
        //$this->autoRender = false;
       // $this->autoRedirect = false;
         $this->layout = 'home';   
                 
         
//         echo "<pre>";
//         print_r($_POST);
//         die;
            if($_POST['hdn_remove_song']) 
            {
                // echo $_POST["Pdid"]; die;
                if(!empty($_POST["Pdid"]))
                {
                  // echo "Result: ". $this->QueuelistDetails->deleteAll($_POST["Pdid"]); 
                    $conditions = array (
                                                        "Pdid" => $_POST["Pdid"]										
                                        );
                                                
                    $delete_reponse	= $this->QueuelistDetails->delete(array('QueuelistDetails.Pdid' => $_POST["Pdid"])); 
                    echo $this->QueuelistDetails->lastQuery();
                    echo "<pre>";
                    print_r($delete_reponse);
                    die;
                    
                    
                }
               
            }
       
                    $libId = $this->Session->read('library');
                    $patId = $this->Session->read('patron');
                    $territory = $this->Session->read('territory');
                    $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
                    $this->set('libraryDownload',$libraryDownload);
                    $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
                    $this->set('patronDownload',$patronDownload);


                    //echo "<pre>";
                     //print_r($this->params['pass'][0]); die;

                    //echo "123";
                    $queue_list_array   =   $this->Queue->getQueueDetails($this->params['pass'][0]);
                   // echo 456;

                    $this->set('queue_list_array',$queue_list_array); 
                    $this->set('queue_id',$this->params['pass'][0]); 

       
         
        

    }
    
    
    
    
    
}


?>
