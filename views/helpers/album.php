<?php

class AlbumHelper extends AppHelper {
    var $uses = array('Album');
    
    function getAlbum($id) {
        $songInstance = ClassRegistry::init('Album');
	 $songInstance->recursive = -1;
        $details = $songInstance->find('all', array(
				'conditions'=>array('Album.ProdID' => $id),
				'fields' => array(
					'Album.ProdID',
					'Album.AlbumTitle'
				)
			)
		);
        return  $details;
    }
    
    function getImage($id) {
        echo $id;        
        $songInstance = ClassRegistry::init('Album');
        $details = $songInstance->find('all', array(
            'conditions'=>array('Album.ProdID' => $id),
          )
        );
        print_r($details);
        die;
        return  $details;
    }
}

?>