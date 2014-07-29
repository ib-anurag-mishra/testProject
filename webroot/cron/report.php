<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @package report
 * This cron script is intended to run on every week to generate the download report for Sony and SCP to sony server
 **/
include 'functions.php';

$currentDate = date('Y-m-d');
ini_set('memory_limit', '-1');
list($year, $month, $day) = explode('-', $currentDate);
$weekFirstDay = date('Y-m-d', strtotime(date('Y')."W".date('W')."1"));
$monthFirstDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));

if(($currentDate == $weekFirstDay) || ($currentDate == $monthFirstDate)) {
	$reports_dir = SONY_REPORTFILES;
	if(!file_exists($reports_dir)) {
		mkdir($reports_dir);
	}
	
	$logs_dir = IMPORTLOGS;
	if(!file_exists($logs_dir)) {
		mkdir($logs_dir);
	}
	
	$outputFile = "/reports_output_".date('Y_m_d_h_i_s').".txt";
	$logFileWrite = fopen(IMPORTLOGS.$outputFile,'w') or die("Can't Open the file!");
	
	if($currentDate == $weekFirstDay) {
		$StartOfLastWeek = 6 + date("w",mktime());
		$showStartDate = date('Ymd', strtotime("-$StartOfLastWeek day") );
		$showEndDate = date('Ymd', strtotime("last sunday") );
		$condStartDate = date('Y-m-d', strtotime("-$StartOfLastWeek day"))." 00:00:00";
		$condEndDate = date('Y-m-d', strtotime("last sunday"))." 23:59:59";
		
		//For US Libraries
		$report_name = $reports_dir."/PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt";
		
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}
		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$count = $row2['ReportCount']+1;
		} else{
			$count = 1;
		}
		$header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
		fwrite($file, $header . "\n");

		$all_Ids = '';		
		$sql = "SELECT id FROM libraries WHERE library_territory = 'US'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" and library_id IN ('.rtrim($all_Ids,",").') group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#US#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "11#*#";										// Product Type Key
			$sales .= $line['TrkCount'] . "#*#";					// Quantity
			$sales .= "0#*#";										// Quantity Returned
			$sales .= ".65#*#";										// WPU
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Wholesale Value (WPU * Quantity)
			$sales .= ".65#*#";										// Net Invoice Price (same as WPU)
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Net Invoice Value (same as Wholesale Value)
			$sales .= ("1.29" * $line['TrkCount']) . "#*#";			// Retail Value
			$sales .= "0#*#";										// Charity Amount
			$sales .= "USD#*#";										// Currency Key
			$sales .= "0#*#";										// VAT/TAX
			$sales .= "0#*#";										// VAT/TAX Charity Amount
			$sales .= "N#*#";										// Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
			$sales .= "05#*#";										// Distribution Type Key
			$sales .= "20#*#";										// Transaction Type Key
			$sales .= "10#*#";										// Service Type Key
			$sales .= "MP3#*#";										// Media Key
			$sales .= $line['artist'] . "#*#";						// Artist Name (METADATA.Artist)
			$sales .= "#*#";										// Album Title
			$sales .= $line['track_title'];							// Track Title (METADATA.Title)
			fwrite($file, $sales . "\n");
			$numSales = $numSales + $line['TrkCount'];
		}
		$market = "M#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$market .= "#*#";									// Vendor/Retailer Name was Library Ideas#*#
		$market .= "#*#";									// Vendor Key was PM43#*#
		$market .= "US#*#10#*#100";
		fwrite($file, $market . "\n");
		
		$sql = 'SELECT COUNT(*) AS Count FROM downloads';
		$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$trailer = "Z#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$trailer .= $numSales . "#*#";						// Number of Standard Sales Records (total number of N records)
		$trailer .= "1#*#";									// Number of Market Share Records (total number of M records)
		//$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= $numSales . "#*#";
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);
	
		$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
		$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
