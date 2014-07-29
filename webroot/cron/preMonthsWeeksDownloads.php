<?php
ini_set('error_reporting', E_ALL);
set_time_limit(0);
date_default_timezone_set("America/New_York");

// Connect to the database
$db = new mysqli('192.168.100.114', 'freegal_prod', '}e47^B1EO9hD', 'freegal');
//$db = new mysqli('127.0.0.1', 'root', '', 'freegal', '3306',':/Applications/MAMP/tmp/mysql/mysql.sock');//testing
if ($db->connect_errno) {
	die('There was an error connected to the database.' . "\n". $db->connect_error);
}

function week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
    return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next sunday', $start)));
}

function getWeekLabels() {
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
	echo "End getWeekLabels \n";
	return $weekLabels;
}
$weekLabels = getWeekLabels();

// This function gets the labels and dates for each of the last four months
function getLastFourMonthsLabels($db) {
	$startMonth = date('Y-m-01', strtotime(date("Y-m",strtotime("-4 Months")))) . ' 00:00:00';
	$endMonth =  date('Y-m-t', strtotime(date("Y-m",strtotime("-1 Months")))) . ' 23:59:59';
	$sql = "SELECT
				LEFT (downloads.created, 7) as created,
				DATE_FORMAT(downloads.created, '%y%m') AS MONTH
			FROM
				downloads
			WHERE 
				downloads.created >= '$startMonth' AND downloads.created <= '$endMonth'
			GROUP BY
				MONTH (created)
			ORDER BY
				downloads.created ASC";

	$result = $db->query($sql);
	$months = array();
	if ($result === FALSE) {
		echo "there was an error with the query";
		exit;
	} elseif ($result && $result->num_rows) {
		while ($row = $result->fetch_assoc()) {
			$months[$row['created']] = $row['MONTH'];
		}
		$result->free();
	}
	echo "End getLastFourMonthsLabels \n";
	return $months;
}
$months = getLastFourMonthsLabels($db);
// print_r($months);
// exit;
// This function gets the library IDs of all of the libraries that are active and have a customer ID
function getLibraryIds($db, $monthLabels, $weekLabels) {
	$sql = "SELECT libraries.id, libraries.library_name, libraries.customer_id FROM libraries WHERE libraries.library_status = 'active' AND libraries.customer_id != 0";
	$result = $db->query($sql);
	if ($result === FALSE) { 
		echo "there was an error getting the library IDs";
	} elseif ($result && $result->num_rows) {
		//$libids = '';
		$weeks = array();
		foreach ($weekLabels as $key => $value) {
			$weeks[] = $key;
		}
		$months = array();
		foreach ($monthLabels as $key => $value) {
			$months[] = $value;
		}
		$libids = array();
		while ($row = $result->fetch_assoc()) {
			$libids[$row['id']] = array(
				'library_name' => $row['library_name'],
				'customer_id' => $row['customer_id'],
				'cte' => '0',
				$months[0] => '0',
				$months[1] => '0',
				$months[2] => '0',
				$months[3] => '0',
				$weeks[0] => '0',
				$weeks[1] => '0',
				$weeks[2] => '0',
				$weeks[3] => '0'
			);
		}
		$result->free();
	}
	echo "End getLibraryIds \n";
	return $libids;
}
$final = getLibraryIds($db, $months, $weekLabels);

// This function gets the start and end contract dates
function getContractStartEnd($db) {
	$sql = 'SELECT concat(min(libraries.library_contract_start_date), " 00:00:00") AS edge FROM libraries WHERE libraries.library_status = "active" AND libraries.customer_id != 0
				UNION
			SELECT concat(max(libraries.library_contract_end_date), " 23:59:59") AS edge FROM libraries WHERE libraries.library_status = "active" AND libraries.customer_id != 0';
	$result = $db->query($sql);
	if ($result === FALSE) { 
		echo "there was an error with the query";
	} elseif ($result && $result->num_rows) {
		$minmax = array();
		while ($row = $result->fetch_assoc()) {
			$minmax[] = $row['edge'];
		}
		$result->free();
	}
	echo "End getContractStartEnd \n";
	return $minmax;
}
list($min, $max) = getContractStartEnd($db);


