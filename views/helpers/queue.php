<?php

/*
  File Name : queue.php
  File Description : helper file for getting queue detail
  Author : m68interactive
 */

class QueueHelper extends AppHelper
{

    var $uses = array('QueueList');
    var $helpers = array('Session');

    /**
     * Function name : getUserQueuesList
     * Description   : This function is used to get queues list for user
     */
    function getUserQueuesList($patron_id = null)
    {
        $queueList = array();
        if (!empty($patron_id))
        {
            if (!$this->Session->check('queuelist'))
            {
                $queueInstance = ClassRegistry::init('QueueList');
                $queueInstance->recursive = -1;
                $queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id, 'status' => 1), 'fields' => array('QueueList.queue_id', 'QueueList.queue_name'), 'order' => 'QueueList.created DESC'));
                
            }           
        }


        $str = <<<EOD
           <div class="playlist-options">
                    <ul>
                            <li><a href="javascript:void(0);" class="create-new-queue-btn">Create New Playlist</a></li>
EOD;
        if (!empty($queueList))
        {
            foreach ($queueList as $key => $value)
            {
                $str.='<li><a href="JavaScript:void(0);" onclick="JavaScript:addToAlbumTest('.$value['QueueList']['queue_id'].', this );"  id="'.$value['QueueList']['queue_id'].'">' . $value['QueueList']['queue_name'] . '</a></li>';
            }
        }

        $str.= '</ul></div>';
        return $str;
    }
    
    
     /**
     * Function name : getUserQueuesList
     * Description   : This function is used to get queues list for user
     */
    function getUserQueuesListNew($patron_id = null)
    {
        $queueList = array();
        if (!empty($patron_id))
        {
            if (!$this->Session->check('queuelist'))
            {
                $queueInstance = ClassRegistry::init('QueueList');
                $queueInstance->recursive = -1;
                $queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id, 'status' => 1), 'fields' => array('QueueList.queue_id', 'QueueList.queue_name'), 'order' => 'QueueList.created DESC'));
                
            }           
        }

        $str = <<<EOD
                
                    <ul class="playlist-menu">
                            <li><a href="javascript:void(0);" class="create-new-queue-btn">Create New Playlist</a></li>
EOD;
        if (!empty($queueList))
        {
            foreach ($queueList as $key => $value)
            {
                $str.='<li><a href="JavaScript:void(0);" onclick="JavaScript:addToPlaylistNew('.$value['QueueList']['queue_id'].', this );"  id="'.$value['QueueList']['queue_id'].'">' . $value['QueueList']['queue_name'] . '</a></li>';
            }
        }

        $str.= '</ul>';
        return $str;
    }

    /**
     * Function name : getQueuesList
     * Description   : This function is used to get mark up related to Add to queue
     */
    function getQueuesList($patron_id, $song_prodid, $song_providertype, $album_prodid, $album_providertype)
    {
        if (!empty($patron_id))
        {
            $queueInstance = ClassRegistry::init('QueueList');
            $queueInstance->recursive = -1;
            $queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id, 'status' => 1), 'fields' => array('QueueList.queue_id', 'QueueList.queue_name'), 'order' => 'QueueList.created DESC'));
        }
        else
        {
            $queueList = array();
        }
        $str = <<<EOD
           <div class="playlist-options">
                    <ul>
                            <li><a href="javascript:void(0);" class="create-new-queue-btn">Create New Playlist</a></li>
EOD;

        if (!empty($queueList))
        {
            foreach ($queueList as $key => $value)
            {
                $str.='<li><a href="JavaScript:void(0);" onclick=' . '\'Javascript: addToQueue("' . $song_prodid . '","' . $song_providertype . '","' . $album_prodid . '","' . $album_providertype . '","' . $value['QueueList']['queue_id'] . '");\'>' . $value['QueueList']['queue_name'] . '</a></li>';
            }
        }
        $str.= '</ul></div>';
        return $str;
    }
    
    
     function getQueueListCountUnique($arr_songs)
    {
        $temp_songs =   array();
        
        foreach($arr_songs as $key=>$value)
        {
            if(in_array($value['song_prodid'], $temp_songs))
            {
               continue;
            }
            else
            {
                $temp_songs[$key] = $value['song_prodid'];
            }
        }
        
        return count($temp_songs);
    }

    /*
      function name  : getQueuesListAlbums
      desc :  This function is used to get mark up for add to queue for albums
     */

    function getQueuesListAlbums($patron_id, $albumSongs, $albumProdId, $albumProviderType)
    {
        if (!empty($patron_id))
        {
            $queueInstance = ClassRegistry::init('QueueList');
            $queueInstance->recursive = -1;
            $queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id, 'status' => 1), 'fields' => array('QueueList.queue_id', 'QueueList.queue_name'), 'order' => 'QueueList.created DESC'));
        }
        else
        {
            $queueList = array();
        }
        $str = <<<EOD
           <div class="playlist-options">
                    <ul>
                            <li><a href="javascript:void(0);" class="create-new-queue-btn">Create New Playlist</a></li>
