<?php

/*
  File Name : common.php
  File Description : Component page for all functionalities.
  Author : m68interactive
 */

Class CommonComponent extends Object
{

    var $components = array('Session', 'Streaming', 'Queue');
    var $uses = array('Token');

    /*
     * Function Name : getGenres
     * Function Description : This function is used to get all genres.
     */

    function getGenres($territory)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);
        $genreInstance = ClassRegistry::init('Genre');
        $genreInstance->Behaviors->attach('Containable');
        $genreInstance->recursive = 2;
        $genreAll = $genreInstance->find('all', array(
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
            ), 'group' => 'Genre.Genre'
        ));

        $this->log("cache written for genre for $territory", 'debug');

        if ((count($genreAll) > 0) && ($genreAll !== false))
        {
            Cache::delete("genre" . $territory);
            Cache::write("genre" . $territory, $genreAll);
            $this->log("cache written for genre for $territory", "cache");
        }
        else
        {
            Cache::write("genre" . $territory, Cache::read("genre" . $territory));
            $this->log("no data available for genre" . $territory, "cache");
        }
    }

    /*
     * Function Name : getNationalTop100
     * Function Description : This function gets data of national top 100
     */

    function getNationalTop100($territory)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {

                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `latest_downloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 110";
            }
            else
            {
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
            foreach ($natTopDownloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($natTopDownloaded) < 1) || ($natTopDownloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
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
                        Country.StreamingSalesDate,
                        Country.StreamingStatus,
                        Country.DownloadStatus,
                        Sample_Files.CdnPath,
                        Sample_Files.SaveAsName,
                        Full_Files.CdnPath,
                        Full_Files.SaveAsName,
                        File.CdnPath,
                        File.SourceURL,
                        File.SaveAsName,
                        Sample_Files.FileID,
                        PRODUCT.pid,
                        Albums.ProdID,
                        Albums.provider_type
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                INNER JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        (Song.ProdID, Song.provider_type) IN ($ids_provider_type) AND 1 = 1
                GROUP BY Song.ProdID
                ORDER BY FIELD(Song.ProdID,$ids) ASC
                LIMIT 75 

STR;
            $data = $albumInstance->query($sql_national_100);
            $this->log("National top 100 songs for " . $territory, "cachequery");
            $this->log($sql_national_100, "cachequery");
            if ($ids_provider_type == "")
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }
            if (!empty($data))
            {   
                foreach ($data as $key => $value)
                {      
                    
                    $albumArtwork = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $songAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['songAlbumImage'] = $songAlbumImage;
                }
                Cache::delete("national" . $country,"cache2");
                Cache::write("national" . $country, $data, "cache2");
                $this->log("cache written for national top 100 songs for $territory", "cache");
            }
            else
            {
                $data = Cache::read("national" . $country, "cache2");
                Cache::write("national" . $country, $data, "cache2");
                $this->log("Unable to update national 100 for " . $territory, "cache");
            }
        }
        $this->log("cache written for national top 100 for $territory", 'debug');
        return $data;
    }

    /*
     * Function Name : getNationalTop100Albums
     * Function Description : This function gets data of national top 100 Albums
     */

    function getNationalTop100Albums($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {

                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `latest_downloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 400";
            }
            else
            {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `downloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 400";
            }
            $ids = '';
            $ids_provider_type = '';
            $albumInstance = ClassRegistry::init('Album');
            $natTopDownloaded = $albumInstance->query($sql);
            foreach ($natTopDownloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($natTopDownloaded) < 1) || ($natTopDownloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
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
			Song.Genre,
                        Genre.Genre,
                        Country.Territory,
                        Country.SalesDate,
                        Country.StreamingSalesDate,
                        Country.StreamingStatus,
                        Country.DownloadStatus,
                        Sample_Files.CdnPath,
                        Sample_Files.SaveAsName,
                        Full_Files.CdnPath,
                        Full_Files.SaveAsName,
                        File.CdnPath,
                        File.SourceURL,
                        File.SaveAsName,
                        Sample_Files.FileID,
                        PRODUCT.pid,
                        Albums.ProdID,
                        Albums.provider_type,
			Albums.AlbumTitle,
                        Albums.Advisory
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                LEFT JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        (Song.ProdID, Song.provider_type) IN ($ids_provider_type) AND 1 = 1
                GROUP BY Song.ReferenceID
                ORDER BY COUNT(Song.ReferenceID) DESC
                LIMIT 100 

STR;
            $data = $albumInstance->query($sql_national_100);
            $this->log("National top 100 Albums for " . $territory, "cachequery");
            $this->log($sql_national_100, "cachequery");
            if ($ids_provider_type == "")
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }
            if (!empty($data))
            {

                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $songAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['songAlbumImage'] = $songAlbumImage;
                    $albumSongs = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type']),0,$country))
                    );
                    if(!empty($albumSongs[$value['Albums']['ProdID']])){
                        $data[$key]['albumSongs'] = 1;
                    }else{
                        $data[$key]['albumSongs'] = 0;
                    }
                    if(!empty($albumSongs[$value['Albums']['ProdID']])){
                        Cache::write("nationaltopalbum_" . $territory.'_'.$value['Albums']['ProdID'], $albumSongs);
                        $this->log("cache written for national top album for $territory".$prodId, "cache");
                    }
                }

                Cache::write("nationaltop100albums" . $country, $data);
                $this->log("cache written for national top 100 albums for $territory", "cache");
            }
            else
            {
                $data = Cache::read("nationaltop100albums" . $country);
                Cache::write("nationaltop100albums" . $country, $data);
                $this->log("Unable to update national 100 albums for " . $territory, "cache");
            }
        }
        $this->log("cache written for national top 100 albums for $territory", 'debug');
        return $data;
    }

    /*
     * Function Name : getFeaturedVideos
     * Function Description : This function get featured videos
     */

    function getFeaturedVideos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        // Added caching functionality for featured videos
        $featured_videos_sql = "SELECT `FeaturedVideo`.`id`,`FeaturedVideo`.`ProdID`,`Video`.`Image_FileID`, `Video`.`VideoTitle`, `Video`.`ArtistText`, 
            `Video`.`provider_type`,`Video`.`Advisory`, `File`.`CdnPath`, `File`.`SourceURL`, `File`.`SaveAsName`, Video_file.SaveAsName,`Country`.`SalesDate` 
            FROM featured_videos as FeaturedVideo 
            LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID  and FeaturedVideo.provider_type = Video.provider_type 
            LEFT JOIN File as File on File.FileID = Video.Image_FileID 
            LEFT JOIN File as Video_file on (Video_file.FileID = Video.FullLength_FileID)
            LEFT JOIN {$countryPrefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) 
                WHERE `FeaturedVideo`.`territory` = '" . $territory . "' AND `Country`.`SalesDate` <= NOW()";

        $this->log("featured videos $territory", "cachequery");
        $this->log($featured_videos_sql, "cachequery");

        $featuredVideos = $albumInstance->query($featured_videos_sql);
        if (!empty($featuredVideos))
        {
            foreach ($featuredVideos as $key => $featureVideo)
            {                
                $videoArtwork = $tokeninstance->artworkToken($featureVideo['File']['CdnPath'] . "/" . $featureVideo['File']['SourceURL']);

                $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                $featuredVideos[$key]['videoImage'] = $videoImage;
            }
            Cache::write("featured_videos" . $territory, $featuredVideos);
            $this->log("cache written for featured videos for $territory", "cache");
        }
        else
        {
            $featuredVideos = Cache::read("featured_videos" . $territory);
            Cache::write("featured_videos" . $territory, $featuredVideos);
            $this->log("Unable to update featured videos cache for " . $territory, "cache");
        }

        // End Caching functionality for featured videos
        return $featuredVideos;
    }

    /*
     * Function Name : getTopVideoDownloads
     * Function Description : This function gets top videos downloaded
     */

    function getTopVideoDownloads($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        // Added caching functionality for top video downloads
        $topDownloadSQL = "SELECT Videodownloads.ProdID, Video.ProdID, Video.provider_type, Video.VideoTitle, Video.ArtistText, Video.Advisory,File.CdnPath, 
            File.SourceURL, File.SaveAsName ,  Video_file.SaveAsName, COUNT(DISTINCT(Videodownloads.id)) AS COUNT, `Country`.`SalesDate` 
            FROM videodownloads as Videodownloads 
            LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) 
            LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) 
            LEFT JOIN File as Video_file on (Video_file.FileID = Video.FullLength_FileID)
            LEFT JOIN {$countryPrefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) 
                WHERE `Country`.`SalesDate` <= NOW() AND Video.DownloadStatus = '1' GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC limit 100";

        $this->log("Top video downloads $territory", "cachequery");
        $this->log($topDownloadSQL, "cachequery");

        $topDownloads = $albumInstance->query($topDownloadSQL);
        if (!empty($topDownloads))
        {
            foreach ($topDownloads as $key => $topDownload)
            {                
                $videoArtwork = $tokeninstance->artworkToken($topDownload['File']['CdnPath'] . "/" . $topDownload['File']['SourceURL']);
                $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                $topDownloads[$key]['videoImage'] = $videoImage;
            }
            Cache::write("top_download_videos" . $territory, $topDownloads);
            $this->log("cache written for top download   videos for $territory", "cache");
        }
        else
        {
            $topDownloads = Cache::read("top_download_videos" . $territory);
            Cache::write("top_download_videos" . $territory, $topDownloads);
            $this->log("Unable to update top download  videos cache for " . $territory, "cache");
        }
        // End Caching functionality for top video downloads
        return $topDownloads;
    }

    /*
     * Function Name : getNationalTop100Videos
     * Function Description : This function gets national top 100 videos
     */

    function getNationalTop100Videos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        // Added caching functionality for national top 100 videos   
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {

                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
          FROM `latest_videodownloads` AS `Download` 
          LEFT JOIN libraries ON libraries.id=Download.library_id
          WHERE libraries.library_territory = '" . $country . "' 
          AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
          GROUP BY Download.ProdID 
          ORDER BY `countProduct` DESC 
          LIMIT 110";
            }
            else
            {

                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
          FROM `videodownloads` AS `Download` 
          LEFT JOIN libraries ON libraries.id=Download.library_id
          WHERE libraries.library_territory = '" . $country . "' 
          AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
          GROUP BY Download.ProdID 
          ORDER BY `countProduct` DESC 
          LIMIT 110";
            }

            $this->log("national top 100 videos first query for $territory", "cachequery");
            $this->log($sql, "cachequery");

            $ids = '';
            $ids_provider_type = '';
            $natTopDownloaded = $albumInstance->query($sql);

            foreach ($natTopDownloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($natTopDownloaded) < 1) || ($natTopDownloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }


            $data = array();

            $sql_national_100_v = <<<STR
            SELECT 
                            Video.ProdID,
                            Video.ReferenceID,
                            Video.Title,
                            Video.ArtistText,
                            Video.DownloadStatus,
                            Video.VideoTitle,
                            Video.Artist,
                            Video.Advisory,
                            Video.Sample_Duration,
                            Video.FullLength_Duration,
                            Video.provider_type,
                            Genre.Genre,
                            Country.Territory,
                            Country.SalesDate,
                            Full_Files.CdnPath,
                            Full_Files.SaveAsName,
                            Full_Files.FileID,
                            Image_Files.FileID,
                            Image_Files.CdnPath,
                            Image_Files.SourceURL,
                            PRODUCT.pid
            FROM
                            video AS Video
                                            LEFT JOIN
                            File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
                                            LEFT JOIN
                            Genre AS Genre ON (Genre.ProdID = Video.ProdID) AND (Video.provider_type = Genre.provider_type)
                                            LEFT JOIN
            {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type) AND Country.SalesDate != '' AND Country.SalesDate < NOW()
                                            LEFT JOIN
                            PRODUCT ON (PRODUCT.ProdID = Video.ProdID) AND (PRODUCT.provider_type = Video.provider_type)
            LEFT JOIN
                            File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
            WHERE
                            ( (Video.DownloadStatus = '1') AND ((Video.ProdID, Video.provider_type) IN ($ids_provider_type))  )   AND 1 = 1
            GROUP BY Video.ProdID
            ORDER BY FIELD(Video.ProdID, $ids) ASC
            LIMIT 100 
STR;

            $data = $albumInstance->query($sql_national_100_v);
            $this->log("national top 100 videos second query for $territory", "cachequery");
            $this->log($sql_national_100_v, "cachequery");



            if ($ids_provider_type == "")
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }

            if (!empty($data))
            {
                Cache::delete("nationalvideos" . $country);
                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
                Cache::write("nationalvideos" . $country, $data);
                $this->log("cache written for national top ten  videos for $territory", "cache");
            }
            else
            {
                $data = Cache::read("nationalvideos" . $country);
                Cache::write("nationalvideos" . $country, $data);
                $this->log("Unable to update national 100  videos for " . $territory, "cache");
            }
        }
        $this->log("cache written for national top ten  videos for $territory", 'debug');
        // End Caching functionality for national top 10 videos
        return $data;
    }

    /*
     * Function Name : getComingSoonSongs
     * Function Description : This function is used to get all coming soon songs.
     */

    function getComingSoonSongs($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        
        if(empty($countryPrefix))
        {
            $this->log("Empty countryPrefix in getComingSoonSongs for : ".$territory, "cache");
            exit;
        }
        $albumInstance = ClassRegistry::init('Album');
        // Added caching functionality for coming soon songs
        $sql_coming_soon_s = <<<STR
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
              Country.DownloadStatus,
              File.CdnPath,
              File.SourceURL,
              File.SaveAsName
            FROM
            Songs AS Song
              LEFT JOIN Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND  (Song.provider_type = Genre.provider_type)
              LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) 
              INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) 
              INNER JOIN File ON (Albums.FileID = File.FileID) 
            WHERE
            ( (Country.DownloadStatus = '1')  )   AND 1 = 1 AND (Country.Territory = '$territory') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate > NOW())
            GROUP BY Song.ReferenceID
            ORDER BY Country.SalesDate ASC
            LIMIT 20      
STR;

        $coming_soon_rs = $albumInstance->query($sql_coming_soon_s);

        $this->log("coming soon songs $territory", "cachequery");
        $this->log($sql_coming_soon_s, "cachequery");


        if (!empty($coming_soon_rs))
        {
            foreach ($coming_soon_rs as $key => $value)
            {                
                $cs_img_url = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $cs_songImage = Configure::read('App.Music_Path') . $cs_img_url;
                $coming_soon_rs[$key]['cs_songImage'] = $cs_songImage;
            }
            Cache::delete("coming_soon_songs" . $territory);
            Cache::write("coming_soon_songs" . $territory, $coming_soon_rs);
            $this->log("cache written for coming soon songs for $territory", "cache");
        }
        else
        {
            $coming_soon_rs = Cache::read("coming_soon_songs" . $territory);
            Cache::write("coming_soon_songs" . $territory, $coming_soon_rs);
            $this->log("Unable to update coming soon songs for " . $territory, "cache");
        }

        $this->log("cache written for coming soon for $territory", 'debug');
        // End Caching functionality for coming soon songs
        return $coming_soon_rs;
    }

    /*
     * Function Name : getComingSoonVideos
     * Function Description : This function is used to get all coming soon Videos.
     */

    function getComingSoonVideos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        
        if(empty($countryPrefix))
        {
            $this->log("Empty countryPrefix in getComingSoonVideos for : ".$territory, "cache");
            exit;
        }

        
        $albumInstance = ClassRegistry::init('Video');
        // Added caching functionality for coming soon videos
        $sql_coming_soon_v = <<<STR
	SELECT 
        Video.ProdID,
        Video.ReferenceID,
        Video.Title,
        Video.ArtistText,
        Video.DownloadStatus,
        Video.VideoTitle,
        Video.Artist,
        Video.Advisory,
        Video.Sample_Duration,
        Video.FullLength_Duration,
        Video.provider_type,
        Genre.Genre,
        Country.Territory,
        Country.SalesDate,
        Image_Files.FileID,
        Image_Files.CdnPath,
        Image_Files.SourceURL
    FROM
        video AS Video
    LEFT JOIN
        Genre AS Genre ON (Genre.ProdID = Video.ProdID) AND (Video.provider_type = Genre.provider_type)
    LEFT JOIN
        {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Video.provider_type = Country.provider_type)
    LEFT JOIN
        File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
    WHERE
        ( (Video.DownloadStatus = '1')) AND (Country.Territory = '$territory')  AND (Country.SalesDate != '') AND (Country.SalesDate > NOW())
    ORDER BY Country.SalesDate ASC
    LIMIT 20
STR;

        $coming_soon_rv = $albumInstance->query($sql_coming_soon_v);
        $this->log("coming soon videos $territory", "cachequery");
        $this->log($sql_coming_soon_v, "cachequery");

        if (!empty($coming_soon_rv))
        {
            foreach ($coming_soon_rv as $key => $value)
            {                
                $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                $coming_soon_rv[$key]['videoAlbumImage'] = $videoAlbumImage;
            }
            Cache::write("coming_soon_videos" . $territory, $coming_soon_rv);
            $this->log("cache written for coming soon videos for $territory", "cache");
        }
        else
        {
            $coming_soon_rv = Cache::read("coming_soon_videos" . $territory);
            Cache::write("coming_soon_videos" . $territory, $coming_soon_rv);
            $this->log("Unable to update coming soon videos for " . $territory, "cache");
        }

        $this->log("cache written for coming soon videos for $territory", 'debug');
        //End Caching functionality for coming soon songs
        return $coming_soon_rv;
    }

    /*
     * Function Name : getUsTop10Songs
     * Function Description : This function is used to get Us Top 10 Albums.
     */

    function getUsTop10Songs($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Songs           
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                FROM `latest_downloads` AS `Download` 
                LEFT JOIN libraries ON libraries.id=Download.library_id
                WHERE libraries.library_territory = '" . $country . "' 
                AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                GROUP BY Download.ProdID 
                ORDER BY `countProduct` DESC 
                LIMIT 110";
            }
            else
            {
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
            $USTop10Downloaded = $albumInstance->query($sql);
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }
            $data = array();

            $sql_US_TOP_10 = <<<STR
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
                    Albums.ProdID,
                    Albums.provider_type,
                    Albums.Advisory,
                    Genre.Genre,
                    Country.Territory,
                    Country.SalesDate,
                    Country.StreamingSalesDate,
                    Country.StreamingStatus,
                    Country.DownloadStatus,                    
                    Sample_Files.CdnPath,
                    Sample_Files.SaveAsName,
                    Full_Files.CdnPath,
                    Full_Files.SaveAsName,
                    File.CdnPath,
                    File.SourceURL,
                    File.SaveAsName,
                    Sample_Files.FileID
            FROM Songs AS Song
            LEFT JOIN File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
            LEFT JOIN File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
            LEFT JOIN Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type)
            LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type)
            INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) 
            INNER JOIN File ON (Albums.FileID = File.FileID) 
            WHERE ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
            GROUP BY Song.ProdID
            ORDER BY FIELD(Song.ProdID,$ids) ASC
            LIMIT 10
STR;
            $data = $albumInstance->query($sql_US_TOP_10);
            $this->log("US top 10 songs for $territory", "cachequery");

            $this->log($sql_US_TOP_10, "cachequery");
            if ($ids_provider_type == "")
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }

            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {                         
                    $songs_img = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                    $songs_img = Configure::read('App.Music_Path') . $songs_img;
                    $data[$key]['songs_img'] = $songs_img;
                                        
                    $filePath = $tokeninstance->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);

                    if (!empty($filePath))
                    {
                        $songPath = explode(':', $filePath);
                        $streamUrl = trim($songPath[1]);
                        $data[$key]['streamUrl'] = $streamUrl;
                        $data[$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                    }
                }
                Cache::delete("national_us_top10_songs" . $country);
                Cache::write("national_us_top10_songs" . $country, $data);
                $this->log("cache written for US top ten for $territory", "cache");
            }
            else
            {
                $data = Cache::read("national_us_top10_songs" . $country);
                Cache::write("national_us_top10_songs" . $country, $data);
                $this->log("Unable to update US top ten for " . $territory, "cache");
            }
        }
        $this->log("cache written for US top ten for $territory", 'debug');
        //End Caching functionality for US TOP 10 Songs
        return $data;
    }

    /*
     * Function Name : getUsTop10Albums
     * Function Description : This function is used to get Us Top 10 Albums.
     */

    function getUsTop10Albums($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Album            
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                   FROM `latest_downloads` AS `Download` 
                   LEFT JOIN libraries ON libraries.id=Download.library_id
                   WHERE libraries.library_territory = '" . $country . "' 
                   AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                   GROUP BY Download.ProdID 
                   ORDER BY `countProduct` DESC 
                   LIMIT 110";
            }
            else
            {
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
            $USTop10Downloaded = $albumInstance->query($sql);
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }
            $data = array();

            $album_sql_US_TOP_10 = <<<STR
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
                   Albums.ProdID,
                   Albums.provider_type,  
                   Albums.AlbumTitle,
                   Albums.Advisory,
                   Genre.Genre,
                   Country.Territory,
                   Country.SalesDate,
                   Country.StreamingSalesDate,
                   Country.StreamingStatus,
                   Country.DownloadStatus,
                   Sample_Files.CdnPath,
                   Sample_Files.SaveAsName,
                   Full_Files.CdnPath,
                   Full_Files.SaveAsName,
                   File.CdnPath,
                   File.SourceURL,
                   File.SaveAsName,
                   Sample_Files.FileID
           FROM Songs AS Song
           LEFT JOIN File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
           LEFT JOIN File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
           LEFT JOIN Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
           LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type)
           INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) 
           INNER JOIN File ON (Albums.FileID = File.FileID) 
           WHERE ( (Country.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) )  AND (Country.Territory = '$country')  AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
           GROUP BY  Song.ReferenceID
           ORDER BY count(Song.ProdID) DESC
           LIMIT 10  
STR;
            $data = $albumInstance->query($album_sql_US_TOP_10);
            $this->log("US top 10 album for $territory", "cachequery");

            $this->log($album_sql_US_TOP_10, "cachequery");
            if ($ids_provider_type == "")
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }

            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {
                    $album_img = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);                    
                    $album_img = Configure::read('App.Music_Path') . $album_img;
                    $data[$key]['album_img'] = $album_img;
                    $data[$key]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type']),0,$country))
                    );
                }
                Cache::delete("national_us_top10_albums" . $country);
                Cache::write("national_us_top10_albums" . $country, $data);
                $this->log("cache written for US top ten Album for $territory", "cache");
            }
            else
            {
                $data = Cache::read("national_us_top10_albums" . $country);
                Cache::write("national_us_top10_albums" . $country, $data);
                $this->log("Unable to update US top ten Album for " . $territory, "cache");
            }
        }
        $this->log("cache written for US top ten Album for $territory", 'debug');
        //End Caching functionality for US TOP 10 Albums
        return $data;
    }

    /*
     * Function Name : getUsTop10Videos
     * Function Description : This function is used to get Us Top 10 Videos.
     */

    function getUsTop10Videos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Video            
        $country = $territory;
        if (!empty($country))
        {
            $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
            if ($maintainLatestDownload)
            {
                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                     FROM `latest_videodownloads` AS `Download` 
                     LEFT JOIN libraries ON libraries.id=Download.library_id
                     WHERE libraries.library_territory = '" . $country . "' 
                     AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                     GROUP BY Download.ProdID 
                     ORDER BY `countProduct` DESC 
                     LIMIT 110";
            }
            else
            {

                $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                     FROM `videodownloads` AS `Download` 
                     LEFT JOIN libraries ON libraries.id=Download.library_id
                     WHERE libraries.library_territory = '" . $country . "' 
                     AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                     GROUP BY Download.ProdID 
                     ORDER BY `countProduct` DESC 
                     LIMIT 110";
            }
            $ids = '';
            $ids_provider_type = '';
            $USTop10Downloaded = $albumInstance->query($sql);
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                }
            }

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }
            $data = array();
            if ($ids_provider_type != "")
            {
            $video_sql_US_TOP_10 = <<<STR
             SELECT 
                     Video.ProdID,
                     Video.ReferenceID,
                     Video.Title,
                     Video.ArtistText,
                     Video.DownloadStatus,
                     Video.VideoTitle,
                     Video.Artist,
                     Video.Advisory,
                     Video.Sample_Duration,
                     Video.FullLength_Duration,
                     Video.provider_type,
                     Genre.Genre,
                     Country.Territory,
                     Country.SalesDate,
                     Full_Files.CdnPath,
                     Full_Files.SaveAsName,
                     Full_Files.FileID,
                     Image_Files.FileID,
                     Image_Files.CdnPath,
                     Image_Files.SourceURL
             FROM video AS Video
             LEFT JOIN File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
             LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID) AND (Video.provider_type = Genre.provider_type)
             LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Video.provider_type = Country.provider_type)
             LEFT JOIN File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
             WHERE ( (Video.DownloadStatus = '1') AND ((Video.ProdID, Video.provider_type) IN ($ids_provider_type))) AND (Country.Territory = '$country') AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
             GROUP BY Video.ProdID
             ORDER BY FIELD(Video.ProdID, $ids) ASC
             LIMIT 10                   
STR;
            $data = $albumInstance->query($video_sql_US_TOP_10);
            $this->log("US top 10 videos for $territory", "cachequery");
            $this->log($video_sql_US_TOP_10, "cachequery");
            
            }
            else
            {
                $this->log("ids_provider_type is set blank for " . $territory, "cache");
            }
            
            
            
            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
                Cache::delete("national_us_top10_videos" . $country);
                Cache::write("national_us_top10_videos" . $country, $data);
                $this->log("cache written for US top ten video for $territory", "cache");
            }
            else
            {
                $data = Cache::read("national_us_top10_videos" . $country);
                Cache::write("national_us_top10_videos" . $country, $data);
                $this->log("Unable to update US top ten video for " . $territory, "cache");
            }
        }
        $this->log("cache written for US top ten video for $territory", 'debug');
        //End Caching functionality for US TOP 10 Videos
        return $data;
    }

    /*
     * Function Name : getNewReleaseAlbums
     * Function Description : This function is used to getNewReleaseAlbums.
     */

    function getNewReleaseAlbums($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $songInstance = ClassRegistry::init('Song');
        //Added caching functionality for new release Albums           
        $country = $territory;
        if (!empty($country))
        {
            $sql = "SELECT Song.ProdID,Song.ReferenceID,Song.provider_type
                FROM Songs AS Song
                LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type)
                WHERE  ( (Country.DownloadStatus = '1')) AND 1 = 1 AND (Country.Territory = '$territory') AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW())                    
                ORDER BY Country.SalesDate DESC LIMIT 10000";


            $ids = '';
            $ids_provider_type = '';
            $newReleaseSongsRec = $songInstance->query($sql);
            foreach ($newReleaseSongsRec as $newReleaseRow)
            {
                if (empty($ids))
                {
                    $ids .= $newReleaseRow['Song']['ProdID'];
                    $ids_provider_type .= "(" . $newReleaseRow['Song']['ProdID'] . ",'" . $newReleaseRow['Song']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $newReleaseRow['Song']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $newReleaseRow['Song']['ProdID'] . ",'" . $newReleaseRow['Song']['provider_type'] . "')";
                }
            }

            if ((count($newReleaseSongsRec) < 1) || ($newReleaseSongsRec === false))
            {
                $this->log("new release data not recevied for " . $territory, "cache");
            }

            $data = array();
            $sql_album_new_release = <<<STR
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
                            Albums.AlbumTitle,
                            Albums.ProdID,
                            Albums.Advisory,
                            Genre.Genre,
                            Country.Territory,
                            Country.SalesDate,
                            Country.StreamingSalesDate,
                            Country.StreamingStatus,
                            Country.DownloadStatus,
                            File.CdnPath,
                            File.SourceURL,
                            File.SaveAsName,
                            Full_Files.CdnPath,
                            Full_Files.SaveAsName,
                            Full_Files.FileID
                    FROM Songs AS Song
                    LEFT JOIN File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                    LEFT JOIN Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND  (Song.provider_type = Genre.provider_type)
                    LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type)
                    INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) 
                    INNER JOIN File ON (Albums.FileID = File.FileID) 
                    WHERE ( (Country.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)))
                        AND (Country.Territory = '$territory') AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW())                    
                    group by Song.ReferenceID
                    ORDER BY Country.SalesDate DESC
                    LIMIT 200
STR;


            $data = $songInstance->query($sql_album_new_release);
            $this->log("new release album for $territory", "cachequery");
            $this->log($sql_album_new_release, "cachequery");


            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {
                    $album_img = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);                    
                    $album_img = Configure::read('App.Music_Path') . $album_img;
                    $data[$key]['albumImage'] = $album_img;
                    $data[$key]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type']),0,$country))
                    );
                }
                Cache::delete("new_releases_albums" . $country);
                Cache::write("new_releases_albums" . $country, $data);
                $this->log("cache written for new releases albums for $territory", "cache");
            }
            else
            {
                $data = Cache::read("new_releases_albums" . $country);
                Cache::write("new_releases_albums" . $country, $data);
                $this->log("Unable to update new releases albums for " . $territory, "cache");
            }


            $this->log("cache written for new releases albums for $territory", 'debug');
            //End Caching functionality for new releases albums
            return $data;
        }
        else
        {
            $this->log("not able to  written cache for new releases albums for $territory", 'cache');
        }
    }

    /*
     * Function Name : getNewReleaseVideos
     * Function Description : This function is used to getNewReleaseVideos.
     */

    function getNewReleaseVideos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for new release videos           
        $country = $territory;
        if (!empty($country))
        {

            $data = array();
            $sql_video_new_release = <<<STR
                        SELECT 
                            Video.ProdID,
                            Video.ReferenceID,
                            Video.Title,
                            Video.ArtistText,
                            Video.DownloadStatus,
                            Video.VideoTitle,
                            Video.Artist,
                            Video.Advisory,
                            Video.Sample_Duration,
                            Video.FullLength_Duration,
                            Video.provider_type,
                            Genre.Genre,
                            Country.Territory,
                            Country.SalesDate,
                            Full_Files.CdnPath,
                            Full_Files.SaveAsName,
                            Full_Files.FileID,
                            Image_Files.FileID,
                            Image_Files.CdnPath,
                            Image_Files.SourceURL
                        FROM video AS Video
                        LEFT JOIN File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
                        LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID)
                        LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Video.provider_type = Country.provider_type)
                        LEFT JOIN File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
                        WHERE ((Video.DownloadStatus = '1')) AND (Country.Territory = '$territory') AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW()) 
                        GROUP BY Video.ProdID 
                        ORDER BY Country.SalesDate DESC 
                        LIMIT 100 
STR;

            $data = $albumInstance->query($sql_video_new_release);
            $this->log("new release album for $territory", "cachequery");
            $this->log($sql_video_new_release, "cachequery");

            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                }
                Cache::delete("new_releases_videos" . $country);
                Cache::write("new_releases_videos" . $country, $data);
                $this->log("cache written for new releases videos for $territory", "cache");
            }
            else
            {
                $data = Cache::read("new_releases_videos" . $country);
                Cache::write("new_releases_videos" . $country, $data);
                $this->log("Unable to update new releases videos for " . $territory, "cache");
            }
        }
        $this->log("cache written for new releases videos for $territory", 'debug');
        //End Caching functionality for new releases videos  
        return $data;
    }

    /*
     * Function Name : getFeaturedArtists
     * Function Description : This function is used to getFeaturedArtists.
     */

    function getFeaturedArtists($territory,$page = 0, $limit = 20)
    {
    	set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
    	if(isset($page)){
    		if($page <= 0)
    		{
    			$page = 1;
    		}
    		$offset = ($page - 1) * $limit;
    	}

        
        $ids = '';
        $ids_provider_type = '';
        $featuredInstance = ClassRegistry::init('Featuredartist');
        $featured = $featuredInstance->find('all', array(
            'conditions' => array(
                                'Featuredartist.territory' => $territory,
                                'Featuredartist.language' => Configure::read('App.LANGUAGE')
                            ),
                'recursive' => -1,
                'order' => array(
                    'Featuredartist.id' => 'DESC'),
                'limit' => "$offset,$limit"
                )
        );        
        
        if ((count($featured) < 1) || ($featured === false))
        {
            $this->log("featured artist data is not available for" . $territory, "cache");
        }
        if(!empty($featured)){
            foreach ($featured as $k => $v)
            {                
                $featured[$k]['albumSongs'] = $this->getRandomSongs($v['Featuredartist']['artist_name'],$v['Featuredartist']['provider_type'],$v['Featuredartist']['flag'],0,$territory);
            }
        }
        return $featured;
    }
    
    /**
     * Function name : writeFeaturedSongsInCache
     * Function Description This is used to write random songs related to a composer or artist into cache.
     *  
     */ 
    
    function writeFeaturedSongsInCache($territory){
        $featuredInstance = ClassRegistry::init('Featuredartist');
        $featured = $featuredInstance->find('all', array(
                        'conditions' => array(
                            'Featuredartist.territory' => $territory,
                            'Featuredartist.language' => Configure::read('App.LANGUAGE')),
                            'Featuredartist.album !=' => 0,
                            'recursive' => -1,
                            'order' => array(
                                'Featuredartist.id' => 'desc'
                            ),
                    )
        ); 
        
        foreach ($featured as $k => $v)
        {                
            $featuredSongs = $this->getRandomSongs($v['Featuredartist']['artist_name'],$v['Featuredartist']['provider_type'],$v['Featuredartist']['flag'],1,$territory);
            if(!empty($featuredSongs)){
                Cache::write("featured_artist_".$v['Featuredartist']['artist_name'].'_'.$v['Featuredartist']['flag'].'_'.$territory, $featuredSongs);
                $this->log("cache written for featured artist for ".$v['Featuredartist']['artist_name']." with flag ".$v['Featuredartist']['flag']." for territory".$territory, "cache");                
            }
        }        
    }
    
    /**
     * Function name : getRandomSongs
     * Function Description This is used to get random songs related to a composer or artist.
     *  
     */
    
    function getRandomSongs($artistComposer , $provider,  $flag = 0, $ajax = 0, $territory = null){
        
        if(!empty($territory)) {
            $country = $territory;
            $countryPrefix = $this->getCountryPrefix($country);  // This is to add prefix to countries table when calling through cron
        } else {
            $country = $this->Session->read('territory'); 
        }        
        
        $songInstance = Classregistry::init('Song');
        if(empty($flag)){
            $cond = array('Song.ArtistText' => $artistComposer , 'Song.provider_type = Country.provider_type' , 'Song.provider_type' => $provider);
        }else{
            $cond = array('Song.Composer' => $artistComposer, 'Song.provider_type = Country.provider_type');
        }
        if(!empty($ajax)){
            $randomSongs = $songInstance->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Song.ProdID = Country.ProdID'),
                        array("Song.Sample_FileID != ''"),
                        array("Song.FullLength_FIleID != ''"),
                        array('Country.Territory' => $country),
                        array('Country.StreamingStatus' => 1),
                        array('Country.StreamingSalesDate <=' => date('Y-m-d')),
                        $cond
                    )
                ),
                'fields' => array(
                    'Song.ProdID',
                    'Song.ArtistText',
                    'Song.SongTitle',
                    'Song.Advisory',
                    'Song.FullLength_Duration',
                    'Song.provider_type',
                ),
                'contain' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.StreamingStatus',
                        )
                    ),
                    'Full_Files' => array(
                        'fields' => array(
                            'Full_Files.CdnPath',
                            'Full_Files.SaveAsName'
                        )
                    )
                ), 'group' => 'Song.ProdID, Song.provider_type','order' => 'Song.CreatedOn DESC','limit' => 50
            ));
        }else{
            $randomSongs = $songInstance->find('first', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Song.ProdID = Country.ProdID'),
                        array("Song.Sample_FileID != ''"),
                        array("Song.FullLength_FIleID != ''"),
                        array('Country.Territory' => $country),
                        array('Country.StreamingStatus' => 1),
                        array('Country.StreamingSalesDate <=' => date('Y-m-d')),
                        $cond
                    )
                ),
                'fields' => array(
                    'Song.ProdID',
                    'Song.ArtistText',
                    'Song.SongTitle',
                    'Song.Advisory',
                    'Song.FullLength_Duration',
                    'Song.provider_type',
                ),
                'contain' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.StreamingStatus',
                        )
                    ),
                    'Full_Files' => array(
                        'fields' => array(
                            'Full_Files.CdnPath',
                            'Full_Files.SaveAsName'
                        )
                    )
                ), 'group' => 'Song.ProdID, Song.provider_type'
            ));            
        }
        if (!empty($ajax)) {
            foreach ($randomSongs as $key => $value) {
                $tokeninstance = ClassRegistry::init('Token');
                $filePath = $tokeninstance->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
                if (!empty($filePath)) {
                    $songPath = explode(':', $filePath);
                    $streamUrl = trim($songPath[1]);
                    $randomSongs[$key]['streamUrl'] = $streamUrl;
                    $randomSongs[$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                }
            }
        }
        
        return $randomSongs;

    }
    
    
    /*
     * Function Name : getTopAlbums
     * Function Description : This function is used to getTopAlbums.
     */

    function getTopAlbums($territory)
    {
        set_time_limit(0); 
        $tokeninstance = ClassRegistry::init('Token');
        
        $ids = '';
        $ids_provider_type = '';
        $topAlbumInstance = ClassRegistry::init('TopAlbum');
        $topAlbums = $topAlbumInstance->find('all', array(
            'conditions' => array(
                'TopAlbum.territory' => $territory,
                'TopAlbum.language' => Configure::read('App.LANGUAGE')),
            'recursive' => -1,
            'order' => array(
                'TopAlbum.id' => 'ASC')
                )
        );
        
        foreach ($topAlbums as $k => $v)
        {
            if ($v['TopAlbum']['album'] != 0)
            {
                if (empty($ids))
                {
                    $ids .= $v['TopAlbum']['album'];
                    $ids_provider_type .= "(" . $v['TopAlbum']['album'] . ",'" . $v['TopAlbum']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['TopAlbum']['album'];
                    $ids_provider_type .= ',' . "(" . $v['TopAlbum']['album'] . ",'" . $v['TopAlbum']['provider_type'] . "')";
                }
            }
        }

        if ((count($topAlbums) < 1) || ($topAlbums === false))
        {
            $this->log("top album data is not available for" . $territory, "cache");
        }

        if ($ids != '')
        {
            $albumInstance = ClassRegistry::init('Album');
            $albumInstance->recursive = 2;
            $topAlbumData = $albumInstance->find('all', array(
                'joins' => array(
                    array(
                        'type' => 'INNER',
                        'table' => 'top_albums',
                        'alias' => 'ta',
                        'conditions' => array('Album.ProdID = ta.album')
                    )
                ),
                'conditions' => array(
                    'and' => array(
                        array(
                            "(Album.ProdID, Album.provider_type) IN (" . rtrim($ids_provider_type, ",'") . ")"
                        ),
                    ), 
                    "1 = 1 GROUP BY Album.ProdID"
                ),
                'fields' => array(
                    'Album.ProdID',
                    'Album.Title',
                    'Album.ArtistText',
                    'Album.AlbumTitle',
                    'Album.Artist',
                    'Album.ArtistURL',
                    'Album.Label',
                    'Album.Copyright',
                    'Album.provider_type'
                ),
                'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                            'Genre.Genre'
                        )
                    ),
                    'Files' => array(
                        'fields' => array(
                            'Files.CdnPath',
                            'Files.SaveAsName',
                            'Files.SourceURL'
                        ),
                    )
                ),
                'order' => 'ta.id ASC',
                'limit' => 25
                    )
            );
        }
        else
        {
            $topAlbumData = array();
        }

        if (empty($topAlbumData))
        {
            Cache::write("topAlbums" . $territory, Cache::read("topAlbums" . $territory));
        }
        else
        {
            foreach ($topAlbumData as $k => $v)
            {                
                $albumArtwork = $tokeninstance->artworkToken($v['Files']['CdnPath'] . "/" . $v['Files']['SourceURL']);
                $image = Configure::read('App.Music_Path') . $albumArtwork;
                $topAlbumData[$k]['topAlbumImage'] = $image;
                    $topAlbumData[$k]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($v['Album']['ArtistText']), $v['Album']['ProdID'], base64_encode($v['Album']['provider_type']),1,$territory))
                    );
            }
            Cache::delete("topAlbums" . $territory);
            Cache::write("topAlbums" . $territory, $topAlbumData);
        }
        $this->log("cache written for Top Albums for $territory", 'debug');
        $this->log("cache written for Top Albums for: $territory", "cache");
        return $topAlbumData;
    }    
    
    /*
     * Function Name : getTopSingles
     * Function Description : This function is used to Top 100 singles songs.
     */
    function getTopSingles($territory)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);

        $ids = '';
        $ids_provider_type = '';
        $top_singles_instance = ClassRegistry::init('TopSingles');
        $top_singles = $top_singles_instance->getAllTopSingles($territory);

        if (!empty($top_singles))
        {
            foreach ($top_singles as $k => $v)
            {
                if ($v['TopSingles']['prod_id'] != 0)
                {
                    if (empty($ids))
                    {
                        $ids .= $v['TopSingles']['prod_id'];
                        $ids_provider_type .= "(" . $v['TopSingles']['prod_id'] . ",'" . $v['TopSingles']['provider_type'] . "')";
                    }
                    else
                    {
                        $ids .= ',' . $v['TopSingles']['prod_id'];
                        $ids_provider_type .= ',' . "(" . $v['TopSingles']['prod_id'] . ",'" . $v['TopSingles']['provider_type'] . "')";
                    }
                }
            }
        }
        else
        {
            $this->log("top album data is not available for" . $territory, "cache");
        }

     
        if ($ids != '')
        {
            $albumInstance = ClassRegistry::init('Album');
            $albumInstance->recursive = 2;
            $topSingleData = array();

            $sql_top_singles = <<<STR
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
                        Country.Territory,
                        Country.SalesDate,
                        Country.StreamingSalesDate,
                        Country.StreamingStatus,
                        Country.DownloadStatus,
                        Sample_Files.CdnPath,
                        Sample_Files.SaveAsName,
                        Full_Files.CdnPath,
                        Full_Files.SaveAsName,
                        File.CdnPath,
                        File.SourceURL,
                        File.SaveAsName,
                        Sample_Files.FileID,
                        PRODUCT.pid,
                        Albums.ProdID,
                        Albums.provider_type
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type)                        
                                INNER JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$territory') AND Country.DownloadStatus = '1' AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        (Song.ProdID, Song.provider_type) IN ($ids_provider_type) 
                GROUP BY Song.ProdID
                ORDER BY FIELD(Song.ProdID,$ids) DESC
                LIMIT 50 

STR;
            $topSingleData = $albumInstance->query($sql_top_singles);


            
            if (!empty($topSingleData))
            {
                Cache::write("top_singles" . $territory, $topSingleData);
                $data = $topSingleData;
                $this->log("cache written for national top 100 songs for $territory", "cache");
            }
            else
            {
                $data = Cache::read("top_singles" . $territory);
                $this->log("Unable to update national 100 for " . $territory, "cache");
            }

            $this->log("cache written for top 100 singles for $territory", 'debug');
            return $data;
        }
    }
    
    
    /*
     * Function Name : getGenreData
     * Function Description : This function is used to getGenreData.
     */

    function getGenreData($territory, $genre)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
        if ($maintainLatestDownload)
        {
            $restoregenre_query = "
        SELECT 
            COUNT(DISTINCT latest_downloads.id) AS countProduct,
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
            Song.Genre,
            Country.Territory,
            Country.SalesDate,
            Country.DownloadStatus,
            Sample_Files.CdnPath,
            Sample_Files.SaveAsName,
            Full_Files.CdnPath,
            Full_Files.SaveAsName,
            Sample_Files.FileID,
            Full_Files.FileID,
            PRODUCT.pid
        FROM
            latest_downloads,
            Songs AS Song
                LEFT JOIN
            {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory LIKE '%" . $territory . "%')  AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                LEFT JOIN
            File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                LEFT JOIN
            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                LEFT JOIN
            PRODUCT ON (PRODUCT.ProdID = Song.ProdID)  AND (PRODUCT.provider_type = Song.provider_type)
        WHERE
            latest_downloads.ProdID = Song.ProdID 
            AND latest_downloads.provider_type = Song.provider_type 
            AND Song.Genre LIKE '%" . mysql_real_escape_string($genre) . "%'
            AND Country.DownloadStatus = '1'               
            AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
        GROUP BY latest_downloads.ProdID
        ORDER BY countProduct DESC
        LIMIT 10
        ";
        }
        else
        {
            $restoregenre_query = "
      SELECT 
          COUNT(DISTINCT downloads.id) AS countProduct,
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
          Song.Genre,
          Country.Territory,
          Country.SalesDate,
          Country.DownloadStatus,
          Sample_Files.CdnPath,
          Sample_Files.SaveAsName,
          Full_Files.CdnPath,
          Full_Files.SaveAsName,
          Sample_Files.FileID,
          Full_Files.FileID,
                      PRODUCT.pid
      FROM
          downloads,
          Songs AS Song
              LEFT JOIN
          {$countryPrefix}countries AS Country ON Country.ProdID = Song.ProdID AND (Country.Territory LIKE '%" . $territory . "%')  AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
              LEFT JOIN
          File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
              LEFT JOIN
          File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
              LEFT JOIN
          PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type)
      WHERE
          downloads.ProdID = Song.ProdID 
          AND downloads.provider_type = Song.provider_type 
          AND Song.Genre LIKE '%" . mysql_real_escape_string($genre) . "%'           
          AND Country.DownloadStatus = '1' 			
          AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
      GROUP BY downloads.ProdID
      ORDER BY countProduct DESC
      LIMIT 10
      ";
        }

        $data = $albumInstance->query($restoregenre_query);
        $this->log("restoregenre_query for $territory", "cachequery");
        $this->log($restoregenre_query, "cachequery");
        if (!empty($data))
        {
            Cache::delete($genre . $territory);
            Cache::write($genre . $territory, $data);
            $this->log("cache written for: $genre $territory", "cache");
        }
        else
        {
            Cache::write($genre . $territory, Cache::read($genre . $territory));
            $this->log("Unable to update key for: $genre $territory", "cache");
        }
    }

    /*
     * @func getDifferentGenreData
     * @desc This is used to get top 10 for different genres
     */

    function getDifferentGenreData($territory)
    {

        $genres = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
            "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");

        foreach ($genres as $genre)
        {
            $this->getGenreData($territory, $genre);
        }
        $this->log("cache written for top 10 for different genres for $territory", 'debug');
    }

    /**
     * @function getLibraryTopTenSongs
     * @desc get data of  LibraryTopTenSongs
     */
    function getLibraryTopTenSongs($territory, $libId)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        //--------------------------------Library Top Ten Start--------------------------------------------------------------------
        $latestDownloadInstance = ClassRegistry::init('LatestDownload');
        $downloadInstance = ClassRegistry::init('Download');
        $songInstance = ClassRegistry::init('Song');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);
        $maintainLatestDownload = $this->Session->read('maintainLatestDownload');

        //this is for my library songs start

        if ($maintainLatestDownload)
        {
            $download_src = 'LatestDownload';
            $topDownloaded = $latestDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }
        else
        {
            $download_src = 'Download';
            $topDownloaded = $downloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }

        $this->log("$download_src - $libId - $country", "cache");

        $ids = '';
        $ioda_ids = array();
        $sony_ids = array();
        $sony_ids_str = '';
        $ioda_ids_str = '';
        $ids_provider_type = '';
        foreach ($topDownloaded as $k => $v)
        {
            if ($maintainLatestDownload)
            {
                if (empty($ids))
                {
                    $ids .= $v['LatestDownload']['ProdID'];
                    $ids_provider_type .= "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['LatestDownload']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                }
                if ($v['LatestDownload']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['LatestDownload']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['LatestDownload']['ProdID'];
                }
            }
            else
            {
                if (empty($ids))
                {
                    $ids .= $v['Download']['ProdID'];
                    $ids_provider_type .= "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['Download']['ProdID'];
                    $ids_provider_type .= ',' . "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                }
                if ($v['Download']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['Download']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['Download']['ProdID'];
                }
            }
        }

        if ((count($topDownloaded) < 1) || ($topDownloaded === false))
        {
            $this->log("top download is not available for library: $libId - $country", "cache");
        }

        if ($ids != '')
        {
            if (!empty($sony_ids))
            {
                $sony_ids_str = implode(',', $sony_ids);
            }
            if (!empty($ioda_ids))
            {
                $ioda_ids_str = implode(',', $ioda_ids);
            }
            if (!empty($sony_ids_str) && !empty($ioda_ids_str))
            {
                $top_ten_condition_songs = "((Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony') OR (Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda'))";
            }
            else if (!empty($sony_ids_str))
            {
                $top_ten_condition_songs = "(Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony')";
            }
            else if (!empty($ioda_ids_str))
            {
                $top_ten_condition_songs = "(Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda')";
            }

            $songInstance->recursive = 2;
            $topDownloaded_query = <<<STR
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
                                    Albums.ProdID,
                                    Albums.provider_type,                                          
                                    Genre.Genre,
                                    Country.Territory,
                                    Country.SalesDate,
                                    Country.StreamingSalesDate,
                                    Country.StreamingStatus,
                                    Country.DownloadStatus,                                    
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
                             {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' AND (Song.provider_type = Country.provider_type) AND (Country.Territory = '$country') AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
                                            LEFT JOIN
                                    PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type) 
                                            INNER JOIN 
                                    Albums ON (Song.ReferenceID=Albums.ProdID) 
                                            INNER JOIN 
                                    File ON (Albums.FileID = File.FileID)
                            WHERE
                                    (($top_ten_condition_songs) AND 1 = 1)
                            GROUP BY Song.ProdID
                            ORDER BY FIELD(Song.ProdID,
                                            $ids) ASC
                            LIMIT 10
STR;
            $topDownload = $songInstance->query($topDownloaded_query);
        }
        else
        {
            $topDownload = array();
        }

        //library top 10 cache set
        if ((count($topDownload) < 1) || ($topDownload === false))
        {
            Cache::write("lib" . $libId, Cache::read("lib" . $libId));
            $this->log("topDownloaded_query songs  returns null for lib: $libId $country", "cache");
        }
        else
        {
            foreach ($topDownload as $key => $value)
            {                  
                $songs_img = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $songs_img = Configure::read('App.Music_Path') . $songs_img;
                $topDownload[$key]['songs_img'] = $songs_img;
                    
                    $filePath = $tokeninstance->streamingToken($value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
                    
                    if (!empty($filePath))
                    {
                        $songPath = explode(':', $filePath);
                        $streamUrl = trim($songPath[1]);
                        $topDownload[$key]['streamUrl'] = $streamUrl;
                        $topDownload[$key]['totalseconds'] = $this->Streaming->getSeconds($value['Song']['FullLength_Duration']);
                    }
            }
            Cache::delete("lib" . $libId);
            Cache::write("lib" . $libId, $topDownload);
            //library top 10 cache set
            $this->log("library top 10 songs cache set for lib: $libId $country", "cache");
        }

        //library top 10 cache set for songs end
        return $topDownload;
    }

    /* @function getLibraryTop10Albums
     * @desc sets Cache for LibraryTopTenSongs
     */

    function getLibraryTop10Albums($territory, $libId)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');         
        $albumInstance = ClassRegistry::init('Album');
        $latestDownloadInstance = ClassRegistry::init('LatestDownload');
        $downloadInstance = ClassRegistry::init('Download');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);
        $maintainLatestDownload = $this->Session->read('maintainLatestDownload');
        //library top 10 cache set for albums start            
        if ($maintainLatestDownload)
        {
            $download_src = 'LatestDownload';
            $topDownloaded_albums = $latestDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }
        else
        {
            $download_src = 'Download';
            $topDownloaded_albums = $downloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }


        $this->log("$download_src - $libId - $country", "cache");

        $ids = '';
        $ioda_ids = array();
        $sony_ids = array();
        $sony_ids_str = '';
        $ioda_ids_str = '';
        $ids_provider_type = '';
        foreach ($topDownloaded_albums as $k => $v)
        {
            if ($maintainLatestDownload)
            {
                if (empty($ids))
                {
                    $ids .= $v['LatestDownload']['ProdID'];
                    $ids_provider_type_album .= "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['LatestDownload']['ProdID'];
                    $ids_provider_type_album .= ',' . "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                }
                if ($v['LatestDownload']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['LatestDownload']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['LatestDownload']['ProdID'];
                }
            }
            else
            {

                if (empty($ids))
                {
                    $ids .= $v['Download']['ProdID'];
                    $ids_provider_type_album .= "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['Download']['ProdID'];
                    $ids_provider_type_album .= ',' . "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                }


                if ($v['Download']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['Download']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['Download']['ProdID'];
                }
            }
        }

        if ((count($topDownloaded_albums) < 1) || ($topDownloaded_albums === false))
        {
            $this->log("top download is not available for library: $libId - $country", "cache");
        }

        if ($ids != '')
        {
            if (!empty($sony_ids))
            {
                $sony_ids_str = implode(',', $sony_ids);
            }
            if (!empty($ioda_ids))
            {
                $ioda_ids_str = implode(',', $ioda_ids);
            }
            if (!empty($sony_ids_str) && !empty($ioda_ids_str))
            {
                $top_ten_condition_albums = "((Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony') OR (Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda'))";
            }
            else if (!empty($sony_ids_str))
            {
                $top_ten_condition_albums = "(Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony')";
            }
            else if (!empty($ioda_ids_str))
            {
                $top_ten_condition_albums = "(Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda')";
            }

            $albumInstance->recursive = 2;
            $topDownloaded_query_albums = <<<STR
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
                    Albums.ProdID,
                    Albums.provider_type,
                    Albums.Advisory,             
                    Albums.AlbumTitle,
                    Genre.Genre,
                    Country.Territory,
                    Country.SalesDate,
                    Country.StreamingSalesDate,
                    Country.StreamingStatus,
                    Country.DownloadStatus,
                    Sample_Files.CdnPath,
                    Sample_Files.SaveAsName,
                    Full_Files.CdnPath,
                    Full_Files.SaveAsName,
                    File.CdnPath,
                    File.SourceURL,
                    File.SaveAsName,
                    Sample_Files.FileID
            FROM Songs AS Song
            LEFT JOIN File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
            LEFT JOIN File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
            LEFT JOIN Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
            LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Song.provider_type = Country.provider_type)
            INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) 
            INNER JOIN File ON (Albums.FileID = File.FileID)
            WHERE (Country.DownloadStatus = '1') AND (($top_ten_condition_albums))  AND 1 = 1  AND (Country.Territory = '$country') AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
            GROUP BY Song.ReferenceID
            ORDER BY count(Song.ReferenceID) DESC
            LIMIT 10
STR;
            $topDownload = $albumInstance->query($topDownloaded_query_albums);
        }
        else
        {
            $topDownload = array();
        }

        //library top 10 cache set
        if ((count($topDownload) < 1) || ($topDownload === false))
        {
            Cache::write("lib_album" . $libId, Cache::read("lib_album" . $libId));
            $this->log("topDownloaded_query albums returns null for lib: $libId $country", "cache");
        }
        else
        {
            foreach ($topDownload as $key => $value)
            {                
                $album_img = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $album_img = Configure::read('App.Music_Path') . $album_img;
                $topDownload[$key]['album_img'] = $album_img;
                    $topDownload[$key]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type']),0,$country))
                    );
            }
            Cache::delete("lib_album" . $libId);
            Cache::write("lib_album" . $libId, $topDownload);
            //library top 10 cache set
            $this->log("library top 10 albums cache set for lib: $libId $country", "cache");
        }

        //library top 10 cache set for albums end
        return $topDownload;
    }

    /* @function getLibraryTop10Videos
     * @desc sets Cache for getLibraryTop10Videos
     */

    function getLibraryTop10Videos($territory, $libId)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $latestVideoDownloadInstance = ClassRegistry::init('LatestVideodownload');
        $videodownloadInstance = ClassRegistry::init('Videodownload');
        $videoInstance = ClassRegistry::init('Video');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);
        $maintainLatestDownload = $this->Session->read('maintainLatestDownload');

        //library top 10 cache set for videos start 
        if ($maintainLatestDownload)
        {
            $download_src = 'LatestDownload';
            $topDownloaded_videos = $latestVideoDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }
        else
        {
            $download_src = 'Download';
            $topDownloaded_videos = $videodownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        }


        $this->log("$download_src - $libId - $country", "cache");

        $ids = '';
        $ioda_ids = array();
        $sony_ids = array();
        $sony_ids_str = '';
        $ioda_ids_str = '';
        $ids_provider_type = '';
        foreach ($topDownloaded_videos as $k => $v)
        {
            if ($maintainLatestDownload)
            {
                if (empty($ids))
                {
                    $ids .= $v['LatestVideodownload']['ProdID'];
                    $ids_provider_type_video .= "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['LatestVideodownload']['ProdID'];
                    $ids_provider_type_video .= ',' . "(" . $v['LatestVideodownload']['ProdID'] . ",'" . $v['LatestVideodownload']['provider_type'] . "')";
                }
                if ($v['LatestVideodownload']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['LatestVideodownload']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['LatestVideodownload']['ProdID'];
                }
            }
            else
            {

                if (empty($ids))
                {
                    $ids .= $v['Download']['ProdID'];
                    $ids_provider_type_video .= "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $v['Download']['ProdID'];
                    $ids_provider_type_video .= ',' . "(" . $v['Videodownload']['ProdID'] . ",'" . $v['Videodownload']['provider_type'] . "')";
                }
                if ($v['Download']['provider_type'] == 'sony')
                {
                    $sony_ids[] = $v['Videodownload']['ProdID'];
                }
                else
                {
                    $ioda_ids[] = $v['Videodownload']['ProdID'];
                }
            }
        }

        if ((count($topDownloaded_videos) < 1) || ($topDownloaded_videos === false))
        {
            $this->log("top download is not available for library: $libId - $country", "cache");
        }

        if ($ids != '')
        {
            if (!empty($sony_ids))
            {
                $sony_ids_str = implode(',', $sony_ids);
            }
            if (!empty($ioda_ids))
            {
                $ioda_ids_str = implode(',', $ioda_ids);
            }
            if (!empty($sony_ids_str) && !empty($ioda_ids_str))
            {
                $top_ten_condition_videos = "((Video.ProdID IN (" . $sony_ids_str . ") AND Video.provider_type='sony') OR (Video.ProdID IN (" . $ioda_ids_str . ") AND Video.provider_type='ioda'))";
            }
            else if (!empty($sony_ids_str))
            {
                $top_ten_condition_videos = "(Video.ProdID IN (" . $sony_ids_str . ") AND Video.provider_type='sony')";
            }
            else if (!empty($ioda_ids_str))
            {
                $top_ten_condition_videos = "(Video.ProdID IN (" . $ioda_ids_str . ") AND Video.provider_type='ioda')";
            }


            $videoInstance->recursive = 2;
            $topDownloaded_query_videos = <<<STR
             SELECT 
                     Video.ProdID,
                     Video.ReferenceID,
                     Video.Title,
                     Video.ArtistText,
                     Video.DownloadStatus,
                     Video.VideoTitle,
                     Video.Artist,
                     Video.Advisory,
                     Video.Sample_Duration,
                     Video.FullLength_Duration,
                     Video.provider_type,
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
                     Sample_Files.FileID
             FROM video AS Video
             LEFT JOIN File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
             LEFT JOIN File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
             LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID)
             LEFT JOIN {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Video.provider_type = Country.provider_type)
             INNER JOIN File ON (Video.Image_FileID = File.FileID)
             WHERE((Video.DownloadStatus = '1') AND ($top_ten_condition_videos) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1)
             GROUP BY Video.ProdID
             ORDER BY FIELD(Video.ProdID, $ids) ASC
             LIMIT 10
STR;
            $topDownload = $videoInstance->query($topDownloaded_query_videos);
        }
        else
        {
            $topDownload = array();
        }

        //library top 10 cache set
        if ((count($topDownload) < 1) || ($topDownload === false))
        {
            Cache::write("lib_video" . $libId, Cache::read("lib_video" . $libId));
            $this->log("topDownloaded_query videos returns null for lib: $libId $country", "cache");
        }
        else
        {
            foreach ($topDownload as $key => $value)
            {                
                $albumArtwork = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                $topDownload[$key]['videoAlbumImage'] = $videoAlbumImage;
            }
            Cache::delete("lib_video" . $libId);
            Cache::write("lib_video" . $libId, $topDownload);
            //library top 10 cache set
            $this->log("library top 10 videos cache set for lib: $libId $country", "cache");
        }

        //library top 10 cache set for videos end
        return $topDownload;
    }

    /**
     * Function Name : getCountryPrefix
     * Function Description : This function is used to get the country prefix
     */
    function getCountryPrefix($territory)
    {
	$multipleCountries = Cache::read('multipleCountries');
        if (($multipleCountries) === false)
        {
            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
            $albumInstance = ClassRegistry::init('Album');
            $siteConfigData = $albumInstance->query($siteConfigSQL);
            $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);
            Cache::write("multipleCountries", $multiple_countries);
        }
        else
        {
            $multiple_countries = $multipleCountries;
        }
        $countryInstance = ClassRegistry::init('Country');
        if (0 == $multiple_countries)
        {
            $countryPrefix = '';
            $countryInstance->setTablePrefix('');
        }
        else
        {
            $countryPrefix = strtolower($territory) . "_";
            $countryInstance->setTablePrefix($countryPrefix);
        }
        return $countryPrefix;
    }

    /**
     * @function getVideoDetails
     * @desc This is used to getVideoDetails
     */
    function getVideoDetails($territory, $indiMusicVidID)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $videoInstance = ClassRegistry::init('Video');
        $albumInstance = ClassRegistry::init('Album');
        $individualVideoSQL =
                "SELECT Video.ProdID, Video.ReferenceID,Video.Advisory,  Video.VideoTitle, Video.ArtistText, Video.FullLength_Duration,
         Video.CreatedOn, Video.Image_FileID, Video.provider_type, Video.Genre,
         Full_Files.CdnPath,Full_Files.SaveAsName,File.CdnPath,File.SourceURL,File.SaveAsName
         FROM video as Video            
         LEFT JOIN
         File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)                                 
         LEFT JOIN
         PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  AND (PRODUCT.provider_type = Video.provider_type)
         INNER JOIN File ON (Video.Image_FileID = File.FileID)
         Where Video.DownloadStatus = '1' AND Video.ProdID = " . $indiMusicVidID;

        $EachVideosData = $videoInstance->query($individualVideoSQL);
        if ((count($EachVideosData) < 1) || ($EachVideosData === false))
        {
            $this->log("Music video id $indiMusicVidID returns null ", "cache");
        }
        else
        {            
            $videoArtwork = $tokeninstance->artworkToken($EachVideosData[0]['File']['CdnPath'] . "/" . $EachVideosData[0]['File']['SourceURL']);
            $EachVideosData[0]['videoImage'] = Configure::read('App.Music_Path') . $videoArtwork;
        }
        if (count($EachVideosData) > 0)
        {
            $MoreVideosSql =
                    "SELECT Video.ProdID, Video.ReferenceID, Video.VideoTitle,Video.Advisory, Video.ArtistText, Video.FullLength_Duration, Video.CreatedOn, Video.Image_FileID, Video.provider_type, Sample_Files.CdnPath,
             Sample_Files.SaveAsName,
             Full_Files.CdnPath,
             Full_Files.SaveAsName,
             File.CdnPath,
             File.SourceURL,
             File.SaveAsName,
             Sample_Files.FileID,
             Country.Territory,
             Country.SalesDate
             FROM video as Video
             LEFT JOIN
             File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
             LEFT JOIN
             File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)   
             LEFT JOIN
             {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$territory') AND (Video.provider_type = Country.provider_type)
             LEFT JOIN
             PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  INNER JOIN File ON (Video.Image_FileID = File.FileID)
             Where Video.DownloadStatus = '1' AND PRODUCT.provider_type = Video.provider_type  AND Video.ArtistText = '" . $EachVideosData[0]['Video']['ArtistText'] . "'   ORDER BY Country.SalesDate desc limit 0,10";

            $MoreVideosData = $albumInstance->query($MoreVideosSql);
            foreach ($MoreVideosData as $key => $value)
            {                
                $videoArtwork = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                $MoreVideosData[$key]['videoImage'] = $videoImage;
            }
            if (!empty($MoreVideosData))
            {
                Cache::write("musicVideoMoreDetails_" . $territory . '_' . $EachVideosData[0]['Video']['ArtistText'], $MoreVideosData);
                $this->log("Music video more details of artist - $EachVideosData[0]['Video']['ArtistText'] cache set", "cache");
            }
            else
            {
                $this->log("Music video more details of artist - $EachVideosData[0]['Video']['ArtistText'] returns null ", "cache");
            }
        }
        if (count($EachVideosData) > 0)
        {
            $TopVideoGenreSql = "SELECT Videodownloads.ProdID, Video.ProdID,Video.Advisory, Video.ReferenceID, Video.provider_type, Video.VideoTitle, Video.Genre, Video.ArtistText, File.CdnPath, File.SourceURL,  COUNT(DISTINCT(Videodownloads.id)) AS COUNT,
                 `Country`.`SalesDate` FROM videodownloads as Videodownloads LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) 
                 LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) LEFT JOIN Genre AS Genre ON (Genre.ProdID = Video.ProdID) LEFT JOIN {$countryPrefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`)
                 LEFT JOIN libraries as Library ON Library.id=Videodownloads.library_id 
                 WHERE library_id=1 AND Library.library_territory='" . $territory . "' AND `Country`.`SalesDate` <= NOW() AND Video.Genre = '" . $EachVideosData[0]['Video']['Genre'] . "' AND (Video.provider_type = Genre.provider_type)  GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC limit 0,10";

            $TopVideoGenreData = $albumInstance->query($TopVideoGenreSql);
            foreach ($TopVideoGenreData as $key => $value)
            {                
                $videoArtwork = $tokeninstance->artworkToken($value['File']['CdnPath'] . "/" . $value['File']['SourceURL']);
                $videoImage = Configure::read('App.Music_Path') . $videoArtwork;
                $TopVideoGenreData[$key]['videoImage'] = $videoImage;
            }
            if (!empty($TopVideoGenreData))
            {
                Cache::write("top_videos_genre_" . $territory . '_' . $EachVideosData[0]['Video']['Genre'], $TopVideoGenreData);
                $this->log("Top videos  of genre - $EachVideosData[0]['Video']['Genre'] for territory -$territory cache set", "cache");
            }
            else
            {
                $this->log("Top videos  of genre - $EachVideosData[0]['Video']['Genre'] for territory -$territory returns null ", "cache");
            }
        }
    }

    /**
     * @function getAllVideoByArtist
     * @desc This is used to getAllVideoByArtist
     */
    function getAllVideoByArtist($country, $decodedId, $explicitContent = true)
    {
        $tokeninstance = ClassRegistry::init('Token');
        //add the slashes in the text
        $decodedId = addslashes($decodedId);
        $videoInstance = ClassRegistry::init('Video');
        $preFix = strtolower($country) . "_";

        if (!empty($country))
        {

        	$countryPrefix = $this->Session->read('multiple_countries');
            $videoAdvisory = '';
            
            if( $explicitContent === false ) {
            	$videoAdvisory = " AND Video.Advisory != 'T'";
            }

            $sql_us_10_v = <<<STR
                SELECT 
                                Video.ProdID,
                                Video.ReferenceID,
                                Video.Title,
                                Video.ArtistText,
                                Video.DownloadStatus,
                                Video.VideoTitle,
                                Video.Artist,
                                Video.Advisory,
                                Video.Sample_Duration,
                                Video.FullLength_Duration,
                                Video.provider_type,
                                Video.video_label,
                                Video.CreatedOn,
                                Video.Image_FileID,
                                Genre.Genre,
                                Country.Territory,
                                Country.SalesDate,
                                Sample_Files.CdnPath,
                                Sample_Files.SaveAsName,
                                Sample_Files.FileID,
                                Full_Files.CdnPath,
                                Full_Files.SaveAsName,
                                Full_Files.FileID,
                                Image_Files.FileID,
                                Image_Files.CdnPath,
                                Image_Files.SourceURL,
                                PRODUCT.pid
                FROM
                                video AS Video
                                                LEFT JOIN
                                File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
                                                LEFT JOIN
                                File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
                                                LEFT JOIN
                                Genre AS Genre ON (Genre.ProdID = Video.ProdID)
                                                LEFT JOIN
         {$preFix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type)
                                                LEFT JOIN
                                PRODUCT ON (PRODUCT.ProdID = Video.ProdID)
                                                LEFT JOIN
                                File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
                WHERE
                                ( (Video.DownloadStatus = '1') AND ((Video.ArtistText) IN ('$decodedId')) AND (Video.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Video.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1 $videoAdvisory
                GROUP BY Video.ProdID
                ORDER BY Country.SalesDate desc  
STR;

            $artistVideoList = $videoInstance->query($sql_us_10_v);
            foreach ($artistVideoList as $key => $value)
            {                
                $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                $artistVideoList[$key]['videoAlbumImage'] = $videoAlbumImage;
            }


            return $artistVideoList;
        }
    }

    /**
     * @function getDefaultQueues
     * @desc     This function is used to get default queues.
     */
    function getDefaultQueues($territory)
    {

        //--------------------------------Default Freegal Queues Start----------------------------------------------------               
        $cond = array('queue_type' => 1, 'status' => '1');
        $queuelistInstance = ClassRegistry::init('QueueList');
        //Unbinded User model
        $queuelistInstance->unbindModel(
                array('belongsTo' => array('User'), 'hasMany' => array('QueueDetail'))
        );
        //fetched the default list
        $queueData = $queuelistInstance->find('all', array(
            'conditions' => $cond,
            'fields' => array('queue_id', 'queue_name', 'queue_type'),
            'order' => 'QueueList.created DESC',
            'limit' => 100
        ));

        //freegal Query Cache set
        if ((count($queueData) < 1) || ($queueData === false))
        {
            Cache::write("defaultqueuelist", Cache::read("defaultqueuelist"));
            $this->log("Freegal Defaut Queues returns null ", "cache");
        }
        else
        {
            Cache::delete("defaultqueuelist");
            Cache::write("defaultqueuelist", $queueData);

            //library top 10 cache set
            $this->log("Freegal Defaut Queues cache set", "cache");
        }

        //set the variable for each freegal default queue 
        foreach ($queueData as $value)
        {
            $defaultQueueId = $value['QueueList']['queue_id'];
            $defaultQueueName = $value['QueueList']['queue_name'];
            $eachQueueDetails = $this->Queue->getQueueDetails($defaultQueueId, $territory);

            if ((count($eachQueueDetails) < 1) || ($eachQueueDetails === false))
            {
                $this->log("Freegal Defaut Queues " . $defaultQueueName . "( " . $defaultQueueId . " )" . " returns null ", "cache");
            }
            else
            {
                Cache::write("defaultqueuelistdetails".$territory.$defaultQueueId, $eachQueueDetails);
                $this->log("Freegal Defaut Queues " . $defaultQueueName . "( " . $defaultQueueId . " )" . " cache set", "cache");
            }
        }
        //--------------------------------Default Freegal Queues End--------------------------------------------------------------
    }

    /**
     * @function setLibraryTopTenCache
     * @desc sets Cache for LibraryTopTen
     */
    function setLibraryTopTenCache()
    {

        //--------------------------------Library Top Ten Start--------------------------------------------------------------------
        set_time_limit(0);
        $libraryInstance = ClassRegistry::init('Library');
        $libraryDetails = $libraryInstance->find('all', array(
            'fields' => array('id', 'library_territory'),
            'conditions' => array('library_status' => 'active'),
            'recursive' => -1
                )
        );

        foreach ($libraryDetails AS $key => $val)
        {
            $libId = $val['Library']['id'];
            $country = $val['Library']['library_territory'];
            $this->getLibraryTopTenSongs($country, $libId);
            $this->getLibraryTop10Albums($country, $libId);
            $this->getLibraryTop10Videos($country, $libId);
        }
    }

    /**
     * @function setVideoCacheVar
     * @desc sets video cache Variable
     */
    function setVideoCacheVar()
    {
        //--------------------------------set each music video in the cache start-------------------------------------------------        
        $videoInstance = ClassRegistry::init('Video');
        $musicVideoRecs = $videoInstance->find('all', array('conditions' => array('DownloadStatus' => 1), 'fields' => 'Video.ProdID'));
        $territoryNames = $this->getTerritories();
        for ($i = 0; $i < count($territoryNames); $i++)
        {
            $territory = $territoryNames[$i];
            foreach ($musicVideoRecs as $musicVideoRec)
            {
                $this->getVideoDetails($territory, $musicVideoRec['Video']['ProdID']);
            }
        }
    }

    /*
     * @func getTerritories
     * @desc This is used to get territories list
     */

    function getTerritories()
    {
	$territoryList = Cache::read('territoryList');
        if (($territoryList) === false)
        {
            $territoryInstance = ClassRegistry::init('Territory');
            $territories = $territoryInstance->find("all");
            for ($mm = 0; $mm < count($territories); $mm++)
            {
                $territoryNames[$mm] = $territories[$mm]['Territory']['Territory'];
            }
            Cache::write("territoryList", $territoryNames);
        }
        else
        {
            $territoryNames = $territoryList;
        }
        return $territoryNames;
    }

    /**
     * @func getQueueAlbumDetails
     * @desc This is used to get Songs list for Stream Now on Search page
     */
    function getQueueAlbumDetails($ProdID)
    {
        $albumInstance = ClassRegistry::init('Album');
        $country = $this->Session->read('territory');
        $countryPrefix = $this->getCountryPrefix($country);
        $album_songs = <<<STR
                SELECT                         
                        Albums.ProdID,
                        Albums.provider_type,
			Albums.AlbumTitle,
                        Albums.Advisory,
                        Albums.ArtistText
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                LEFT JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        Albums.ProdID = '$ProdID'
                GROUP BY Song.ReferenceID
                ORDER BY COUNT(Song.ReferenceID) DESC
                LIMIT 100 

STR;


        return $albumInstance->query($album_songs);
    }
    
    
    /**
     * @func getAlbumSongs
     * @desc This is used to get album songs
     */    
    function getAlbumSongs($albumProdId,$provider){
        
        $songInstance = ClassRegistry::init('Song');
        $country = $this->Session->read('territory');
        $countryPrefix = $this->getCountryPrefix($country);        
        $albumSongs = array();
        $libType = $this->Session->read('library_type');
        $cond = "";
        if ($this->Session->read('block') == 'yes')
        {
            $cond = array('Song.Advisory' => 'F');
        }
        else
        {
            $cond = "";
        }        
        if ($libType != 2)
        {
            $albumSongs = $songInstance->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Song.ReferenceID' => $albumProdId),
                        array('Song.provider_type = Country.provider_type'),
                        array('Country.DownloadStatus' => 1),
                        array("Song.Sample_FileID != ''"),
                        array("Song.FullLength_FIleID != ''"),
                        array("Song.provider_type" => $provider),
                        array('Country.Territory' => $country),
                        $cond
                    )
                ),
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
                        )
                    ),
                    'Country' => array(
                        'fields' => array(
                            'Country.Territory',
                            'Country.provider_type'
                        )
                    ),
                ),
                'group' => 'Song.ProdID, Song.provider_type',
                'order' => array('Song.sequence_number', 'Song.ProdID')
            ));
        }
        else
        {
            $albumSongs = $songInstance->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Song.ReferenceID' => $albumProdId),
                        array('Song.provider_type = Country.provider_type'),
                        array("Song.Sample_FileID != ''"),
                        array("Song.FullLength_FIleID != ''"),
                        array("Song.provider_type" => $provider),
                        array('Country.Territory' => $country),
                        $cond
                    ),
                    'or' => array(array('and' => array(
                                'Country.StreamingStatus' => 1,
                                'Country.StreamingSalesDate <=' => date('Y-m-d')
                            ))
                        ,
                        array('and' => array(
                                'Country.DownloadStatus' => 1
                            ))
                    )
                ),
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
                        )
                    ),
                    'Country' => array(
                        'fields' => array(
                            'Country.Territory',
                            'Country.provider_type'
                        )
                    ),
                ),
                'group' => 'Song.ProdID, Song.provider_type',
                'order' => array('Song.sequence_number', 'Song.ProdID')
            ));
        }
        
        return $albumSongs;
    }

    /**
     * @func getSongsDetails
     * @desc This is used to get Songs details 
     */
    function getSongsDetails($ProdID)
    {
        $albumInstance = ClassRegistry::init('Album');
        $country = $this->Session->read('territory');
        $countryPrefix = $this->getCountryPrefix($country);

        $album_songs = <<<STR
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
                        Albums.ProdID,
                        Albums.provider_type,                                       
                        Genre.Genre,
                        Country.Territory,
                        Country.SalesDate,
                        Country.StreamingSalesDate,
                        Country.StreamingStatus,
                        Country.DownloadStatus  
                FROM
                        Songs AS Song
                                LEFT JOIN
                        File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                LEFT JOIN
                        File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                LEFT JOIN
                        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                LEFT JOIN
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND Country.DownloadStatus = '1' 
                            AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                LEFT JOIN
                        PRODUCT ON ((PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type))
                                INNER JOIN 
                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                INNER JOIN 
                        File ON (Albums.FileID = File.FileID) 
                WHERE
                        Song.ProdID = '$ProdID'
                GROUP BY Song.ReferenceID
                ORDER BY COUNT(Song.ReferenceID) DESC
                LIMIT 100 

STR;


        return $albumInstance->query($album_songs);
    }

    function getVideodownloadStatus($libId, $patID, $startDate, $endDate, $update = false)
    {
        $videodownloadCountArray = array();

        $videodownloadInstance = ClassRegistry::init('Videodownload');
        $videodownloadInstance->recursive = -1;

        if (!$this->Session->check('videodownloadCountArray') || $update)
        {
            $videodownloadCount = $videodownloadInstance->getPatronDownloadCount($libId ,  $patID, $startDate, $endDate );;
            foreach ($videodownloadCount as $key => $value)
            {
                $videodownloadCountArray[$value['Videodownload']['ProdID']] = array(
                    'provider_type' => $value['Videodownload']['provider_type'],
                    'totalProds' => $value[0]['totalProds']
                );
            }
            $this->Session->write('videodownloadCountArray', $videodownloadCountArray);
        }
    }

    /**
     * This function get Condition for Block if its found in session
     * @return string
     */
    function getBlockCondition()
    {
        if ($this->Session->read('block') == 'yes')
        {
            return array('Song.Advisory' => 'F');
        }
        else
        {
            return "";
        }
    }
    
    /**
     * This function return the string which contains the
     * 
     * @param array $song_array
     * @return String
     */
    function getRefAndProviderCondString($song_array)
    {
        $val = '';
        $val_provider_type = '';
        
        foreach ($song_array as $k => $v)
        {
            if (empty($val))
            {
                $val .= $v['Song']['ReferenceID'];
                $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
            }
            else
            {
                $val .= ',' . $v['Song']['ReferenceID'];
                $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
            }
        }
        return $val . "&" . $val_provider_type;
    }

}


?>
