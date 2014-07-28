<?php
/*
 File Name : videodownload.php
File Description : Models page for the  videodownloads table.
Author : m68interactive
*/

class Videodownload extends AppModel
{
	var $name = 'Videodownload';
	var $usetable = 'videodownloads';
	var $belongsTo = array(
			'Genre' => array(
					'className' => 'Genre',
					'foreignKey' => 'ProdID'
			)
	);

	/*
	 Function Name : getAllLibraryDownloadsDay
	Desc : creates array of each library videodownloads for day
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
	Desc : creates array of each library videodownloads for week
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
	Desc : creates array of each library videodownloads for month
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
	Desc : creates array of each library videodownloads for year
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
	Desc : creates array of each library videodownloads for Manual
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
	Desc : lists all the videodownloads for for the selected day
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
				'Videodownload.created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY Videodownload.id  ORDER BY created ASC"
		);
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Currentpatrons.id', 'Videodownload.id','Videodownload.library_id','Videodownload.patron_id','Videodownload.artist','Videodownload.track_title','Videodownload.email','Videodownload.created'),'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Videodownload.patron_id', 'Currentpatrons.libid = Videodownload.library_id'))),
'recursive' => -1));
	}

	/*
	 Function Name : getWeeksDownloadInformation
	Desc : lists all the videodownloads for for the selected week
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
		$conditions = array('Videodownload.created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY Videodownload.id ORDER BY created ASC");
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Currentpatrons.id', 'Videodownload.id','Videodownload.library_id','Videodownload.patron_id','Videodownload.artist','Videodownload.track_title','Videodownload.email','Videodownload.created'), 'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Videodownload.patron_id', 'Currentpatrons.libid = Videodownload.library_id'))),'recursive' => -1));
	}

	/*
	 Function Name : getMonthsDownloadInformation
	Desc : lists all the videodownloads for for the selected month
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
				'Videodownload.created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY Videodownload.id  ORDER BY created ASC"
		);
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Currentpatrons.id', 'Videodownload.id','Videodownload.library_id','Videodownload.patron_id','Videodownload.artist','Videodownload.track_title','Videodownload.email','Videodownload.created'), 'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Videodownload.patron_id', 'Currentpatrons.libid = Videodownload.library_id'))), 'recursive' => -1));
	}

	/*
	 Function Name : getYearsDownloadInformation
	Desc : lists all the videodownloads for for the selected year
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
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Videodownload.id','Videodownload.library_id','Videodownload.patron_id','Videodownload.artist','Videodownload.track_title','Videodownload.email','Videodownload.created'),'recursive' => -1));
	}

	/*
	 Function Name : getYearsDownloadInformation
	Desc : lists all the videodownloads for for the selected date range
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
				'Videodownload.created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY Videodownload.id  ORDER BY created ASC"
		);
		return $this->find('all', array('conditions'=>$conditions, 'fields'=>array('Currentpatrons.id', 'Videodownload.id','Videodownload.library_id','Videodownload.patron_id','Videodownload.artist','Videodownload.track_title','Videodownload.email','Videodownload.created'), 'joins' => array(array('table' => 'currentpatrons','alias' => 'Currentpatrons','type' => 'left', 'conditions'=> array('Currentpatrons.patronid = Videodownload.patron_id', 'Currentpatrons.libid = Videodownload.library_id'))),'recursive' => -1));
	}

	function getConsortiumDaysDownloadInformation($libraryID, $date) {
		$lib_condition = "and library_id IN (".$libraryID.")";
		$date_arr = explode("/", $date);
		$startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
		$endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
		$conditions = array(
		  'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id"
		);
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Videodownload.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

	/*
	 Function Name : getWeeksDownloadInformation
	Desc : lists all the videodownloads for for the selected week
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
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Videodownload.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

	/*
	 Function Name : getMonthsDownloadInformation
	Desc : lists all the videodownloads for for the selected month
	*/
	function getConsortiumMonthsDownloadInformation($libraryID, $date) {
		$lib_condition = "and library_id IN (".$libraryID.")";
		$date_arr = explode("/", $date);
		$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
		$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
		$conditions = array(
				'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
		);
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Videodownload.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

	/*
	 Function Name : getYearsDownloadInformation
	Desc : lists all the videodownloads for for the selected year
	*/
	function getConsortiumYearsDownloadInformation($libraryID, $date) {
		$lib_condition = "and library_id IN (".$libraryID.")";
		$date_arr = explode("/", $date);
		$startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
		$endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
		$conditions = array(
				'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC"
		);
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Videodownload.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

	/*
	 Function Name : getYearsDownloadInformation
	Desc : lists all the videodownloads for for the selected date range
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
		return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC','recursive' => -1)), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(DISTINCT Videodownload.id) AS totalProds'), 'order' => 'Genre.Genre')));
	}

	function getCurrentPatronDownloads($library,$date,$territory=null,$allIds=null) { //,$startTime,$endTime
		if(is_numeric($library)){
			$library = (int)$library;
			$data = $this->query("SELECT date_format(Videodownload.created,'%Y-%m-%d') as day_downloaded,Videodownload.library_id,Videodownload.patron_id, CASE Videodownload.email WHEN '' THEN NULL ELSE Videodownload.email END AS emailtest, COUNT(patron_id) AS total FROM videodownloads AS Videodownload WHERE Videodownload.created >= DATE('".$date."') AND Videodownload.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Videodownload.library_id=$library GROUP BY day_downloaded,patron_id,emailtest");
		} else {
			if($library == 'all'){
				$data = $this->query("SELECT date_format(Videodownload.created,'%Y-%m-%d') as day_downloaded,Videodownload.library_id,Videodownload.patron_id, CASE Videodownload.email WHEN '' THEN NULL ELSE Videodownload.email END AS emailtest, COUNT(patron_id) AS total FROM videodownloads AS Videodownload WHERE Videodownload.created >= DATE('".$date."') AND Videodownload.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Videodownload.library_id in(".rtrim($allIds,",'").") GROUP BY day_downloaded,patron_id,library_id, emailtest");
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

	function getCurrentGenreDownloads($library,$date,$territory=null,$allIds=null) { //,$startTime,$endTime
		if(is_numeric($library)){
			$library = (int)$library;
			$data = $this->query("SELECT day_downloaded,library_id,Genre,count(id) as total FROM (SELECT date_format(Videodownload.created,'%Y-%m-%d') as day_downloaded, Videodownload.id, Videodownload.library_id, Genre.Genre FROM videodownloads AS Videodownload LEFT JOIN Genre AS Genre ON (Videodownload.ProdID = Genre.ProdId) WHERE Videodownload.created >= DATE('".$date."') AND Videodownload.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Videodownload.library_id=$library GROUP BY Videodownload.id) as table1 Group by day_downloaded,library_id,Genre");
		} else {
			if($library == 'all'){
				$data = $this->query("SELECT day_downloaded,library_id,Genre,count(id) as total FROM (SELECT date_format(Videodownload.created,'%Y-%m-%d') as day_downloaded, Videodownload.id, Videodownload.library_id, Genre.Genre FROM videodownloads AS Videodownload LEFT JOIN Genre AS Genre ON (Videodownload.ProdID = Genre.ProdId) WHERE Videodownload.created >= DATE('".$date."') AND Videodownload.created < (DATE('".$date."') + INTERVAL 1 DAY) AND Videodownload.library_id in(".rtrim($allIds,",'").") GROUP BY Videodownload.id) as table1 Group by day_downloaded,Genre");
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

	/**
	 * This function is used to return the downlaod count
	 * for the current patron
	 *
	 * @param string $library_id
	 * @param string $patron_id
	 * @param string $start_date
	 * @param string $end_date
	 */
	function getPatronDownloadCount($library_id , $patron_id , $start_date , $end_date )
	{
		return $this->find(
				'all', array(
						'fields' => array('DISTINCT ProdID , provider_type, COUNT(DISTINCT id) AS totalProds'),
						'conditions' => array(
								'library_id' => $library_id,
								'patron_id' => $patron_id,
								'history < 2',
								'created BETWEEN ? AND ?' => array($start_date, $end_date)
						),
						'group' => 'ProdID',
				));
	}

	/**
	 * This function check if the video is downloaded or not.
	 * and it return the count.
	 *
	 * @param int $prodId
	 * @param string $provider_type
	 * @param int $library_id
	 * @param int $patron_id
	 * @param datetime $start_date
	 * @param datetime $end_date
	 * @return array
	 */
	function checkVideoDownloadStatus($prodId, $provider_type,$library_id , $patron_id , $start_date , $end_date)
	{
		return $this->find(
				'all', array(
						'fields' => array('DISTINCT ProdID , provider_type, COUNT(DISTINCT id) AS totalProds'),
						'conditions' => array(
								'ProdID' => $prodId,
								'provider_type' => $provider_type,
								'library_id' => $library_id,
								'patron_id' => $patron_id,
								'history < 2',
								'created BETWEEN ? AND ?' => array($start_date, $end_date)
						),
						'group' => 'ProdID',
				));
	}

	public function fetchVideodownloadTopDownloadedVideos( $prefix ) {
	
		$this->unBindModel(array('belongsTo' => array('Genre')));
	
		$options = array(
				'conditions' => array(
						'`Country`.`SalesDate` <=' => 'NOW()',
						'`Video`.`DownloadStatus`' => '1'
				),
				'group' => array('`Videodownload`.`ProdID`'),
				'order' => array('COUNT DESC'),
				'limit' => 100,
				'joins' => array(
						array(
								'table' => '`video`',
								'alias' => '`Video`',
								'type' 	=> 'LEFT',
								'conditions' => array(
										'`Videodownload`.`ProdID` = `Video`.`ProdID`',
										'`Videodownload`.`provider_type` = `Video`.`provider_type`'
								)
						),
						array(
								'table' => '`File`',
								'alias' => '`File`',
								'type'  => 'LEFT',
								'conditions' => array('`Video`.`Image_FileID` = `File`.`FileID`')
						),
						array(
								'table' => '`File`',
								'alias' => '`Video_file`',
								'type'	=> 'LEFT',
								'conditions' => array('`Video_file`.`FileID` = `Video`.`FullLength_FileID`')
						),
						array(
								'table' => '`' . $prefix . 'countries`',
								'alias' => '`Country`',
								'type'	=> 'LEFT',
								'conditions' => array(
										'`Video`.`ProdId` = `Country`.`ProdId`',
										'`Video`.`provider_type` = `Country`.`provider_type`'
								)
						)
				),
				'fields' => array(
						'`Videodownload`.`ProdID`',
						'`Video`.`ProdID`',
						'`Video`.`provider_type`',
						'`Video`.`VideoTitle`',
						'`Video`.`ArtistText`',
						'`Video`.`Advisory`',
						'`File`.`CdnPath`',
						'`File`.`SourceURL`',
						'`Video_file`.`SaveAsName`',
						'COUNT(DISTINCT(`Videodownload`.`id`)) AS COUNT',
						'`Country`.`SalesDate`'
				)
		);
	
		return $this->find('all', $options);
	}
	
	public function fetchVideodownloadTopDownloadedVideosByLibraryIdAndCreated($libraryId) {
	
		$options = array(
				'conditions' => array('library_id' => $libraryId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))),
				'group' => array('ProdID'),
				'fields' => array(
						'ProdID',
						'COUNT(DISTINCT id) AS countProduct',
						'provider_type'
				),
				'order' => 'countProduct DESC',
				'limit' => 15
		);
			
		$this->find('all', $options);
	}
	
