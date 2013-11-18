<?php
/*
	 File Name : queue.php
	 File Description : helper file for getting queue detail
	 Author : m68interactive
 */
class QueueHelper extends AppHelper {
    var $uses = array('QueueList');
    
    /**
     * Function name : getQueuesList
     * Description   : This function is used to get mark up related to Add to queue
     */
    function getQueuesList($patron_id,$song_prodid,$song_providertype,$album_prodid,$album_providertype) {
        if(!empty($patron_id)){
			$queueInstance = ClassRegistry::init('QueueList');
			$queueInstance->recursive = -1;
			$queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id,'status' => 1),'fields' =>  array('QueueList.queue_id', 'QueueList.queue_name'),'order' => 'QueueList.created DESC'));
		}else{
			$queueList = array();
		}
$str = <<<EOD
           <div class="playlist-options">
                    <ul>
                            <li><a href="#" class="create-new-queue-btn">Create New Queue</a></li>
EOD;

        if(!empty($queueList)){
            foreach($queueList as $key => $value){
                $str.='<li><a href="JavaScript:void(0);" onclick='.'\'Javascript: addToQueue("'.$song_prodid.'","'.$song_providertype.'","'.$album_prodid.'","'.$album_providertype.'","'.$value['QueueList']['queue_id'].'");\'>'.$value['QueueList']['queue_name'].'</a></li>';
            }
        }                    
        $str.= '</ul></div>';
        return $str;
    }
    
	/*
	function name  : getQueuesListAlbums
	desc :  This function is used to get mark up for add to queue for albums
	*/
	
  
	function getQueuesListAlbums($patron_id,$albumSongs,$albumProdId,$albumProviderType){
		if(!empty($patron_id)){
			$queueInstance = ClassRegistry::init('QueueList');
			$queueInstance->recursive = -1;
			$queueList = $queueInstance->find('all', array('conditions' => array('patron_id' => $patron_id,'status' => 1),'fields' =>  array('QueueList.queue_id', 'QueueList.queue_name'),'order' => 'QueueList.created DESC'));
		}else{
			$queueList = array();
		}
			$str = <<<EOD
           <div class="playlist-options">
                    <ul>
                            <li><a href="#" class="create-new-queue-btn">Create New Queue</a></li>
EOD;

        if(!empty($queueList)){
            foreach($queueList as $key => $queuevalue){
				if(!empty($albumSongs)){
					$albumSongsToAdd = array();
					foreach($albumSongs as $value){
						$albumSongsArray = array('song_prodid' => $value['Song']['ProdID'],'song_providertype' => $value['Song']['provider_type'],'album_prodid' => $albumProdId, 'album_providertype' => $albumProviderType,'queue_id' => $queuevalue['QueueList']['queue_id']);
						$albumSongsArray = json_encode($albumSongsArray);
						$albumSongsArray = str_replace("\/","/",$albumSongsArray); 
						$albumSongsToAdd[] =$albumSongsArray;						
					}
				
				}			
				if(!empty($albumSongsToAdd)){
					$albumToQueue = implode(',', $albumSongsToAdd);
					if(!empty($albumToQueue)){
						$albumToQueue = '['.addslashes($albumToQueue).']';
					}				
				}			
                            $str.='<li><a href="JavaScript:void(0);" onclick='.'\'Javascript: addAlbumSongsToQueue("'.$albumToQueue.'");\'>'.$queuevalue['QueueList']['queue_name'].'</a></li>';
                        
            }
        }                    
        $str.= '</ul></div>';
        return $str;
	}

  
    /* Function name : getStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */
    
    function getStreamNowLabel($streamUrl,$songTitle,$artistText,$songLength,$songProdId,$providerType){
$str = <<<EOD
       <a onclick="loadSong('$streamUrl','$songTitle','$artistText',$songLength,$songProdId,'$providerType');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>        
EOD;
        return $str;

    }
    
    /* Function name : getAlbumStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */
    
    function getAlbumStreamNowLabel($albumSongs){
        if(!empty($albumSongs)){
                foreach($albumSongs as $value){
                        if(!empty($value['streamUrl']) || !empty($value['Song']['SongTitle'])){
                            
                            if($value["Song"]["Advisory"] =='T')
                            {
                                $value["Song"]["SongTitle"]  =   $value["Song"]["SongTitle"].' (Explicit)';
                            }
                            
                            $playItem = array('playlistId' => 0, 'songId' => $value["Song"]["ProdID"],'providerType' => $value["Song"]["provider_type"],  'label' => $value['Song']['SongTitle'],'songTitle' => $value['Song']['SongTitle'],'artistName' => $value['Song']['ArtistText'],'songLength' => $value['totalseconds'],'data' => $value['streamUrl']);
                            $jsonPlayItem = json_encode($playItem);
                            $jsonPlayItem = str_replace("\/","/",$jsonPlayItem); 
                            $playListData[] =$jsonPlayItem;
                        }                    
                }

        }			
        if(!empty($playListData)){
            $playList = implode(',', $playListData);
            if(!empty($playList)){
                $playList = base64_encode('['.$playList.']');
            }				
        }        
 $str = <<<EOD
       <a onclick="javascript:loadAlbumSong('{$playList}');"  class="album-preview" href="javascript:void(0);" >Stream Now</a>
EOD;
 
        return $str;

    }    

    /**
     * Function Name: getSocialNetworkinglinksMarkup
     * Description  : This function is used to get mark up of social networking links 
     */
    function getSocialNetworkinglinksMarkup(){
        
//        return  '<div class="share clearfix">
//                        <p>Share via</p>
//                        <a class="facebook" href="#"></a>
//                        <a class="twitter" href="#"></a>
//                </div>';
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
    function getSeconds($durationString){        
        
       if(isset($durationString) && $durationString!=0){
           sscanf($durationString, "%d:%d", $minutes, $seconds);
           $time_seconds = $minutes * 60 + $seconds;          
           return $time_seconds;
       } else {
           return 0;
       }        
    }
    
    
}

?>