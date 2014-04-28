<section class="my-top-100-page">
    <div class="breadcrumbs">
        <?php
        $html->addCrumb('New Releases', '/homes/new_releases');
        echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
        ?>
    </div>
    <header>
        <h2><?php __('New Releases'); ?></h2>
    </header>
    <h3>Albums</h3>
    <div class="album-shadow-container">
        <div class="album-scrollable horiz-scroll">
            <ul style="width:27000px;">
                <?php
                $libId = $this->Session->read('library');
                $patId = $this->Session->read('patron');
                $count = 1;

                foreach ($new_releases_albums as $key => $value)
                {
                    if($count==101) break;

                    ?>					
                    <li>
                        <div class="album-container">
                            <?php
                            echo $html->link($html->image($value['albumImage'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))
                            ?>
                            <div class="top-10-ranking"><?php echo $count; ?></div>

                            <?php
                            if ($this->Session->read("patron"))
                            {
                                if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']]))
                                {
                                    echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
                                    ?>
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>

                                    <div class="wishlist-popover">
                                        <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album"/>

                                        <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                    </div>
                                <?php } ?>
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
                            <a title="<?php echo $this->getTextEncode($value['Albums']['AlbumTitle']); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                <?php
                                if (strlen($value['Albums']['AlbumTitle']) > 20)
                                    echo substr($this->getTextEncode($value['Albums']['AlbumTitle']), 0, 20) . "...";
                                else
                                    echo $this->getTextEncode($value['Albums']['AlbumTitle']);
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
                                    echo substr($this->getTextEncode($value['Song']['Artist']), 0, 32) . "...";
                                else
                                    echo $this->getTextEncode($this->getTextEncode($value['Song']['Artist']));
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
    </div>

<h3>Videos</h3>
<div class="videos-shadow-container">
    <div class="videos-scrollable horiz-scroll">
        <ul style="width:44100px;">
            <?php

            $count = 1;

            foreach ($new_releases_videos as $key => $value)
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
                        if ($this->Session->read('patron'))
                        {
                            if ($value['Country']['SalesDate'] <= date('Y-m-d'))
                            {

                                if ($libraryDownload == '1' && $patronDownload == '1')
                                {
                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                    $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);

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
                                        <span class="top-100-download-now-button ">
                                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                                    <![if !IE]>
                                                    <a class="no-ajaxy" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
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
                                        <a class="top-10-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>                    
                                    <?php
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
                        <?php
                        if ($this->Session->read("patron"))
                        {
                            ?> 
                            <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                            <div class="wishlist-popover">
                                <?php
                                $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']["ProdID"]);
                                echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']["ProdID"], $value['Video']["provider_type"]);
                                ?>  
                            </div>
                        <?php } ?>

                    </div>
                    <div class="album-title">
                        <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                            <?php

                            if (strlen($value['Video']['VideoTitle']) > 20)
                                echo substr($this->getTextEncode($value['Video']['VideoTitle']), 0, 20) . "...";
                            else
                                echo $this->getTextEncode($value['Video']['VideoTitle']);
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

</div>
</section>