	public function fetchVideodownloadTopVideoGenre( $prefix, $territory, $genre, $explicitContent = true ) {
	
		$this->unBindModel( array( 'belongsTo' => array( 'Genre' ) ) );
	
		$options = array(
				'fields' => array(
						'Videodownload.ProdID',
						'Video.ProdID',
						'Video.Advisory',
						'Video.ReferenceID',
						'Video.provider_type',
						'Video.VideoTitle',
						'Video.Genre',
						'Video.ArtistText',
						'File.CdnPath',
						'File.SourceURL',
						'COUNT(DISTINCT(Videodownload.id)) AS COUNT',
						'Country.SalesDate'
				),
				'group' => 'Videodownload.ProdID',
				'order' => 'COUNT DESC',
				'limit' => '0, 10',
				'joins' => array(
						array(
								'table' => 'video',
								'alias' => 'Video',
								'type'  => 'LEFT',
								'conditions' => array('Videodownload.ProdID = Video.ProdID', 'Videodownload.provider_type = Video.provider_type')
						),
						array(
								'table' => 'File',
								'alias' => 'File',
								'type'  => 'LEFT',
								'conditions' => array('Video.Image_FileID = File.FileID')
						),
						array(
								'table' => 'Genre',
								'alias' => 'Genre',
								'type'  => 'LEFT',
								'conditions' => array('Genre.ProdID = Video.ProdID')
						),
						array(
								'table' => $prefix . 'countries',
								'alias' => 'Country',
								'type'  => 'LEFT',
								'conditions' => array('Video.ProdID = Country.ProdID', 'Video.provider_type = Country.provider_type')
						),
						array(
								'table' => 'libraries',
								'alias' => 'Library',
								'type'  => 'LEFT',
								'conditions' => array('Library.id=Videodownload.library_id')
						)
				)
		);
	
		if ( $explicitContent === false ) {
			$options['conditions'] = array(
					'Videodownload.library_id' => 1,
					'Library.library_territory' => $territory,
					'Country.SalesDate <=' => 'NOW()',
					'Video.Genre' => $genre,
					'Video.provider_type = Genre.provider_type',
					'Video.Advisory !=' => 'T'
			);
		} else {
			$options['conditions'] = array(
					'Videodownload.library_id' => 1,
					'Library.library_territory' => $territory,
					'Country.SalesDate <=' => 'NOW()',
					'Video.Genre' => $genre,
					'Video.provider_type = Genre.provider_type'
			);
		}
	
		return $this->find('all', $options);
	}
	
	public function getDownloadStatusOfVideos( $idsProviderType, $libraryId , $patronId, $startDate, $endDate ) {

		$this->unBindModel( array( 'belongsTo' => array( 'Genre' ) ) );

		$options = array(
				'fields' => array('DISTINCT ProdID , provider_type, COUNT(DISTINCT id) AS totalProds'),
				'conditions' => array(
						'(ProdID, provider_type) IN (' . $idsProviderType . ')',
						'library_id' => $libraryId,
						'patron_id' => $patronId,
						'history < 2',
						'created BETWEEN ? AND ?' => array($startDate, $endDate)
				),
				'group' => 'ProdID',
		);
	
		return $this->find( 'all', $options );
	}
}