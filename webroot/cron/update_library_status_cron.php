<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 January, 2010
 * This cron script is intended to run every midnight to update the libraries Activate or Deactivate status based on their
 * Contract Start Date by adding One Year.
 * @package update_library_status_cron
 **/
include 'config.php';
include 'dbconnect.php';

$query = 'SELECT * FROM `libraries`';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$currDate = strtotime(date("Y-m-d"));
	$contractDate = strtotime($line['library_contract_start_date']);
	if($contractDate > $currDate) {
		$status = "inactive";
	}
	elseif((date('Y', $currDate)-date('Y', $contractDate)) > 0) {
		$status = "inactive";
	}
	else {
		$status = "active";
	}
	$sql = "UPDATE libraries SET library_status='$status' WHERE id=".$line['id'];
	$result2 = mysql_query($sql) or die('Query failed: ' . mysql_error());
	echo "Library satus updated successfully for Library ID ".$line['id']." to $status !!\n";
}