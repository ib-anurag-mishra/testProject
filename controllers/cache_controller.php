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
    
    function admin_setGenre($territory){ 
        $this->Common->getGenres($territory);
    }
    
    function admin_setTopSingles($territory){
       $this->Common->getTopSingles($territory); 
    }
    function admin_setFeaturedVideos($territory){
        $this->Common->getFeaturedVideos($territory);
    }
    
    function admin_setTopVideoDownloads($territory){
        $this->Common->getTopVideoDownloads($territory);
    }
    
    function admin_setTopAlbums($territory) {
        $this->Common->getTopAlbums($territory);
    }
    function admin_setUsTop10Songs($territory) {
        $this->Common->getUsTop10Songs($territory);
    }
    
    function admin_setUsTop10Albums($territory) {
        $this->Common->getUsTop10Albums($territory);
    }
    
    function admin_setUsTop10Videos($territory) {
        $this->Common->getUsTop10Videos($territory);
    } 
    
    function admin_setNewReleaseAlbums($territory) {
        $this->Common->getNewReleaseAlbums($territory, true);
    }
    
    function admin_setNewReleaseVideos($territory) {
        $this->Common->getNewReleaseVideos($territory);
    } 
    
    function admin_setDifferentGenreData($territory) { 
        $this->Common->getDifferentGenreData($territory);
    }
    
    function admin_setDefaultQueues($territory) {
        $this->Common->getDefaultQueues($territory);
    }
    
    function admin_setLibraryTopTenCache() { 
        $this->Common->setLibraryTopTenCache();
    }
    
    function admin_writeLibraryTop10songsCache() {
        $this->Common->setLibraryTopTenSongsCache();
    }
    
    function admin_setVideoCacheVar() {   
        $this->Common->setVideoCacheVar(); 
    }

    function admin_setFeaturedSongsInCache($territory) {
        $this->Common->writeFeaturedSongsInCache($territory);
    }         
}
