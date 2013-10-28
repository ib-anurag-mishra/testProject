<?php
/*
	 File Name : queue.php
	 File Description : helper file for getting queue detail
	 Author : m68interactive
 */
class StreamingHelper extends AppHelper {
    var $uses = array('StreamingRecords');    
    
     /*
     Function Name : getTotalStreamTime
     Desc : get Stream Time of Patron for a given Library
     * 
     * @param   patron_id, library_id
     *          
     * @return Boolean or second value
    */
    function getTotalStreamTime($library_id, $patron_id){    //echo 'in'.$patron_id;
        
        if($patron_id=='8389')
        {     
            //echo 'patron_id: '.$patron_id.", library_id: ".$library_id;
            $streamingInstance = ClassRegistry::init('StreamingRecords');
            $streamingInstance->recursive = -1;
            $streamingDetails = $streamingInstance->find('first', array('conditions' => array('patron_id' => $patron_id, 'library_id' => $library_id), 'fields' => 'consumed_time'));
            
        }             
        
        return $streamingDetails['StreamingRecords']['consumed_time'];      
    }
    
    
}

?>