<?php

 /*
 File Name : queue.php
 File Description : Component page for the  queues functionality.
 Author : m68interactive
 */

Class QueueComponent extends Object
{
    
    var $uses = array('Queuelist','QueuelistDetails','User','Album','Song');
    
    /**
     * Function name : getQueueList
     * Description   : This is used to retrieve the list of queues created by an individual
     */
    function getQueueList($patronID){
        
        $queuelistInstance = ClassRegistry::init('Queuelist');        
        $cond = array('patronID' => $patronID, 'status' => '1');
        
        // Unbinded User model
        $queuelistInstance->unbindModel(
            array('belongsTo' => array('User'))
        );        
        
        $queueData = $queuelistInstance->find('all', array(
                'conditions' => $cond,
                'order' => 'Queuelist.Created DESC',
                'limit' => 100
              ));
        
        return $queueData;        
    }
    
    
    function getQueueDetails($queueID, $pat_Id){
       //echo "QID: ".$queueID;
  //  $patId= 1101400335373;
       // echo "<br>[".$pat_Id."]";
    
    $queueDetailList = ClassRegistry::init('QueuelistDetails');
    $queueDetail = $queueDetailList->find('all',
      array(
        'fields' =>  array('QueuelistDetails.Pdid', 'Queuelists.PlaylistName', 'Queuelists.description', 'Songs.SongTitle', 'Songs.FullLength_Duration', 'Songs.ProdID', 'Songs.provider_type', 'Songs.Title as STitle', 'Songs.ArtistText',  'Songs.Artist', 'Albums.AlbumTitle', 'Albums.Title as ATitle', 'Product.pid as AlbumProdID', 'AlbumFile.CdnPath as ACdnPath', 'AlbumFile.SourceURL as ASourceURL', 'SongFile.CdnPath as SCdnPath', 'SongFile.SaveAsName as SSaveAsName'),
        'joins' => array(
          array(
            'type' => 'INNER',
            'table' => 'Queuelists',
            'alias' => 'Queuelists',
            'foreignKey' => false,
            'conditions' => array('Queuelists.Plid = QueuelistDetails.Plid'),        
          ),
          array(
            'type' => 'INNER',
            'table' => 'Songs',
            'alias' => 'Songs',
            'foreignKey' => false,
            'conditions' => array('Songs.ProdID = QueuelistDetails.SongProdId', 'Songs.provider_type = QueuelistDetails.SongProviderType'),        
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
        'conditions' => array('Queuelists.status' => 1, 'QueuelistDetails.Plid' => $queueID , 'Queuelists.patronID' => trim($pat_Id)),                
      )
    );
    
//    echo "Query: ".$queueDetailList->lastQuery();
//    
//    echo "<pre>";
//    echo "in queue.php";
//    echo "Patron ID: ".$patId;
//    print_r($queueDetail);
//    echo 456;
//    die;
    
    
   /* $lib_territory = $this->getLibraryTerritory( $this->getLibraryIdFromAuthenticationToken($authenticationToken) );
         
    for( $cnt = $startFrom; $cnt < ($startFrom+$recordCount); $cnt++  ) {
      
      if(!(empty($data[$cnt]['Queuelists']['PlaylistName']))) {
        //if($this->IsDownloadable($data[$cnt]['Songs']['ProdID'], $lib_territory, $data[$cnt]['Songs']['provider_type'])) { 
          $obj = new QueueDetailDataType;
        
          $obj->QueueName                    = $data[$cnt]['Queuelists']['PlaylistName'];
          $obj->QueueSongSongTitle           = $data[$cnt]['Songs']['SongTitle'];
          $obj->QueueSongTitle               = $data[$cnt]['Songs']['STitle'];
          $obj->QueueSongArtistText          = $data[$cnt]['Songs']['ArtistText'];          
          $obj->QueueSongArtist              = $data[$cnt]['Songs']['Artist'];
          $obj->QueueSongFullLengthURL       = Configure::read('App.Music_Path').shell_exec('perl '.ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'files'.DS.'tokengen '.$data[$cnt]['SongFile']['SCdnPath']."/".$data[$cnt]['SongFile']['SSaveAsName']);
          $obj->QueueAlbumProductID          = $data[$cnt]['Product']['AlbumProdID'];
          $obj->QueueAlbumTitle              = $data[$cnt]['Albums']['ATitle'];
          $obj->QueueAlbumAlbumTitle         = $data[$cnt]['Albums']['AlbumTitle'];
          $obj->QueueAlbumImage              = Configure::read('App.Music_Path').shell_exec('perl '.ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.'files'.DS.'tokengen ' . $data[$cnt]['AlbumFile']['ACdnPath']."/".$data[$cnt]['AlbumFile']['ASourceURL']);
                 
          $queue[] = new SoapVar($obj,SOAP_ENC_OBJECT,null,null,'QueueDetailDataType');
        //}  
      }     
    }
    
    $queueDetail = new SoapVar($queue,SOAP_ENC_OBJECT,null,null,'ArrayQueueDetailDataType'); */
    
    return $queueDetail;
   
   
   
  }
    
}


?>
