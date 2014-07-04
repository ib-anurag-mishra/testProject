<?php
set_time_limit(0);
include 'functions.php';

//establishing connection with
$link = mysql_connect(DBHOST,DBUSER,DBPASS);
$db1 = mysql_select_db(DBNAME);
$sql = "SELECT id,library_name,library_contract_start_date,library_contract_end_date,library_available_downloads FROM libraries WHERE library_unlimited != '1' AND library_status != 'active'";
$result = mysql_query($sql);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$credential[] = $line; 
}
$i = 1;
$j = 1;
$m = 1;
foreach($credential as $k=>$v){
	$today = date("Y-m-d h:i:s");
	$date = strtotime($v['library_contract_end_date']) - strtotime($today);
	if((($date/86400) < 30) && $date > 0){
		$still .= $i."- ".$v['library_name']. " contract ends on date ".$v['library_contract_end_date']."\n\n";
		$i++;
	}	
	if((($date/86400) < 30) && $date < 0){
		$already .= $j."- ".$v['library_name']. " contract has ended on date ".$v['library_contract_end_date']."\n\n";
		$j++;
	}
	$sql = "SELECT SUM(purchased_tracks) as Total FROM library_purchases WHERE library_id=".$v['id'];
	$result = mysql_fetch_array(mysql_query($sql));
	$record = number_format((($result['Total']/100)*85),0);
	if($v['library_available_downloads'] < $record){
		$download .= $m."- ".$v['library_name']. " has only ".$v['library_available_downloads']." downloads left\n\n";
		$m++;
	}
}
if($still != ''){
	$str .= "Libraries whose Contracts still remaining\n\n".$still."\n\n";
	print "Libraries whose Contracts still remaining".$still."\n\n";
}else{
	$str .= "No Library contracts expiring in the next 30 days\n\n\n\n";
	print "No Library contracts expiring in the next 30 days\n\n\n\n";
}
if($already != ''){
	$str .= "Libraries whose Contracts already finished\n\n".$already."\n\n";
	print "Libraries whose Contracts already finished\n\n".$already."\n\n";
}
if($download != ''){
	$str .=  "Libraries reached their 85% of downloads\n\n".$download;
	print "Libraries reached their 85% of downloads\n\n".$download;
}
echo mail("tech@libraryideas.com,briand@libraryideas.com,jimp@libraryideas.com","Library Status",$str,'From:no-reply@freegalmusic.com');exit;
?>