//		FOR SENDING REPORT TO SONY SERVER USING SFTP 
		if(sendReportFilesftp($report_name, "PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {

//		FOR SENDING REPORT TO SONY SERVER USING FTP 			
//		if(sendReportFileftp($report_name, "PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {
			$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
			$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		}
		
		//for canadian libraries
		$report_name = $reports_dir."/PV96_W_" . $showStartDate . "_" . $showEndDate . ".txt";
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}
		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PV96_W_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$count = $row2['ReportCount']+1;
		} else{
			$count = 1;
		}
		$header = "A#*#PV96#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
		fwrite($file, $header . "\n");

		$all_Ids = '';		
		$sql = "SELECT id FROM libraries WHERE library_territory = 'CA'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" and library_id IN ('.rtrim($all_Ids,",").') group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PV96#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#CA#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "11#*#";										// Product Type Key
			$sales .= $line['TrkCount'] . "#*#";					// Quantity
			$sales .= "0#*#";										// Quantity Returned
			$sales .= ".65#*#";										// WPU
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Wholesale Value (WPU * Quantity)
			$sales .= ".65#*#";										// Net Invoice Price (same as WPU)
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Net Invoice Value (same as Wholesale Value)
			$sales .= ("1.29" * $line['TrkCount']) . "#*#";			// Retail Value
			$sales .= "0#*#";										// Charity Amount
			$sales .= "CAD#*#";										// Currency Key
			$sales .= "0#*#";										// VAT/TAX
			$sales .= "0#*#";										// VAT/TAX Charity Amount
			$sales .= "N#*#";										// Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
			$sales .= "05#*#";										// Distribution Type Key
			$sales .= "20#*#";										// Transaction Type Key
			$sales .= "10#*#";										// Service Type Key
			$sales .= "MP3#*#";										// Media Key
			$sales .= $line['artist'] . "#*#";						// Artist Name (METADATA.Artist)
			$sales .= "#*#";										// Album Title
			$sales .= $line['track_title'];							// Track Title (METADATA.Title)
			fwrite($file, $sales . "\n");
			$numSales = $numSales + $line['TrkCount'];
		}
		$market = "M#*#PV96#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$market .= "#*#";									// Vendor/Retailer Name was Library Ideas#*#
		$market .= "#*#";									// Vendor Key was PM43#*#
		$market .= "CA#*#10#*#100";
		fwrite($file, $market . "\n");
		
		$sql = 'SELECT COUNT(*) AS Count FROM downloads';
		$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$trailer = "Z#*#PV96#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$trailer .= $numSales . "#*#";						// Number of Standard Sales Records (total number of N records)
		$trailer .= "1#*#";									// Number of Market Share Records (total number of M records)
		//$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= $numSales . "#*#";
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);
		
		$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PV96_W_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
		$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
//		FOR SENDING REPORT TO SONY SERVER USING SFTP 
//		if(sendReportFilesftp($report_name, "PV96_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {

//		FOR SENDING REPORT TO SONY SERVER USING FTP 			
		if(sendReportFileftp_CA($report_name, "PV96_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {
			$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
			$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		}
	}
	if($currentDate == $monthFirstDate) {
		$showStartDate = date("Ymd", strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')));
		$showEndDate = date("Ymd", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))));
		$condStartDate = date("Y-m-d", strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))." 00:00:00";
		$condEndDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))))." 23:59:59";
		
		//For US libraries
		$report_name = $reports_dir."/PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt";
		
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}
		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$count = $row2['ReportCount'] + 1;
		} else{
			$count = 1;
		}		
		$header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
		fwrite($file, $header . "\n");
		
		$all_Ids = '';
		$sql = "SELECT id FROM libraries WHERE library_territory = 'US'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" and library_id IN ('.rtrim($all_Ids,",").') group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#US#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "11#*#";										// Product Type Key
			$sales .= $line['TrkCount'] . "#*#";					// Quantity
			$sales .= "0#*#";										// Quantity Returned
			$sales .= ".65#*#";										// WPU
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Wholesale Value (WPU * Quantity)
			$sales .= ".65#*#";										// Net Invoice Price (same as WPU)
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Net Invoice Value (same as Wholesale Value)
			$sales .= ("1.29" * $line['TrkCount']) . "#*#";			// Retail Value
			$sales .= "0#*#";										// Charity Amount
			$sales .= "USD#*#";										// Currency Key
			$sales .= "0#*#";										// VAT/TAX
			$sales .= "0#*#";										// VAT/TAX Charity Amount
			$sales .= "N#*#";										// Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
			$sales .= "05#*#";										// Distribution Type Key
			$sales .= "20#*#";										// Transaction Type Key
			$sales .= "10#*#";										// Service Type Key
			$sales .= "MP3#*#";										// Media Key
			$sales .= $line['artist'] . "#*#";						// Artist Name (METADATA.Artist)
			$sales .= "#*#";										// Album Title
			$sales .= $line['track_title'];							// Track Title (METADATA.Title)
			fwrite($file, $sales . "\n");
			$numSales = $numSales + $line['TrkCount'];
		}
		
		$market = "M#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$market .= "#*#";									// Vendor/Retailer Name was Library Ideas#*#
		$market .= "#*#";									// Vendor Key was PM43#*#
		$market .= "US#*#10#*#100";
		fwrite($file, $market . "\n");

		$sql = 'SELECT COUNT(*) AS Count FROM downloads';
		$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$trailer = "Z#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$trailer .= $numSales . "#*#";						// Number of Standard Sales Records (total number of N records)
		$trailer .= "1#*#";									// Number of Market Share Records (total number of M records)
		//$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= $numSales . "#*#";
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);

		$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
		$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
