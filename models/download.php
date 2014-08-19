<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class Download extends AppModel
{
  var $name = 'Download';
  var $belongsTo = array(
    'Genre' => array(
    'className' => 'Genre',
    'foreignKey' => 'ProdID'
    )
  );

  /*
   Function Name : getAllLibraryDownloadsDay
   Desc : creates array of each library downloads for day
  */
  function getAllLibraryDownloadsDay($libraryID, $date, $territory) {
    
    $arr_all_library_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name from libraries where library_territory = '".$territory."' ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {

      $date_arr = explode("/", $date);
      $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
      $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 

      $lib_condition = "and library_id = '".$libraryID."'";
      $conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition."");
      
      $count = $this->find(
        'count', 
        array(
          'conditions'  =>  $conditions,
          'recursive' => -1
        )
      );
      
      $arr_all_library_downloads[$libraryName] = $count;
      
    }
    
    return $arr_all_library_downloads;
          
  }
  
  /*
   Function Name : getAllLibraryDownloadsWeek
   Desc : creates array of each library downloads for week
  */
  function getAllLibraryDownloadsWeek($libraryID, $date, $territory) {
    
    $arr_all_library_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name from libraries where library_territory = '".$territory."' ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {

      $date_arr = explode("/", $date);
      if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
        $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
        $endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
      }else{
        $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
        $endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
      }
      
      $libraryID = $row["id"];
      $libraryName = $row["library_name"]; 

      $lib_condition = "and library_id = '".$libraryID."'";
      $conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition."");
      
      $count = $this->find(
        'count', 
        array(
          'conditions'  =>  $conditions,
          'recursive' => -1
        )
      );
      
      $arr_all_library_downloads[$libraryName] = $count;
          
    }
    
    return $arr_all_library_downloads;
          
  }
  
  /*
   Function Name : getAllLibraryDownloadsMonth
   Desc : creates array of each library downloads for month
  */
  function getAllLibraryDownloadsMonth($libraryID, $date, $territory) {
    
    $arr_all_library_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name from libraries where library_territory = '".$territory."' ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {

      $date_arr = explode("/", $date);
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 

      $lib_condition = "and library_id = '".$libraryID."'";
      $conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition."");
      
      $count = $this->find(
        'count', 
        array(
          'conditions'  =>  $conditions,
          'recursive' => -1
        )
      );
      
      $arr_all_library_downloads[$libraryName] = $count;
      
    }
    
    return $arr_all_library_downloads;
          
  }
  
  /*
   Function Name : getAllLibraryDownloadsYear
   Desc : creates array of each library downloads for year
  */
  
  function getAllLibraryDownloadsYear($libraryID, $date, $territory){
  
    $arr_all_library_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name from libraries where library_territory = '".$territory."' ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {

      $date_arr = explode("/", $date);
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
      
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 

      $lib_condition = "and library_id = '".$libraryID."'";
      $conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition."");
      
      $count = $this->find(
        'count', 
        array(
          'conditions'  =>  $conditions,
          'recursive' => -1
        )
      );
      
      $arr_all_library_downloads[$libraryName] = $count;
      
    }
    
    return $arr_all_library_downloads;
    
  }
  
  /*
   Function Name : getAllLibraryDownloadsManual
   Desc : creates array of each library downloads for Manual
  */
  
  function getAllLibraryDownloadsManual($libraryID, $date_from, $date_to, $territory){
    
    $arr_all_library_downloads = array();
    $all_Ids = '';
		$sql = "SELECT id, library_name from libraries where library_territory = '".$territory."' ORDER BY library_name ASC";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {

      $date_arr_from = explode("/", $date_from);
      $date_arr_to = explode("/", $date_to);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
         
      $libraryID = $row["id"]; 
      $libraryName = $row["library_name"]; 

      $lib_condition = "and library_id = '".$libraryID."'";
      $conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition."");
      
      $count = $this->find(
        'count', 
        array(
          'conditions'  =>  $conditions,
          'recursive' => -1
        )
      );
      
      $arr_all_library_downloads[$libraryName] = $count;
      
    }
    
    return $arr_all_library_downloads;
    
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
    $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
    $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
    $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
    );
    return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Download.id','Download.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));
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
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
		}else{
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
		}
		$conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id ORDER BY created ASC");
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Download.id','Download.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));
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
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
      return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Download.id','Download.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));
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
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
      return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Download.id','Download.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));
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
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
      return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Download.id','Download.library_id','Download.patron_id','Download.artist','Download.track_title','Download.email','Download.created'),'recursive' => -1));
  }

	function getConsortiumDaysDownloadInformation($libraryID, $date) {
		$lib_condition = "and library_id IN (".$libraryID.")";
		$date_arr = explode("/", $date);
		$startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
		$endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
		$conditions = array(
		  'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id"
		);
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Download.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

  /*
   Function Name : getWeeksDownloadInformation
   Desc : lists all the downloads for for the selected week
  */
	function getConsortiumWeeksDownloadInformation($libraryID, $date) {
		$lib_condition = "and library_id IN (".$libraryID.")";
		$date_arr = explode("/", $date);
		if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
		}else{
			$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
			$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
		}
		$conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC");
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Download.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

  /*
   Function Name : getMonthsDownloadInformation
   Desc : lists all the downloads for for the selected month
  */
  function getConsortiumMonthsDownloadInformation($libraryID, $date) {
	  $lib_condition = "and library_id IN (".$libraryID.")";
      $date_arr = explode("/", $date);
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Download.id) AS totalProds'), 'order' => 'Genre.Genre')));
  }

  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected year
  */
  function getConsortiumYearsDownloadInformation($libraryID, $date) {
	$lib_condition = "and library_id IN (".$libraryID.")";
	$date_arr = explode("/", $date);
    $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
    $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
    $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
    return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Download.id) AS totalProds'), 'order' => 'Genre.Genre')));
  }

  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected date range
  */
  function getConsortiumManualDownloadInformation($libraryID, $date) {
	  $lib_condition = "and library_id IN (".$libraryID.")";
      $date_arr_from = explode("/", $date_from);
      $date_arr_to = explode("/", $date_to);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Download.id) AS totalProds'), 'order' => 'Genre.Genre')));
  }

  function getCurrentPatronDownloads($library,$date,$territory=null,$allIds=null) { //,$startTime,$endTime
    if(is_numeric($library)){
      $library = (int)$library;
      $data = $this->query("SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded,Download.library_id,Download.patron_id, CASE Download.email WHEN '' THEN NULL ELSE Download.email END AS emailtest, COUNT(patron_id) AS total FROM downloads AS Download WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id=$library GROUP BY day_downloaded,patron_id,emailtest");
    } else {
      if($library == 'all'){
        $data = $this->query("SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded,Download.library_id,Download.patron_id, CASE Download.email WHEN '' THEN NULL ELSE Download.email END AS emailtest, COUNT(patron_id) AS total FROM downloads AS Download WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id in(".rtrim($allIds,",'").") GROUP BY day_downloaded,patron_id,library_id, emailtest");
      } else {
        //Do nothing
        return false;
      }
    }

    if(!empty($data)){
      return $data;
    } else {
      return false;
    }
  }
  
  function getCurrentPatronBothDownloads($library,$date,$territory=null,$allIds=null) { //,$startTime,$endTime
    if(is_numeric($library)){
      $library = (int)$library;
      
      $sql = "select * from (SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded,Download.library_id,Download.patron_id, CASE Download.email WHEN '' THEN NULL ELSE Download.email END AS emailtest, COUNT(patron_id) AS total FROM downloads AS Download WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id=$library GROUP BY day_downloaded,patron_id,emailtest 
              UNION
              SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded,Download.library_id,Download.patron_id, CASE Download.email WHEN '' THEN NULL ELSE Download.email END AS emailtest, COUNT(patron_id) AS total FROM videodownloads AS Download WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id=$library GROUP BY day_downloaded,patron_id,emailtest ) AS table1 GROUP BY patron_id";
      $data = $this->query($sql);
    } 
    if(!empty($data)){
      return $data;
    } else {
      return false;
    }
  }  

  function getCurrentGenreDownloads($library,$date,$territory=null,$allIds=null) { //,$startTime,$endTime
    if(is_numeric($library)){
      $library = (int)$library;
      $data = $this->query("SELECT day_downloaded,library_id,Genre,count(id) as total FROM (SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded, Download.id, Download.library_id, Genre.Genre FROM downloads AS Download LEFT JOIN Genre AS Genre ON (Download.ProdID = Genre.ProdId) WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id=$library GROUP BY Download.id) as table1 Group by day_downloaded,library_id,Genre");
    } else {
      if($library == 'all'){
        $data = $this->query("SELECT day_downloaded,library_id,Genre,count(id) as total FROM (SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded, Download.id, Download.library_id, Genre.Genre FROM downloads AS Download LEFT JOIN Genre AS Genre ON (Download.ProdID = Genre.ProdId) WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Download.library_id in(".rtrim($allIds,",'").") GROUP BY Download.id) as table1 Group by day_downloaded,Genre");
      } else {
        //Do nothing
        return false;
      }
    }

    if(!empty($data)){
      return $data;
    } else {
      return false;
    }
  }
  
  
  function getDownloadStatus($prodID , $libId, $patId)
    {        
        return $this->find('all', array('conditions' => 
                                                    array('ProdID' => $prodID,
                                                            'library_id' => $libId,
                                                            'patron_id' => $patId,
                                                            'history < 2',
                                                            'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'),
                                                            Configure::read('App.twoWeekEndDate'))
                                                        ),
                                        'limit' => '1'
                                        )
                        );
    }
  
}
?>