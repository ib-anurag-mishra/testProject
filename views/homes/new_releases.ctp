<section class="my-top-100-page">
    <div class="breadcrumbs">
        <?php
        $html->addCrumb(__('New Releases', true), '/homes/new_releases');
        echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
        ?>
    </div>
    <header>
        <h2><?php __('New Releases'); ?></h2>
    </header>
    <h3><?php __('Albums'); ?></h3>
    <div class="album-shadow-container">
        <div class="album-scrollable horiz-scroll carousel">
            <ul style="width:16500px;">
                <?php
                $libId = $this->Session->read('library');
                $patId = $this->Session->read('patron');
                $count = 1;

                foreach ($new_releases_albums as $key => $value) {
                    if($count==101) { 
                    	break;
                    };

                    ?>					
                    <li>
                        <div class="album-container">

                            <?php
                            $albumTitleTrack = $value['Albums']['AlbumTitle'];
                            echo $html->link($html->image($value['albumImage'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('onclick' => "ga('send', 'event', 'New Release Albums', 'Artwork Click', '$count-$this->getTextEncode($albumTitleTrack)')", 'class' => 'first', 'escape' => false))
                            ?>
                            <div class="top-10-ranking"><?php echo $count; ?></div>

                            <?php
                            if ($this->Session->read("patron")) {
                                if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']])) {
                                    echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']], null, $count . '-' . $this->getTextEncode($value['Albums']['AlbumTitle']), 'New Release Albums');
                                    echo $this->Form->hidden('empty', array('value' => 'album', 'id' => $value['Albums']['ProdID'], 'name' => false, 'data-provider' => $value['Albums']['provider_type']));

                                ?>


                                    <a onclick="ga('send', 'event', 'New Release Albums', 'Toggle Playlists', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>')" class="playlist-menu-icon add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                                    <ul>
                                        <li><a href="#" class="create-new-playlist"><?php __('Create New Playlist'); ?>...</a></li>
                                    </ul>
                                    <a onclick="ga('send', 'event', 'New Release Albums', 'Add to Wishlist', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>')" class="wishlist-icon toggleable no-ajaxy" href="#" title="Add to Wishlist"></a>                                     

                                <?php } 
                            } ?>


                        </div>
                        <div class="album-title">							
                            <a onclick="ga('send', 'event', 'New Release Albums', 'Title Click', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>')" title="<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                <?php
                                if (strlen($value['Albums']['AlbumTitle']) > 20)
                                    echo substr($this->getTextEncode($value['Albums']['AlbumTitle']), 0, 20) . "...";
                                    //echo substr(mb_convert_encoding($value['Albums']['AlbumTitle'],'UTF-8', 'UTF-8'), 0, 20) . "...";
                                else
                                    echo $this->getTextEncode($value['Albums']['AlbumTitle']);
                                    //echo mb_convert_encoding($value['Albums']['AlbumTitle'],'UTF-8', 'UTF-8');
                                ?>
                            </a><?php
                            if ('T' == $value['Albums']['Advisory']) {
                                ?> <span style="color: red;display: inline;"> (<?php __('Explicit'); ?>)</span> <?php } ?>
                        </div>
                        <div class="artist-name">							
                            <a onclick="ga('send', 'event', 'New Release Albums', 'Artist Click', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>')" title="<?php echo $this->getTextEncode($value['Song']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
                                <?php
                                if (strlen($value['Song']['Artist']) > 32)
                                    echo substr($this->getTextEncode($value['Song']['Artist']), 0, 32) . "...";
                                    //echo substr(mb_convert_encoding($value['Song']['Artist'],'UTF-8', 'UTF-8'), 0, 32) . "...";
                                else
                                    echo $this->getTextEncode($this->getTextEncode($value['Song']['Artist']));
                                    //echo mb_convert_encoding($value['Song']['Artist'],'UTF-8', 'UTF-8');
                                ?>
                            </a>
                        </div>
                    </li>
                    <?php
                    $count++;
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
        <ul style="width:29100px;">
            <?php
            $count = 1;
            foreach ($new_releases_videos as $key => $value) {

                //hide song if library block the explicit content
                if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T')) {
                    continue;
                }
                ?>
                <li>

                    <div class="video-container">
                        <a onclick="ga('send', 'event', 'New Release Videos', 'Artwork Click', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>')" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                            <img src="<?php echo $value['videoAlbumImage']; ?>" alt="<?php echo $this->getValidText($value['Video']['Artist'] . ' - ' . $value['Video']['VideoTitle']); ?>" width="423" height="250" />
                        </a>                                                  
                        <div class="top-10-ranking"><?php echo $count; ?></div>

                        <?php
                        if ($this->Session->read('patron')) {
                            
                            if ($value['Country']['SalesDate'] <= date('Y-m-d')) {

                                if ($libraryDownload == '1' && $patronDownload == '1') {
                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                    $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                    if ($downloadsUsed > 0) {
                                        $value['Video']['status'] = 'avail';
                                    } else {
                                        $value['Video']['status'] = 'not';
                                    }
                                    if ($value['Video']['status'] != 'avail') {
                                        ?>
                                        <span class="mylib-top-10-video-download-now-button">
                                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                    <![if !IE]>
                                                    <a class="no-ajaxy" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>"); ga("send", "event", "New Release Videos", "Toggle Menu", "<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>")'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
                                                    <![endif]>
                                                    <!--[if IE]>
                                                            <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a></label>
                                                    <![endif]-->
                                                </span>
                                                <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait'); ?>...&nbsp;&nbsp;</span>
                                                <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                            </form>
                                        </span>
                                        <?php
                                    } else {
                                        ?>
                                        <a class="mylib-top-10-video-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a class="mylib-top-10-video-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>                    
                                    <?php
                                }
                            } else {
                                ?>
                                <a class="mylib-top-10-video-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon"); ?> ( <?php
                                    if (isset($value['Country']['SalesDate'])) {
                                        echo date("F d Y", strtotime($value['Country']['SalesDate']));
                                    }
                                    ?> )'><?php __("Coming Soon"); ?></span></a>
                                    <?php
                                }
                            }

                        if ($this->Session->read("patron")) { ?> 
                            <a onclick="ga('send', 'event', 'New Release Videos', 'Toggle Menu', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>')" class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                            <div class="wishlist-popover">
                                <?php
                                $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']["ProdID"]);
                                echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']["ProdID"], $value['Video']["provider_type"]);
                                ?>  
                            </div>
                        <?php } ?>

                    </div>
                    <div class="album-title">
                        <a onclick="ga('send', 'event', 'New Release Videos', 'Title Click', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>')" title="<?php echo $this->getValidText($value['Video']['VideoTitle']); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                            <?php
                            if (strlen($value['Video']['VideoTitle']) > 20)
                                echo substr($this->getTextEncode($value['Video']['VideoTitle']), 0, 20) . "...";
                            else
                                echo $this->getTextEncode($value['Video']['VideoTitle']);
                            ?>
                        </a><?php
                        if ('T' == $value['Video']['Advisory']) {
                            ?> <span style="color: red;display: inline;"> (<?php __('Explicit'); ?>)</span> <?php } ?>
                    </div>
                    <div class="artist-name">
                        <a onclick="ga('send', 'event', 'New Release Videos', 'Artist Click', '<?php echo $count; ?>-<?php echo $this->getTextEncode($value['Video']['VideoTitle']); ?>')" title="<?php echo $this->getValidText($value['Video']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Video']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
                            <?php
                            if (strlen($value['Video']['Artist']) > 32)
                                echo substr($this->getTextEncode($value['Video']['Artist']), 0, 32) . "...";
                            else
                                echo $this->getTextEncode($value['Video']['Artist']);
                            ?>
                        </a>
                    </div>
                </li>

                <?php
                $count++;
            }
            ?>

        </ul>
    </div>
    <button class="left-scroll-button" type="button"></button>
    <button class="right-scroll-button" type="button"></button>
</div>
</section>
