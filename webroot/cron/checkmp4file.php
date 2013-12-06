<?php

/**
 * @file Streaming.php
 * Class which create log of the records which not have valid MP4 file
 * 
 * 
 * you need to to put (&) person at the end of the script command executation
 **/
 
class Streaming {

 
  //set the database connection variables for staging for test only 
  //luther database setting
    
  /*
  var $STAGE_DB_HOST = 'localhost';
  var $STAGE_DB_USER = 'narendran';
  var $STAGE_DB_PASS = 'NMx{h7b<366g';
  var $STAGE_SONY_DB   = 'sony';
  var $STAGE_FREEGAL_DB   = 'freegal';
  var $STAGE_ORCHARD_DB   = 'theorchard';
  */
   
  
  
  
  //db1 database setting
  var $STAGE_DB_HOST = '10.181.56.177';
  var $STAGE_DB_USER = 'freegal_test';
  var $STAGE_DB_PASS = 'c45X^E1X7:TQ';
  var $STAGE_SONY_DB   = 'sony2';
  var $STAGE_FREEGAL_DB   = 'freegal';
  var $STAGE_ORCHARD_DB   = 'theorchard';
  
  
  //set the database connection for production 
  var $PODUCTION_DB_HOST = '192.168.100.114';
  var $PODUCTION_DB_USER = 'freegal_prod';
  var $PODUCTION_DB_PASS = '}e47^B1EO9hD';
  var $PODUCTION_SONY_DB   = 'sony';
  var $PODUCTION_FREEGAL_DB   = 'freegal';
  var $PODUCTION_ORCHARD_DB   = 'theorchard';
  
  
  var $SFTP_HOST = 'libraryideas.ingest.cdn.level3.net';
  var $SFTP_USER = 'libraryideas';
  var $SFTP_PASS = 't837dgkZU6xCMnc';
  
  
  var $freegalDBConnectionObj;
  
  
  
  var $LIVE = '0'; //1-live,0-stage
  
  

  var $EnableShortLogs = 0;

  var $ShortLogsString = '';
  var $ShortLogsFileObj;
  
  
  
