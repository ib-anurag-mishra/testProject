
<section class="albums-page">
    <section class="album-detail-container clearfix">
        <div class="breadcrumbs">
            <span>
                <?php
                $genre_text_conversion = array(
                    "Children's Music" => "Children's",
                    "Classic" => "Soundtracks",
                    "Comedy/Humor" => "Comedy",
                    "Country/Folk" => "Country",
                    "Dance/House" => "Dance",
                    "Easy Listening Vocal" => "Easy Listening",
                    "Easy Listening Vocals" => "Easy Listening",
                    "Folk/Blues" => "Folk",
                    "Folk/Country" => "Folk",
                    "Folk/Country/Blues" => "Folk",
                    "Hip Hop Rap" => "Hip-Hop Rap",
                    "Rap/Hip-Hop" => "Hip-Hop Rap",
                    "Rap / Hip-Hop" => "Hip-Hop Rap",
                    "Jazz/Blues" => "Jazz",
                    "Kindermusik" => "Children's",
                    "Miscellaneous/Other" => "Miscellaneous",
                    "Other" => "Miscellaneous",
                    "Age/Instumental" => "New Age",
                    "Pop / Rock" => "Pop/Rock",
                    "R&B/Soul" => "R&B",
                    "Soundtracks" => "Soundtrack",
                    "Soundtracks/Musicals" => "Soundtrack",
                    "World Music (Other)" => "World Music"
                );
                $genre_crumb_name = isset($genre_text_conversion[trim($genre)]) ? $genre_text_conversion[trim($genre)] : trim($genre);
                $html->addCrumb(__('All Genre', true), '/genres/view/');
                if ($genre_crumb_name != "")
                {
                    $html->addCrumb($this->getTextEncode($genre_crumb_name), '/genres/view/' . base64_encode($genre_crumb_name));
                }
                $html->addCrumb(__($this->getTextEncode($artistName), true), '/artists/album/' . str_replace('/', '@', base64_encode($artistName)) . '/' . base64_encode($genre));
                $html->addCrumb($this->getTextEncode($albumData[0]['Album']['AlbumTitle']), '/artists/view/' . str_replace('/', '@', base64_encode($artistName)) . '/' . $album . '/' . base64_encode($albumData[0]['Album']['provider_type']));
                echo $html->getCrumbs(' > ', __('Home', true), '/homes');
                ?>
            </span>
        </div>

        <?php
        if (count($albumData) > 0)
        {
            foreach ($albumData as $album_key => $album):
                ?>
                <section class="album-detail">
                    <div class="album-cover-image">
                        <?php $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']); ?>
                        <img src="<?php echo Configure::read('App.Music_Path') . $albumArtwork; ?>" alt="album-detail-cover" width="250" height="250" />
                        <?php
                        if ($this->Session->read('library_type') == 2 && !empty($album['albumSongs'][$album['Album']['ProdID']]) && $this->Session->read("patron"))
                        {
                            //echo $this->Queue->getAlbumStreamNowLabel($album['albumSongs'][$album['Album']['ProdID']]);
                            echo $this->Queue->getAlbumStreamLabel($album['albumSongs'][$album['Album']['ProdID']]);
                            ?>
                            <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                            <div class="wishlist-popover">
                                <input type="hidden" id="<?= $album['Album']['ProdID'] ?>" value="album"/>
                                <?php
                                // echo $this->Queue->getQueuesListAlbums($this->Session->read('patron'), $album['albumSongs'][$album['Album']['ProdID']], $album['Album']['ProdID'], $album['Album']['provider_type']);
                                ?>
                                <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                <?php //echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
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
                    <div class="release-info">Release Information</div>





                    <div class="album-genre">
                        <?php echo __('Genre') . ": "; ?>
                        <span>
                            <?php
                            echo $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre'])), array("title" => $this->getTextEncode($album['Genre']['Genre'])));
                            if ($album['Album']['Advisory'] == 'T')
                            {
                                echo '<br />';
                                echo '<font class="explicit"> (Explicit)</font>';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="album-label">
                        <?php echo __('Label') . ": "; ?>
                        <span>
                            <?php
                            if ($album['Album']['Label'] != '')
                            {
                                echo $this->getTextEncode($album['Album']['Label']);
                            }
                            ?>
                        </span>
                    </div>

                    <div class="release-detail">
                        <?php
                        if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown')
                        {
                            echo $this->getTextEncode($album['Album']['Copyright']);
                        }
                        ?>
                    </div>

                </section>
                <section class="tracklist-container">
                    <div class="button-container">
                        <div class="play-album-btn"><span></span></div>

                    </div>
                    <div class="album-title"><?php
                        
                    
                        //check the album title value exist or not
                        $albumTextLenght = strlen($album['Album']['AlbumTitle']);
                        $albumTextValue =$album['Album']['AlbumTitle'];
                        if ($albumTextLenght >= 50){
                            $albumTextValue = substr($album['Album']['AlbumTitle'], 0, 50) . '...';                             
                        }
                        if($this->getTextEncode($albumTextValue)){
                                $albumTextValue = $this->getTextEncode($albumTextValue);
                        }
            
                        ?>
                        <?php echo $albumTextValue; ?></div>
                    
                    
                    
                    
                    
                    <div class="artist-name"><?php
                        $artistNames = $artistName;
                        if (strlen($artistName) >= 30)
                        {
                            $artistName = substr($artistName, 0, 30) . '...';
                        }
                        ?>
                        <a title="<?php echo $this->getTextEncode($artistNames); ?>" href="/artists/album/<?php echo base64_encode($albumSongs[$album['Album']['ProdID']][0]['Song']['Artist']); ?>"><?php echo $this->getTextEncode($artistName); ?></a></div>
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


                            if ($this->Session->read("patron"))
                            {

                                if ($this->Session->read('library_type') == 2 && $albumSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $albumSong['Country']['StreamingStatus'] == 1)
                                {

                                    if ('T' == $albumSong['Song']['Advisory'])
                                    {
                                        $song_title = $albumSong['Song']['SongTitle'] . '(Explicit)';
                                    }
                                    else
                                    {
                                        $song_title = $albumSong['Song']['SongTitle'];
                                    }

                                    echo $html->image('play.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'loadSong("' . $albumSong['streamUrl'] . '", "' . base64_encode($song_title) . '","' . base64_encode($albumSong['Song']['ArtistText']) . '",' . $albumSong['totalseconds'] . ',"' . $albumSong['Song']['ProdID'] . '","' . $albumSong['Song']['provider_type'] . '");'));

                                    if (!empty($albumSong['streamUrl']) || !empty($song_title))
                                    {
                                        $playItem = array('playlistId' => 0, 'songId' => $albumSong['Song']['ProdID'], 'providerType' => $albumSong['Song']['provider_type'], 'label' => $song_title, 'songTitle' => $song_title, 'artistName' => $albumSong['Song']['ArtistText'], 'songLength' => $albumSong['totalseconds'], 'data' => $albumSong['streamUrl']);
                                        $jsonPlayItem = json_encode($playItem);
                                        $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                                        $playListData[] = $jsonPlayItem;
                                    }
                                }
                                else if ($albumSong['Country']['SalesDate'] <= date('Y-m-d'))
                                {
                                    echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'playSample(this, "' . $album_key . $key . '", ' . $albumSong["Song"]["ProdID"] . ', "' . base64_encode($albumSong["Song"]["provider_type"]) . '", "' . $this->webroot . '");'));
                                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $album_key . $key));
                                    echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $album_key . $key, "onClick" => 'stopThis(this, "' . $album_key . $key . '");'));
                                }
                                $class = 'logged_in';

                                $cs = '';
                                if (($albumSong['Country']['SalesDate'] > date('Y-m-d') ) && ($albumSong['Country']['DownloadStatus'] == 1))
                                {
                                    $cs = ' cs';
                                }
                            }
                            ?>



                            <div class="song <?php       echo $class;     echo $cs;  ?>">
                                <?php
                                     if (strlen($albumSong['Song']['SongTitle']) >= 30)
                                     {
                                         echo '<a style="text-decoration:none;" title="' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '">' . $this->getTextEncode(substr($albumSong['Song']['SongTitle'], 0, 30)) . '...</a>';
                                     }
                                     else
                                     {
                                         if($this->getTextEncode($albumSong['Song']['SongTitle'])){
                                               echo '<a style="text-decoration:none;" title="' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '">' . $this->getTextEncode($albumSong['Song']['SongTitle']) . '</a>';
                                         }else{
                                               echo '<a style="text-decoration:none;" title="' . $albumSong['Song']['SongTitle'] . '">' . $albumSong['Song']['SongTitle'] . '</a>';
                                         }
                                         
                                     }
                                     if ($albumSong['Song']['Advisory'] == 'T')
                                     {
                                         echo '<span class="explicit"> (Explicit)</span>';
                                     }
                                     ?></div>
                            <?php
                                   //check the artist value exist or not
                                    $artistTextLenght = strlen($albumSong['Song']['Artist']);
                                    $artistTextValue =$albumSong['Song']['Artist'];
                                    if ($artistTextLenght >= 30){
                                        $artistTextValue = substr($albumSong['Song']['Artist'], 0, 30) . '...';                                        
                                    }
                                    if($this->getTextEncode($artistTextValue)){
                                            $artistTextValue = $this->getTextEncode($artistTextValue);
                                    }
                            ?>
                            <div class="artist">
                                <a href="/artists/album/<?php echo base64_encode($albumSong['Song']['Artist']); ?>" 
                                   title="<?php echo $artistTextValue; ?>">
                                       <?php
                                       echo $artistTextValue;
                                       ?>
                                </a>
                            </div>
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
                                        //$productInfo = $song->getDownloadData($albumSong["Song"]['ProdID'], $albumSong["Song"]['provider_type']);

                                        if ($libraryDownload == '1' && $patronDownload == '1')
                                        {
//                                        $songUrl = shell_exec('perl files/tokengen ' . $albumSong['Full_Files']['CdnPath'] . "/" . $albumSong['Full_Files']['SaveAsName']);
//                                        $finalSongUrl = Configure::read('App.Music_Path') . $songUrl;
//                                        $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl) / 3));
                                            if ($albumSong['Song']['status'] != 'avail')
                                            {
                                                ?>
                                                <form method="Post" id="form<?php echo $albumSong["Song"]["ProdID"]; ?>" action="/homes/userDownload">
                                                    <input type="hidden" name="ProdID" value="<?php echo $albumSong["Song"]["ProdID"]; ?>" />
                                                    <input type="hidden" name="ProviderType" value="<?php echo $albumSong["Song"]["provider_type"]; ?>" />

                                                    <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
                                                        <![if !IE]>
                                                        <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" onclick='return wishlistDownloadOthersHome("<?php echo $albumSong["Song"]['ProdID']; ?>", "0", "<?php echo $albumSong['Full_Files']['CdnPath']; ?>", "<?php echo $albumSong['Full_Files']['SaveAsName']; ?>", "<?php echo $albumSong["Song"]["provider_type"]; ?>");'><?php __('Download Now'); ?></a>
                                                        <![endif]>
                                                        <!--[if IE]>
                                                               <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $albumSong["Song"]['ProdID']; ?>", "0" , "<?php echo $albumSong["Song"]["provider_type"]; ?>", "<?php echo $albumSong['Full_Files']['CdnPath']; ?>", "<?php echo $albumSong['Full_Files']['SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
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
                                    else
                                    {
                                        ?>
                                        <a class="add-to-wishlist" title='<?php __("Coming Soon"); ?> ( <?php
                                        if (isset($albumSong['Country']['SalesDate']))
                                        {
                                            echo date("F d Y", strtotime($albumSong['Country']['SalesDate']));
                                        }
                                        ?> )' href="javascript:void(0)"><?php __('Coming Soon'); ?></a>
                                           <?php
                                       }
                                       ?>

                                    <?php
                                    ?>
                                    <?php
                                    if ($streamingFlag == 1)
                                    {
                                        //echo $this->Queue->getQueuesList($this->Session->read('patron'), $albumSong["Song"]["ProdID"], $albumSong["Song"]["provider_type"], $album['Album']["ProdID"], $album['Album']["provider_type"]);
                                        ?>
                                        <a class="add-to-playlist" href="javascript:void(0);">Add To Playlist</a>
                                        <?php
                                    }
                                    ?>
                                    <!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->
                                    <?php
                                    $wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);

                                    echo $wishlist->getWishListMarkup($wishlistInfo, $albumSong["Song"]["ProdID"], $albumSong["Song"]["provider_type"]);
                                    ?>
                                <?php //echo $this->Queue->getSocialNetworkinglinksMarkup();  ?>                                                                                 
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
                    <?php
                    if (!empty($playListData))
                    {
                        ?>    
                        <div id="playlist_data" style="display:none;">
                            <?php
                            $playList = implode(',', $playListData);
                            if (!empty($playList))
                            {
                                echo '[' . $playList . ']';
                            }
                            ?>
                        </div>
        <?php } ?>    


                </section>
                <?php
            endforeach;
        }
        else
        {
            echo '<span>Sorry,there are no more details available.</span>';
        }
        ?>			
    </section>
</section>
