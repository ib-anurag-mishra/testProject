<?php
/**
 * @Name    :      CacheHandler
 * @Desc    :      This component validating Memecache data
 * @Author  :      m68interactive
 */

Class CacheHandlerComponent extends Object  {
    
        
    var $components = array('Session', 'Streaming', 'Queue','Email','Common');
    var $uses = array('Token','FeaturedVideo','MemDatas');

    
    /* @name    checkMemData
     * @desc    responsible to check memcache data 
     * 
     * @param   $cacheVariableName   string
     * 
     * @return @array
     * 
     */
    function checkMemData($cacheVariableName) {
        $memDatasInstance = ClassRegistry::init('MemDatas');
        
         
        //check cache variable exist in mem_datas table or not
        $cacheVarInfo =  $this->checkMemdataVariableExist($cacheVariableName);
        if( $cacheVarInfo != false ) { 
            $new_releases_albums_rs =  unserialize(base64_decode($cacheVarInfo['MemDatas']['vari_info']));
            Cache::write($cacheVariableName, $new_releases_albums_rs);
           
            return $new_releases_albums_rs;            
        }else{
            return false;                            
        }
    }
    
    
    /* @name    checkMemdataVariableExist
     * @desc    responsible to check memcache variable in the mem_datas table 
     * 
     * @param   $cacheVariableName   string
     * 
     * @return @array
     * 
     */
    function checkMemdataVariableExist($cacheVariableName) {
        
        $memDatasInstance = ClassRegistry::init('MemDatas');
        $memDatasInstance->recursive = -1;
        $results = $memDatasInstance->find('first',array('conditions' => array('cache_variable_name' => $cacheVariableName),'fields' => array('id','vari_info')));

        if(count($results) > 0 && isset($results['MemDatas']['id']) && $results['MemDatas']['id']!='') {
                return $results;
        } else {
                return false;
        }
        
    }
    
    /* @name    setMemData
     * @desc    responsible to update cache variable in to the mem_datas table 
     * 
     * @param   $cacheVariableName   string
     * @param   $dataArray  array
     * 
     * @return void
     * 
     */
    function setMemData($cacheVariableName,$dataArray) {
        
        $memDatasInstance = ClassRegistry::init('MemDatas');
        $memDatasInstance->recursive = -1;
        
        $encodedDataValue = base64_encode(serialize($dataArray));
        
        //check cache variable exist in mem_datas table or not
        $cacheVarInfo =  $this->checkMemdataVariableExist($cacheVariableName);
        if( $cacheVarInfo != false ) { 
            //update memdatas variables            
            $memDataFieldArr = array('id' => $cacheVarInfo['MemDatas']['id'],'vari_info' => $encodedDataValue );            
        }else{
            //insert memdata variables
            $memDatasInstance->create();
            $memDataFieldArr = array('cache_variable_name' => $cacheVariableName , 'vari_info' => $encodedDataValue ); 
        } 
        $memDatasInstance->save($memDataFieldArr); 
    }
    
    
    
}
