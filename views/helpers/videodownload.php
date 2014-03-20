<?php

/*
 File Name : videodownload.php
File Description : helper file for getting download details
Author : m68interactive
*/

class VideodownloadHelper extends AppHelper
{

	var $uses = array('Videodownload');
	var $helpers = array('Session');

	function getVideodownloadfind($prodId, $provider_type, $libId, $patID, $startDate, $endDate)
	{
		$videodownloadCountArray = array();

		$videodownloadInstance = ClassRegistry::init('Videodownload');

		$videodownloadInstance->recursive = -1;

		if ($this->Session->check('videodownloadCountArray'))
		{
			$videodownloadCountArray = $this->Session->read('videodownloadCountArray');
			if (isset($videodownloadCountArray[$prodId]) && $videodownloadCountArray[$prodId]['provider_type'] == $provider_type)
			{
				return $videodownloadCountArray[$prodId]['totalProds'];
			}
			return 0;
		}
		else
		{
			$videodownloadCount = $videodownloadInstance->find(
					'all', array(
							'fields' => array('DISTINCT ProdID , provider_type, COUNT(DISTINCT id) AS totalProds'),
							'conditions' => array(
									'library_id' => $libId,
									'patron_id' => $patID,
									'history < 2',
									'created BETWEEN ? AND ?' => array($startDate, $endDate)
							),
							'group' => 'ProdID',
					));
			foreach ($videodownloadCount as $key => $value)
			{
				$videodownloadCountArray[$value['Videodownload']['ProdID']] = array(
						'provider_type' => $value['Videodownload']['provider_type'],
						'totalProds' => $value[0]['totalProds']
				);
			}
			$this->Session->write('videodownloadCountArray', $videodownloadCountArray);
			return $videodownloadCountArray[$prodId]['totalProds'];
		}

	}

}

?>