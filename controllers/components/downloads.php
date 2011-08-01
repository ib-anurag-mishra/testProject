<?php
 /*
 File Name : downloads.php
 File Description : Component page for the  download functionality.
 Author : m68interactive
 */
 
Class DownloadsComponent extends Object
{
    var $components = array('Session');
    
    /*
     Function Name : checkLibraryDownload
     Desc : function used for checking library downloads
    */
    function checkLibraryDownload($libId) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $results = $libraryInstance->find('count',array('conditions' => array('library_download_limit > library_current_downloads','id' => $libId,'library_available_downloads > 0')));
        if($results > 0) {
            return 1;
        }
        else {
            return 0;
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
            return 1;
        }
        else {
            return 0;
        }
    }
}
?>