<?php

// includeds Streaming class file
require_once('streaming.php');

//connects to db1, intinialize object
$Streaming = new Streaming();

//Instance
$Streaming->Instance   = 1;
//LIMIT of SELECT query (Songs,Files)
$Streaming->ChunkSize  = 5;
//1st value in LIMIT in SELECT query (Songs,Files) : Start point - ProdID
$Streaming->LimitIndex = 0;
//2nd value in LIMIT in SELECT query (Songs,Files) : Totals rows count
$Streaming->LimitCount = 10;
//if script will run in live or test environment
$Streaming->LIVE       = 1;

//get start
$Streaming->getAllSongsData();


exit('Completed.');




?>
