<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueueListDetailsController extends AppController{
    
    var $name = 'QueuesListDetails';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue');
    var $uses = array( 'Queuelist','QueuelistDetails','User','Album','Song');
    
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
     //  $this->Queue->getQueueList($patron_id);
        
        
        
    }
    
    
    
    
    
}


?>
