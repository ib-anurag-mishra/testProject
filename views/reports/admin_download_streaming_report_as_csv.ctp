<?php
//echo $testing;exit;
/*
 File Name : admin_download_streaming_report_as_csv.ctp
 File Description : View page for streaming csv
 Author : m68interactive
 */
?>
<?php
// start - Total Straming during Reporting Period


if(!is_array($streamingHours)) {
  
  $line = array('Total Songs Streamed');
  $csv->addRow($line);

  $line = array('Total Streamed (Number of Songs)');
  $csv->addRow($line);

  $line = array($streamingHours);
  $csv->addRow($line);

  $line = array('', '', '', '', '', '');
  $csv->addRow($line);
 
} else {
  
  $line = array('Total Songs Streamed');
  $csv->addRow($line);
  
  $line = array('', 'Library Name', 'Total Streamed (Number of Songs)');
  $csv->addRow($line);
  $key=1;
  foreach($streamingHours as $LibraryName => $streamedCount) {
   
    $line = array($key, $streamedCount['lib']['library_name'], $streamedCount['0']['total_count']);
    $csv->addRow($line);
    $key++;
  }
  
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);

}

  
// end - Total Streaming during Reporting Period 


// start - Total Patrons


if(!is_array($patronStreamedInfo)) {
  
  $line = array('Total Patrons');
  $csv->addRow($line);

  $line = array('Total Number of Patrons who have streamed during Reporting Period');
  $csv->addRow($line);

  $line = array($patronStreamedInfo);
  $csv->addRow($line);
  
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);
  
} else {
  
  $line = array('Total Number of Patrons who have streamed during Reporting Period');
  $csv->addRow($line);
  
  $line = array('', 'Library Name', 'Total Patrons');
  $csv->addRow($line);
  $key=1;
  foreach($patronStreamedInfo as $LibraryName => $streamdCount) {
   
    $line = array($key, $streamdCount['lib']['library_name'], $streamdCount['0']['total_patrons']);
    $csv->addRow($line);
    $key++;
  }
    
  $line = array('', '', '', '', '', '');
  $csv->addRow($line);


}

  
// end - Total Patrons



$line = array('Library Streaming Report');
$csv->addRow($line);

if($this->data['Report']['library_id'] == "all") {
    $line = array('', 'Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Streamed date');
}else{
    $line = array('', 'Patron ID', 'Artists Name', 'Track title', 'Streamed date');
}
$csv->addRow($line);
if($this->data['Report']['library_id'] == "all") {
    foreach($dayStreamingInfo as $key => $stream) {
            if($stream['StreamingHistory']['patron_id']!=''){
                    $patron = $stream['StreamingHistory']['patron_id'];
            }
        $libraryName = $this->getAdminTextEncode($library->getLibraryName($stream['StreamingHistory']['library_id']));
        $line = array($key+1, $libraryName,$patron, $this->getAdminTextEncode($stream['songs']['artist']), $this->getAdminTextEncode($stream['songs']['track_title']), date('Y-m-d', strtotime($stream['StreamingHistory']['createdOn'])));
        $csv->addRow($line);
    }
}else{
    foreach($dayStreamingInfo as $key => $stream) {
            if($stream['StreamingHistory']['patron_id']!=''){
                    $patron = $stream['StreamingHistory']['patron_id'];
            }
        $line = array($key+1,  $patron, $this->getAdminTextEncode($stream['songs']['artist']), $this->getAdminTextEncode($stream['songs']['track_title']), date('Y-m-d', strtotime($stream['StreamingHistory']['createdOn'])));
        $csv->addRow($line);
    }
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Streaming Report');
$csv->addRow($line);

if($this->data['Report']['library_id'] == "all") {
    $line = array('', 'Patron ID', 'Library Name', 'Total Number of Tracks Downloaded');
}else{
    $line = array('', 'Patron ID', 'Total Number of Tracks Downloaded');
}
$csv->addRow($line);

if($this->data['Report']['library_id'] == "all") {
    foreach($patronStreamedDetailedInfo as $key => $patronStreamed) {
            if($patronStreamed['StreamingHistory']['patron_id']!=''){
                    $patron_id = $patronStreamed['StreamingHistory']['patron_id'];
            }

        $line = array($key+1, $patron_id, $this->getAdminTextEncode($library->getLibraryName($patronStreamed['StreamingHistory']['library_id'])),($patronStreamed[0]['total_streamed_songs']));
        $csv->addRow($line);
    }
}else{
    foreach($patronStreamedDetailedInfo as $key => $patronStreamed) {
            if($patronStreamed['StreamingHistory']['patron_id']!=''){
                    $patron_id = $patronStreamed['StreamingHistory']['patron_id'];
            }

        $line = array($key+1, $patron_id, ($patronStreamed[0]['total_streamed_songs']));
        $csv->addRow($line);
    }
}
$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Genres Streaming Report');
$csv->addRow($line);

$line = array('', 'Genre Name', 'Total Number of Tracks Streamed');
$csv->addRow($line);

foreach($genreDayStremedInfo as $key => $genreStreamed) {
    $line = array($key+1, $this->getAdminTextEncode($genreStreamed['songs']['Genre']), ($genreStreamed[0]['total_streamed_songs']));
    $csv->addRow($line);
}



if($this->data['Report']['library_id'] == "all") {
    $libraryName = "All_Libraries";
}
else {
    $libraryName = str_replace(" ", "_", $library->getLibraryName($library_id));
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
    $dateRange = "_for_week_of_".$startDate."_to_".$endDate;
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
echo $csv->render('StreamingReport_'.$libraryName.$dateRange.'.csv');
?>