<?php

/*
 File Name : queues_controller.php
 File Description : Queues controller page
 Author : m68interactive
 */

class QueuesController extends AppController{
    
    var $name = 'Queues';
    var $layout = 'home';
    var $helpers = array( 'Html', 'Form', 'Session');
    var $components = array('Session', 'Auth', 'Acl' ,'Queue');
    var $uses = array( 'Queuelist','QueuelistDetails','User','Album','Song');
    
    function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('savedQueuesList');
    }
    
    /**
     * function name : queueList
     * Description   : This function is used to retrieve all the queues created by an individual
     * return        : List of queues 
     */
    
    function savedQueuesList($patron_id){
        $this->layout = 'home';
        $this->Queue->getQueueList($patron_id);
        
    }
    
    
    
    
    
}


?>
