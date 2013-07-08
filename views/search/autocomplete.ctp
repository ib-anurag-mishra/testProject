<?php
 /*
	 File Name : auto_complete.ctp
	 File Description : View page for auto_complete
	 Author : m68interactive
 */
  if($type != 'all'){
    foreach($records as $record){
      $record = $this->getTextEncode($record);
      echo "$record|$record\n";
    }
  } else {
    foreach($records as $record){
      $record = $this->getTextEncode($record);
      echo "$record\n";
    }
  }