EOD;

        if (!empty($queueList))
        {
            foreach ($queueList as $key => $queuevalue)
            {
                if (!empty($albumSongs))
                {
                    $albumSongsToAdd = array();
                    foreach ($albumSongs as $value)
                    {
                        $albumSongsArray = array('song_prodid' => $value['Song']['ProdID'], 'song_providertype' => $value['Song']['provider_type'], 'album_prodid' => $albumProdId, 'album_providertype' => $albumProviderType, 'queue_id' => $queuevalue['QueueList']['queue_id']);
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
                        $albumToQueue = '[' . addslashes($albumToQueue) . ']';
                    }
                }
                $str.='<li><a href="JavaScript:void(0);" onclick=' . '\'Javascript: addAlbumSongsToQueue("' . $albumToQueue . '");\'>' . $queuevalue['QueueList']['queue_name'] . '</a></li>';
            }
        }
        $str.= '</ul></div>';
        return $str;
    }

    /* Function name : getStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getStreamNowLabel($streamUrl, $songTitle, $artistText, $songLength, $songProdId, $providerType)
    {

        $songTitle = base64_encode($songTitle);
        $artistText = base64_encode($artistText);

        $str = <<<EOD
       <a onclick="loadSong('$streamUrl','$songTitle','$artistText',$songLength,$songProdId,'$providerType');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>        
EOD;
        return $str;
    }
    
    
    /* Function name : getNationalsongsStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getNationalsongsStreamNowLabel($cdnPath, $sourceUrl,$songTitle, $artistText, $songLength, $songProdId, $providerType)
    {

        $songTitle = base64_encode($songTitle);
        $artistText = base64_encode($artistText);
        $cdnPath = base64_encode($cdnPath);
        $sourceUrl = base64_encode($sourceUrl);
        $songLength = base64_encode($songLength);
        $str = <<<EOD
       <button class="play-btn-icon" onclick="loadNationalTopSong('$cdnPath','$sourceUrl','$songTitle','$artistText','$songLength',$songProdId,'$providerType');"></button>        
EOD;
        return $str;
    } 
    

    /* Function name : getsearchSongsStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getsearchSongsStreamNowLabel($streamUrl, $songTitle, $artistText, $songLength, $songProdId, $providerType)
    {

        $songTitle = base64_encode($songTitle);
        $artistText = base64_encode($artistText);
        $str = <<<EOD
       <button class="play-btn" onclick="loadSong('$streamUrl','$songTitle','$artistText','$songLength',$songProdId,'$providerType');"></button>        
EOD;
        return $str;
    }    

    /* Function name : getAlbumStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getAlbumStreamNowLabel($albumSongs , $top = null)
    {
        if (!empty($albumSongs))
        {
            foreach ($albumSongs as $value)
            {
                if (!empty($value['streamUrl']) || !empty($value['Song']['SongTitle']))
                {

                    if ($value["Song"]["Advisory"] == 'T')
                    {
                        $value["Song"]["SongTitle"] = $value["Song"]["SongTitle"] . ' (Explicit)';
                    }

                    $playItem = array('playlistId' => 0, 'songId' => $value["Song"]["ProdID"], 'providerType' => $value["Song"]["provider_type"], 'label' => $value['Song']['SongTitle'], 'songTitle' => $value['Song']['SongTitle'], 'artistName' => $value['Song']['ArtistText'], 'songLength' => $value['totalseconds'], 'data' => $value['streamUrl']);
                    $jsonPlayItem = json_encode($playItem);
                    $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                    $playListData[] = $jsonPlayItem;
                }
            }
        }
        if (!empty($playListData))
        {
            $playList = implode(',', $playListData);
            if (!empty($playList))
            {
                $playList = base64_encode('[' . $playList . ']');
            }
        }
        if(empty($top)){
        $str = <<<EOD
            <a onclick="javascript:loadAlbumSong('{$playList}');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>
EOD;
            return $str;
        }else if($top == 1){
           
$str = <<<EOD
            <button onclick="javascript:loadAlbumSong('{$playList}');" class="play-btn-icon toggleable"></button>
EOD;
            return $str;           
       }else if($top == 2){
       
$str = <<<EOD
            <button onclick="javascript:loadAlbumSong('{$playList}');" class="stream-artist">Stream Album</button>
EOD;
            return $str;  
            
       }     
    }
    
    
    
    /* Function name : getTopAlbumStreamData
     * Description   : This function is used to get data for play button 
     */

    function getTopAlbumStreamData($albumSongs)
    {
        if (!empty($albumSongs))
        {
            foreach ($albumSongs as $value)
            {
                if (!empty($value['streamUrl']) || !empty($value['Song']['SongTitle']))
                {

                    if ($value["Song"]["Advisory"] == 'T')
                    {
                        $value["Song"]["SongTitle"] = $value["Song"]["SongTitle"] . ' (Explicit)';
                    }

                    $playItem = array('playlistId' => 0, 'songId' => $value["Song"]["ProdID"], 'providerType' => $value["Song"]["provider_type"], 'label' => $value['Song']['SongTitle'], 'songTitle' => $value['Song']['SongTitle'], 'artistName' => $value['Song']['ArtistText'], 'songLength' => $value['totalseconds'], 'data' => $value['streamUrl']);
                    $jsonPlayItem = json_encode($playItem);
                    $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                    $playListData[] = $jsonPlayItem;
                }
            }
        }
        if (!empty($playListData))
        {
            $playList = implode(',', $playListData);
            if (!empty($playList))
            {
                $playList = base64_encode('[' . $playList . ']');
            }
        }
        
        $playList.= '{'.$playList.'}';
        
        return   $playList;
    }    
    
    
    /* Function name : getAlbumStreamLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getAlbumStreamLabel($albumSongs,$flag = 0)
    {
        $albumSongs = base64_encode(json_encode($albumSongs));
        
        if(empty($flag)){
            $str = <<<EOD
       <a onclick="javascript:loadAlbumData('$albumSongs');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>
EOD;
            return $str;
        }else if ($flag == 1){
            $str = <<<EOD
                <button onclick="javascript:loadAlbumData('$albumSongs');" class="play-btn-icon toggleable"></button>
EOD;
            return $str;            
            
        }else if($flag == 2){
       
$str = <<<EOD
            <button onclick="javascript:loadAlbumData('$albumSongs');" class="stream-artist">Stream Artist</button>
EOD;
            return $str;
            
       }else if($flag == 3){
       
$str = <<<EOD
            <button onclick="javascript:loadAlbumData('$albumSongs');" class="stream-now-btn">Stream Now</button>
EOD;
            return $str;
            
       }
     
    }
    
    /* Function name : getfeaturedStreamLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */    
    
    function getfeaturedStreamLabel($artistName,$provider_type,$flag){
        
        $artistName = base64_encode($artistName);
        $provider_type = base64_encode($provider_type);
        $str = <<<EOD
            <button onclick="javascript:loadfeaturedSongs('$artistName','$provider_type',$flag);" class="stream-artist">Stream Artist</button>
EOD;
            return $str;        
    }
    
    /* Function name : getNationalAlbumStreamLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */

    function getNationalAlbumStreamLabel($artistText,$prodId,$providerType)
    {
        $providerType = base64_encode($providerType);
        $artistText = base64_encode($artistText);        
        
        $str = <<<EOD
       <a onclick="javascript:loadNationalAlbumData('$artistText',$prodId,'$providerType');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>
EOD;
        return $str;
    } 
    
    /**
     * Function Name: getSocialNetworkinglinksMarkup
     * Description  : This function is used to get mark up of social networking links 
     */
    function getSocialNetworkinglinksMarkup()
    {
        return "";
    }

    /*
      Function Name : getSeconds
      Desc : function used convert minut:second value in to seconds values
     * 
     * @param $durationString varChar  'library uniqe id'     
     *          
     * @return Boolean or second value
     */

    function getSeconds($durationString)
    {

        if (isset($durationString) && $durationString != 0)
        {
            sscanf($durationString, "%d:%d", $minutes, $seconds);
            $time_seconds = $minutes * 60 + $seconds;
            return $time_seconds;
        }
        else
        {
            return 0;
        }
    }
    
     

}

?>