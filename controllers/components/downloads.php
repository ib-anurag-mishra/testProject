<?php
 /*
 File Name : downloads.php
 File Description : Component page for the  download functionality.
 Author : m68interactive
 */
 
Class DownloadsComponent extends Object
{
    var $components = array('Auth','Session');
    
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
     Function Name : checkLibraryDownload
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
        if($results < $patronLimit) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function validateDownload($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null){
        $user = $this->Auth->user();
        $uid = (int)$user['User']['id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if($uid > 0){
            $libId = $this->Session->read('library');
            if($this->checkSongExists($prodId, $providerType)){
                if($this->checkAllowedCountry($prodId, $providerType, $isMobileDownload, $mobileTerritory)){
                    if($this->checkLibraryDownload($libId)){
                        if($this->checkPatronDownload($uid,$libId)){
                            return array(true,'');
                        } else {
                            $this->log("Rejected download request for ".$prodID." ".$providerType." from User:".$uid." IP:".$ip." as the patron download limit has been reached");
                            return array(false,'The patron has reached the download limit');
                        }
                    } else {
                        $this->log("Rejected download request for ".$prodID." ".$providerType." from User:".$uid." IP:".$ip." as the library download limit has been reached");
                        return array(false,'The library has reached the download limit.');
                    }
                } else {
                    $this->log("Rejected download request for ".$prodID." ".$providerType." from User:".$uid." IP:".$ip." as the song requested is not available for territory ".((!$isMobileDownload)?$this->Session->read('territory'):$mobileTerritory));
                    return array(false,'The song is not available for this Country.');
                }
            } else {
                $this->log("Rejected download request for ".$prodID." ".$providerType." from User:".$uid." IP:".$ip." as the song requested does not exist in songs table");
                return array(false,'The song requested for download does not exist');
            }
        } else {
            $this->log("Rejected download request for ".$prodID." ".$providerType." from IP:".$ip." as user was not authenticated.");
            return array(false, 'User is not Authenticated');
        }
    }
    
    function checkSongExists($prodId, $providerType){
        $songInstance = ClassRegistry::init('Song');
        $songInstance->recursive = -1;
        $song = $songInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'DownloadStatus'=>'1')));
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
        $country = $countryInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'Territory'=>$territory, 'SalesDate <= NOW()')));
        if(!empty($country['Country'])){
            return true;
        } else {
            return false;
        }
    }
}
?>