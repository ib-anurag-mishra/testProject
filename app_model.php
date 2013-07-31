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
		global $callType;
		$callType = "paginate";
		if(isset($extra['extra'])){
			$pageVal = 6;
		}
		else{
			$pageVal = 20;
		}
		if(isset($extra['webservice'])){
			$pageVal = 10000;
		}
                
                
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
          if(empty($pagination)){
              echo 'emptypage-';
              echo 'pagination-'.$this->alias.'-'.$uniqueCacheId;
          }
                  
          if (empty($pagination)) {
              
                if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
                    
                        if (isset($extra['sphinxsort']) && ($extra['sphinxsort'] != '')) {
                                $field = $extra['sphinxsort'];
                                $expField = explode(".", $field);
                                $sortField = "Sort".$expField[1];
                                if ($extra['sphinxdirection'] == 'asc') {
                                        $modeSphinx = SPH_SORT_ATTR_ASC;
                                        $sphinx = array('matchMode' => SPH_MATCH_EXTENDED2, 'sortMode' => array(SPH_SORT_ATTR_ASC => $sortField));
                                } else {
                                        $modeSphinx = SPH_SORT_ATTR_DESC;
                                        $sphinx = array('matchMode' => SPH_MATCH_EXTENDED2, 'sortMode' => array(SPH_SORT_ATTR_DESC => $sortField));
                                }
                        } else {
                                $sphinx = array('matchMode' => SPH_MATCH_EXTENDED2);
                        }
                              

                            $pagination = $this->find('all', array('search' =>  $extra['sphinxcheck'], 'group' => 'Song.ProdID', 'limit' => $pageVal, 'recursive' => 0, 'sphinx' => $sphinx), compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
                } else {
                   
                            $pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
                            
                            
                              // print_r($pagination);
                }
                
               echo 'pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination, 'paginate_cache';
                  Cache::write('pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination, 'paginate_cache');
                  
          }
        } else {
           
			if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
					if (isset($extra['sphinxsort']) && ($extra['sphinxsort'] != '')) {
						$field = $extra['sphinxsort'];
						$expField = explode(".", $field);
						$sortField = "Sort".$expField[1];
						if ($extra['sphinxdirection'] == 'asc') {
							$modeSphinx = SPH_SORT_ATTR_ASC;
							$sphinx = array('matchMode' => SPH_MATCH_EXTENDED2, 'sortMode' => array(SPH_SORT_ATTR_ASC => $sortField));
						} else {
							$modeSphinx = SPH_SORT_ATTR_DESC;
							$sphinx = array('matchMode' => SPH_MATCH_EXTENDED2, 'sortMode' => array(SPH_SORT_ATTR_DESC => $sortField));
						}
					} else {
						$sphinx = array('matchMode' => SPH_MATCH_EXTENDED2);
					}

					$pagination = $this->find('all', array('search' =>  $extra['sphinxcheck'], 'group' => 'Song.ProdID', 'limit' => $pageVal, 'recursive' => 0, 'sphinx' => $sphinx), compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
			  } else {
					$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
			}
        }
        return $pagination;
    }

    function paginateCount ($conditions = null, $recursive = 0, $extra = array()) {
		global $callType;
		$callType = "paginateCount";
        $args = func_get_args();
        $uniqueCacheId = '';
        foreach ($args as $arg) {
                $uniqueCacheId .= serialize($arg);
        }
        $uniqueCacheId = md5($uniqueCacheId);
        if (!empty($extra['contain'])) {
                $contain = $extra['contain'];
        }

		if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
			$paginationcount = "";
		} else {
			$paginationcount = Cache::read('paginationcount-'.$this->alias.'-'.$uniqueCacheId, 'paginate_cache');
		}
        if (empty($paginationcount)) {
                $group = "";
                foreach($conditions as $k => $v){
                    
                    
                    
                    
                    if($v === "1 = 1 GROUP BY Album.ProdID "){
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }
                    
                    if($v === "1 = 1 GROUP BY Album.ProdID, Album.provider_type"){
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }
                    
                    if($v === "1 = 1 GROUP BY Song.ProdID"){
                        $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                        $paginationcount = count($paginationcount);
                        $group = "yes";
                    }
                    if($v === "1 = 1 GROUP BY Song.ArtistText"){
                        if(isset($extra['all_query']) && $extra['all_query'] == true){
                          $fields = array('count'=>'count(DISTINCT ArtistText)');
                          $paginationrow = $this->query("SELECT COUNT(Distinct ArtistText) FROM Songs AS Song WHERE Song.DownloadStatus = '1' AND Song.Sample_FileID != '' AND Song.FullLength_FIleID != '' AND ".$extra['all_country'].((!empty($extra['all_condition']))?" AND ".$extra['all_condition']:""));
                          $paginationcount = $paginationrow[0][0]['COUNT(Distinct ArtistText)'];
                          $group = "yes";
                          //$paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                          //$paginationcount = count($paginationcount);
                          //$group = "yes";
                        }
                        else{
                          $paginationcount = $this->find('all',compact('conditions', 'contain', 'recursive', 'fields'));
                          $paginationcount = count($paginationcount);
                          $group = "yes";
                        }


                    }
                }
				if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
					$sphinx = array('matchMode' => SPH_MATCH_EXTENDED2);
					$paginationcount = $this->find('all', array('search' =>  $extra['sphinxcheck'], 'group' => 'Song.ProdID', 'recursive' => 0, 'sphinx' => $sphinx), compact('conditions', 'contain', 'recursive', 'fields'));
					$paginationcount = count($paginationcount);
					$group = "yes";
				}
                if($group != "yes"){
					if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
						$sphinx = array('matchMode' => SPH_MATCH_EXTENDED2);
						$paginationcount = $this->find('count', array('search' =>  $extra['sphinxcheck'], 'group' => 'Song.ProdID', 'recursive' => 0, 'sphinx' => $sphinx), compact('conditions', 'contain', 'recursive', 'fields'));
					} else {
						$paginationcount = $this->find('count', compact('conditions', 'contain', 'recursive'));
					}
                }
				if(isset($extra['sphinx']) &&  $extra['sphinx'] == 'yes') {
					$paginationcount = $paginationcount;
				} else {
					Cache::write('paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount, 'paginate_cache');
				}
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
  function lastQuery(){
    $dbo = $this->getDatasource();
    $logs = $dbo->_queriesLog;
    // return the first element of the last array (i.e. the last query)
    return current(end($logs));
  }

}
?>