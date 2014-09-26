<?php

/*
  File Name : common.php
  File Description : Component page for all functionalities.
  Author : m68interactive
 */

Class CommonComponent extends Object
{

    var $components = array('Session', 'Streaming', 'Queue','Email','CacheHandler');
    var $uses = array('Token','FeaturedVideo');

    /*
     * Function Name : getGenres
     * Function Description : This function is used to get all genres.
     */

    function getGenres($territory)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);
        $genreInstance = ClassRegistry::init('Genre');
        $songInstance = ClassRegistry::init('Song');
        $genreInstance->Behaviors->attach('Containable');
        $genreInstance->recursive = 2;
        $genreList = array();
        
        $genreAll = $genreInstance->find('all', array(
            'conditions' =>
            array('and' =>
                array(
                    array('Country.Territory' => $territory,'Country.DownloadStatus' => 1, "Genre.Genre NOT IN('Porn Groove')")
                )
            ),
            'fields' => array(
                'Genre.expected_genre'
            ),
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.Territory'
                    )
                ),
            ), 'group' => 'Genre.Genre'
        ));
      
        
        $this->log("Each Genre Artist value checked finished for $territory", "genreLogs");      
        
        $combine_genre  =   array();
        if ((count($genreAll) > 0) && ($genreAll !== false))
        {                
            for($count=0; $count<count($genreAll);$count++)
            {                               
                array_push($combine_genre, str_replace("\\", "", $genreAll[$count]['Genre']['expected_genre']));
            }
            $combine_genre  = array_unique($combine_genre);
            sort($combine_genre);
            $combine_genre = array_filter($combine_genre);
            
            Cache::write("allgenre" . $territory, $combine_genre,'GenreCache');
            $this->log("cache written for genre for $territory", "cache");
            
            /*Cache::write("genre" . $territory, $genreAll,'GenreCache');
            $this->log("cache written for genre for $territory", "cache");*/
        }      
        
        return $combine_genre;
         
    }
    
     /*
     * @func runGenreCacheFromShell
     * @desc This function is used to call all functions for setting Genre page cache variables and run from shell
     */    
    function runGenreCacheFromShell(){
        set_time_limit(0); 
        $this->log("shel cron log genreated", "shellCronLog");    
        $territoriesList = $this->getTerritories();       
        foreach($territoriesList as $territory){           
            $this->setArtistText($territory);            
        }
       
    }
    
    /*
     * Function Name : setArtistText
     * Function Description : This function is used to setArtistText.
     * all this function create the cache variable for Genre and run from the cache cron
     * 
     * @paran $territory varChar 'territory value'
     * 
     */
    function setArtistText($territory){
        set_time_limit(0); 
    
              
        //set the aritst cache for specific Genre
        $genreAll = $this->getGenres($territory);
        //commented but need sometime for testing perpuse
        //$genreAll = Cache::read("genre" . $territory);
       
        sleep(1);
        //add All filter
        array_unshift($genreAll, "All");      
        // create cache one by one for each Genre
        foreach($genreAll as $genreEach){
             //fetch the alphabets
             for($k = 63;$k < 91;$k++){
                 
                $artistFilter = chr($k);             
                
                if($k==63){
                    $artistFilter = 'All';
                }
                
                if($k==64){
                    $artistFilter = 'spl';
                }             
                    
                //this code is commented for some testing               
                //$totalPages = $this->checkGenrepagesCount($territory,$genreEach,$artistFilter);
                
                //for fetching two pages for per Genre with per Artist filter
                $totalPages = 1;
                
                //set cache variable one by one
                for( $i=1;$i<=$totalPages;$i++ ){                     
                   $this->getArtistText($genreEach,$territory,$artistFilter,$i);                    
                }                
            }      
        }       
    }   
    
    
     /*
     * Function Name : getArtistText
     * Function Description : get first 120 artist for selected Genre
     * @paran $genreValue varChar 'genre value'
     * @paran $territory varChar 'territory value'
     * @paran $artistFilter varChar 'artist filter value'
     * @paran $pageNo int 'page number value'
     * 
     * @return  $artistListResults array
     */
     function getArtistText($genreValue,$territory,$artistFilter='',$pageNo=1){        
        set_time_limit(0);  
        
        //check the page no. must be greater than 0
        if($pageNo < 1){
            $pageNo=1;            
        }
        
        //add the Song table model
        $songInstance = ClassRegistry::init('Song');
        //set the territory value
        $territory = strtolower($territory);
        
        //Common conditions 
        $conditionArray[] = "(Country.DownloadStatus = 1 OR Country.StreamingStatus = 1)";   
        $conditionArray[] = "Country.SalesDate != ''";         
        $conditionArray[] = "Song.ArtistText!=''";
         
        //make condition according to Genre value
        if ($genreValue != 'All') {
            $synonym_list   =   $this->getGenreSynonyms($genreValue);
            $conditionOR = '';
            foreach($synonym_list as $single_synGenre){
                $conditionOR = empty($conditionOR)? "(Genres.Genre = '".mysql_escape_string($single_synGenre)."'" : $conditionOR." OR Genres.Genre = '".mysql_escape_string($single_synGenre)."'";            
            }            
            if(!empty($conditionOR))
            {
                $conditionArray[] = $conditionOR.")";
            }
        }       
        
        //make condition according to Genre value
        if ($artistFilter == 'spl'){
            $conditionArray[] = "Song.ArtistText REGEXP '^[^A-Za-z]'";
        }
        elseif ($artistFilter != '' && $artistFilter != 'All') {
            $conditionArray[] = " Song.ArtistText LIKE '".$artistFilter."%'";
        }
        
        $songInstance->unbindModel(array('hasOne' => array('Participant')));
        $songInstance->unbindModel(array('hasOne' => array('Country')));
        $songInstance->unbindModel(array('hasOne' => array('Genre')));
        $songInstance->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
        $songInstance->recursive = 0;
        //create query that fetch all artist according to selected Genre
        
        if ($genreValue != 'All') {
            
            $endLimit =  120;
            $startLimit = ($pageNo * 120) - 120;
            
            $artistListResults = $songInstance->find('all', array(
                'conditions' => $conditionArray,
                'fields' => array('DISTINCT Song.ArtistText'),
                'limit'=> $endLimit, 'offset'=> $startLimit,
                'order' => array('Song.ArtistText ASC'),
                'joins' => array(
                    array(
                        'table' => $territory.'_countries',
                        'alias' => 'Country',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions'=> array('Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type')
                    ),
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genres',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions'=> array('Genres.ProdID = Song.ProdID', 'Genres.provider_type = Song.provider_type')
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions'=> array('Song.ReferenceID = Albums.ProdID', )
                    )
                )
             ));        
        
        }
        else
        {
            $endLimitG =  12000;
            $startLimitG = ($pageNo * 12000) - 12000;
            $conditionString = '';
           
             //make condition according to Genre value
            if ($artistFilter == 'spl'){
                $conditionString = " And Songs.ArtistText REGEXP '^[^A-Za-z]'";
            }
            elseif ($artistFilter != '' && $artistFilter != 'All') {
                $conditionString = "And Songs.ArtistText LIKE '".$artistFilter."%'";
            }
            
            /* Query written in below format as it is not possible to write in Cakephp Standard.
             * Ref URL: http://stackoverflow.com/questions/8175080/cakephp-select-from-subquery-select-foo-from-select
             * Ref URL: http://stackoverflow.com/questions/3781654/how-to-implement-a-sorting-subquery-in-the-from-section
             */
            
            $artistListResults = $songInstance->query( "SELECT DISTINCT Song.ArtistText 
                                                        FROM 
                                                        (SELECT Songs.ArtistText FROM Songs AS Songs 
                                                        LEFT JOIN ".$territory."_countries  AS Country ON (Country.ProdID = Songs.ProdID and Country.provider_type = Songs.provider_type) 
                                                        LEFT JOIN Albums AS Albums ON (Songs.ReferenceID = Albums.ProdID) 
                                                        WHERE (Country.DownloadStatus = 1 or Country.StreamingStatus =1) 
                                                        AND Songs.ArtistText!='' AND Country.SalesDate != '' ".$conditionString."
                                                        ORDER BY Songs.ArtistText ASC 
                                                        LIMIT ".$startLimitG.", ".$endLimitG.") Song");
            
            array_pop($artistListResults);          
        }
                
         //set artist list in the cache
         if (!empty($artistListResults)) {             
            //create cache variable name
             
            $cacheVariableName = base64_encode($genreValue).$territory.strtolower($artistFilter).$pageNo;              
            Cache::write($cacheVariableName, $artistListResults,'GenreCache');    
            $this->log("cache variable $cacheVariableName  set for ".$genreValue.'_'.$territory.'_'.$artistFilter.'_'.$pageNo, "genreLogs");
         } 
//         elseif($artistFilter == 'All' && empty($artistListResults))
//         {       
//             $territoryUpper    = strtoupper($territory);
//             $genreList = Cache::read("allgenre" . $territoryUpper,'GenreCache');
//             $genreKey  = array_search($genreValue, $genreList);
//             
//             if ($genreKey!=false) {
//                 
//                 unset($genreList[$genreKey]);
//                 Cache::write("allgenre" . $territoryUpper, $genreList,'GenreCache');
//                 $this->log($genreValue." deleted from genre Cache for $territory", "cache");
//                 
//             }
//         }
         
        return $artistListResults;
         
     }
     
     /*
     * Function Name : checkGenrepagesCount
     * Function Description : This get count of particular Genre and pages.
     * this currenty not using anyware 
     
     * @paran $territory varChar 'territory value'
     * @paran $genreEach varChar 'genre value'
     * @paran $artistFilter varChar 'artist filter value'
     * 
     * @return  $totalPages int
     */
     
     function checkGenrepagesCount($territory,$genreEach,$artistFilter){
         
        set_time_limit(0);    
        //add the Song table model
        $songInstance = ClassRegistry::init('Song');
        
        //check if artist filter is All or not
        if($genreEach != 'All')
        {
            //create conditions array
            $conditionArray = array(
                'Country.DownloadStatus' => 1,                    
                'Country.Territory' => strtoupper($territory)                
            );

            //Genre filter
            if ($genreEach != '' && $genreEach != 'All')
            {
                $conditionArray[] = " Song.Genre LIKE '%".mysql_escape_string($genreEach)."%'";
            }

            //Artist filter
            if ($artistFilter == 'spl')
            {                       
                $conditionArray[] = " Song.ArtistText REGEXP '^[^A-Za-z]'";
            }
            elseif ($artistFilter != '' && $artistFilter != 'All')
            {
                $conditionArray[] = " Song.ArtistText LIKE '".$artistFilter."%'";
            }

            $songInstance->unbindModel(array('hasOne' => array('Participant')));
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
            $songInstance->recursive = 0;

            //query that fetch  artist count according to Genre
            $artistCount = $songInstance->find('all', array(
                'conditions' => $conditionArray,
                'fields' => array('count(DISTINCT Song.ArtistText) as total'),

                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions'=> array('Country.ProdID = Song.ProdID')
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions'=> array('Song.ReferenceID = Albums.ProdID')
                    )
                )
             ));
            
            if( isset($artistCount[0][0]['total']) && ($artistCount[0][0]['total'] > 0 ) ){                    
                $totalPages = ceil( $artistCount[0][0]['total'] / 120 );                    
            }else{
                $totalPages =1;
            }
            
            //value less then one then set default 1
            if( $totalPages < 1){
                $totalPages =1;
            }

        }else{
            $totalPages =5;
        }
        return $totalPages;         
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
                LIMIT 100 

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
                //update the mem datas table
                $MemDatas = ClassRegistry::init('MemDatas');
                $nationalTopDownloadSer = base64_encode(serialize($data));
                $memQuery = "update mem_datas set vari_info='".$nationalTopDownloadSer."'  where territory='".$territory."'";
                $MemDatas->setDataSource('master');
                $MemDatas->query($memQuery);
                $MemDatas->setDataSource('default');
                
                Cache::write("national" . $country, $data);
                $this->log("cache written for national top 100 songs for $territory", "cache");
            }
            else
            {
                $data = Cache::read("national" . $country);
                Cache::write("national" . $country, Cache::read("national" . $country));
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
     * Function Name: featuredVideos
     * Desc: cache read & write featured videos for index action
     *
     * @param: $prefix string
     * @param: $territory string
     * @param: $explicitContent string
     * @param: $cacheVariableSuffix string
     * 
     * @return: array
     */

    public function featuredVideos( $prefix, $territory,$explicitContent,$cacheVariableSuffix ) {
        
         set_time_limit(0);
          global $brokenImages;
         $tokeninstance = ClassRegistry::init('Token');
         $FeaturedVideo = ClassRegistry::init('FeaturedVideo');
    	
         $featuredVideos = $FeaturedVideo->fetchFeaturedVideo( $prefix, $territory, $explicitContent );

         if ( !empty( $featuredVideos ) ) {

                foreach ( $featuredVideos as $key => $featureVideo ) {

                        $videoArtwork = $tokeninstance->artworkToken( $featureVideo['File']['CdnPath'] . '/' . $featureVideo['File']['SourceURL'] );
                        $videoImage   = Configure::read( 'App.Music_Path' ) . $videoArtwork;

                        $featuredVideos[$key]['videoImage'] = $videoImage;

                        //check image file exist or not for each entry
                        if(!$this->checkImageFileExist($featuredVideos[$key]['videoImage'])){              
                            //write broken image entry in the log files
                            $this->log($territory.' : ' .' Featured Videos : '. $featuredVideos[$key]['videoImage'], 'check_images');
                            $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .' Featured Videos : '. $featuredVideos[$key]['videoImage'];
                            //unset the broken images variable in the array
                            unset($featuredVideos[$key]);             
                        }                            
                }
                
                Cache::write( 'featured_videos' . $cacheVariableSuffix . $territory, $featuredVideos );
                $this->log("Updated featured videos $cacheVariableSuffix cache for " . $territory, "cache");
         }
           
    	return $featuredVideos;
    }
    
    
      /**
     * Function Name: topDownloadVideos
     * Desc: cache read & write top download videos for index action
     *
     * @param: Two and type String
     * @return: array
     */

    public function topDownloadVideos( $prefix, $territory ) {    	
    	
        set_time_limit(0);
        global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        $Videodownload = ClassRegistry::init('Videodownload');        
        $topDownloads = $Videodownload->fetchVideodownloadTopDownloadedVideos( $prefix );

        if ( !empty( $topDownloads ) ) {

                foreach ( $topDownloads as $key => $topDownload ) {

                        $videoArtwork = $tokeninstance->artworkToken( $topDownload['File']['CdnPath'] . '/' . $topDownload['File']['SourceURL'] );
                        $videoImage   = Configure::read( 'App.Music_Path' ) . $videoArtwork;

                        $topDownloads[$key]['videoImage'] = $videoImage;
                        
                        //check image file exist or not for each entry
                        if(!$this->checkImageFileExist($topDownloads[$key]['videoImage'])){              
                            //write broken image entry in the log files
                            $this->log($territory.' : ' .' Top Download Videos : '. $topDownloads[$key]['videoImage'], 'check_images');
                            $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .' Top Download Videos : '. $topDownloads[$key]['videoImage'];
                            //unset the broken images variable in the array
                            unset($topDownloads[$key]);             
                        }                        
                }
                
                Cache::write( 'top_download_videos' . $territory, $topDownloads );
                $this->log("Updated top download videos cache for " . $territory, "cache");    
        }
       
        return $topDownloads;
        
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
     * Function Name : getUsTop10Songs
     * Function Description : This function is used to get Us Top 10 Albums.
     */

    function getUsTop10Songs($territory)
    {
        set_time_limit(0);
         global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Songs           
        $country = $territory;
        if (!empty($country))
        {
            $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
            FROM `latest_downloads` AS `Download` 
            LEFT JOIN libraries ON libraries.id=Download.library_id
            WHERE libraries.library_territory = '" . $country . "' 
            AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
            GROUP BY Download.ProdID 
            ORDER BY `countProduct` DESC 
            LIMIT 110";
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
                    
                    //check image file exist or not for each entry
                    if(!$this->checkImageFileExist($data[$key]['songs_img'])){              
                       //write broken image entry in the log files
                       $this->log($country.' :  Top10 Songs : '. $data[$key]['songs_img'], 'check_images');
                       $brokenImages[] = date('Y-m-d H:i:s').' : ' .$country.' : ' .' Top10 Songs : '. $data[$key]['songs_img'];
                       //unset the broken images variable in the array
                       unset($data[$key]);             
                    }                  
                    
                }
                
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
         global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Album            
        $country = $territory;
        if (!empty($country))
        {
            $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
               FROM `latest_downloads` AS `Download` 
               LEFT JOIN libraries ON libraries.id=Download.library_id
               WHERE libraries.library_territory = '" . $country . "' 
               AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
               GROUP BY Download.ProdID 
               ORDER BY `countProduct` DESC 
               LIMIT 110";
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
                    
                    //check image file exist or not for each entry
                    if(!$this->checkImageFileExist($data[$key]['album_img'])){              
                       //write broken image entry in the log files
                       $this->log($country.' :  Top10 Albums : '. $data[$key]['album_img'], 'check_images');
                       $brokenImages[] = date('Y-m-d H:i:s').' : ' .$country.' : ' .' Top10 Albums : '. $data[$key]['album_img'];
                       //unset the broken images variable in the array
                       unset($data[$key]);             
                    }
                }
                
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
         global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');
        //Added caching functionality for us top 10 Video            
        $country = $territory;
        if (!empty($country))
        {
            $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type 
                 FROM `latest_videodownloads` AS `Download` 
                 LEFT JOIN libraries ON libraries.id=Download.library_id
                 WHERE libraries.library_territory = '" . $country . "' 
                 AND `Download`.`created` BETWEEN '" . Configure::read('App.lastWeekStartDate') . "' AND '" . Configure::read('App.lastWeekEndDate') . "' 
                 GROUP BY Download.ProdID 
                 ORDER BY `countProduct` DESC 
                 LIMIT 110";
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
                    
                    
                     //check image file exist or not for each entry
                    if(!$this->checkImageFileExist($data[$key]['videoAlbumImage'])){              
                       //write broken image entry in the log files
                       $this->log($country.' :  Top10 Videos : '. $data[$key]['videoAlbumImage'], 'check_images');
                       $brokenImages[] = date('Y-m-d H:i:s').' : ' .$country.' : ' .' Top10 Videos : '. $data[$key]['videoAlbumImage'];
                       //unset the broken images variable in the array
                       unset($data[$key]);             
                    }
                }
                
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

    function getNewReleaseAlbums($territory, $explicitContent = false)
    {
       
        set_time_limit(0);
        global $brokenImages;
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

            $albumAdvisory 	   = '';
            $cacheVariableName = 'new_releases_albums';
            
            if(true === $explicitContent) {
            	$albumAdvisory 	   = " AND Albums.Advisory != 'T'";
            	$cacheVariableName = 'new_releases_albums_none_explicit';
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
                            Albums.provider_type,
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
                        AND (Country.Territory = '$territory') AND (Country.SalesDate != '') AND (Country.SalesDate <= NOW()) $albumAdvisory                
                    group by Albums.AlbumTitle
                    ORDER BY Country.SalesDate DESC
                    LIMIT 150
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
                    
                    //check image file exist or not for each entry
                    if(!$this->checkImageFileExist($data[$key]['albumImage'])){              
                       //write broken image entry in the log files
                       $this->log($territory.' : ' .' New Release Albums '. $data[$key]['albumImage'], 'check_images');
                       $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .' New Release Albums '. $data[$key]['albumImage'];
                       //unset the broken images variable in the array
                       unset($data[$key]);             
                    }  

                }
                
                Cache::write($cacheVariableName . $country, $data);
                $this->log("cache written for new releases albums for $territory", "cache");
            }
            else
            {
                $data = Cache::read($cacheVariableName . $country);
                Cache::write($cacheVariableName . $country, $data);
                $this->log("Unable to update new releases albums for " . $territory, "cache");
            }
            
            
             //update the mem datas table
            $MemDatas = ClassRegistry::init('MemDatas');
            $MemDatas->setDataSource('master');
            $this->CacheHandler->setMemData($cacheVariableName . $country,$data);
            $MemDatas->setDataSource('default');
            
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
        
        global $brokenImages;
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

            if (!empty($data)) {
                
                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                    
                    
                    //check image file exist or not for each entry
                    if(!$this->checkImageFileExist($data[$key]['videoAlbumImage'])){              
                       //write broken image entry in the log files
                       $this->log($territory.' : ' .' New Release Videos '. $data[$key]['videoAlbumImage'], 'check_images');
                       $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .' New Release Videos '. $data[$key]['videoAlbumImage'];
                       //unset the broken images variable in the array
                       unset($data[$key]);             
                    }  
                }
                
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
         global $brokenImages;
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
            
            foreach ($featured as $k => $v) {         
                
            	$albumids = explode(',',$v['Featuredartist']['album']);

               	if($v['Featuredartist']['album']!=0){
			$streamsongs = array();
                 	for ($i=0; $i<count($albumids); $i++){
        				$streamsongs[$i] =  $this->requestAction(
                         	array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($v['Featuredartist']['artist_name']), $albumids[$i], base64_encode($v['Featuredartist']['provider_type']),0,$territory))
                    	);
					}
        			$albumsongs = array();
    				for($a =0; $a<count($streamsongs);$a++){
        				$playlist = reset($streamsongs[$a]);
					$albumsongs =  array_merge($albumsongs,$playlist);
				}
					if(empty($albumsongs)) {
				 		$albumsongs = array();
						$artistAlbums = $this->getStreamArtist($v['Featuredartist']['artist_name']);
					$i = 0;
					foreach ($artistAlbums as $index => $value) {
						if($i == 4) break;
						if(!empty($value['albumSongs'][$value['Album']['ProdID']])) {
						$albumsongs = array_merge($albumsongs,$value['albumSongs'][$value['Album']['ProdID']]);
						$i++;
					}
					}	
					}
					
        			$featured[$k]['albumSongs'] = $albumsongs;
        

                
                    $featureImageURL =  Configure::read('App.CDN') . 'featuredimg/' . $featured[$k]['Featuredartist']['artist_image'];

                     //check image file exist or not for each Artist
                    if(!$this->checkImageFileExist($featureImageURL)) {              
                        //write broken image entry in the log files
                        $this->log($territory.' : ' .' Feature Artist and Composer : '. $featured[$k]['Featuredartist']['artist_name'], 'check_images');
                        $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .'Feature Artist and Composer : '. $featured[$k]['Featuredartist']['artist_name'];
                        //unset the broken images variable in the array
                        unset($featured[$k]);
                    }
                }                
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
    
    function getRandomSongs($artistComposer , $provider,  $flag = 0, $ajax = 0, $territory = null) {

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

  function getTopAlbums($territory) {
     
      global $brokenImages;
    // Gets the list of the top albums that are manually set
    $TopAlbum = ClassRegistry::init('TopAlbum');
    $topAlbumsList = $TopAlbum->getTopAlbumsList($territory);

    if ((count($topAlbumsList) < 1) || ($topAlbumsList === false)) {

      $this->log('a list of top albums was not available for ' . $territory, "cache");

    } else {

      // creating a list of the album ids and provider types.
      $ids_provider_type = '';
      foreach ($topAlbumsList as $topAlbum) {
        if ($topAlbum['TopAlbum']['album'] != 0) {
          if (empty($ids_provider_type)) {
            $ids_provider_type .= "(" . $topAlbum['TopAlbum']['album'] . ",'" . $topAlbum['TopAlbum']['provider_type'] . "')";
          } else {
            $ids_provider_type .= ',(' . $topAlbum['TopAlbum']['album'] . ",'" . $topAlbum['TopAlbum']['provider_type'] . "')";
          }
        }
      }

      // Gets the album info for each album on the list
      if ($ids_provider_type != '') {
            $Album = ClassRegistry::init('Album');
            $topAlbumData = $Album->getTopAlbumData($territory, $ids_provider_type);
      } else {
            $topAlbumData = array();
      }

      if (!empty($topAlbumData)) {
            $Token = ClassRegistry::init('Token');
            $musicPath = Configure::read('App.Music_Path');  
        
            foreach ($topAlbumData as $key => $data) { 

                $topAlbumData[$key]['topAlbumImage'] = $musicPath . $Token->artworkToken($data['Files']['CdnPath'] . '/' . $data['Files']['SourceURL']);;
                $topAlbumData[$key]['albumSongs'] = $this->getAlbumSongsNew($data['Album']['ProdID'], $data['Album']['provider_type'], $territory);          

                //check image file exist or not for each entry
                if(!$this->checkImageFileExist($topAlbumData[$key]['topAlbumImage'])){              
                    //write broken image entry in the log files
                    $this->log($territory.' : ' .' Top Albums : '. $topAlbumData[$key]['topAlbumImage'], 'check_images');
                     $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .'Top Albums : '. $topAlbumData[$key]['topAlbumImage'];
                    //unset the broken images variable in the array
                    unset($topAlbumData[$key]);             
                }          
            } 
            
            Cache::write('top_albums' . $territory, $topAlbumData);
            $this->log('cache written for Top Albums for: ' . $territory, 'debug');
            $this->log('cache written for Top Albums for: ' . $territory, 'cache');
        
      }
      
    }
    return $topAlbumData;
  }
  
  /* @FunctionName : checkImageFileExist
   * @Desc         : check the images exist or not using CURL
   * 
   * @param ImageURL String 
   * @return Boolean
   */
  function checkImageFileExist($imageURL) {      
     
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$imageURL);
      // don't download content
      curl_setopt($ch, CURLOPT_NOBODY, 1);
      curl_setopt($ch, CURLOPT_FAILONERROR, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      if(curl_exec($ch)!==FALSE) {
          return true;
      }else{
          return false;
      }      
  }

  function getAlbumSongsNew($prodId, $provider, $territory) {

    $countryPrefix = $this->getCountryPrefix($territory);
    $Album = ClassRegistry::init('Album');
    $albumData = $Album->findSongsForAlbum($prodId, $provider);

    $albumSongs = array();
    if (!empty($albumData)) {
      $Song = ClassRegistry::init('Song');
      foreach ($albumData as $album) {
        $albumSongs[$album['Album']['ProdID']] = $Song->getSongDetails($album['Album']['ProdID'], $provider, $territory);  
      }
    }
    foreach ($albumSongs as $k => $albumSong) {
      foreach ($albumSong as $key => $value) {
        $albumSongs[$k][$key]['CdnPath'] = $value['Full_Files']['CdnPath'];
        $albumSongs[$k][$key]['SaveAsName'] = $value['Full_Files']['SaveAsName'];
        $albumSongs[$k][$key]['FullLength_Duration'] = $value['Song']['FullLength_Duration'];
        unset($albumSongs[$k][$key]['Song']['DownloadStatus']);
        unset($albumSongs[$k][$key]['Song']['Sample_Duration']);
        unset($albumSongs[$k][$key]['Song']['FullLength_Duration']);
        unset($albumSongs[$k][$key]['Song']['Sample_FileID']);
        unset($albumSongs[$k][$key]['Song']['FullLength_FIleID']);
        unset($albumSongs[$k][$key]['Song']['sequence_number']);
        unset($albumSongs[$k][$key]['Song']['Title']);
        unset($albumSongs[$k][$key]['Song']['Artist']);
        unset($albumSongs[$k][$key]['Genre']);
        unset($albumSongs[$k][$key]['Country']);
        unset($albumSongs[$k][$key]['Sample_Files']);
        unset($albumSongs[$k][$key]['Full_Files']);
      }
    }
    return $albumSongs;
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
                        Albums.provider_type,
                        TopSingles.SortId
                FROM
                        Songs AS Song
                                INNER JOIN
                        top_singles as TopSingles ON (TopSingles.prod_id = Song.ProdID AND TopSingles.Territory = '$territory')
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
                ORDER BY SortId ASC
                LIMIT 50 

STR;
            $topSingleData = $albumInstance->query($sql_top_singles);

            //update the mem datas table
            $MemDatas = ClassRegistry::init('MemDatas');
            $MemDatas->setDataSource('master');
            $this->CacheHandler->setMemData("top_singles" . $territory,$topSingleData);
            $MemDatas->setDataSource('default');
            
            

            
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
        LIMIT 25
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
      LIMIT 25
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
        global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        //--------------------------------Library Top Ten Start--------------------------------------------------------------------
        $latestDownloadInstance = ClassRegistry::init('LatestDownload');
        $songInstance = ClassRegistry::init('Song');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);
        //this is for my library songs start

        $download_src = 'LatestDownload';
        $topDownloaded = $latestDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
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
                                            INNER JOIN
                                    File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                                            INNER JOIN
                                    File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
                                            LEFT JOIN
                                    Genre AS Genre ON (Genre.ProdID = Song.ProdID) AND (Song.provider_type = Genre.provider_type) 
                                            INNER JOIN
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
                    
                   //check image file exist or not for each entry
                if(!$this->checkImageFileExist($topDownload[$key]['songs_img'])){              
                    //write broken image entry in the log files
                    $this->log($territory.' : ' .' LibraryID '. $libId .' Top10 Songs : '. $topDownload[$key]['songs_img'], 'check_images');
                    $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .'LibraryID '. $libId .' Top10 Songs : '. $topDownload[$key]['songs_img'];
                    //unset the broken images variable in the array
                    unset($topDownload[$key]);             
                }       
                    
            }
           
            //update the mem datas table
            $MemDatas = ClassRegistry::init('MemDatas');
            $MemDatas->setDataSource('master');
            $this->CacheHandler->setMemData("lib" . $libId,$topDownload);
            $MemDatas->setDataSource('default');
           
            
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
         global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');         
        $albumInstance = ClassRegistry::init('Album');
        $latestDownloadInstance = ClassRegistry::init('LatestDownload');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);
        //library top 10 cache set for albums start            
        $download_src = 'LatestDownload';
        $topDownloaded_albums = $latestDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        $this->log("$download_src - $libId - $country", "cache");

        $ids = '';
        $ioda_ids = array();
        $sony_ids = array();
        $sony_ids_str = '';
        $ioda_ids_str = '';
        $ids_provider_type = '';
        $ids_provider_type_album ='';
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
                    
            
               //check image file exist or not for each entry
               if(!$this->checkImageFileExist($topDownload[$key]['album_img'])){              
                   //write broken image entry in the log files
                   $this->log($territory.' : ' .' LibraryID '. $libId .' Top10 Albums : '. $topDownload[$key]['album_img'], 'check_images');
                   $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .'LibraryID '. $libId .' Top10 Albums : '. $topDownload[$key]['album_img'];
                   //unset the broken images variable in the array
                   unset($topDownload[$key]);             
               }                     
            }
            
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
        global $brokenImages;
        $tokeninstance = ClassRegistry::init('Token');
        $latestVideoDownloadInstance = ClassRegistry::init('LatestVideodownload');
        $videoInstance = ClassRegistry::init('Video');
        $country = $territory;
        $countryPrefix = $this->getCountryPrefix($territory);

        //library top 10 cache set for videos start 
        $download_src = 'LatestVideodownload';
        $topDownloaded_videos = $latestVideoDownloadInstance->find('all', array('conditions' => array('library_id' => $libId, 'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit' => '15'));
        $this->log("$download_src - $libId - $country", "cache");

        $ids = '';
        $ioda_ids = array();
        $sony_ids = array();
        $sony_ids_str = '';
        $ioda_ids_str = '';
        $ids_provider_type = '';
        $ids_provider_type_video = '';
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
                
                
                //check image file exist or not for each entry
               if(!$this->checkImageFileExist($topDownload[$key]['videoAlbumImage'])){              
                   //write broken image entry in the log files
                   $this->log($territory.' : ' .' LibraryID '. $libId .' Top10 Video : '. $topDownload[$key]['videoAlbumImage'], 'check_images');
                   $brokenImages[] = date('Y-m-d H:i:s').' : ' .$territory.' : ' .' LibraryID '. $libId .' Top10 Video : '. $topDownload[$key]['videoAlbumImage'];
                   //unset the broken images variable in the array
                   unset($topDownload[$key]);             
               }
            }
            
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
    
    
    /* Function : sendBrokenImageAlert
     * Desc: reponsible to send broken image alert
     * 
     * @param $content string    
     * 
     */
     function sendBrokenImageAlert($content) {

     	$to = "tech@libraryideas.com";
     	$subject = "FreegalMusic - Broken Images information";

     	$message = $content;

     	// Always set content-type when sending HTML email
     	$headers = "MIME-Version: 1.0" . "\r\n";
     	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

     	// More headers
     	$headers .= 'From: <no-reply@freegalmusic.com>' . "\r\n";
     	$headers .= 'Cc: libraryideas@infobeans.com' . "\r\n";
        
        //$this->sendNormalEmails($to,$subject,$message,$headers);
        
     	// mail($to,$subject,$message,$headers);     
    }
    
    /* Function : sendNormalEmails
     * Desc: common function is used to send email without SMTP
     * 
     * @param $content string    
     * 
     */
     function sendNormalEmails($to,$subject,$message,$headers) {
         mail($to,$subject,$message,$headers); 
         
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
    function getAllVideoByArtist( $country, $decodedId, $explicitContent = true )
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
    
    
    function setAdminDefaultQueuesCache() {
        Configure::write('Cache.disable', false);
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
    }
    
    function refreshQueueSongs($defaultQueueId){
        Configure::write('Cache.disable', false);
        $territoryInstance = ClassRegistry::init('Territory');
        $territories = $territoryInstance->find("all");
        for ($m = 0; $m < count($territories); $m++) {
            $eachQueueDetails = $this->Queue->getQueueDetails($defaultQueueId, $territories[$m]['Territory']['Territory']);
            if ((count($eachQueueDetails) < 1) || ($eachQueueDetails === false))
            {
                $this->log("Freegal Defaut Queues " . $defaultQueueName . "( " . $defaultQueueId . " )" . " returns null ", "cache");
            }
            else
            {
                Cache::write("defaultqueuelistdetails".$territories[$m]['Territory']['Territory'].$defaultQueueId, $eachQueueDetails);
                $this->log("Freegal Defaut Queues " . $defaultQueueName . "( " . $defaultQueueId . " )" . " cache set", "cache");
            }
        }
        
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
        $this->log("Cache for library top 10 starts here for date".date("Y-m-d"), "cache");
        foreach ($libraryDetails AS $key => $val)
        {
            $libId = $val['Library']['id'];
            $country = $val['Library']['library_territory'];
            $this->getLibraryTop10Albums($country, $libId);
            $this->getLibraryTop10Videos($country, $libId);
        }
        $this->log("Cache for library top 10 ends here for date".date("Y-m-d"), "cache");
    }
    
    
    /**
     * @function setLibraryTopTenSongsCache
     * @desc sets Cache for LibraryTopTensongs
     */    
    
    function setLibraryTopTenSongsCache()
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
        $this->log("Cache for library top 10 songs starts here for date".date("Y-m-d"), "cache");
        foreach ($libraryDetails AS $key => $val)
        {
            $libId = $val['Library']['id'];
            $country = $val['Library']['library_territory'];
            $this->getLibraryTopTenSongs($country, $libId);
        }
        $this->log("Cache for library top 10 songs ends here for date".date("Y-m-d"), "cache");
    }    

    /**
     * @function setVideoCacheVar
     * @desc sets video cache Variable
     */
    function setVideoCacheVar()
    {
        //--------------------------------set each music video in the cache start-------------------------------------------------        
        $videoInstance = ClassRegistry::init('Video');
        $musicVideoRecs = $videoInstance->find('all', array('conditions' => array('Video.DownloadStatus' => 1), 'fields' => 'Video.ProdID'));
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
        }
    }
    
    
    /*
     * @func getGenreForSelection
     * @desc This is used to get the 
     */
    function getGenreForSelection($genre_name)
    {
        $combineGenreData = Cache::read("combine_genre");
        
        if ($combineGenreData === false)
        {
            $combineGenreInstance = ClassRegistry::init('CombineGenre');
            $combineGenreData     = $combineGenreInstance->find("all");                        
            Cache::write("combine_genre", $combineGenreData);
        }
       
       if($genre_name!='')
        {            
            for($cnt=0; $cnt<count($combineGenreData); $cnt++)
            {
                if($combineGenreData[$cnt]['CombineGenre']['genre']==str_replace("\\", "", $genre_name))       // if $genre_name (expected_genre from Genre table) matches  $combineGenreData[$cnt]['CombineGenre']['expected_genre'] (expected_genre from combine_genres table), then copy genre value from combine_genre
                {
                    $genre_name = $combineGenreData[$cnt]['CombineGenre']['expected_genre'];
                }
            }
        }  
        
        return $genre_name;        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    /*
     * @func getGenreSynonyms
     * @desc This is used to get synonyms list
     */

    function getGenreSynonyms($genre_name)
    {
        $combineGenreData = Cache::read("combine_genre");
        
        if ($combineGenreData === false)
        {
            $combineGenreInstance = ClassRegistry::init('CombineGenre');
            $combineGenreData     = $combineGenreInstance->find("all");
                        
            Cache::write("combine_genre", $combineGenreData);
        }
        
        $synGenres = array();
        
        if($genre_name!='')
        {            
            for($cnt=0; $cnt<count($combineGenreData); $cnt++)
            {
                if($combineGenreData[$cnt]['CombineGenre']['expected_genre']==str_replace("\\", "", $genre_name))       // if $genre_name (expected_genre from Genre table) matches  $combineGenreData[$cnt]['CombineGenre']['expected_genre'] (expected_genre from combine_genres table), then copy genre value from combine_genre
                {
                    //$synGenres  .=    empty($synGenres)?$combineGenreData[$cnt]['CombineGenre']['genre']:'|'.$combineGenreData[$cnt]['CombineGenre']['genre'];
                    array_push($synGenres, $combineGenreData[$cnt]['CombineGenre']['genre']);
                }
            }
        }
        
        if(count($synGenres)==0)
        {
            array_push($synGenres, $genre_name);
        }
        
        
        return $synGenres;
    }

    /*
     * @func freegalEncode
     * @desc This is used to encrypt a value using the key
     */

	function freegalEncode($string, $key) {
  		$result = '';
  		for($i=0; $i<strlen($string); $i++) {
    	$char = substr($string, $i, 1);
    	$keychar = substr($key, ($i % strlen($key))-1, 1);
    	$char = chr(ord($char)+ord($keychar));
    	$result.=$char;
  		}
 
  		return base64_encode($result);
	}
 
    /*
     * @func freegalDecode
     * @desc This is used to decrypt an encrypted value using the key
     */

	function freegalDecode($string, $key) {
  		$result = '';
  		$string = base64_decode($string);
 
  		for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)-ord($keychar));
    		$result.=$char;
  		}
 
  		return $result;
	}
        
    /*
     * @func file_exists_remote
     * @desc Check if file exists on Remote server
     */        
        
        function file_exists_remote($url) {
            
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            //Check connection only
            $result = curl_exec($curl);
            //Actual request
            $ret = false;
            if ($result !== false) {
             $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
             //Check HTTP status code
             if ($statusCode == 200) {
              $ret = true;  
             }
            }
            curl_close($curl);
            return $ret;
            
       }

	/*
     * @func getStreamArtist
     * @desc get streaming songs from artist
     */        
        
        function getStreamArtist($artist) {
            
            $country = $this->Session->read('territory');
        	$patId = $this->Session->read('patron');
        	$libId = $this->Session->read('library');
        	$libType = $this->Session->read('library_type');

        	if ($this->Session->read('block') == 'yes') {
            	$cond = array('Song.Advisory' => 'F');
        	} else {
            	$cond = "";
        	}

        	
            $id = $artist;
			$Song = ClassRegistry::init('Song');
        	$Song->Behaviors->attach('Containable');
        	$songs = $Song->find('all', array(
            'fields' => array(
                'DISTINCT Song.ReferenceID',
                'Song.provider_type',
                'Country.SalesDate'),
            'conditions' => array('Song.ArtistText' => $id,
                'Country.DownloadStatus' => 1, /* Changed on 16/01/2014 from Song.DownloadStatus to Country.DownloadStatus */
				'Country.StreamingStatus' => 1,
                "Song.Sample_FileID != ''",
                "Song.FullLength_FIleID != ''",
                'Country.Territory' => $country, $cond,
                'Song.provider_type = Country.provider_type'),
            'contain' => array(
                'Country' => array(
                    'fields' => array('Country.Territory')
                )),
            'recursive' => 0,
            'order' => array(
                'Country.SalesDate DESC'
            ))
        );

        $val = '';
        $val_provider_type = '';

        if (!empty($songs)) {
            foreach ($songs as $k => $v) {
                if (empty($val)) {
                    $val .= $v['Song']['ReferenceID'];
                    $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                } else {
                    $val .= ',' . $v['Song']['ReferenceID'];
                    $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                }
            }

            $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ")");
			$Album = ClassRegistry::init('Album');
	
            $albumData = $Album->find('all',
                    array(
                        'conditions' =>
                        array(
                            'and' =>
                            array(
                                $condition
                            ),
                            "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
                        ),
                        'fields' => array(
                            'Album.ProdID',
                            'Album.Title',
                            'Album.ArtistText',
                            'Album.AlbumTitle',
                            'Album.Advisory',
                            'Album.Artist',
                            'Album.ArtistURL',
                            'Album.Label',
                            'Album.Copyright',
                            'Album.provider_type',
                            'Files.CdnPath',
                            'Files.SaveAsName',
                            'Files.SourceURL',
                            'Genre.Genre'
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
                        'order' => array('FIELD(Album.ProdID, ' . $val . ') ASC'),
						'limit' => '15',
                        'cache' => 'yes',
                        'chk' => 2
            ));
            
            if ($libType == 2) {
                foreach ($albumData as $key => $value) {
                   
				$albumData[$key]['albumSongs'] =  $this->requestAction(
                         	array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($albumData[$key]['Album']['ArtistText']), $albumData[$key]['Album']['ProdID'], base64_encode($albumData[$key]['Album']['provider_type']),0,$country))
                    	);
	
                }
            }

            foreach ($albumData as $key => $value) {
                    $albumData[$key]['combineGenre'] = $this->getGenreForSelection($albumData[$key]['Genre']['Genre']);
                }
        }
		return $albumData;            
       }  

}
