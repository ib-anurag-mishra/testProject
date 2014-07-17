<?php
ini_set('error_reporting', E_ALL);
set_time_limit(0);
date_default_timezone_set("America/New_York");

$startMonth = date('Y-m-01', strtotime(date("Y-m",strtotime("-4 Months")))) . ' 00:00:00';
$endMonth =  date('Y-m-t', strtotime(date("Y-m",strtotime("-1 Months")))) . ' 23:59:59';

$monthLabels = array();
$monthLabels[] =  $month = date('ym',strtotime('-4 Months'));
for ($i=1; $i < 4; $i++) { 
	$month++;
	if ($month=='13') {
		$month = '01';
	}
	if (strlen($month) == 1) {
		$month = '0' . $month;
	}
	$monthLabels[] = $month;
}

function week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
    return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next sunday', $start)));
}
$weekLabels = array();
list($startWeek, $endWeek) = week_range(date('Y-m-j', strtotime('last monday')));
$end = $endWeek;
for ($i=1; $i < 4; $i++) { 
	list($start, $end) = week_range(date($startWeek, strtotime('last monday')));
	$startWeek = $start;
	$weekLabels[substr(str_replace('-', '', $startWeek), 2)] = array('start' => $startWeek . ' 00:00:00', 'end' =>  $end . ' 23:59:59');
}
ksort($weekLabels);
$week4start = date('Y-m-d', strtotime('last monday', strtotime($endWeek)));
$week4end = date('Y-m-d', strtotime('next sunday', strtotime($week4start)));
$weekLabels[substr(str_replace('-', '', $week4start), 2)] = array('start' => $week4start . ' 00:00:00', 'end' =>  $week4end . ' 23:59:59');

// Connect to the database
$db = new mysqli('192.168.100.114', 'freegal_prod', '}e47^B1EO9hD', 'freegal');
//$db = new mysqli('127.0.0.1', 'root', '', 'freegal', '3306',':/Applications/MAMP/tmp/mysql/mysql.sock');//testing
if ($db->connect_errno) {
	die('There was an error connected to the database.' . "\n". $db->connect_error);
}
$sql = <<<EOT
SELECT
	LEFT (downloads.created, 7) as created,
	DATE_FORMAT(downloads.created, '%y%m') AS MONTH
FROM
	downloads
WHERE 
	downloads.created >= "$startMonth" AND downloads.created <= "$endMonth"
GROUP BY
	MONTH (created)
ORDER BY
	downloads.created ASC
EOT;
$result = $db->query($sql);


if ($result === FALSE) { 
	echo "there was an error with the query";
} elseif ($result && $result->num_rows) {
	$months = array();

	while ($row = $result->fetch_assoc()) {
		$months[$row['created']] = $row['MONTH'];
	}

	$result->free();

	$case_statement = '';
	
	$allMonths = array();

	foreach ($months as $created => $month) {
		
		$case_statement .= ', sum(CASE WHEN LEFT (downloads.created, 7) = "' . $created . '" then 1 else 0 end) "' . $month . '"';
		$allMonths[] = $month;

	}

	$allWeeks = array();

	foreach ($weekLabels as $label => $range) {
		
		$case_statement .= ', sum(CASE WHEN downloads.created >= "' . $range['start'] . '" AND  downloads.created <= "' . $range['end'] . '" then 1 else 0 end) "' . $label . '"';
		$allWeeks[] = $label;

	}
	//echo $case_statement;
	$lastWeekEnd = array_pop($weekLabels);
	$max = max($endMonth, $lastWeekEnd['end']);
	$sql = <<<EOD
SELECT
	libraries.id,
	libraries.library_name
	$case_statement
FROM downloads
JOIN libraries ON downloads.library_id = libraries.id
WHERE downloads.created >= "$startMonth" AND downloads.created <= "$max"
GROUP BY downloads.library_id
ORDER BY libraries.library_name ASC
EOD;
	echo $sql;
	$result2 = $db->query($sql);
	
	if ($result2 === FALSE) {
		echo $sql;
	} elseif ($result2 && $result2->num_rows) {

		// Forms the name for the new file and deletes the file if it already exist
		$file_path = 'reports/';
		$file_name = 'fourMonthWeekDownloads.csv';
		if (file_exists($file_path . $file_name))  {
			unlink($file_path . $file_name);
		}

		//create the file
		$report = fopen($file_path . $file_name, 'w') or die("Can't open file");
		$header = 'Library ID,Library Name,' . $allMonths[0] . ',' . $allMonths[1] . ',' . $allMonths[2] . ',' . $allMonths[3] . ',' . $allWeeks[0] . ',' . $allWeeks[1] . ',' . $allWeeks[2] . ',' . $allWeeks[3] . "\n";
		fwrite($report, $header);

		// Gets all the records
		while ($download = $result2->fetch_assoc()) {
			$final[] = $download;
		}

		$result2->free();

		foreach ($final as $key => $value) {

			$string = $value['id'] . ',' . $value['library_name'] . ',' . $value[$allMonths[0]] . ',' . $value[$allMonths[1]] . ',' . $value[$allMonths[2]] . ',' . $value[$allMonths[3]] . ',' . $value[$allWeeks[0]] . ',' . $value[$allWeeks[1]] . ',' . $value[$allWeeks[2]] . ',' . $value[$allWeeks[3]] . "\n";

			fwrite($report, $string);
		}

		fclose($report);
		print_r($final);

		// array with filenames to be sent as attachment
		// $files = array();
		// $files = array($file_path . $file_name);
		// if (!file_exists($files[0])) {
		// 	exit; echo 'no file exists';
		// }

		$to = "ralphk@libraryideas.com";
		$from = "noreply@freegalmusic.com";
		$bcc = "ralph_kelley@yahoo.com";

		$subject ="Freegalmusic.com:  4 month / 4 week download report";
		$message =
				"Greetings,<br/><br/>

				Attached is a report that contains the total downloads per library for each of the last four months and four weeks.<br/><br/>

				Thanks,<br/>
				The Freegal Music Team<br/><br/>";

		$file = fopen($file_path . $file_name, "rw");
		$data = fread($file, filesize($file_path . $file_name));
		fclose($file);

		$mail_header="From: $from\r\n";
		$mail_header.="Bcc: $bcc\r\n";
		$boundary = "b_".strtoupper(md5(uniqid(time())));

		$data = chunk_split(base64_encode($data));

		// text part of message
		$mail_header .= "MIME-Version: 1.0\r\n";
		$mail_header .= "Content-Type: multipart/mixed; boundary=$boundary\r\n";
		$mail_header .= "\r\n";

		// message
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

}