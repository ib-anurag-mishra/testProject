<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class Downloadpatron extends AppModel
{
  var $name = 'Downloadpatron';
  var $useTable = 'downloadpatrons';
 
  /*
   Function Name : getTotalPatronDownloadDay
   Desc : get array of total patron downloads for the day
  */
  function getTotalPatronDownloadDay($libraryID, $date, $territory) {
  
    $arr_all_patron_downloads = array();
    $all_Ids = '';
    $sql = "SELECT id, library_name FROM libraries WHERE library_territory = '".$territory."'  ORDER BY library_name ASC";
    $result = mysql_query($sql);
       
    while ($row = mysql_fetch_assoc($result)) {    
        $count = 0;
        $date_arr = explode("/", $date);
        $downloadDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1];

        $libraryID = $row["id"];
        $libraryName = $row["library_name"]; 
        $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE library_id = '.$libraryID.' AND  download_date = "'.$downloadDate.'"
                UNION
                SELECT patron_id FROM download_video_patrons WHERE library_id = '.$libraryID.' AND  download_date = "'.$downloadDate.'") AS table1 GROUP BY patron_id';
        $patronDownload = $this->query($sql);
        if(!empty($patronDownload)){
           $count = count($patronDownload); 

        }
        $arr_all_patron_downloads[$libraryName] = $count;
    }
    return $arr_all_patron_downloads;
 }
  
  /*
   Function Name : getTotalPatronDownloadWeek
   Desc : get array of total patron downloads for the week
  */
  function getTotalPatronDownloadWeek($libraryID, $date, $territory) {
  
    $arr_all_patron_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name FROM libraries WHERE library_territory = '".$territory."'  ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
      $count = 0;
      $date_arr = explode("/", $date);
      if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
        $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
        $endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
      }else{
        $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
        $endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
      }
      
      
      $libraryID = $row["id"];
      $libraryName = $row["library_name"]; 
      $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN"'.$startDate.'" and "'.$endDate.'"
            UNION
            SELECT patron_id FROM download_video_patrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN "'.$startDate.'" and "'.$endDate.'") AS table1 GROUP BY patron_id';      
      
      $patronDownload = $this->query($sql);
        if(!empty($patronDownload)){
           $count = count($patronDownload); 

        }      
          
      $arr_all_patron_downloads[$libraryName] = $count;

		}
    
    
    return $arr_all_patron_downloads;
  }

  /*
   Function Name : getTotalPatronDownloadWeek
   Desc : get array of total patron downloads for the month
  */
  function getTotalPatronDownloadMonth($libraryID, $date, $territory) {
   
    $arr_all_patron_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name FROM libraries WHERE library_territory = '".$territory."'  ORDER BY library_name ASC";
		$result = mysql_query($sql);
       
		while ($row = mysql_fetch_assoc($result)) {    
      $count = 0;
      $date_arr = explode("/", $date);
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
      
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 
      $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN"'.$startDate.'" and "'.$endDate.'"
            UNION
            SELECT patron_id FROM download_video_patrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN "'.$startDate.'" and "'.$endDate.'") AS table1 GROUP BY patron_id';      
      
      $patronDownload = $this->query($sql);
        if(!empty($patronDownload)){
           $count = count($patronDownload); 

        }      
          
      $arr_all_patron_downloads[$libraryName] = $count;
		}
    
    return $arr_all_patron_downloads;  
  
  }
  
  /*
   Function Name : getTotalPatronDownloadYear
   Desc : get array of total patron downloads for the Year
  */
  
  function getTotalPatronDownloadYear($libraryID, $date, $territory) {
   
    $arr_all_patron_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name FROM libraries WHERE library_territory = '".$territory."'  ORDER BY library_name ASC";
		$result = mysql_query($sql);
       
		while ($row = mysql_fetch_assoc($result)) {    
      $count = 0;
      $date_arr = explode("/", $date);
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]));
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]));
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 
      $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN"'.$startDate.'" and "'.$endDate.'"
            UNION
            SELECT patron_id FROM download_video_patrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN "'.$startDate.'" and "'.$endDate.'") AS table1 GROUP BY patron_id';      
      
      $patronDownload = $this->query($sql);
        if(!empty($patronDownload)){
           $count = count($patronDownload); 

        }      
          
      $arr_all_patron_downloads[$libraryName] = $count;
		}
    
    return $arr_all_patron_downloads;  
  
  }

  /*
   Function Name : getTotalPatronDownloadManual
   Desc : get array of total patron downloads for the Manual
  */
  
  function getTotalPatronDownloadManual($libraryID, $date_from, $date_to, $territory) {
  
    $arr_all_patron_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name FROM libraries WHERE library_territory = '".$territory."'  ORDER BY library_name ASC";
		$result = mysql_query($sql);
       
		while ($row = mysql_fetch_assoc($result)) {    
      $count =0;  
      $date_arr_from = explode("/", $date_from);
      $date_arr_to = explode("/", $date_to);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 
      $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN"'.$startDate.'" and "'.$endDate.'"
            UNION
            SELECT patron_id FROM download_video_patrons WHERE library_id = '.$libraryID.' AND  download_date BETWEEN "'.$startDate.'" and "'.$endDate.'") AS table1 GROUP BY patron_id';      
      
      $patronDownload = $this->query($sql);
        if(!empty($patronDownload)){
           $count = count($patronDownload); 

        }      
          
      $arr_all_patron_downloads[$libraryName] = $count;
		}
    
    
    return $arr_all_patron_downloads;
    
  }
  
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
    
    $record = $this->find('all',array('conditions'=>$conditions, 'fields' => array('Currentpatrons.id, `Downloadpatron`.`download_date`, `Downloadpatron`.`library_id`, `Downloadpatron`.`patron_id`, `Downloadpatron`.`email`, `Downloadpatron`.`total`'), 'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Downloadpatron.patron_id', 'Currentpatrons.libid = Downloadpatron.library_id')))));
    
    return $record;
  }
  
  /*
   Function Name : getpatronDaysBothDownloadInformation
   Desc : lists all the downloads for for the selected day
  */
  function getDaysBothDownloadInformation($libraryID, $date, $territory) {
      
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
    $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE download_date = "'.$downloadDate.'"'.$lib_condition.'
            UNION
            SELECT patron_id FROM download_video_patrons WHERE  download_date = "'.$downloadDate.'"'.$lib_condition.') AS table1 GROUP BY patron_id';
    $patronDownload = $this->query($sql);
    return $patronDownload;      

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
		$conditions = array('download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY patron_id, library_id ORDER BY download_date DESC");
                return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('Currentpatrons.id,','email','patron_id','library_id','SUM(total) as total'), 'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Downloadpatron.patron_id', 'Currentpatrons.libid = Downloadpatron.library_id'))))));
	}
        
  /*
   Function Name : getWeeksDownloadInformation
   Desc : lists all the downloads for for the selected week
  */
	function getWeeksBothDownloadInformation($libraryID, $date, $territory) {
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
            $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE download_date BETWEEN  "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.'
            UNION
            SELECT patron_id FROM download_video_patrons WHERE  download_date BETWEEN "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.') AS table1 GROUP BY patron_id';
            $patronDownload = $this->query($sql);
            return $patronDownload;            

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
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY patron_id, library_id ORDER BY download_date DESC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('email','patron_id','library_id','SUM(total) as total'))));
  }
  
  /*
   Function Name : getMonthsBothDownloadInformation
   Desc : lists all the downloads for for the selected month
  */
  function getMonthsBothDownloadInformation($libraryID, $date, $territory) {
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
    $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE download_date BETWEEN  "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.'
    UNION
    SELECT patron_id FROM download_video_patrons WHERE  download_date BETWEEN "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.') AS table1 GROUP BY patron_id';
    $patronDownload = $this->query($sql);
    return $patronDownload;
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
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY patron_id,library_id ORDER BY download_date DESC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('email','patron_id','library_id','SUM(total) as total'))));
  }
  
  /*
   Function Name : getYearsBothDownloadInformation
   Desc : lists all the downloads for for the selected year
  */
  function getYearsBothDownloadInformation($libraryID, $date, $territory) {
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
    $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE download_date BETWEEN  "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.'
    UNION
    SELECT patron_id FROM download_video_patrons WHERE  download_date BETWEEN "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.') AS table1 GROUP BY patron_id';
    $patronDownload = $this->query($sql);
    return $patronDownload;
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
          'download_date BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY patron_id,library_id ORDER BY download_date DESC"
      );
      return array($this->find('all', array('conditions'=>$conditions,'fields'=>array('patron_id','library_id','SUM(total) as total'))));
  }
  
  /*
   Function Name : getManualBothDownloadInformation
   Desc : lists all the downloads for for the selected date range
  */
  function getManualBothDownloadInformation($libraryID, $date_from, $date_to, $territory) {
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
    $sql = 'SELECT * FROM (SELECT patron_id FROM downloadpatrons WHERE download_date BETWEEN  "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.'
    UNION
    SELECT patron_id FROM download_video_patrons WHERE  download_date BETWEEN "'.$startDate.'"and "'.$endDate.'" '.$lib_condition.') AS table1 GROUP BY patron_id';
    $patronDownload = $this->query($sql);
    return $patronDownload;
  }  

}