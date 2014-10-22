<section class="my-top-100-page">

    <div class="breadcrumbs">
        <?php
        $html->addCrumb(__($this->Session->read('territory') . ' Top 10', true), '/homes/us_top_10');
        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        $find = array('\'', '"');
        $replace = array('', '');
        ?>
    </div>
    <header class="clearfix">
        <h2><?php echo __($this->Session->read('territory') . ' Top 10', true); ?></h2>

    </header>
    <h3><?php __('Albums'); ?></h3>
    <div class="album-shadow-container">
        <div class="album-scrollable horiz-scroll carousel">
            <ul style="width:1650px;">
                <?php
                $libId = $this->Session->read('library');
                $patId = $this->Session->read('patron');
                $count = 1;
                if (count($ustop10Albums) > 0)
                {
                    foreach ($ustop10Albums as $key => $value)
                    {

                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        $trackingAlbumTitle = $count . '-' . str_replace($find, $replace, $this->getTextEncode($value['Albums']['AlbumTitle']));
                        ?>					
                        <li>
                            <div class="album-container">
                                <a onclick="ga('send', 'event', 'Country Top Albums', 'Artwork Click', '<?php echo $trackingAlbumTitle; ?>')" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">                                                        
                                    <img src="<?php echo $value['album_img']; ?>" alt="<?php echo $this->getValidText($value['Song']['Artist'] . ' - ' . $value['Song']['SongTitle']); ?>" width="250" height="250" />
                                </a>
                                <div class="top-10-ranking"><?php echo $count; ?></div>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                ?>
                                <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album" data-provider="<?= $value["Albums"]["provider_type"] ?>" />
                                <?php
                                    if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']], 0, $trackingAlbumTitle, 'Country Top Albums', $value['Albums']['ProdID']);
                                        ?> 
                                        <a onclick="ga('send', 'event', 'Country Top Albums', 'Toggle Playlists', '<?php echo $trackingAlbumTitle; ?>')" class="playlist-menu-icon no-ajaxy toggleable" href="javascript:void(0)" ></a>
                                        <ul>
                                            <li><a href="#" class="create-new-playlist">Create New Playlist...</a></li>

                                        </ul>
                                        <a onclick="ga('send', 'event', 'Country Top Albums', 'Add to Wishlist', '<?php echo $trackingAlbumTitle; ?>')" class="wishlist-icon toggleable no-ajaxy" href="#" title="Add to Wishlist"></a>                                         
                                       
                                        <?php
                                    }
                                    ?>

                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <?php /*<a class="top-10-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> */ ?>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="album-title">
                                <a onclick="ga('send', 'event', 'Country Top Albums', 'Title Click', '<?php echo $trackingAlbumTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])); ?>" 
                                   href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                       <?php
                                       if (strlen($value['Albums']['AlbumTitle']) > 20)
                                           echo substr($value['Albums']['AlbumTitle'], 0, 20) . "...";
                                       else
                                           echo $value['Albums']['AlbumTitle'];
                                       ?>
                                </a><?php
                                if ('T' == $value['Albums']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (<?php __('Explicit'); ?>)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a onclick="ga('send', 'event', 'Country Top Albums', 'Artist Click', '<?php echo $trackingAlbumTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
                                    <?php
                                    if (strlen($value['Song']['Artist']) > 32)
                                        echo substr($value['Song']['Artist'], 0, 32) . "...";
                                    else
                                        echo $value['Song']['Artist'];
                                    ?>
                                </a>
                            </div>
                        </li>
                        <?php
                        $count++;
                    }
                }else
                {

                    echo '<span style="font-size:14px;">' . __('Sorry, there are no downloads.', true) . '<span>';
                }
                ?>
            </ul>
        </div>
        <button class="left-scroll-button" type="button"></button>
        <button class="right-scroll-button" type="button"></button>
    </div>
    <h3><?php __('Songs'); ?></h3>
    <div class="songs-shadow-container">
        <div class="songs-scrollable horiz-scroll carousel">
            <ul tyle="width:1650px;">
                <?php

                $count = 1;
                if (count($nationalTopDownload) > 0)
                {
                    foreach ($nationalTopDownload as $key => $value)
                    {

                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        $trackingSongTitle = $count . '-' . str_replace($find, $replace, $this->getTextEncode($value['Song']['SongTitle']));
                        if ($count > 10)
                            break;

                        ?>
                        <li>

                            <div class="song-container">
                                <?php
                                if ($this->Session->read("patron")) {
                                    ?>                                 
                                <input type="hidden" id="<?php echo $value["Song"]["ProdID"]; ?>" value="song" data-provider="<?php echo $value["Song"]["provider_type"]; ?>" />
                              <?php } ?>
                                <a onclick="ga('send', 'event', 'Country Top Songs', 'Artwork Click', '<?php echo $trackingSongTitle; ?>')" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">                                                        
                                    <img src="<?php echo $value['songs_img']; ?>" alt="<?php echo $this->getValidText($value['Song']['Artist'] . ' - ' . $value['Song']['SongTitle']); ?>" width="250" height="250" />
                                </a>
                                <div class="top-10-ranking"><?php echo $count; ?></div>


                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 
                                    <?php
                                    if ($this->Session->read('library_type') == 2 && $value['Country']['StreamingSalesDate'] <= date('Y-m-d') && $value['Country']['StreamingStatus'] == 1)
                                    {
                                        if ('T' == $value['Song']['Advisory'])
                                        {
                                            $song_title = $value['Song']['SongTitle'] . '(' . __('Explicit', true) . ')';
                                        }
                                        else
                                        {
                                            $song_title = $value['Song']['SongTitle'];
                                        }
                                        echo $this->Queue->getStreamNowLabel($value['streamUrl'], $song_title, $value['Song']['ArtistText'], $value['totalseconds'], $value['Song']['ProdID'], $value['Song']['provider_type'], 'Country Top Songs', $trackingSongTitle);
 
                                    }
                                    else if ($value['Country']['SalesDate'] <= date('Y-m-d'))
                                    {
                                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;border: 0px solid;", "id" => "play_audio" . $key, "onClick" => 'playSample(this, "' . $key . '", ' . $value['Song']['ProdID'] . ', "' . base64_encode($value['Song']['provider_type']) . '", "' . $this->webroot . '");'));
                                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "load_audio" . $key));
                                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "stop_audio" . $key, "onClick" => 'stopThis(this, "' . $key . '");'));
                                    }
                                }
                                ?>
                                <?php
                                if ($this->Session->read('patron'))
                                {
                                    if ($value['Country']['SalesDate'] <= date('Y-m-d'))
                                    {

                                        if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
                                            
                                            if($this->Session->read('downloadVariArray'))
                                            {
                                                $downloadsUsed = $this->Download->getDownloadResults($value['Song']['ProdID'], $value['Song']['provider_type']);
                                            } 
                                            else
                                            {
                                                $downloadsUsed = $this->Download->getDownloadfind($value['Song']['ProdID'], $value['Song']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                            }
                                            
                                            
                                            if ($downloadsUsed > 0)
                                            {
                                                $value['Song']['status'] = 'avail';
                                            }
                                            else
                                            {
                                                $value['Song']['status'] = 'not';
                                            }
                                            if (isset($value['Song']['status']) && ($value['Song']['status'] != 'avail'))
                                            {
                                                ?>
                                                <span class="top-10-download-now-button">
                                                    <form method="Post" id="form<?php echo $value["Song"]["ProdID"]; ?>" action="/homes/wishlistDownloadHome" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Song"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Song"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a href='javascript:void(0);' class="add-to-wishlist no-ajaxy" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" onclick='ga("send", "event", "Country Top Songs", "Download", "<?php echo $trackingSongTitle; ?>", 1); return wishlistDownloadOthersHome("<?php echo $value["Song"]['ProdID']; ?>", "0", "<?php echo $value["Song"]['CdnPath']; ?>", "<?php echo $value["Song"]['FullLength_SaveAsName']; ?>", "<?php echo $value["Song"]["provider_type"]; ?>");'><?php __('Download Now'); ?></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                   <a class="no-ajaxy add-to-wishlist" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='ga("send", "event", "Country Top Songs", "Download", "<?php echo $trackingSongTitle; ?>", 1); wishlistDownloadIEHome("<?php echo $value["Song"]['ProdID']; ?>", "0" , "<?php echo $value["Song"]["provider_type"]; ?>", "<?php echo $value["Song"]['CdnPath']; ?>", "<?php echo $value["Song"]['FullLength_SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait"); ?>...
                                                                <span id="wishlist_loader_<?php echo $value["Song"]["ProdID"]; ?>" style="display: block; position: absolute; left: 0; top: 0; width:28px; height: 19px; background: rgba(0,0,0,.1);"><?php echo $html->image('ajax-loader_black.gif',array('alt' => 'ajax loader gif', 'style' => 'width: 16px; height:16px; margin: 2px auto;')); ?></span> </a> </span>
                                                    </form>
                                                </span>    

                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-10-download-now-button song-downloaded" href='/homes/my_history'><label style="width:120px;cursor:pointer;" title="<?php __('You have already downloaded this song. Get it from your recent downloads'); ?>"><?php __('Downloaded'); ?></label></a>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <a class="top-100-download-now-button download-limit-met" href="javascript:void(0);"><?php __("Limit Met"); ?></a>  
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="top-100-download-now-button " href="javascript:void(0);"><span title='<?php __("Coming Soon"); ?> ( <?php
                                            if (isset($value['Country']['SalesDate']))
                                            {
                                                echo date("F d Y", strtotime($value['Country']['SalesDate']));
                                            }
                                            ?> )'><?php __("Coming Soon"); ?></span></a>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                    <?php /*<a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> */ ?>


                                    <?php
                                }
                                ?>


                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?>
                                  
                                    <a onclick="ga('send', 'event', 'Country Top Songs', 'Toggle Playlist', '<?php echo $trackingSongTitle; ?>')" class="playlist-menu-icon no-ajaxy toggleable" href="javascript:void(0)" ></a>
                                    <ul>
                                        <li><a href="#" class="create-new-playlist"><?php __('Create New Playlist'); ?>...</a></li>

                                    </ul>
                                   
                                    <a onclick="ga('send', 'event', 'Country Top Songs', 'Add to Wishlist', '<?php echo $trackingSongTitle; ?>')" class="wishlist-icon toggleable no-ajaxy" href="#" title="Add to Wishlist"></a>
                                <?php } ?>

                            </div>
                            <div class="album-title">
                                <a onclick="ga('send', 'event', 'Country Top Songs', 'Title Click', '<?php echo $trackingSongTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['SongTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <?php
                                    if (strlen($value['Song']['SongTitle']) > 20)
                                        echo substr($value['Song']['SongTitle'], 0, 20) . "...";
                                    else
                                        echo $value['Song']['SongTitle'];
                                    ?>
                                </a><?php
                                if ('T' == $value['Song']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (<?php __('Explicit'); ?>)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a onclick="ga('send', 'event', 'Country Top Songs', 'Artist Click', '<?php echo $trackingSongTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
                                    <?php
                                    if (strlen($value['Song']['Artist']) > 32)
                                        echo substr($value['Song']['Artist'], 0, 32) . "...";
                                    else
                                        echo $value['Song']['Artist'];
                                    ?>
                                </a>
                            </div>
                        </li>

                        <?php
                        $count++;
                    }
                }else
                {

                    echo '<span style="font-size:14px;">' . __('Sorry, there are no downloads.', true) . '<span>';
                }
                ?>


            </ul>
        </div>
        <button class="left-scroll-button" type="button"></button>
        <button class="right-scroll-button" type="button"></button>        

    </div>
    <h3><?php __('Videos'); ?></h3>
    <div class="videos-shadow-container">
        <div class="videos-scrollable horiz-scroll carousel">
            <ul>
                <?php
                $count = 1;
                if (!empty($usTop10VideoDownload) > 0)
                {
                    foreach ($usTop10VideoDownload as $key => $value)
                    {


                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        $trackingVideoTitle = $count . '-' . str_replace($find, $replace, $this->getTextEncode($value['Video']['VideoTitle']));
                        ?>
                        <li>

                            <div class="video-container">
                                <a onclick="ga('send', 'event', 'Country Top Videos', 'Artwork Click', '<?php echo $trackingVideoTitle; ?>')" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">                                                        
                                    <img src="<?php echo $value['videoAlbumImage']; ?>" alt="<?php echo $this->getValidText($value['Video']['Artist'] . ' - ' . $value['Video']['VideoTitle']); ?>" width="423" height="250" />
                                </a>                                                  
                                <div class="top-10-ranking"><?php echo $count; ?></div>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 														
                                    <a href="javascript:void(0)" class="preview"></a>
                                <?php } ?>



                                <?php
                                if ($this->Session->read('patron'))
                                {
                                    if ($value['Country']['SalesDate'] <= date('Y-m-d'))
                                    {

                                        if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
                                            $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);

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
                                                <span class="mylib-top-10-video-download-now-button">
                                                    <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a class="no-ajaxy top-10-download-now-button" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='ga("send", "event", "Country Top Videos", "Video Download", "<?php echo $trackingVideoTitle; ?>"); return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $value['Video']['CdnPath']; ?>", "<?php echo $value['Video']['FullLength_SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>");'><?php __('Download Now'); ?></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <a class="no-ajaxy top-10-download-now-button" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="ga('send', 'event', 'Country Top Videos', 'Video Download', '<?php echo $trackingVideoTitle; ?>''); wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $value['Video']['CdnPath']; ?>', '<?php echo $value['Video']['FullLength_SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait') . '...'; ?></span>
                                                        <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-23px; margin-right:2px; width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </span>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <?php
                                                /*
                                                <a class="mylib-top-10-video-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                                */
                                                ?>
                                                <a class="mylib-top-10-video-download-now-button dload" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></a>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <a class="mylib-top-10-video-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="mylib-top-10-video-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon"); ?> ( <?php
                                            if (isset($value['Country']['SalesDate']))
                                            {
                                                echo date("F d Y", strtotime($value['Country']['SalesDate']));
                                            }
                                            ?> )'><?php __("Coming Soon"); ?></span></a>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                    <?php /*<a class="mylib-top-10-video-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a> */ ?>


                                    <?php
                                }
                                ?>


                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 

                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>

                                    <div class="wishlist-popover">
                                        <?php
                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"], 1, 'Country Top Videos', $trackingVideoTitle);
                                        ?>
                                    </div>
                                <?php } ?>



                            </div>
                            <div class="album-title">
                                <a onclick="ga('send', 'event', 'Country Top Videos', 'Title Click', '<?php echo $trackingVideoTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <?php

                                    if (strlen($value['Video']['VideoTitle']) > 20)
                                        echo substr($value['Video']['VideoTitle'], 0, 20) . "...";
                                    else
                                        echo $value['Video']['VideoTitle'];
                                    ?>
                                </a><?php
                                if ('T' == $value['Video']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (<?php __('Explicit'); ?>)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a onclick="ga('send', 'event', 'Country Top Videos', 'Artist Click', '<?php echo $trackingVideoTitle; ?>')" title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Video']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
                                    <?php
                                    if (strlen($value['Video']['Artist']) > 32)
                                        echo substr($value['Video']['Artist'], 0, 32) . "...";
                                    else
                                        echo $value['Video']['Artist'];
                                    ?>
                                </a>
                            </div>
                        </li>

                        <?php
                        $count++;
                    }
                }else
                {

                    echo '<span style="font-size:14px;">' . __('Sorry, there are no downloads.', true) . '<span>';
                }
                ?>


            </ul>
        </div>
        <button class="left-scroll-button" type="button"></button>
        <button class="right-scroll-button" type="button"></button>        

    </div>
</section>