<?php

class VideosController extends AppController {
  
    var $uses = array('Album');
    var $layout = 'home';
    
    function index(){
        if($featuredVideos = Cache::read("featured_videos".$this->Session->read('territory')) === false){
            $featuredVideosSql = "SELECT `FeaturedVideo`.`id`,`FeaturedVideo`.`ProdID`,`Video`.`Image_FileID`, `Video`.`VideoTitle`, `Video`.`ArtistText`, `File`.`CdnPath`, `File`.`SourceURL`, `File`.`SaveAsName` FROM featured_videos as FeaturedVideo LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID LEFT JOIN File as File on File.FileID = Video.Image_FileID WHERE `FeaturedVideo`.`territory` = '".$this->Session->read('territory')."'";
            $featuredVideos = $this->Album->query($featuredVideosSql);
            if(!empty($featuredVideos)){
                Cache::write("featured_videos".$this->Session->read('territory'), $featuredVideos);
            }
        }
        
        $prefix = strtolower($this->Session->read('territory'));
        
        if($topDownloads = Cache::read("top_download_videos".$this->Session->read('territory')) === false){
            $topDownloadSQL = "SELECT Videodownloads.ProdID, Video.VideoTitle, Video.ArtistText, File.CdnPath, File.SourceURL, COUNT(DISTINCT(Videodownloads.id)) AS COUNT FROM videodownloads as Videodownloads LEFT JOIN video as Video ON (Videodownloads.ProdID = Video.ProdID AND Videodownloads.provider_type = Video.provider_type) LEFT JOIN File as File ON (Video.Image_FileID = File.FileID) LEFT JOIN libraries as Library ON Library.id=Videodownloads.library_id WHERE library_id=1 AND Library.library_territory='".$this->Session->read('territory')."' GROUP BY Videodownloads.ProdID ORDER BY COUNT DESC";
            $topDownloads = $this->Album->query($topDownloadSQL);
            if(!empty($topDownloads)){
                Cache::write("top_download_videos".$this->Session->read('territory'), $topDownloads);
            }
        }
        
        $featuredVideos = Cache::read("featured_videos".$this->Session->read('territory'));
        
        $topDownloads = Cache::read("top_download_videos".$this->Session->read('territory'));
        
        $this->set('featuredVideos',$featuredVideos);
        
        $this->set('topVideoDownloads', $topDownloads);
        
    }
}