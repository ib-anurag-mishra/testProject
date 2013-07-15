<?php

 /*
 File Name : queue.php
 File Description : Component page for the  queues functionality.
 Author : m68interactive
 */

Class QueueComponent extends Object
{
    
    var $uses = array('Queuelist','QueuelistDetails','User','Album','Song');
    
    /**
     * Function name : getQueueList
     * Description   : This is used to retrieve the list of queues created by an individual
     */
    function getQueueList($patronID){

        $downloadInstance = ClassRegistry::init('Queuelist');
        $cond = array('patronID' => $patronID, 'status' => '1');
        $downloadInstance->find('all', array(
                'conditions' => $cond,
                'recursive' => -1,
                'order' => 'Created DESC'
              ));
        
    }
    
    
    
    
    
}


?>
