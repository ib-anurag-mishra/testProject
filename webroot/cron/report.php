<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
 * @package report
 * This cron script is intended to run on every week to generate the download report for Sony and SCP to sony server
 **/
include 'functions.php';

$currentDate = date('Y-m-d');
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
		
		$report_name = $reports_dir."/PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt";
		
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}
		
		$header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#2";
		fwrite($file, $header . "\n");
		
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#US#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "10#*#";										// Product Type Key
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
			$numSales++;
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
		$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);
		
		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$sql = "UPDATE sony_reports SET created = now(), modified = now(), is_uploaded = 'no' WHERE id = ".$row2['id'];
			$result4 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if(sendReportFile($report_name, "PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {
				$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".$row2['id'];
				$result5 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			}
		}
		else {
			$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
			$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if(sendReportFile($report_name, "PM43_W_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "weekly")) {
				$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
				$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			}
		}
		echo "\nWeekly report sent successfully!!\n";
	}
	if($currentDate == $monthFirstDate) {
		$showStartDate = date("Ymd", strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')));
		$showEndDate = date("Ymd", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))));
		$condStartDate = date("Y-m-d", strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))." 00:00:00";
		$condEndDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('-1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00')))))." 23:59:59";
		
		$report_name = $reports_dir."/PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt";
		
		$file = fopen($report_name, "w");
	
		if ($file == false) {
			die ("Unable to open/create file");
		}
		
		$header = "A#*#PM43#*#" . $showStartDate . "#*#" . $showEndDate . "#*#2";
		fwrite($file, $header . "\n");
		
		$query = 'SELECT COUNT(ISRC) AS TrkCount, ISRC AS TrkID, artist, track_title, ProductID AS productcode, created FROM `downloads` WHERE created between "'.$condStartDate.'" and "'.$condEndDate.'" group by TrkID, created ORDER BY created';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numSales = 0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales = "N#*#PM43#*#2222#*#" . $showStartDate . "#*#" . $showEndDate . "#*##*##*#US#*#SA#*##*##*#";
			$sales .= $line['productcode'] . '#*#'; 				// UPC/Official Product Number (PhysicalProduct.ProductID)
			$sales .= $line['TrkID'] . "#*#";						// ISRC/Official Track Number (METADATA.ISRC)
			$sales .= "#*#";										// GRID/Official Digital Identifier
			$sales .= "10#*#";										// Product Type Key
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
			$numSales++;
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
		$trailer .= $row['Count'] . "#*#";					// Total Quantity
		$trailer .= "0#*#";									// Total Quantity Free
		$trailer .= "0#*#";									// Total Quantity Promo
		$trailer .= "0";									// Total Quantity Returned
		fwrite($file, $trailer);
		
		fclose($file);
		
		$sql = "SELECT COUNT(*) as ReportCount, id FROM sony_reports WHERE report_name = 'PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt'";
		$result3 = mysql_query($sql) or die('Query failed: ' . mysql_error());
		$row2 = mysql_fetch_array($result3, MYSQL_ASSOC);
		if($row2['ReportCount'] > 0) {
			$sql = "UPDATE sony_reports SET created = now(), modified = now(), is_uploaded = 'no' WHERE id = ".$row2['id'];
			$result4 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if(sendReportFile($report_name, "PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {
				$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".$row2['id'];
				$result5 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			}
		}
		else {
			$sql = "INSERT INTO sony_reports(report_name, report_location, created, modified)values('PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt', '".addslashes(SONY_REPORTFILES)."', now(), now())";
			$result6 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if(sendReportFile($report_name, "PM43_M_" . $showStartDate . "_" . $showEndDate . ".txt", $logFileWrite, "monthly")) {
				$sql = "UPDATE sony_reports SET is_uploaded = 'yes', modified = now() WHERE id = ".mysql_insert_id();
				$result7 = mysql_query($sql) or die('Query failed: ' . mysql_error());
			}
		}
		echo "\nMonthly report sent successfully!!\n";
	}
}
else {
	echo "\nToday is not either the week first day or the month first day so the report didn't get generated.\n";
}
?>