<?php

class CacheController extends AppController {

    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song', 'Album', 'Library', 'Download', 'LatestDownload', 'Country', 'Video','Genre', 'Videodownload','LatestVideodownload','QueueList', 'Territory','News','Language','MemDatas');
    var $components = array('Queue','Common','Email');
    
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

    /**
     * @function setAppMyMusicVideoList
     * this function sets music videos list in cache for each territory for App
     * @param nil
     */
    function setAppMyMusicVideoList() {
        Configure::write('debug', 0);
        set_time_limit(0);

        $territories = $this->Territory->find("all");

        for($mm=0;$mm<count($territories);$mm++)
        {
            $territoryNames[$mm] = $territories[$mm]['Territory']['Territory'];
        }
        $siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'multiple_countries'";
        $siteConfigData = $this->Album->query($siteConfigSQL);
        $multiple_countries = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
        for($i=0;$i<count($territoryNames);$i++){
          $territory = $territoryNames[$i];
          if(0 == $multiple_countries){
            $countryPrefix = '';
            $this->Country->setTablePrefix('');
          } else {
            $countryPrefix = strtolower($territory)."_";
            $this->Country->setTablePrefix($countryPrefix);
          }

            $str_query = 'SELECT v.ProdID, v.ReferenceID, v.Title, v.VideoTitle, v.ArtistText, v.Artist, v.Advisory, v.ISRC, v.Composer,
                v.FullLength_Duration, v.DownloadStatus, c.SalesDate, gr.Genre, ff.CdnPath AS VideoCdnPath, ff.SaveAsName AS VideoSaveAsName,
                imgf.CdnPath AS ImgCdnPath, imgf.SourceURL AS ImgSourceURL, prd.pid, COUNT(vd.id) AS cnt
                FROM video AS v
                INNER JOIN '.$countryPrefix.'countries AS c ON v.ProdID = c.ProdID AND v.provider_type = c.provider_type
                INNER JOIN Genre AS gr ON gr.ProdID = v.ProdID AND gr.provider_type = v.provider_type
                INNER JOIN File AS ff ON v.FullLength_FileID = ff.FileID
                INNER JOIN File AS imgf ON v.Image_FileID = imgf.FileID
                INNER JOIN PRODUCT AS prd ON prd.ProdID = v.ProdID AND prd.provider_type = v.provider_type
                LEFT JOIN videodownloads AS vd ON vd.ProdID = v.ProdID AND vd.provider_type = v.provider_type
                WHERE c.Territory = "'.$territory.'" AND v.DownloadStatus = "1" GROUP BY v.ProdID
                ORDER BY cnt DESC LIMIT 100';
            $arr_video = $this->Video->query($str_query);

            $status = Cache::write("AppMyMusicVideosList_".$territory, $arr_video);
        }
    }   
    
    /*
     * @func runCache
     * @desc This function is used to call all functions for setting cache variables
     */    
    function runCache(){        
      
        $territoriesList = $this->Common->getTerritories();   
        
        foreach($territoriesList as $territory){            
           
            $this->setNewsCache($territory);
            $this->Common->getTopSingles($territory);
            $this->Common->getFeaturedVideos($territory);
            $this->Common->getTopVideoDownloads($territory);
            $this->Common->getTopAlbums($territory);
            $this->Common->getComingSoonSongs($territory);
            $this->Common->getComingSoonVideos($territory);
            $this->Common->getUsTop10Songs($territory);
            $this->Common->getUsTop10Albums($territory);
            $this->Common->getUsTop10Videos($territory);
            $this->Common->getNewReleaseAlbums($territory);
            $this->Common->getNewReleaseVideos($territory);
            //$this->Common->getDifferentGenreData($territory);            
            $this->Common->getDefaultQueues($territory);  
            $this->getArtistText($territory);
            $this->setFeaturedArtists($territory);
            $this->Common->writeFeaturedSongsInCache($territory);
        }
       $this->Common->setLibraryTopTenCache();
       $this->Common->setVideoCacheVar();    
       $this->setAppMyMusicVideoList(); 
       $this->setAnnouncementCache();
       $this->setTopArtist();
    }
    
    /*
     * @func runGenreCache
     * @desc This function is used to call all functions for setting Genre page cache variables
     */    
    function runGenreCache(){
        set_time_limit(0);  
     
        $territoriesList = $this->Common->getTerritories();       
        foreach($territoriesList as $territory){           
            $this->Common->setArtistText($territory);            
        }
       
    }
    
    /*
     * Function Name : setAnnouncementCache
     * Function Description : This function is used to set announcment Cache.
     * all this function query must be same as queries written in app controller for announcement.
     */
    function setAnnouncementCache(){
        $announcment_query = "SELECT * from pages WHERE announcement = '1' and language='en' ORDER BY modified DESC LIMIT 1";
        $announcment_rs = $this->Album->query($announcment_query);
        Cache::write("announcementCache",$announcment_rs);
   
    }
    
