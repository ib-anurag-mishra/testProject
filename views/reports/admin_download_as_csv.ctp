<?php
$line = array('Library Downloads Report');
$csv->addRow($line);

$line = array('', 'Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Download');
$csv->addRow($line);

foreach($downloads as $key => $download) {
    $libraryName = $library->getLibraryName($download['Download']['library_id']);
    $line = array($key+1, $libraryName, $download['Download']['patron_id'], $download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Downloads Report');
$csv->addRow($line);

$line = array('', 'Patron ID', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($patronDownloads as $key => $patronDownload) {
    $line = array($key+1, $patronDownload['Download']['patron_id'], $patronDownload[0]['totalDownloads']);
    $csv->addRow($line);
}

if($this->data['Report']['library_id'] == "all") {
    $libraryName = "All_Libraries";
}
else {
    $libraryName = "LibraryID_".$download['Download']['library_id'];
}
$date_arr = explode("/", $this->data['Report']['date']);
$date_arr_from = explode("/", $this->data['Report']['date_from']);
$date_arr_to = explode("/", $this->data['Report']['date_to']);
if($this->data['Report']['reports_daterange'] == 'day') {
    $dateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
}
elseif($this->data['Report']['reports_daterange'] == 'week') {
    $startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]-(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))-1), $date_arr[2]));
    $endDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], $date_arr[1]+(7-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
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