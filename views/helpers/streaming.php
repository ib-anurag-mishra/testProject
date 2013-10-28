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
    function getTotalStreamTime($patron_id, $library_id){   //Configure::write('debug', 2);     echo 'in';
        
        if($patron_id==8389)
        {
            
            $streamingInstance = ClassRegistry::init('StreamingRecords');
            $streamingInstance->recursive = -1;
            $streamingDetails = $this->$streamingInstance->find('first', array('conditions' => array('patron_id' => $patron_id, 'library_id' => $library_id), 'fields' => 'consumed_time'));
            print_r($streamingDetails);
        }        
     //   echo '';
        
        return $streamingDetails;      
    }
    
    
}

?>