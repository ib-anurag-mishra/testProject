<?php
/*
 File Name : wishlistvideo.php
 File Description : Models page for the Video Wishlist functionality.
 Author : m68interactive
*/

class WishlistVideo extends AppModel
{
  var $name = 'WishlistVideo';
  var $usetable = 'wishlist_videos'; 
  
  
  /*
   Function Name : getWishListInformation
   Desc : To get the wishlisted songs based on the user's selection
  */
  function getVideoWishListInformation($libraryID, $dateRange, $date, $dateFrom, $dateTo) {
    if($dateRange == 'day') {
      $date_arr = explode("/", $date);
      $startDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 00:00:00";
      $endDate = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1]." 23:59:59";
    }
    elseif($dateRange == 'week') {
        $date_arr = explode("/", $date);
        if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));	
                $endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
        }else{	  
                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));	
                $endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
        }	  
    }
    elseif($dateRange == 'month') {
      $date_arr = explode("/", $date);
      $startDate = date("Y-m-d", strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))." 00:00:00";
      $endDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).'/01/'.date('Y', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])).' 00:00:00'))))." 23:59:59";
    }
    elseif($dateRange == 'year') {
      $date_arr = explode("/", $date);
      $startDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $date_arr[2]))." 00:00:00";
      $endDate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $date_arr[2]))." 23:59:59";
    }
    elseif($dateRange == 'manual') {
      $date_arr_from = explode("/", $dateFrom);
      $date_arr_to = explode("/", $dateTo);
      $startDate = $date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]." 00:00:00";
      $endDate = $date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1]." 23:59:59";
    }
    if($libraryID == "all") {
        return $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'"', '1 = 1 GROUP BY library_id'), 'fields' => array('library_id', 'COUNT(library_id) AS totalWishlistedSongs')));
    }
    else {
        return $this->find('all', array('conditions' => array('created BETWEEN "'.$startDate.'" and "'.$endDate.'" and library_id = '.$libraryID)));
    }
  }
}
?>