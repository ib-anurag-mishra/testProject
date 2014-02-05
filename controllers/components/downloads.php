<?php
 /*
 File Name : downloads.php
 File Description : Component page for the  download functionality.
 Author : m68interactive
 */
 
Class DownloadsComponent extends Object
{
    var $components = array('Session');
    
    /*
     Function Name : checkLibraryDownload
     Desc : function used for checking library downloads
    */
    function checkLibraryDownload($libId) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $results = $libraryInstance->find('count',array('conditions' => array('library_download_limit > library_current_downloads','id' => $libId,'library_available_downloads > 0','library_status'=>'active')));
        if($results > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /*
     Function Name : checkPatronDownload
     Desc : function used for checking patron downloads
    */
    function checkPatronDownload($patId,$libId) {
        $downloadInstance = ClassRegistry::init('Download');
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $downloadInstance->recursive = -1;
        $libraryResults = $libraryInstance->find('all',array('conditions' => array('Library.id' => $libId)));        
        $patronLimit = $libraryResults['0']['Library']['library_user_download_limit'];
        $results = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN "'.Configure::read('App.curWeekStartDate').'" and "'.Configure::read('App.curWeekEndDate').'" ')));
        
        $videoDownloadInstance = ClassRegistry::init('Videodownload');
        $videoDownloadInstance->recursive = -1;
        $videoDownloadCount = $videoDownloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        $videoDownloadCount = $videoDownloadCount *2;
        $downloadCount = $results + $videoDownloadCount; 
        
        if($downloadCount < $patronLimit) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function validateDownload($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null, $patId = null, $agent = null, $library_id = null){
       
        if(!$isMobileDownload){
          $uid = $this->Session->read('Auth.User.id');
          if(empty($uid)){
          	$uid = $this->Session->read('patron');
          }
          //$uid = (int)$user['User']['id'];
          $ip = $_SERVER['REMOTE_ADDR'];
          $channel = 'Website';
          $libId = $this->Session->read('library');
        
        } else {
            $uid = $patId;
            $ip = $agent;
            $channel = 'Mobile App';
            $libId = $library_id;
        }
  
         
            if($this->checkSongExists($prodId, $providerType)){ 
               
                if($this->checkAllowedCountry($prodId, $providerType, $isMobileDownload, $mobileTerritory)){ 
                    
                    if($this->checkLibraryDownload($libId)){
                         
                        if($this->checkPatronDownload($uid,$libId)){
                            $this->log($channel." : allowed download request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip,'download');
                            return array(true,'', 1);
                        } else {
                            $this->log($channel." : Rejected download request for ".$prodId." ".$providerType." from User:".$uid." IP:".$ip." as the patron download limit has been reached");
                            return array(false,'The patron has reached the download limit', 2);
                        }
                    } else {
                        $this->log($channel." : Rejected download request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the library download limit has been reached",'download');
                        return array(false,'The library has reached the download limit.', 3);
                    }
                } else {
                    $this->log($channel." : Rejected download request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the song requested is not available for territory ".((!$isMobileDownload)?$this->Session->read('territory'):$mobileTerritory),'download');
                    return array(false,'The song is not available for this Country.', 4);
                }
            } else {
                echo $channel." : Rejected download request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the song requested does not exist in songs table";
                $this->log($channel." : Rejected download request for ".$prodId." - ".$providerType." - ".$libId." from User:".$uid." IP:".$ip." as the song requested does not exist in songs table",'download');
                return array(false,'The song requested for download does not exist', 5);
            }
        
    }
    
    function checkSongExists($prodId, $providerType){
         
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;
        $song = $songInstance->find('first',array('fields'=>'Song.Title'),array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType)));
        
       
        if(!empty($song['Song'])){             
            return true;
        } else {
            return false;
        }
    }
    
    function checkAllowedCountry($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null){
        $countryInstance = ClassRegistry::init('Country');
        $countryInstance->recursive = -1;
        if(!$isMobileDownload){
            $territory = $this->Session->read('territory');
        } else {
            $territory = $mobileTerritory;
        }
        $prodId='28194771';
        $providerType = 'sony';
        
        $countryInstance->tablePrefix = strtolower($territory)."_";
        $country = $countryInstance->find('count',array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'Territory'=>$territory,'DownloadStatus'=>'1', 'SalesDate <= NOW()')));
        if($country > 0){        
            return true;
        } else {            
            return false;
        }



        
        
        
        
          //$country = $countryInstance->find('first',array('fields'=>'ProdID','Territory','SalesDate','provider_type'),array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'Territory'=>$territory,'DownloadStatus'=>'1', 'SalesDate <= NOW()')));

//        if(!empty($country['Country'])){            
//            return true;
//        } else {            
//            return false;
//        }
    }
    

    
    
}
?>