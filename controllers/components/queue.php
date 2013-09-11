<?php

 /*
 File Name : queue.php
 File Description : Component page for the  queues functionality.
 Author : m68interactive
 */

Class QueueComponent extends Object
{
    
    var $uses = array('QueueList','QueueDetail','User','Album','Song');
    
    /**
     * Function name : getQueueList
     * Description   : This is used to retrieve the list of queues created by an individual
     */
    function getQueueList($patronID){
        
        if(!empty($patronID)){
            $queuelistInstance = ClassRegistry::init('QueueList');        
            $cond = array('patron_id' => $patronID, 'status' => '1');

            // Unbinded User model
            $queuelistInstance->unbindModel(
                array('belongsTo' => array('User'))
            );        

            $queueData = $queuelistInstance->find('all', array(
                    'conditions' => $cond,
                    'order' => 'QueueList.created DESC',
                    'limit' => 100
                  ));
        }else{
            $queueData = array();
        }
        return $queueData;        
    }
    
    
    function getQueueDetails($queueID){
        $queueDetailList = ClassRegistry::init('QueueDetail');
        $queueDetail = $queueDetailList->find('all',
          array(
            'fields' =>  array('QueueDetail.id', 'QueueList.queue_name', 'QueueList.description', 'Songs.SongTitle','Songs.ReferenceID', 'Songs.FullLength_Duration', 'Songs.ProdID', 'Songs.provider_type', 'Songs.Title as STitle', 'Songs.ArtistText',  'Songs.Artist', 'Albums.AlbumTitle','Albums.ProdID','Albums.provider_type', 'Albums.Title as ATitle', 'Product.pid as AlbumProdID', 'AlbumFile.CdnPath as ACdnPath', 'AlbumFile.SourceURL as ASourceURL', 'SongFile.CdnPath as SCdnPath', 'SongFile.SaveAsName as SSaveAsName'),
            'joins' => array(
              array(
                'type' => 'INNER',
                'table' => 'queue_lists',
                'alias' => 'QueueList',
                'foreignKey' => false,
                'conditions' => array('QueueList.queue_id = QueueDetail.queue_id'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'Songs',
                'alias' => 'Songs',
                'foreignKey' => false,
                'conditions' => array('Songs.ProdID = QueueDetail.song_prodid', 'Songs.provider_type = QueueDetail.song_providertype'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'Albums',
                'alias' => 'Albums',
                'foreignKey' => false,
                'conditions' => array('Albums.ProdID = Songs.ReferenceID', 'Albums.provider_type = Songs.provider_type'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'PRODUCT',
                'alias' => 'Product',
                'foreignKey' => false,
                'conditions' => array('Albums.ProdID = Product.ProdID', 'Albums.provider_type = Product.provider_type'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'File',
                'alias' => 'AlbumFile',
                'foreignKey' => false,
                'conditions' => array('Albums.FileID = AlbumFile.FileID'),        
              ),  
              array(
                'type' => 'INNER',
                'table' => 'File',
                'alias' => 'SongFile',
                'foreignKey' => false,
                'conditions' => array('Songs.FullLength_FileID = SongFile.FileID'),        
              ),           
            ),
            'recursive' => -1,
            'conditions' => array('QueueList.status' => 1, 'QueueDetail.queue_id' => $queueID ),                
          )
        );
        return $queueDetail;
        
        
  }

    function getNowstreamingSongDetails($prodId,$providerType){
        $nowStreamingSongDetailList = ClassRegistry::init('Song');
        $nowStreamingSongDetail = $nowStreamingSongDetailList->find('all',
          array(
            'fields' =>  array('Songs.SongTitle','Songs.ReferenceID', 'Songs.FullLength_Duration', 'Songs.ProdID', 'Songs.provider_type', 'Songs.Title as STitle', 'Songs.ArtistText',  'Songs.Artist', 'Albums.AlbumTitle','Albums.ProdID','Albums.provider_type', 'Albums.Title as ATitle', 'Product.pid as AlbumProdID', 'AlbumFile.CdnPath as ACdnPath', 'AlbumFile.SourceURL as ASourceURL', 'SongFile.CdnPath as SCdnPath', 'SongFile.SaveAsName as SSaveAsName'),
            'joins' => array(
              array(
                'type' => 'INNER',
                'table' => 'Albums',
                'alias' => 'Albums',
                'foreignKey' => false,
                'conditions' => array('Albums.ProdID = Songs.ReferenceID', 'Albums.provider_type = Songs.provider_type'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'PRODUCT',
                'alias' => 'Product',
                'foreignKey' => false,
                'conditions' => array('Albums.ProdID = Product.ProdID', 'Albums.provider_type = Product.provider_type'),        
              ),
              array(
                'type' => 'INNER',
                'table' => 'File',
                'alias' => 'AlbumFile',
                'foreignKey' => false,
                'conditions' => array('Albums.FileID = AlbumFile.FileID'),        
              ),  
              array(
                'type' => 'INNER',
                'table' => 'File',
                'alias' => 'SongFile',
                'foreignKey' => false,
                'conditions' => array('Songs.FullLength_FileID = SongFile.FileID'),        
              ),           
            ),
            'recursive' => -1,
            'conditions' => array('Songs.ProdID = '.$prodId, 'Songs.provider_type = '.$providerType),                
          )
        );
        return $nowStreamingSongDetail;
        
        
  }
}


?>