//		FOR SENDING REPORT TO SONY SERVER USING SFTP 
		if(sendReportFilesftp($report_name, "PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {

//		FOR SENDING REPORT TO SONY SERVER USING FTP 			
//		if(sendReportFileftp($report_name, "PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {
			$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
			$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		}
		
		//For Canadian Libraries
		$report_name = $reports_dir."/PV96_M_" . $showStartDate . "_" . $showEndDate . ".txt";
		
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}

		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PV96_M_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$count = $row2['ReportCount']+1;
		} else{
			$count = 1;
		}		
		
		$header = "A#*#PV96#*#" . $showStartDate . "#*#" . $showEndDate . "#*#".$count;
		fwrite($file, $header . "\n");
		
		$all_Ids = '';
		$sql = "SELECT id FROM libraries WHERE library_territory = 'CA'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$all_Ids = $all_Ids.$row["id"].",";
		}
		
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" and library_id IN ('.rtrim($all_Ids,",").') group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PV96#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#CA#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "11#*#";										// Product Type Key
			$sales .= $line['TrkCount'] . "#*#";					// Quantity
			$sales .= "0#*#";										// Quantity Returned
			$sales .= ".65#*#";										// WPU
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Wholesale Value (WPU * Quantity)
			$sales .= ".65#*#";										// Net Invoice Price (same as WPU)
			$sales .= (".65" * $line['TrkCount']) . "#*#";			// Net Invoice Value (same as Wholesale Value)
			$sales .= ("1.29" * $line['TrkCount']) . "#*#";			// Retail Value
			$sales .= "0#*#";										// Charity Amount
			$sales .= "CAD#*#";										// Currency Key
			$sales .= "0#*#";										// VAT/TAX
			$sales .= "0#*#";										// VAT/TAX Charity Amount
			$sales .= "N#*#";										// Copyright Indicator (NEED TO FIND OUT FROM BRIAN DOWNING)
			$sales .= "05#*#";										// Distribution Type Key
			$sales .= "20#*#";										// Transaction Type Key
			$sales .= "10#*#";										// Service Type Key
			$sales .= "MP3#*#";										// Media Key
			$sales .= $line['artist'] . "#*#";						// Artist Name (METADATA.Artist)
			$sales .= "#*#";										// Album Title
			$sales .= $line['track_title'];							// Track Title (METADATA.Title)
			fwrite($file, $sales . "\n");
			$numSales = $numSales + $line['TrkCount'];
		}
		
		$market = "M#*#PV96#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$market .= "#*#";									// Vendor/Retailer Name was Library Ideas#*#
		$market .= "#*#";									// Vendor Key was PM43#*#
		$market .= "CA#*#10#*#100";
		fwrite($file, $market . "\n");
		
		$sql = 'SELECT COUNT(*) AS Count FROM downloads';
		$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$trailer = "Z#*#PV96#*#" . $showStartDate . "#*#" . $showEndDate . "#*#";
		$trailer .= $numSales . "#*#";						// Number of Standard Sales Records (total number of N records)
		$trailer .= "1#*#";									// Number of Market Share Records (total number of M records)
		//$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= $numSales . "#*#";
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);
		
		$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PV96_M_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
		$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
//		FOR SENDING REPORT TO SONY SERVER USING SFTP 
//		if(sendReportFilesftp($report_name, "PV96_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {

//		FOR SENDING REPORT TO SONY SERVER USING FTP 			
		if(sendReportFileftp_CA($report_name, "PV96_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {
			$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
			$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		}
	}
}
else {
	echo "\nToday is not either the week first day or the month first day so the report didn't get generated.\n";
}
?>