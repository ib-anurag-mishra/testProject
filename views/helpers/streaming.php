<?php
/*
	 File Name : queue.php
	 File Description : helper file for getting queue detail
	 Author : m68interactive
 */
class StreamingHelper extends AppHelper {
    var $uses = array('StreamingRecords' , 'Library');    
    
     /*
     Function Name : getTotalStreamTime
     Desc : get Stream Time of Patron for a given Library
     * 
     * @param   patron_id, library_id
     *          
     * @return Boolean or second value
    */
    function getTotalStreamTime($library_id, $patron_id)
    {                    
            $streamingInstance = ClassRegistry::init('StreamingRecords');
            $streamingInstance->recursive = -1;
            $streamingDetails = $streamingInstance->find('first', array('conditions' => array('patron_id' => $patron_id, 'library_id' => $library_id), 'fields' => 'consumed_time'));
        
            return $streamingDetails['StreamingRecords']['consumed_time'];      
    }
    
    
      /*
     Function Name : getLastStreamDate
     Desc : get Stream Time of Patron for a given Library
     * 
     * @param   patron_id, library_id
     *          
     * @return Boolean or second value
    */
    function getLastStreamDate($library_id, $patron_id)
    {                    
            $streamingInstance = ClassRegistry::init('StreamingRecords');
            $streamingInstance->recursive = -1;
            $streamingDetails = $streamingInstance->find('first', array('conditions' => array('patron_id' => $patron_id, 'library_id' => $library_id), 'fields' => 'modified_date'));
        
            return $streamingDetails['StreamingRecords']['modified_date'];      
    }
    
   function admin_getLibraryIdsStream() {
          
        //$territory = $_REQUEST['Territory'];        
       // $libValue = isset($_REQUEST['lib_id'])? $_REQUEST['lib_id']:'';
        $data = '';
        $var = array();
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
        
            $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_admin_id' => $this->Session->read("Auth.User.id"), 
                    'Library.library_type = 2'),  
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
            
        } elseif ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '') {
        
              $var = $this->Library->find("list", array(
                "conditions" => array(
                    'Library.library_apikey' => $this->Session->read("Auth.User.consortium"), 
                    'Library.library_type = 2' 
                    ), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1));
              
        } else {
         
            $var = $this->Library->find('list', array(
                'conditions' => array(
                   // 'Library.library_territory' => $territory, 
                    'Library.library_type =2'), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
            $data = "<option value='all'>All Libraries</option>";
        }
        
         return $var;
         
//        foreach ($var as $k => $v) {
//            
//            $selected= '';
//            if(isset($libValue) && $libValue == $k){
//                 $selected= 'selected';
//            }
//            
//            $data = $data . "<option value=" . $k . " ".$selected.">" . $v . "</option>";
//        }
//        print "<select class='select_fields' name='library_id' id='library_id'>" . $data . "</select>";
//        exit;
    }
}

?>