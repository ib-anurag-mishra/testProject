<?php
/*
 File Name : admin_download_as_csv.ctp
 File Description : View page for download csv
 Author : m68interactive
 */
?>

<?php

$line = array('Library Remaining Downloads');
$csv->addRow($line);

$line = array('', 'Library Name', 'Number of Remaining Downloads');
$csv->addRow($line);
$key=1;
foreach($libraries_download as $LibraryName => $DownloadCount) {
	if($DownloadCount['Library']['library_unlimited'] == 1){
		$text = "Unlimited";
	} else {
		$text = $DownloadCount['Library']['library_available_downloads'];
	}
    $line = array($key, $DownloadCount['Library']['library_name'], $text);
    $csv->addRow($line);
	$key++;
}
$line = array('', '', '', '', '', '');
$csv->addRow($line);


// start - Total Downloads during Reporting Period


if(empty($arr_all_library_downloads)) {
  
  $line = array('Total Downloads during Reporting Period');
  $csv->addRow($line);

  $line = array('Total Downloads');
  $csv->addRow($line);

  $line = array(count($downloads)+(count($videoDownloads)*2));
  $csv->addRow($line);

  $line = array('', '', '', '', '', '');
  $csv->addRow($line);
 
} else {
  
  $line = array('Total Downloads during Reporting Period');
  $csv->addRow($line);
  
  $line = array('', 'Library Name', 'Total Downloads');
  $csv->addRow($line);
  $key=1;
  foreach($arr_all_library_downloads as $LibraryName => $DownloadCount) {
   
    $line = array($key, $LibraryName, $DownloadCount+($arr_all_video_library_downloads[$LibraryName]*2));
    $csv->addRow($line);
    $key++;
  }
  
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);

}

  
// end - Total Downloads during Reporting Period 


// start - Total Patrons


if(empty($arr_all_patron_downloads)) {
  
  $line = array('Total Patrons');
  $csv->addRow($line);

  $line = array('Total Number of Patrons who have downloaded during Reporting Period');
  $csv->addRow($line);

  $line = array(count($patronBothDownloads));
  $csv->addRow($line);
  
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);
  
} else {
  
  $line = array('Total Number of Patrons who have downloaded during Reporting Period');
  $csv->addRow($line);
  
  $line = array('', 'Library Name', 'Total Patrons');
  $csv->addRow($line);
  $key=1;
  foreach($arr_all_patron_downloads as $LibraryName => $DownloadCount) {
   
    $line = array($key, $LibraryName, $DownloadCount);
    $csv->addRow($line);
    $key++;
  }
    
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);


}

  
// end - Total Patrons



$line = array('Library Downloads Report');
$csv->addRow($line);

$line = array('', 'Library Name', 'ID', 'Artists Name', 'Track title', 'Download');
$csv->addRow($line);

foreach($downloads as $key => $download) {
    $patron = $download['Currentpatrons']['id'];
    $libraryName = $this->getAdminTextEncode($library->getLibraryName($download['Download']['library_id']));
    $line = array($key+1, $libraryName, $patron, $this->getAdminTextEncode($download['Download']['artist']), $this->getAdminTextEncode($download['Download']['track_title']), date('Y-m-d', strtotime($download['Download']['created'])));
    $csv->addRow($line);
}


$line = array('Library Video Downloads Report');
$csv->addRow($line);

$line = array('', 'Library Name', 'ID', 'Artists Name', 'Video title', 'Download');
$csv->addRow($line);

foreach($videoDownloads as $key => $download) {
    $patron = $download['Currentpatrons']['id'];
    $libraryName = $this->getAdminTextEncode($library->getLibraryName($download['Videodownload']['library_id']));
    $line = array($key+1, $libraryName, $patron, $this->getAdminTextEncode($download['Videodownload']['artist']), $this->getAdminTextEncode($download['Videodownload']['track_title']), date('Y-m-d', strtotime($download['Videodownload']['created'])));
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Downloads Report');
$csv->addRow($line);

$line = array('', 'ID', 'Library Name', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($patronDownloads as $key => $patronDownload) {
    $patron_id = $patronDownload['Currentpatrons']['id'];
    $line = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronDownload['Downloadpatron']['library_id'])), (($dataRange == 'day')?$patronDownload['Downloadpatron']['total']:$patronDownload[0]['total']));
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Video Downloads Report');
$csv->addRow($line);

$line = array('', 'ID', 'Library Name', 'Total Number of Videos Downloaded');
$csv->addRow($line);

foreach($patronVideoDownloads as $key => $patronDownload) {
    $patron_id = $patronDownload['Currentpatrons']['id'];
    $line = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronDownload['DownloadVideoPatron']['library_id'])), (($dataRange == 'day')?$patronDownload['DownloadVideoPatron']['total']:$patronDownload[0]['total']));
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Genres Downloads Report');
$csv->addRow($line);

$line = array('', 'Genre Name', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($genreDownloads as $key => $genreDownload) {
    $line = array($key+1, $this->getAdminTextEncode($genreDownload['Downloadgenre']['genre_name']), (($dataRange == 'day')?$genreDownload['Downloadgenre']['total']:$genreDownload[0]['total']));
    $csv->addRow($line);
}

$line = array('Genres Video Downloads Report');
$csv->addRow($line);

$line = array('', 'Genre Name', 'Total Number of Videos Downloaded');
$csv->addRow($line);

foreach($genreVideoDownloads as $key => $genreDownload) {
    $line = array($key+1, $this->getAdminTextEncode($genreDownload['DownloadVideoGenre']['genre_name']), (($dataRange == 'day')?$genreDownload['DownloadVideoGenre']['total']:$genreDownload[0]['total']));
    $csv->addRow($line);
}


if($this->data['Report']['library_id'] == "all") {
    $libraryName = "All_Libraries";
}
else {
    $libraryName = str_replace(" ", "_", $libraryInfo['Library']['library_name']);
}
$date_arr = explode("/", $this->data['Report']['date']);
$date_arr_from = explode("/", $this->data['Report']['date_from']);
$date_arr_to = explode("/", $this->data['Report']['date_to']);
if($this->data['Report']['reports_daterange'] == 'day') {
    $dateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
}
elseif($this->data['Report']['reports_daterange'] == 'week') {
	if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
		$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));
		$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
	}else{
		$startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));
		$endDate = date('Y-m-d H:i:s', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
	}
    $dateRange = "_for_week_of_".str_replace(" ","_",$startDate)."_to_".str_replace(" ","_",$endDate);
}
elseif($this->data['Report']['reports_daterange'] == 'month') {
    $dateRange = "_for_month_of_".date("F", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."_".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
}
elseif($this->data['Report']['reports_daterange'] == 'year') {
    $dateRange = "_for_".date("Y", mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]));
}
elseif($this->data['Report']['reports_daterange'] == 'manual') {
    $dateRange = "_for_".$date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]."_to_".$date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1];
}
echo $csv->render('DownloadsReport_'.$libraryName.$dateRange.'.csv');
?>