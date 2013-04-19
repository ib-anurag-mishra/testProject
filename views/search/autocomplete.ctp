<?php
 /*
	 File Name : auto_complete.ctp
	 File Description : View page for auto_complete
	 Author : m68interactive
 */
  if($type != 'all'){
    foreach($records as $record){
      $record = utf8_decode($record);
      $record = iconv(mb_detect_encoding($record), "UTF-8//IGNORE", $record);
      echo "$record|$record\n";
    }
  } else {
    foreach($records as $record){
      $record = utf8_decode($record);
      $record = iconv(mb_detect_encoding($record), "UTF-8//IGNORE", $record);
      echo "$record\n";
    }
  }
