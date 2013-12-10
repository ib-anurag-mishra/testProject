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
             }
             else
             {
                    $downloadResults =  $downloadInstance->find('all',array('joins'=>array(array('table' => 'Songs','alias' => 'Song','type' => 'LEFT','conditions' => array('Download.ProdID = Song.ProdID','Download.provider_type = Song.provider_type')),array('table' => $countryTableName,'alias' => 'Country','type' => 'INNER','conditions' => array('Country.ProdID = Song.ProdID','Country.provider_type = Song.provider_type')),array('table' => 'Albums','alias' => 'Album','type' => 'LEFT','conditions' => array('Song.ReferenceID = Album.ProdID','Song.provider_type = Album.provider_type')),array('table' => 'File','alias' => 'File','type' => 'LEFT','conditions' => array('Album.FileID = File.FileID')),array('table' => 'File','alias' => 'Full_Files','type' => 'LEFT','conditions' => array('Song.FullLength_FileID = Full_Files.FileID'))),'group' => 'Download.id','conditions' => array('library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'fields'=>array('Download.ProdID','Download.provider_type')));                    
                    
                    $downloadVariArray = array();
                    foreach($downloadResults as $key => $downloadResult){
                        $downloadVariArray[] = $downloadResult['Download']['ProdID'].'~'.$downloadResult['Download']['provider_type'];
                    }
                    $downloadVariArray= @array_unique($downloadVariArray);
                    $this->Session->write('downloadVariArray', $downloadVariArray ); 
             }
             
            if(!empty($downloadVariArr))
            {
               $checkDownloadVar =  $prodID.'~'.$providerType;                                                   

               if (in_array($checkDownloadVar, $downloadVariArr)) 
               {                                                               
                   $downloadsUsed=1;
               }
               else
               {
                    $downloadsUsed=0;
               }
           }
           else
           {
              $downloadsUsed = 0;
           } 
             
             
           return $downloadsUsed;
    }
}

?>