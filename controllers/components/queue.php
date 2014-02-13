<?php

/*
  File Name : queue.php
  File Description : Component page for the  queues functionality.
  Author : m68interactive
 */

Class QueueComponent extends Object
{

    var $uses = array('QueueList', 'QueueDetail', 'User', 'Album', 'Song');

    /**
     * Function name : getQueueList
     * Description   : This is used to retrieve the list of queues created by an individual
     */
    function getQueueList($patronID)
    {

        if (!empty($patronID))
        {
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
        }
        else
        {
            $queueData = array();
        }
        return $queueData;
    }

    function getQueueDetails($queueID, $territory = '')
    {
        $queueDetailList = ClassRegistry::init('QueueDetail');

        $queueDetail = $queueDetailList->find('all', array(
            'fields' => array('QueueDetail.id', 'QueueList.queue_name', 'QueueList.description', 'Songs.SongTitle', 'Songs.ReferenceID', 'Songs.Advisory', 'Songs.FullLength_Duration', 'Songs.ProdID', 'Songs.provider_type', 'Songs.Title as STitle', 'Songs.ArtistText', 'Songs.Artist', 'Albums.AlbumTitle', 'Albums.ProdID', 'Albums.provider_type', 'Albums.Title as ATitle', 'AProduct.pid as AlbumProdID', 'SProduct.pid as SongProdID', 'AlbumFile.CdnPath as ACdnPath', 'AlbumFile.SourceURL as ASourceURL', 'SongFile.CdnPath as SCdnPath', 'SongFile.SaveAsName as SSaveAsName', 'Countries.StreamingStatus', 'Countries.StreamingSalesDate', 'Countries.DownloadStatus', 'Countries.SalesDate'),
            'group' => array('Songs.ProdID', 'Songs.provider_type'),
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
                    'conditions' => array('QueueDetail.song_prodid=Songs.ProdID', 'QueueDetail.song_providertype=Songs.provider_type'),
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
                    'table' => strtolower($territory) . '_countries',
                    'alias' => 'Countries',
                    'foreignKey' => false,
                    'conditions' => array('QueueDetail.song_prodid = Countries.ProdID', 'QueueDetail.song_providertype = Countries.provider_type',),
                ),
                array(
                    'type' => 'INNER',
                    'table' => 'PRODUCT',
                    'alias' => 'AProduct',
                    'foreignKey' => false,
                    'conditions' => array('Albums.ProdID = AProduct.ProdID', 'Albums.provider_type = AProduct.provider_type'),
                ),
                array(
                    'type' => 'INNER',
                    'table' => 'PRODUCT',
                    'alias' => 'SProduct',
                    'foreignKey' => false,
                    'conditions' => array('Songs.ProdID = SProduct.ProdID', 'Songs.provider_type = SProduct.provider_type'),
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
            'conditions' => array('and' =>
                array(
                    array('QueueList.status' => 1),
                    array('QueueDetail.queue_id' => $queueID),
                    array('Countries.StreamingStatus' => 1),
                    array('Countries.StreamingSalesDate' => date('Y-m-d')),
                    array('Countries.DownloadStatus' => 1)
                ),
               /* 'or' => array(array('and' => array('Countries.StreamingStatus' => 1, 'Countries.StreamingSalesDate <=' => date('Y-m-d')))
                    , array('and' => array('Countries.DownloadStatus' => 1))
                )*/     // Commented to shown only songs which are available for (Streaming and Downloading)
            )
                )
        );
        return $queueDetail;
    }
    
    
    
    function getOnlyQueueDetails($queueID)
    {
         $queueInstance = ClassRegistry::init('QueueList');
         $queueInstance->recursive = -1;
            $queueList = $queueInstance->find('all', array('conditions' => array('queue_id' => $queueID, 'status' => 1),
                'fields' => array('QueueList.queue_id', 'QueueList.queue_name', 'QueueList.description')));
        return $queueList;
    }
    

    function getNowstreamingSongDetails($prodId, $providerType, $territory = '')
    {
        $nowStreamingSongDetailList = ClassRegistry::init('Song');
        $territoryArray = array();
        if ($territory != '')
        {
            $territoryArray = array(
                'type' => 'INNER',
                'table' => strtolower($territory) . '_countries',
                'alias' => 'Countries',
                'foreignKey' => false,
                'conditions' => array('Song.ProdID = Countries.ProdID', 'Song.provider_type = Countries.provider_type'),
            );
        }

        $nowStreamingSongDetail = $nowStreamingSongDetailList->find('all', array(
            'fields' => array('Song.SongTitle', 'Song.ReferenceID', 'Song.FullLength_Duration', 'Song.ProdID', 'Song.provider_type', 'Song.Advisory', 'Song.Title as STitle', 'Song.ArtistText', 'Song.Artist', 'Albums.AlbumTitle', 'Albums.ProdID', 'Albums.provider_type', 'Albums.Title as ATitle', 'Product.pid as AlbumProdID', 'AlbumFile.CdnPath as ACdnPath', 'AlbumFile.SourceURL as ASourceURL', 'SongFile.CdnPath as SCdnPath', 'SongFile.SaveAsName as SSaveAsName'),
            'joins' => array(
                array(
                    'type' => 'INNER',
                    'table' => 'Albums',
                    'alias' => 'Albums',
                    'foreignKey' => false,
                    'conditions' => array('Albums.ProdID = Song.ReferenceID', 'Albums.provider_type = Song.provider_type'),
                ),
                $territoryArray,
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
                    'conditions' => array('Song.FullLength_FileID = SongFile.FileID'),
                ),
            ),
            'recursive' => -1,
            'conditions' => array('Song.ProdID = ' . $prodId, 'Song.provider_type = ' . '"' . $providerType . '"'),
                )
        );
        return $nowStreamingSongDetail;
    }

    /**
     * Function Name: getAlbumEncodeSongsList
     * Description  : This function is used to get mark up of social networking links 
     */
    function getAlbumEncodeSongsList($patron_id, $albumSongs, $albumProdId, $albumProviderType, $queueID)
    {
        if (!empty($patron_id))
        {
            $queueInstance = ClassRegistry::init('QueueList');
            $queueInstance->recursive = -1;
            $queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id, 'status' => 1),
                'fields' => array('QueueList.queue_id', 'QueueList.queue_name'), 'order' => 'QueueList.created DESC'));
        }
        else
        {
            $queueList = array();
        }


        if (!empty($queueList))
        {
            foreach ($queueList as $key => $queuevalue)
            {
                if (!empty($albumSongs))
                {
                    $albumSongsToAdd = array();
                    foreach ($albumSongs as $value)
                    {
                        $albumSongsArray = array('song_prodid' => $value['Song']['ProdID'], 'song_providertype' => $value['Song']['provider_type'], 'album_prodid' => $albumProdId, 'album_providertype' => $albumProviderType, 'queue_id' => $queueID);
                        $albumSongsArray = json_encode($albumSongsArray);
                        $albumSongsArray = str_replace("\/", "/", $albumSongsArray);
                        $albumSongsToAdd[] = $albumSongsArray;
                    }
                }
                if (!empty($albumSongsToAdd))
                {
                    $albumToQueue = implode(',', $albumSongsToAdd);
                    if (!empty($albumToQueue))
                    {
                        $albumToQueue = '[' . $albumToQueue . ']';
                    }
                }
            }
        }

        return $albumToQueue;
    }

}

?>
