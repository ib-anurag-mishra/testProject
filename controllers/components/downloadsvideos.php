<?php
/*
 File Name : downloads.php
File Description : Component page for the  download functionality.
Author : m68interactive
*/

Class DownloadsvideosComponent extends Object
{
	var $name = 'Downloadsvideos';
	var $components = array('Auth','Session');

	/*
	 Function Name : checkLibraryDownload
	Desc : function used for checking library downloads
	*/
	function checkLibraryDownloadVideos($libId) {
		$libraryInstance = ClassRegistry::init('Library');
		$libraryInstance->recursive = -1;
		$results = $libraryInstance->find('count',array('conditions' => array('library_download_limit > library_current_downloads','id' => $libId,'library_available_downloads > 1','library_status'=>'active')));
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
	function checkPatronDownloadVideos($patId,$libId) {
		$downloadInstance = ClassRegistry::init('LatestDownload');
		$libraryInstance = ClassRegistry::init('Library');
		$libraryInstance->recursive = -1;
		$downloadInstance->recursive = -1;
		$libraryResults = $libraryInstance->find('all',array('conditions' => array('Library.id' => $libId)));
		$patronLimit = $libraryResults['0']['Library']['library_user_download_limit'];
		$results = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN "'.Configure::read('App.curWeekStartDate').'" and "'.Configure::read('App.curWeekEndDate').'" ')));

		$videoDownloadInstance = ClassRegistry::init('LatestVideodownload');
		$videoDownloadInstance->recursive = -1;
		$videoDownloadCount = $videoDownloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
		$videoDownloadCount = $videoDownloadCount *2;
		$downloadCount = $results + $videoDownloadCount;

		if(($downloadCount+1) < $patronLimit) {
			return true;
		}
		else {
			return false;
		}
	}

	function validateDownloadVideos($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null, $patId = null, $agent = null, $library_id = null){

		if(!$isMobileDownload){
			$user = $this->Auth->user();
			$uid = (int)$user['User']['id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$channel = 'Website';
			$libId = $this->Session->read('library');
		} else {
			$uid = $patId;
			$ip = $agent;
			$channel = 'Mobile App';
			$libId = $library_id;
		}

		if($this->checkSongExistsVideos($prodId, $providerType)){
			if($this->checkAllowedCountryVideos($prodId, $providerType, $isMobileDownload, $mobileTerritory)){
				if($this->checkLibraryDownloadVideos($libId)){
					if($this->checkPatronDownloadVideos($uid,$libId)){
						return array(true,'', 1);
					} else {
						$this->log($channel." : Rejected download request for ".$prodID." ".$providerType." from User:".$uid." IP:".$ip." as the patron download limit has been reached");
						return array(false,'The patron has reached the download limit', 2);
					}
				} else {
					$this->log($channel." : Rejected download request for ".$prodId." ".$providerType." from User:".$uid." IP:".$ip." as the library download limit has been reached");
					return array(false,'The library has reached the download limit for videos.', 3);
				}
			} else {
				$this->log($channel." : Rejected download request for ".$prodId." ".$providerType." from User:".$uid." IP:".$ip." as the song requested is not available for territory ".((!$isMobileDownload)?$this->Session->read('territory'):$mobileTerritory));
				return array(false,'The song is not available for this Country.', 4);
			}
		} else {
			$this->log($channel." : Rejected download request for ".$prodId." ".$providerType." from User:".$uid." IP:".$ip." as the song requested does not exist in songs table");
			return array(false,'The song requested for download does not exist', 5);
		}
	}

	function checkSongExistsVideos($prodId, $providerType){
		$songInstance = ClassRegistry::init('Video');
		$songInstance->recursive = -1;
		$song = $songInstance->find('first', array('conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'DownloadStatus'=>'1')));
		if(!empty($song['Video'])){
			return true;
		} else {
			return false;
		}
	}

	function checkAllowedCountryVideos($prodId, $providerType, $isMobileDownload = false, $mobileTerritory = null){
		$countryInstance = ClassRegistry::init('Country');
		$countryInstance->recursive = -1;
		if(!$isMobileDownload){
			$territory = $this->Session->read('territory');
		} else {
			$territory = $mobileTerritory;
		}
		$countryInstance->tablePrefix = strtolower($territory)."_";
		$country = $countryInstance->find('first', array('fields'=>array('ProdID'),'conditions' => array('ProdID'=>$prodId, 'provider_type'=>$providerType,'Territory'=>$territory, 'SalesDate <= NOW()')));
		if(!empty($country['Country'])){
			return true;
		} else {
			return false;
		}
	}
}
