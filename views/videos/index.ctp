<section class="videos">


    <section class="featured-videos">

        <header class="clearfix">

            <h3>Featured Videos</h3>

        </header>
        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">


<?php if(count($featuredVideos) > 0){ ?>
                <li>
                <?php
                // $featured_video_array = array('img/videos/featured-videos/featured-video-aerosmith-274x162.jpg', 'img/videos/featured-videos/featured-video-aliciakeys-274x162.jpg', 'img/videos/featured-videos/featured-video-calvinharris-274x162.jpg', 'img/videos/featured-videos/featured-video-cherlloyd-274x162.jpg', 'img/videos/featured-videos/featured-video-kingsofleon-274x162.jpg', 'img/videos/featured-videos/featured-video-pink-274x162.jpg', 'img/videos/featured-videos/featured-video-aerosmith-274x162.jpg', 'img/videos/featured-videos/featured-video-aliciakeys-274x162.jpg', 'img/videos/featured-videos/featured-video-calvinharris-274x162.jpg', 'img/videos/featured-videos/featured-video-cherlloyd-274x162.jpg', 'img/videos/featured-videos/featured-video-kingsofleon-274x162.jpg', 'img/videos/featured-videos/featured-video-pink-274x162.jpg');
                
                
                
                // for ($c = 0; $c < count($featured_video_array); $c+=2) {
                $i = 0;
                
                foreach($featuredVideos as $featureVideo){
                    $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$featureVideo['File']['CdnPath']."/".$featureVideo['File']['SourceURL']);
                    // print_r($featureVideo);
                    $videoImage = Configure::read('App.Music_Path').$videoArtwork;
                ?>
                    <div class="featured-video-detail">
                            <div class="video-thumbnail-container">
                                <a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="275" height="162" /></a>

                                <a class="featured-video-download-now-button" href="#"><?php echo __('Download Now'); ?></a>
                                <a class="add-to-playlist-button" href="#"></a>
                                <div class="wishlist-popover">

                                    <a class="add-to-wishlist" href="#"><?php echo __('Add To Wishlist'); ?></a>

                                    <div class="share clearfix">
                                        <p><?php echo __('Share via'); ?></p>
                                        <a class="facebook" href="#"></a>
                                        <a class="twitter" href="#"></a>
                                    </div>

                                </div>

                            </div>
                            <div class="video-title">
                                <a href="#"><?php echo $featureVideo['Video']['VideoTitle']; ?></a>
                            </div>
                            <div class="video-name">
                                <a href="#"><?php echo $featureVideo['Video']['ArtistText']; ?></a>
                            </div>
                        </div>
                <?php 
                $i++;
                if(($i % 2) == 0) {
                  echo "</li><li>";  
                }
                ?>
                
    <?php
    }
    ?>
                    </li>
                    <?php
}
?>
            </ul>
        </section>
    </section> <!-- end .featured-videos -->
    <section class="video-top-genres">
        <header class="clearfix">
            <h3>Top Videos</h3>

        </header>

        <div class="video-top-genres-grid horiz-scroll">

            <ul class="clearfix">
<?php if(!empty($topVideoDownloads)){ ?>
<li>
<?php
$i = 0;
foreach($topVideoDownloads as $topDownload)
{
     $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$topDownload['File']['CdnPath']."/".$topDownload['File']['SourceURL']);
     // print_r($featureVideo);
     $videoImage = Configure::read('App.Music_Path').$videoArtwork;
    ?>

                    

                        <div class="video-cover-container">
                            <a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="163" height="97" /></a>
                            <a class="top-video-download-now-button" href="#">Download Now</a>
                            <a class="add-to-playlist-button" href="#"></a>
                            <div class="wishlist-popover">

                                <a class="download-now" href="#">Download Now</a>
                                <a class="add-to-wishlist" href="#">Add To Wishlist</a>

                                <div class="share clearfix">
                                    <p>Share via</p>
                                    <a class="facebook" href="#"></a>
                                    <a class="twitter" href="#"></a>
                                </div>

                            </div>

                        </div>
                        <div class="video-title">
                            <a href="#"><?php echo $topDownload['Video']['VideoTitle']; ?></a>
                        </div>
                        <div class="video-name">
                            <a href="#"><?php echo $topDownload['Video']['ArtistText']; ?></a>
                        </div>
               <?php 
                $i++;
                if(($i % 2) == 0) {
                  echo "</li><li>";  
                }
                ?>
    <?php
    }
?>
                    </li>
                    <?php
}   
?>


            </ul>
        </div>
    </section> <!-- end .video-top-genres -->








</section> <!-- end .videos -->