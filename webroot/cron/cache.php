<?php
    error_reporting(E_ALL);
	set_time_limit(0);
	include 'functions.php';
    $memcache = new Memcache;
    $memcache->connect('10.181.59.94', 11211) or die ("Could not connect to memcache server");
	//http://173.203.136.99:8888/currentPatrons_test.php - to check for memcache keys
	//http://173.203.136.99:8080/cron/cache.php- to add queries to mamecache
	for($i = 97;$i < 123;$i++){
		$alphabet = chr($i);
		$sql = "SELECT `Song`.`ArtistText`, `Song`.`DownloadStatus`, `Country`.`Territory` FROM `Songs` AS `Song` LEFT JOIN `countries` AS `Country` ON (`Country`.`ProdID` = `Song`.`ProdID`)  WHERE ((`ArtistText` LIKE '".$alphabet."%') AND (`Country`.`Territory` = 'US') AND (`DownloadStatus` = '1') AND (`Song`.`Sample_FileID` != ''))  GROUP BY `Song`.`ArtistText`  ORDER BY `Song`.`ArtistText` ASC";
		$result = mysql_query($sql);
		$count = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$data[$count]['Song']['ArtistText'] = $row['ArtistText'];
			$data[$count]['Song']['DownloadStatus'] = $row['DownloadStatus'];
			$data[$count]['Country']['Territory'] = $row['Territory'];
			$count++;		
		}
		echo $alphabet."2".memcache_delete($memcache,"app_test_artist".$alphabet."_u_s")."<BR>";
		echo $alphabet."2".memcache_set($memcache,"app_test_artist".$alphabet."_u_s",$data,false,14400)."<BR>";		
	}
	for($i = 97;$i < 123;$i++){
		$alphabet = chr($i);
		$sql = "SELECT `Song`.`ArtistText`, `Song`.`DownloadStatus`, `Country`.`Territory` FROM `Songs` AS `Song` LEFT JOIN `countries` AS `Country` ON (`Country`.`ProdID` = `Song`.`ProdID`)  WHERE ((`ArtistText` LIKE '".$alphabet."%') AND (`Country`.`Territory` = 'CA') AND (`DownloadStatus` = '1') AND (`Song`.`Sample_FileID` != ''))  GROUP BY `Song`.`ArtistText`  ORDER BY `Song`.`ArtistText` ASC";
		$result = mysql_query($sql);
		$count = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$data[$count]['Song']['ArtistText'] = $row['ArtistText'];
			$data[$count]['Song']['DownloadStatus'] = $row['DownloadStatus'];
			$data[$count]['Country']['Territory'] = $row['Territory'];
			$count++;		
		}
		echo $alphabet."2".memcache_delete($memcache,"app_test_artist".$alphabet."_c_a")."<BR>";
		echo $alphabet."2".memcache_set($memcache,"app_test_artist".$alphabet."_c_a",$data,false,14400)."<BR>";		
	}
	$url = "http://10.181.60.3/cache/cacheGenre";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);echo $result;
	curl_close($ch);exit;	
?>