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


set_time_limit(0);
$freegalConn = mysql_connect("192.168.100.114","freegal_prod","}e47^B1EO9hD");
mysql_select_db("freegal", $freegalConn); 
$query = "select patron_id,library_id,created from downloads where patron_id not in (select distinct patronid from currentpatrons) group by patron_id";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$list = array();
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $patron_id = $line['patron_id'];
    $library_id = $line['library_id'];
    $created = $line['created'];
    $sql = "insert into currentpatrons (libid,patronid,created,modified) values($library_id,"."'".$patron_id."'"    .","."'".$created."',"."'".$created."'".")";
    $result1 = mysql_query($sql) or die('Query failed: ' . mysql_error());
    $list[] = mysql_insert_id();
}
mysql_close($freegalConn);
echo "count of effected rows are".count($list);
?>