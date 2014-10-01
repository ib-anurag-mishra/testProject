<?php

/*
 File Name : streaming_history.php
File Description : Models page for the  streaming_history table.
Author : m68interactive
*/

class StreamingHistory extends AppModel {

	var $name = 'StreamingHistory';
	var $usetable = 'streaming_histories';
	var $primaryKey = 'id';

	function getDayAllLibraryStreamingDuringReportingPeriod($libraryID, $date, $territory,$reportCond=NULL) {
		if ($libraryID == "all") {

		}else{
			$arr_all_library_streaming = array();
			$all_Ids = '';
			$sql = "SELECT id, library_name from libraries where library_territory = '" . $territory . "' ORDER BY library_name ASC";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {

				$date_arr = explode("/", $date);
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";

				$libraryID = $row["id"];
				$libraryName = $row["library_name"];

				$lib_condition = "and library_id = '" . $libraryID . "'";
				$conditions = array('created BETWEEN "' . $startDate . '" and "' . $endDate . '" and token_id is not null ' . $lib_condition . "");

				$count = $this->find(
						'count', array(
								'conditions' => $conditions,
								'recursive' => -1
						)
				);

				$arr_all_library_streaming[$libraryName] = $count;
			}
		}
		return $arr_all_library_streaming;
	}

	/*
	 Function Name : getDaysStreamedInformation
	Desc : lists all the streaming for the selected day
	*/

