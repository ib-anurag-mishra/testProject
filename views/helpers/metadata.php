<?php

class MetadataHelper extends AppHelper {
    var $uses = array('Metadata');
    
    function getArtistDetails($artist) {
        $metadataInstance = ClassRegistry::init('Metadata');
        $metadataInstance->Behaviors->attach('Containable');
        $metadataInstance->recursive = 2;
        $metadataDetails = $metadataInstance->find('first', array('conditions' =>
                                                                    array('Artist' => $artist),                                                       
                                                                  'contain' => array(
                                                                        'Genre' => array(
							'fields' => array(
								'Genre.Genre'								
								)),
                                                         'Physicalproduct' => array(
							'fields' => array(
								'Physicalproduct.ArtistText'								
								)
							))));        
        return $metadataDetails;
    }
}

?>