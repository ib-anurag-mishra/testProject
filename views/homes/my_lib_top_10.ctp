<section class="my-top-100-page">

    <div class="breadcrumbs">
        <?php
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $html->addCrumb(__('My Library Top 10', true), '/homes/my_lib_top_10');
        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        ?>
    </div>
    <header class="clearfix">
        <h2> <?php echo __('My Library Top 10', true); ?>  </h2>

    </header>
    <h3>Albums</h3>
    <div class="album-shadow-container">
        <div class="album-scrollable horiz-scroll">
            <ul style="width:2700px;">
                <?php
                //$this->log("My Lib Top 10 -- Album START", "siteSpeed");
                $count = 1;
                if (count($topDownload_albums) > 0)
                {
                    foreach ($topDownload_albums as $key => $value)
                    {

                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
                        {
                            continue;
                        }

                        // $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                        // $album_img =  Configure::read('App.Music_Path').$album_img;                                            					
                        ?>					
                        <li>
                            <div class="album-container">
                                    <!-- <a href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['ProdID']); ?>">
                                    <img class="lazy" src="<?php echo $album_img; ?>" alt="pitbull162x162" width="250" height="250" />
                                    </a> -->

                                <?php
                                echo $html->link($html->image($value['album_img'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view',
                                    base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))
                                ?>
                                <div class="top-10-ranking"><?php echo $count; ?></div>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
                                        ?> 
                                        <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                        <div class="wishlist-popover">
                                            <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album"/>
                                            <?php
                                            // echo $this->Queue->getQueuesListAlbums($this->Session->read('patron'), $value['albumSongs'][$value['Albums']['ProdID']], $value['Albums']['ProdID'], $value['Albums']['provider_type']);
                                            ?>
                                            <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                            <?php //echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>


                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="top-10-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="album-title">							
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <?php
                                    //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                    if (strlen($value['Albums']['AlbumTitle']) > 20)
                                        echo substr($value['Albums']['AlbumTitle'], 0, 20) . "...";
                                    else
                                        echo $value['Albums']['AlbumTitle'];
                                    ?>
                                </a><?php
                                if ('T' == $value['Albums']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">							
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
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

                    echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
                }
                //$this->log("My Lib Top 10 -- Album END", "siteSpeed");
                ?>
            </ul>
        </div>
    </div>
    <h3>Songs</h3>
    <div class="songs-shadow-container">
        <div class="songs-scrollable horiz-scroll">
            <ul style="width:2700px;">
                <?php
                //$this->log("My Lib Top 10 -- Songs START", "siteSpeed");
                $count = 1;
                if (count($top_10_songs) > 0)
                {
                    //for($d=1;$d<$count;$d++) {
                    foreach ($top_10_songs as $key => $value)
                    {



                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T'))
                        {
                            continue;
                        }

                        if ($count > 10)
                            break;

                        //$songs_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                        //$songs_img =  Configure::read('App.Music_Path').$songs_img; 
                        //echo "<pre>"; print_r($value); 
                        ?>
                        <li>

                            <div class="song-container">
                                <a href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <img class="lazy"  src="<?php echo $value['songs_img']; ?>" alt="<?php echo $this->getValidText($value['Song']['Artist'] . ' - ' . $value['Song']['SongTitle']); ?>" width="250" height="250" />                                                        
                                </a>
                                <div class="top-10-ranking"><?php echo $count; ?></div>

                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 
                                    <!-- <a href="#" class="preview"></a>  -->
                                    <?php
                                    if ($this->Session->read('library_type') == 2 && $value['Country']['StreamingSalesDate'] <= date('Y-m-d') && $value['Country']['StreamingStatus'] == 1)
                                    {
                                        if ('T' == $value['Song']['Advisory'])
                                        {
                                            $song_title = $value['Song']['SongTitle'] . '(Explicit)';
                                        }
                                        else
                                        {
                                            $song_title = $value['Song']['SongTitle'];
                                        }

                                        echo $this->Queue->getStreamNowLabel($value['streamUrl'], $song_title, $value['Song']['ArtistText'], $value['totalseconds'], $value['Song']['ProdID'], $value['Song']['provider_type']);
                                        //echo $html->image('/img/news/top-100/preview-off.png', array( "class" => "preview",  "style" => "cursor:pointer;display:block;border: 0px solid;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.$song_title.'","'.$value['Song']['ArtistText'].'",'.$value['totalseconds'].',"'.$value['Song']['ProdID'].'","'.$value['Song']['provider_type'].'");')); 
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
                                            $productInfo = $song->getDownloadData($value['Song']['ProdID'], $value['Song']['provider_type']);
//                                            $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
//                                            $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
//                                            $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl) / 3));
                                                                                        
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

                                                <form method="Post" id="form<?php echo $value["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                    <input type="hidden" name="ProdID" value="<?php echo $value["Song"]["ProdID"]; ?>" />
                                                    <input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" />
                                                    <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Song"]["ProdID"]; ?>">
                                                        <![if !IE]>
                                                        <a href='javascript:void(0);' class="add-to-wishlist no-ajaxy top-10-download-now-button" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" onclick='return wishlistDownloadOthers("<?php echo $value["Song"]['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>",  "<?php echo $value["Song"]["provider_type"]; ?>");'><?php __('Download Now'); ?></a>
                                                        <![endif]>
                                                        <!--[if IE]>
                                                               <a class="no-ajaxy top-10-download-now-button" 
                                                                title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                onclick='wishlistDownloadIE("<?php echo $value["Song"]['ProdID']; ?>", "0" , "<?php echo $value["Song"]["provider_type"]; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' 
                                                                href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                        <![endif]-->
                                                    </span>
                                                    <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait.."); ?>
                                                            <span id="wishlist_loader_<?php echo $value["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php echo $html->image('ajax-loader_black.gif'); ?></span> </a> </span>
                                                </form>

                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-10-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                                <?php
                                            }
                                        }
                                        else
                                        {

                                            if ($libraryDownload != '1')
                                            {
                                                $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                                                $wishlistCount = $wishlist->getWishlistCount();
                                                if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount)
                                                {
                                                    ?> 
                                                    <a class="top-10-download-now-button " href="javascript:void(0);"><?php __("Limit Met"); ?></a>
                                                    <?php
                                                }
                                                else
                                                {
                                                    $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

                                                    echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-10-download-now-button " href="javascript:void(0);"><?php __("Limit Met"); ?></a>
                                                <?php
                                            }
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="top-10-download-now-button " href="javascript:void(0);"><span title='<?php __("Coming Soon"); ?> ( <?php
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
                                    <a class="top-10-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a>


                                    <?php
                                }
                                ?>


                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>

                                    <div class="wishlist-popover">
                                        <input type="hidden" id="<?= $value['Song']['ProdID'] ?>" value="song"/>
                                        <?php
                                        if ($this->Session->read('library_type') == 2 && $value['Country']['StreamingSalesDate'] <= date('Y-m-d') && $value['Country']['StreamingStatus'] == 1)
                                        {
                                            // echo $this->Queue->getQueuesList($this->Session->read('patron'), $value["Song"]["ProdID"], $value["Song"]["provider_type"], $value["Albums"]["ProdID"], $value["Albums"]["provider_type"]);
                                            ?>
                                            <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                        <?php } ?>



                                        <?php
                                        $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

                                        echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
                                        //echo $this->Queue->getSocialNetworkinglinksMarkup();
                                        ?>
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="album-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['SongTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <?php
                                    //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                    if (strlen($value['Song']['SongTitle']) > 20)
                                        echo substr($value['Song']['SongTitle'], 0, 20) . "...";
                                    else
                                        echo $value['Song']['SongTitle'];
                                    ?>
                                </a><?php
                                if ('T' == $value['Song']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
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

                    echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
                }
                //$this->log("My Lib Top 10 -- Songs END", "siteSpeed");
                ?>


            </ul>
        </div>

    </div>
    <h3>Videos</h3>
    <div class="videos-shadow-container">
        <div class="videos-scrollable horiz-scroll">
            <ul style="width:4430px;">
                <?php
                //$this->log("My Lib Top 10 -- Video START", "siteSpeed");
                $count = 1;

                if (count($topDownload_videos_data) > 0)
                {

                    foreach ($topDownload_videos_data as $key => $value)
                    {

                        //hide song if library block the explicit content
                        if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        ?>
                        <li>

                            <div class="video-container">
                                <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
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
                                            $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName']);
                                            $finalVideoUrl = Configure::read('App.Music_Path') . $videoUrl;
                                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl) / 3));
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
                                                <span class="top-100-download-now-button">
                                                    <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a class="no-ajaxy" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthers("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo urlencode($finalVideoUrlArr[0]); ?>", "<?php echo urlencode($finalVideoUrlArr[1]); ?>", "<?php echo urlencode($finalVideoUrlArr[2]); ?>", "<?php echo $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIE('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>');" href="<?php echo trim($finalVideoUrl); ?>"><?php __('Download Now'); ?></a></label>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...  '); ?></span>
                                                        <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </span>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-10-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                                <?php
                                            }
                                        }
                                        else
                                        {

                                            if ($libraryDownload != '1')
                                            {
                                                $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                                                $wishlistCount = $wishlist->getWishlistCount();
                                                if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount)
                                                {
                                                    ?> 
                                                    <a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>
                                                    <?php
                                                }
                                                else
                                                {
                                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>
                                                <?php
                                            }
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="top-10-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon"); ?> ( <?php
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
                                    <a class="top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>


                                    <?php
                                }
                                ?>


                                <!-- <a class="top-10-download-now-button" href="#">Download Now</a> -->
                                <?php
                                if ($this->Session->read('patron'))
                                {
                                    ?>
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                                    <div class="wishlist-popover">

                                        <?php
                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                        //echo $this->Queue->getSocialNetworkinglinksMarkup();
                                        ?>

                                    </div>
                                <?php } ?>
                            </div>
                            <div class="album-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <?php
                                    //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                    if (strlen($value['Video']['VideoTitle']) > 20)
                                        echo substr($value['Video']['VideoTitle'], 0, 20) . "...";
                                    else
                                        echo $value['Video']['VideoTitle'];
                                    ?>
                                </a><?php
                                if ('T' == $value['Video']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Video']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
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

                    echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
                }
                 //$this->log("My Lib Top 10 -- Video END", "siteSpeed");
                
                ?>


            </ul>
        </div>

    </div>
</section>