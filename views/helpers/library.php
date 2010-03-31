<?php

class LibraryHelper extends AppHelper {
    var $uses = array('Library');
    
    function getLibraryDetails($id) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $libraryDetails = $libraryInstance->find('first', array('conditions' => array('id' => $id)));
        return $libraryDetails;
    }
}

?>