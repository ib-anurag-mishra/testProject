<?php
ini_set('error_reporting', E_ALL);
set_time_limit(0);
date_default_timezone_set("America/New_York");

function sendMail($file_path, $file_name) {
	$to = "ralphk@libraryideas.com";
	$from = "no-reply@freegalmusic.com";
	$bcc = "ralph_kelley@yahoo.com";
	$subject ='TEST - Freegalmusic.com: Library download report (' . date('ymd') . ')';
	$message =
			"Greetings,<br/><br/>
			Attached is a report that contains the total number of downloads each library had during their contract period. This report also contains the total downloads for each of the previous four months and each of the previous four weeks.<br/><br/>
			Thanks,<br/>
			The Freegal Music Team<br/><br/>";

	$file = fopen($file_path . $file_name, "rw");
	$data = fread($file, filesize($file_path . $file_name));
	fclose($file);

	$mail_header="From: $from\r\n";
	$mail_header.="Bcc: $bcc\r\n";
	$boundary = "b_".strtoupper(md5(uniqid(time())));
	$data = chunk_split(base64_encode($data));
	$mail_header .= "MIME-Version: 1.0\r\n";
	$mail_header .= "Content-Type: multipart/mixed; boundary=$boundary\r\n";
	$mail_header .= "\r\n";
	$mail_header .= "--$boundary\r\n";
	$mail_header .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$mail_header .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
	$mail_header .= $message."\r\n\r\n";
	$mail_header .= "\r\n--$boundary\r\n";
	$mail_header .= "Content-Type: application/octet-stream; name=\"".$file_name."\""."\r\n";
	$mail_header .= "Content-Transfer-Encoding: base64\r\n";
	$mail_header .= "Content-Disposition: attachment; filename=\"".$file_name."\""."\r\n";
	$mail_header .= "\r\n".$data;
	$mail_header .= "\r\n--$boundary--";		 
	// send email
	$ok = mail($to, $subject, $message, $mail_header); 
	if ($ok) { 
		echo "<p>Mail sent to $to!</p>\n"; 
	} else { 
		echo "<p>Mail could not be sent!</p>\n"; 
	}
}

// Connect to the database
// $db = new mysqli('192.168.100.114', 'freegal_prod', '}e47^B1EO9hD', 'freegal');
// $db = new mysqli('127.0.0.1', 'root', 'pelebertix', 'freegal', '3306',':/Applications/MAMP/tmp/mysql/mysql.sock');//testing
// if ($db->connect_errno) {
// 	die('There was an error connected to the database.' . "\n". $db->connect_error);
// }

require_once('salesfore_reports.php');

function freegalMusicDownloads() {
	$SalesforceReports = new salesfore_reports('127.0.0.1', 'freegal', 'root', 'pelebertix');
	$labels = $SalesforceReports->getLabels();
	$weeks = $SalesforceReports->getLastFourWeeks();
	$months = $SalesforceReports->getLastFourMonths();
	// This function gets the library IDs of all of the libraries that are active and have a customer ID
	$final = $SalesforceReports->getLibraryIds($labels);
	// This gets the total music downloads for each library's contract period
	$final = $SalesforceReports->getContractToEndDownloads($final);
	// This gets the total music downloads for each library during each period
	$final = $SalesforceReports->getPeriodDownloads($final, $months, $weeks, $labels);
	$file_path = '../uploads/';
	$file_name = 'freegalmusic_download_' . date('ymd') . '.csv';
	$report = $SalesforceReports->createReport($file_path . $file_name, $labels, $final);
	print_r($final);
	sendMail($file_path, $file_name);
}
// freegalMusicDownloads();

function freegalMoviesStreams() {
	$SalesforceReports = new salesfore_reports('127.0.0.1', 'fmovies', 'root', 'pelebertix');
	$labels = $SalesforceReports->getLabels();
	$weeks = $SalesforceReports->getLastFourWeeks();
	$months = $SalesforceReports->getLastFourMonths();
	// This function gets the library IDs of all of the libraries that are active and have a customer ID
	$final = $SalesforceReports->getLibraryIds($labels);
	// This gets the total movie streams for each library's contract period
	$final = $SalesforceReports->getContractToEndDownloads($final);
	// This gets the total movie for each library during each period
	$final = $SalesforceReports->getPeriodDownloads($final, $months, $weeks, $labels);
	$file_path = '../uploads/';
	$file_name = 'freegalmovies_streaming_' . date('ymd') . '.csv';
	$report = $SalesforceReports->createReport($file_path . $file_name, $labels, $final);
	print_r($final);
	sendMail($file_path, $file_name);
}
// freegalMoviesStreams();

function freadingDownloads() {
	$SalesforceReports = new salesfore_reports('127.0.0.1', 'freading', 'root', 'pelebertix');
	$labels = $SalesforceReports->getLabels();
	$weeks = $SalesforceReports->getLastFourWeeks();
	$months = $SalesforceReports->getLastFourMonths();
	// This function gets the library IDs of all of the libraries that are active and have a customer ID
	$final = $SalesforceReports->getLibraryIds($labels);
	// This gets the total book downloads for each library's contract period
	$final = $SalesforceReports->getContractToEndDownloads($final, 'acsdownloads', 'libraryid');
	// This gets the total book downloads for each library during each period
	$final = $SalesforceReports->getPeriodDownloads($final, $months, $weeks, $labels, 'acsdownloads', 'libraryid');
	$file_path = '../uploads/';
	$file_name = 'freading_download_' . date('ymd') . '.csv';
	$report = $SalesforceReports->createReport($file_path . $file_name, $labels, $final);
	print_r($final);
	sendMail($file_path, $file_name);
}
// freadingDownloads();

function freegalMusicStreams() {
	$SalesforceReports = new salesfore_reports('127.0.0.1', 'freegal', 'root', 'pelebertix');
	$labels = $SalesforceReports->getLabels();
	$weeks = $SalesforceReports->getLastFourWeeks();
	$months = $SalesforceReports->getLastFourMonths();
	// This function gets the library IDs of all of the libraries that are active and have a customer ID
	$final = $SalesforceReports->getLibraryIds($labels);
	// This gets the total music streams for each library's contract period
	$final = $SalesforceReports->getContractToEndStreams($final);
	// This gets the total music streams for each library during each period
	$final = $SalesforceReports->getPeriodStreams($final, $months, $weeks, $labels);
	$file_path = '../uploads/';
	$file_name = 'freegalmusic_streaming_' . date('ymd') . '.csv';
	$report = $SalesforceReports->createReport($file_path . $file_name, $labels, $final);
	print_r($final);
	sendMail($file_path, $file_name);
}
freegalMusicStreams();




