<?php
$ch=curl_init();
// tell curl target url
curl_setopt($ch, CURLOPT_URL, "http://192.168.2.178:8080/solr/freegalmusic/dataimport?command=full-import&clean=false");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// make the connection
$result = curl_exec($ch);
echo $result;
?>