<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class Downloadgenre extends AppModel
{
  var $name = 'Downloadgenre';

  /*
   Function Name : getDaysDownloadInformation
   Desc : lists all the downloads for for the selected day
  */
  function getDaysDownloadInformation($libraryID, $date, $territory) {
    if($libraryID == "all") {
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
    $downloadDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
    $conditions = array(
      'download_date = "'.$downloadDate.'" '.$lib_condition." ORDER BY download_date DESC"
    );
    return $this->find('all',array('conditions'=>$conditions));
  }

  /*
   Function Name : getWeeksDownloadInformation
   Desc : lists all the downloads for for the selected week
  */
	function getWeeksDownloadInformation($libraryID, $date, $territory) {
		if($libraryID == "all") {
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
		if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
			$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
			$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
		}else{
			$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
			$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
		}
		$conditions = array('download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY genre_name, library_id ORDER BY genre_name ASC");
    return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('genre_name','SUM(total) as total'))));
	}

  /*
   Function Name : getMonthsDownloadInformation
   Desc : lists all the downloads for for the selected month
  */
  function getMonthsDownloadInformation($libraryID, $date, $territory) {
      if($libraryID == "all") {
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
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
      $conditions = array(
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY genre_name, library_id ORDER BY genre_name ASC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('genre_name','SUM(total) as total'))));
  }

  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected year
  */
  function getYearsDownloadInformation($libraryID, $date, $territory) {
      if($libraryID == "all") {
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
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]));
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]));
      $conditions = array(
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY genre_name, library_id ORDER BY genre_name ASC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('genre_name','SUM(total) as total'))));
  }

  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected date range
  */
  function getManualDownloadInformation($libraryID, $date_from, $date_to, $territory) {
      if($libraryID == "all") {
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
      $date_arr_from = explode("/", $date_from);
      $date_arr_to = explode("/", $date_to);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
      $conditions = array(
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY genre_name, library_id ORDER BY genre_name ASC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('genre_name','SUM(total) as total'))));
  }

}