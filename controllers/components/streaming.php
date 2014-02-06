<?php
 /*
 File Name : streaming.php
 File Description : Component page for the  song streaming functionality.
 Author : m68interactive
 */
 
Class StreamingComponent extends Object
{
    var $components = array('Auth','Session');
    
    var $streamingLimit = 10800; //3 hours
    var $streamingLog = 'on'; //off
    var $timerCallDuration = 60; //off
    
    
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
    function validateSongStreaming($libId,$patId,$prodId,$provider,$userStreamedTime,$actionType,$agent = null,$songDuration,$queue_id,$token_id) {
        
        /**
          creates log file name
        */
        

        $this->streamingLimit = $this->getStreamingLimit($libId);

        if($this->streamingLimit === false || $this->streamingLimit===0){
            $this->log("error|Not able to stream this song,streaming limit has been over.;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;streamingLimit :".$this->streamingLimit,'streaming');            
            
            //return the final result array
            return array(0,'Not able to stream this song,streaming limit has been over.',$currentTimeDuration, 1 ,$timerCallTime,$this->timerCallDuration);           
            exit;
        }

        if(!isset($queue_id)) { $queue_id = '0'; }
        
        //set the default value
        $currentTimeDuration = 0;
        $timerCallTime = (2 * $songDuration) ;
        
        $currentTimeDuration = $this->getPatronUsedStreamingTime($libId,$patId);
      
    
        $log_name = 'song_streaming_web_log_'.date('Y_m_d');
        $log_id = md5(time());
        $log_data = PHP_EOL."----------Request (".$log_id.") Start----------------".PHP_EOL;
        
        $this->log("Streaming Request :-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;ConsumedTime  : ".$userStreamedTime." ;agent : ".$agent." ;song duration : ".$songDuration." ;queueID : ".$queue_id." ;tokenID : ".$token_id,'streaming');            
        $log_data .= PHP_EOL."Streaming Request  :-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;ConsumedTime : ".$userStreamedTime." ;agent : ".$agent." ;song duration : ".$songDuration." ;queueID : ".$queue_id." ;tokenID : ".$token_id.PHP_EOL; 
        
        //if ProdID and Provider type is not set then
        if(($prodId === '' || $prodId === 0) && ($provider === '' || $provider === 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,prod_id or provider variables not come;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId,'streaming');            
            
            //return the final result array
            return array(0,'Not able to stream this song.You need to login again.',$currentTimeDuration, 1 ,$timerCallTime,$this->timerCallDuration);           
            exit;
        }
        
        //if patron is set null than then
        if(($patId === '' || $patId === 0)){           
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,user not login,patron_id not set;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId,'streaming');            
             //return the final result array
            return array(0,'Not able to play this song.You need to login again.',$currentTimeDuration, 2 ,$timerCallTime,$this->timerCallDuration);            
            exit;
        }
        
        //if library id set null then
        if(($libId === '' || $libId === 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,user not login,library_id not set;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId,'streaming');            
             //return the final result array
            return array(0,'Not able to play this song.You need to login again.',$currentTimeDuration, 3 ,$timerCallTime,$this->timerCallDuration);  
            exit;
        }
        
         //if $songDuration  not set then
        if(($songDuration === '' || $songDuration === 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,song duration is empty;songDuration :".$songDuration." ;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId,'streaming');            
             //return the final result array
            return array(0,'Not able to stream this song.',$currentTimeDuration, 4 ,$timerCallTime,$this->timerCallDuration);  
            exit;
        }
        
         //if $songDuration  not set then
        if(($token_id === '' || $token_id === 0)){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,token is empty;songDuration :".$songDuration." ;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;token id : ".$token_id,'streaming');            
             //return the final result array
            return array(0,'An error has occurred. Please reload the page.',$currentTimeDuration, 44 ,$timerCallTime,$this->timerCallDuration);  
            exit;
        }
        
         //if $songDuration  not set then
        if(($userStreamedTime === '' || $userStreamedTime < 0 ||  $userStreamedTime ==='NaN')){
             //$this->redirect(array('controller' => 'homes', 'action' => 'index'));
            $this->log("error|Not able to stream this song,stream time is negetive;songDuration :".$songDuration." ;ConsumedTime : ".$userStreamedTime." ;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;token id : ".$token_id,'streaming');            
             //return the final result array
            //return array(0,'Not able to process.Stream time is negetive or NaN.',$currentTimeDuration, 45 ,$timerCallTime,$this->timerCallDuration);  
              return array(0,'An error has occurred. Please reload the page.',$currentTimeDuration, 45 ,$timerCallTime,$this->timerCallDuration);  
            exit;
        }
        
        
                  
        
        //check the streaming validation
        //this check that library is allow for streaming
        // and song is allow for streaming or not
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
            $patronStreamingresults = $this->checkStreamingInfoExist($libId, $patId);             

            if(is_array($patronStreamingresults) && !empty($patronStreamingresults)){
                
                $modified_date = $patronStreamingresults['StreamingRecords']['modified_date'];               
                    $onlyDate = date('Y-m-d',strtotime($modified_date));
                   
                    $log_data .= PHP_EOL."Streaming Info Exist : modified_date  : ".$modified_date;

                    //check if the current streaming date is equal to today's date or not
                    if(strtotime($onlyDate) != strtotime(date('Y-m-d'))){                       
                        $updateArr = Array();
                        $updateArr['id'] = $patronStreamingresults['StreamingRecords']['id'];                       
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
            
            //check the libary unlimited condition
            $libraryUnlimitedFlag = $this->checkLibraryUnlimited($libId);
            if($libraryUnlimitedFlag){               
               
                $cdate = date('Y:m:d H:i:s');
                //updated streaming record table
                $streamingRecordsInstance->setDataSource('master');
                $StreamingRecordsSQL = "UPDATE `streaming_records` SET consumed_time=consumed_time+".$userStreamedTime.",modified_date='".$cdate."' Where patron_id='".$patId."' and library_id='".$libId."'";
                if($streamingRecordsInstance->query($StreamingRecordsSQL)){                             
                    $log_data .= PHP_EOL."update streaming_reocrds table:-LibID='".$libId."':Parameters:-Patron='".$patId."':songDuration='".$userStreamedTime.PHP_EOL;
                }
                $streamingRecordsInstance->setDataSource('default'); 
                
                //updated streaming history table
                $currentDate= date('Y-m-d H:i:s');                
                //insert the patron record if not exist in the streaming  table
                $insertArr = Array();               
                //check token already exist or not 
                //if exist, than update that table otherwise insert new records
                $tokenExistFlag = $this->checkTokenExist($libId, $patId,$token_id);
                if($tokenExistFlag){                    
                    $idAndTimeArray = explode('-', $tokenExistFlag);
                    $insertArr['id'] = $idAndTimeArray[0]; 
                    $insertArr['consumed_time'] = $idAndTimeArray[1]+$userStreamedTime; 
                }else{
                    $insertArr['consumed_time'] = $userStreamedTime; 
                }
                $insertArr['library_id'] = $libId;
                $insertArr['patron_id'] = $patId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['provider_type'] = $provider;                             
                $insertArr['createdOn'] = $currentDate;              
                $insertArr['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $insertArr['action_type'] = $actionType;
                $insertArr['songs_queue_id'] = $queue_id; 
                $insertArr['token_id'] = $token_id;
                if($agent == null){
                    $insertArr['user_agent'] = mysql_real_escape_string(str_replace(";","",  addslashes($_SERVER['HTTP_USER_AGENT'])));
                }else{
                    $insertArr['user_agent'] = mysql_real_escape_string($agent);   
                }
              
                $streamingRecordsInstance->setDataSource('master');
                $streamingHistoryInstance->save($insertArr);
                $streamingRecordsInstance->setDataSource('default');
                
                //return the final result array
                return array(1,'successfully able to streaming this song','86400', 5 ,$timerCallTime,$this->timerCallDuration);
                exit;
            }
            
            
            
            //collect the validation facts
            $validateStreamingInfoResult = $this->validateStreamingDurationInfo($libId, $patId,$userStreamedTime,$agent);
            $validateStreamingInfoFlag = $validateStreamingInfoResult[0];
            $validateStreamingInfoMessage = $validateStreamingInfoResult[1];
            $validateStreamingInfoIndex = $validateStreamingInfoResult[2];
            
                       
                    
            if($validateStreamingInfoFlag){
                
                $this->log("Second Validation Checked :- Valdition Passed : validation Index: ".$validateStreamingInfoFlag." ;Validation Message : ".$validateStreamingInfoMessage." ;ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId,'streaming');            

                //update streaming_record table table
                $cdate = date('Y:m:d H:i:s');
                $remainingTimeDuration = $this->getPatronUsedStreamingTime($libId,$patId);
                if( ($userStreamedTime > 0) && ($userStreamedTime <= $remainingTimeDuration) ){
                    $streamingRecordsInstance->setDataSource('master');
                    $StreamingRecordsSQL = "UPDATE `streaming_records` SET consumed_time=consumed_time+".$userStreamedTime.",modified_date='".$cdate."' Where patron_id='".$patId."' and library_id='".$libId."'";
                    if($streamingRecordsInstance->query($StreamingRecordsSQL)){
                                   
                        $log_data .= PHP_EOL."update streaming_reocrds table:-LibID='".$libId."':Parameters:-Patron='".$patId."':songDuration='".$userStreamedTime.PHP_EOL;
                    }
                    $streamingRecordsInstance->setDataSource('default'); 
                }else if($userStreamedTime > $remainingTimeDuration){
                    
                    $streamingRecordsInstance->setDataSource('master');
                    $StreamingRecordsSQL = "UPDATE `streaming_records` SET consumed_time=".$this->streamingLimit.",modified_date='".$cdate."' Where patron_id='".$patId."' and library_id='".$libId."'";
                    if($streamingRecordsInstance->query($StreamingRecordsSQL)){
                                    
                        $log_data .= PHP_EOL."update streaming_reocrds table(Error:songduration > remainingTimeDuration: Agent:".$agent."):-LibID='".$libId."':Parameters:-Patron='".$patId."':songDuration='".$userStreamedTime."':remainingTimeDuration='".$remainingTimeDuration.PHP_EOL;
                    }
                    $streamingRecordsInstance->setDataSource('default'); 
                }
               
                $currentDate= date('Y-m-d H:i:s');
                
                //insert the patron record if not exist in the streaming  table
                $insertArr = Array();
                $insertUpdateFlag= 'Insert';
                //check token already exist or not 
                //if exist, than update that table otherwise insert new records
                $tokenExistFlag = $this->checkTokenExist($libId, $patId,$token_id);             
                if($tokenExistFlag){
                    $insertUpdateFlag= 'Update';
                    $idAndTimeArray = explode('-', $tokenExistFlag);
                    $insertArr['id'] = $idAndTimeArray[0]; 
                    $insertArr['consumed_time'] = $idAndTimeArray[1] + $userStreamedTime; 
                }else{
                    $insertArr['consumed_time'] = $userStreamedTime; 
                }
                $insertArr['library_id'] = $libId;
                $insertArr['token_id'] = $token_id;
                $insertArr['patron_id'] = $patId;
                $insertArr['ProdID'] = $prodId;
                $insertArr['provider_type'] = $provider;               
                $insertArr['modified_date'] = $currentDate;              
                $insertArr['createdOn'] = $currentDate; 
                $insertArr['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $insertArr['action_type'] = $actionType; 
                $insertArr['songs_queue_id'] = $queue_id;
                if($agent == null){
                    $insertArr['user_agent'] = mysql_real_escape_string(str_replace(";","",  addslashes($_SERVER['HTTP_USER_AGENT'])));
                }else{
                    $insertArr['user_agent'] = mysql_real_escape_string($agent);   
                }
              
                //updated record if user Streamed time is not 0 and less then to stream time
               if( ($userStreamedTime > 0) && ($userStreamedTime <= $remainingTimeDuration) ){
                    
                    $streamingHistoryInstance->setDataSource('master');                    
                    if($streamingHistoryInstance->save($insertArr)){         
                      
                        $log_data .= PHP_EOL.$insertUpdateFlag." streaming_history table:-LibID=".$libId.":Parameters:-Patron=".$patId.":songDuration=".$userStreamedTime." ;modified_date : ".$currentDate." ;queue_id :".$queue_id." ;token id : ".$token_id.PHP_EOL;
                        $this->log("success:-ProdID :".$prodId." ;Provider : ".$provider." ;library id : ".$libId." ;user id : ".$patId." ;consumed_time : ".$userStreamedTime." ;modified_date : ".$currentDate." ;token id : ".$token_id,'streaming');            
                        $log_data .= PHP_EOL."success|".$validateStreamingInfoMessage.PHP_EOL;
                        $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
                        $this->createStreamingLog($log_data, $log_name);
                    }
                    $streamingHistoryInstance->setDataSource('default');
                }             
                
                $remainingTimeDuration = $this->getPatronUsedStreamingTime($libId,$patId);
                if($remainingTimeDuration){
                     //return the final result array
                    return array(1,'successfully able to streaming this song',$remainingTimeDuration, 6, $timerCallTime,$this->timerCallDuration);
                    exit;
                }else{
                     //return the final result array
                    return array(0,'You have reached your streaming limit for the day.',$remainingTimeDuration, 7,$timerCallTime,$this->timerCallDuration);
                    exit;
                }        
                
            }else{
                $this->log("error|message=".$validateStreamingInfoMessage.";validatin Index :".$validateStreamingInfoIndex,'streaming');            
                $log_data .= PHP_EOL."error|".$validateStreamingInfoMessage."|".$validateStreamingInfoIndex.PHP_EOL;
                $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
                $this->createStreamingLog($log_data, $log_name);
                //return the final result array
                return array(0,$validateStreamingInfoMessage,$currentTimeDuration, 8,$timerCallTime,$this->timerCallDuration);               
                exit;
            }

        } else {        
          /*
            complete records with validation fail
          */
          $log_data .= PHP_EOL."error|".$validationMessage."|".$validationIndex.PHP_EOL;
          $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------".PHP_EOL;
          $this->createStreamingLog($log_data, $log_name); 
          //return the final result array
          return array(0,$validationMessage,$currentTimeDuration, 9,$timerCallTime,$this->timerCallDuration);         
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
    function validateStreamingDurationInfo($libId,$patId,$userStreamedTime,$agent = null) {        
       
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
           
            $consumedTime = $streamingRecordsResults['StreamingRecords']['consumed_time'];
            $updatedDate = $streamingRecordsResults['StreamingRecords']['modified_date'];
           
            //check patron time limit            
            if($this->checkPatronStreamingLimitForDay($consumedTime,$updatedDate)){                
                return array(1,'successfully able to streaming this song.', 1);
            }else{
                $this->log($channel." : Rejected streaming request for patron:".$patId.";libid:".$libId.";User:".$uid.";IP:".$ip.";songDureation:".$userStreamedTime.";consumedTime:".$consumedTime.";updatedDate:".$updatedDate." as the patron limit is over to stream this song",'streaming');
                return array(0,'You have reached your streaming limit for the day.', 2);
            }                
            
        } else {
            
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
    function checkPatronStreamingLimitForDay($consumedTime,$updatedDate){
        //per day allowed time for patron in seconds
         $onlyDate = date('Y-m-d',strtotime($updatedDate));
         if(strtotime($onlyDate) == strtotime(date('Y-m-d'))){ 
            //total allow time in  second for the day 
            $allowedTime = $this->streamingLimit;
            if($consumedTime < $allowedTime){
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
       
        
        //check for library allow for streaming
        if($libraryTerritory=$this->checkLibraryStreaming($libId)){           
            //check song allow for streaming
            if($this->checkSongExists($prodId, $providerType,$libraryTerritory)){                
                return array(true,'First validation passed', 1);
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
    function checkSongExists($prodId, $providerType,$libraryTerritory){
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;        
        $song = $songInstance->find('first',array(
        'joins' => array(
            array(
                'table' => strtolower($libraryTerritory).'_countries',
                'alias' => 'Country',
                'type' => 'INNER',
                'conditions' => array(
                    'Country.ProdID = Song.ProdID',
                    'Country.provider_type = Song.provider_type',
                )
            )
        ),
        'conditions' => array(
                            'Song.ProdID'=>$prodId,
                            'Song.provider_type'=>$providerType,
                            'Country.StreamingSalesDate < NOW()',
                            'Country.StreamingStatus'=> 1
                            ),
        'fields' => array('Song.FullLength_Duration'))); 
        
        
        if(isset($song['Song']['FullLength_Duration']) && $libraryTerritory){      
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
     * @return false or library territory value
    */
    function checkLibraryStreaming($libId) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;        
        $results = $libraryInstance->find('first',array('conditions' => array('library_type = "2"','id' => $libId,'library_status'=>'active'),'fields' => 'library_territory'));
            
        if(count($results) > 0 && isset($results['Library']['library_territory']) && $results['Library']['library_territory']!='') {            
            return $results['Library']['library_territory'];
        }
        else {            
            return false;
        }
     
    }
    
    
    
    
    /*
     Function Name : checkStreamingInfoExist
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
        $results = $streamingRecordsInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id'=> $patId)));
        if(!empty($results) && isset($results['StreamingRecords']['id']) && $results['StreamingRecords']['id']!='') {
            //return $results['StreamingRecords']['id'];
            return $results;
        } else {
            return false;
        }
    }
    
    
    
    /*
     Function Name : getSeconds
     Desc : function used convert minut:second value in to seconds values
     * 
     * @param $durationString varChar  'duration string'     
     *          
     * @return Boolean or second value
    */
    function getSeconds($durationString){        
        
       if(isset($durationString)){
           sscanf($durationString, "%d:%d", $minutes, $seconds);
           $time_seconds = $minutes * 60 + $seconds;          
           return $time_seconds;
       } else {
           return 0;
       }        
    }
    
    
    
    /*
     Function Name : getPatronUsedStreamingTime
     Desc : function used to get the patron remaining streaming time
     * 
     * @param $libId Int  'library uniqe id'
     * @param $patId Int  'patron uniqe id'
     *          
     * @return Boolean
    */
    function getPatronUsedStreamingTime($libId,$patId) {
      
        $streamingRecordsInstance = ClassRegistry::init('StreamingRecords');
        $streamingRecordsInstance->recursive = -1;
        
        $currentDate = date('Y-m-d');        
        $results = $streamingRecordsInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id'=> $patId,'date(modified_date)'=>$currentDate),'fields' => 'consumed_time'));
                
        if(!empty($results)) {
            
            $remainingTime = ($this->streamingLimit - $results['StreamingRecords']['consumed_time']);
           
            if($remainingTime <= 0){
                return 0;
            }else{
                return $remainingTime;
            }
            
        } else {
            return $this->streamingLimit;
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
        $streamingLogFlag = $this->streamingLog;
        if($streamingLogFlag == 'on'){
            $this->log($log_data, $log_name);
        }
    }
    
    
    /*
     Function Name : checkLibraryUnlimited
     Desc : function used for creating logs for streaming song
     * 
     * @param $libID Int  'library id'         
     * 
     * @return bool value
    */
    function checkLibraryUnlimited($libId){
        
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;        
        $results = $libraryInstance->find('first',array('conditions' => array('library_streaming_hours = "24"','id' => $libId),'fields' => 'id'));          
        if(count($results) > 0 && isset($results['Library']['id']) && $results['Library']['id']!='') {            
            return true;
        }
        else {            
            return false;
        }        
    }
    
    /*
     Function Name : getStreamingLimit
     Desc : function used for creating logs for streaming song
     * 
     * @param $libID Int  'library id'         
     * 
     * @return bool value
    */
    function getStreamingLimit($libId){
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;        
        $results = $libraryInstance->find('first',array('conditions' => array('id' => $libId),'fields' => array('library_streaming_hours','id')));          
        if(count($results) > 0 && isset($results['Library']['id']) && $results['Library']['id']!='') {            
            return ($results['Library']['library_streaming_hours'] * 3600);            
        }
        else {            
            return false;
        }        
    }
    
    
    
    /*
     Function Name : checkTokenExist
     Desc : function used for checking token exist or not
     *
     * @param $libId int()  'library id'  
     * @param $patId int()  'patron id' 
     * @param $token_id varChar()  'token id'         
     * 
     * @return boolean value 'if found then return record id ,if not than false'
    */
    function checkTokenExist($libId,$patId,$token_id){
        $streamHistoryInstance = ClassRegistry::init('StreamingHistory');
        $streamHistoryInstance->recursive = -1;   
        $currentDate = date('Y-m-d'); 
        $results = $streamHistoryInstance->find('first',array('conditions' => array('library_id' => $libId,'patron_id'=> $patId,'date(createdOn)'=>$currentDate,'token_id' => $token_id),'fields' => array('id','consumed_time')));          
        if(count($results) > 0 && isset($results['StreamingHistory']['id']) && $results['StreamingHistory']['id']!='') {            
            return $results['StreamingHistory']['id'].'-'.$results['StreamingHistory']['consumed_time'];
        } else {            
            return false;
        }        
    }
    
    
    
    /*
     Function Name : getStreamingDetails
     Desc : function used for fetching information realted to streaming
     * 
     * @param $prodId Int  'song prodID'
     * @param $providerType varChar 'song provider type'
     * 
     * @return false or song duration in seconds
    */
    function getStreamingDetails($prodId, $providerType){
        Configure::write('debug', 0);
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;        
        $song = $songInstance->find('first',array(
        'joins' => array(
            array(
                'table' => $this->Session->read('multiple_countries').'countries',
                'alias' => 'Country',
                'type' => 'INNER',
                'conditions' => array(
                    'Country.ProdID = Song.ProdID',
                    'Country.provider_type = Song.provider_type',
                )
            )
        ),
        'conditions' => array(
                            'Song.ProdID'=>$prodId,
                            'Song.provider_type'=>$providerType,
                            'Country.StreamingSalesDate < NOW()',
                            'Country.StreamingStatus'=> 1
                            ),
        'fields' => array('Country.StreamingSalesDate', 'Country.StreamingStatus'))); 
        
       // echo "<br>Query: ".$this->$songInstance->lastQuery();
        //echo "<pre>"; print_r($song);
        return $song;
        
    }
    
    
      function admin_getLibraryIdsStream() {
       
        $LibraryInstance = ClassRegistry::init('Library');
        $data = '';
        $var = array();
        if ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") == '') {
        
            $var = $LibraryInstance->find("list", array(
                "conditions" => array(
                    'Library.library_admin_id' => $this->Session->read("Auth.User.id"), 
                    'Library.library_type = 2'),  
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1)
                    );
            
        } elseif ($this->Session->read("Auth.User.type_id") == 4 && $this->Session->read("Auth.User.consortium") != '') {
        
              $var = $LibraryInstance->find("list", array(
                "conditions" => array(
                    'Library.library_apikey' => $this->Session->read("Auth.User.consortium"), 
                    'Library.library_type = 2' 
                    ), 
                'fields' => array('Library.id', 'Library.library_name'), 
                'order' => 'Library.library_name ASC', 
                'recursive' => -1));
              
        } else {
         
             $var = $LibraryInstance->find('list', array(
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
        
    }

}
?>