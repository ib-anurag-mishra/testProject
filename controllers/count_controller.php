<?php
/*
 File Name : count_controller.php
 File Description : Controller page for writting the memcache key.
 Author : m68interactive
 */
class CountController extends AppController {
    var $name = 'Count';
    var $autoLayout = false;
    var $uses = array();
/*	function admin_index(){
		$memcache = new Memcache;
		$memcache->connect('127.0.0.1', 11211) or $this->_stop("Could not connect to memcache server");
		$list = array();
		$allSlabs = $memcache->getExtendedStats('slabs');
		$items = $memcache->getExtendedStats('items');
		foreach($allSlabs as $server => $slabs) {
		foreach($slabs AS $slabId => $slabMeta) {
		   $cdump = $memcache->getExtendedStats('cachedump',(int)$slabId);
			foreach($cdump AS $keys => $arrVal) {
				foreach($arrVal AS $k => $v) {                   
				   // echo $k .'<br>';
			  $arr[$k] = $k;
				}
		   }
		}
		}
		$arrAlias = array();
		$arrAlias = $arr;
		foreach($arrAlias as $k => $v){
		if(strstr($k, 'app_prod_login_')){
			$test[$k] = $v; 
		}
		}
		foreach($test as $k1 => $v1){
		if(strstr($k1, '_expires')){
			$test_expires[$k1] = $v1; 
		}
		}
		$new=array_diff($test,$test_expires);
		foreach($new as $key=>$value){
		$text=explode("_",$key);
		$x = Cache::read("login_".$text[3]);
		$date = time();
		$modifiedTime = $x[0];
		if(($date-$modifiedTime) > 60){
			//do nothing
		}else{
			$new1[$key] = $key;
		}
		}
		print  "<title>Patron Count Page</title>";
		print "No of users curently online: ".count($new1);exit;
	}*/
}	
?>