	function getDaysStreamedInformation($libraryID, $date, $territory,$reportCond=NULL) {
		if(!is_array($date)){
			$date_arr = explode("/", $date);
			if($reportCond=='day'){
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
			}elseif ($reportCond=='week') {
				if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2])).' 23:59:59';
				}else{
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2])).' 23:59:59';
				}
			}elseif ($reportCond=='month'){
				$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
				$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			}
		}else{
			$date_arr_from = explode("/", $date[0]);
			$date_arr_to = explode("/", $date[1]);
			$startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
			$endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
		}
		if ($libraryID == "all") {

			$all_Ids = '';
			$sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids[] = $row["id"];
			}

			$lib_condition = $all_Ids;
		} else {
			$lib_condition = $libraryID;
		}

		if ($libraryID != "all") {
			$qryArr=array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							)
					),
					'fields' => array('count(StreamingHistory.ProdID) AS total_streamed'),
					'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null)),
					'recursive' => -1);
		}else{
			$qryArr=array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							)
					),
					'fields' => array('lib.library_name','count(StreamingHistory.ProdID) as total_count'),
					'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null)),
					'group' => array('StreamingHistory.library_id'),
					'recursive' => -1);
		}
		return($this->find('all', $qryArr));
	}
	/*
	 Function Name : getDaysStreamedInformation
	Desc : lists all the streaming for the selected day
	*/

	function getDaysStreamedByPetronInformation($libraryID, $date, $territory,$reportCond=NULL) {

		if(!is_array($date)){
			$date_arr = explode("/", $date);
			if($reportCond=='day'){
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
			}elseif ($reportCond=='week') {
				if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2])).' 23:59:59';
				}else{
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2])).' 23:59:59';
				}
			}elseif ($reportCond=='month'){
				$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
				$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			}
		}else{
			$date_arr_from = explode("/", $date[0]);
			$date_arr_to = explode("/", $date[1]);
			$startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
			$endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
		}

		if ($libraryID == "all") {
			$all_Ids = '';
			$sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids[] = $row["id"];
			}

			$lib_condition = $all_Ids;
			$arr_all_library_streaming = array();
			$qryArr=array(
					'joins' => array(
							array(
									'table' => strtolower($territory) . '_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							)
					),
					'fields' => array('count(distinct StreamingHistory.patron_id) AS total_patrons', 'lib.library_name','lib.show_barcode'),
					'conditions' => array('StreamingHistory.provider_type=countries.provider_type', 'createdOn BETWEEN "' . $startDate . '" and "' . $endDate . '" ', array('StreamingHistory.library_id' => $lib_condition), 'not' => array('StreamingHistory.token_id' => null)),
					'group' => array('StreamingHistory.library_id'),
					'recursive' => -1);

			return $this->find('all', $qryArr);
		} else {
			$lib_condition = "StreamingHistory.library_id=$libraryID";
			$qryArr=array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							)
					),
					'fields' => array('count(distinct StreamingHistory.patron_id) AS total_patrons','lib.show_barcode'),
					'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null)),
					'recursive' => -1);
			return $this->find('all', $qryArr);
		}
	}

	function getDayStreamingReportingPeriod($libraryID, $date, $territory,$reportCond=NULL) {

		if(!is_array($date)){
			$date_arr = explode("/", $date);
			if($reportCond=='day'){
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
			}elseif ($reportCond=='week') {
				if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2])).' 23:59:59';
				}else{
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2])).' 23:59:59';
				}
			}elseif ($reportCond=='month'){
				$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
				$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			}
		}else{
			$date_arr_from = explode("/", $date[0]);
			$date_arr_to = explode("/", $date[1]);
			$startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
			$endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
		}

		if ($libraryID == "all") {
			$all_Ids = '';
			$sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids[] = $row["id"];
			}

			$lib_condition = $all_Ids;
			$arr_all_library_streaming = array();
			$qryArr=array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							),
							array(
									'table' => 'Songs',
									'alias' => 'songs',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=songs.ProdID')
							)
					),
					'fields' => array('StreamingHistory.library_id','StreamingHistory.patron_id','songs.artist','songs.SongTitle As track_title','StreamingHistory.createdOn'),
					'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null),'songs.provider_type=StreamingHistory.provider_type'),
					'recursive' => -1);

			return $this->find('all', $qryArr);
		}else{
			$lib_condition = "StreamingHistory.library_id=$libraryID";

			return $this->find('all', array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							),
							array(
									'table' => 'Songs',
									'alias' => 'songs',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=songs.ProdID')
							)
					),
					'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'songs.provider_type=StreamingHistory.provider_type'),
					'fields'=>array('StreamingHistory.library_id','StreamingHistory.patron_id','songs.artist','songs.SongTitle As track_title','StreamingHistory.createdOn'),
					'recursive' => -1));
		}
	}
	function getPatronStreamingDay($libraryID, $date, $territory,$reportCond=NULL) {

		if(!is_array($date)){
			$date_arr = explode("/", $date);
			if($reportCond=='day'){
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
			}elseif ($reportCond=='week') {
				if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2])).' 23:59:59';
				}else{
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2])).' 23:59:59';
				}
			}elseif ($reportCond=='month'){
				$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
				$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			}
		}else{
			$date_arr_from = explode("/", $date[0]);
			$date_arr_to = explode("/", $date[1]);
			$startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
			$endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
		}

		if ($libraryID == "all") {
			$all_Ids = '';
			$sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids[] = $row["id"];
			}

			$lib_condition = $all_Ids;
			return $this->find('all', array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
							array(
									'table' => 'users',
									'alias' => 'user',
									'type' => 'left',
									'conditions' => array('user.id=StreamingHistory.patron_id')
							),
							array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							),
                                                        array(
									'table' => 'currentpatrons',
									'alias' => 'Currentpatrons',
									'type' => 'left',
									'conditions' => array('Currentpatrons.patronid = StreamingHistory.patron_id', 'Currentpatrons.libid = StreamingHistory.library_id')
							),
					),
					'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.provider_type=countries.provider_type'),
					'fields'=>array('Currentpatrons.id', 'Currentpatrons.branch_name', 'StreamingHistory.patron_id','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id','user.email','lib.show_barcode'),
					'group' => array('StreamingHistory.patron_id'),
					'recursive' => -1));
		}else{
			$lib_condition = "StreamingHistory.library_id=$libraryID";

			return $this->find('all', array(
					'joins' => array(
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							),
                                                        array(
									'table' => 'libraries',
									'alias' => 'lib',
									'type' => 'left',
									'conditions' => array('lib.id=StreamingHistory.library_id')
							),
							array(
									'table' => 'users',
									'alias' => 'user',
									'type' => 'left',
									'conditions' => array('user.id=StreamingHistory.patron_id')
							),
                                                        array(
									'table' => 'currentpatrons',
									'alias' => 'Currentpatrons',
									'type' => 'left',
									'conditions' => array('Currentpatrons.patronid = StreamingHistory.patron_id', 'Currentpatrons.libid = StreamingHistory.library_id')
							),
					),
					'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.provider_type=countries.provider_type'),
					'fields'=>array('Currentpatrons.id','Currentpatrons.branch_name', 'StreamingHistory.patron_id','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id','user.email','lib.show_barcode'),
					'group' => array('StreamingHistory.patron_id'),
					'recursive' => -1));
		}

	}

	function getDaysGenreStramedInformation($libraryID, $date, $territory,$reportCond=NULL) {

		if(!is_array($date)){
			$date_arr = explode("/", $date);
			if($reportCond=='day'){
				$startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
				$endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
			}elseif ($reportCond=='week') {
				if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(0,0,0,$date_arr[0],($date_arr[1]-date('w', mktime(0,0,0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2])).' 23:59:59';
				}else{
					$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2])).' 00:00:00';
					$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2])).' 23:59:59';
				}
			}elseif ($reportCond=='month'){
				$startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
				$endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
			}
		}else{
			$date_arr_from = explode("/", $date[0]);
			$date_arr_to = explode("/", $date[1]);
			$startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
			$endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
		}
		if ($libraryID == "all") {
			$all_Ids = '';
			$sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$all_Ids[] = $row["id"];
			}

			$lib_condition = $all_Ids;
			return $this->find('all', array(
					'joins' => array(
							array(
									'table' => 'Genre',
									'alias' => 'Genres',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=Genres.ProdID')
							),
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							)
					),
					'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.provider_type=countries.provider_type','StreamingHistory.provider_type=Genres.provider_type'),
					'fields'=>array('Genres.expected_genre','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id'),
					'group' => array('Genres.expected_genre'),
					'recursive' => -1));
		}else{
			$lib_condition = "StreamingHistory.library_id=$libraryID";
			return $this->find('all', array(
					'joins' => array(
							array(
									'table' => 'Genre',
									'alias' => 'Genres',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=Genres.ProdID')
							),
							array(
									'table' => strtolower($territory).'_countries',
									'alias' => 'countries',
									'type' => 'left',
									'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
							)
					),
					'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.provider_type=countries.provider_type','StreamingHistory.provider_type=Genres.provider_type'),
					'fields'=>array('Genres.expected_genre','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id'),
					'group' => array('Genres.expected_genre'),
					'recursive' => -1));
		}
	}
}