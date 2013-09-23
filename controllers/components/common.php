<?php
 /*
 File Name : common.php
 File Description : Component page for all functionalities.
 Author : m68interactive
 */
 
Class CommonComponent extends Object
{
    var $components = array('Session');
    
    /*
     * Function Name : getGenres
     * Function Description : This function is used to get all genres.
     */
    
    function getGenres($territory){
        set_time_limit(0);
        $this->getCountryPrefix();
        $genreInstance = ClassRegistry::init('Genre');
        $genreInstance->Behaviors->attach('Containable');
        $genreInstance->recursive = 2;
        $genreAll = $genreInstance->find('all',array(
                                'conditions' =>
                                        array('and' =>
                                                array(
                                                        array('Country.Territory' => $territory, "Genre.Genre NOT IN('Porn Groove')")
                                                )
                                        ),
                                'fields' => array(
                                                'Genre.Genre'
                                                ),
                                'contain' => array(
                                        'Country' => array(
                                                        'fields' => array(
                                                                        'Country.Territory'
                                                                )
                                                        ),
                                ),'group' => 'Genre.Genre'
                        ));

          $this->log("cache written for genre for $territory",'debug');      

          if( (count($genreAll) > 0) && ($genreAll !== false) )
          {
            Cache::delete("genre".$territory);
            Cache::write("genre".$territory, $genreAll);
            $this->log( "cache written for genre for $territory", "cache");
            echo "cache written for genre for $territory";
          }
          else
          {                                  

            Cache::write("genre".$territory, Cache::read("genre".$territory) );
            $this->log( "no data available for genre".$territory, "cache");
            echo "no data available for genre".$territory;
          }        
    }
    
    /*
     * Function Name : getNationalTop100
     * Function Description : This function gets data of national top 100
     */
    
    function getNationalTop100($territory){
        $countryPrefix = $this->getCountryPrefix();        
        $country = $territory;
        if(!empty($country)){
          $maintainLatestDownload =  $this->Session->read('maintainLatestDownload');
          if($maintainLatestDownload){

                    $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `latest_downloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 110";
                } else {
                    $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `downloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 110";
            }
            $ids = '';
            $ids_provider_type = '';
            $albumInstance = ClassRegistry::init('Album');
            $natTopDownloaded = $albumInstance->query($sql);
            foreach ($natTopDownloaded as $natTopSong) {
                if (empty($ids)) {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                } else {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($natTopDownloaded) < 1) || ($natTopDownloaded === false)) {
                $this->log("download data not recevied for " . $territory, "cache");
                echo "download data not recevied for " . $territory;
            }
            $data = array();

            $sql_national_100 = <<<STR
                SELECT 
                        Song.ProdID,
                        Song.ReferenceID,
                        Song.Title,
                        Song.ArtistText,
                        Song.DownloadStatus,
                        Song.SongTitle,
                        Song.Artist,
                        Song.Advisory,
                        Song.Sample_Duration,
                        Song.FullLength_Duration,
                        Song.provider_type,
                        Genre.Genre,
                        Country.Territory,
                        Country.SalesDate,
                        Sample_Files.CdnPath,
                        Sample_Files.SaveAsName,
                        Full_Files.CdnPath,
                        Full_Files.SaveAsName,
                        File.CdnPath,
                        File.SourceURL,
                        File.SaveAsName,
                        Sample_Files.FileID,
                        PRODUCT.pid
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                LEFT JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        ( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) ) AND 1 = 1
                GROUP BY Song.ProdID
                ORDER BY FIELD(Song.ProdID,$ids) ASC
                LIMIT 100 

STR;
            $data = $albumInstance->query($sql_national_100);
            $this->log("National top 100 songs for " . $territory, "cachequery");
            $this->log($sql_national_100, "cachequery");
            if ($ids_provider_type == "") {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
                echo "ids_provider_type is set blank for " . $territory;
            }

            if (!empty($data)) {
                Cache::delete("national" . $country);
                foreach($data as $key => $value){
                        $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                        $songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                        $data[$key]['songAlbumImage'] = $songAlbumImage;
                }                    
                Cache::write("national" . $country, $data);
                $this->log("cache written for national top ten for $territory", "cache");
                echo "cache written for national top ten for $territory";
            } else {

                Cache::write("national" . $country, Cache::read("national" . $country));
                echo "Unable to update key";
                $this->log("Unable to update national 100 for " . $territory, "cache");
                echo "Unable to update national 100 for " . $territory;
            }
        }
        $this->log("cache written for national top 100 for $territory", 'debug');        
        
        
    }
    
    /**
     * Function Name : getCountryPrefix
     * Function Description : This function is used to get the country prefix
     */
    
    function getCountryPrefix(){
        if ((Cache::read('multipleCountries')) === false) {
            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
            $albumInstance = ClassRegistry::init('Album');
            $siteConfigData = $albumInstance->query($siteConfigSQL);
            $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
            Cache::write("multipleCountries", $multiple_countries);                    
        }else{
            $multiple_countries = Cache::read('multipleCountries');
        }
        $countryInstance = ClassRegistry::init('Country');
        if(0 == $multiple_countries){
            $countryPrefix = '';
            $countryInstance->setTablePrefix('');

        } else {
            $countryPrefix = strtolower($territory)."_";
            $countryInstance->setTablePrefix($countryPrefix);
        }
        return $countryPrefix;
    }           

}
?>