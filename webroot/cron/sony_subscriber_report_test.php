<?php
ini_set('error_reporting', E_ALL);
set_time_limit(0);
date_default_timezone_set("America/New_York");

class database {
	
	private $conn;
	private $dsn;
	private $user;
	private $pass;

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

	public function freegalMusicStreaming($date) {
		$fullstart = date('Y-m-01', strtotime($date));
		$fullend =   date('Y-m-t',  strtotime($date));
		$sql = "SELECT DISTINCT libraries.library_territory FROM libraries ORDER BY libraries.library_territory";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = array();
		foreach($result as $row){
			$data[$row['library_territory']] = array(
				'territory' => $row['library_territory'],
				'total' => '0',
				'new' => '0',
				'existing' => '0',
				'cancellations' => '0',
			);
		}
		$sql = <<<EOD
			SELECT 
				l.library_territory,
				count(DISTINCT l.id) 'Total'
			FROM
				contract_library_streaming_purchases clsp
			LEFT JOIN libraries l ON l.id = clsp.library_id
			WHERE '$fullstart' BETWEEN clsp.library_contract_start_date AND clsp.library_contract_end_date
			OR    '$fullend' BETWEEN clsp.library_contract_start_date AND clsp.library_contract_end_date
			GROUP BY l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['total'] = $row['Total'];
		}
		$sql = <<<EOD
			SELECT
				l.library_territory,
				count(DISTINCT clsp.library_id) 'Renewals'
			FROM
				contract_library_streaming_purchases clsp
			JOIN libraries l ON clsp.library_id = l.id
			WHERE clsp.library_contract_start_date = (
				SELECT max(contract_library_streaming_purchases.library_contract_start_date)
				FROM contract_library_streaming_purchases
				WHERE contract_library_streaming_purchases.library_id = l.id
			)
			AND clsp.library_contract_start_date LIKE '$date%'
			AND (
				SELECT max(contract_library_streaming_purchases.library_contract_end_date)
				FROM contract_library_streaming_purchases
				WHERE contract_library_streaming_purchases.library_contract_end_date != (
					SELECT max(contract_library_streaming_purchases.library_contract_end_date)
					FROM contract_library_streaming_purchases
					WHERE contract_library_streaming_purchases.library_id = l.id
				)
				AND contract_library_streaming_purchases.library_id = l.id
			) < clsp.library_contract_start_date
			GROUP BY
				l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['existing'] = $row['Renewals'];
		}
		$sql = <<<EOD
			SELECT
				l.library_territory,
				count(DISTINCT library_id) 'New'
			FROM
				contract_library_streaming_purchases clsp
			JOIN libraries l ON clsp.library_id = l.id
			WHERE
				clsp.library_contract_start_date = (
					SELECT min(contract_library_streaming_purchases.library_contract_start_date) 
					FROM contract_library_streaming_purchases 
					WHERE contract_library_streaming_purchases.library_id = l.id
				) 
			AND clsp.library_contract_start_date LIKE '$date%'
			GROUP BY
				l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['new'] = $row['New'];
		}
		$sql = <<<EOD
			SELECT
				l.library_territory,
				count(DISTINCT library_id) 'Cancelled'
			FROM
				contract_library_streaming_purchases clsp
			JOIN libraries l ON clsp.library_id = l.id
			WHERE
				clsp.library_contract_end_date = (
					SELECT max(contract_library_streaming_purchases.library_contract_end_date)
					FROM contract_library_streaming_purchases
					WHERE contract_library_streaming_purchases.library_id = l.id
				)
				AND clsp.library_contract_end_date LIKE '$date%'
			GROUP BY
				l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['cancellations'] = $row['Cancelled'];
		}

		// print_r($data);
		// echo 'freegalMusicStreaming() successful' . "\n";
		return $data;
	}
	
	public function freegalMusicSubscriptions($date) {
		$fullstart = date('Y-m-01', strtotime($date));
		$fullend =   date('Y-m-t',  strtotime($date));
		$sql = "SELECT DISTINCT libraries.library_territory FROM libraries ORDER BY libraries.library_territory";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = array();
		foreach($result as $row){
			$data[$row['library_territory']] = array(
				'territory' => $row['library_territory'],
				'total' => '0',
				'new' => '0',
				'existing' => '0',
				'cancellations' => '0',
			);
		}
		$sql = <<<EOD
			SELECT
				l.library_territory,
				count(DISTINCT library_id) 'Total'
			FROM
				contract_library_purchases clp
			INNER JOIN libraries l ON l.id = clp.library_id
			WHERE
				 clp.library_unlimited = '1' AND '$fullstart' BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date
			OR   clp.library_unlimited = '1' AND '$fullend' BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date
			GROUP BY l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['total'] = $row['Total'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'Renewals'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_start_date LIKE '$date%'
			AND clp.library_unlimited = '1'
			AND clp.id = (
				SELECT
					max(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			AND clp.id > (
				SELECT
					min(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['existing'] = $row['Renewals'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'New'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_start_date LIKE '$date%'
			AND clp.library_unlimited = '1'
			AND clp.id = (
				SELECT
					min(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['new'] = $row['New'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'Cancelled'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_end_date LIKE '$date%'
			AND clp.library_unlimited = '1'
			AND clp.id = (
				SELECT
					max(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['cancellations'] = $row['Cancelled'];
		}

		// print_r($data);
		// echo 'freegalMusicSubscriptions() successful' . "\n";
		return $data;
	}
	
	public function freegalMusicAlc($date) {
		$fullstart = date('Y-m-01', strtotime($date));
		$fullend =   date('Y-m-t',  strtotime($date));
		$sql = "SELECT DISTINCT libraries.library_territory FROM libraries ORDER BY libraries.library_territory";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = array();
		foreach($result as $row){
			$data[$row['library_territory']] = array(
				'territory' => $row['library_territory'],
				'total' => '0',
				'new' => '0',
				'existing' => '0',
				'cancellations' => '0',
			);
		}
		$sql = <<<EOD
			SELECT l.library_territory, count(l.id) AS 'Total'
			FROM
				libraries AS l
			LEFT JOIN 
			    (
			        SELECT DISTINCT(library_id), library_contract_end_date, library_contract_start_date, library_unlimited
			        FROM contract_library_purchases
					WHERE library_unlimited = '0'
					ORDER BY library_unlimited ASC, library_contract_start_date DESC
			    ) AS clp
			ON l.id = clp.library_id
			WHERE clp.library_unlimited = '0' AND '$fullstart' BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date
				OR clp.library_unlimited = '0' AND '$fullend' BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date
			GROUP BY l.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['total'] = $row['Total'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'New'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_start_date LIKE '$date%'
			AND clp.library_unlimited = '0'
			AND clp.id = (
				SELECT
					min(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['existing'] = $row['Renewals'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'Renewals'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_start_date LIKE '$date%'
			AND clp.library_unlimited = '0'
			AND clp.id = (
				SELECT
					max(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			AND clp.id > (
				SELECT
					min(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['new'] = $row['New'];
		}
		$sql = <<<EOD
			SELECT
				library_territory,
				count(DISTINCT library_id) 'Cancelled'
			FROM
				contract_library_purchases clp
			JOIN libraries l ON clp.library_id = l.id
			WHERE
				clp.library_contract_end_date LIKE '$date%'
			AND clp.library_unlimited = '0'
			AND clp.id = (
				SELECT
					max(clp2.id)
				FROM
					contract_library_purchases clp2
				WHERE
					clp2.library_id = l.id
			)
			GROUP BY
				library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$data[$row['library_territory']]['cancellations'] = $row['Cancelled'];
		}

		// print_r($data);
		// echo 'freegalMusicAlc() successful' . "\n";
		return $data;
	}
	
	public function freegalMoviesStreaming($date) {
		$fullstart = date('Y-m-01', strtotime($date));
		$fullend =   date('Y-m-t',  strtotime($date));
		$sql = <<<EOD
			SELECT
				libraries.library_territory,
				sum(
					CASE
					WHEN "$fullstart" BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date OR "$fullend" BETWEEN clp.library_contract_start_date AND clp.library_contract_end_date THEN
						1
					ELSE
						0
					END
				) 'Total',
				sum(
					CASE
					WHEN clp.library_contract_start_date = (SELECT min(contract_library_purchases.library_contract_start_date) FROM contract_library_purchases WHERE contract_library_purchases.library_id = libraries.id) AND clp.library_contract_start_date LIKE "$date%" THEN
						1
					ELSE
						0
					END
				) 'New',
				sum(
					CASE
					WHEN clp.library_contract_start_date = (
						SELECT max(contract_library_purchases.library_contract_start_date)
						FROM contract_library_purchases
						WHERE contract_library_purchases.library_id = libraries.id
					)
					AND clp.library_contract_start_date LIKE "$date%"
					AND (
						SELECT max(contract_library_purchases.library_contract_end_date)
						FROM contract_library_purchases
						WHERE contract_library_purchases.library_contract_end_date != (
							SELECT max(contract_library_purchases.library_contract_end_date)
							FROM contract_library_purchases
							WHERE contract_library_purchases.library_id = libraries.id
						)
						AND contract_library_purchases.library_id = libraries.id
					) < clp.library_contract_start_date THEN
						1
					ELSE
						0
					END
				) 'Renewals',
				sum(
					CASE
					WHEN clp.library_contract_end_date = (
						SELECT max(contract_library_purchases.library_contract_end_date)
						FROM contract_library_purchases
						WHERE contract_library_purchases.library_id = libraries.id
					)
					AND clp.library_contract_end_date LIKE "$date%" THEN
						1
					ELSE
						0
					END
				) 'Cancelled'
			FROM
				libraries
			LEFT JOIN contract_library_purchases AS clp ON libraries.id = clp.library_id
			WHERE
				libraries.customer_id != 0
			GROUP BY libraries.library_territory
			ORDER BY libraries.library_territory
EOD;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = array();
		foreach($result as $row){
			$data[] = array(
				'territory' => $row['library_territory'],
				'total' => $row['Total'],
				'new' => $row['New'],
				'existing' => $row['Renewals'],
				'cancellations' => $row['Cancelled'],
			);
		}
		// echo 'freegalMoviesStreaming() successful' . "\n";
		return $data;
	}

} // end class

function sendMail($file_path, $file_name, $previousMonth) {
	$to = "ralphk@libraryideas.com";
	$from = "no-reply@freegalmusic.com";
	$bcc = "ralph_kelley@yahoo.com";
	$subject ='Library subscriptions report (' . $previousMonth . ')';
	$message =
			"Greetings,<br/><br/>
			Attached is a report that contains the total number of library subscriptions for this month.<br/><br/>
			Thanks,<br/>
			The Freegal Team<br/><br/>";

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
		echo "Mail sent to $to!$file_name\n"; 
	} else { 
		echo "Mail could not be sent!$file_name\n"; 
	}
}

function sonyReport($previousMonth) {
	$dbconfig = array('host' => '192.168.100.114', 'user' => 'freegal_prod', 'pass' => '}e47^B1EO9hD');
	//$dbconfig = array('host' => 'localhost;port=3306', 'user' => 'root', 'pass' => '');
	//$previousMonth = date("Y-m", strtotime("previous month"));
	//$previousMonth = '2014-06';//**********This is for testing**********//
	$sections = array(
		'Freegal Music Streaming' => '0',
		'Freegal Music Subscription' => '1',
		'Freegal Music ALC' => '2',
		'Freegal Movies Streaming' => '3'
	);
	// Create the workbook
	$file_path = '../uploads/';
	$file_name = 'sony_totals_' . date('Ym' , strtotime($previousMonth)) . '.xls';
	chdir('phpxls');
	require_once 'Writer.php';
	chdir('..');
	$workbook = new Spreadsheet_Excel_Writer($file_path . $file_name);

	// Basic formatting
	$fmt_cal =& $workbook->addFormat(array('size' => 10, "align" => 'center'));
	//$fmt_cal->setFgColor('White');
	$fmt_cal->setTextWrap();
	$fmt_cal->setVAlign('vcenter');

	// Format header cells
	$header_tag =& $workbook->addFormat(array('size' => 10));
	$header_tag->setAlign('center');
	$header_tag->setBold();

	// Loops through each section
	foreach ($sections as $section => $i) {

		// Create the worksheet and its formatting
		$wbname = $section;
		$worksheet_ms =& $workbook->addWorksheet($wbname); // Adds a worksheet to the current workbook

		// Sets the width of columns
		$worksheet_ms->setColumn(0, 0, 10);
		$worksheet_ms->setColumn(0, 1, 18);
		$worksheet_ms->setColumn(0, 2, 18);
		$worksheet_ms->setColumn(0, 3, 18);
		$worksheet_ms->setColumn(0, 4, 18);

		// First row - headings
		$worksheet_ms->writeString(0, 0, "Territory", $header_tag);
		$worksheet_ms->writeString(0, 1, "Total Libraries", $header_tag);
		$worksheet_ms->writeString(0, 2, "New Libraries", $header_tag );
		$worksheet_ms->writeString(0, 3, "Renewed Libraries", $header_tag );
		$worksheet_ms->writeString(0, 4, "Cancelled Libraries", $header_tag);

		
		switch ($i) {
			case '0':
				$conn = new Database($dbconfig['host'], 'freegal', $dbconfig['user'], $dbconfig['pass']);
				$data = $conn->freegalMusicStreaming($previousMonth);
				break;
			case '1':
				$conn = new Database($dbconfig['host'], 'freegal', $dbconfig['user'], $dbconfig['pass']);
				$data = $conn->freegalMusicSubscriptions($previousMonth);
				break;
			case '2':
				$conn = new Database($dbconfig['host'], 'freegal', $dbconfig['user'], $dbconfig['pass']);
				$data = $conn->freegalMusicAlc($previousMonth);
				break;
			case '3':
				$conn = new Database($dbconfig['host'], 'fmovies', $dbconfig['user'], $dbconfig['pass']);
				$data = $conn->freegalMoviesStreaming($previousMonth);
				break;
			default:
				break;
		}
		$conn = null;
		
		if (!empty($data)) {
			$row_num = 1;
			foreach ($data as $key => $value) {
				$worksheet_ms->writeString($row_num, 0, $value['territory'], $fmt_cal);
				$worksheet_ms->writeString($row_num, 1, $value['total'], $fmt_cal);
				$worksheet_ms->writeString($row_num, 2, $value['new'], $fmt_cal);
				$worksheet_ms->writeString($row_num, 3, $value['existing'], $fmt_cal);
				$worksheet_ms->writeString($row_num, 4, $value['cancellations'], $fmt_cal);
				$row_num++;
			}
		} // end if
	} // end foreach

	$workbook->close();
	sendMail($file_path, $file_name, $previousMonth);
}

//sonyReport();
$backReports = array(
	'2010-10',
	'2010-11',
	'2010-12',
	'2011-01',
	'2011-02',
	'2011-03',
	'2011-04',
	'2011-05',
	'2011-06',
	'2011-07',
	'2011-08',
	'2011-09',
	'2011-10',
	'2011-11',
	'2011-12',
	'2012-01',
	'2012-02',
	'2012-03',
	'2012-04',
	'2012-05',
	'2012-06',
	'2012-07',
	'2012-08',
	'2012-09',
	'2012-10',
	'2012-11',
	'2012-12',
	'2013-01',
	'2013-02',
	'2013-03',
	'2013-04',
	'2013-05',
	'2013-06',
	'2013-07',
	'2013-08',
	'2013-09',
	'2013-10',
	'2013-11',
	'2013-12',
	'2014-01',
	'2014-02',
	'2014-03',
	'2014-04',
	'2014-05',
	'2014-06',
	'2014-07',
	'2014-08'
);
//$backReports = array('2014-06','2014-07');
foreach ($backReports as $key => $previousMonth) {
	sonyReport($previousMonth);

}





