<?php
 /*
 File Name : streaming.php
 File Description : Component page for the  song streaming functionality.
 Author : m68interactive
 */
 
Class StreamingComponent extends Object
{
    var $components = array('Auth','Session');
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /*
     Function Name : validateStreamingDurationInfo
     Desc : function used for checking patron streaming
     * 
     * 
     * @param $patId Int  'Patron Unique id'
     * @param $libId Int  'library Unique id'
     * @param $isMobileDownload Boolean  'check for mobile download'
     * @param $mobileTerritory Boolean  'check for mobile territory'
     * @param $patId Int  'Uniqe patron id'
     * @param $agent Int  'Browser user agent'
     * @param $library_id Int  'Uniq library id'
     * @return Boolean
    */
    function validateStreamingDurationInfo($libId,$patId, $songDuration,$isMobileDownload = false, $mobileTerritory = null,$agent = null) {
        
       
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');      
        $streamingRecordsInstance->recursive = -1;
        
        if(!$isMobileDownload){
          
          $uid = $patId;
          $libId = $libId;
          $ip = $_SERVER['REMOTE_ADDR'];
          $channel = 'Website';
          
        
        } else {
            $uid = $patId;
            $libId = $libId;
            $ip = $agent;
            $channel = 'Mobile App';
            
        }
      
        $streamingRecordsResults = $streamingRecordsInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id' => $patId)));
        
        if(!empty($streamingRecordsResults)){
           
            $consumed_time = $streamingRecordsResults['StreamingRecords']['consumed_time'];
            $updatedDate = $streamingRecordsResults['StreamingRecords']['modified_date'];
           
            //check patron time limit 
            if($this->checkPatronStreamingLimitForDay($consumed_time,$updatedDate)){
                $limitToPlaySong = $songDuration + $consumed_time;
                if($this->checkPatronStreamingLimitForDay($limitToPlaySong,$updatedDate)){
                    return array(true,'successfully able to streaming this song.', 1);
                }else{
                    $this->log($channel." : Rejected streaming request for patron:".$patId.";libid:".$libId.";User:".$uid.";IP:".$ip.";limitToPlaySong:".$limitToPlaySong.";updatedDate:".$updatedDate." as the patron limit is over to stream this song",'streaming');
                    return array(false,'Your song streaming limit is over for the day.', 2);
                }                
            }else{
                $this->log($channel." : Rejected streaming request for patron:".$patId.";libid:".$libId.";User:".$uid.";IP:".$ip.";limitToPlaySong:".$limitToPlaySong.";updatedDate:".$updatedDate." as the patron limit is over for the day",'streaming');
                return array(false,'Your song streaming limit is over for the day.', 3);
            }
        }else {
            $this->log($channel." : Rejected streaming request for patron:".$patId.";libid:".$libId.";User:".$uid.";IP:".$ip." as the  library and patron information not exist in streaming_records tables",'streaming');
            return array(false,'There are no record exist for update.', 4);
        }
    }
    
    
     /*
     Function Name : checkPatronStreamingLimit
     Desc : function used for checking patron streaming limit
     * 
     * @param $consumed_time int  'time seconds value'
     * @param $updatedDate timestamp  'modified date of record'
  
     * @return Boolean
    */
    function checkPatronStreamingLimitForDay($consumed_time,$updatedDate){
        //per day allowed time for patron in seconds
         $onlyDate = date('Y-m-d',strtotime($updatedDate));
         if(strtotime($onlyDate) == strtotime(date('Y-m-d'))){ 
            //total allow time in  second for the day 
            $allowedTime = Configure::read('App.streaming_time');
            if($consumed_time <= $allowedTime){
                return true;
            }else{
                return false;
            }
         }       
    }    
    
    
  
    
    /*
     Function Name : validateStreaming
     Desc : function used for checking patron streaming
     * 
     * @param $prodId Int  'song prod id'
     * @param $providerType varChar  'song provider type'
     * @param $isMobileDownload Boolean  'check for mobile download'
     * @param $mobileTerritory Boolean  'check for mobile territory'
     * @param $patId Int  'Uniqe patron id'
     * @param $agent Int  'Browser user agent'
     * @param $library_id Int  'Uniq library id'
     * 
     * @return Boolean
    */
    function validateStreaming($prodId, $providerType,$libId,$patId, $isMobileDownload = false, $mobileTerritory = null, $agent = null){
       
        if(!$isMobileDownload){
          
          $uid = $patId;
          $libId = $libId;
          $ip = $_SERVER['REMOTE_ADDR'];
          $channel = 'Website';
          
        
        } else {
            $uid = $patId;
            $libId = $libId;
            $ip = $agent;
            $channel = 'Mobile App';
            
        }
       
        
        
        if($this->checkLibraryStreaming($libId)){ 
            if($this->checkSongExists($prodId, $providerType)){                
                if($this->checkAllowedCountry($prodId, $providerType, $isMobileDownload, $mobileTerritory)){
                    return array(true,'', 1);
                } else {
                    $this->log($channel." : Rejected streaming request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the song requested is not available for territory ".((!$isMobileDownload)?$this->Session->read('territory'):$mobileTerritory),'streaming');
                    return array(false,'The song streaming is not available for this Country.', 2);
                }
            } else {
                $this->log($channel." : Rejected streaming request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the song requested for streaming does not allow for streaming or its mp4 file id is empty in Songs table",'streaming');
                return array(false,'The song requested for streaming does not exist', 3);
            }
        } else {
            $this->log($channel." : Rejected streaming request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as streaming is not allowed for this library",'streaming');
            return array(false,'Streaming is not allowed for this library.', 4);
        }        
    }
    
    
 
    
    /*
     Function Name : checkSongExists
     Desc : function used for checking songs  exist for streaming
     * 
     * @param $prodId Int  'song prodID'
     * @param $providerType varChar 'song provider type'
     * 
     * @return Boolean
    */
    function checkSongExists($prodId, $providerType){
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;
        $song = $songInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType, 'StreamingStatus'=>'0'), 'fields' => array('FullLength_Duration')));                
        if(isset($song['Song']['FullLength_Duration'])){
            $secondsValue = $this->getSeconds($song['Song']['FullLength_Duration']);          
            if(isset($secondsValue) && is_numeric($secondsValue)){
                return $secondsValue;
            }else{
                 return false;
            }          
        } else {
            return false;
        }
    }
    
    /*
     Function Name : checkAllowedCountry
     Desc : function used for checking songs territory allowed for streaming
     * 
     * @param $prodId Int  'song prodID'
     * @param $providerType varChar 'song provider type'
     * 
     * @param $isMobileDownload Int optional
     * @param $mobileTerritory varChar optional
     * 
     * @return Boolean
     * 
    */
    function checkAllowedCountry($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null){
        $countryInstance = ClassRegistry::init('Country');
        $countryInstance->recursive = -1;
        if(!$isMobileDownload){
            $territory = $this->Session->read('territory');
        } else {
            $territory = $mobileTerritory;
        }
        $countryInstance->tablePrefix = strtolower($territory)."_";
        $country = $countryInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'Territory'=>$territory, 'SalesDate <= NOW()')));
        if(!empty($country['Country'])){            
            return true;
        } else {            
            return false;
        }
    }
    
    
    /*
     Function Name : checkLibraryStreaming
     Desc : function used for checking library information for streaming
     * 
     * @param $libId Int  'library uniqe id' 
     *     
     * @return Boolean
    */
    function checkLibraryStreaming($libId) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;        
        $results = $libraryInstance->find('count',array('conditions' => array('library_type = "2"','id' => $libId,'library_status'=>'active')));
       
        if($results > 0) {            
            return true;
        }
        else {            
            return false;
        }
     
    }
    
    /*
     Function Name : checkPatronExist
     Desc : function used for checking library and patron details are stored in the streaming records table or not
     * 
     * @param $libId Int  'library uniqe id'
     * @param $patId Int  'patron uniqe id'
     *          
     * @return Boolean
    */
    function checkStreamingInfoExist($libId,$patId) {
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');
        $streamingRecordsInstance->recursive = -1;
        $results = $streamingRecordsInstance->find('first',array('conditions' => array('patron_id'=> $patId,'library_id' => $libId),'fields' => 'id'));
        if(!empty($results)) {
            return $results['StreamingRecords']['id'];
        }
        else {
            return false;
        }
    }
    
    
    /*
     Function Name : getSeconds
     Desc : function used convert minut:second value in to seconds values
     * 
     * @param $durationString varChar  'library uniqe id'     
     *          
     * @return Boolean or second value
    */
    function getSeconds($durationString){
        
        
       if(isset($durationString) && $durationString!=0){
           sscanf($durationString, "%d:%d", $minutes, $seconds);
           $time_seconds = $minutes * 60 + $seconds;          
           return $time_seconds;
       } else {
           return 0;
       }        
    }
    
    

}
?>