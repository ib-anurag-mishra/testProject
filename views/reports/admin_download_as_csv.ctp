<?php
$line = array('Library Remaining Downloads');
$csv->addRow($line);

$line = array('', 'Library Name', 'Number of Remaining Downloads');
$csv->addRow($line);
$key=1;
foreach($libraries_download as $LibraryName => $DownloadCount) {
    $line = array($key, $LibraryName, $DownloadCount);
    $csv->addRow($line);
	$key++;
}
$line = array('Library Downloads Report');
$csv->addRow($line);

$line = array('', 'Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Download');
$csv->addRow($line);

foreach($downloads as $key => $download) {
	if($download['Download']['email']!=''){
		$patron = $download['Download']['email'];
	}
	else{
		$patron = $download['Download']['patron_id'];
	}
    $libraryName = $library->getLibraryName($download['Download']['library_id']);
    $line = array($key+1, $libraryName, $patron, $download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Downloads Report');
$csv->addRow($line);

$line = array('', 'Patron ID', 'Library Name', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($patronDownloads as $key => $patronDownload) {
	if($patronDownload['Download']['email']!=''){
		$patron_id = $patronDownload['Download']['email'];
	}
	else{
		$patron_id = $patronDownload['Download']['patron_id'];
	}
    $line = array($key+1, $patron_id, $library->getLibraryName($patronDownload['Download']['library_id']), $patronDownload[0]['totalDownloads']);
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Genres Downloads Report');
$csv->addRow($line);

$line = array('', 'Genre Name', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($genreDownloads as $key => $genreDownload) {
    $line = array($key+1, $genreDownload['Genre']['Genre'], $genreDownload[0]['totalProds']);
    $csv->addRow($line);
}

if($this->data['Report']['library_id'] == "all") {
    $libraryName = "All_Libraries";
}
else {
    $libraryName = "LibraryID_".$downloads[0]['Download']['library_id'];
}
$date_arr = explode("/", $this->data['Report']['date']);
$date_arr_from = explode("/", $this->data['Report']['date_from']);
$date_arr_to = explode("/", $this->data['Report']['date_to']);
if($this->data['Report']['reports_daterange'] == 'day') {
    $dateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
}
elseif($this->data['Report']['reports_daterange'] == 'week') {
    $startDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."1"));
    $endDate = date('Y-m-d', strtotime($date_arr[2]."W".date('W', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))."7"));
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
echo $csv->render('DownloadsReport_'.$libraryName.$dateRange.'.csv');
?>