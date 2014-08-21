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
            if(!empty($arr_video)) {
                $status = Cache::write("AppMyMusicVideosList_".$territory, $arr_video);
                $this->log("cache wrritten for mobile music videos list for territory_".$territory, "cache");
            }
        }
    }   
    
    /*
     * @func runCache
     * @desc This function is used to call all functions for setting cache variables
     */    
    function runCache(){
        set_time_limit(0);
        $this->writeLibraryTop10songsCache();
        $territoriesList = $this->Common->getTerritories();       
        foreach($territoriesList as $territory){            
            $this->setGenre($territory);
	    $this->setTopSingles($territory);
            $this->setFeaturedVideos($territory);
            $this->setTopVideoDownloads($territory);
	    $this->setTopAlbums($territory);
            $this->setUsTop10Songs($territory);
            $this->setUsTop10Albums($territory);
            $this->setUsTop10Videos($territory);
            $this->setNewReleaseAlbums($territory);
            $this->setNewReleaseVideos($territory);
            $this->setFeaturedArtists($territory);
	    $this->setFeaturedSongsInCache($territory);
            $this->setDifferentGenreData($territory);
            //$this->getArtistText($territory);
            $this->setDefaultQueues($territory);   
            
        }
       $this->setLibraryTopTenCache();
       $this->setVideoCacheVar();    
       $this->setAppMyMusicVideoList(); 
       $this->setAnnouncementCache();
       $this->setMoviesAnnouncements();
       $this->setTopArtist();
    }
    
    function setGenre($territory){ 
        $this->Common->getGenres($territory);
    }
    
    function setTopSingles($territory){
       $this->Common->getTopSingles($territory); 
    }
    function setFeaturedVideos($territory){
        $this->Common->getFeaturedVideos($territory);
    }
    
    function setTopVideoDownloads($territory){
        $this->Common->getTopVideoDownloads($territory);
    }
    
    function setTopAlbums($territory) {
        $this->Common->getTopAlbums($territory);
    }
    function setUsTop10Songs($territory) {
        $this->Common->getUsTop10Songs($territory);
    }
    
    function setUsTop10Albums($territory) {
        $this->Common->getUsTop10Albums($territory);
    }
    
    function setUsTop10Videos($territory) {
        $this->Common->getUsTop10Videos($territory);
    } 
    
    function setNewReleaseAlbums($territory) {
        $this->Common->getNewReleaseAlbums($territory, true);
    }
    
    function setNewReleaseVideos($territory) {
        $this->Common->getNewReleaseVideos($territory);
    } 
    
    function setDifferentGenreData($territory) { 
        $this->Common->getDifferentGenreData($territory);
    }
    
    function setDefaultQueues($territory) {
        $this->Common->getDefaultQueues($territory);
    }
    
    function  setLibraryTopTenCache() { 
        $this->Common->setLibraryTopTenCache();
    }
    
    function writeLibraryTop10songsCache() {
        $this->Common->setLibraryTopTenSongsCache();
    }
    
    function setVideoCacheVar() {   
        $this->Common->setVideoCacheVar(); 
    }

	function setFeaturedSongsInCache($territory) {
		$this->Common->writeFeaturedSongsInCache($territory);
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
        if(!empty($announcment_rs)){
            Cache::write("announcementCache",$announcment_rs);
            $this->log("cache wrritten for announcements", "cache");
        }    
   
    }
    
    /* Function name : setMoviesAnnouncements
     * Function Description : This function is used to set cache for movies announcements
     * 
     */
    
    function setMoviesAnnouncements() {
        $mvAannouncmentQquery = "SELECT * from announcements ORDER BY id DESC LIMIT 4";
        $db = ConnectionManager::getDataSource('movies');
        $mvAnnouncment = $db->query($mvAannouncmentQquery);                
        Cache::write("moviesannouncementCache", $mvAnnouncment);
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
