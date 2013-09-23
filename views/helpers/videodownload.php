<?php
/*
	 File Name : videodownload.php
	 File Description : helper file for getting download details
	 Author : m68interactive
 */
class VideodownloadHelper extends AppHelper {
    var $uses = array('Videodownload');
    
    function getVideodownloadfind($prodId,$provider_type,$libId,$patID,$startDate,$endDate) {
        $videodownloadInstance = ClassRegistry::init('Videodownload');
        $videodownloadInstance->recursive = -1;
        $videodownloadCount = $videodownloadInstance->find('all',array('fields' => array('COUNT(DISTINCT Videodownload.id) AS totalProds'),'conditions' => array('ProdID' => $prodId,'provider_type' => $provider_type,'library_id' => $libId,'patron_id' => $patID,'history < 2','created BETWEEN ? AND ?' => array($startDate, $endDate))));
        return $videodownloadCount[0][0]['totalProds'];
    }
}

?>