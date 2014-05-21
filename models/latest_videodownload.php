<?php
/*
 File Name : latest_videodownloads.php
 File Description : Models page for the  latest_videodownloads table.
 Author : m68interactive
*/

class LatestVideodownload extends AppModel
{
  var $name = 'LatestVideodownload';
  var $usetable = 'latest_videodownloads';
  
  public function fetchLatestVideoDownloadCount($libraryId, $patronId, $productId, $providerType) {

  	$options = array(
  				'conditions' => array(
  					'LatestVideodownload.library_id' => $libraryId,
  					'LatestVideodownload.patron_id' => $patronId,
  					'LatestVideodownload.ProdID' => $productId,
  					'LatestVideodownload.provider_type' => $providerType,
  					'DATE(LatestVideodownload.created)' => date('Y-m-d'),
  			),
  			'recursive' => -1,
  	);

  	return $this->find('count', $options);
  }
  
  public function fetchLatestVideodownloadTopDownloadedVideos($libraryId) {
  	
  	$options = array(
  				'conditions' => array('library_id' => $libraryId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 
  				'group' => array('ProdID'), 
  				'fields' => array(
  							'ProdID', 
  							'COUNT(DISTINCT id) AS countProduct', 
  							'provider_type'
  							), 
  				'order' => 'countProduct DESC', 
  				'limit' => 15
  			);
  	
  	$this->find('all', $options);
  }
}
?>