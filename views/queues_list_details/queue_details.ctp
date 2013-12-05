<section class="queue-detail-page <?php echo ($default_queue != 1) ? '' : 'fq'; ?>">
    <?php
//    if (!empty($queue_list_array))
//    {
    ?>
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
            <input type="hidden" id="hid_Plid" value="<?php echo $queue_id; ?>" />
            <input type="hidden" id="hid_playlist_name" value="<?php echo $queue_list_array[0]["QueueList"]["queue_name"]; ?>" />
            <input type="hidden" id="hid_description" value="<?php echo $queue_list_array[0]["QueueList"]["description"]; ?>" />
        </div>
        <div class="col-3">
            <div class="faq-link"><?php echo __('Need help? Visit our', true); ?>  <a href="javascript:void(0);">FAQ section</a>.</div>
            <div class="button-container" >

                <?php
                if ($default_queue != 1)
                {
                    ?>
                    <div class="gear-icon no-ajaxy"></div>
                <?php } ?>
                <?php
                if ($queue_songs_count > 0)
                {
                    ?>
                    <div class="play-queue-btn" ></div>
                    <?php
                }
                ?>
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
                <!--<div class="share clearfix">
                        <p>Share via</p>
                        <a class="facebook" href="javascript:void(0);"></a>
                        <a class="twitter" href="javascript:void(0);"></a>
                </div> -->
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
                        if(!isset($value['Songs'])) continue;
                        if (($this->Session->read('block') == 'yes') && ($value['Songs']['Advisory'] == 'T'))
                        {
                            continue;
                        }
                        
                        $i++;
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

