<?php
    error_reporting(E_ALL);
    set_time_limit(0);
    include 'functions.php';
    $memcache = new Memcache;
    $memcache->addServer('10.178.4.51', 11211);
    $memcache->addServer('10.208.2.226', 11211);
   // $url = "http://198.101.168.184/cache/runCache";
    $url = "http://www.freegalmusic.com/cache/runCache";
    print $url;
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);echo $result;
    curl_close($ch);


    $CacheBackupUrl = "http://www.freegalmusic.com/Resetcache/genrateXML";
    print $CacheBackupUrl;
    $ch2=curl_init();
    curl_setopt($ch2, CURLOPT_URL, $CacheBackupUrl);
    curl_setopt($ch2, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch2);echo $result;
    curl_close($ch2);exit;
?>
