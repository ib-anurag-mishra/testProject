<?php
/**
File Name : dbconnect.php
File Description : This file is used to establish connection with the data base
@author : m68interactive 
**/
//$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
//mysql_select_db(DBNAME, $conn);

//$conn = mysql_connect("192.168.2.178","infobeans","infobeans");
//mysql_select_db("fmusic", $conn);

$conn = mysql_connect("192.168.100.115","freegal_prod","}e47^B1EO9hD");
mysql_select_db("freegal", $conn);

?>
