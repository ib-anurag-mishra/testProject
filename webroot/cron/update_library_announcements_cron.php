<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
 * This cron script is intended to run every midnight to update the libraries announcements status
 * @package update_library_announcements_cron
 **/

$freegalConn = mysql_connect("192.168.100.52","freegal_prod","}e47^B1EO9hD");
mysql_select_db("fmovies", $freegalConn);
$query = "SELECT customer_id FROM libraries where library_status = 'active' and customer_id is not null and customer_id != 0 and customer_id <> ''";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $freegalIds[] = $line['customer_id'];
}

mysql_select_db("freegal", $freegalConn);
$sql = "UPDATE libraries SET library_announcement = 1 WHERE customer_id IN (".implode(',',$freegalIds).") and id NOT IN (1,2)";
$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
$sql = "UPDATE libraries SET library_announcement = 0 WHERE customer_id NOT IN (".implode(',',$freegalIds).") and id NOT IN (1,2)";
$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
echo date("Y-m-d H:i:s")." - Library satus updated successfully for Library ID's ".implode(',',$freegalIds);
mysql_close($freegalConn);

?>
