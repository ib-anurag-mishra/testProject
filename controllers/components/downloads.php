<?php
Class DownloadsComponent extends Object
{    
    var $components = array('Session');

    function checkLibraryDownload($libId)
    {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $results = $libraryInstance->find('count',array('conditions' => array('library_download_limit > library_current_downloads','id' => $libId)));
        if($results > 0)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
    
    function checkPatronDownload($patId,$libId)
    {
        $downloadInstance = ClassRegistry::init('Download');
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $libraryResults = $libraryInstance->find('all',array('conditions' => array('Library.id' => $libId)));        
        $patronLimit = $libraryResults['0']['Library']['library_user_download_limit'];
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-(date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))-1), date('Y')))." 00:00:00";
        $endDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+(7-date('w', mktime(0, 0, 0, date('m'), date('d'), date('Y')))), date('Y')))." 23:59:59";              
        $results = $downloadInstance->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN "'.$startDate.'" and "'.$endDate.'" ')));        
        if($results < $patronLimit)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
}
?>