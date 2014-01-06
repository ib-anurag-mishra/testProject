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

    function getAllLibraryStreamingDuringReportingPeriod($libraryID, $date, $territory) {

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
            $conditions = array('created BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . "");

            $count = $this->find(
                    'count', array(
                'conditions' => $conditions,
                'recursive' => -1
                    )
            );

            $arr_all_library_streaming[$libraryName] = $count;
        }

        return $arr_all_library_streaming;
    }
    
    /*
      Function Name : getDaysStreamedInformation
      Desc : lists all the streaming for the selected day
     */

    function getDaysStreamedInformation($libraryID, $date, $territory) {
        Configure::write('debug',2);
        if ($libraryID == "all") {

            $all_Ids = '';
            $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $all_Ids = $all_Ids . $row["id"] . ",";
            }
//            $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",") . ")";
            $lib_condition = "'StreamingHistory.library_id' =>array (" . rtrim($all_Ids, ",") . ")";
        } else {
//            $lib_condition = "and library_id = " . $libraryID;
            $lib_condition = "'StreamingHistory.library_id'=$libraryID";
        }
        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        /*$conditions = array(
            'StreamingHistory.provider_type=countries.provider_type and StreamingHistory.ProdID=countries.ProdID and createdOn BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 and StreamingHistory.token_id is not null GROUP BY id  ORDER BY createdOn ASC"
        );*/
        $testArr=array(
            'joins' => array(
                array(
                    'table' => strtolower($territory).'_countries',
                    'alias' => 'countries',
                    'type' => 'left',
                    'conditions' => array('StreamingHistory.Prod=countries.ProdID')
                )
             ), 
            'fields' => array('sum(StreamingHistory.consumed_time)'),
            'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','StreamingHistory.createdOn BETWEEN ? and ?' => array($startDate,$endDate),$lib_condition)) ;
        //print_r($testArr);exit;
//        print_r( $this->find('all',array(
//            'joins' => array(
//                array(
//                    'table' => strtolower($territory).'_countries',
//                    'alias' => 'countries',
//                    'type' => 'left',
//                    'conditions' => array('StreamingHistory.ProdID=countries.ProdID','StreamingHistory.provider_type=countries.provider_type')
//                )
//             ), 
//            'fields' => array('sum(StreamingHistory.consumed_time) AS total_streamed'),
//            'conditions'=>array('StreamingHistory.createdOn BETWEEN ? and ?' => array($startDate,$endDate),$lib_condition), 
//            'recursive' => -1)));exit;
        //return $this->find('all', array('joins' => array(array('table' => strtolower($territory).'_countries','alias' => 'countries','type' => 'left','conditions'=>$conditions)), 'fields'=>array('sum(StreamingHistory.consumed_time)'),'recursive' => -1));
        return $this->find('all', array('joins' => array(array('table' => strtolower($territory).'_countries','alias' => 'countries','type' => 'left','conditions'=>$conditions)),'recursive' => -1));
    }
    /*
      Function Name : getDaysStreamedInformation
      Desc : lists all the streaming for the selected day
     */

    function getDaysStreamedByPetronInformation($libraryID, $date, $territory) {
        if ($libraryID == "all") {

            $all_Ids = '';
            $sql = "SELECT id from libraries where library_territory = '" . $territory . "'";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_assoc($result)) {
                $all_Ids = $all_Ids . $row["id"] . ",";
            }
            $lib_condition = "and library_id IN (" . rtrim($all_Ids, ",") . ")";
        } else {
            $lib_condition = "and library_id = " . $libraryID;
        }
        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        $conditions = array(
            'createdOn BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 and StreamingHistory.token_id is not null GROUP BY id  ORDER BY createdOn ASC"
        );
        return $this->find('all', array('conditions' => $conditions, 'fields' => array('StreamingHistory.token_id', 'StreamingHistory.library_id', 'StreamingHistory.patron_id',), 'recursive' => -1));
    }

}

?>