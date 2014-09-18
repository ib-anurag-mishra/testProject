<?php
/*
 File Name : admin_downloadConsortiumRenewalReportAsCsv.ctp
 File Description : 
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

$line = array('Library Downloads Report');
$csv->addRow($line);

$line = array('', 'Library Name', 'ID', 'Artists Name', 'Track title', 'Download');
$csv->addRow($line);

foreach($downloads as $key => $download) {
    $patron = $download['Download']['patron_id'];
    $libraryName = $library->getLibraryName($download['Download']['library_id']);
    if(isset($download['Library']['show_barcode']) && ($download['Library']['show_barcode'] == 1 )){
         $line = array($key+1, $libraryName, $patron, $download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    }else{
         $line = array($key+1, $libraryName, '-',$download['Download']['artist'], $download['Download']['track_title'], date('Y-m-d', strtotime($download['Download']['created'])));
    }
    
    
    $csv->addRow($line);
}

$line = array('', '', '', '', '', '');
$csv->addRow($line);

$line = array('Patron Downloads Report');
$csv->addRow($line);

$line = array('', 'ID', 'Library Name', 'Total Number of Tracks Downloaded');
$csv->addRow($line);

foreach($patronDownloads as $key => $patronDownload) {
    $patron_id = $patronDownload['Download']['patron_id'];
    
    if(isset($patronDownload['Library']['show_barcode']) && ($patronDownload['Library']['show_barcode'] == 1 )){
         $line = array($key+1, $patron_id, $library->getLibraryName($patronDownload['Download']['library_id']), $patronDownload[0]['totalDownloads']);
    }else{
         $line = array($key+1, '-', $library->getLibraryName($patronDownload['Download']['library_id']), $patronDownload[0]['totalDownloads']);
    }
    
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
    $libraryName = str_replace(" ","_",$consortium_name)."_".$downloads[0]['Download']['library_id'];
}
$date_arr = explode("/", $this->data['Report']['date']);
$date_arr_from = explode("/", $this->data['Report']['date_from']);
$date_arr_to = explode("/", $this->data['Report']['date_to']);
if($this->data['Report']['reports_daterange'] == 'day') {
    $dateRange = "_for_".$date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
}
elseif($this->data['Report']['reports_daterange'] == 'week') {
	if(date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])) == 0){
		$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))-6, $date_arr[2]));	
		$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2]))), $date_arr[2]));
	}else{	  
		$startDate = date('Y-m-d', mktime(0, 0, 0, $date_arr[0], ($date_arr[1]-date('w', mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2])))+1, $date_arr[2]));	
		$endDate = date('Y-m-d', mktime(23, 59, 59, $date_arr[0], ($date_arr[1]-date('w', mktime(23, 59, 59, $date_arr[0], $date_arr[1], $date_arr[2])))+7, $date_arr[2]));
	}	  
    $dateRange = "_for_week_of_".str_replace("-", "_", $startDate)."_to_".str_replace("-", "_", $endDate);
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