<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class StreamingHistory extends AppModel
{
  var $name = 'StreamingHistory';
  var $usetable = 'streaming_histories';
  var $primaryKey = 'id';  
  function getDaysStreamingInformation($libraryID, $date, $territory) {
    
    /*if($libraryID == "all") {
		  
      $all_Ids = '';
		  $sql = "SELECT id from libraries where library_territory = '".$territory."'";
		  $result = mysql_query($sql);
		  while ($row = mysql_fetch_assoc($result)) {
				$all_Ids = $all_Ids.$row["id"].",";
			}
          $lib_condition = "and library_id IN (".rtrim($all_Ids,",").")";
    }
      else {
          $lib_condition = "and library_id = ".$libraryID;
    }
    $date_arr = explode("/", $date);
    $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
    $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
    $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
    );
    return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('streaming_histories.token_id','streaming_histories.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));*/
  }
}
?>