<?php
    error_reporting(E_ALL);
	set_time_limit(0);
	include 'functions.php';
    $memcache = new Memcache;
//    $memcache->connect('10.181.59.94', 11211) or die ("Could not connect to memcache server");
    $memcache->addServer('10.181.59.94', 11211);
    $memcache->addServer('10.181.59.64', 11211);
	for($i = 97;$i < 123;$i++){
		$alphabet = chr($i);
		$sql = "SELECT `Song`.`ArtistText`, `Song`.`DownloadStatus`, `Country`.`Territory` FROM `Songs` AS `Song` LEFT JOIN `countries` AS `Country` ON (`Country`.`ProdID` = `Song`.`ProdID`)  WHERE ((`ArtistText` LIKE '".$alphabet."%') AND (`Country`.`Territory` = 'US') AND (`DownloadStatus` = '1') AND (`Song`.`Sample_FileID` != ''))  GROUP BY `Song`.`ArtistText`  ORDER BY `Song`.`ArtistText` ASC";
		$result = mysql_query($sql);
		$count = 0;
		$data = array();
		while ($row = mysql_fetch_assoc($result)) {
			$data[$count]['Song']['ArtistText'] = $row['ArtistText'];
			$data[$count]['Song']['DownloadStatus'] = $row['DownloadStatus'];
			$data[$count]['Country']['Territory'] = $row['Territory'];
			$count++;		
		}
		echo $alphabet."2".memcache_delete($memcache,"app_prod_artist".$alphabet."_u_s")."<BR>";
		echo $alphabet."2".memcache_set($memcache,"app_prod_artist".$alphabet."_u_s",$data,false,86400)."<BR>";		
	}
	$url = "http://10.181.59.64/cache/cacheGenre/us";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);echo $result;
	curl_close($ch);exit;	
?>