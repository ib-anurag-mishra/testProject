<?php

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASSWORD', 'sN3ekrs!82');
// define('DB1', 'ioda');
// define('DB2', 'freegal');
define('DB_HOST', '10.181.56.177');
define('DB_USER', 'ioda_test');
define('DB_PASSWORD', '?26N<M4bZ67w');
define('DB1', 'ioda_test');
define('DB2', 'freegal');
// error_reporting(1);

//define('ROOTPATH','/mnt/music/musicusers/iodamusic/libraryideas_20110817_test/');
define('ROOTPATH','/home/iodamusic/libraryideas_20110817_test5/');
define('IMPORTLOGS','/home/parser/ioda/ioda_logs/');
define('REPORT_LOGS','/home/parser/ioda/report/');
define('SERVER_PATH','/home/parser/ioda_parser/');


define('CDNPATH','ioda_test');

define('SFTP_HOST','libraryideas.ingest.cdn.level3.net');
define('SFTP_PORT',22);
define('SFTP_USER','libraryideas');
define('SFTP_PASS','rwBYMZZC');
define('SLEEP_TIME',6); //set to 1200 on server for 20 min sleep
define('LOG_TEMP_PATH','/opt/ioda/temp/');
define('HOST_URL','http://music.libraryideas.com');
define('TO','rob@m68interactive.com');
define('FROM','no-reply@freegalmusic.com');
define('SUBJECT','FreegalMusic.com: IODA File Processing Information');
define('HEADERS','From:'. FROM);
define('MAILSERVER','localhost');

$ioda = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD ,true) or die("Could not connect to Database");
$freegal = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD,true) or die("Could not connect to Database");

if(!mysql_select_db(DB1, $ioda))
	die("Could not connect to Database");
	
if(!mysql_select_db(DB2, $freegal))
	die("Could not connect to Database");

?>
