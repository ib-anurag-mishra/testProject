<?php

class MetadataHelper extends AppHelper {
    var $uses = array('Metadata');
    
    function getArtistDetails($artist) {
        $metadataInstance = ClassRegistry::init('Metadata');                
        $metadataInstance->recursive = 1;
        $metadataDetails = $metadataInstance->find('first', array('conditions' => array('Artist' => $artist)));
        return $metadataDetails;
    }
}

?>