<section class="videos">

    <section class="featured-videos">
        <header class="clearfix">
            <h3><?php echo __('Featured Videos', true); ?></h3>
        </header>      

        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">
                <?php
                $total_videos = count($featuredVideos);

                if ($total_videos > 0)
                {
                    foreach ($featuredVideos as $key => $featureVideo)
                    {
                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && isset($featureVideo["FeaturedVideo"]['Advisory']) && ($featureVideo["FeaturedVideo"]['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        ?>
                        <li>
                            <div class="featured-video-detail">
                                <div class="video-thumbnail-container">

                                </div>

                                <div class="video-title">
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($featureVideo['Video']['VideoTitle'])); ?>" 
                                       href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>">
                                           <?php
                                           if (strlen($featureVideo['Video']['VideoTitle']) >= 20)
                                           {
                                               $featureVideo['Video']['VideoTitle'] = substr($featureVideo['Video']['VideoTitle'], 0, 20) . '...';
                                           }
                                           ?>
                                           <?php echo $this->getTextEncode($featureVideo['Video']['VideoTitle']); ?>
                                    </a> 
                                    <?php
                                    if (isset($featureVideo['Video']['Advisory']) && 'T' == $featureVideo['Video']['Advisory'])
                                    {
                                        ?> 
                                        <span style="color: red;display: inline;"> (Explicit)</span> 
                                        <?php
                                    }
                                    ?>

                                </div>

                                <div class="video-name">
                                    <?php
                                    if (strlen($featureVideo['Video']['ArtistText']) >= 20)
                                    {
                                        $featureVideo['Video']['ArtistText'] = substr($featureVideo['Video']['ArtistText'], 0, 20) . '...';
                                    }
                                    ?>
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($featureVideo['Video']['ArtistText'])); ?>" 
                                       href="/artists/album/<?php echo base64_encode($featureVideo['Video']['ArtistText']); ?>">
                                           <?php echo $this->getTextEncode($featureVideo['Video']['ArtistText']); ?>
                                    </a>
                                </div>
                            </div>                            
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </section>
    </section><!-- end .featured-videos -->








    <section class="video-top-genres">
        <header class="clearfix">
            <h3><?php echo __('Top Videos', true); ?></h3>
        </header>

        <div class="video-top-genres-grid horiz-scroll" style="margin-top:26px;">
            <ul class="clearfix">
                <?php
                $total_videos = count($topVideoDownloads);
                if ($total_videos > 0)
                {
                    foreach ($topVideoDownloads as $key => $topDownload)
                    {
                        ?>
                        <li> 
                            <div class="video-cover-container">
                                <a href="/videos/details/<?php echo $topDownload["Videodownloads"]["ProdID"]; ?>">
                                    <img alt="" src="<?php echo $topDownload['videoImage']; ?>" data-original="" width="163" height="97" />
                                </a>

                                <?php
                                if ($patId)
                                {
                                    ?>                                  
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a> 
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="top-video-login-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>
                                    <?php
                                }
                                ?>
                                <div class="wishlist-popover">
                                    <?php
                                    if ($patId)
                                    {
                                        
                                       if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
                                           $productInfo = $mvideo->getDownloadData($topDownload["Video"]["ProdID"], $topDownload["Video"]["provider_type"]);
                                            $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
                                            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
                                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind($topDownload['Video']['ProdID'], $topDownload['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                            if ($downloadsUsed > 0)
                                            {
                                                $topDownload['Video']['status'] = 'avail';
                                            }
                                            else
                                            {
                                                $topDownload['Video']['status'] = 'not';
                                            }
                                       }
                                       else
                                        {
                                            ?>
                                            <a class="featured-video-download-now-button " href="javascript:void(0);">
                                                <?php __("Limit Met"); ?>
                                            </a> 
                                            <?php
                                        }
                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($topDownload["Video"]["ProdID"]);
                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $topDownload["Video"]["ProdID"], $featureVideo["Video"]["provider_type"]);
                                        echo $this->Queue->getSocialNetworkinglinksMarkup();
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="video-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($topDownload['Video']['VideoTitle'])); ?>" 
                                   href="/videos/details/<?php echo $topDownload["Videodownloads"]["ProdID"]; ?>">

                                    <?php
                                    if (strlen($topDownload['Video']['VideoTitle']) >= 20)
                                    {
                                        $topDownload['Video']['VideoTitle'] = substr($topDownload['Video']['VideoTitle'], 0, 20) . '...';
                                    }
                                    ?>
                                    <?php echo $this->getTextEncode($topDownload['Video']['VideoTitle']); ?>
                                </a> 
                                <?php
                                if (isset($topDownload['Video']['Advisory']) && 'T' == $topDownload['Video']['Advisory'])
                                {
                                    ?> 
                                    <span style="color: red;display: inline;"> (Explicit)</span> 
                                    <?php
                                }
                                ?>
                            </div>

                            <div class="video-name">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($topDownload['Video']['ArtistText'])); ?>" 
                                   href="/artists/album/<?php echo base64_encode($topDownload['Video']['ArtistText']); ?>">
                                       <?php
                                       if (strlen($topDownload['Video']['ArtistText']) >= 20)
                                       {
                                           $topDownload['Video']['ArtistText'] = substr($topDownload['Video']['ArtistText'], 0, 20) . '...';
                                       }
                                       ?>
                                       <?php echo $this->getTextEncode($topDownload['Video']['ArtistText']); ?>
                                </a>
                            </div>
                        <li> 
                            <?php
                        }
                    }
                    ?>
            </ul>
        </div>

    </section> <!-- end .video-top-genres -->

</section>