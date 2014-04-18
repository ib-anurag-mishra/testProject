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
    
    function getImage($id, $provider = null) {
        if($provider == null) {
        	$conditions = array('Album.ProdID' => $id);
 		}
         else {
         	$conditions = array('Album.ProdID' => $id,'Album.provider_type'=>$provider);
 		}
        $songInstance = ClassRegistry::init('Album');
        $details = $songInstance->find('all', array(
           'conditions'=>$conditions,
          )
        );       
        return  $details;
    }
}

?>
