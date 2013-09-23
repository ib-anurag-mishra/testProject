<section class="videos">


    <section class="featured-videos">

        <header class="clearfix">

            <h3><?php echo __('Featured Videos', true); ?></h3>

        </header>
        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">


<?php 
$libId = $this->Session->read('library');
$patId = $this->Session->read('patron');
if(count($featuredVideos) > 0){ ?>                
                <?php
                    $total_videos = count($featuredVideos);
                    $sr_no = 0;
                
                foreach($featuredVideos as $featureVideo){  
                    //hide song if library block the explicit content
                    if(($this->Session->read('block') == 'yes') && isset($featureVideo["FeaturedVideo"]['Advisory']) && ($featureVideo["FeaturedVideo"]['Advisory'] =='T')) {
                        continue;
                    }
                ?>
	<?php if($sr_no%2==0) {?><li> <?php }?>
                    <div class="featured-video-detail">
                            <div class="video-thumbnail-container">
                                <a href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>"><img src="<?php echo $featureVideo['videoImage']; ?>" data-original="" width="275" height="162" /></a>
                                <?php
                                if($this->Session->read('patron')) {

                                    if($libraryDownload == '1' && $patronDownload == '1') {
                                        $downloadsUsed =  $this->Videodownload->find('all',array('conditions' => array('ProdID' => $featureVideo['Video']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
                                        if(count($downloadsUsed) > 0){
                                          $featureVideo[$key]['Video']['status'] = 'avail';
                                        } else{
                                          $featureVideo[$key]['Video']['status'] = 'not';
                                        }
                                        if($featureVideo['Video']['status'] != 'avail') {?>
                                            <span class="featured-video-download-now-button no-ajaxy">
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
                                        } else {?>
                                            <a class="featured-video-download-now-button no-ajaxy" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                                        <?php
                                        }
                                    }else{ ?>
                                        <a class="featured-video-download-now-button no-ajaxy" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                                    <?php
                                    }
                                    ?>
                                    <!-- <a class="featured-video-download-now-button" href="#"><?php echo __('Download Now'); ?></a> -->
                                    <a class="add-to-playlist-button no-ajaxy" href="#"></a>
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
                                if(strlen($featureVideo['Video']['VideoTitle']) >= 20){
                                        $featureVideo['Video']['VideoTitle'] = substr($featureVideo['Video']['VideoTitle'], 0, 20). '...';
                                }
                                ?>
                                <?php echo $this->getTextEncode($featureVideo['Video']['VideoTitle']);?>
                                </a> <?php if(isset($featureVideo['Video']['Advisory']) && 'T' == $featureVideo['Video']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="video-name">
                                <?php
                                if(strlen($featureVideo['Video']['ArtistText']) >= 20){
                                    $featureVideo['Video']['ArtistText'] = substr($featureVideo['Video']['ArtistText'], 0, 20). '...';
                                }
                                ?>
                                <?php echo $this->getTextEncode($featureVideo['Video']['ArtistText']);?>
                            </div>
                        </div>
               <?php if($sr_no%2==1 || $sr_no==($total_videos-1)) {?> </li> <?php } ?>
                
    <?php
                 $sr_no++;
    }
    ?>
                    
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

        <div class="video-top-genres-grid horiz-scroll" style="margin-top:26px;">

            <ul class="clearfix">
<?php if(!empty($topVideoDownloads)){ ?>

<?php
//print_r($topVideoDownloads); die;
  $total_videos = count($topVideoDownloads);
  $sr_no = 0;
foreach($topVideoDownloads as $topDownload)
{
    ?>

                    
                    <?php if($sr_no%2==0) {?><li> <?php }?>
                        <div class="video-cover-container">
                            <a href="/videos/details/<?php echo $topDownload["Videodownloads"]["ProdID"]; ?>"><img src="<?php echo $topDownload['videoImage']; ?>" data-original="" width="163" height="97" /></a>
                            
                            
                           <?php
                                    if($this->Session->read('patron'))
                                    {
                           ?>
                         <!--  <a class="top-video-download-now-button" href="#">Download Now</a> -->
                           <a class="add-to-playlist-button no-ajaxy" href="#"></a> 
                           <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <a class="top-video-login-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                        <?php
                                    }
                              ?>
                           
                           <div class="wishlist-popover">
                                <?php
                                if($this->Session->read('patron')) {
                                    if($libraryDownload == '1' && $patronDownload == '1') {
                                        $downloadsUsed =  $this->Videodownload->find('all',array('conditions' => array('ProdID' => $topDownload['Video']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
                                        if(count($downloadsUsed) > 0){
                                          $topDownload[$key]['Video']['status'] = 'avail';
                                        } else{
                                          $topDownload[$key]['Video']['status'] = 'not';
                                        }
                                        if($topDownload['Video']['status'] != 'avail') {?>
                                ?>
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
                                        <?php	
                                        } else {?>
                                            <a class="featured-video-download-now-button no-ajaxy" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                                        <?php
                                        }
                                    }else{
                            ?>
                                <a class="featured-video-download-now-button no-ajaxy" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                            <?php
                               }
                            ?>	
                                <?php
                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($topDownload["Video"]["ProdID"]);
                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$topDownload["Video"]["ProdID"],$featureVideo["Video"]["provider_type"]);
                                    echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                                
                                <?php
                                } 
                                ?>
                            
                                 </div>
                        </div>
                        <div class="video-title">
                            <a href="javascript:void(0);">

                            <?php
                            if(strlen($topDownload['Video']['VideoTitle']) >= 20){
                                    $topDownload['Video']['VideoTitle'] = substr($topDownload['Video']['VideoTitle'], 0, 20). '...';
                            }
                            ?>
                            <?php echo $this->getTextEncode($topDownload['Video']['VideoTitle']);?>
                            </a> <?php if(isset($topDownload['Video']['Advisory']) && 'T' == $topDownload['Video']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
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
                <?php if($sr_no%2==1 || $sr_no==($total_videos-1)) {?> </li> <?php } ?>
    <?php
             $sr_no++;
    }
?>
                   
                    <?php
                    
                      
}   
?>


            </ul>
        </div>
    </section> <!-- end .video-top-genres -->








</section> <!-- end .videos -->