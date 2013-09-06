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
     Function Name : validateSongStreaming
     Desc : main function used for validate song streaming
     * 
     *      
     * @param $libId Int  'library unique id'     
     * @param $patId Int  'patron unique id'
     * @param $prodId Int  'song unique id'     
     * @param $provider varCh  'song provider type'
     * @param $agent Int (optional)  'Browser user agent' 
     *   
     * @return array
    */
    function validateSongStreaming($libId,$patId,$prodId,$provider,$agent = null) {
        
        /**
          creates log file name
        */
        //set the default value
        $currentTimeDuration = 0;
        
        $currentTimeDuration = $this->getPatronUsedStreamingTime($libId,$patId);
        
        
        $log_name = 'song_streaming_web_log_'.date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL."----------Request (".$log_id.") Start----------------".PHP_EOL;
        
        $this->log("Streaming Request :-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;agent : ".$agent,'streaming');            
        $log_data .= PHP_EOL."Streaming Request  :-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$this->Session->read('library')." ;user id : ".$patId." ;agent : ".$agent.PHP_EOL; 
        
        //if ProdID and Provider type is not set then
        if(($prodId == '' || $prodId == 0) && ($provider == '' || $provider == 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,prod_id or provider variables not come;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$this->Session->read('library')." ;user id : ".$patId,'streaming');            
            return array('error','Not able to stream this song.You need to login again.',$currentTimeDuration);           
            exit;
        }
        
        //if ProdID and Provider type is not set then
        if(($patId == '' || $patId == 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,user not login,patron_id not set;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$this->Session->read('library')." ;user id : ".$patId,'streaming');            
            return array('error','Not able to play this song.You need to login again.',$currentTimeDuration);            
            exit;
        }
        
        //if ProdID and Provider type is not set then
        if(($libId == '' || $libId == 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,user not login,library_id not set;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$this->Session->read('library')." ;user id : ".$patId,'streaming');            
            return array('error','Not able to play this song.You need to login again.',$currentTimeDuration);  
            exit;
        }
        
        //check the streaming validation
        $validationResult = $this->validateStreaming($prodId, $provider,$libId,$patId,$agent);
        $validationFlag = $validationResult[0];
        $validationMessage = $validationResult[1];
        $validationIndex = $validationResult[2];
        
        //create object for streaming_records table
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');      
        $streamingRecordsInstance->recursive = -1;
        
        //create object for streaming_records table
        $streamingHistoryInstance = ClassRegistry::init('StreamingHistory');      
        $streamingHistoryInstance->recursive = -1;
        
        //if first validation passed then	
        if($validationFlag){
            
            $this->log("First Validation Checked :- Valdition Passed : validation Index: ".$validationIndex." ;Validation Message : ".$validationMessage,'streaming');             
            $log_data .= PHP_EOL."First Validation Checked :- Valdition Passed : validation Index: ".$validationIndex." ;Validation Message : ".$validationMessage.PHP_EOL;        
            
            //check the patron record is exist or not
            $streamingInfoFlag = $this->checkStreamingInfoExist($libId, $patId);            
            if($streamingInfoFlag){
                //if patron record is exist then fetch the details
                $patronStreamingresults = $streamingRecordsInstance->find('first',array('conditions' => array('id'=> $streamingInfoFlag),'fields' => 'modified_date'));        
                if(count($patronStreamingresults) > 0) {                    
                     $modified_date = $patronStreamingresults['StreamingRecords']['modified_date'];                     
                     $onlyDate = date('Y-m-d',strtotime($modified_date));                     

                     $log_data .= PHP_EOL."Streaming Info Exist : modified_date  : ".$modified_date;
                     
                     //check if the current streaming date is equal to today's date or not
                     if(strtotime($onlyDate) != strtotime(date('Y-m-d'))){                                       
                         $updateArr = Array();
                         $updateArr['id'] = $streamingInfoFlag;                       
                         $updateArr['consumed_time'] = 0;
                         $updateArr['modified_date'] = date('Y-m-d H:i:s');                         
                         $streamingRecordsInstance->setDataSource('master');
                         //update the date and reset the consumed time as the day start
                         if($streamingRecordsInstance->save($updateArr)){
                              $log_data .= "update Streaming_records table(day first request) :- modified_date  : ".$modified_date.PHP_EOL;  
                         }
                         $streamingRecordsInstance->setDataSource('default');
                     }else{
                        $log_data .= PHP_EOL;     
                     }
                }                
            } else {
                //insert the patron record if not exist in the streaming records table
                $insertArr = Array();
                $insertArr['library_id'] = $libId;
                $insertArr['patron_id'] = $patId;
                $insertArr['consumed_time'] = 0;
                $insertArr['modified_date'] = date('Y-m-d H:i:s');
                $insertArr['createdOn'] = date('Y-m-d H:i:s');    
                $streamingRecordsInstance->setDataSource('master');
                if($streamingRecordsInstance->save($insertArr)){
                    $log_data .= PHP_EOL."Insert Streaming_records table :-  library_id: ".$libId." ;patron_id : ".$patId." ;consumed_time :0 ;modified_date : ".date('Y-m-d H:i:s').PHP_EOL;        
                }
                $streamingRecordsInstance->setDataSource('default');
            }
                
               
            //get the requested song duration time
            $songDuration = $this->checkSongExists($prodId, $provider);
           
            //collect the validation facts
            $validateStreamingInfoResult = $this->validateStreamingDurationInfo($libId, $patId,$songDuration,$agent);
            $validateStreamingInfoFlag = $validateStreamingInfoResult[0];
            $validateStreamingInfoMessage = $validateStreamingInfoResult[1];
            $validateStreamingInfoIndex = $validateStreamingInfoResult[2];
            
            $queryUpdateFlag = 0;
            $queryInsertFlag = 0;
            
            if($validateStreamingInfoFlag){
                
                $this->log("Second Validation Checked :- Valdition Passed : validation Index: ".$validateStreamingInfoFlag." ;Validation Message : ".$validateStreamingInfoMessage." ;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$this->Session->read('library')." ;user id : ".$patId,'streaming');            

                //update streaming_record table table
                $cdate = date('Y:m:d H:i:s');
                $streamingRecordsInstance->setDataSource('master');
                $StreamingRecordsSQL = "UPDATE `streaming_records` SET consumed_time=consumed_time+".$songDuration.",modified_date='".$cdate."' Where patron_id='".$patId."' and library_id='".$libId."'";
                if($streamingRecordsInstance->query($StreamingRecordsSQL)){
                     $queryUpdateFlag = 1;            
                     $log_data .= PHP_EOL."update streaming_reocrds table:-LibID='".$libId."':Parameters:-Patron='".$patId."':songDuration='".$songDuration.PHP_EOL;
                }
                $streamingRecordsInstance->setDataSource('default');
                $currentDate= date('Y-m-d H:i:s');
                
                //insert the patron record if not exist in the streaming records table
                $insertArr = Array();
                $insertArr['library_id'] = $libId;
                $insertArr['patron_id'] = $patId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['provider_type'] = $provider;
                $insertArr['consumed_time'] = $songDuration;
                $insertArr['modified_date'] = $currentDate;
                $insertArr['createdOn'] = $currentDate;
                $insertArr['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $insertArr['user_agent'] = str_replace(";","",$_SERVER['HTTP_USER_AGENT']);
                $streamingHistoryInstance->setDataSource('master');
                if($streamingHistoryInstance->save($insertArr)){
                    $queryInsertFlag = 1;
                    $log_data .= PHP_EOL."update streaming_reocrds table:-LibID=".$libId.":Parameters:-Patron=".$patId.":songDuration=".$songDuration." ;modified_date : ".$currentDate.PHP_EOL;
                    $this->log("suces:-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;consumed_time : ".$songDuration." ;modified_date : ".$currentDate,'streaming');            
                    $log_data .= PHP_EOL."suces|".$validateStreamingInfoMessage.PHP_EOL;
                    $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
                    $this->createStreamingLog($log_data, $log_name);
                }
                $streamingHistoryInstance->setDataSource('default');
                if( ($queryUpdateFlag == 1) && ($queryUpdateFlag == 1) ){
                     $updatedTimeDuration = $this->getPatronUsedStreamingTime($libId,$patId);
                     return array('suces',$validateStreamingInfoMessage,$updatedTimeDuration);                  
                }                
                exit;
                
            }else{
                $this->log("error|message=".$validateStreamingInfoMessage.";validatin Index :".$validateStreamingInfoIndex,'streaming');            
                $log_data .= PHP_EOL."error|".$validateStreamingInfoMessage."|".$validateStreamingInfoIndex.PHP_EOL;
                $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
                $this->createStreamingLog($log_data, $log_name);
                return array('error',$validateStreamingInfoMessage,$currentTimeDuration);               
                exit;
            }

        } else {        
          /*
            complete records with validation fail
          */
          $log_data .= PHP_EOL."error|".$validationMessage."|".$validationIndex.PHP_EOL;
          $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------".PHP_EOL;
          $this->createStreamingLog($log_data, $log_name); 
          return array('error',$validationMessage,$currentTimeDuration);         
          exit;
        
        }
        exit;
        
    }
    
    
        
    /*
     Function Name : validateStreamingDurationInfo
     Desc : function used for checking patron streaming
     * 
     * 
     * @param $patId Int  'Patron Unique id'
     * @param $libId Int  'library Unique id'     
     * @param $patId Int  'Uniqe patron id'
     * @param $agent Int  'Browser user agent'
     * @param $library_id Int  'Uniq library id'
     * @return Boolean
    */
    function validateStreamingDurationInfo($libId,$patId,$songDuration,$agent = null) {
        
       
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');      
        $streamingRecordsInstance->recursive = -1;
        
        if(!$agent){
          
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
                    return array(false,'You have not enough streaming time left to play this song.', 2);
                }                
            }else{
                $this->log($channel." : Rejected streaming request for patron:".$patId.";libid:".$libId.";User:".$uid.";IP:".$ip.";limitToPlaySong:".$limitToPlaySong.";updatedDate:".$updatedDate." as the patron limit is over for the day",'streaming');
                return array(false,'You have not enough streaming time left to play this song.', 3);
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
     * @param $patId Int  'Uniqe patron id'
     * @param $agent Int  'Browser user agent'
     * @param $library_id Int  'Uniq library id'
     * 
     * @return Boolean
    */
    function validateStreaming($prodId, $providerType,$libId,$patId,$agent = null){
       
        if(!$agent){          
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
       
        
        //check the validation
        if($this->checkLibraryStreaming($libId)){ 
            if($this->checkSongExists($prodId, $providerType)){                
                return array(true,'First validatin passed', 1);
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
     * @return false or song duration in seconds
    */
    function checkSongExists($prodId, $providerType){
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;
        $song = $songInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType, 'StreamingStatus'=>'1'), 'fields' => array('FullLength_Duration')));                
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
        $results = $streamingRecordsInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id'=> $patId),'fields' => 'id'));
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
    
    
    
    /*
     Function Name : checkPatronExist
     Desc : function used for checking library and patron details are stored in the streaming records table or not
     * 
     * @param $libId Int  'library uniqe id'
     * @param $patId Int  'patron uniqe id'
     *          
     * @return Boolean
    */
    function getPatronUsedStreamingTime($libId,$patId) {
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');
        $streamingRecordsInstance->recursive = -1;
        $results = $streamingRecordsInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id'=> $patId,'date(modified_date)=date(now())'),'fields' => 'consumed_time'));
        if(!empty($results)) {
            return $results['StreamingRecords']['consumed_time'];
        } else {
            return 0;
        }
    }
    
    /*
     Function Name : createStreamingLog
     Desc : function used for creating logs for streaming song
     * 
     * @param $log_data String  'complete logs string' 
     * @param $log_name String  'daily create log file name'     
     * 
     * @return mix result
    */
    function createStreamingLog($log_data,$log_name){
        
        //check log create condition on or off
        $streamingLogFlag = Configure::read('App.streaming_log');
        if($streamingLogFlag == 'on'){
            $this->log($log_data, $log_name);
        }
    }    
    
    

}
?>