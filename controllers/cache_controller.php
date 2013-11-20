<?php

class CacheController extends AppController {

    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song', 'Album', 'Library', 'Download', 'LatestDownload', 'Country', 'Video','Genre', 'Videodownload','LatestVideodownload','QueueList', 'Territory');
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
        set_time_limit(0);
        Configure::write('debug', 2);
        $territoriesList = $this->Common->getTerritories();       
        foreach($territoriesList as $territory){
//            $this->Common->getGenres($territory);
//            $this->Common->getNationalTop100($territory);
//            $this->Common->getFeaturedVideos($territory);
//            $this->Common->getTopVideoDownloads($territory);
//            //$this->Common->getNationalTop100Videos($territory); //National top 100 videos are removed and instead albums are shownn
//            $this->Common->getNationalTop100Albums($territory);
//            $this->Common->getComingSoonSongs($territory);
//            $this->Common->getComingSoonVideos($territory);
//            $this->Common->getUsTop10Songs($territory);
//            $this->Common->getUsTop10Albums($territory);
//            $this->Common->getUsTop10Videos($territory);
//            $this->Common->getNewReleaseAlbums($territory);
//            $this->Common->getNewReleaseVideos($territory);
//            $this->Common->getFeaturedArtists($territory);
//            $this->Common->getDifferentGenreData($territory);
//            $this->getArtistText($territory);
            $this->Common->getDefaultQueues($territory);    
        }
//        $this->Common->setLibraryTopTenCache();
//        $this->Common->setVideoCacheVar();    
//        $this->setAppMyMusicVideoList(); 
       
    }
    
    /*
     * Function Name : getArtistText
     * Function Description : This function is used to getArtistText.
     */
    function getArtistText($territory){
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
            $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 ');

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
                $this->paginate = array(
                    'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 '),
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                        'Genre.Genre'
                        )
                    ),
                    ),
                    'extra' => array('chk' => 1),                   
                    'limit' => '60', 'cache' => 'yes','check' => 2
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
                    'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 '),
                    'fields' => array('DISTINCT Song.ArtistText'),
                    'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                        'Genre.Genre'
                        )
                    ),
                    ),
                    'extra' => array('chk' => 1),                    
                    'limit' => '60', 'cache' => 'yes','check' => 2
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
}