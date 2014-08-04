<?php
ini_set('error_reporting', E_ALL);
set_time_limit(0);
date_default_timezone_set("America/New_York");

class salesfore_reports {
	
	private $conn;
	private $dsn;
	private $user;
	private $pass;
	private $labels = array(
		'four_months_ago',
		'three_months_ago',
		'two_months_ago',
		'last_month',
		'four_weeks_ago',
		'three_weeks_ago',
		'two_weeks_ago',
		'last_week'
	);

	public function __construct($host, $db, $user, $pass){	
		$dsn = 'mysql:host=' . $host . ';dbname=' . $db;
		$user = $user;
		$pass = $pass;
		try {
			$this->conn = new PDO($dsn, $user, $pass);
			$this->conn->query('SET NAMES utf8'); 
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage() . "\n";
			exit;
		}
	} // end __construct()

	public function getLabels() {
		return $this->labels;
	}

	public function getLastFourWeeks() {
		$weeks = array();
		for ($i=5; $i > 1; $i--) { 
			$weeks[] = array(
				'start' => date("Y-m-d",strtotime("-" . $i . " monday")) . ' 00:00:00',
				'end' => date("Y-m-d",strtotime("-" . $i . " sunday")) . ' 23:59:59'
			);
		}
		echo "End getWeekLabels \n";
		return $weeks;
	}

	public function getLastFourMonths() {
		$months = array();
		for ($i=4; $i > 0; $i--) { 
			$created = date("Y-m", strtotime("-" . $i . " Months"));
			$months[] = $created;
		}
		return $months;
	}

	public function getLibraryIds($labels){
		$sql = "SELECT libraries.id, libraries.library_name, libraries.customer_id FROM libraries WHERE libraries.library_status = 'active' AND libraries.library_type = 2 AND libraries.customer_id != 0";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$libids = array();
		foreach($result as $row){
			$libids[$row['id']] = array(
				'library_name' => $row['library_name'],
				'customer_id' => $row['customer_id'],
				'cte' => '0',
				$labels[0] => '0',
				$labels[1] => '0',
				$labels[2] => '0',
				$labels[3] => '0',
				$labels[4] => '0',
				$labels[5] => '0',
				$labels[6] => '0',
				$labels[7] => '0'
			);
		}
		echo 'getLibraryIds() successful' . "\n";
		return $libids;
	} // end getLibraryIds()

	public function getContractToEndStreams($final) {
		foreach ($final as $library_id => $value) {
			$sql = "SELECT
						libraries.id,
						count(
							DISTINCT streaming_histories.token_id
						) AS cte
					FROM
						streaming_histories
					JOIN libraries ON streaming_histories.library_id = libraries.id
					WHERE
						streaming_histories.library_id = $library_id
					AND streaming_histories.createdOn BETWEEN concat(
						libraries.library_contract_start_date,
						' 00:00:00'
					)
					AND concat(
						libraries.library_contract_end_date,
						' 23:59:59'
					)
					AND streaming_histories.token_id IS NOT NULL
					LIMIT 1
			";

			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row){
				$final[$row['id']]['cte'] = $row['cte'];
			}
			echo "$library_id" . ': getContractToEndStreams - ' . $row['cte'] . "\n";
		}
		return $final;
	}

	public function getPeriodStreams($final, $months, $weeks, $labels) {
		$case_statement = '';
	 	for ($i=0; $i < 4; $i++) { 
	 		$case_statement .= ', sum(CASE WHEN LEFT (createdOn, 7) = "' . $months[$i] . '" then 1 else 0 end) "' . $labels[$i] . '"';
	 	}
	 	for ($i=0; $i <4 ; $i++) { 
	 		$j = $i + 4;
	 		$case_statement .= ', sum(CASE WHEN createdOn BETWEEN "' . $weeks[$i]['start'] . '" AND "' . $weeks[$i]['end'] . '" then 1 else 0 end) "' . $labels[$j] . '"';
	 	}
	 	$lastWeekEnd = array_pop($weeks);
	 	$startMonth = date('Y-m-01', strtotime(date("Y-m",strtotime("-4 Months")))) . ' 00:00:00';
		$max = max(date('Y-m-t', strtotime(date("Y-m",strtotime("-1 Months")))) . ' 23:59:59', $lastWeekEnd['end']);
	 	foreach ($final as $library_id => $value) {	
			$sql = "SELECT
						library_id$case_statement
					FROM
						(
							SELECT DISTINCT
								streaming_histories.token_id,
								streaming_histories.createdOn,
								streaming_histories.library_id
							FROM
								streaming_histories
							WHERE
								streaming_histories.createdOn BETWEEN '$startMonth'
							AND '$max'
							AND streaming_histories.library_id = $library_id
							AND streaming_histories.token_id IS NOT NULL
						) AS foo
					LIMIT 1";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row){
				if (!array_key_exists($row['library_id'], $final)) {
					break;
				}
				$final[$row['library_id']][$labels[0]] = $row[$labels[0]];
				$final[$row['library_id']][$labels[1]] = $row[$labels[1]];
				$final[$row['library_id']][$labels[2]] = $row[$labels[2]];
				$final[$row['library_id']][$labels[3]] = $row[$labels[3]];
				$final[$row['library_id']][$labels[4]] = $row[$labels[4]];
				$final[$row['library_id']][$labels[5]] = $row[$labels[5]];
				$final[$row['library_id']][$labels[6]] = $row[$labels[6]];
				$final[$row['library_id']][$labels[7]] = $row[$labels[7]];
			}
			echo "$library_id" . ': getPeriodDownloads' . "\n";
	 	}
		return $final;
	}

	public function createReport($file_name, $labels, $final) {
		if (file_exists($file_name))  {
			unlink($file_name);
		}
		$report = fopen($file_name, 'w') or die("Can't open file");
		$header = 'customer_id,library_name,cte,' . $labels[0] . ',' . $labels[1] . ',' . $labels[2] . ',' . $labels[3] . ',' . $labels[4] . ',' . $labels[5] . ',' . $labels[6] . ',' . $labels[7] . "\n";
		fwrite($report, $header);

		foreach ($final as $key => $value) {
			$string = $value['customer_id'] . ',' . $value['library_name'] . ',' . $value['cte'] . ',' . $value[$labels[0]] . ',' . $value[$labels[1]] . ',' . $value[$labels[2]] . ',' . $value[$labels[3]] . ',' . $value[$labels[4]] . ',' . $value[$labels[5]] . ',' . $value[$labels[6]] . ',' . $value[$labels[7]] . "\n";
			fwrite($report, $string);
		}
		fclose($report);
	}

} // end salesforce_functions class

function sendMail($file_path, $file_name) {
	$to = "ralphk@libraryideas.com";
	$from = "no-reply@freegalmusic.com";
	$bcc = "ralph_kelley@yahoo.com";
	$subject ='Freegalmusic.com: Library streaming report (' . date('ymd') . ')';
	$message =
			"Greetings,<br/><br/>
			Attached is a report that contains the total number of songs streamed by each library during their contract period. This report also contains the total number of songs streamed for each of the previous four months and each of the previous four weeks.<br/><br/>
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

function freegalMusicStreams() {
	// $SalesforceReports = new salesfore_reports('127.0.0.1', 'freegal', 'root', 'pelebertix');
	$SalesforceReports = new salesfore_reports('192.168.100.114', 'freegal', 'freegal_prod', '}e47^B1EO9hD');
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
