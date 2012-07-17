<?php
    error_reporting(E_ALL);
    $memcache = new Memcache;
    $memcache->addServer('10.176.4.199', 11211);
//    $memcache->addServer('10.181.59.64', 11211);
	memcache_delete($memcache, "app_prod_featured");
	memcache_delete($memcache, "app_prod_newartists");
	memcache_delete($memcache, "app_prod_artists");	
?>