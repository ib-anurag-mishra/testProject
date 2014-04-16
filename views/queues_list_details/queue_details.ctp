<section class="queue-detail-page <?php echo ($default_queue != 1) ? '' : 'fq'; ?>">
    <div class="breadcrumbs">
        <?php
        $queue_type = ($queueType == 'Default') ? '1' : '0';
        $html->addCrumb(__(empty($queue_list_array[0]['QueueList']['queue_name']) ? $queue_name : $queue_list_array[0]['QueueList']['queue_name'], true), '/queuelistdetails/queue_details/' . $queue_id . '/' . $queue_type . '/' . base64_encode($queue_name));
        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        ?>
    </div>
    <div class="col-container clearfix">
        <div class="col-1">
            <img src="/app/webroot/img/queue-details/generic-album-cover.jpg" width="155" height="155" />
        </div>
        <div class="col-2">
            <div class="queue-name">
                <?php echo empty($queue_list_array[0]['QueueList']['queue_name']) ? $queue_name : $queue_list_array[0]['QueueList']['queue_name']; ?>
            </div>
            <div class="queue-length">
                <?php echo $queue_songs_count; ?> Songs
            </div>
            <div class="queue-duration">
                Duration: <?php echo $total_time; ?>
            </div>
            <?php
            if ($queue_songs_count > 0)
            {
                ?>
                <div class="play-queue-btn" ></div>
                <?php
            }
            ?>
            <input type="hidden" id="hid_Plid" value="<?php echo $queue_id; ?>" />
            <input type="hidden" id="hid_playlist_name" value="<?php echo $queue_list_array[0]["QueueList"]["queue_name"]; ?>" />
            <input type="hidden" id="hid_description" value="<?php echo $queue_list_array[0]["QueueList"]["description"]; ?>" />
        </div>
        <div class="col-3">
            <div class="faq-link"><?php echo __('Need help? Visit our', true); ?>  <a href="/questions">FAQ section</a>.</div>
            <div class="button-container" >

                <?php
                if ($default_queue != 1)
                {
                    ?>
                    <div class="gear-icon no-ajaxy"></div>
                <?php } ?>

            </div>
            <div class="queue-options">
                <?php
                if (($this->Session->read("Auth.User.type_id") == 1 && $queueType == 'Default') ||
                        ($this->Session->read("Auth.User.type_id") == 1 && $queueType == 'Custom') ||
                        ($this->Session->read("Auth.User.type_id") != 1 && $queueType == 'Custom'))
                {
                    ?>
                    <a class="rename-queue" href="javascript:void(0);" onclick="queueModifications();">Rename Playlist</a>	
                    <a class="delete-queue" href="javascript:void(0);" onclick="queueModifications();">Delete Playlist</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    echo $session->flash();
    ?>
    <div class="now-playing-container">
        <nav class="playlist-filter-container clearfix">
            <div class="song-filter-button">Song</div>
            <div class="album-filter-button">Album</div>
            <div class="artist-filter-button">Artist</div>
            <div class="time-filter-button">Time</div>
        </nav>

        <div class="playlist-shadow-container">
            <div class="playlist-scrollable">
                <div class="row-container">
                    <?php
                    $playListData = array();
                    $i = 0;
                    foreach ($queue_list_array as $key => $value)
                    {                       
                        if (!isset($value['Songs']))
                            continue;
                        if (($this->Session->read('block') == 'yes') && ($value['Songs']['Advisory'] == 'T'))
                        {
                            continue;
                        }

                        $i++;
                        ?>
                        <div class="row clearfix<?php echo $i; ?>">
                            <?php
                            if ('T' == $value['Songs']['Advisory'])
                            {
                                if (strlen($value['Songs']['SongTitle']) >= 20)
                                {
                                    $value['Songs']['SongTitle'] = $this->getTextEncode(substr($value['Songs']['SongTitle'], 0, 20)) . "..";
                                }
                                $value['Songs']['SongTitle'] .='(Explicit)';
                            }

                            $duration = explode(':', $value['Songs']['FullLength_Duration']);
                            $duration_in_secs = $duration[0] * 60;
                            $total_duration = $duration_in_secs + $duration[1];
                            
                            if ($value['Countries']['StreamingSalesDate'] <= date('Y-m-d') && $value['Countries']['StreamingStatus'] == 1)
                            {
                            echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $key, "onClick" => 'loadSong("' . $value['streamUrl'] . '", "' . base64_encode($value['Songs']['SongTitle']) . '","' . base64_encode($value['Songs']['ArtistText']) . '",' . $total_duration . ',"' . $value['Songs']['ProdID'] . '","' . $value['Songs']['provider_type'] . '",' . $queue_id . ');'));
                            echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $key));
                            echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $key, "onClick" => 'stopThis(this, "' . $key . '");'));
                            }
                             else if ($value['Countries']['SalesDate'] <= date('Y-m-d'))
                            {
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;border: 0px solid;", "id" => "play_audio" . $key, "onClick" => 'playSample(this, "' . $key . '", ' . $value['Songs']['ProdID'] . ', "' . base64_encode($value['Songs']['provider_type']) . '", "' . $this->webroot . '");'));
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "load_audio" . $key));
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "stop_audio" . $key, "onClick" => 'stopThis(this, "' . $key . '");'));
                            }
                            if (!empty($value['Songs']['ProdID']))
                            {
                                ?>
                                <div id="play_item_<?php echo $i; ?>"style="display:none;">
                                    <?php echo $value['Songs']['ProdID'] . ',' . $value['Songs']['provider_type']; ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="song-title">
                                <?php
                                echo $this->getValidText($this->getTextEncode($value['Songs']['SongTitle']));
                                ?>
                            </div>

                            <a class="add-to-wishlist-button no-ajaxy" href='javascript:void(0);'></a>
                            <div class="wishlist-popover <?php echo ($default_queue != 1) ? '' : 'fq'; ?>">
                                <input type="hidden" id="<?= $value['Songs']['ProdID'] ?>" value="song"/>

                                <?php
                                //check if this song is allowed for download
                                if (($value['Countries']['SalesDate'] <= date('Y-m-d') ) && ($value['Countries']['DownloadStatus'] == 1))
                                {
                                    if ($libraryDownload == '1' && $patronDownload == '1')
                                    {                                        
                                        //checking the downlaod status from session
                                         if ($this->Session->read('downloadVariArray'))
                                                    {
                                                        $downloadsUsed = $this->Download->getDownloadResults($value['Songs']['ProdID'],$value['Songs']['provider_type']);
                                                    }
                                                    else
                                                    {
                                                        $downloadsUsed = $this->Download->getDownloadfind($value['Songs']['ProdID'], $value['Songs']['provider_type'], $libraryDownload, $patronDownload, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                                    }


                                                    if ($downloadsUsed > 0)
                                                    {
                                                        $value['Songs']['status'] = 'avail';
                                                    }
                                                    else
                                                    {
                                                        $value['Songs']['status'] = 'not';
                                                    }

                                        
                                        if ($value['Songs']['status'] != 'avail')
                                        {
                                            ?>
                                            <form method="Post" id="form<?php echo $value["Songs"]["ProdID"]; ?>" action="/homes/userDownload">
                                                <input type="hidden" name="ProdID" value="<?php echo $value["Songs"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />

                                                <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                    <![if !IE]>
                                                    <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
                                                       onclick='return wishlistDownloadOthersHome("<?php echo $value["Songs"]['ProdID']; ?>", "0", "<?php echo $value['SongFile']['SCdnPath']; ?>", "<?php echo $value['SongFile']['SSaveAsName']; ?>", "<?php echo $value["Songs"]["provider_type"]; ?>");'>
                                                           <?php __('Download Now'); ?>
                                                    </a>
                                                    <![endif]>
                                                    <!--[if IE]>
                                                            <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $value["Songs"]['ProdID']; ?>", "0" , "<?php echo $value["Songs"]["provider_type"]; ?>", "<?php echo $value['SongFile']['SCdnPath']; ?>", "<?php echo $value['SongFile']['SSaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                    <![endif]-->
                                                </span>

                                                <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;">
                                                    <a  class="add-to-wishlist"  >
                                                        <?php __("Please Wait.."); ?>
                                                        <span id="wishlist_loader_<?php echo $value["Songs"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;">
                                                            <?php echo $html->image('ajax-loader_black.gif'); ?>
                                                        </span> 
                                                    </a> 
                                                </span>

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
                                    if (isset($value['Countries']['SalesDate']))
                                    {
                                        echo date("F d Y", strtotime($value['Countries']['SalesDate']));
                                    }
                                    ?> )' href="javascript:void(0)"><?php __('Coming Soon'); ?></a>
                                       <?php
                                   }
                                   ?>
                                   <?php
                                   if ($this->Session->read('library_type') == 2 &&
                                           $value['Countries']['StreamingSalesDate'] <= date('Y-m-d') && $value['Countries']['StreamingStatus'] == 1)
                                   {
                                       ?>
                                    <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                    <?php
                                }

                                $wishlistInfo = $wishlist->getWishlistData($value["Songs"]["ProdID"]);

                                echo $wishlist->getWishListMarkup($wishlistInfo, $value["Songs"]["ProdID"], $value["Songs"]["provider_type"]);
                                ?>
                                <span class="top-100-download-now-button">
                                    <?php
                                    if (($this->Session->read("Auth.User.type_id") == 1 && $queueType == 'Default') ||
                                            ($this->Session->read("Auth.User.type_id") == 1 && $queueType == 'Custom') ||
                                            ($this->Session->read("Auth.User.type_id") != 1 && $queueType == 'Custom'))
                                    {
                                        ?>
                                        <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                            <a  href="JavaScript:void(0);" onclick="JavaScript:removeSong(<?php echo $value["QueueDetail"]["id"]; ?>,<?php echo $i; ?>)">
                                                <label class="dload" style="width:120px;cursor:pointer;"><?php __('Remove Song'); ?></label>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="album-title">
                                <a href="/artists/view/<?php echo base64_encode($value['Songs']['ArtistText']); ?>/<?= $value['Songs']['ReferenceID']; ?>/<?= base64_encode($value['Songs']['provider_type']); ?>">
                                    <?php echo $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])); ?>
                                </a>                                                
                            </div>

                            <div class="artist-name">

                                <?php
                                $artistText = (strlen($value['Songs']['ArtistText']) >= 30) ? $this->getTextEncode(substr($value['Songs']['ArtistText'], 0, 30)) . ".." : $this->getTextEncode($value['Songs']['ArtistText']);
                                ?>  

                                <a href="/artists/album/<?= base64_encode($value['Songs']['ArtistText']); ?>/<?= $value['Songs']['ReferenceID']; ?>/<?= base64_encode($value['Songs']['provider_type']); ?>">
                                    <?php echo $this->getValidText($this->getTextEncode($artistText)); ?>
                                </a>                                                
                            </div>

                            <div class="time"><?php echo $this->Song->getSongDurationTime($value['Songs']['FullLength_Duration']); ?></div>

                        </div>
                        <?php
                        if (!empty($value['streamUrl']) || !empty($value['Songs']['SongTitle']))
                        {
                            $playItem = array('playlistId' => $queue_id, 'songId' => $value["Songs"]["ProdID"], 'providerType' => $value["Songs"]["provider_type"], 'label' => $value['Songs']['SongTitle'], 'songTitle' => $value['Songs']['SongTitle'], 'artistName' => $value['Songs']['ArtistText'], 'songLength' => $total_duration, 'data' => $value['streamUrl']);
                            $jsonPlayItem = json_encode($playItem);
                            $jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
                            $playListData[] = $jsonPlayItem;
                        }
                    }
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
                            echo '[' . ($playList) . ']';
                        }
                        ?>
                        </div>
                            <?php
                        }
                        ?>    
                </div>
            </div>
        </div>
    </div>
</section>