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
    	$songInstance = ClassRegistry::init('Album');
        /*if($provider == null) {
        	$conditions = array('Album.ProdID' => $id);
 		}
         else {
         	$conditions = array('Album.ProdID' => $id,'Album.provider_type'=>$provider);
 		}

        $details = $songInstance->find('all', array(
           'conditions'=>$conditions,
          )
        );*/

    	if ( $provider == null ) {
    		$conditions = ' Album.ProdID =' . $id;
    	} else {
    		$conditions = ' Album.ProdID =' . $id . ' AND Album.provider_type =\'' . $provider .'\'';
    	}

    	$sql = "SELECT
    	`Album`.`ProdID`,
    	`Album`.`ProductID`,
    	`Album`.`AlbumTitle`,
    	`Album`.`Title`,
    	`Album`.`ArtistText`,
    	`Album`.`Artist`,
    	`Album`.`ArtistURL`,
    	`Album`.`Label`,
    	`Album`.`Copyright`,
    	`Album`.`Advisory`,
    	`Album`.`FileID`,
    	`Album`.`DownloadStatus`,
    	`Album`.`TrackBundleCount`,
    	`Album`.`UPC`,
    	`Album`.`PublicationStatus`,
    	`Album`.`LastUpdated`,
    	`Album`.`StatusNotes`,
    	`Album`.`PublicationDate`,
    	`Album`.`provider_type`,
    	`Files`.`FileID`,
    	`Files`.`SourceURL`,
    	`Files`.`HostURL`,
    	`Files`.`SaveAsName`,
    	`Files`.`DigitalSignature`,
    	`Files`.`CdnPath`,
    	`Genre`.`ProdID`,
    	`Genre`.`Genre`,
    	`Genre`.`expected_genre`,
    	`Genre`.`Subgenre`,
    	`Genre`.`GenreId`,
    	`Genre`.`provider_type`,
    	`Country`.`ProdID`,
    	`Country`.`Territory`,
    	`Country`.`SalesDate`,
    	`Country`.`TerritoryId`,
    	`Country`.`provider_type`
    	FROM
    	`Albums` AS `Album`
    	LEFT JOIN
    	`File` AS `Files`
    	ON (
    	`Album`.`FileID` = `Files`.`FileID`
    	)
    	LEFT JOIN
    	`Genre` AS `Genre`
    	ON (
    	`Genre`.`ProdID` = `Album`.`ProdID`
    	)
    	LEFT JOIN
    	`us_countries` AS `Country`
    	ON (
    	`Country`.`ProdID` = `Album`.`ProdID`
    	)
    	WHERE
    	`Album`.`ProdID` = $id
    	AND `Album`.`provider_type` = '$provider'";
    	$details = $songInstance->query($sql);
    	return  $details;
    }
}

?>
