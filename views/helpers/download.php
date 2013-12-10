<?php
/*
	 File Name : download.php
	 File Description : helper file for getting download details
	 Author : m68interactive
 */
class DownloadHelper extends AppHelper {
    var $uses = array('Download');
    var $helpers = array('Session');
    
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
    function getDownloadfind($prodId,$provider_type,$libId,$patID,$startDate,$endDate) {
        $downloadInstance = ClassRegistry::init('Download');
        $downloadInstance->recursive = -1;
        $downloadCount = $downloadInstance->find('all',array('fields' => array('COUNT(DISTINCT Download.id) AS totalProds'),'conditions' => array('ProdID' => $prodId,'provider_type' => $provider_type,'library_id' => $libId,'patron_id' => $patID,'history < 2','created BETWEEN ? AND ?' => array($startDate, $endDate))));
        return $downloadCount[0][0]['totalProds'];
    }
    
    function getDownloadResults($prodID, $providerType)
    { 
            $downloadInstance = ClassRegistry::init('Download');
            
            if($this->Session->read('downloadVariArray'))
            { 
                 $downloadVariArr = $this->Session->read('downloadVariArray');
                 if(!empty($downloadVariArr)){
                     $checkDownloadVar =  $prodID.'~'.$providerType;                                                   

                     if (in_array($checkDownloadVar, $downloadVariArr)) 
                     {                                                               
                         $downloadsUsed=1;
                     }
                     else
                     {
                          $downloadsUsed=0;
                     }
                 }else{
                    $downloadsUsed = 0;
                 }                                                        
             }
             else
             {
                    $downloadsUsed = '';
             }
             
             
           return $downloadsUsed;
    }
}

?>