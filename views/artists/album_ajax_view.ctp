<?php
foreach ($albumData as $album_key => $album):
    ?>
    <section class="album-detail">
        <div class="album-cover-image" id="genres_stream_now">
            <?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']); ?>
            <img src="<?php echo Configure::read('App.Music_Path') . $albumArtwork; ?>" alt="album-detail-cover" width="250" height="250" />
            <?php
            if ($this->Session->read('library_type') == 2 && !empty($album['albumSongs'][$album['Album']['ProdID']]) && $this->Session->read("patron"))
            {
                echo $this->Queue->getAlbumStreamNowLabel($album['albumSongs'][$album['Album']['ProdID']]);
                ?>
                <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>

                <div class="wishlist-popover">
                    <input type="hidden" id="<?= $album['Album']['ProdID'] ?>" value="album"/>
                    <?php
                    //echo $this->Queue->getQueuesListAlbums($this->Session->read('patron'),$album['albumSongs'][$album['Album']['ProdID']],$album['Album']['ProdID'],$album['Album']['provider_type']);
                    ?>
                    <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>

                    <?php //echo $this->Queue->getSocialNetworkinglinksMarkup();  ?>
                </div>
                <?php
            }
            ?>
            <?php
            $image = Configure::read('App.Music_Path') . $albumArtwork;
            if ($page->isImage($image))
            {
                //Image is a correct one
            }
            else
            {

                //	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
            }
            ?>

        </div>
        <div class="album-title"><?php
            if (strlen($album['Album']['AlbumTitle']) >= 50)
            {
                $album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50) . '...';
            }
            ?>
            <?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>
        </div>                      




        <div class="album-genre"><?php echo __('Genre') . ": "; ?><span><?php
                echo $this->getTextEncode($album['Genre']['Genre']);
                if ($album['Album']['Advisory'] == 'T')
                {
                    echo '<br />';
                    echo '<font class="explicit"> (Explicit)</font>';
                }
                ?></span></div>
        <div class="album-label"><?php echo __('Label') . ": "; ?><span><?php
                if ($album['Album']['Label'] != '')
                {
                    echo $this->getTextEncode($album['Album']['Label']);
                }
                ?></span></div>


    </section>




    <section class="tracklist-container">		

        <div class="tracklist-header"><span class="song">Song</span><span class="artist">Artist</span><span class="time">Time</span></div>

        <?php
        $i = 1;

        foreach ($albumSongs[$album['Album']['ProdID']] as $key => $albumSong):

            //hide song if library block the explicit content
            if (($this->Session->read('block') == 'yes') && ($albumSong['Song']['Advisory'] == 'T'))
            {
                continue;
            }
            ?>

            <?php
            if ($this->Session->read('library_type') == 2)
            {
                $filePath = shell_exec('perl files/tokengen_streaming ' . $albumSong['Full_Files']['CdnPath'] . "/" . $albumSong['Full_Files']['SaveAsName']);

                if (!empty($filePath))
                {
                    $songPath = explode(':', $filePath);
                    $streamUrl = trim($songPath[1]);
                    $albumSong['streamUrl'] = $streamUrl;
                    $albumSong['totalseconds'] = $this->Queue->getSeconds($albumSong['Song']['FullLength_Duration']);
                }
            }
            ?>

            <div class="tracklist">

                <?php
                //check the song streaming status
                $streamingFlag = 0;
                if ($this->Session->read('library_type') == 2 && $albumSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $albumSong['Country']['StreamingStatus'] == 1)
                {
                    $streamingFlag = 1;
                }
                $downloadFlag = 0;
                if ($this->Session->read('library_type') == 2 && $albumSong['Country']['SalesDate'] <= date('Y-m-d') && $albumSong['Country']['DownloadStatus'] == 1)
                {
                    $downloadFlag = 1;
                }

                if ($this->Session->read("patron"))
                {

                    if ('T' == $albumSong['Song']['Advisory'])
                    {
                        $song_title = $albumSong['Song']['SongTitle'] . '(Explicit)';
                    }
                    else
                    {
                        $song_title = $albumSong['Song']['SongTitle'];
                    }


                    if ($this->Session->read('library_type') == 2 && $albumSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $albumSong['Country']['StreamingStatus'] == 1)
                    {
                        echo $html->image('play.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'loadSong("' . $albumSong['streamUrl'] . '", "' . base64_encode($song_title) . '","' . base64_encode($albumSong['Song']['ArtistText']) . '",' . ($albumSong['totalseconds']) . ',"' . $albumSong['Song']['ProdID'] . '","' . $albumSong['Song']['provider_type'] . '");'));
                    }
                    else if ($albumSong['Country']['SalesDate'] <= date('Y-m-d'))
                    {
                        echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'playSample(this, "' . $album_key . $key . '", ' . $albumSong["Song"]["ProdID"] . ', "' . base64_encode($albumSong["Song"]["provider_type"]) . '", "' . $this->webroot . '");'));
                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $album_key . $key));
                        echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $album_key . $key, "onClick" => 'stopThis(this, "' . $album_key . $key . '");'));
                    }
                }
                ?>



                <div class="song" style="width:200px;"><?php
                    if (strlen($albumSong['Song']['SongTitle']) >= 20)
                    {
                        echo '<span title="' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '">' . $this->getTextEncode(substr($albumSong['Song']['SongTitle'], 0, 20)) . '...</span>';
                    }
                    else
                    {
                        echo '<p style="float:left;"><span title="' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '">' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '</span></p>';
                    }
                    if ($albumSong['Song']['Advisory'] == 'T')
                    {
                        echo '<span class="explicit"> (Explicit)</span>';
                    }
                    ?></div>
                <div class="artist"><a title="<?php echo $this->getTextEncode($albumSong['Song']['Artist']); ?>"href="/artists/album/<?php echo base64_encode($albumSong['Song']['Artist']); ?>"><?php
                        if (strlen($albumSong['Song']['Artist']) >= 11)
                        {
                            if (strlen($albumSong['Song']['Artist']) >= 30)
                            {

                                $albumSong['Song']['Artist'] = substr($albumSong['Song']['Artist'], 0, 30) . '...';
                            }
                            echo $this->getTextEncode(substr($albumSong['Song']['Artist'], 0, 13));
                        }
                        else
                        {
                            echo $this->getTextEncode($albumSong['Song']['Artist']);
                        }
                        ?></a></div>
                <div class="time"><?php echo $this->Song->getSongDurationTime($albumSong['Song']['FullLength_Duration']); ?></div>
                <?php
                if ($this->Session->read('patron'))
                {
                    ?>

                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0);"></a>
                    <div class="wishlist-popover">        
                        <input type="hidden" id="<?= $albumSong["Song"]["ProdID"] ?>" value="song"/>

                        <?php
                        if (($albumSong['Country']['SalesDate'] <= date('Y-m-d') ) && ($albumSong['Country']['DownloadStatus'] == 1))
                        {
                            $productInfo = $song->getDownloadData($albumSong["Song"]['ProdID'], $albumSong["Song"]['provider_type']);
                            if ($libraryDownload == '1' && $patronDownload == '1')
                            {
//                                                                                            $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
//                                                                                            $finalSongUrl = Configure::read('App.Music_Path').$songUrl;
//                                                                                            $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
                                if ($albumSong['Song']['status'] != 'avail')
                                {
                                    ?>
                                    <form method="Post" id="form<?php echo $albumSong["Song"]["ProdID"]; ?>" action="/homes/userDownload">
                                        <input type="hidden" name="ProdID" value="<?php echo $albumSong["Song"]["ProdID"]; ?>" />
                                        <input type="hidden" name="ProviderType" value="<?php echo $albumSong["Song"]["provider_type"]; ?>" />

                                        <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
                                            <![if !IE]>
                                            <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" 
                                               onclick='return wishlistDownloadOthersHome("<?php echo $albumSong["Song"]['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $albumSong["Song"]["provider_type"]; ?>");'>
                                                   <?php __('Download Now'); ?>
                                            </a>
                                            <![endif]>
                                            <!--[if IE]>
                                                   <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                    onclick='wishlistDownloadIEHome("<?php echo $albumSong["Song"]['ProdID']; ?>", "0" , "<?php echo $albumSong["Song"]["provider_type"]; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' 
                                                    href="javascript:void(0);">
                                            <?php __('Download Now'); ?></a>
                                            <![endif]-->
                                        </span>

                                        <span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait.."); ?>
                                                <span id="wishlist_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php echo $html->image('ajax-loader_black.gif'); ?></span> </a> </span>

                                    </form>
                                    <?php
                                }
                                else
                                {
                                    ?><a class='add-to-wishlist' href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __("Downloaded"); ?></a><?php
                                }
                            }
                            else
                            {
                                ?>
                                <a class="add-to-wishlist" href="javascript:void(0)"><?php __("Limit Met"); ?></a>                
                                <?php
                            }
                        }
                        ?>

                        <?php ?>
                        <?php
                        if ($streamingFlag == 1)
                        {
                            //echo $this->Queue->getQueuesList($this->Session->read('patron'),$albumSong["Song"]["ProdID"],$albumSong["Song"]["provider_type"],$album['Album']["ProdID"],$album['Album']["provider_type"]); 
                            ?>
                            <a class="add-to-playlist" href="javascript:void(0);">Add To Playlist</a>
                        <?php } ?>
                        <!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->
                        <?php
                        $wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);

                        echo $wishlist->getWishListMarkup($wishlistInfo, $albumSong["Song"]["ProdID"], $albumSong["Song"]["provider_type"]);
                        ?>
                        <?php //echo $this->Queue->getSocialNetworkinglinksMarkup();   ?>                                                                                 
                    </div> 
                    <?php
                }
                else
                {
                    ?>
                    <a class="genre-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>

                    <?php
                }
                ?>

            </div>

            <?php
        endforeach;
        ?>



    </section>
    <?php
endforeach;
?>
		