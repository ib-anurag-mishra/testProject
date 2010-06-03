<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : maycreate
*/

class Download extends AppModel
{
  var $name = 'Download';
  var $usetable = 'downloads';
  
  var $belongsTo = array(
    'Genre' => array(
    'className' => 'Genre',
    'foreignKey' => 'ProdID'
    )
  );
  
  /*
   Function Name : getDaysDownloadInformation
   Desc : lists all the downloads for for the selected day
  */
  function getDaysDownloadInformation($libraryID, $date) {
      if($libraryID == "all") {
          $lib_condition = "";
      }
      else {
          $lib_condition = "and library_id = ".$libraryID;
      }
      $date_arr = explode("/", $date);
      $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
      $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(Download.ProdID) AS totalProds'), 'order' => 'Genre.Genre')));
  }
  
  /*
   Function Name : getWeeksDownloadInformation
   Desc : lists all the downloads for for the selected week
  */
  function getWeeksDownloadInformation($libraryID, $date) {
      if($libraryID == "all") {
          $lib_condition = "";
      }
      else {
          $lib_condition = "and library_id = ".$libraryID;
      }
      $date_arr = explode("/", $date);
      $startDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."1"))." 00:00:00";
      $endDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."7"))." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' ,'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(Download.ProdID) AS totalProds'), 'order' => 'Genre.Genre')));
  }
  
  /*
   Function Name : getMonthsDownloadInformation
   Desc : lists all the downloads for for the selected month
  */
  function getMonthsDownloadInformation($libraryID, $date) {
      if($libraryID == "all") {
          $lib_condition = "";
      }
      else {
          $lib_condition = "and library_id = ".$libraryID;
      }
      $date_arr = explode("/", $date);
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(Download.ProdID) AS totalProds'), 'order' => 'Genre.Genre')));
  }
  
  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected year
  */
  function getYearsDownloadInformation($libraryID, $date) {
      if($libraryID == "all") {
          $lib_condition = "";
      }
      else {
          $lib_condition = "and library_id = ".$libraryID;
      }
      $date_arr = explode("/", $date);
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(Download.ProdID) AS totalProds'), 'order' => 'Genre.Genre')));
  }
  
  /*
   Function Name : getYearsDownloadInformation
   Desc : lists all the downloads for for the selected date range
  */
  function getManualDownloadInformation($libraryID, $date_from, $date_to) {
      if($libraryID == "all") {
          $lib_condition = "";
      }
      else {
          $lib_condition = "and library_id = ".$libraryID;
      }
      $date_arr_from = explode("/", $date_from);
      $date_arr_to = explode("/", $date_to);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
      $conditions = array(
          'created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition
      );
      return array($this->find('all', compact('conditions')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition, '1 = 1 GROUP BY patron_id, library_id'), 'fields' => array('patron_id', 'library_id','email' , 'COUNT(patron_id) AS totalDownloads'), 'order' => 'patron_id DESC')), $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" '.$lib_condition), 'group' => array('Genre.Genre'), 'fields' => array('Genre.Genre', 'COUNT(Download.ProdID) AS totalProds'), 'order' => 'Genre.Genre')));
  }
}