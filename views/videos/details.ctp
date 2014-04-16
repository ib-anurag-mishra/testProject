
<section class="individual-videos-page">
    <div class="breadcrumbs">                
        <?php
        $html->addCrumb(__('Video', true), 'javascript:void(0);');
        echo $html->getCrumbs('>', __('Home', true), '/homes');
        ?>
    </div>
    <div class="hero-container clearfix">
        <div class="hero-image-container">

            <img src="<?php echo $VideosData[0]['videoImage']; ?>" alt="<?php echo $this->getValidText($VideosData[0]['Video']['VideoTitle']); ?>" width="555" height="323" />

            <?php
            $libId = $this->Session->read('library');
            $patId = $this->Session->read('patron');
            if ($this->Session->read('patron'))
            {
                if (strtotime($VideosData[0]['Country']['SalesDate']) < time())
                {
                    if ($libraryDownload == '1' && $patronDownload == '1')
                    {
                        $productInfo = $mvideo->getDownloadData($VideosData[0]["Video"]["ProdID"], $VideosData[0]["Video"]["provider_type"]);                        
                        $videoUrl = $this->Token->regularToken($productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
                        $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
                        $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                        $downloadsUsed = $this->Videodownload->getVideodownloadfind($VideosData[0]['Video']['ProdID'], $VideosData[0]['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                        if ($downloadsUsed > 0)
                        {
                            $VideosData[0]['Video']['status'] = 'avail';
                        }
                        else
                        {
                            $VideosData[0]['Video']['status'] = 'not';
                        }
                        if ($VideosData[0]['Video']['status'] != 'avail')
                        {
                            ?>
                            <span class="download-now-button ">
                                <form method="Post" id="form<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                    <input type="hidden" name="ProdID" value="<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" />
                                    <input type="hidden" name="ProviderType" value="<?php echo $VideosData[0]["Video"]["provider_type"]; ?>" />
                                    <span class="beforeClick" id="download_video_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>">
                                        <![if !IE]>
                                        <a class="top-10-download-now-button no-ajaxy" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthers("<?php echo $VideosData[0]['Video']['ProdID']; ?>", "0", "<?php echo urlencode($finalVideoUrlArr[0]); ?>", "<?php echo urlencode($finalVideoUrlArr[1]); ?>", "<?php echo urlencode($finalVideoUrlArr[2]); ?>", "<?php echo $VideosData[0]['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                        <![endif]>
                                        <!--[if IE]>
                                                <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIE('<?php echo $VideosData[0]['Video']['ProdID']; ?>','0','<?php echo $VideosData[0]['Video']['provider_type']; ?>');" href="<?php echo trim($finalVideoUrl); ?>"><?php __('Download Now'); ?></a></label>
                                        <![endif]-->
                                    </span>
                                    <span class="afterClick" id="vdownloading_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
                                    <span id="vdownload_loader_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                </form>
                            </span>
                            <?php
                        }
                        else
                        {
                            ?>
                            <a class="download-now-button top-10-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></a>
                            <?php
                        }
                    }
                    ?>
                    <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                    <div class="wishlist-popover">

                        <?php
                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($VideosData[0]["Video"]["ProdID"]);
                        //echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$VideosData[0]["Video"]["ProdID"],$VideosData[0]["Video"]["provider_type"]);
                        //echo $this->Queue->getSocialNetworkinglinksMarkup();  
                        ?>                                                    
                    </div>
                    <?php
                }
                else
                {
                    ?>    
                    <span class="download-now-button top-10-download-now-button"><a  href='javascript:void(0);' title='<?php __('Coming Soon'); ?>  ( <?php
                        if (isset($VideosData[0]['Country']['SalesDate']))
                        {
                            echo date("F d Y", strtotime($VideosData[0]['Country']['SalesDate']));
                        }
                        ?> ) ' style="width:120px;cursor:pointer;"><?php __('Coming Soon'); ?></a></span>
                                                                                     <?php
                                                                                 }
                                                                             }
                                                                             else
                                                                             {
                                                                                 ?>
                <span class="download-now-button">
                    <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>
                </span>
                <?php
            }
            ?>


        </div>
        <div class="hero-detail">

            <h2 class="song-title">
                <?php
                echo wordwrap($VideosData[0]['Video']['VideoTitle'], 15, "<br />");
                ;
                ?>

            </h2><?php
            if ('T' == $VideosData[0]['Video']['Advisory'])
            {
                ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
            <h3 class="artist-name">
                <a title="<?php echo $this->getTextEncode($VideosData[0]['Video']['ArtistText']); ?>" href="/artists/album/<?php echo base64_encode($VideosData[0]['Video']['ArtistText']); ?>"><?php echo $VideosData[0]['Video']['ArtistText']; ?></a>
            </h3>
            <?php
            $duration = $VideosData[0]['Video']['FullLength_Duration'];
            $duration_arr = explode(":", $duration);
            ?>
            <div class="release-information">
                <p><?php echo __('Release Information', true); ?> </p>
                <div class="release-date">Date: <?php echo date("M d, Y", strtotime($VideosData[0]['Country']['SalesDate'])); ?></div>
                <div class="video-duration">Duration: <?php echo $duration_arr[0] . " min " . $duration_arr[1] . " sec"; ?></div>
              <!--  <div class="video-size">Size: 67.2 MB</div> -->
            </div>
        </div>

    </div>
    <section class="more-videos">
        <header>
            <h2><?php echo __('More Videos By', true); ?> <?php echo $VideosData[0]['Video']['ArtistText']; ?></h2>
        </header>
        <div class="more-videos-scrollable horiz-scroll">
            <ul style="width:30900px;">
                <?php
                if (!empty($MoreVideosData))
                {
                    foreach ($MoreVideosData as $key => $value)
                    {                       
                        //hide video if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        ?>								
                        <li>
                            <div class="video-thumb-container">
                                <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><img alt="" class="lazy" src="<?php echo $value['videoAlbumImage']; ?>" data-original="" width="274" height="162" /></a>
                                <!--				<a class="download-now-button" href="#">Download Now</a>-->
                                <?php
                                if ($this->Session->read('patron'))
                                {
                                    if (strtotime($value['Country']['SalesDate']) < time())
                                    {

                                        if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
                                            //$productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                           
//                                            $videoUrl = shell_exec('perl files/tokengen ' . $value['Full_Files']['CdnPath'] . "/" . $value['Full_Files']['SaveAsName']);
//                                            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
//                                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                            if ($downloadsUsed > 0)
                                            {
                                                $value['Video']['status'] = 'avail';
                                            }
                                            else
                                            {
                                                $value['Video']['status'] = 'not';
                                            }
                                            if ($value['Video']['status'] != 'avail')
                                            {
                                                ?>                                               
                                                <span class="download-now-button ">
                                                    <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a class="no-ajaxy top-10-download-now-button" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $value['Full_Files']['CdnPath']; ?>", "<?php echo $value['Full_Files']['SaveAsName']; ?>",  "<?php echo $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $value['Full_Files']['CdnPath']; ?>', '<?php echo $value['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a></label>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
                                                        <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </span>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="download-now-button top-10-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></a>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                                        <div class="wishlist-popover">

                                            <?php
                                            $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']['ProdID']);
                                            echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']['ProdID'], $value['Video']["provider_type"]);
                                            //echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                            ?> 

                                        </div>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <span class="download-now-button top-10-download-now-button"><a  href='javascript:void(0);' style="width:120px;cursor:pointer;" title='<?php __('Coming Soon'); ?>  ( <?php
                                            if (isset($value['Country']['SalesDate']))
                                            {
                                                echo date("F d Y", strtotime($value['Country']['SalesDate']));
                                            }
                                            ?> ) '><?php __('Coming Soon'); ?></a></span>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                    <span class="download-now-button">
                                        <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>
                                    </span>
                                    <?php
                                }
                                ?>

                            </div>
                            <div class="song-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <?php
                                    if (strlen($value['Video']['VideoTitle']) >= 20)
                                    {
                                        $VideoTitle = $this->getTextEncode(substr($value['Video']['VideoTitle'], 0, 20)) . "..";
                                    }
                                    else
                                    {
                                        $VideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
                                    }
                                    echo $VideoTitle;
                                    ?>
                                </a><?php
                                if ('T' == $value['Video']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">	
                                <?php $artistText = $VideosData[0]['Video']['ArtistText']; ?>
                                <a title="<?php echo $this->getValidText($this->getTextEncode($artistText)); ?>" href="/artists/album/<?php echo base64_encode($artistText); ?>">
                                    <?php
                                    if (strlen($value['Video']['ArtistText']) >= 35)
                                    {
                                        $VideoArtist = $this->getTextEncode(substr($artistText, 0, 35)) . "..";
                                    }
                                    else
                                    {
                                        $VideoArtist = $this->getTextEncode($artistText);
                                    }
                                    echo $VideoArtist;
                                    ?>
                                </a>
                            </div>
                        </li>


                        <?php
                    }
                }
                else
                {
                    echo 'Sorry,there are no more videos.';
                }
                ?>

            </ul>
        </div>
    </section>

    <section class="top-videos">
        <header>
            <h2>Top <span><?php echo $VideoGenre; ?></span> Videos</h2>
        </header>
        <div class="top-videos-scrollable horiz-scroll">
            <ul>
                <?php
                foreach ($TopVideoGenreData as $key => $value)
                {


                    //hide video if library block the explicit content
                    if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                    {
                        continue;
                    }
                    ?>

                    <li>
                        <div class="video-thumb-container">
                            <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><img alt="" class="lazy" src="<?php echo $value['videoImage']; ?>" width="274" height="162" /></a>
                            <!--				<a class="download-now-button" href="#">Download Now</a>-->
                            <?php
                            if ($this->Session->read('patron'))
                            {

                                if ($libraryDownload == '1' && $patronDownload == '1')
                                {
                                    $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
//                                    $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
//                                    $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
//                                    $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                    if ($downloadsUsed > 0)
                                    {
                                        $value['Video']['status'] = 'avail';
                                    }
                                    else
                                    {
                                        $value['Video']['status'] = 'not';
                                    }
                                    if ($value['Video']['status'] != 'avail')
                                    {
                                        ?>
                                        <span class="download-now-button ">
                                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                    <![if !IE]>
                                                    <a class="no-ajaxy top-10-download-now-button" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                    <![endif]>
                                                    <!--[if IE]>
                                                            <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a></label>
                                                    <![endif]-->
                                                </span>
                                                <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
                                                <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                            </form>
                                        </span>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="download-now-button " href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></a>
                                        <?php
                                    }
                                }
                                ?>
                                <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                                <div class="wishlist-popover">
                                    <?php
                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']['ProdID']);
                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']['ProdID'], $value['Video']["provider_type"]);
                                    //echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                    ?>

                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <span class="download-now-button">
                                    <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>
                                </span>
                                <?php
                            }
                            ?>

                        </div>
                        <div class="song-title">
                            <a title="<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><?php
                                if (strlen($value['Video']['VideoTitle']) >= 20)
                                {
                                    $VideoTitle = $this->getTextEncode(substr($value['Video']['VideoTitle'], 0, 20)) . "..";
                                }
                                else
                                {
                                    $VideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
                                }
                                echo $VideoTitle;
                                ?>
                            </a><?php
                            if ('T' == $value['Video']['Advisory'])
                            {
                                ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                        </div>
                        <div class="artist-name">
                            <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['ArtistText'])); ?>" href="/artists/album/<?php echo base64_encode($VideosData['Video']['ArtistText']); ?>">
                                <?php
                                if (strlen($value['Video']['ArtistText']) >= 35)
                                {
                                    $VideoArtist = $this->getTextEncode(substr($value['Video']['ArtistText'], 0, 35)) . "..";
                                }
                                else
                                {
                                    $VideoArtist = $this->getTextEncode($value['Video']['ArtistText']);
                                }
                                echo $VideoArtist;
                                ?>
                            </a>
                        </div>
                    </li>


                    <?php
                }
                ?>

            </ul>
        </div>


    </section>		

</section>
