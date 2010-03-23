<?php
class AppModel extends Model {
 
    function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
 $doQuery = true;
     // check if we want the cache
     if (!empty($fields['cache'])) {
  $cacheConfig = null;
  // check if we have specified a custom config, e.g. different expiry time
  if (!empty($fields['cacheConfig']))
      $cacheConfig = $fields['cacheConfig'];
 
  $cacheName = $this->name . '-' . $fields['cache'];
     
      // if so, check if the cache exists
      if (($data = Cache::read($cacheName, $cacheConfig)) === false) {
       $data = parent::find($conditions, $fields, $order, $recursive);
       Cache::write($cacheName, $data, $cacheConfig);
      }
      $doQuery = false;
     }
 if ($doQuery)
     $data = parent::find($conditions, $fields, $order, $recursive);
 return $data;
    }
 
}
?>