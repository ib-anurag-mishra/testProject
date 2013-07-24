<section class="videos">


    <section class="featured-videos">

        <header class="clearfix">

            <h3><?php echo __('Featured Videos', true); ?></h3>

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
                    // print_r($featureVideo); die;
                    $videoImage = Configure::read('App.Music_Path').$videoArtwork;
                ?>
	<li>
                    <div class="featured-video-detail">
                            <div class="video-thumbnail-container">
                                <a href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="275" height="162" /></a>
                                <?php
                                if($this->Session->read('patron')) {

                        if($libraryDownload == '1' && $patronDownload == '1') {
                                ?>
<span class="featured-video-download-now-button">
                                <form method="Post" id="form<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" action="/videos/download">
                                    <input type="hidden" name="ProdID" value="<?php echo $featureVideo["FeaturedVideo"]["ProdID"];?>" />
									<input type="hidden" name="ProviderType" value="<?php echo $featureVideo["Video"]["provider_type"]; ?>" />
                                    <span id="song_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" class="beforeClick">
                                            <a  href='#' style="cursor:pointer;" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.");?>" onclick='videoDownloadAll(<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>);'><?php __('Download Now');?></a>
                                    </span>
                            <span class="afterClick" id="downloading_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                </form>
</span>
                            <?php
                               }else{
                            ?>
                                <a class="featured-video-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                            <?php
                               }
                            ?>


                                <!-- <a class="featured-video-download-now-button" href="#"><?php echo __('Download Now'); ?></a> -->
                                <a class="add-to-playlist-button" href="#"></a>
                                <div class="wishlist-popover">
                                <?php

                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($featureVideo["FeaturedVideo"]["ProdID"]);
                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$featureVideo["FeaturedVideo"]["ProdID"],$featureVideo["Video"]["provider_type"]);
                                    echo $this->Queue->getSocialNetworkinglinksMarkup();
                                ?>
                                </div>
                                <?php
                                } else {
                                ?>
                                <a class="featured-video-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="video-title">
                                
                            <a href="javascript:void(0);">
                            <?php
                            if(strlen($featureVideo['Video']['VideoTitle']) >= 50){
                                    $featureVideo['Video']['VideoTitle'] = substr($featureVideo['Video']['VideoTitle'], 0, 50). '...';
                            }
                            ?>
                            <?php echo $this->getTextEncode($featureVideo['Video']['VideoTitle']);?>
                            </a>
                            </div>
                            <div class="video-name">
                            <?php
                            if(strlen($featureVideo['Video']['ArtistText']) >= 50){
                                    $featureVideo['Video']['ArtistText'] = substr($featureVideo['Video']['ArtistText'], 0, 50). '...';
                            }
                            ?>
                            <?php echo $this->getTextEncode($ArtistText['Video']['ArtistText']);?>
                            </a>
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
            <h3><?php echo __('Top Videos', true); ?></h3>

        </header>

        <div class="video-top-genres-grid horiz-scroll">

            <ul class="clearfix">
<?php if(!empty($topVideoDownloads)){ ?>
<li>
<?php
//print_r($topVideoDownloads); die;
$i = 0;
foreach($topVideoDownloads as $topDownload)
{
     $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$topDownload['File']['CdnPath']."/".$topDownload['File']['SourceURL']);
     // print_r($featureVideo);
     $videoImage = Configure::read('App.Music_Path').$videoArtwork;
     
     
    ?>

                    
<li>
                        <div class="video-cover-container">
                            <a href="/videos/details/<?php echo $topDownload["Videodownloads"]["ProdID"]; ?>"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="163" height="97" /></a>
                            <a class="top-video-download-now-button" href="#">Download Now</a>
                           <!-- <a class="add-to-playlist-button" href="#"></a> -->
                           
                           
                                <?php
                                if($this->Session->read('patron')) {
                                    if($libraryDownload == '1' && $patronDownload == '1') {

                                ?>
                            <div class="wishlist-popover">
                 <form method="post" id="form<?php echo $topDownload["Video"]["ProdID"]; ?>" action="/videos/download">
                                <input type="hidden" name="ProdID" value="<?php echo $topDownload["Video"]["ProdID"];?>" />
				<input type="hidden" name="ProviderType" value="<?php echo $topDownload["Video"]["provider_type"]; ?>" />
                                <span class="beforeClick" style="cursor:pointer;" id="song_<?php echo $topDownload["Video"]["ProdID"]; ?>">
                                    <a href='javascript:void(0);'  class="download-now" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.");?>" onclick='videoDownloadAll(<?php echo $topDownload["Video"]["ProdID"]; ?>);'><?php __('Download Now');?></a>
                                </span>
                                <span class="afterClick"  id="downloading_<?php echo $topDownload["Video"]["ProdID"]; ?>" style="display:none;"><a href="class="download-now"><?php __('Please Wait...');?>
                                <span id="download_loader_<?php echo $topDownload["Video"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php echo $html->image('ajax-loader_black.gif'); ?>
                                </span>
                                </a></span>                                                              

                </form>	
                                </div>
			<?php
                               }else{
                            ?>
                                <a class="featured-video-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                            <?php
                               }
                            ?>					
                                <?php
                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($topDownload["Video"]["ProdID"]);
                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$topDownload["Video"]["ProdID"],$featureVideo["Video"]["provider_type"]);
                                    echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                                
                                <?php
                                } else {
                                ?>
                                <div class="featured-video-detail">
                                <div class="video-thumbnail-container" style="opacity: 0; line-height:25px; text-align: center; position:relative; top: -25px; left: 100px; font-weight: bold; height: 25px; line-height: 26px: text-transform: uppercase; color: #000000; font-size: 12px; text-decoration: none; box-shadow: 0 0 2px rgba(0, 0, 0, 0.5); width: 84px; background: none repeat scroll 0 0 #FFFFFF;">
                                <a class="featured-video-download-now-button"  href='/users/redirection_manager'> <?php __("Login");?></a>
                               <!--  <a class="add-to-wishlist" href='/users/redirection_manager'> <?php __("Login");?></a> -->
                                </div>
                                </div>
                                <?php
                                }
                                ?>
                            

                        </div>
                        <div class="video-title">
                            <a href="javascript:void(0);">

                            <?php
                            if(strlen($topDownload['Video']['VideoTitle']) >= 20){
                                    $topDownload['Video']['VideoTitle'] = substr($topDownload['Video']['VideoTitle'], 0, 20). '...';
                            }
                            ?>
                            <?php echo $this->getTextEncode($topDownload['Video']['VideoTitle']);?>
                            </a>
                        </div>
                        <div class="video-name">
                            <a href="javascript:void(0);">
                            <?php 
                            if(strlen($topDownload['Video']['ArtistText']) >= 20){
                                    $topDownload['Video']['ArtistText'] = substr($topDownload['Video']['ArtistText'], 0, 20). '...';
                            }
                            ?>
                            <?php echo $this->getTextEncode($topDownload['Video']['ArtistText']);?>


                            </a>
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