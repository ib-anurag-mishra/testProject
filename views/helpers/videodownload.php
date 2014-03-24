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
           return $videodownloadInstance->checkVideoDownloadStatus($prodId, $provider_type, $libId ,  $patID, $startDate, $endDate );            
        }
    }

}

?>