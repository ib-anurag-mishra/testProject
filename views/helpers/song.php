<?php
/*
 File Name : song.php
File Description : helper file for getting songs detail
Author : m68interactive
*/
class SongHelper extends AppHelper {
	var $uses = array('Song');

	function getDownloadData($id, $provider) {
		$songInstance = ClassRegistry::init('Song');
		$details = $songInstance->find('all', array(
				'conditions'=>array('Song.ProdID' => $id, 'Song.provider_type' => $provider),
				'fields' => array(
						'Song.ProdID',
						'Song.ProductID',
						'Song.Title',
						'Song.SongTitle',
						'Song.Artist',
						'Song.ISRC'
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


	function getSongDurationTime($totalTime)
	{
		if(strlen($totalTime)>5)
		{
			$totalTime =  ltrim(date('i:s', strtotime($totalTime)), 0);;
		}

		return $totalTime;

	}
}

?>