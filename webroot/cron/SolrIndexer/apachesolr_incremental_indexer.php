<?php

/**
 * @page apachesolr_indexer.php
 * This page start indexing for Apache Solr & notify owner 
 *
**/

// url to start indexing & preserving old once
$start_index_url = 'http://192.168.100.24:8080/solr/freegalmusic/dataimport?command=delta-import&clean=false'; 

set_time_limit(0);

$sleep_time = 300;
$email_list = 'taran2010jeet@gmail.com';

//log
$log_id = strtotime( date('Y-m-d h:i:s') );
$log_data = PHP_EOL."----------Request (".$log_id.") Start----------------".PHP_EOL;
$log_data .= date('Y-m-d h:i:s').' > Start Time: '.date('Y-m-d h:i:s').PHP_EOL; 


// initiate curl request to trigger indexing
$ch = curl_init($start_index_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if(0 === stripos($start_index_url, 'https')) {
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
}
$start_index_resp = curl_exec ( $ch );
curl_close($ch);

// object type-casted 
$checkValidXml = null;
$checkValidXml = simplexml_load_string($start_index_resp);

// executes IF for valid xml response
if($checkValidXml) {

  // url to start indexing & preserving old once
  $status_index_url = 'http://192.168.100.24:8080/solr/freegalmusic/dataimport?command=status'; 

  // initiate curl request to get status of indexing
  $ch = curl_init($status_index_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  if(0 === stripos($status_index_url, 'https')) {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  }
  $status_index_resp = curl_exec ( $ch );
  curl_close($ch);

  // object type-casted 
  $obj_xml_response = simplexml_load_string($status_index_resp);
  
  // type-casted to array format
  $arrData = (array)$obj_xml_response;
    
  if('busy' == strtolower($arrData['str'][1])){
    // valid
    
    $log_data .= date('Y-m-d h:i:s').' > Incremental Indexing Started ( Response: '.$status_index_resp.' )'.PHP_EOL; 
      
    $msg = 'Incremental Indexing Started ('.$status_index_resp.')';
    mail($email_list, 'Apache Solr Indexer ('.date('Y-m-d h:i').'-'.$log_id.') Status', 'Status :- "'.$msg.'"');
    
    // sleeo perodic
    sleep ( $sleep_time );
    $loop = getOperationStatus($email_list, $log_data, $log_id);
    while($loop){
      sleep ( $sleep_time );
      $loop = getOperationStatus($email_list, $log_data, $log_id);
    }
    
  }else{
    $log_data .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$status_index_resp.' )'.PHP_EOL;
    $log_data .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
    $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";

    $msg = 'Indexing Failed To Start: Internal Error ('.$status_index_resp.')';
    mail($email_list, 'Apache Solr Indexer ('.date('Y-m-d h:i').'-'.$log_id.') Status', 'Status :- "'.$msg.'"');
  }

}//for valid xml response
else{
   
  $log_data .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$start_index_resp.' )'.PHP_EOL;
  $log_data .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
  $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
  
  $msg = 'Indexing Failed To Start: Valid response XML not sent ('.$start_index_resp.')';
  mail($email_list, 'Apache Solr Indexer ('.date('Y-m-d h:i').'-'.$log_id.') Status', 'Status :- "'.$msg.'"');
  writeToLog($log_data);
}//for invalid xml response


/**
 * @function  getOperationStatus
 * returns operation status
 */

function getOperationStatus($email_list, &$log_data, $log_id){

  // url to start indexing & preserving old once
  $status_index_url = 'http://192.168.100.24:8080/solr/freegalmusic/dataimport?command=status'; 

  // initiate curl request to get status of indexing
  $ch = curl_init($status_index_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  if(0 === stripos($status_index_url, 'https')) {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  }
  $status_index_resp = curl_exec ( $ch );
  curl_close($ch);

  // object type-casted 
  $obj_xml_response = simplexml_load_string($status_index_resp);
  
  // type-casted to array format
  $arrData = (array)$obj_xml_response;
  
  if('busy' == strtolower($arrData['str'][1])){
    // valid
    //log
    $log_data .= date('Y-m-d h:i:s').' > Indexing in progress ( Response: '.$status_index_resp.' )'.PHP_EOL;
    
    return 1;
    
  }// if still running
  else{
    
    // object type-casted 
    $obj_xml_response = simplexml_load_string($status_index_resp);
  
    // type-casted to array format
    $arrData = (array)$obj_xml_response;

    //Total Documents Processed
    $total_documents_processed = null;
    $test = (array)$arrData['lst'][2]->str[7];
    $total_documents_processed = $test[0];
  
    //Total Documents Processed
    $total_time = null;
    $test = (array)$arrData['lst'][2]->str[8];
    $total_time = $test[0];
  
    if( (!empty($total_documents_processed)) && (!empty($total_time)) ) {
      //log
      $log_data .= date('Y-m-d h:i:s').' > Indexing completed ( Response: '.$status_index_resp.' )'.PHP_EOL;
      $log_data .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
      $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
      writeToLog($log_data);
      
      $msg = 'Indexer processed '.$total_documents_processed. ' documents in '.$total_time.' hours.'; 
      mail($email_list, 'Apache Solr Indexer ('.date('Y-m-d h:i').'-'.$log_id.') Status', 'Status :- "'.$msg.'"');
    }//completed
    else{
      //log
      $log_data .= date('Y-m-d h:i:s').' > Indexing failed to complete ( Response: '.$status_index_resp.' )'.PHP_EOL;
      $log_data .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
      $log_data .= PHP_EOL."---------Request (".$log_id.") End----------------";
      writeToLog($log_data);
      
      $msg = 'Indexer failed to complete ('.$status_index_resp.')';
      mail($email_list, 'Apache Solr Indexer ('.date('Y-m-d h:i').'-'.$log_id.') Status', 'Status :- "'.$msg.'"');
    }//stopped
    
    return 0;
  }//if either completed or stopped

}

/**
 * @function  writeToLog
 * writes data to log file
 */

function writeToLog($log_data){

  $fp = fopen('SolrIndexStatus.txt', 'a+');
  fwrite($fp, $log_data);
  fclose($fp);
}
 
?>
