<?php
/**
File Name : functions.php
File Description : Contains all the necessary function for the xml parser
@author : Maycreate
**/
include 'config.php';
include 'dbconnect.php';

/*
Function Name : updateDownloadPatrons
Description : Function to update downloadpatrons table for download reports
*/
function updateDownloadPatrons($date){
  $checkQuery = "SELECT Count(*) as count FROM downloadpatrons WHERE download_date = '".$date."'";
  $checkRes = mysql_query($checkQuery);
  $row = mysql_fetch_array($checkRes);
  if($row['count']!=0){
    echo "Downloadpatrons data already added for the date ".$date;
    mailUpdate(1,$date,"Downloadpatrons data already added for the date ".$date);
    return;
  } else {
    $updateDownloadPatronsQuery = "INSERT INTO downloadpatrons SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded, Download.library_id,Download.patron_id, CASE Download.email WHEN '' THEN NULL ELSE Download.email END AS emailtest, COUNT(patron_id) AS total FROM downloads AS Download WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) GROUP BY day_downloaded,patron_id,library_id, emailtest";
    if(!mysql_query($updateDownloadPatronsQuery)){
      echo "DownloadPatrons Table Not Updated\n";
      mailUpdate(3,$date,$updateDownloadPatronsQuery);
    } else {
      echo "DownloadPatrons Table Updated Successfullly\n";
      mailUpdate(5,$date,$updateDownloadPatronsQuery);
    }
  }
}

/*
Function Name : updateDownloadGenres
Description : Function to update downloadgenres table for download reports
*/
function updateDownloadGenres($date){
  $checkQuery = "SELECT Count(*) as count FROM downloadgenres WHERE download_date = '".$date."'";
  $checkRes = mysql_query($checkQuery);
  $row = mysql_fetch_array($checkRes);
  if($row['count']!=0){
    echo "Downloadgenres data already added for the date ".$date;
    mailUpdate(2,$date,"Downloadgenres data already added for the date ".$date);
    return;
  } else {
    $updateDownloadGenresQuery = "INSERT INTO downloadgenres SELECT day_downloaded,library_id,Genre,count(id) as total FROM (SELECT date_format(Download.created,'%Y-%m-%d') as day_downloaded, Download.id, Download.library_id, Genre.Genre FROM downloads AS Download LEFT JOIN Genre AS Genre ON (Download.ProdID = Genre.ProdId) WHERE Download.created >= DATE('".$date."') AND Download.created < (DATE('".$date."') + INTERVAL 1 DAY) GROUP BY Download.id) as table1 Group by day_downloaded,library_id,Genre";
    if(!mysql_query($updateDownloadGenresQuery)){
      echo "DownloadGenres Table Not Updated\n";
      mailUpdate(4,$date,$updateDownloadGenresQuery);
    } else {
      echo "DownloadGenres Table Updated Successfullly\n";
      mailUpdate(6,$date,$updateDownloadGenresQuery);
    }
  }
}


function mailUpdate($response,$date,$query){
  $toArray = array('tech@m68interactive.com');
  switch($response){
    case 1:
      $subject = 'Download Patrons Not Updated Successfully';
      $message = "Somebody tried to update downloadpatrons table for date ".$date.". The table is not updated successfullly. ".$query."\n\nThanks,\nFreegal System Mail";
      break;
    case 2:
      $subject = 'Download Genres Not Updated Successfully';
      $message = "Somebody tried to update downloadgenres table for date ".$date.". The table is not updated successfullly. ".$query."\n\nThanks,\nFreegal System Mail";
      break;
    case 3:
      $subject = 'Download Patrons Not Updated Successfully';
      $message = "Somebody tried to update downloadpatrons table for date ".$date.". The table is not updated successfullly. The query executed was : \n\n".$query."\n\nThanks,\nFreegal System Mail";
      break;
    case 4:
      $subject = 'Download Genres Not Updated Successfully';
      $message = "Somebody tried to update downloadgenres table for date ".$date.". The table is not updated successfullly. The query executed was : \n\n".$query."\n\nThanks,\nFreegal System Mail";
      break;
    case 5:
      $subject = 'Download Patrons Updated Successfully For Date '.$date;
      $message = "downloadpatrons table updated for date ".$date.". The query executed was : \n\n".$query."\n\nThanks,\nFreegal System Mail";
      break;
    case 6:
      $subject = 'Download Genres Updated Successfully For Date '.$date;
      $message = "downloadgenres table updated for date ".$date.". The query executed was : \n\n".$query."\n\nThanks,\nFreegal System Mail";
      break;
  }
  $header = "From: ". FROM . " <" . FROM . ">\r\n"; //optional headerfields
  foreach($toArray as $to){
    mail($to , $subject , $message , $header);
  }
}

$dataDate = date('Y-m-d',(time() - 86400));
echo "==========".$dataDate."============\n";
updateDownloadPatrons($dataDate);
updateDownloadGenres($dataDate);
