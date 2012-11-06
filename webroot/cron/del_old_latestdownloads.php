<?php
/**
File Name : del_old_latestdownloads.php
File Description : Cron file to delete one month old data from latestdownloads table
**/

include 'config.php';
include 'dbconnect.php';

$logFile = "delete_latest_download.txt";

$logFileWriter=fopen($logFile,'w') or die("Can't Open the file!");

$deleteQuery = "DELETE FROM latest_downloads WHERE created < TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 6 WEEK))";

if(!mysql_query($deleteQuery)){
   fwrite($logFileWriter, date('Y-m-d h:i:s', time())." : Query for delete not executed : ".$deleteQuery); 
} else {
   fwrite($logFileWriter, date('Y-m-d h:i:s', time())." : Query for delete executed succesfully : ".$deleteQuery);  
}

fclose($logFileWriter);

echo "Script execution completed.....";
exit();

?>