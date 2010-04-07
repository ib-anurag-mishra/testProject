<?php
$line = array('Sl No.', 'Library ID', 'Library Name', 'Patron ID', 'Artists Name', 'Track title', 'Downloaded Date');
$csv->addRow($line);

foreach($downloads as $key => $download) {
    $libraryName = $library->getLibraryName($download['Download']['library_id']);
    $line = array($key+1, $download['Download']['library_id'], $libraryName, $download['Download']['patron_id'], $download['Download']['artist'], $download['Download']['track_title'], $download['Download']['created']);
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
    $dateRange = "_for_week_of_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
}
elseif($this->data['Report']['reports_daterange'] == 'month') {
    $dateRange = "_for_month_of_".date("F", $date_arr[0])."_".date("Y", $date_arr[2]);
}
elseif($this->data['Report']['reports_daterange'] == 'Year') {
    $dateRange = "_for_".date("Y", $date_arr[2]);
}
elseif($this->data['Report']['reports_daterange'] == 'manual') {
    $dateRange = "_for_".$date_arr_from[2]."-".$date_arr_from[0]."-".$date_arr_from[1]."_to_".$date_arr_to[2]."-".$date_arr_to[0]."-".$date_arr_to[1];
}
echo $csv->render('DownloadsReport_'.$libraryName.$dateRange.'.csv');
?>