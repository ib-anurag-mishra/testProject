<?php
/*
	 File Name : queue.php
	 File Description : helper file for getting queue detail
	 Author : m68interactive
 */
class QueueHelper extends AppHelper {
    var $uses = array('QueueList');
    
    function getQueuesList($patron_id) {
        if(!empty($patron_id)){
			$queueInstance = ClassRegistry::init('QueueList');
			$queueInstance->recursive = -1;
			$queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id,'status' => 1),'fields' =>  array('QueueList.queue_id', 'QueueList.queue_name'),'order' => 'QueueList.created DESC'));
		}else{
			$queueList = array();
		}	
	    return $queueList;
    }
}

?>