<?php

class DownloadHelper extends AppHelper {
    var $uses = array('Download');
    
    function getDownloadDetails($libId,$patId) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";
        $downloadCount = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array($startDate, $endDate))));
        return $downloadCount;
    }    
}

?>