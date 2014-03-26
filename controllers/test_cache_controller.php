<?php

class CacheController extends AppController {

    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song', 'Album', 'Library', 'Download', 'LatestDownload', 'Country', 'Video', 'Videodownload','LatestVideodownload','QueueList');
    var $components = array('Queue');
    
    function cacheLogin() {
        $libid = $_REQUEST['libid'];
        $patronid = $_REQUEST['patronid'];
        $date = time();
        $values = array(0 => $date, 1 => session_id());
        Cache::write("login_" . $libid . $patronid, $values);
        print "success";
        exit;
    }

    function cacheUpdate() {
        $libid = $_REQUEST['libid'];
        $patronid = $_REQUEST['patronid'];
        $date = time();
        $values = array(0 => $date, 1 => session_id());
        Cache::write("login_" . $libid . $patronid, $values);
        print "success";
        exit;
    }

    function cacheDelete() {
        $libid = $_REQUEST['libid'];
        $patronid = $_REQUEST['patronid'];
        Cache::delete("login_" . $libid . $patronid);
        print "success";
        exit;
    }

    //for caching data
    function cacheGenre() {
        set_time_limit(0);
        error_reporting(1); ini_set('display_errors', 1);
        
        $this->log("============" . date("Y-m-d H:i:s") . "===============", 'debug');
        echo "============" . date("Y-m-d H:i:s") . "===============";
        $territoryNames = array('US', 'CA', 'AU', 'NZ', 'IT');
        $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
        $siteConfigData = $this->Album->query($siteConfigSQL);
        $maintainLatestVideoDownload = false;
        $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);
        $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
        $siteConfigData = $this->Album->query($siteConfigSQL);
        $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);
        
             
        for ($i = 0; $i < count($territoryNames); $i++) {
            
            $territory = $territoryNames[$i];
            if (0 == $multiple_countries) {
                $countryPrefix = '';
                $this->Country->setTablePrefix('');
            } else {
                $countryPrefix = strtolower($territory) . "_";
                $this->Country->setTablePrefix($countryPrefix);
            }
            $this->log("Starting caching for $territory", 'debug');
        
            $this->Genre->Behaviors->attach('Containable');
            $this->Genre->recursive = 2;
           
            $genreAll = $this->Genre->find('all', array(
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

            if ((count($genreAll) > 0) && ($genreAll !== false)) {
                Cache::delete("genre" . $territory);
                Cache::write("genre" . $territory, $genreAll);
                $this->log("cache written for genre for $territory", "cache");
                echo "cache written for genre for $territory";
            } else {

                Cache::write("genre" . $territory, Cache::read("genre" . $territory));
                $this->log("no data available for genre" . $territory, "cache");
                echo "no data available for genre" . $territory;
            }
          
  
      
            
            $country = $territory;
            
            
                                
            if (!empty($country)) {
                if ($maintainLatestDownload) {

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
                $natTopDownloaded = $this->Album->query($sql);
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
                $data = $this->Album->query($sql_national_100);
                $this->log($sql_national_100, "cachequery");
                if ($ids_provider_type == "") {
                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
                    echo "ids_provider_type is set blank for " . $territory;
                }

                if (!empty($data)) {
                    Cache::delete("national" . $country);
                    foreach($data as $key => $value){
                            $albumArtwork = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
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
            $this->log("cache written for national top ten for $territory", 'debug');

print_r(Cache::read("national" . $country));
die;
          
            // Added caching functionality for featured videos
            $featured_videos_sql = "SELECT `FeaturedVideo`.`id`,`FeaturedVideo`.`ProdID`,`Video`.`Image_FileID`, `Video`.`VideoTitle`, `Video`.`ArtistText`, `Video`.`provider_type`, `File`.`CdnPath`, `File`.`SourceURL`, `File`.`SaveAsName`,`Country`.`SalesDate` FROM featured_videos as FeaturedVideo LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID LEFT JOIN File as File on File.FileID = Video.Image_FileID LEFT JOIN {$countryPrefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) WHERE `FeaturedVideo`.`territory` = '" . $territory . "' AND `Country`.`SalesDate` <= NOW()";
            $featuredVideos = $this->Album->query($featured_videos_sql);
            if (!empty($featuredVideos)) {
                foreach($featuredVideos as $key => $featureVideo){
                    $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$featureVideo['File']['CdnPath']."/".$featureVideo['File']['SourceURL']);
                    // print_r($featureVideo); die;
                    $videoImage = Configure::read('App.Music_Path').$videoArtwork;
                    $featuredVideos[$key]['videoImage'] = $videoImage;
                }                
                Cache::write("featured_videos" . $territory, $featuredVideos);
            }
            // End Caching functionality for featured videos
 
            
            // Added caching functionality for top video downloads
            $topDownloadSQL = "SELECT Videodownloads.ProdID, Video.ProdID, Video.provider_type, Video.VideoTitle, Video.ArtistText, File.CdnPath, File.SourceURL, COUNT(DISTINCT(Videodownloads.id)) AS COUNT, `Country`.`SalesDate` FROM videodownloads as Videodownloads LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) LEFT JOIN {$countryPrefix}countries as Country on (`Video`.`ProdID`=`Country`.`ProdID` AND `Video`.`provider_type`=`Country`.`provider_type`) LEFT JOIN libraries as Library ON Library.id=Videodownloads.library_id WHERE library_id=1 AND Library.library_territory='" . $territory . "' AND `Country`.`SalesDate` <= NOW() GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC";
            $topDownloads = $this->Album->query($topDownloadSQL);
            if(!empty($topDownloads)){
                foreach($topDownloads as $key => $topDownload)
                {
                     $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$topDownload['File']['CdnPath']."/".$topDownload['File']['SourceURL']);
                     // print_r($featureVideo);
                     $videoImage = Configure::read('App.Music_Path').$videoArtwork;
                     $topDownloads[$key]['videoImage'] = $videoImage;
                }                
                Cache::write("top_download_videos".$territory, $topDownloads);
            }
            // End Caching functionality for top video downloads
           
            // Added caching functionality for national top 10 videos   
            
            $country = $territory;

            if (!empty($country)) {
                if ($maintainLatestVideoDownload) {

                    $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
              FROM `latest_videodownloads` AS `Download` 
              LEFT JOIN libraries ON libraries.id=Download.library_id
              WHERE libraries.library_territory = '" . $country . "' 
              AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
              GROUP BY Download.ProdID 
              ORDER BY `countProduct` DESC 
              LIMIT 110";
                } else {

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
                $natTopDownloaded = $this->Album->query($sql);
                // echo $sql;
                // print_r($natTopDownloaded); die;
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

                // echo $sql_national_100_v; die;
                $data = $this->Album->query($sql_national_100_v);
                $this->log($sql_national_100_v, "cachequery");
                if ($ids_provider_type == "") {
                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
                    echo "ids_provider_type is set blank for " . $territory;
                }

                if (!empty($data)) {
                    Cache::delete("nationalvideos" . $country);
                    foreach($data as $key => $value){
                        $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                        $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;                    
                        $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                    }                    
                    Cache::write("nationalvideos" . $country, $data);
                    $this->log("cache written for national top ten  videos for $territory", "cache");
                    echo "cache written for national top ten  videos for $territory";
                } else {

                    Cache::write("nationalvideos" . $country, Cache::read("nationalvideos" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update national 100  videos for " . $territory, "cache");
                    echo "Unable to update national 100 videos for " . $territory;
                }
            }
            $this->log("cache written for national top ten  videos for $territory", 'debug');
            // End Caching functionality for national top 10 videos
            
            

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
        Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND  (Song.provider_type = Genre.provider_type)
                LEFT JOIN
        {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$territory') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate > NOW())
                LEFT JOIN
        PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type)
                INNER JOIN 
        Albums ON (Song.ReferenceID=Albums.ProdID) 
                INNER JOIN 
        File ON (Albums.FileID = File.FileID) 
    WHERE
            ( (Song.DownloadStatus = '1')  )   AND 1 = 1
    GROUP BY Song.ReferenceID
    ORDER BY Country.SalesDate ASC
    LIMIT 20       
STR;

//AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type))
            // echo $sql_coming_soon_s; die;
            $coming_soon_rs = $this->Album->query($sql_coming_soon_s);

            if (!empty($coming_soon_rs)) {
                foreach($coming_soon_rs as $key => $value)
                {     
                    $cs_img_url = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                    $cs_songImage =  Configure::read('App.Music_Path').$cs_img_url;
                    $coming_soon_rs[$key]['cs_songImage'] = $cs_songImage;
                }                
                Cache::write("coming_soon_songs" . $territory, $coming_soon_rs);
            }

            $this->log("cache written for coming soon for $territory", 'debug');
            // End Caching functionality for coming soon songs

            
            
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
        {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$territory') AND (Video.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate > NOW()) 
    LEFT JOIN
        PRODUCT ON (PRODUCT.ProdID = Video.ProdID) AND (PRODUCT.provider_type = Video.provider_type)
    LEFT JOIN
        File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
    WHERE
        ( (Video.DownloadStatus = '1')   )  AND 1 = 1
    GROUP BY Video.ProdID
    ORDER BY Country.SalesDate ASC
    LIMIT 20 	  
STR;
//AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type))

            $coming_soon_rv = $this->Album->query($sql_coming_soon_v);

            if (!empty($coming_soon_rv)) {
                foreach($coming_soon_videos as $key => $value)
                {                                                                                     

                    $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                    $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                    $coming_soon_videos[$key]['videoAlbumImage'] = $videoAlbumImage;
                }                
                Cache::write("coming_soon_videos." . $territory, $coming_soon_rv);
            }

            $this->log("cache written for coming soon videos for $territory", 'debug');
            //End Caching functionality for coming soon songs
            
           
        
            
            //Added caching functionality for us top 10 Songs           
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
               if($maintainLatestDownload){
                        $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                    FROM `latest_downloads` AS `Download` 
                    LEFT JOIN libraries ON libraries.id=Download.library_id
                    WHERE libraries.library_territory = '".$country."' 
                    AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                    GROUP BY Download.ProdID 
                    ORDER BY `countProduct` DESC 
                    LIMIT 110";
                } else {
                        $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                    FROM `downloads` AS `Download` 
                    LEFT JOIN libraries ON libraries.id=Download.library_id
                    WHERE libraries.library_territory = '".$country."' 
                    AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                    GROUP BY Download.ProdID 
                    ORDER BY `countProduct` DESC 
                    LIMIT 110";
                }
                $ids = '';
                $ids_provider_type = '';
                $USTop10Downloaded = $this->Album->query($sql);
                foreach ($USTop10Downloaded as $natTopSong) {
                    if (empty($ids)) {
                        $ids .= $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    }
                }

                if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false)) {
                    $this->log("download data not recevied for " . $territory, "cache");
                    echo "download data not recevied for " . $territory;
                }
                $data = array();

                $sql_US_TOP_10 =<<<STR
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
                                       Genre AS Genre ON (Genre.ProdID = Song.ProdID)
                                               LEFT JOIN
                                       {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
                                               LEFT JOIN
                                       PRODUCT ON (PRODUCT.ProdID = Song.ProdID) INNER JOIN Albums ON (Song.ReferenceID=Albums.ProdID) INNER JOIN File ON (Albums.FileID = File.FileID) 
                               WHERE
                                       ( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
                               GROUP BY Song.ProdID
                               ORDER BY FIELD(Song.ProdID,$ids) ASC
                               LIMIT 10 

STR;
                $data = $this->Album->query($sql_US_TOP_10);
                
               
                $this->log($sql_US_TOP_10, "cachequery");
                if ($ids_provider_type == "") {
                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
                    echo "ids_provider_type is set blank for " . $territory;
                }

                if (!empty($data)) {
                    foreach($data as $key => $value){
                         $songs_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                         $songs_img =  Configure::read('App.Music_Path').$songs_img;
                         $data[$key]['songs_img'] = $songs_img;
                    }                    
                    Cache::delete("national_us_top10_songs" . $country);
                    Cache::write("national_us_top10_songs" . $country, $data);
                    $this->log("cache written for US top ten for $territory", "cache");
                    echo "cache written for US top ten for $territory";
                } else {

                    Cache::write("national_us_top10_songs" . $country, Cache::read("national_us_top10_songs" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update US top ten for " . $territory, "cache");
                    echo "Unable to update US top ten for " . $territory;
                }
            }
            $this->log("cache written for US top ten for $territory", 'debug');
             //End Caching functionality for US TOP 10 Songs
            
         
            
             //Added caching functionality for us top 10 Album            
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
                if($maintainLatestDownload){
                            $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                        FROM `latest_downloads` AS `Download` 
                        LEFT JOIN libraries ON libraries.id=Download.library_id
                        WHERE libraries.library_territory = '".$country."' 
                        AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                        GROUP BY Download.ProdID 
                        ORDER BY `countProduct` DESC 
                        LIMIT 110";
                    } else {
                            $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                        FROM `downloads` AS `Download` 
                        LEFT JOIN libraries ON libraries.id=Download.library_id
                        WHERE libraries.library_territory = '".$country."' 
                        AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                        GROUP BY Download.ProdID 
                        ORDER BY `countProduct` DESC 
                        LIMIT 110";
                    }
                $ids = '';
                $ids_provider_type = '';
                $USTop10Downloaded = $this->Album->query($sql);
                foreach ($USTop10Downloaded as $natTopSong) {
                    if (empty($ids)) {
                        $ids .= $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    }
                }

                if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false)) {
                    $this->log("download data not recevied for " . $territory, "cache");
                    echo "download data not recevied for " . $territory;
                }
                $data = array();

                $album_sql_US_TOP_10 =<<<STR
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
                                       {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)  AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
                                               LEFT JOIN
                                       PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type) 
                                               INNER JOIN 
                                       Albums ON (Song.ReferenceID=Albums.ProdID) 
                                               INNER JOIN 
                                       File ON (Albums.FileID = File.FileID) 
                               WHERE
                                       ( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) )  AND 1 = 1
                               GROUP BY  Song.ReferenceID
                               ORDER BY count(Song.ProdID) DESC
                               LIMIT 10  

STR;
                $data = $this->Album->query($album_sql_US_TOP_10);
             
                $this->log($album_sql_US_TOP_10, "cachequery");
                if ($ids_provider_type == "") {
                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
                    echo "ids_provider_type is set blank for " . $territory;
                }

                if (!empty($data)) {
                    foreach($data as $key => $value){

                         $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                         $album_img =  Configure::read('App.Music_Path').$album_img;
                         $data[$key]['album_img'] = $album_img;
                    }                     
                    Cache::delete("national_us_top10_albums" . $country);
                    Cache::write("national_us_top10_albums" . $country, $data);
                    $this->log("cache written for US top ten Album for $territory", "cache");
                    echo "cache written for US top ten Album for $territory";
                } else {

                    Cache::write("national_us_top10_albums" . $country, Cache::read("national_us_top10_albums" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update US top ten Album for " . $territory, "cache");
                    echo "Unable to update US top ten Album for " . $territory;
                }
            }
            $this->log("cache written for US top ten Album for $territory", 'debug');
            //End Caching functionality for US TOP 10 Albums
            
            
          
           //Added caching functionality for us top 10 Video            
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
                   if($maintainLatestVideoDownload){                    

                        $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                        FROM `latest_videodownloads` AS `Download` 
                        LEFT JOIN libraries ON libraries.id=Download.library_id
                        WHERE libraries.library_territory = '".$country."' 
                        AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                        GROUP BY Download.ProdID 
                        ORDER BY `countProduct` DESC 
                        LIMIT 110";
                   } else {

                        $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                        FROM `videodownloads` AS `Download` 
                        LEFT JOIN libraries ON libraries.id=Download.library_id
                        WHERE libraries.library_territory = '".$country."' 
                        AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."' 
                        GROUP BY Download.ProdID 
                        ORDER BY `countProduct` DESC 
                        LIMIT 110";
                    }
                $ids = '';
                $ids_provider_type = '';
                $USTop10Downloaded = $this->Album->query($sql);
                foreach ($USTop10Downloaded as $natTopSong) {
                    if (empty($ids)) {
                        $ids .= $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $natTopSong['Download']['ProdID'];
                        $ids_provider_type .= ',' . "(" . $natTopSong['Download']['ProdID'] . ",'" . $natTopSong['Download']['provider_type'] . "')";
                    }
                }

                if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false)) {
                    $this->log("download data not recevied for " . $territory, "cache");
                    echo "download data not recevied for " . $territory;
                }
                $data = array();

                $video_sql_US_TOP_10 =<<<STR
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
                        {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW()) 
                                        LEFT JOIN
                        PRODUCT ON (PRODUCT.ProdID = Video.ProdID) AND (PRODUCT.provider_type = Video.provider_type)
                                        LEFT JOIN
                        File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
                WHERE
                                ( (Video.DownloadStatus = '1') AND ((Video.ProdID, Video.provider_type) IN ($ids_provider_type))  ) AND 1 = 1
                GROUP BY Video.ProdID
                ORDER BY FIELD(Video.ProdID, $ids) ASC
                LIMIT 10 
                  
STR;
                $data = $this->Album->query($video_sql_US_TOP_10);
                $this->log($video_sql_US_TOP_10, "cachequery");
                if ($ids_provider_type == "") {
                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
                    echo "ids_provider_type is set blank for " . $territory;
                }
                if (!empty($data)) {
                    foreach($data as $key => $value){
                        $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                        $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                        $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                    }                     
                    Cache::delete("national_us_top10_videos" . $country);
                    Cache::write("national_us_top10_videos" . $country, $data);
                    $this->log("cache written for US top ten video for $territory", "cache");
                    echo "cache written for US top ten video for $territory";
                } else {

                    Cache::write("national_us_top10_videos" . $country, Cache::read("national_us_top10_videos" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update US top ten video for " . $territory, "cache");
                    echo "Unable to update US top ten video for " . $territory;
                }
            }
            $this->log("cache written for US top ten video for $territory", 'debug');
            //End Caching functionality for US TOP 10 Videos
            
                  
     
            
            
            
            //Added caching functionality for new release Songs           
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
                
                $data = array();
                $sql_song_coming_soon =<<<STR
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
                            {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$territory') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW())
                                    LEFT JOIN
                            PRODUCT ON (PRODUCT.ProdID = Song.ProdID)  AND (PRODUCT.provider_type = Song.provider_type)
                                    INNER JOIN 
                            Albums ON (Song.ReferenceID=Albums.ProdID) 
                                    INNER JOIN 
                            File ON (Albums.FileID = File.FileID) 
                    WHERE
                            ( (Song.DownloadStatus = '1') )  AND 1 = 1                    
                    ORDER BY Country.SalesDate DESC
                    LIMIT 100
	  	
	  
STR;
                 
                $data = $this->Album->query($sql_song_coming_soon);
                $this->log($sql_song_coming_soon, "cachequery");
//                if ($ids_provider_type == "") {
//                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
//                    echo "ids_provider_type is set blank for " . $territory;
//                }
                
               

                if (!empty($data)) {
                    Cache::delete("new_releases_songs" . $country);
                    Cache::write("new_releases_songs" . $country, $data);
                    $this->log("cache written for new releases songs for $territory", "cache");
                    echo "cache written for new releases songs for $territory";
                } else {
                    Cache::write("new_releases_songs" . $country, Cache::read("new_releases_songs" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update new releases songs for " . $territory, "cache");
                    echo "Unable to update new releases songs for " . $territory;
                }
            }
            $this->log("cache written for new releases songs for $territory", 'debug');
            //End Caching functionality for new releases songs
            
            
            //Added caching functionality for new release Albums           
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
                
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
                            Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND  (Song.provider_type = Genre.provider_type)
                                    LEFT JOIN
                            {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$territory') AND (Song.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW()) 
                                    LEFT JOIN
                            PRODUCT ON (PRODUCT.ProdID = Song.ProdID)  AND (PRODUCT.provider_type = Song.provider_type)
                                    INNER JOIN 
                            Albums ON (Song.ReferenceID=Albums.ProdID) 
                                    INNER JOIN 
                            File ON (Albums.FileID = File.FileID) 
                    WHERE
                            ( (Song.DownloadStatus = '1')  )  AND 1 = 1                    
                    ORDER BY Country.SalesDate DESC
                    LIMIT 100
	  	
	  
STR;
                 
                $data = $this->Album->query($sql_album_new_release);
                $this->log($sql_album_new_release, "cachequery");
//                if ($ids_provider_type == "") {
//                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
//                    echo "ids_provider_type is set blank for " . $territory;
//                }

                if (!empty($data)) {
                    foreach($data as $key => $value){
                         $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                         $album_img =  Configure::read('App.Music_Path').$album_img;
                         $data[$key]['albumImage'] = $album_img;
                    }                    
                    Cache::delete("new_releases_albums" . $country);
                    Cache::write("new_releases_albums" . $country, $data);
                    $this->log("cache written for new releases albums for $territory", "cache");
                    echo "cache written for new releases albums for $territory";
                } else {
                    Cache::write("new_releases_albums" . $country, Cache::read("new_releases_albums" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update new releases albums for " . $territory, "cache");
                    echo "Unable to update new releases albums for " . $territory;
                }
            }
            $this->log("cache written for new releases albums for $territory", 'debug');
            //End Caching functionality for new releases albums
            
            
            
            
            //Added caching functionality for new release videos           
            $country = $territory;
            if ( !empty($country ) && ( $territory == "US" ) ) {
                
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
                Image_Files.SourceURL,
                PRODUCT.pid
                FROM
                video AS Video
                LEFT JOIN
                File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
                LEFT JOIN
                Genre AS Genre ON (Genre.ProdID = Video.ProdID) AND (Video.provider_type = Genre.provider_type)
                LEFT JOIN
                {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$territory') AND (Video.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW()) 
                LEFT JOIN
                PRODUCT ON (PRODUCT.ProdID = Video.ProdID) AND (PRODUCT.provider_type = Video.provider_type)
                LEFT JOIN
                File AS Image_Files ON (Video.Image_FileID = Image_Files.FileID) 
                WHERE
                ( (Video.DownloadStatus = '1')   )  AND 1 = 1
                GROUP BY Video.ProdID
                ORDER BY Country.SalesDate DESC
                LIMIT 100	  
STR;
                 
                $data = $this->Album->query($sql_video_new_release);
                $this->log($sql_video_new_release, "cachequery");
//                if ($ids_provider_type == "") {
//                    $this->log("ids_provider_type is set blank for " . $territory, "cache");
//                    echo "ids_provider_type is set blank for " . $territory;
//                }

                if (!empty($data)) {
                    foreach($data as $key => $value){
                          $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                          $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                          $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                    }                    
                    Cache::delete("new_releases_videos" . $country);
                    Cache::write("new_releases_videos" . $country, $data);
                    $this->log("cache written for new releases videos for $territory", "cache");
                    echo "cache written for new releases videos for $territory";
                } else {
                    Cache::write("new_releases_videos" . $country, Cache::read("new_releases_videos" . $country));
                    echo "Unable to update key";
                    $this->log("Unable to update new releases videos for " . $territory, "cache");
                    echo "Unable to update new releases videos for " . $territory;
                }
            }
            $this->log("cache written for new releases albums for $territory", 'debug');
            //End Caching functionality for new releases videos         
            
         
           
 
            // Checking for download status
            $featured = array();
            $ids = '';
            $ids_provider_type = '';
            $featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $territory, 'Featuredartist.language' => Configure::read('App.LANGUAGE')), 'recursive' => -1));
            foreach ($featured as $k => $v) {
                if ($v['Featuredartist']['album'] != 0) {
                    if (empty($ids)) {
                        $ids .= $v['Featuredartist']['album'];
                        $ids_provider_type .= "(" . $v['Featuredartist']['album'] . ",'" . $v['Featuredartist']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $v['Featuredartist']['album'];
                        $ids_provider_type .= ',' . "(" . $v['Featuredartist']['album'] . ",'" . $v['Featuredartist']['provider_type'] . "')";
                    }
                }
            }

            if ((count($featured) < 1) || ($featured === false)) {
                $this->log("featured artist data is not available for" . $territory, "cache");
                echo "featured artist data is not available for" . $territory;
            }

            if ($ids != '') {
                $this->Album->recursive = 2;
                $featured = $this->Album->find('all', array('conditions' =>
                    array('and' =>
                        array(
                            array("(Album.ProdID, Album.provider_type) IN (" . rtrim($ids_provider_type, ",'") . ")", "Country.Territory" => $territory, "Album.provider_type = Country.provider_type"),
                        ), "1 = 1 GROUP BY Album.ProdID"
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
                        'Country' => array(
                            'fields' => array(
                                'Country.Territory'
                            )
                        ),
                        'Files' => array(
                            'fields' => array(
                                'Files.CdnPath',
                                'Files.SaveAsName',
                                'Files.SourceURL'
                            ),
                        )
                    ), 'order' => array('Country.SalesDate' => 'desc')
                        )
                );
            } else {
                $featured = array();
            }

            if (empty($featured)) {
                Cache::write("featured" . $territory, Cache::read("featured" . $territory));
            } else {
                foreach($featured as $k => $v){
                    $albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
                    $image =  Configure::read('App.Music_Path').$albumArtwork;
                    $featured[$k]['featuredImage'] = $image;
                }                
                Cache::delete("featured" . $territory);
                Cache::write("featured" . $territory, $featured);
            }

            $this->log("cache written for featured artists for $territory", 'debug');
            $this->log("cache written for featured artists for: $territory", "cache");
            echo "cache written for featured artists for: $territory";

            $genres = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");

            foreach ($genres as $genre) {
                $genre_data = array();
                //echo $territory;

          if ($maintainLatestDownload) {
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
              AND Song.DownloadStatus = '1'               
              AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
          GROUP BY latest_downloads.ProdID
          ORDER BY countProduct DESC
          LIMIT 10
          ";
                } else {
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
            AND Song.DownloadStatus = '1' 			
            AND created BETWEEN '" . Configure::read('App.tenWeekStartDate') . "' AND '" . Configure::read('App.curWeekEndDate') . "'
        GROUP BY downloads.ProdID
        ORDER BY countProduct DESC
        LIMIT 10
        ";
            }
                $data = $this->Album->query($restoregenre_query);
                $this->log($restoregenre_query, "cachequery");
                if (!empty($data)) {
                    Cache::delete($genre . $territory);
                    Cache::write($genre . $territory, $data);
                    $this->log("cache written for: $genre $territory", "cache");
                    echo "cache written for: $genre $territory";
                } else {

                    Cache::write($genre . $territory, Cache::read($genre . $territory));
                    echo "Unable to update key";
                    $this->log("Unable to update key for: $genre $territory", "cache");
                    echo "Unable to update key for: $genre $territory";
                }
            }
            $this->log("cache written for top 10 for different genres for $territory", 'debug');

        
     
        
            
            //-------------------------------------------ArtistText Pagenation Start------------------------------------------------------
            try {
     
        
                $this->log("Starting to cache Artist Browsing Data for each genre for $territory",'debug');

                $country = $territory;
                $condition = "";
                $this->Song->unbindModel(array('hasOne' => array('Participant')));
                $this->Song->unbindModel(array('hasOne' => array('Country')));
                $this->Song->unbindModel(array('hasOne' => array('Genre')));
                $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
                $this->Song->Behaviors->attach('Containable');
                $this->Song->recursive = 0;
                $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');

                $this->paginate = array(
                    'conditions' => $gcondition,
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'extra' => array('chk' => 1),
                    'order' => 'TRIM(Song.ArtistText) ASC',
                    'limit' => '60',
                    'cache' => 'yes',
                    'check' => 2,
                    'all_query'=> true,
                    'all_country'=> "find_in_set('\"$country\"',Song.Territory) > 0",
                    'all_condition'=>((is_array($condition) && isset($condition['Song.ArtistText LIKE']))? "Song.ArtistText LIKE '".$condition['Song.ArtistText LIKE']."'":(is_array($condition)?$condition[0]:$condition))
                );
                $allArtists = $this->paginate('Song');
                
              
                
                for($j = 65;$j < 93;$j++){
                    
                    $alphabet = chr($j);
                    if($alphabet == '[') {
                    $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
                    }
                    elseif($j == 92) {
                    $condition = "";
                    }
                    elseif($alphabet != '') {
                    $condition = array('Song.ArtistText LIKE' => $alphabet.'%');
                    }
                    else {
                    $condition = "";
                    }
                    $this->Song->unbindModel(array('hasOne' => array('Participant')));
                    $this->Song->unbindModel(array('hasOne' => array('Country')));
                    $this->Song->unbindModel(array('hasOne' => array('Genre')));
                    $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
                    $this->Song->Behaviors->attach('Containable');
                    $this->Song->recursive = 0;
                    $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');
         
                    $this->paginate = array(
                    'conditions' => $gcondition,
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'extra' => array('chk' => 1),
                    'order' => 'TRIM(Song.ArtistText) ASC',
                    'limit' => '60',
                    'cache' => 'yes',
                    'check' => 2,
                    'all_query'=> true,
                    'all_country'=> "find_in_set('\"$country\"',Song.Territory) > 0",
                    'all_condition'=>((is_array($condition) && isset($condition['Song.ArtistText LIKE']))? "Song.ArtistText LIKE '".$condition['Song.ArtistText LIKE']."'":(is_array($condition)?$condition[0]:$condition))
                    );
                    $allArtists = $this->paginate('Song');
                    $this->log("$totalPages cached for All Artists ".$alphabet."-".$territory,'debug');
                    $this->log("$totalPages cached for All Artists $alphabet - $territory", "cache");
                }
                

                $this->Song->bindmodel(array('hasOne'=>array(
                        'Genre' => array(
                        'className' => 'Genre',
                        'foreignKey' => 'ProdID'
                        ),
                        'Country' => array(
                            'className' => 'Country',
                            'foreignKey' => 'ProdID'
                        )
                    )
                    )
                );

                foreach($genreAll as $genreRow){
                    $genre = mysql_real_escape_string(addslashes($genreRow['Genre']['Genre']));
                    $condition = "";
                    $this->Song->unbindModel(array('hasOne' => array('Participant')));
                    $this->Song->unbindModel(array('hasOne' => array('Country')));
                    $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
                    $this->Song->Behaviors->attach('Containable');
                    $this->Song->recursive = 0;
                    $this->paginate = array(
                        'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
                        'fields' => array('DISTINCT Song.ArtistText'),
                        'contain' => array(
                        'Genre' => array(
                            'fields' => array(
                            'Genre.Genre'
                            )
                        ),
                        ),
                        'extra' => array('chk' => 1),
                        'order' => 'TRIM(Song.ArtistText) ASC',
                        'limit' => '60', 'cache' => 'no','check' => 2
                    );
                    $allArtists = $this->paginate('Song');
                    $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'debug');
                    $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'cache');
                    for($k = 65;$k < 93;$k++){
                    $alphabet = chr($k);
                    if($alphabet == '[') {
                        $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
                    }
                    elseif($k == 92) {
                        $condition = "";
                    }
                    elseif($alphabet != '') {
                        $condition = array('Song.ArtistText LIKE' => $alphabet.'%');
                    }
                    else {
                        $condition = "";
                    }
                    $this->Song->unbindModel(array('hasOne' => array('Participant')));
                    $this->Song->unbindModel(array('hasOne' => array('Country')));
                    $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
                    $this->Song->Behaviors->attach('Containable');
                    $this->Song->recursive = 0;
                    $this->paginate = array(
                        'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
                        'fields' => array('DISTINCT Song.ArtistText'),
                        'contain' => array(
                        'Genre' => array(
                            'fields' => array(
                            'Genre.Genre'
                            )
                        ),
                        ),
                        'extra' => array('chk' => 1),
                        'order' => 'TRIM(Song.ArtistText) ASC',
                        'limit' => '60', 'cache' => 'no','check' => 2
                    );
                    $this->Song->unbindModel(array('hasOne' => array('Participant')));
                    $allArtists = $this->paginate('Song');
                    $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'debug');
                    $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'cache');
                    }
                }
                $this->Song->bindmodel(array('hasOne'=>array(
                        'Genre' => array(
                        'className' => 'Genre',
                        'foreignKey' => 'ProdID'
                        ),
                        'Country' => array(
                            'className' => 'Country',
                            'foreignKey' => 'ProdID'
                        )
                    ), 'belongsTo'=>array('Sample_Files' => array(
                        'className' => 'Files',
                        'foreignKey' => 'Sample_FileID'
                        ),
                        'Full_Files' => array(
                        'className' => 'Files',
                        'foreignKey' => 'FullLength_FileID'
                        )
                    )
                    )
                );

                } catch(Exception $e) {

                $this->log("Artist Pagenation Mesg : ".$e->getMessage(), "cache");
                $this->log("Artist Pagenation      :   $country $alphabet $genre", "cache");
                $this->log("Artist Pagenation Query: ".$this->Song->lastQuery(), "cache");

                }
            //-------------------------------------------ArtistText Pagenation End----------------------------------------
                
             
                 
        }
      

       
        //--------------------------------Default Freegal Queues Start----------------------------------------------------               
        $cond = array('queue_type' => 1, 'status' => '1');
        //Unbinded User model
        $this->QueueList->unbindModel(
            array('belongsTo' => array('User'),'hasMany' => array('QueueDetail'))
        );
        //fetched the default list
        $queueData = $this->QueueList->find('all', array(
        'conditions' => $cond,
        'fields' => array('queue_id','queue_name'),
        'order' => 'QueueList.created DESC',
        'limit' => 100
        ));        
        
        //freegal Query Cache set
        if ((count($queueData) < 1) || ($queueData === false)) {            
            Cache::write(defaultqueuelist, Cache::read("defaultqueuelist"));
            $this->log("Freegal Defaut Queues returns null ", "cache");
            echo "<br /> Freegal Defaut Queues returns null<br />";
        } else {           
            Cache::delete("defaultqueuelist");
            Cache::write("defaultqueuelist", $queueData);
            
            //library top 10 cache set
            $this->log("Freegal Defaut Queues cache set", "cache");
            echo "<br />Freegal Defaut Queues cache set <br />";
        }  
        
        //set the variable for each freegal default queue 
        foreach($queueData as $value){
           $defaultQueueId = $value['QueueList']['queue_id'];
           $defaultQueueName = $value['QueueList']['queue_name'];      
           $eachQueueDetails =  $this->Queue->getQueueDetails($defaultQueueId);
           
           if ((count($eachQueueDetails) < 1) || ($eachQueueDetails === false)) {
                $this->log("Freegal Defaut Queues ". $defaultQueueName ."( ".$defaultQueueId." )"." returns null ", "cache");
                echo "<br /> Freegal Defaut Queues ". $defaultQueueName ."( ".$defaultQueueId." )"." returns null<br />";
           } else {                 
                Cache::write("defaultqueuelistdetails".$defaultQueueId, $eachQueueDetails);       
                $this->log("Freegal Defaut Queues ". $defaultQueueName ."( ".$defaultQueueId." )"." cache set", "cache");
                echo "<br />Freegal Defaut Queues ". $defaultQueueName ."( ".$defaultQueueId." )"." cache set <br />";              
           }            
        }     
        //--------------------------------Default Freegal Queues End--------------------------------------------------------------
       
        
        //--------------------------------set each music video in the cache start-------------------------------------------------        
        
        
       $musicVideoRecs = $this->Video->find('all', array('conditions' => array('DownloadStatus' => 1),'fields' => 'Video.ProdID'));
       
       foreach($musicVideoRecs as $musicVideoRec){
           
           $indiMusicVidID =  $musicVideoRec['Video']['ProdID'];
           
           $individualVideoSQL  =
            "SELECT Video.ProdID, Video.ReferenceID,  Video.VideoTitle, Video.ArtistText, Video.FullLength_Duration,
            Video.CreatedOn, Video.Image_FileID, Video.provider_type, Video.Genre,
            Full_Files.CdnPath,Full_Files.SaveAsName,File.CdnPath,File.SourceURL,File.SaveAsName
            FROM video as Video            
            LEFT JOIN
            File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)                                 
            LEFT JOIN
            PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  AND (PRODUCT.provider_type = Video.provider_type)
            INNER JOIN File ON (Video.Image_FileID = File.FileID)
            Where Video.DownloadStatus = '1' AND Video.ProdID = ".$indiMusicVidID;

           $EachVideosData = $this->Video->query($individualVideoSQL);
           if ((count($EachVideosData) < 1) || ($EachVideosData === false)) {
                $this->log("Music video id $indiMusicVidID returns null ", "cache");
                echo "<br /> Music video id $indiMusicVidID returns null<br />";
           } else {                 
                Cache::write("musicVideoDetails".$indiMusicVidID, $EachVideosData);       
                $this->log("Music video id $indiMusicVidID cache set", "cache");
                echo "<br />Music video id $indiMusicVidID cache set <br />";              
           }         
       } 
       
       //--------------------------------set each music video in the cache end---------------------------------------------------
        
       
        
        
        
       

        //--------------------------------Library Top Ten Start--------------------------------------------------------------------

        $libraryDetails = $this->Library->find('all', array(
            'fields' => array('id', 'library_territory'),
            'conditions' => array('library_status' => 'active'),
            'recursive' => -1
                )
        );

        foreach ($libraryDetails AS $key => $val) {

            $libId = $val['Library']['id'];
            $country = $val['Library']['library_territory'];

            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            if (0 == $multiple_countries) {
                $countryPrefix = '';
                $this->Country->setTablePrefix('');
            } else {
                $countryPrefix = strtolower($country) . "_";
                $this->Country->setTablePrefix($countryPrefix);
            }

            $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
            $siteConfigData = $this->Album->query($siteConfigSQL);
            $maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue'] == 1) ? true : false);

            
            //this is for my library songs start
            
            if ($maintainLatestDownload) {
                $download_src = 'LatestDownload';
                $topDownloaded = $this->LatestDownload->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
            } else {
                $download_src = 'Download';
                $topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
            }

            $this->log("$download_src - $libId - $country", "cache");

            $ids = '';
            $ioda_ids = array();
            $sony_ids = array();
            $sony_ids_str = '';
            $ioda_ids_str = '';
            $ids_provider_type = '';
            foreach ($topDownloaded as $k => $v) {
                if ($maintainLatestDownload) {
                    if (empty($ids)) {
                        $ids .= $v['LatestDownload']['ProdID'];
                        $ids_provider_type .= "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $v['LatestDownload']['ProdID'];
                        $ids_provider_type .= ',' . "(" . $v['LatestDownload']['ProdID'] . ",'" . $v['LatestDownload']['provider_type'] . "')";
                    }
                    if ($v['LatestDownload']['provider_type'] == 'sony') {
                        $sony_ids[] = $v['LatestDownload']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['LatestDownload']['ProdID'];
                    }
                } else {
                    if (empty($ids)) {
                        $ids .= $v['Download']['ProdID'];
                        $ids_provider_type .= "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                    } else {
                        $ids .= ',' . $v['Download']['ProdID'];
                        $ids_provider_type .= ',' . "(" . $v['Download']['ProdID'] . ",'" . $v['Download']['provider_type'] . "')";
                    }
                    if ($v['Download']['provider_type'] == 'sony') {
                        $sony_ids[] = $v['Download']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['Download']['ProdID'];
                    }
                }
            }

            if ((count($topDownloaded) < 1) || ($topDownloaded === false)) {
                $this->log("top download is not available for library: $libId - $country", "cache");
            }

            if ($ids != '') {
                if (!empty($sony_ids)) {
                    $sony_ids_str = implode(',', $sony_ids);
                }
                if (!empty($ioda_ids)) {
                    $ioda_ids_str = implode(',', $ioda_ids);
                }
                if (!empty($sony_ids_str) && !empty($ioda_ids_str)) {
                    $top_ten_condition = "((Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony') OR (Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda'))";
                } else if (!empty($sony_ids_str)) {
                    $top_ten_condition = "(Song.ProdID IN (" . $sony_ids_str . ") AND Song.provider_type='sony')";
                } else if (!empty($ioda_ids_str)) {
                    $top_ten_condition = "(Song.ProdID IN (" . $ioda_ids_str . ") AND Song.provider_type='ioda')";
                }

                $this->Song->recursive = 2;
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
                                 {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type) AND (Country.Territory = '$country') AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type) 
                                                INNER JOIN 
                                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                                INNER JOIN 
                                        File ON (Albums.FileID = File.FileID)
				WHERE
					((Song.DownloadStatus = '1') AND (($top_ten_condition_songs) )  AND 1 = 1)
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;
                $topDownload = $this->Album->query($topDownloaded_query);
            } else {
                $topDownload = array();
            }

            //library top 10 cache set
            if ((count($topDownload) < 1) || ($topDownload === false)) {
                Cache::write("lib" . $libId, Cache::read("lib" . $libId));
                $this->log("topDownloaded_query songs  returns null for lib: $libId $country", "cache");
                echo "<br /> library top 10 songs returns null for lib: $libId $country <br />";
            } else {
                foreach($topDownload as $key => $value){
                     $songs_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                     $songs_img =  Configure::read('App.Music_Path').$songs_img;
                     $topDownload[$key]['songs_img'] = $songs_img;
                }                
                Cache::delete("lib" . $libId);
                Cache::write("lib" . $libId, $topDownload);
                //library top 10 cache set
                $this->log("library top 10 songs cache set for lib: $libId $country", "cache");
                echo "<br />library top 10 songs cache set for lib: $libId $country <br />";
            }
           
            //library top 10 cache set for songs end
            
            
            //library top 10 cache set for albums start            
            if($maintainLatestDownload){
                $download_src = 'LatestDownload';
                $topDownloaded_albums = $this->LatestDownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));                                                        
            } else {
                $download_src = 'Download';
                $topDownloaded_albums = $this->Download->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
            }
            
            
            $this->log("$download_src - $libId - $country", "cache");

            $ids = '';
            $ioda_ids = array();
            $sony_ids = array();
            $sony_ids_str = '';
            $ioda_ids_str = '';
            $ids_provider_type = '';
            foreach ($topDownloaded_albums as $k => $v) {
                if ($maintainLatestDownload) {
                    if(empty($ids)){
                        $ids .= $v['LatestDownload']['ProdID'];
                        $ids_provider_type_album .= "(" . $v['LatestDownload']['ProdID'] .",'" . $v['LatestDownload']['provider_type'] ."')";
                    } else {
                        $ids .= ','.$v['LatestDownload']['ProdID'];
                        $ids_provider_type_album .= ','. "(" . $v['LatestDownload']['ProdID'] .",'" . $v['LatestDownload']['provider_type'] ."')";
                    }
                    if($v['LatestDownload']['provider_type'] == 'sony'){
                        $sony_ids[] = $v['LatestDownload']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['LatestDownload']['ProdID'];
                    }
                } else {
                    
                    if(empty($ids)){
                        $ids .= $v['Download']['ProdID'];
                        $ids_provider_type_album .= "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
                    } else {
                        $ids .= ','.$v['Download']['ProdID'];
                        $ids_provider_type_album .= ','. "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
                    }
                    if($v['Download']['provider_type'] == 'sony'){
                        $sony_ids[] = $v['Download']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['Download']['ProdID'];
                    }
                }
            }

            if ((count($topDownloaded_albums) < 1) || ($topDownloaded_albums === false)) {
                $this->log("top download is not available for library: $libId - $country", "cache");
            }

            if ($ids != '') {
                if(!empty($sony_ids)){
                        $sony_ids_str = implode(',',$sony_ids);
                }
                if(!empty($ioda_ids)){
                        $ioda_ids_str = implode(',',$ioda_ids);
                }
                if(!empty($sony_ids_str) && !empty($ioda_ids_str)){
                        $top_ten_condition_albums = "((Song.ProdID IN (".$sony_ids_str.") AND Song.provider_type='sony') OR (Song.ProdID IN (".$ioda_ids_str.") AND Song.provider_type='ioda'))";                                       
                } else if(!empty($sony_ids_str)){
                        $top_ten_condition_albums = "(Song.ProdID IN (".$sony_ids_str.") AND Song.provider_type='sony')";                                        

                } else if(!empty($ioda_ids_str)){
                        $top_ten_condition_albums = "(Song.ProdID IN (".$ioda_ids_str.") AND Song.provider_type='ioda')";                                        
                }

                $this->Song->recursive = 2;
                $topDownloaded_query_albums =<<<STR
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
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID) AND (PRODUCT.provider_type = Song.provider_type)
                                                INNER JOIN 
                                        Albums ON (Song.ReferenceID=Albums.ProdID) 
                                                INNER JOIN 
                                        File ON (Albums.FileID = File.FileID)
				WHERE
					((Song.DownloadStatus = '1') AND (($top_ten_condition_albums)  )  AND 1 = 1)
				GROUP BY  Song.ReferenceID
				ORDER BY count(Song.ProdID) DESC
				LIMIT 10
