<?php

/*
  File Name : common.php
  File Description : Component page for all functionalities.
  Author : m68interactive
 */

Class CommonComponent extends Object
{

    var $components = array('Session');
    var $maintainLatestDownload=true;

    
    /*
     * @func runGlobalCacheFromShell
     * @desc This function is used to call all functions for all cache variables and run from shell
     */

    function runGlobalCacheFromShell() {
        set_time_limit(0);
        $this->log("Cache cron Starts", "cache");
        $this->runCache();
        $this->log("Cache cron Ends", "cache");
    }
    
    /*
     * @func runCache
     * @desc This function is used to call all functions for setting cache variables
     */

    function runCache() {
        set_time_limit(0);
        $this->setLibraryTopTenCache();
        $territoriesList = $this->getTerritories();
        foreach ($territoriesList as $territory) {
            $this->getTopSingles($territory);
            $this->getTopAlbums($territory);
            $this->getFeaturedVideos($territory);
            $this->getTopVideoDownloads($territory);            
            $this->getUsTop10Songs($territory);
            $this->getUsTop10Albums($territory);
            $this->getUsTop10Videos($territory);
            $this->getNewReleaseAlbums($territory);
            $this->getNewReleaseVideos($territory);
            $this->setFeaturedArtists($territory);
            $this->writeFeaturedSongsInCache($territory);
            $this->getDefaultQueues($territory);
            $this->setArtistText($territory);
        }
        // I guess this is not required to set every day in the cache. When user will hit the page it will automatically get set.
        //$this->setVideoCacheVar();

        $this->setAppMyMusicVideoList();
        $this->setAnnouncementCache();
        $this->setTopArtist();
    }
    
    /**
     * @function setAppMyMusicVideoList
     * this function sets music videos list in cache for each territory for App
     * @param nil
     */
    function setAppMyMusicVideoList() {
        set_time_limit(0);

        $countryInstance = ClassRegistry::init('Country');
        $territoryInstance = ClassRegistry::init('Territory');
        $albumInstance = ClassRegistry::init('Album');
        $videoInstance = ClassRegistry::init( array( 'class'=>'Video','alias'=>'v' ) );
        $siteconfig = ClassRegistry::init('Siteconfig');
        
        $territories = $territoryInstance->find("all");

        for ($mm = 0; $mm < count($territories); $mm++) {
            $territoryNames[$mm] = $territories[$mm]['Territory']['Territory'];
        }
        
        //get the config value from site config table
        $siteConfigData = $siteconfig->fetchSiteconfigDataBySoption('multiple_countries');
              
        
        $multiple_countries = (($siteConfigData['Siteconfig']['svalue'] == 1) ? true : false);
        for ($i = 0; $i < count($territoryNames); $i++) {
            $territory = $territoryNames[$i];
            if (0 == $multiple_countries) {
                $countryPrefix = '';
                $countryInstance->setTablePrefix('');
            } else {
                $countryPrefix = strtolower($territory) . "_";
                $countryInstance->setTablePrefix($countryPrefix);
            }  
                       
            $videoInstance->unbindModel(array('belongsTo' => array('Sample_Files')));
            $videoInstance->unbindModel(array('belongsTo' => array('Full_Files')));           
            $videoInstance->unbindModel(array('hasOne' => array('Country', 'Genre', 'Participant')));           
            
           
            $arr_video = array();            
            $arr_video = $videoInstance->find('all', array(
                'conditions' => array(
                    'v.DownloadStatus'   => '1',
                    'c.Territory'     => $territory                  
                ),               
                'fields' => array( 'v.ProdID,'
                    . ' v.ReferenceID, v.Title, v.VideoTitle, v.ArtistText,'
                    . ' v.Artist, v.Advisory, v.ISRC, v.Composer, v.FullLength_Duration,'
                    . ' v.DownloadStatus, c.SalesDate, gr.Genre, ff.CdnPath AS VideoCdnPath,'
                    . ' ff.SaveAsName AS VideoSaveAsName, imgf.CdnPath AS ImgCdnPath,'
                    . ' imgf.SourceURL AS ImgSourceURL, prd.pid,'
                    . ' COUNT(vd.id) AS cnt '
                    ),                
                'group' => 'v.ProdID ',
                'order' => array('cnt DESC'),  
                'limit' => 100,
                'joins' => array(
                     array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'c',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array('c.ProdID = v.ProdID', 'c.provider_type = v.provider_type')
                    ),
                    array(
                        'table' => 'Genre',
                        'alias' => 'gr',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array('gr.ProdID = v.ProdID', 'v.provider_type = gr.provider_type' )
                    ),                   
                    array(
                        'table' => 'File',
                        'alias' => 'imgf',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array('v.Image_FileID = imgf.FileID' )
                    ),
                     array(
                        'table' => 'File',
                        'alias' => 'ff',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'v.FullLength_FileID = ff.FileID' )
                    ), 
                    array(
                        'table' => 'PRODUCT',
                        'alias' => 'prd',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'prd.ProdID = v.ProdID','prd.provider_type = v.provider_type' )                    
                    ),                                   
                    array(
                        'table' => 'videodownloads',
                        'alias' => 'vd',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'vd.ProdID = v.ProdID','vd.provider_type = v.provider_type' )                    
                    )
                )
             ));            
           
          
            if (!empty($arr_video)) {
                $status = Cache::write("AppMyMusicVideosList_" . $territory, $arr_video);
                $this->log("cache wrritten for mobile music videos list for territory_" . $territory, "cache");
            }
        }
    }
    
    /*
     * Function Name : setAnnouncementCache
     * Function Description : This function is used to set announcment Cache.
     * all this function query must be same as queries written in app controller for announcement.
     */

    function setAnnouncementCache() {
        
        $pageInstance = ClassRegistry::init('Page');
                     
        $announcmentRs = $pageInstance->find('first',array(
            'condtion' => array( 'announcement' => '1','language' => 'en'),
            'fields' => array('*'),
            'order' => array('modified DESC'),
            'Limit' => 1
        ));  
               
        if (!empty($announcmentRs)) {
            Cache::write("announcementCache", $announcmentRs);
            $this->log("cache wrritten for announcements", "cache");
        }else{
             $this->log("cache not wrritten for announcements", "cache");
        }
        
        return $announcmentRs;
        
    }

    /*
     * Function Name : setFeaturedArtists
     * Function Description : This function is used to set all featured artists in Cache.
     * 
     */

    function setFeaturedArtists($territory) {

        $featuresArtists = $this->getFeaturedArtists($territory, 1);
        if (!empty($featuresArtists)) {
            Cache::write("featured_artists_" . $territory . '_' . '1', $featuresArtists);
            $this->log("cache written for featured artists for: " . $territory . '_' . '1', "cache");
        } else {
            $this->log("unable to write cache for featured artists for: " . $territory . '_' . '1', "cache");
        }

        $page = 2;
        while ($featuresArtists = $this->getFeaturedArtists($territory, $page)) {
            if (!empty($featuresArtists)) {
                Cache::write("featured_artists_" . $territory . '_' . $page, $featuresArtists);
                $this->log("cache written for featured artists for: " . $territory . '_' . $page, "cache");
            } else {
                $this->log("unable to write cache for featured artists for: " . $territory . '_' . $page, "cache");
            }
            $page++;
        }
    }
    
     /*
     * Function Name : setTopArtist
     * Function Description : This function sets top artist albums in cache, used in App only
     */

    function setTopArtist() {

        set_time_limit(0);
        
        $libraryInstance = ClassRegistry::init('Library');
        $songInstance = ClassRegistry::init('Song');
        $albumInstance = ClassRegistry::init('Album');

        //fetch all libraries
        $libraryDetails = $libraryInstance->find('all', array(
            'fields' => array('id', 'library_territory', 'library_block_explicit_content'),
            'conditions' => array('library_status' => 'active'),
            'recursive' => -1
        ));

        //loop for library
        foreach ($libraryDetails AS $key => $libval) {

            $library_territory = $libval['Library']['library_territory'];
            $topSinglesCache = Cache::read("top_singles" . $library_territory);
            if ((($topSinglesCache) !== false) && ($topSinglesCache !== null)) { // checks if nationalTop100 is set
                //fetches top artist from nationTop100----Start
                $arrTmp = $arrData = $arrFinal = $arrArtist = array();
                $arrTmp = $topSinglesCache;

                foreach ($arrTmp AS $key => $val) {
                    $arrData[] = trim($val['Song']['ArtistText']);
                }

                $arrFinal = array_count_values($arrData);
                arsort($arrFinal, SORT_NUMERIC);

                foreach ($arrFinal AS $key => $val) {
                    $arrArtist[] = $key;
                }
                //----------------------------------------End
                //loop for artist
                foreach ($arrArtist AS $key => $artistText) {

                    $this->Session->write('territory', $library_territory);
                    $this->switchCpuntriesTable();

                    if (1 == $libval['Library']['library_block_explicit_content']) {
                        $cond = array('Song.Advisory' => 'F');
                    } else {
                        $cond = "";
                    }

                    //fetches albums ids
                    $songs = array();
                    $songs = $songInstance->find('all', array(
                        'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type', 'Country.SalesDate'),
                        'conditions' => array(
                            'LOWER(Song.ArtistText)' => strtolower($artistText),
                            "Song.Sample_FileID != ''",
                            "Song.FullLength_FIleID != ''",
                            'Country.Territory' => $library_territory,
                            'Country.DownloadStatus' => 1,
                            $cond,
                            'Song.provider_type = Country.provider_type'
                        ),
                        'contain' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.Territory'
                                )
                            )
                        ),
                        'recursive' => 0,
                        'order' => array('Country.SalesDate DESC')
                    ));

                    $val = '';
                    $val_provider_type = '';

                    foreach ($songs as $k => $v) {
                        if (empty($val)) {
                            $val .= $v['Song']['ReferenceID'];
                            $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                        } else {
                            $val .= ',' . $v['Song']['ReferenceID'];
                            $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
                        }
                    }

                    $condition = array();
                    $condition = array("(Album.ProdID, Album.provider_type) IN (" . rtrim($val_provider_type, ",") . ") AND Album.provider_type = Genre.provider_type");


                    //fetch album details
                    $albumData = array();
                    $albumData = $albumInstance->find('all', array('conditions' =>
                        array('and' =>
                            array(
                                $condition
                            ), "1 = 1 GROUP BY Album.ProdID, Album.provider_type"
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
                            'Album.Advisory',
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
                        'order' => array('FIELD(Album.ProdID, ' . $val . ') ASC'),
                        'chk' => 2,
                    ));

                    //sets cache 
                    if (!empty($albumData)) {

                        $artistText = strtolower(str_replace(' ', '_', $artistText));
                        Cache::write("mobile_top_artist_" . $artistText . '_' . $library_territory, $albumData);
                        $this->log("mobile_top_artist_" . $artistText . '_' . $library_territory . 'set successfully', "topartist");
                    } else {

                        $this->log("mobile_top_artist_" . $artistText . '_' . $library_territory . 'failed', "topartist");
                    }
                }
            } else {
                $this->log("national top 100 not set in Cache for territory " . $library_territory, "topartist");
            }
        }
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

        $genreInstance = ClassRegistry::init('Genre');
        
        //set the aritst cache for specific Genre
        $genreAll = $genreInstance->getGenres($territory);
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
                                                        AND Songs.ArtistText!='' AND Country.SalesDate != ''
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
         elseif($artistFilter == 'All')
         {       
             $territoryUpper    = strtoupper($territory);
             $genreList = Cache::read("genre" . $territoryUpper,'GenreCache');
             $genreKey  = array_search($genreValue, $genreList);
             
             if ($genreKey!=false) {
                 
                 unset($genreList[$genreKey]);
                 Cache::write("genre" . $territoryUpper, $genreList,'GenreCache');
                 $this->log($genreValue." deleted from genre Cache for $territory", "cache");
                 
             }
         }
         
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
            
            if ($this->maintainLatestDownload)
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
            
            if ($this->maintainLatestDownload)
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
        $fvInstance = ClassRegistry::init('FeaturedVideo');
        
        // Added caching functionality for featured videos
        $featuredVideos =  $fvInstance->fetchFeaturedVideo(strtolower($territory)."_", $territory);   

        $this->log("featured videos $territory", "cachequery");        
       
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
        $vdInstance = ClassRegistry::init('Videodownload');
        // Added caching functionality for top video downloads
           
        $topDownloads = $vdInstance->fetchVideodownloadTopDownloadedVideos(strtolower($territory)."_");
        
        $this->log("Top video downloads $territory", "cachequery");        
        
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
     * 
     * @param $territory  string
     * 
     * @return $data array()
     */

    function getUsTop10Songs($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $songInstance = ClassRegistry::init('Song'); 
        
        
        $latestdownload = ClassRegistry::init('LatestDownload');
        $download = ClassRegistry::init('Download'); 
        
        //Added caching functionality for us top 10 Songs           
        $country = $territory;       
    
        if (!empty($country))
        {
            
            if ($this->maintainLatestDownload)
            {                
                 $fetchTableUsed = 'LatestDownload';
                 $USTop10Downloaded = $latestdownload->find('all', array(
                    'conditions' => array(
                        'libraries.library_territory = "' . $territory . '"',
                        'LatestDownload.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                    ),               
                    'fields' => array( 'LatestDownload.ProdID,
                                       COUNT(DISTINCT LatestDownload.id) AS countProduct,
                                        LatestDownload.provider_type'
                                    ),                
                    'group' => 'LatestDownload.ProdID ',
                    'order' => array('countProduct DESC'),  
                    'limit' => '110',
                    'joins' => array(
                                    array(
                                        'table' => 'libraries',
                                        'alias' => 'libraries',
                                        'type' => 'Left',
                                        'foreignKey' => false,
                                        'conditions'=> array('libraries.id = LatestDownload.library_id' )
                                    )
                              )
                 ));                
            }
            else
            {               
                 $download->unbindModel(array('belongsTo' => array('Genre')));
                 $fetchTableUsed = 'Download';
                 $USTop10Downloaded = $download->find('all', array(
                    'conditions' => array(
                        'libraries.library_territory = "' . $territory . '"',
                        'Download.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                    ),               
                    'fields' => array( 'Download.ProdID,
                                       COUNT(DISTINCT Download.id) AS countProduct,
                                        Download.provider_type'
                                    ),                
                    'group' => 'Download.ProdID ',
                    'order' => array('countProduct DESC'),  
                    'limit' => 110,
                    'recursive' => 2,
                    'joins' => array(
                                    array(
                                        'table' => 'libraries',
                                        'alias' => 'libraries',
                                        'type' => 'Left',
                                        'foreignKey' => false,
                                        'conditions'=> array('libraries.id = Download.library_id' )
                                    )
                              )
                 ));               
            }     
            $ids = '';
            $ids_provider_type = '';
                                
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
            }

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }
         
            $data = array();            
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));            
            $songInstance->recursive = 2;
            
            $data = $songInstance->find('all', array(
                'conditions' => array(                    
                    '( Country.DownloadStatus = "1" )',
                    '( Country.Territory = "'. $territory. '") AND ( Country.SalesDate != "" ) AND ( Country.SalesDate <= NOW() )',
                    '(( Song.ProdID, Song.provider_type) IN ( '.$ids_provider_type.' ))'                   
                ),               
                'fields' => array(' Song.ProdID,
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
                                    Sample_Files.FileID '
                    ),                
                'group' => 'Song.ProdID',
                'order' => array('FIELD(Song.ProdID,'.$ids .') ASC'),
                'limit' => 10,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type' )
                    ),                  
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genre',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                    ),
                    array(
                        'table' => 'File',
                        'alias' => 'File',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Albums.FileID = File.FileID' )
                    )
             )));           
            
            
            App::import('Component', 'Streaming');
            $StreamingComponent = new StreamingComponent();

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
                        $data[$key]['totalseconds'] = $StreamingComponent->getSeconds($value['Song']['FullLength_Duration']);
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
     * 
     * @param $territory string
     * 
     * @return $data  array()
     */

    function getUsTop10Albums($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $songInstance = ClassRegistry::init('Song');
        
        $latestdownload = ClassRegistry::init('LatestDownload');
        $download = ClassRegistry::init('Download'); 
        
        //Added caching functionality for us top 10 Album            
        $country = $territory;
        if (!empty($country))
        {
            
            if ($this->maintainLatestDownload)
            {                
                 $fetchTableUsed = 'LatestDownload';
                 $USTop10Downloaded = $latestdownload->find('all', array(
                    'conditions' => array(
                        'libraries.library_territory = "' . $territory . '"',
                        'LatestDownload.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                    ),               
                    'fields' => array( 'LatestDownload.ProdID,
                                       COUNT(DISTINCT LatestDownload.id) AS countProduct,
                                        LatestDownload.provider_type'
                                    ),                
                    'group' => 'LatestDownload.ProdID ',
                    'order' => array('countProduct DESC'),  
                    'limit' => '110',
                    'joins' => array(
                                    array(
                                        'table' => 'libraries',
                                        'alias' => 'libraries',
                                        'type' => 'Left',
                                        'foreignKey' => false,
                                        'conditions'=> array('libraries.id = LatestDownload.library_id' )
                                    )
                              )
                 ));                
            }
            else
            {               
                 $download->unbindModel(array('belongsTo' => array('Genre')));
                 $fetchTableUsed = 'Download';
                 $USTop10Downloaded = $download->find('all', array(
                    'conditions' => array(
                        'libraries.library_territory = "' . $territory . '"',
                        'Download.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                    ),               
                    'fields' => array( 'Download.ProdID,
                                       COUNT(DISTINCT Download.id) AS countProduct,
                                        Download.provider_type'
                                    ),                
                    'group' => 'Download.ProdID ',
                    'order' => array('countProduct DESC'),  
                    'limit' => '110',
                    'recursive' => '2',
                    'joins' => array(
                                    array(
                                        'table' => 'libraries',
                                        'alias' => 'libraries',
                                        'type' => 'Left',
                                        'foreignKey' => false,
                                        'conditions'=> array('libraries.id = Download.library_id' )
                                    )
                               )
                 ));               
            }          
            
            $ids = '';
            $ids_provider_type = '';
          
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
            }           
            

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }    
            
            $data = array();               
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));            
            $songInstance->recursive = 2;
               
               
            $data = $songInstance->find('all', array(
                'conditions' => array(                    
                    '( Country.DownloadStatus = "1" )',
                    '( Country.Territory = "'. $territory. '") AND ( Country.SalesDate != "" ) AND ( Country.SalesDate <= NOW() )',
                    '(( Song.ProdID, Song.provider_type) IN ( '.$ids_provider_type.' ))'                   
                ),               
                'fields' => array(' Song.ProdID,
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
                                    Sample_Files.FileID '
                    ),                
                'group' => 'Song.ReferenceID',
                'order' => array('count(Song.ProdID) DESC'),
                'limit' => 10,
                'joins' => array(
                                array(
                                    'table' => strtolower($territory).'_countries',
                                    'alias' => 'Country',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type' )
                                ),                  
                                array(
                                    'table' => 'Genre',
                                    'alias' => 'Genre',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                                ),
                                array(
                                    'table' => 'Albums',
                                    'alias' => 'Albums',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                                ),
                                array(
                                    'table' => 'File',
                                    'alias' => 'File',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Albums.FileID = File.FileID' )
                                )
                          )
                 ));  
            
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
     * 
     * @param $territory string
     * 
     * @return $data array
     */

    function getUsTop10Videos($territory)
    {
        set_time_limit(0);
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $videoInstance = ClassRegistry::init('Video');
        $albumInstance = ClassRegistry::init('Album'); 
        
        $latestVideodownload = ClassRegistry::init('LatestVideodownload');
        $videodownload = ClassRegistry::init('Videodownload'); 
        //Added caching functionality for us top 10 Video            
        $country = $territory;
        if (!empty($country))
        {           
            if ($this->maintainLatestDownload)
            {                
                 $fetchTableUsed = 'LatestVideodownload';
                 $USTop10Downloaded = $latestVideodownload->find('all', array(
                    'conditions' => array(
                        'libraries.library_territory = "' . $territory . '"',
                        'LatestVideodownload.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                    ),               
                    'fields' => array( 'LatestVideodownload.ProdID,
                                       COUNT(DISTINCT LatestVideodownload.id) AS countProduct,
                                        provider_type'
                                    ),                
                    'group' => 'LatestVideodownload.ProdID ',
                    'order' => array('countProduct DESC'),  
                    'limit' => '110',
                    'joins' => array(
                        array(
                            'table' => 'libraries',
                            'alias' => 'libraries',
                            'type' => 'Left',
                            'foreignKey' => false,
                            'conditions'=> array('libraries.id = LatestVideodownload.library_id' )
                        )
                    )
                 ));
                 
        }
        else
        {               
                $videodownload->unbindModel(array('belongsTo' => array('Genre')));
                $fetchTableUsed = 'Videodownload';
                $USTop10Downloaded = $videodownload->find('all', array(
                   'conditions' => array(
                       'libraries.library_territory = "' . $territory . '"',
                       'Videodownload.created BETWEEN "' . Configure::read('App.lastWeekStartDate') . '" AND "' . Configure::read('App.lastWeekEndDate') . '"'                       
                   ),               
                   'fields' => array( 'Videodownload.ProdID,
                                      COUNT(DISTINCT Videodownload.id) AS countProduct,
                                       provider_type'
                                   ),                
                   'group' => 'Videodownload.ProdID ',
                   'order' => array('countProduct DESC'),  
                   'limit' => 110,
                   'joins' => array(
                                   array(
                                       'table' => 'libraries',
                                       'alias' => 'libraries',
                                       'type' => 'Left',
                                       'foreignKey' => false,
                                       'conditions'=> array('libraries.id = Videodownload.library_id' )
                                   )
                             )
                ));                

          }     
    
            
            $ids = '';
            $ids_provider_type = '';           
            foreach ($USTop10Downloaded as $natTopSong)
            {
                if (empty($ids))
                {
                    $ids .= $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
                else
                {
                    $ids .= ',' . $natTopSong[$fetchTableUsed]['ProdID'];
                    $ids_provider_type .= ',' . "(" . $natTopSong[$fetchTableUsed]['ProdID'] . ",'" . $natTopSong[$fetchTableUsed]['provider_type'] . "')";
                }
            }

            if ((count($USTop10Downloaded) < 1) || ($USTop10Downloaded === false))
            {
                $this->log("download data not recevied for " . $territory, "cache");
            }
            
            
            
            $data = array();
            if ($ids_provider_type != "")
            {
                $videoInstance->unbindModel(array('belongsTo' => array('Sample_Files')));
                $videoInstance->unbindModel(array('hasOne' => array('Country', 'Genre', 'Participant')));
               
           
                $data = array();            
                $data = $videoInstance->find('all', array(
                    'conditions' => array(
                        'Video.DownloadStatus'   => '1',
                        'Country.Territory'     => $territory,
                        'Country.SalesDate != ' => '',
                        'Country.SalesDate <=  NOW()',
                        '((Video.ProdID, Video.provider_type) IN ('.$ids_provider_type.'))'
                    ),               
                    'fields' => array( 'Video.ProdID,
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
                                        Image_Files.SourceURL'
                                    ),                
                    'group' => 'Video.ProdID ',
                    'order' => array('FIELD(Video.ProdID, '.$ids.') ASC'),  
                    'limit' => 10,
                    'joins' => array(
                        array(
                            'table' => 'Genre',
                            'alias' => 'Genre',
                            'type' => 'Left',
                            'foreignKey' => false,
                            'conditions'=> array('Genre.ProdID = Video.ProdID', 'Video.provider_type = Genre.provider_type' )
                        ),
                        array(
                            'table' => strtolower($territory).'_countries',
                            'alias' => 'Country',
                            'type' => 'Left',
                            'foreignKey' => false,
                            'conditions'=> array('Country.ProdID = Video.ProdID', 'Country.provider_type = Video.provider_type')
                        ),
                        array(
                            'table' => 'File',
                            'alias' => 'Image_Files',
                            'type' => 'Left',
                            'foreignKey' => false,
                            'conditions'=> array('Video.Image_FileID = Image_Files.FileID' )
                        )
                    )
                 ));
             
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
        $tokeninstance = ClassRegistry::init('Token');
        $countryPrefix = $this->getCountryPrefix($territory);
        $songInstance = ClassRegistry::init('Song');
        //Added caching functionality for new release Albums           
        $country = $territory;
        if (!empty($country))
        {

            $newReleaseSongsRec = $songInstance->find('all', array(
            'conditions' => array(
                'Country.DownloadStatus = "1"' ,
                'Country.Territory = "'.$country.'" AND 1 = 1 AND Country.SalesDate != "" AND Country.SalesDate < NOW()'                   
            ),               
            'fields' => array('
                    Song.ProdID,
                    Song.ReferenceID,                     
                    Song.provider_type'
                ),              
            'order' => array(' Country.SalesDate DESC '),
            'limit' => 10000,
            'recursive' => -1,
            'joins' => array(
                            array(
                                'table' => strtolower($territory).'_countries',
                                'alias' => 'Country',
                                'type' => 'Left',
                                'foreignKey' => false,
                                'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type' )
                            )
                      )
            ));                

            $ids = '';
            $ids_provider_type = '';
            
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
            	$albumAdvisory 	   = " AND Albums.Advisory != 'T' ";
            	$cacheVariableName = 'new_releases_albums_none_explicit';
            }

            $data = array();            
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));
            $songInstance->unbindModel(array('belongsTo' => array('Sample_Files')));
            $songInstance->recursive = 2;
            
            $data = $songInstance->find('all', array(
                'conditions' => array(
                    '( Country.DownloadStatus = "1" )',
                    '( Country.Territory = "'. $territory. '") AND ( Country.SalesDate != "" ) AND ( Country.SalesDate <= NOW() )',
                    '( Song.ProdID, Song.provider_type) IN ( '.$ids_provider_type.' )  '.$albumAdvisory.' AND 1 = 1'                   
                ),               
                'fields' => array('
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
                                Full_Files.FileID'
                    ),                
                'group' => 'Albums.AlbumTitle',
                'order' => array('Country.SalesDate DESC'),
                'limit' => 100,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type' )
                    ),                  
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genre',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                    ),
                    array(
                        'table' => 'File',
                        'alias' => 'File',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Albums.FileID = File.FileID' )
                    )
             )));           

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
                Cache::write($cacheVariableName . $country, $data);
                $this->log("cache written for new releases albums for $territory", "cache");
            }
            else
            {
                $data = Cache::read($cacheVariableName . $country);
                Cache::write($cacheVariableName . $country, $data);
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
        $videoInstance = ClassRegistry::init('Video');                  
             
        //check if con
        if (!empty($territory))
        {            
            $videoInstance->unbindModel(array('hasOne' => array('Country')));
            $videoInstance->unbindModel(array('hasOne' => array('Participant')));
           
            $data = array();            
            $data = $videoInstance->find('all', array(
                'conditions' => array(
                    'Video.DownloadStatus'   => '1',
                    'Country.Territory'     => $territory,
                    'Country.SalesDate != ' => '',
                    'Country.SalesDate <=  NOW()' 
                ),               
                'fields' => array('Video.ProdID,
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
                            Image_Files.SourceURL'
                    ),                
                'group' => 'Video.ProdID ',
                'order' => array('Country.SalesDate DESC'),
                'limit' => 100,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array('Country.ProdID = Video.ProdID', 'Country.provider_type = Video.provider_type')
                    ),                   
                   
                    array(
                        'table' => 'File',
                        'alias' => 'Image_Files',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array('Video.Image_FileID = Image_Files.FileID' )
                    )
                )
             ));          
                

            if (!empty($data))
            {
                foreach ($data as $key => $value)
                {                    
                    $albumArtwork = $tokeninstance->artworkToken($value['Image_Files']['CdnPath'] . "/" . $value['Image_Files']['SourceURL']);
                    $videoAlbumImage = Configure::read('App.Music_Path') . $albumArtwork;
                    $data[$key]['videoAlbumImage'] = $videoAlbumImage;
                }            
                Cache::write("new_releases_videos" . $territory, $data);
                $this->log("cache written for new releases videos for $territory", "cache");
            }
            else
            {
                $data = Cache::read("new_releases_videos" . $territory);
                Cache::write("new_releases_videos" . $territory, $data);
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
        			$featured[$k]['albumSongs'] = $albumsongs;
        
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
    
    function getRandomSongs($artistComposer , $provider,  $flag = 0, $ajax = 0, $territory = null){
        
        if(!empty($territory)) {
            $country = $territory;
            $countryPrefix = $this->getCountryPrefix($country);  // This is to add prefix to countries table when calling through cron
        } else {
            $country = $this->Session->read('territory'); 
        }
        
         App::import('Component', 'Streaming');
            $StreamingComponent = new StreamingComponent();
        
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
                    $randomSongs[$key]['totalseconds'] = $StreamingComponent->getSeconds($value['Song']['FullLength_Duration']);
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

        }
        Cache::write('top_albums' . $territory, $topAlbumData);
        $this->log('cache written for Top Albums for: ' . $territory, 'debug');
        $this->log('cache written for Top Albums for: ' . $territory, 'cache');
      }

    }

    return $topAlbumData;
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
            $songInstance = ClassRegistry::init('Song');
            $songInstance->recursive = 2;
                       
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));       
            $songInstance->recursive = 2;
            
            $topSingleData = array();    
            $topSingleData = $songInstance->find('all', array(
                'conditions' => array(
                     '(Song.ProdID, Song.provider_type) IN ('. $ids_provider_type.' ) '           
                ),               
                'fields' => array('
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
                                Albums.provider_type'  
                    ),                
                'group' => 'Song.ProdID',
                'order' => array('FIELD(Song.ProdID,'.$ids.'  ) DESC'),
                'limit' => 50,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( '( Country.ProdID = Song.ProdID )', ' ( Country.provider_type = Song.provider_type )','Country.DownloadStatus = "1"', 
                        'Country.SalesDate != ""','Country.SalesDate < NOW()' )
                    ),                  
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genre',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                    ),
                    array(
                        'table' => 'PRODUCT',
                        'alias' => 'PRODUCT',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )                    
                    ),   
                    array(
                        'table' => 'File',
                        'alias' => 'File',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Albums.FileID = File.FileID' )
                    )
             ))); 
            
                      
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
     * 
     * @param $territory String
     * @param $genre String
     * 
     * 
     * @return $data array
     */

    function getGenreData($territory, $genre)
    {
        set_time_limit(0);
        $countryPrefix = $this->getCountryPrefix($territory);
        $albumInstance = ClassRegistry::init('Album');        
        $latestDownloadInstance = ClassRegistry::init('LatestDownload');        
        $downloadInstance = ClassRegistry::init('Download');
        
        //check data will fetch from which table
        if ($this->maintainLatestDownload)
        {
            
                $data = $latestDownloadInstance->find('all', array(
                'conditions' => array(
                    ' Song.Genre LIKE "%' . mysql_real_escape_string($genre) . '%" ',
                    ' Country.DownloadStatus = "1" ',
                     'created BETWEEN "' . Configure::read('App.tenWeekStartDate') . '" AND "' . Configure::read('App.curWeekEndDate') . '"'                            
                ),               
                'fields' => array('
                                COUNT(DISTINCT LatestDownload.id) AS countProduct,
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
                                PRODUCT.pid'
                    ),                
                'group' => 'LatestDownload.ProdID',
                'order' => array('countProduct DESC'),
                'limit' => 10,
                'recursive' => '-1',
                'joins' => array(                    
                                array(
                                    'table' => 'Songs',
                                    'alias' => 'Song',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'LatestDownload.ProdID = Song.ProdID ' ,'LatestDownload.provider_type = Song.provider_type')
                                ), 
                                array(
                                    'table' => strtolower($territory).'_countries',
                                    'alias' => 'Country',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type',
                                    ' Country.SalesDate != "" ','Country.SalesDate < NOW() ' )
                                ),                
                                array(
                                    'table' => 'PRODUCT',
                                    'alias' => 'PRODUCT',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )                    
                                ),                  
                                array(
                                    'table' => 'File',
                                    'alias' => 'Sample_Files',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.Sample_FileID = Sample_Files.FileID' )
                                ),                  
                                array(
                                    'table' => 'File',
                                    'alias' => 'Full_Files',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.FullLength_FileID = Full_Files.FileID' )
                                )
                            )
                       ));
                
        }
        else
        {            
               $data = $downloadInstance->find('all', array(
                'conditions' => array(
                    ' Song.Genre LIKE "%' . mysql_real_escape_string($genre) . '%" ',
                    ' Country.DownloadStatus = "1" ',
                     'created BETWEEN "' . Configure::read('App.tenWeekStartDate') . '" AND "' . Configure::read('App.curWeekEndDate') . '"'                            
               ),               
               'fields' => array('
                                COUNT(DISTINCT Download.id) AS countProduct,
                                Song.ProdID1,
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
                                PRODUCT.pid'
                    ),                
                'group' => 'Download.ProdID',
                'order' => array('countProduct DESC'),
                'limit' => 10,
                'recursive' => '-1',
                'joins' => array(                    
                                array(
                                    'table' => 'Songs',
                                    'alias' => 'Song',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Download.ProdID = Song.ProdID ' ,'Download.provider_type = Song.provider_type')
                                ), 
                                array(
                                    'table' => strtolower($territory).'_countries',
                                    'alias' => 'Country',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type',
                                    ' Country.SalesDate != "" ','Country.SalesDate < NOW() ' )
                                ),                
                                array(
                                    'table' => 'PRODUCT',
                                    'alias' => 'PRODUCT',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )                    
                                ),                  
                                array(
                                    'table' => 'File',
                                    'alias' => 'Sample_Files',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.Sample_FileID = Sample_Files.FileID' )
                                ),                  
                                array(
                                    'table' => 'File',
                                    'alias' => 'Full_Files',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.FullLength_FileID = Full_Files.FileID' )
                                )
                            )
                       ));       
            
            
        } 
      
        if (!empty($data))
        {           
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
        App::import('Component', 'Streaming');
        $StreamingComponent = new StreamingComponent();

        //this is for my library songs start

        if ($this->maintainLatestDownload)
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
            if ($this->maintainLatestDownload)
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

            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));
            $songInstance->recursive = 2;
            
                $topDownload = $songInstance->find('all', array(
                'conditions' => array(
                    '( ('.$top_ten_condition_songs.' ) AND 1 = 1)'                   
                ),               
                'fields' => array('
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
                            PRODUCT.pid'
                    ),                
                'group' => 'Song.ProdID ',
                'order' => array('FIELD(Song.ProdID,'.$ids.' ) ASC'),
                'limit' => 10,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type',
                        'Country.DownloadStatus = "1" ',' Country.SalesDate != "" ','Country.SalesDate < NOW() ' )
                    ),                   
                   
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genre',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                    ),                   
                   
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                    ),                   
                   
                    array(
                        'table' => 'File',
                        'alias' => 'File',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Albums.FileID = File.FileID' )
                    ),                  
                    array(
                        'table' => 'PRODUCT',
                        'alias' => 'PRODUCT',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )
                    
                )
             ))); 
            
            
           
                              
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
                        $topDownload[$key]['totalseconds'] = $StreamingComponent->getSeconds($value['Song']['FullLength_Duration']);
                    }
            }
           
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
        $songInstance = ClassRegistry::init('Song');
        
        //library top 10 cache set for albums start            
        if ($this->maintainLatestDownload)
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
        $ids_provider_type_album = '';
        foreach ($topDownloaded_albums as $k => $v)
        {
            if ($this->maintainLatestDownload) 
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

           
            $songInstance->unbindModel(array('hasOne' => array('Country')));
            $songInstance->unbindModel(array('hasOne' => array('Genre')));
            $songInstance->unbindModel(array('hasOne' => array('Participant')));
            $songInstance->recursive = 2;
            
                $topDownload = $songInstance->find('all', array(
                'conditions' => array(
                    'Country.DownloadStatus = "1"' ,
                    'Country.Territory = "'.$country.'" AND Country.SalesDate != "" AND Country.SalesDate < NOW()',
                    '('.$top_ten_condition_albums.' ) AND 1 = 1'
                ),               
                'fields' => array('
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
                        Sample_Files.FileID'
                    ),                
                'group' => 'Song.ReferenceID ',
                'order' => array('count(Song.ReferenceID) DESC'),
                'limit' => 10,
                'joins' => array(
                                array(
                                    'table' => strtolower($territory).'_countries',
                                    'alias' => 'Country',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Country.ProdID = Song.ProdID', 'Country.provider_type = Song.provider_type' )
                                ),                   

                                array(
                                    'table' => 'Genre',
                                    'alias' => 'Genre',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                                ),                   

                                array(
                                    'table' => 'Albums',
                                    'alias' => 'Albums',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                                ),                   

                                array(
                                    'table' => 'File',
                                    'alias' => 'File',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Albums.FileID = File.FileID' )
                                )
                           )
                     ));         
   
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
        

        //library top 10 cache set for videos start 
        if ($this->maintainLatestDownload)
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
            if ($this->maintainLatestDownload)
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
            
            Cache::write("defaultqueuelist", $queueData);

            //library top 10 cache set
            $this->log("Freegal Defaut Queues cache set", "cache");
        }
        
        App::import('Component', 'Queue');
        $QueueComponent = new QueueComponent();

        //set the variable for each freegal default queue 
        foreach ($queueData as $value)
        {
            $defaultQueueId = $value['QueueList']['queue_id'];
            $defaultQueueName = $value['QueueList']['queue_name'];
            $eachQueueDetails = $QueueComponent->getQueueDetails($defaultQueueId, $territory);

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
        $this->log("Cache for library top 10 starts here for date".date("Y-m-d"), "cache");
        foreach ($libraryDetails AS $key => $val)
        {
            $libId = $val['Library']['id'];
            $country = $val['Library']['library_territory'];
            $this->getLibraryTop10Albums($country, $libId);
            $this->getLibraryTop10Videos($country, $libId);
            $this->getLibraryTopTenSongs($country, $libId);
        }
        $this->log("Cache for library top 10 ends here for date".date("Y-m-d"), "cache");
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
     * 
     * @param $prodID  Int
     * 
     * @return $data array()
     */
    function getQueueAlbumDetails($ProdID)
    {
        $songInstance = ClassRegistry::init('Song');
        $territory = $this->Session->read('territory');       
        
        $songInstance->unbindModel(array('hasOne' => array('Country')));
        $songInstance->unbindModel(array('hasOne' => array('Genre')));
        $songInstance->unbindModel(array('hasOne' => array('Participant')));       
        $songInstance->recursive = 2;
        $data = array();    
        $data = $songInstance->find('all', array(
                'conditions' => array(
                     'Albums.ProdID  = "'.$ProdID.'"'                
                ),               
                'fields' => array('
                                Albums.ProdID,
                                Albums.provider_type,
                                Albums.AlbumTitle,
                                Albums.Advisory,
                                Albums.ArtistText '
                    ),                
                'group' => 'Song.ReferenceID',
                'order' => array('COUNT(Song.ReferenceID) DESC'),
                'limit' => 100,
                'joins' => array(
                                array(
                                    'table' => strtolower($territory).'_countries',
                                    'alias' => 'Country',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( '( Country.ProdID = Song.ProdID )', ' ( Country.provider_type = Song.provider_type )','Country.DownloadStatus = "1"', 
                                    'Country.SalesDate != ""','Country.SalesDate < NOW()' )
                                ),                  
                                array(
                                    'table' => 'Genre',
                                    'alias' => 'Genre',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                                ),
                                array(
                                    'table' => 'Albums',
                                    'alias' => 'Albums',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                                ),
                                array(
                                    'table' => 'PRODUCT',
                                    'alias' => 'PRODUCT',
                                    'type' => 'Left',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )                    
                                ),   
                                array(
                                    'table' => 'File',
                                    'alias' => 'File',
                                    'type' => 'Inner',
                                    'foreignKey' => false,
                                    'conditions'=> array( 'Albums.FileID = File.FileID' )
                                )
                        )
                )); 
        
        return  $data;
         
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
     * 
     * @param $ProdID Integer
     * 
     * 
     * @return $data array
     */
    function getSongsDetails($ProdID)
    {
       $songInstance = ClassRegistry::init('Song');
       $territory = $this->Session->read('territory');
       $countryPrefix = $this->getCountryPrefix($territory);
        
        
        
        $songInstance->unbindModel(array('hasOne' => array('Country')));
        $songInstance->unbindModel(array('hasOne' => array('Genre')));
        $songInstance->unbindModel(array('hasOne' => array('Participant')));       
        $songInstance->recursive = 2;
         $data = array();    
         $data = $songInstance->find('all', array(
                'conditions' => array(
                     'Song.ProdID = "'.$ProdID.'"'                
                ),               
                'fields' => array('
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
                                Country.DownloadStatus'  
                    ),                
                'group' => 'Song.ReferenceID',
                'order' => array('COUNT(Song.ReferenceID) DESC'),
                'limit' => 100,
                'joins' => array(
                    array(
                        'table' => strtolower($territory).'_countries',
                        'alias' => 'Country',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( '( Country.ProdID = Song.ProdID )', ' ( Country.provider_type = Song.provider_type )','Country.DownloadStatus = "1"', 
                        'Country.SalesDate != ""','Country.SalesDate < NOW()' )
                    ),                  
                    array(
                        'table' => 'Genre',
                        'alias' => 'Genre',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'Genre.ProdID = Song.ProdID',' Genre.provider_type = Song.provider_type ' )
                    ),
                    array(
                        'table' => 'Albums',
                        'alias' => 'Albums',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Song.ReferenceID = Albums.ProdID' )
                    ),
                    array(
                        'table' => 'PRODUCT',
                        'alias' => 'PRODUCT',
                        'type' => 'Left',
                        'foreignKey' => false,
                        'conditions'=> array( 'PRODUCT.ProdID = Song.ProdID','PRODUCT.provider_type = Song.provider_type' )                    
                    ),   
                    array(
                        'table' => 'File',
                        'alias' => 'File',
                        'type' => 'Inner',
                        'foreignKey' => false,
                        'conditions'=> array( 'Albums.FileID = File.FileID' )
                    )
             ))); 
         
               
        return  $data;

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

}
