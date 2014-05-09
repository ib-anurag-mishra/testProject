<?php
    error_reporting(E_ALL);
    set_time_limit(0);
    include 'functions.php';
    $memcache = new Memcache;
    $memcache->addServer('10.209.137.72', 11211); 
    $script_name    =   array("cache/runGenreCache", "cron/combine_genre.php");
    
    for($cnt=0; $cnt<count($script_name); $cnt++)
    {
        $url = "http://10.181.60.3/".$script_name[$cnt];
        
        echo "<br>".$url;
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);
    }
    
    exit;    
?>
