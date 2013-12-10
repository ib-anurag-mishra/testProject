<section class="videos">

    <section class="featured-videos">
        <header class="clearfix">
            <h3><?php echo __('Featured Videos', true); ?></h3>
        </header>      

        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">
                <?php
                $total_videos = count($featuredVideos);
                $sr_no = 0;
                              
                if ($total_videos > 0)
                {
                    foreach ($featuredVideos as $key => $featureVideo)
                    {
                                             
                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && isset($featureVideo["FeaturedVideo"]['Advisory']) && ($featureVideo["FeaturedVideo"]['Advisory'] == 'T'))
                        {
                            continue;
                        }

                        if ($sr_no % 2 == 0)
                        {
                            ?>
                            <li> 
                                <?php
                            }
                            ?>
                            <div class="featured-video-detail">
                                <div class="video-thumbnail-container">
                                    <a href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>">
                                        <img src="<?php echo $featureVideo['videoImage']; ?>" class="lazy" data-original="" width="275" height="162" alt="" />
                                    </a>

                                    <?php
                                    if ($patId)
                                    {
                                        if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
                                            $productInfo = $mvideo->getDownloadData($featureVideo["FeaturedVideo"]["ProdID"], $featureVideo["Video"]["provider_type"]);
                                            $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
                                            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
                                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind($featureVideo['FeaturedVideo']['ProdID'], $featureVideo['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));

                                            if ($featureVideo[0]['totalProds'] > 0)
                                            {
                                                $featureVideo['Video']['status'] = 'avail';
                                            }
                                            else
                                            {
                                                $featureVideo['Video']['status'] = 'not';
                                            }

                                            if ($featureVideo['Video']['status'] != 'avail')
                                            {
                                                ?>
                                                <span class="featured-video-download-now-button ">
                                                    <form method="Post" id="form<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" action="/videos/download">
                                                        <input type="hidden" name="ProdID" value="<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $featureVideo["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="download_video_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a class="no-ajaxy" href="javascript:void(0);" 
                                                               title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" 
                                                               onclick='return wishlistVideoDownloadOthers("<?php echo $featureVideo['FeaturedVideo']['ProdID']; ?>", "0", "<?php echo urlencode($finalVideoUrlArr[0]); ?>", "<?php echo urlencode($finalVideoUrlArr[1]); ?>", "<?php echo urlencode($finalVideoUrlArr[2]); ?>", "<?php echo $featureVideo['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <label class="top-10-download-now-button">
                                                                    <a class="no-ajaxy" 
                                                                            title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                            onclick="wishlistVideoDownloadIE('<?php echo $featureVideo['FeaturedVideo']['ProdID']; ?>','0','<?php echo $featureVideo['Video']['provider_type']; ?>');" href="<?php echo trim($finalVideoUrl); ?>"><?php __('Download Now'); ?></a></label>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
                                                        <span id="vdownload_loader_<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </span>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="featured-video-download-now-button " href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <a class="featured-video-download-now-button " href="javascript:void(0);"><?php __("Limit Met"); ?></a> 
                                            <?php
                                        }
                                        ?>
                                        <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                                        <div class="wishlist-popover">
                                            <?php
                                            $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($featureVideo["FeaturedVideo"]["ProdID"]);
                                            echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $featureVideo["FeaturedVideo"]["ProdID"], $featureVideo["Video"]["provider_type"]);
                                            echo $this->Queue->getSocialNetworkinglinksMarkup();
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="featured-video-download-now-button" href='/users/redirection_manager'> 
                                            <?php __("Login"); ?>
                                        </a>
                                        <?php
                                    }
                                    ?>

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
                            <?php
                            if ($sr_no % 2 == 1 || $sr_no == ($total_videos - 1))
                            {
                                ?> 
                            </li> 
                            <?php
                        }
                        $sr_no++;
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
                $sr_no = 0;

                if ($total_videos > 0)
                {
                    foreach ($topVideoDownloads as $key => $topDownload)
                    {

                        if ($sr_no % 2 == 0)
                        {
                            ?>
                            <li> 
                                <?php
                            }
                            ?>
                            <div class="video-cover-container">
                                <a href="/videos/details/<?php echo $topDownload["Videodownloads"]["ProdID"]; ?>">
                                    <img alt="" class="lazy" src="<?php echo $topDownload['videoImage']; ?>" data-original="" width="163" height="97" />
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
                                            if ($topDownload[0]['totalProds'] > 0)
                                            {
                                                $topDownload['Video']['status'] = 'avail';
                                            }
                                            else
                                            {
                                                $topDownload['Video']['status'] = 'not';
                                            }

                                            if ($topDownload['Video']['status'] != 'avail')
                                            {
                                                ?>
                                                <form method="post" id="form<?php echo $topDownload["Video"]["ProdID"]; ?>" action="/videos/download">
                                                    <input type="hidden" name="ProdID" value="<?php echo $topDownload["Video"]["ProdID"]; ?>" />
                                                    <input type="hidden" name="ProviderType" value="<?php echo $topDownload["Video"]["provider_type"]; ?>" />
                                                    <span class="beforeClick" id="download_video_<?php echo $topDownload["Video"]["ProdID"]; ?>">
                                                        <![if !IE]>
                                                        <a class="no-ajaxy" href="javascript:void(0);" 
                                                           title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" 
                                                           onclick='return wishlistVideoDownloadOthers("<?php echo $topDownload['Video']['ProdID']; ?>", "0", "<?php echo urlencode($finalVideoUrlArr[0]); ?>", "<?php echo urlencode($finalVideoUrlArr[1]); ?>", "<?php echo urlencode($finalVideoUrlArr[2]); ?>", "<?php echo $topDownload['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                        <![endif]>
                                                        <!--[if IE]>
                                                        <label class="top-10-download-now-button">
                                                        <a class="no-ajaxy" 
                                                        title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                        onclick="wishlistVideoDownloadIE('<?php echo $topDownload['Video']['ProdID']; ?>','0','<?php echo $topDownload['Video']['provider_type']; ?>');" href="<?php echo trim($finalVideoUrl); ?>"><?php __('Download Now'); ?></a></label>
                                                        <![endif]-->
                                                    </span>
                                                    <span class="afterClick" id="vdownloading_<?php echo $topDownload["Video"]["ProdID"]; ?>" 
                                                          style="display:none;">
                                                              <?php __('Please Wait...&nbsp&nbsp'); ?>
                                                    </span>
                                                    <span id="vdownload_loader_<?php echo $topDownload["Video"]["ProdID"]; ?>" style="display:none;float:right;">
                                                        <?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?>
                                                    </span>
                                                    </span>
                                                    </a></span>                                                              
                                                </form>	
                                                <?php
                                            }
                                             else
                                            {
                                                ?>
                                                <a class="featured-video-download-now-button " href='/homes/my_history'>
                                                    <label class="dload" style="width:120px;cursor:pointer;" 
                                                           title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'>
                                                               <?php __('Downloaded'); ?>
                                                    </label>
                                                </a>
                                                <?php
                                            }
                                            
                                        }
                                        else
                                        {
                                            ?>
                                            <a class="featured-video-download-now-button " href="javascript:void(0);"><?php __("Limit Met"); ?></a> 
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
                            <?php
                            if ($sr_no % 2 == 1 || $sr_no == ($total_videos - 1))
                            {
                                ?> 
                            </li> 
                            <?php
                        }
                        $sr_no++;
                    }
                }
                ?>
            </ul>
        </div>

    </section> <!-- end .video-top-genres -->

</section>