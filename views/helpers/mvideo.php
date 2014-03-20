<?php
/*
 File Name : song.php
File Description : helper file for getting songs detail
Author : m68interactive
*/
class MvideoHelper extends AppHelper {
	var $uses = array('Song');

	function getDownloadData($id, $provider) {
		$videoInstance = ClassRegistry::init('Video');
		$details = $videoInstance->find('all', array(
				'conditions'=>array('Video.ProdID' => $id, 'Video.provider_type' => $provider),
				'fields' => array(
						'Video.ProdID',
						'Video.ProductID',
						'Video.Title',
						'Video.VideoTitle',
						'Video.Artist',
						'Video.ISRC'
				),
				'contain' => array(
						'Full_Files' => array(
								'fields' => array(
										'Full_Files.CdnPath',
										'Full_Files.SaveAsName'
								),
						)
				)));
		return  $details;
	}
}

?>