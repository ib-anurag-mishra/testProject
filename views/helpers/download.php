<?php

class DownloadHelper extends AppHelper {
    var $uses = array('Download');
    
    function getDownloadDetails($libId,$patId) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $downloadCount = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        return $downloadCount;
    }
    function getDownloadData($libId,$startDate,$endDate) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $downloadCount = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        return $downloadCount;
    }      
}

?>