    /**
     * Function Name : setFeaturedArtists
     * Function Description : This function is used to set all featured artists in Cache.
     * 
     */
    
    function setFeaturedArtists($territory){
        
        $featuresArtists = $this->Common->getFeaturedArtists($territory,1);
        if(!empty($featuresArtists)){
            Cache::write("featured_artists_" . $territory.'_'.'1', $featuresArtists);
            $this->log("cache written for featured artists for ".$territory.'_'.'1', 'debug');
            $this->log("cache written for featured artists for: ".$territory.'_'.'1', "cache");        
        }else{
            $this->log("unable to write cache for featured artists for ".$territory.'_'.'1', 'debug');
            $this->log("unable to write cache for featured artists for: ".$territory.'_'.'1', "cache");             
        }
        
        $page = 2;
        while($featuresArtists = $this->Common->getFeaturedArtists($territory,$page)){
            if(!empty($featuresArtists)){
                Cache::write("featured_artists_" . $territory.'_'.$page, $featuresArtists);
                $this->log("cache written for featured artists for ".$territory.'_'.$page, 'debug');
                $this->log("cache written for featured artists for: ".$territory.'_'.$page, "cache");        
            }else{
                $this->log("unable to write cache for featured artists for ".$territory.'_'.$page, 'debug');
                $this->log("unable to write cache for featured artists for: ".$territory.'_'.$page, "cache");             
            }                       
            $page++;
        }        
        
    }
    
