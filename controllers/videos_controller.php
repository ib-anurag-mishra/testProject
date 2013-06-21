<?php

class VideosController extends AppController {
  
    var $uses = array('Album');
    var $layout = 'home';
    
    function index(){
        if($featuredVideos = Cache::read("featured_videos".$this->Session->read('terrotory')) === false){
            $featuredVideosSql = "SELECT `FeaturedVideo`.`id`,`FeaturedVideo`.`ProdID`,`Video`.`Image_FileID`, `Video`.`VideoTitle`, `Video`.`ArtistText`, `File`.`CdnPath`, `File`.`SourceURL`, `File`.`SaveAsName` FROM featured_videos as FeaturedVideo LEFT JOIN video as Video on FeaturedVideo.ProdID = Video.ProdID LEFT JOIN File as File on File.FileID = Video.Image_FileID WHERE `FeaturedVideo`.`territory` = '".$this->Session->read('territory')."'";
            $featuredVideos = $this->Album->query($featuredVideosSql);
            if(!empty($featuredVideos)){
                Cache::write("featured_videos".$this->Session->read('territory'), $featuredVideos);
            }
        }
        
        $featuredVideos = Cache::read("featured_videos".$this->Session->read('territory'));
        
        $this->set('featuredVideos',$featuredVideos);
        
    }
}