STR;
                $topDownload = $this->Album->query($topDownloaded_query_albums);
            } else {
                $topDownload = array();
            }

            //library top 10 cache set
            if ((count($topDownload) < 1) || ($topDownload === false)) {
                Cache::write("lib_album" . $libId, Cache::read("lib_album" . $libId));
                $this->log("topDownloaded_query albums returns null for lib: $libId $country", "cache");
                echo "<br /> library top 10 albums returns null for lib: $libId $country <br />";
            } else {
                foreach($topDownload as $key => $value){
                     $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                     $album_img =  Configure::read('App.Music_Path').$album_img;
                     $topDownload[$key]['album_img'] = $album_img;
                }                
                Cache::delete("lib_album" . $libId);
                Cache::write("lib_album" . $libId, $topDownload);
                //library top 10 cache set
                $this->log("library top 10 albums cache set for lib: $libId $country", "cache");
                echo "<br />library top 10 albums cache set for lib: $libId $country <br />";
            }
           
           //library top 10 cache set for albums end
            
           //library top 10 cache set for videos start 
            if($maintainLatestDownload){
                $download_src = 'LatestDownload';
                $topDownloaded_videos = $this->LatestVideodownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));                                                        
            } else {
                $download_src = 'Download';
                $topDownloaded_videos = $this->Videodownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
            }
            
            
            $this->log("$download_src - $libId - $country", "cache");

            $ids = '';
            $ioda_ids = array();
            $sony_ids = array();
            $sony_ids_str = '';
            $ioda_ids_str = '';
            $ids_provider_type = '';
            foreach ($topDownloaded_videos as $k => $v) {
                if ($maintainLatestDownload) {
                    if(empty($ids)){
                        $ids .= $v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['LatestVideodownload']['ProdID'] .",'" . $v['LatestVideodownload']['provider_type'] ."')";
                    } else {
                        $ids .= ','.$v['LatestVideodownload']['ProdID'];
                        $ids_provider_type_video .= ','. "(" . $v['LatestVideodownload']['ProdID'] .",'" . $v['LatestVideodownload']['provider_type'] ."')";
                    }
                    if($v['LatestVideodownload']['provider_type'] == 'sony'){
                        $sony_ids[] = $v['LatestVideodownload']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['LatestVideodownload']['ProdID'];
                    }
                } else {
                    
                    if(empty($ids)){
                        $ids .= $v['Download']['ProdID'];
                        $ids_provider_type_video .= "(" . $v['Videodownload']['ProdID'] .",'" . $v['Videodownload']['provider_type'] ."')";
                    } else {
                        $ids .= ','.$v['Download']['ProdID'];
                        $ids_provider_type_video .= ','. "(" . $v['Videodownload']['ProdID'] .",'" . $v['Videodownload']['provider_type'] ."')";
                    }
                    if($v['Download']['provider_type'] == 'sony'){
                        $sony_ids[] = $v['Videodownload']['ProdID'];
                    } else {
                        $ioda_ids[] = $v['Videodownload']['ProdID'];
                    }
                }
            }

            if ((count($topDownloaded_videos) < 1) || ($topDownloaded_videos === false)) {
                $this->log("top download is not available for library: $libId - $country", "cache");
            }

            if ($ids != '') {
                if(!empty($sony_ids)){
                        $sony_ids_str = implode(',',$sony_ids);
                }
                if(!empty($ioda_ids)){
                        $ioda_ids_str = implode(',',$ioda_ids);
                }
                if(!empty($sony_ids_str) && !empty($ioda_ids_str)){
                        $top_ten_condition_videos = "((Video.ProdID IN (".$sony_ids_str.") AND Video.provider_type='sony') OR (Video.ProdID IN (".$ioda_ids_str.") AND Video.provider_type='ioda'))";                                       
                } else if(!empty($sony_ids_str)){
                        $top_ten_condition_videos = "(Video.ProdID IN (".$sony_ids_str.") AND Video.provider_type='sony')";                                        

                } else if(!empty($ioda_ids_str)){
                        $top_ten_condition_videos = "(Video.ProdID IN (".$ioda_ids_str.") AND Video.provider_type='ioda')";                                        
                }
				

                $this->Song->recursive = 2;
		$topDownloaded_query_videos =<<<STR
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
                                        Sample_Files.FileID,
					PRODUCT.pid
				FROM
					video AS Video
						LEFT JOIN
					File AS Sample_Files ON (Video.Sample_FileID = Sample_Files.FileID)
						LEFT JOIN
					File AS Full_Files ON (Video.FullLength_FileID = Full_Files.FileID)
						LEFT JOIN
					Genre AS Genre ON (Genre.ProdID = Video.ProdID) AND (Video.provider_type = Genre.provider_type)
						LEFT JOIN
                                 {$countryPrefix}countries AS Country ON (Country.ProdID = Video.ProdID) AND (Country.Territory = '$country') AND (Video.provider_type = Country.provider_type) AND (Country.SalesDate != '') AND (Country.SalesDate < NOW())
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Video.ProdID)  AND (PRODUCT.provider_type = Video.provider_type)
                                                INNER JOIN 
                                        File ON (Video.Image_FileID = File.FileID)
				WHERE
					((Video.DownloadStatus = '1') AND (($top_ten_condition_videos))  AND 1 = 1)
				GROUP BY Video.ProdID
				ORDER BY FIELD(Video.ProdID,
						$ids) ASC
				LIMIT 10
STR;
                $topDownload = $this->Album->query($topDownloaded_query_videos);
            } else {
                $topDownload = array();
            }

            //library top 10 cache set
            if ((count($topDownload) < 1) || ($topDownload === false)) {
                Cache::write("lib_video" . $libId, Cache::read("lib_video" . $libId));
                $this->log("topDownloaded_query videos returns null for lib: $libId $country", "cache");
                echo "<br /> library top 10 videos returns null for lib: $libId $country <br />";
            } else {
                foreach($topDownload as $key => $value){
                    $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                    $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                    $topDownload[$key]['videoAlbumImage'] = $videoAlbumImage;
                }                
                Cache::delete("lib_video" . $libId);
                Cache::write("lib_video" . $libId, $topDownload);
                //library top 10 cache set
                $this->log("library top 10 videos cache set for lib: $libId $country", "cache");
                echo "<br />library top 10 videos cache set for lib: $libId $country <br />";
            }
            
           //library top 10 cache set for videos end
           
        }
        
    

        //--------------------------------------Library Top Ten End for Songs,Albums and Videos----------------------------------------------

        echo "============" . date("Y-m-d H:i:s") . "===============";
        $this->requestAction('/Resetcache/genrateXML');
        exit;
       
   
          
    }

}