     /*
     * Function Name : setNewsCache
     * Function Description : This function is used to set News Cache.
     * all this function query must be same as queries written in home controller for news.
     */
    function setNewsCache($territory){
        
         $lengRs = $this->Language->find('all', array('conditions' => array('status' => 'active'),'fields' => 'short_name'));
         
         
         foreach($lengRs as $perLeg => $lengRow) {
             $lenguage = trim($lengRow['Language']['short_name']);
             
             $news_count = $this->News->find('count', array('conditions' => array('AND' => array('language' => $lenguage))));

             if($news_count != 0){
                 $news_rs = $this->News->find('all', array('conditions' => array('AND' => array('language' => $lenguage, 'place LIKE' => "%".$territory."%")),
                'order' => 'News.created DESC',
                'limit' => '10'
                ));
                 
                $newCacheVarName = "news".$territory.$lenguage;
                
                
             }else{
                 $news_rs = $this->News->find('all', array('conditions' => array('AND' => array('language' => 'en', 'place LIKE' => "%".$territory."%")),
                'order' => 'News.created DESC',
                'limit' => '10'
                ));
                 
                $newCacheVarName = "news".$territory."en";
                
             }         
             
            Cache::write($newCacheVarName,$news_rs);           
            $this->log("cache wrritten for ".  $newCacheVarName, "cache"); 
         }     
        
    }
    
        
    /*
     * Function Name : getArtistText
     * Function Description : This function is used to getArtistText.
     * all this function query must be same as queries written in the Genere code.
     */
    function getArtistText($territory){
        
        //-------------------------------------------ArtistText Pagenation Start------------------------------------------------------
        try {
            
            $this->log("Starting to cache Artist Browsing Data for each genre for $territory",'debug');

            $country = $territory;
            
            //This code is running for all artist            
            $condition = "";
            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('hasOne' => array('Genre')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $this->Song->recursive = 0;
            $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", $condition, '1 = 1 ');
            
            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'extra' => array('chk' => 1),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'limit' => '60',
                'cache' => 'yes',
                'check' => 2,
                'all_query'=> true,                
                'all_condition'=>((is_array($condition) && isset($condition['Song.ArtistText LIKE']))? "Song.ArtistText LIKE '".$condition['Song.ArtistText LIKE']."'":(is_array($condition)?$condition[0]:$condition))
            );
            
           
            $allArtists = $this->paginate('Song');
            //this code is running for every alphabets
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
                $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 ');

                $this->paginate = array(
                    'conditions' => $gcondition,
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'order' => 'TRIM(Song.ArtistText) ASC',
                    'extra' => array('chk' => 1),                
                    'limit' => '60',
                    'cache' => 'yes',
                    'check' => 2,
                    'all_query'=> true,                
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
            
            //run for all Genre
            $this->Genre->Behaviors->attach('Containable');
            $this->Genre->recursive = 2;
           
            $genreAll = $this->Genre->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Country.Territory' => $country, "Genre.Genre NOT IN( 'Caribbean','Downtempo','Dub','Fusion','House','Indie' ,'Progressive Rock','Psychedelic Rock', 'Symphony' ,'World' ,'Porn Groove')"
                        )
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
           
           
            
            foreach($genreAll as $genreRow){
                
                $genre = mysql_real_escape_string(addslashes($genreRow['Genre']['Genre']));
                $condition = "";
                $this->Song->unbindModel(array('hasOne' => array('Participant')));
                $this->Song->unbindModel(array('hasOne' => array('Country')));
                $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
                $this->Song->Behaviors->attach('Containable');
                $this->Song->recursive = 0;               
                
                $gcondition = array("Song.provider_type = Genre.provider_type", "Genre.Genre = '$genre'", "find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", "Song.FullLength_FIleID != ''", $condition, '1 = 1 ');
                $this->paginate = array(
                    'conditions' => $gcondition,
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'order' => 'TRIM(Song.ArtistText) ASC',
                    'contain' => array(
                        'Genre' => array(
                            'fields' => array(
                                'Genre.Genre'
                            )),
                    ),
                    'extra' => array('chk' => 1),
                    'limit' => '60', 'cache' => 'yes', 'check' => 2
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
                    $gcondition = array("Song.provider_type = Genre.provider_type", "Genre.Genre = '$genre'", "find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", "Song.FullLength_FIleID != ''", $condition, '1 = 1 ');
                    $this->paginate = array(
                        'conditions' => $gcondition,
                        'fields' => array('DISTINCT Song.ArtistText'),
                        'order' => 'TRIM(Song.ArtistText) ASC',
                        'contain' => array(
                            'Genre' => array(
                                'fields' => array(
                                    'Genre.Genre'
                                )),
                        ),
                        'extra' => array('chk' => 1),
                        'limit' => '60', 'cache' => 'yes', 'check' => 2
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
        
        
/**
  * Function Name : setTopArtist
  * Function Description : This function sets top artist albums in cache, used in App only
  */  
        
  function setTopArtist(){

    set_time_limit(0);
    
    //fetch all libraries
    $libraryDetails = $this->Library->find('all',array(
      'fields' => array('id', 'library_territory', 'library_block_explicit_content'),
      'conditions' => array('library_status' => 'active'),
      'recursive' => -1
    ));
  
    //loop for library
    foreach ($libraryDetails AS $key => $libval){  
    
      $library_territory = $libval['Library']['library_territory'];
       $topSinglesCache = Cache::read("top_singles".$library_territory);
      if ( (($topSinglesCache) !== false) && ($topSinglesCache !== null) ) { // checks if nationalTop100 is set
    
        //fetches top artist from nationTop100----Start
        $arrTmp = $arrData = $arrFinal = $arrArtist = array();
        $arrTmp = $topSinglesCache;
    
        foreach($arrTmp AS $key => $val){
          $arrData[] = trim($val['Song']['ArtistText']);
        }

        $arrFinal = array_count_values($arrData);
        arsort($arrFinal, SORT_NUMERIC);

        foreach($arrFinal AS $key => $val){
          $arrArtist[] = $key;
        }
        //----------------------------------------End

        //loop for artist
        foreach($arrArtist AS $key => $artistText){ 
          
          $this->Session->write('territory', $library_territory);
          $this->switchCpuntriesTable();          
          
          if(1 == $libval['Library']['library_block_explicit_content']) {
            $cond = array('Song.Advisory' => 'F');
          } else  {
            $cond = "";
          }
          
          //fetches albums ids
          $songs = array();
          $songs = $this->Song->find('all', array(
            'fields' => array('DISTINCT Song.ReferenceID', 'Song.provider_type', 'Country.SalesDate'),
            'conditions' => array(
              'LOWER(Song.ArtistText)' => strtolower($artistText),
              "Song.Sample_FileID != ''",
              "Song.FullLength_FIleID != ''" ,
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
            'order'=>array('Country.SalesDate DESC')
          ));
          
          $val = '';
          $val_provider_type = '';
          
          foreach($songs as $k => $v){
            if (empty($val)) {
              $val .= $v['Song']['ReferenceID'];
              $val_provider_type .= "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
            } else {
              $val .= ',' . $v['Song']['ReferenceID'];
              $val_provider_type .= ',' . "(" . $v['Song']['ReferenceID'] . ",'" . $v['Song']['provider_type'] . "')";
            }
          }
          
          $condition = array();
          $condition = array("(Album.ProdID, Album.provider_type) IN (".rtrim($val_provider_type,",").") AND Album.provider_type = Genre.provider_type");
          
          
          //fetch album details
          $albumData = array();
          $albumData = $this->Album->find('all',array('conditions' =>
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
                  'Files.CdnPath' ,
                  'Files.SaveAsName',
                  'Files.SourceURL'
                ),
              )
            ), 
            'order' => array('FIELD(Album.ProdID, '.$val.') ASC'), 
            'chk' => 2,
          ));
          
          //sets cache 
          if (!empty($albumData)) {
            
            $artistText = strtolower(str_replace(' ', '_', $artistText));
            Cache::write("mobile_top_artist_" . $artistText . '_' . $library_territory, $albumData);
            $this->log("mobile_top_artist_" . $artistText . '_' . $library_territory . 'set successfully', "topartist");

          } else  {
            
            $this->log("mobile_top_artist_" . $artistText . '_' . $library_territory . 'failed', "topartist");
          }
            
        }
    
      } else {
        $this->log("national top 100 not set in Cache for territory " . $library_territory, "topartist");
      }

  
    }  
    
  }

  private function getTextUTF($text) {

    $text = iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    return iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  }           
}