  var $Instance = null;
  var $ProcessedRowsCount = 1;
  var $ChunkSize = 1000;       //default set 1000
  var $LimitIndex = 0;         //default set 0
  var $LimitCount = 100000;    //default set 100000
  


 
  /**
   * Constructer, (intialize object) connection to db1 
   *
   **/
  function __construct(){
      
     //set the database connection
     if( $this->LIVE == '1' ) {
         
          // connect to  Production
          //connect to freegal database
          $this->freegalDBConnectionObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS, true)
            or die('Could not connect to mysql server for freegal db of live.' );
          mysql_select_db($this->PODUCTION_FREEGAL_DB, $this->freegalDBConnectionObj) 
            or die('Could not select database.'); 
     } else {         
         
          // connect to  stage                     
          //connect to freegal database
          $this->freegalDBConnectionObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS, true)
            or die('Could not connect to mysql server for freegal db of stage.' );
          mysql_select_db($this->STAGE_FREEGAL_DB, $this->freegalDBConnectionObj) 
            or die('Could not select freegal database.');
     }
          
  }
  
  
  
  /**
   *@function  getAllSongsData-1009240
   *Fetches all songs data for processing
   *
   *@return array
   **/

  function getAllSongsData() {
    
    $totRows = $this->LimitCount + $this->LimitIndex;  
    $iniTotRows = $totRows; 
    
    $log_id = md5(time());
    $this->LogsString = PHP_EOL."---------- Request (".$log_id.") Start ---".date('Y-m-d H:i:s')." -------------".PHP_EOL;
    
    while($totRows > 0) {
        
      $this->ShortLogsString ='';
       
      $index = $limit = null;
      if($totRows < $this->ChunkSize) { 
        $index = $this->LimitIndex;
        $limit = $totRows;
      }else{
        $index = $this->LimitIndex;
        $limit = $this->ChunkSize;
      }
      
      
      $songQuery ='SELECT Songs.ProdID,Songs.provider_type,Songs.MP4_FileID FROM Songs LIMIT '.$index.', '.$limit;
      
      $obj_resultset = mysql_query( $songQuery, $this->freegalDBConnectionObj);
      
      if(mysql_num_rows($obj_resultset) > 0){
            
          while($arr_row = mysql_fetch_assoc($obj_resultset)){
                
            $this->ShortLogsString ='';               

            $this->ShortLogsString .= PHP_EOL.date('Y-m-d h:i:s')." SNO: ".$this->ProcessedRowsCount." ProdID: ".$arr_row['ProdID']." ProviderType: ".$arr_row['provider_type'];

            $this->getMP4filePath($arr_row);                            

            $this->ProcessedRowsCount++;
            
           }            
      }                   
         
      $this->LimitIndex = $this->LimitIndex + $limit; 
      $totRows = $iniTotRows - $this->LimitIndex;
           
      }     
     
  }
  
    /**
   *@function  openLogsFiles
   *creating the log file
   *
   *@return void
   **/
  function openLogsFiles() {
      
        if($this->EnableShortLogs){
            $shortLogsFileName ="logs/shortLogs".$this->Instance.".txt";
            $this->ShortLogsFileObj = fopen($shortLogsFileName,"a");

        }         
  }
  
  /**
   *@function  openLogsFiles
   *close the log file
   *
   *@return void
   **/
  function closeLogsFiles() {
      
        if($this->EnableShortLogs){
            $shortLogsFileName ="logs/shortLogs".$this->Instance.".txt";
            if(file_exists($shortLogsFileName)){
                fclose($this->ShortLogsFileObj);
            }            
        }         
  }
  
  /**
   *@function  getMP4filePath
   *get the mp4 file information from the table
   *
   *@param $arr_row array records information
   * 
   *@return void
   **/

  function getMP4filePath($arr_row) {
      
      $MP4_FileID = $arr_row['MP4_FileID']; 
      
      $sql = "select * from File_mp4 where FileID ='".$MP4_FileID."'";
      
      $obj_resultset = mysql_query($sql, $this->freegalDBConnectionObj);
                
      if(mysql_num_rows($obj_resultset) > 0){
            while($file_arr_row = mysql_fetch_assoc($obj_resultset)){ 
                $this->checkMP4File($file_arr_row,$MP4_FileID);           
            }  
      }      
  }   
    

  /**
   *@function  checkMP4File
   *check the file exist or not on the CDN server 
   *
   *@param $file_arr_row array records information
   * 
   *@return void
   **/

  function checkMP4File($file_arr_row,$MP4_FileID) {
      
        if (!$connection = ssh2_connect($this->SFTP_HOST, 22)) {
            echo "Not Able to Establish Connection to CDN\n";
        } else {
            if (!ssh2_auth_password($connection, $this->SFTP_USER, $this->SFTP_PASS)) {
                    echo "fail: unable to authenticate CDN\n";
            }
	}
        
        $filePath= '/published/'.$file_arr_row['SaveAsName'].'/'.$file_arr_row['CdnPath']; 
        $sftp = ssh2_sftp($connection);
        $statinfo = ssh2_sftp_stat($sftp, $filePath);
        
       
        /* /published/000/000/000/000/004/519/20/RoseFalcon_LooksAreEverything_G010001640168b_1_2-256K_44S_2C_cbr1x.mp4 */
        
        $sftp = ssh2_sftp($connection);
        $statinfo = ssh2_sftp_stat($sftp, $filePath);  
        if(!is_array($statinfo) || empty($statinfo)){
           
//            $sql = "update Songs set MP4_FileID=''where FileID ='".$MP4_FileID."'";      
//            $obj_resultset = mysql_query($sql, $this->freegalDBConnectionObj);
            
           $this->ShortLogsString .= " FileID: ".$MP4_FileID;           
           $this->ShortLogsString .= PHP_EOL;
           if($this->EnableShortLogs){
             fwrite($this->ShortLogsFileObj,$this->ShortLogsString); 
           }
 
        }
      
  }
 
  
  

                                                                                                            
}





?>
