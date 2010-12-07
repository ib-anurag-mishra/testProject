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
    
    function paginate ($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) { 
        if(isset($extra['cache']) &&  $extra['cache'] == 'yes'){
          $args = func_get_args();
          $uniqueCacheId = '';
          foreach ($args as $arg) {
                  $uniqueCacheId .= serialize($arg);
          }
          if (!empty($extra['contain'])) {
                  $contain = $extra['contain']; 
          }
          $uniqueCacheId = md5($uniqueCacheId);
          $pagination = Cache::read('pagination-'.$this->alias.'-'.$uniqueCacheId, 'paginate_cache');
          if (empty($pagination)) {
				  if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes'){
						$sphinx = array('matchMode' => SPH_MATCH_EXTENDED);
						$pagination = $this->find('all', array('search' =>  $extra['sphinxcheck'], 'limit' => 20, 'sphinx' => $sphinx), compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
				  } else {
						$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
				 }
                  Cache::write('pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination, 'paginate_cache');
          }
        }
        else{
          $pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));            
        }
        return $pagination;
    }

    function paginateCount ($conditions = null, $recursive = 0, $extra = array()) {
        $args = func_get_args();
        $uniqueCacheId = '';
        foreach ($args as $arg) {
                $uniqueCacheId .= serialize($arg);
        }
        $uniqueCacheId = md5($uniqueCacheId);
        if (!empty($extra['contain'])) {
                $contain = $extra['contain'];	
        }
    
        $paginationcount = Cache::read('paginationcount-'.$this->alias.'-'.$uniqueCacheId, 'paginate_cache');
        if (empty($paginationcount)) {
                $group = "";
                foreach($conditions as $k => $v){
                    if($v == "1 = 1 GROUP BY Album.ProdID"){
                        //$fields = array('fields' => 'ProdID');
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }
                    if($v == "1 = 1 GROUP BY Song.ProdID"){
                        //$fields = array('fields' => 'ProdID');
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }
                    if($v == "1 = 1 GROUP BY Song.ArtistText"){
                        //$fields = array('fields' => 'ProdID');
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }					

				}
                if($group != "yes"){
                    $paginationcount = $this->find('count', compact('conditions', 'contain', 'recursive'));
                }
                Cache::write('paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount, 'paginate_cache');
        }
        return $paginationcount;
    }
    function save($data = null, $validate = true, $fieldList = array()) {
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::save($data, $validate, $fieldList);
        $this->useDbConfig = $oldDb;
        return $return;
    }
	
	function saveAll($data = null, $options = array()){
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::saveAll($data , $options);
        $this->useDbConfig = $oldDb;
        return $return;	
	}
	
	function delete($id = null, $cascade = true) {	
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::delete($id, $cascade);
        $this->useDbConfig = $oldDb;
        return $return;
	}
	
	function deleteAll($conditions, $cascade = true, $callbacks = false) {
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::deleteAll($conditions, $cascade , $callbacks);
        $this->useDbConfig = $oldDb;
        return $return;
	}
	function saveField($name, $value, $validate = false) {
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::saveField($name, $value, $validate);
        $this->useDbConfig = $oldDb;
        return $return;
	}	
	function create($data = array(), $filterKey = false) {
        $oldDb = $this->useDbConfig;
        $this->setDataSource('master');
        $return = parent::create($data, $filterKey);
        $this->useDbConfig = $oldDb;
        return $return;
	}
}
?>