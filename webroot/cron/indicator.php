<?php
    error_reporting(E_ALL);
	set_time_limit(0);
	include 'functions.php';
	$sql = "SELECT library_name FROM libraries WHERE id = 1";
	$result = mysql_query($sql);
	$data = array();
	while ($row = mysql_fetch_assoc($result)) {
		$data[] = $row;
	}
	if(!empty($data)){
		mail('jiturn1@gmail.com','Server meltdown','lookslike server is down :(');
	}
	else{
		echo "cool dude";
	}
	print_r($data);exit;
?>