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

    function getDayAllLibraryStreamingDuringReportingPeriod($libraryID, $date, $territory) {
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
            $lib_condition = $all_Ids;
        } else {
//            $lib_condition = "and library_id = " . $libraryID;
//            $lib_condition = "StreamingHistory.library_id=$libraryID";
            $lib_condition = $libraryID;
        }
        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        /*$conditions = array(
            'StreamingHistory.provider_type=countries.provider_type and StreamingHistory.ProdID=countries.ProdID and createdOn BETWEEN "' . $startDate . '" and "' . $endDate . '" ' . $lib_condition . " AND 1 = 1 and StreamingHistory.token_id is not null GROUP BY id  ORDER BY createdOn ASC"
        );*/
        $qryArr=array(
            'joins' => array(
                array(
                    'table' => strtolower($territory).'_countries',
                    'alias' => 'countries',
                    'type' => 'left',
                    'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
                )
             ),
            'fields' => array('sum(StreamingHistory.consumed_time) AS total_streamed'),
            'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',array('StreamingHistory.library_id'=>$lib_condition),'not'=>array('StreamingHistory.token_id'=>null)),
            'recursive' => -1);
        
//        return($this->find('all', $qryArr));
        print_r($this->find('all', $qryArr));exit;
    }
    /*
      Function Name : getDaysStreamedInformation
      Desc : lists all the streaming for the selected day
     */

    function getDaysStreamedByPetronInformation($libraryID, $date, $territory) {
                Configure::write('debug',2);

        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        if ($libraryID == "all") {
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
                $conditions = array('createdOn BETWEEN "' . $startDate . '" and "' . $endDate . '" and token_id is not null ' . $lib_condition . "");

                $count = $this->find(
                        'count', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                        )
                );

                $arr_all_library_streaming[$libraryName] = $count;
            }

            return $arr_all_library_streaming;
        } else {
            $lib_condition = "StreamingHistory.library_id=$libraryID";
            $qryArr=array(
            'joins' => array(
                array(
                    'table' => strtolower($territory).'_countries',
                    'alias' => 'countries',
                    'type' => 'left',
                    'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
                )
             ),
            'fields' => array('distinct(StreamingHistory.patron_id) AS total_patrons'),
            'conditions'=>array('StreamingHistory.provider_type=countries.provider_type','createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null)),
            'recursive' => -1);
            return $this->find('all', $qryArr);
        }
    }
    
    function getDayStreamingReportingPeriod($libraryID, $date, $territory) {
        Configure::write('debug',2);

        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        if ($libraryID == "all") {
            //something
        }else{
            $lib_condition = "StreamingHistory.library_id=$libraryID";
            //$conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC");
            return $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'users',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.library_id=users.library_id')
                    ),
                    array(
                        'table' => 'Songs',
                        'alias' => 'songs',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.ProdID=songs.ProdID')
                    )
                 ),
                'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.patron_id=users.id','songs.provider_type=StreamingHistory.provider_type'), 
                'fields'=>array('StreamingHistory.library_id','StreamingHistory.patron_id','songs.artist','songs.SongTitle As track_title','users.email','StreamingHistory.createdOn'),
                'recursive' => -1));
        }
    }
    function getPatronStreamingDay($libraryID, $date, $territory) {
        Configure::write('debug',2);
        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        
        if ($libraryID == "all") {
            //something
        }else{
            $lib_condition = "StreamingHistory.library_id=$libraryID";
            //$conditions = array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition." AND 1 = 1 GROUP BY id  ORDER BY created ASC");
            return $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'users',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.library_id=users.library_id')
                    ),
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'countries',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
                    )
                 ),
                'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.patron_id=users.id'), 
                'fields'=>array('users.id as patron_id','users.email','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id'),
                'group' => array('StreamingHistory.patron_id'),
                'recursive' => -1));
        }
        return $this->query($sql);
    }
    
    function getDaysGenreStramedInformation($libraryID, $date, $territory) {
        Configure::write('debug',2);
        $date_arr = explode("/", $date);
        $startDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 00:00:00";
        $endDate = $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1] . " 23:59:59";
        
        if ($libraryID == "all") {
            //something
        }else{
            $lib_condition = "StreamingHistory.library_id=$libraryID";
            /*SELECT Songs.Genre ,count(sh.ProdID) from streaming_histories as sh 
left join us_countries as countries on sh.ProdID=countries.ProdID
LEFT JOIN Songs on Songs.ProdID=sh.ProdID AND Songs.provider_type=sh.provider_type 
where sh.provider_type=countries.provider_type and sh.createdOn between '2013-12-02 00:00:00' and '2013-12-02 23:59:59' 
and sh.library_id = '1' and sh.token_id is not null group by Songs.Genre;
             */
            return $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'Songs',
                        'alias' => 'songs',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.ProdID=songs.ProdID')
                    ),
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'countries',
                        'type' => 'left',
                        'conditions' => array('StreamingHistory.ProdID=countries.ProdID')
                    )
                 ),
                'conditions'=>array('StreamingHistory.createdOn BETWEEN "'.$startDate.'" and "'.$endDate.'" ',$lib_condition,'not'=>array('StreamingHistory.token_id'=>null),'StreamingHistory.provider_type=songs.provider_type'), 
                'fields'=>array('songs.Genre','count(StreamingHistory.ProdID) as total_streamed_songs','StreamingHistory.library_id'),
                'group' => array('songs.Genre'),
                'recursive' => -1));
        }
        return $this->query($sql);
    }

}

?>