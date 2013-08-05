<?php
    error_reporting(E_ALL);
	set_time_limit(0);
	include 'functions.php';
        $memcache = new Memcache;
	//$memcache->addServer('10.176.4.199', 11211);
	$url = "http://www.freegaldev.com/cache/cacheGenre";
	print $url;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);echo $result;
	curl_close($ch);exit;	
?>