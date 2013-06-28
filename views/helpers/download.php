<?php
/*
	 File Name : download.php
	 File Description : helper file for getting download details
	 Author : m68interactive
 */
class DownloadHelper extends AppHelper {
    var $uses = array('Download');
    
    function getDownloadDetails($libId,$patId) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $downloadCount = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        $videoDownloadInstance = ClassRegistry::init('Videodownload');
        $videoDownloadInstance->recursive = -1;
        $videoDownloadCount = $videoDownloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        $videoDownloadCount = $videoDownloadCount *2;
        $downloadCount = $downloadCount + $videoDownloadCount;
        return $downloadCount;
    }
    function getDownloadData($libId,$startDate,$endDate) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $downloadCount = $downloadInstance->find('all',array('fields' => array('COUNT(DISTINCT Download.id) AS totalProds'),'conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        return $downloadCount[0][0]['totalProds'];
    }      
}

?>