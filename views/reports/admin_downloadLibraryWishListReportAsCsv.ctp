<?php
/*
 File Name : admin_downloadLibraryWishListReportAsCsv.ctp
 File Description : 
 Author : m68interactive
 */
?>
<?php
if($this->data['Report']['library_id'] == "all") {
    $libraryName = "All_Libraries";
}
else {
    $libraryName = str_replace(" ", "_", $libraries[$this->data['Report']['library_id']])."_".$wishlists['Wishlist']['library_id'];
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

if($this->data['Report']['library_id'] == "all") {
    $line = array('Library Name', 'Available Downloads', 'Download Limit Type', 'Download Limit', '# of Songs WishListed');
    $csv->addRow($line);
    $totalSongs = 0;
    foreach($wishlists as $key => $wishlist) {
        $libraryDetails = $library->getLibraryDetails($wishlist['Wishlist']['library_id']);
		if($libraryDetails['Library']['library_unlimited'] == 1){
			$text = "Unlimited";
		} else {
			$text = $libraryDetails['Library']['library_available_downloads'];
		}		
        $line = array($libraryDetails['Library']['library_name'], $text, ucwords($libraryDetails['Library']['library_download_type']), $libraryDetails['Library']['library_download_limit'], $wishlist[0]['totalWishlistedSongs']);
        $csv->addRow($line);
        $totalSongs = $totalSongs+$wishlist[0]['totalWishlistedSongs'];
    }
    $line = array('', '', '', '', '');
    $csv->addRow($line);
    $line = array('', '', '', '', 'Total Songs WishListed: '.$totalSongs);
    $csv->addRow($line);
    
    echo $csv->render('WishListsReport_'.$libraryName.$dateRange.'.csv');
}
else {
    $libraryDetails = $library->getLibraryDetails($this->data['Report']['library_id']);
    $line = array('Library Name', 'ID', 'Artists Name', 'Track Title', 'WishListed On');
    $csv->addRow($line);
    foreach($wishlists as $key => $wishlist) {
        if(isset($wishlist['Library']['show_barcode']) && $wishlist['Library']['show_barcode'] == 1){
                    $line = array($libraryDetails['Library']['library_name'], $wishlist['Currentpatrons']['id'], $wishlist['Wishlist']['artist'], $wishlist['Wishlist']['track_title'], date("Y-m-d", strtotime($wishlist['Wishlist']['created'])));

        }else{
                    $line = array($libraryDetails['Library']['library_name'], '-', $wishlist['Wishlist']['artist'], $wishlist['Wishlist']['track_title'], date("Y-m-d", strtotime($wishlist['Wishlist']['created'])));

        }
        
        
        
        $csv->addRow($line);
    }
    $line = array('', '', '', '', '');
    $csv->addRow($line);
	if($libraryDetails['Library']['library_unlimited'] == 1){
		$text = "Unlimited";
	} else {
		$text = $libraryDetails['Library']['library_available_downloads'];
	}	
    $line = array('Available Downloads: '.$text, '', 'Download Limit Type: '.ucwords($libraryDetails['Library']['library_download_type']), 'Download Limit: '.$libraryDetails['Library']['library_download_limit'], 'Total Songs WishListed: '.count($wishlists));
    $csv->addRow($line);
    
    echo $csv->render('WishListsReport_'.$libraryName.$dateRange.'.csv');
}
?>