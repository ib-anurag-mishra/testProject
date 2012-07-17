<?php
/**
File Name : dbconnect.php
File Description : This file is used to establish connection with the data base
@author : m68interactive 
**/
$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
mysql_select_db(DBNAME, $conn);
?>
