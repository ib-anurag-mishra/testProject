<?php

/**
 * @file Streaming.php
 * Class which performs all MP3 to Mp$ file conversion
 * 
 * 
 * you need to to put (&) person at the end of the script command executation
 **/
 
class Streaming {

 
  //set the database connection variables for staging for test only  
  var $STAGE_DB_HOST = '10.181.56.177';
  var $STAGE_DB_USER = 'freegal_test';
  var $STAGE_DB_PASS = 'c45X^E1X7:TQ';
  var $STAGE_DB1_DB   = 'sony2';
  var $STAGE_DB2_DB   = 'freegal';
  
  
  //set the database connection for production 
  var $PODUCTION_DB_HOST = '192.168.100.114';
  var $PODUCTION_DB_USER = 'freegal_prod';
  var $PODUCTION_DB_PASS = '}e47^B1EO9hD';
  var $PODUCTION_DB1_DB   = 'sony';
  var $PODUCTION_DB2_DB   = 'freegal';
  
  
  var $sonyDBConnectioObj;
  var $freegalDBConnectioObj;
 

  var $LIVE = '0'; //1-live,0-stage
 
  var $Instance = null;
  var $ProcessedRowsCount = 1;
  var $ChunkSize = 5;       //default set 1000
  var $LimitIndex = 0;         //default set 0
  var $LimitCount = 10;    //default set 100000

 
  /**
   * Constructer, (intialize object) connection to db1 
   *
   **/
  function __construct(){
      
     //set the database connection
     if($this->LIVE == '1'){
         
          // connect to  Production 
          //connect to sony database
          $this->sonyDBConnectioObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS)
            or die('Could not connect to mysql server for sony db of live.' );
          mysql_select_db($this->PODUCTION_DB1_DB, $this->sonyDBConnectioObj) 
            or die('Could not select database.');          
          
          //connect to freegal database
          $this->freegalDBConnectioObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS)
            or die('Could not connect to mysql server for freegal db of live.' );
          mysql_select_db($this->PODUCTION_DB2_DB, $this->freegalDBConnectioObj) 
            or die('Could not select database.');   
    
     } else {
         echo 1;
          // connect to  stage 
          //connect to sony database
          $this->sonyDBConnectioObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS)
            or die('Could not connect to mysql server for sony db of stage.' );
          mysql_select_db($this->STAGE_DB1_DB, $this->sonyDBConnectioObj) 
            or die('Could not select database.');          
          
          //connect to freegal database
          $this->freegalDBConnectioObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS)
            or die('Could not connect to mysql server for freegal db of stage.' );
          mysql_select_db($this->STAGE_DB2_DB, $this->freegalDBConnectioObj) 
            or die('Could not select database.');
         
     }
          
  }
  
  
  
  /**
   *@function  getAllSongsData
   *Fetches all songs data for processing
   *
   *@return array
   **/

  function getAllSongsData() {
    
    $totRows = $this->LimitCount + $this->LimitIndex;     $iniTotRows = $totRows; 

    while($totRows > 0) {

      $index = $limit = null;
      if($totRows < $this->ChunkSize) { 
        $index = $this->LimitIndex;
        $limit = $totRows;
      }else{
        $index = $this->LimitIndex;
        $limit = $this->ChunkSize;
      }
      echo$songQuery ='SELECT Songs.ProdID, Songs.provider_type FROM Songs where Songs.DownloadStatus=1 and  Songs.provider_type="sony" ORDER BY Songs.ProdID ASC 
                                    LIMIT '.$index.', '.$limit;
      
      echo '<br><br>';
      $obj_resultset = mysql_query( $songQuery, $this->freegalDBConnectioObj);
                                 
      while($arr_row = mysql_fetch_assoc($obj_resultset)){
        
        $this->checkRecordInSonyDB($arr_row);
      }
      
      $this->LimitIndex = $this->LimitIndex + $limit;
 
      $totRows = $iniTotRows - $this->LimitIndex;
      
    }   
    
  }
  
  /**
   *@function  checkRecordInSonyDB
   *check the song prodid in sony database table
   *
   *@return array
   **/

  function checkRecordInSonyDB($arr_row) {   
     
      echo $ProdID = $arrSong['ProdID'];   
      die;
      
      $sql = "SELECT PRODUCT_OFFER.ProdID, Availability.AvailabilityStatus,SALES_TERRITORY.SALES_START_DATE \n"
                        . "FROM Availability INNER JOIN PRODUCT_OFFER ON Availability.ProdID = PRODUCT_OFFER.ProdID \n"
                        . " INNER JOIN SALES_TERRITORY ON SALES_TERRITORY.PRODUCT_OFFER_ID = PRODUCT_OFFER.PRODUCT_OFFER_ID \n"
                        . "WHERE Availability.AvailabilityType = 'SUBSCRIPTION' AND \n"
                        . " SALES_TERRITORY.PRICE_CATEGORY = 'SUBSCRIPTION' AND \n"
                        . " Availability.AvailabilityStatus = 'I' AND \n"
                        . " PRODUCT_OFFER.ProdID = $ProdID";
      
      $obj_resultset = mysql_query($sql, $this->sonyDBConnectioObj);
                                 
      while($arr_row = mysql_fetch_assoc($obj_resultset)){
        print_r($arr_row);
        
      }
     
      
 }   
    



}





?>
