<?php
date_default_timezone_set('America/Los_Angeles');
set_time_limit(0);
ini_set('memory_limit','1024M');

error_reporting(E_ALL);
ini_set('display_errors', '1');
// includeds Streaming class file
require_once('streaming.php');

//connects to db1, intinialize object
$Streaming = new Streaming();

//Instance
$Streaming->Instance   = 2;
//LIMIT of SELECT query (Songs,Files)
$Streaming->ChunkSize  = 1000;
//1st value in LIMIT in SELECT query (Songs,Files) : Start point - ProdID
$Streaming->LimitIndex = 100000;
//2nd value in LIMIT in SELECT query (Songs,Files) : Totals rows count
$Streaming->LimitCount = 500000;
//if script will run in live or test environment
$Streaming->LIVE       = 0;

$Streaming->EnableBigLogs  = 0;  //This one logs all queries
$Streaming->EnableShortLogs  = 1; //This one logs only true false value


//get start
$Streaming->openLogsFiles();
$Streaming->getAllSongsData();
$Streaming->closeLogsFiles();


exit('Completed.');




?>
