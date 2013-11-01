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
    
    
    /* Function name : getStreamNowLabel
     * Description   : This function is used to get stream now mark up replacing play button 
     */
    
    function getStreamNowLabel($streamUrl,$songTitle,$artistText,$songLength,$songProdId,$providerType){
$str = <<<EOD
       <a onclick="loadSong('$streamUrl','$songTitle','$artistText',$songLength,$songProdId,'$providerType');"  class="preview" href="javascript:void(0);" style="background: none repeat scroll 0 0 #FFFFFF;bottom: 10px;box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);color: #000000;display: block;font-size: 12px;font-weight: bold;height: 25px;line-height: 25px; opacity: 0;position: absolute;right: 38px;text-align: center;text-decoration: none;text-transform: uppercase;width: 124px;left: 76px;">Stream Now</a>;        
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