function getContractToEndPurchases($db, $final, $min, $max, $key) {

	$sql = "SELECT
				libraries.id,
				count(libraries.id) as cte
			FROM downloads
			JOIN libraries ON downloads.library_id = libraries.id
			WHERE libraries.id = $key AND downloads.created >= concat(libraries.library_contract_start_date, ' 00:00:00') AND downloads.created <= concat(libraries.library_contract_end_date, ' 23:59:59')
			LIMIT 1
	";
	$result = $db->query($sql);
	if ($result === FALSE) { 
		echo "there was an error getting the library IDs";
	} elseif ($result && $result->num_rows) {
		while ($row = $result->fetch_assoc()) {
			$final[$row['id']]['cte'] = $row['cte'];
		}
		$result->free();
	}
	echo "End getContractToEndPurchases \n";
	return $final;
}
foreach ($final as $key => $value) {
	$final = getContractToEndPurchases($db, $final, $min, $max, $key);
}

function getLastFourMonthsDownloads($db, $final, $months) {
	$allMonths = array();
	foreach ($months as $created => $month) {	
		$allMonths[] = $month;

		$sql = "SELECT libraries.id, count(libraries.id) as '$month'
				FROM downloads
				JOIN libraries ON downloads.library_id = libraries.id
				WHERE downloads.created LIKE '$created%' AND libraries.library_status = 'active' AND libraries.customer_id != 0
				GROUP BY downloads.library_id";
		print_r($sql);
		$result = $db->query($sql);
		if ($result === FALSE) { 
			echo "there was an error getting the library IDs";
		} elseif ($result && $result->num_rows) {
			while ($row = $result->fetch_assoc()) {
				$final[$row['id']][$month] = $row[$month];
			}
			$result->free();
		}
	}
	echo "End getLastFourMonthsDownloads \n";
	return array($final, $allMonths);
}
list($final, $allMonths) = getLastFourMonthsDownloads($db, $final, $months);

function getLastFourWeeksDownloads($db, $final, $weekLabels) {
	$allWeeks = array();
	foreach ($weekLabels as $label => $range) {	
		$allWeeks[] = $label;
		$min = $range['start'];
		$max = $range['end'];
		$sql = "SELECT libraries.id, count(libraries.id) as '$label'
				FROM downloads
				JOIN libraries ON downloads.library_id = libraries.id
				WHERE downloads.created >= '$min' AND downloads.created <= '$max' AND libraries.library_status = 'active' AND libraries.customer_id != 0
				GROUP BY downloads.library_id";
		print_r($sql);
		$result = $db->query($sql);
		if ($result === FALSE) { 
			echo "there was an error getting the library IDs";
		} elseif ($result && $result->num_rows) {
			while ($row = $result->fetch_assoc()) {
				$final[$row['id']][$label] = $row[$label];
			}
			$result->free();
		}
	}
	echo "End getLastFourMonthsDownloads \n";
	return array($final, $allWeeks);
}

list($final, $allWeeks) = getLastFourWeeksDownloads($db, $final, $weekLabels);
//print_r($final);
//exit;

// Forms the name for the new file and deletes the file if it already exist
$file_path = '../uploads/';
$file_name = 'freegalmusic_download_' . date('ymd') . '.csv';
if (file_exists($file_path . $file_name))  {
	unlink($file_path . $file_name);
}

// Create the file
$report = fopen($file_path . $file_name, 'w') or die("Can't open file");
$header = 'customer_id,library_name,cte,' . $allMonths[0] . ',' . $allMonths[1] . ',' . $allMonths[2] . ',' . $allMonths[3] . ',' . $allWeeks[0] . ',' . $allWeeks[1] . ',' . $allWeeks[2] . ',' . $allWeeks[3] . "\n";
fwrite($report, $header);

foreach ($final as $key => $value) {
	$string = $value['customer_id'] . ',' . $value['library_name'] . ',' . $value['cte'] . ',' . $value[$allMonths[0]] . ',' . $value[$allMonths[1]] . ',' . $value[$allMonths[2]] . ',' . $value[$allMonths[3]] . ',' . $value[$allWeeks[0]] . ',' . $value[$allWeeks[1]] . ',' . $value[$allWeeks[2]] . ',' . $value[$allWeeks[3]] . "\n";
	fwrite($report, $string);
}

fclose($report);
print_r($final);

function sendMail($file_path, $file_name) {
	$to = "ralphk@libraryideas.com";
	$from = "no-reply@freegalmusic.com";
	$bcc = "ralph_kelley@yahoo.com";
	$subject ='Freegalmusic.com: Library download report (' . date('ymd') . ')';
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
sendMail($file_path, $file_name);



