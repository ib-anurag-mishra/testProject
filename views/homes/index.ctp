<?php
echo $session->flash();
ini_set("session.cookie_lifetime", "0"); // 0 means "until the browser is closed 
?>
<section class="news">
    <div class="top-100">
        <header>
            <h3><?php echo __('National Top 100', true); ?></h3>

        </header>
        <nav class="top-100-nav">
            <ul>

                <li>

                    <a href="#top-100-songs" id="songsIDVal" class="active no-ajaxy hp-tabs" data-category-type="songs" onclick="showHideGrid('songs')">Songs</a>


                </li>
                <li>

                    <a href="#top-100-videos" id="videosIDVal" class="no-ajaxy hp-tabs" data-category-type="videos" onclick="showHideGrid('videos')">Albums</a>

                </li>
            </ul>



        </nav>
        <div class="grids active">

            <div id="top-100-songs-grid" class="top-100-grids horiz-scroll active">
                <ul style="width:27064px;">


                    <?php  
                    if (is_array($nationalTopDownload) && count($nationalTopDownload) > 0)
                    {
                        
                    ?>

                        <?php
                        $libId = $this->Session->read('library');
                        $patId = $this->Session->read('patron');
                        $j = 0;
                        $k = 2000;
                        for ($i = 0; $i < count($nationalTopDownload); $i++)
                        {
                            //hide song if library block the explicit content
                            if (($this->Session->read('block') == 'yes') && ($nationalTopDownload[$i]['Song']['Advisory'] == 'T'))
                            {
                                continue;
                            }

                            if ($j == 5)
                            {
                                break;
                            }

                            if ($i <= 9)
                            {
                                $lazyClass = '';
                                $srcImg = $nationalTopDownload[$i]['songAlbumImage'];
                                $dataoriginal = '';
                            }
                            else                //  Apply Lazy Class for images other than first 10.
                            {
                                $lazyClass = 'lazy';
                                $srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
                                $dataoriginal = $nationalTopDownload[$i]['songAlbumImage'];
                            }
                            ?>

                            <li>
                                <div class="top-100-songs-detail">
                                    <div class="song-cover-container">
                                        <a href="/artists/view/<?= base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']); ?>">
                                            <img class="<?php echo $lazyClass; ?>" alt="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']) . ' - ' . $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])); ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>"  width="250" height="250" /></a>
                                        <div class="top-100-ranking"><?php
                                            $slNo = ($i + 1);
                                            echo $slNo;
                                            ?></div>

                                        <?php
                                        if ($this->Session->read("patron"))
                                        {
                                            if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1)
                                            {
                                                if ('T' == $nationalTopDownload[$i]['Song']['Advisory'])
                                                {
                                                    $song_title = $nationalTopDownload[$i]['Song']['SongTitle'] . '(Explicit)';
                                                }
                                                else
                                                {
                                                    $song_title = $nationalTopDownload[$i]['Song']['SongTitle'];
                                                }

                                                echo $this->Queue->getNationalsongsStreamNowLabel($nationalTopDownload[$i]['Full_Files']['CdnPath'],$nationalTopDownload[$i]['Full_Files']['SaveAsName'], $song_title, $nationalTopDownload[$i]['Song']['ArtistText'], $nationalTopDownload[$i]['Song']['FullLength_Duration'], $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);
                                            }
                                            else if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d'))
                                            {
                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $nationalTopDownload[$i]['Song']['ProdID'] . ', "' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) . '", "' . $this->webroot . '");'));
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                                            }
                                        }
                                        
                                        if ($this->Session->read('patron'))
                                        {
                                            if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d'))
                                            {
                                             
                                                if ($libraryDownload == '1' && $patronDownload == '1')
                                                {
                                                    if ($this->Session->read('downloadVariArray'))
                                                    {
                                                        $downloadsUsed = $this->Download->getDownloadResults($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);
                                                    }
                                                    else
                                                    {
                                                        $downloadsUsed = $this->Download->getDownloadfind($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                                    }


                                                    if ($downloadsUsed > 0)
                                                    {
                                                        $nationalTopDownload[$i]['Song']['status'] = 'avail';
                                                    }
                                                    else
                                                    {
                                                        $nationalTopDownload[$i]['Song']['status'] = 'not';
                                                    }

                                                    if ($nationalTopDownload[$i]['Song']['status'] != 'avail')
                                                    {
                                                        ?>
                                                        <span class="top-100-download-now-button">
                                                            <form method="Post" id="form<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                                <input type="hidden" name="ProdID" value="<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" />
                                                                <input type="hidden" name="ProviderType" value="<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>" />
                                                                <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
                                                                    <![if !IE]>
                                                                    <a href='javascript:void(0);' class="add-to-wishlist no-ajaxy top-10-download-now-button" 
                                                                       title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" 
                                                                       onclick='return wishlistDownloadOthersHome("<?php echo $nationalTopDownload[$i]["Song"]['ProdID']; ?>", "0", "<?php echo $nationalTopDownload[$i]['Full_Files']['CdnPath']; ?>", "<?php echo $nationalTopDownload[$i]['Full_Files']['SaveAsName']; ?>", "<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>");'>
                                                                        <?php __('Download Now'); ?></a>
                                                                    <![endif]>
                                                                    <!--[if IE]>
                                                                           <a id="song_download_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" 
                                                                                class="no-ajaxy top-10-download-now-button" 
                                                                                title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                                onclick='wishlistDownloadIEHome("<?php echo $nationalTopDownload[$i]["Song"]['ProdID']; ?>", "0" , "<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>", "<?php echo $nationalTopDownload[$i]['Full_Files']['CdnPath']; ?>", "<?php echo $nationalTopDownload[$i]['Full_Files']['SaveAsName']; ?>");' 
                                                                                href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                                    <![endif]-->
                                                                </span>
                                                                <span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait.."); ?>
                                                                        <span id="wishlist_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php echo $html->image('ajax-loader_black.gif'); ?></span> </a> </span>
                                                            </form>
                                                        </span>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a> 

                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                <a class="top-100-download-now-button" href="javascript:void(0);">
                                                    <span title='<?php __("Coming Soon"); ?> ( <?php
                                                    if (isset($nationalTopDownload[$i]['Country']['SalesDate']))
                                                    {
                                                        echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));
                                                    }
                                                    ?> )'>
                                                              <?php __("Coming Soon"); ?>
                                                    </span>
                                                </a>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>


                                            <?php
                                        }
                                        ?>



                                        <?php
                                        if ($this->Session->read("patron"))
                                        {
                                            
                                                ?> 
                                                <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                                
                                            <div class="wishlist-popover">
                                                <input type="hidden" id="<?= $nationalTopDownload[$i]["Song"]["ProdID"] ?>" value="song"/>
                                                
                                                <?php
                                                if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1)
                                                {
                                                    ?>
                                                    <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                
                                                
                                                $wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);
                                              
                                                echo $wishlist->getWishListMarkup($wishlistInfo, $nationalTopDownload[$i]["Song"]["ProdID"], $nationalTopDownload[$i]["Song"]["provider_type"]);
                                                ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <?php
                                    if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 30)
                                    {
                                        $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 30)) . "..";
                                    }
                                    else
                                    {
                                        $songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
                                    }

                                    if ('T' == $nationalTopDownload[$i]['Song']['Advisory'])
                                    {
                                        if (strlen($songTitle) >= 20)
                                        {
                                            $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 20)) . "..";
                                        }
                                        $songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';
                                    }
                                    ?>


                                    <?php
                                    if (strlen($nationalTopDownload[$i]['Song']['ArtistText']) >= 30)
                                    {
                                        $artistText = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 30)) . "..";
                                    }
                                    else
                                    {
                                        $artistText = $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']);
                                    }
                                    ?>


                                    <div class="song-title">
                                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])); ?> " href="/artists/view/<?= base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']); ?>"><?php echo $this->getTextEncode($songTitle); ?></a>
                                    </div>
                                    <div class="artist-name">                                                                                                            
                                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText'])); ?>" href="/artists/album/<?php echo base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>"><?php echo $this->getTextEncode($artistText); ?></a>
                                    </div>
                                </div>
                            </li>

                            <?php
                            $k++;
                        }
                    }
                    ?>	
                </ul>
            </div>
            <div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
                <ul style="width:27100px;">
                    <?php
                    $count = 1;
                    if (count($nationalTopAlbumsDownload) > 0)
                    {
                        foreach ($nationalTopAlbumsDownload as $key => $value)
                        {
                            //hide song if library block the explicit content
                            if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
                            {
                                continue;
                            }
                            ?>					
                            <li>
                                <div class="album-container">							
                                    <?php
                                    
                                        if ($count <= 10)
                                        {
                                            $lazyClass = '';
                                            $srcImg = $value['songAlbumImage'];
                                            $dataoriginal = '';
                                        }
                                        else                //  Apply Lazy Class for images other than first 10.
                                        {
                                            $lazyClass = 'lazy';
                                            $srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
                                            $dataoriginal = $value['songAlbumImage'];
                                        }
                                    
                                    
                                    echo $html->link($html->image($srcImg, array("height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal)), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))
                                            
                                    ?>
                                    <div class="top-100-ranking"><?php echo $count; ?></div>
                                    <?php
                                    if ($this->Session->read("patron"))
                                    {
                                        if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs']))
                                        {
                                            echo $this->Queue->getNationalAlbumStreamLabel($value['Song']['ArtistText'],$value['Albums']['ProdID'],$value['Song']['provider_type']);
                                            ?> 
                                            <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                            <?php
                                        }
                                        ?>
                                        <div class="wishlist-popover">
                                            <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album"/>
                                            <?php
                                            if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs']))
                                            {
                                                ?>
                                                <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> 
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="album-title">							
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                        <?php
                                        if (strlen($value['Albums']['AlbumTitle']) > 20)
                                            echo $this->getValidText($this->getTextEncode(substr($value['Albums']['AlbumTitle'], 0, 20))) . "...";
                                        else
                                            echo $value['Albums']['AlbumTitle'];
                                        ?>
                                    </a><?php
                                    if ('T' == $value['Albums']['Advisory'])
                                    {
                                        ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                                </div>
                                <div class="artist-name">							
                                    <a title="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Song']['Genre']) ?>">
                                        <?php
                                        if (strlen($value['Song']['Artist']) > 32)
                                            echo $this->getValidText($this->getTextEncode(substr($value['Song']['Artist'], 0, 32))) . "...";
                                        else
                                            echo $this->getValidText($this->getTextEncode($value['Song']['Artist']));
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
                    ?>
                </ul>  
            </div>
        </div> <!-- end .grids -->

    </div>
    <div class="featured">
        <header>
            <h3>Featured Albums</h3>
        </header>
        <div class="featured-grid horiz-scroll">
            <ul style="width:3690px;">
                <?php 
                foreach ($featuredArtists as $k => $v)
                {
                    if (strlen($v['Album']['AlbumTitle']) > 22)
                    {
                        $title = substr($v['Album']['AlbumTitle'], 0, 22) . "..";
                    }
                    else
                    {
                        $title = $v['Album']['AlbumTitle'];
                    }

                    if (strlen($v['Album']['ArtistText']) > 22)
                    {
                        $ArtistText = substr($v['Album']['ArtistText'], 0, 22) . "..";
                    }
                    else
                    {
                        $ArtistText = $v['Album']['ArtistText'];
                    }
                    ?>								

                    <li>
                        <div class="featured-album-detail">
                            <div class="album-cover-container">												

                                <a href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])); ?></a>

                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'][$v['Album']['ProdID']]);
                                    }
                                    ?> 
                                    <?php
                                    if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                                    {
                                        ?>
                                        <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                        <div class="wishlist-popover">    
                                            <input type="hidden" id="<?= $v['Album']['ProdID'] ?>" value="album"/>

                                            <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                            <?php ?>

                                        </div>                                            
                                    <?php
                                    }
                                    ?>

                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> 
                                <?php 
                                }
                                ?>
                            </div>
                            <div class="album-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $this->getTextEncode($title); ?></a>
                            </div>


                            <div class="artist-name">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($v['Album']['ArtistText'])); ?>/<?= base64_encode($v['Genre']['Genre']) ?>"><?php echo $this->getTextEncode($ArtistText); ?></a>
                            </div>
                        </div>
                    </li>

                    <?php
                }
                ?>	


            </ul>
        </div>
    </div><!-- end .featured -->
    <div class="coming-soon">
        <header class="clearfix">
            <h3>Coming Soon</h3>


        </header>
        <div class="coming-soon-filter-container clearfix">
            <nav class="category-filter">
                <ul class="clearfix">
                    <li><a href="#coming-soon-singles-grid" id="songsIDValComming" class="active no-ajaxy hp-tabs" onclick="showHideGridCommingSoon('songs')">Songs</a></li>
                    <li><a href="#coming-soon-videos-grid" id="videosIDValComming" class="no-ajaxy hp-tabs" onclick="showHideGridCommingSoon('videos')">Videos</a></li>
                </ul>

            </nav>

        </div>
        <?php ?>


        <div id="coming-soon-singles-grid" class="horiz-scroll active">
            <ul class="clearfix">
                <?php
                $total_songs = count($coming_soon_rs);
                $sr_no = 0;

                foreach ($coming_soon_rs as $key => $value)
                {

                    //hide song if library block the explicit content
                    if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T'))
                    {
                        continue;
                    }

                    if ($sr_no <= 9)
                    {
                        $lazyClass = '';
                        $srcImg = $value['cs_songImage'];
                        $dataoriginal = '';
                    }
                    else                //  Apply Lazy Class for images other than first 10.
                    {
                        $lazyClass = 'lazy';
                        $srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
                        $dataoriginal = $value['cs_songImage'];
                    }


                    if ($sr_no >= 20)
                        break;
                    ?>
                    <?php
                    if ($sr_no % 2 == 0)
                    {
                        ?><li> <?php } ?>
                        <div class="single-detail">
                            <div class="single-cover-container">

                                <a href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <img class="<?php echo $lazyClass; ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>" alt="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist']) . ' - ' . $this->getTextEncode($value['Song']['SongTitle'])); ?>" width="162" height="162" /></a>

                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 													
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">

                                    </a>
                                    <div class="wishlist-popover"> 
                                        <?php
                                        $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
                                        echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
                                        ?>
                                    </div>

                                <?php } ?>
                            </div>
                            <div class="song-title">
                                <a title="<?php echo $this->getTextEncode($value['Song']['SongTitle']); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <?php

                                    $commingSoonSongTitle = $this->getTextEncode($value['Song']['SongTitle']);

                                    if ('T' == $value['Song']['Advisory'])
                                    {

                                        if (strlen($commingSoonSongTitle) > 13)
                                            echo substr($commingSoonSongTitle, 0, 13) . "...";
                                        else
                                            echo $commingSoonSongTitle;
                                    }else
                                    {
                                        if (strlen($commingSoonSongTitle) > 20)
                                            echo substr($commingSoonSongTitle, 0, 20) . "...";
                                        else
                                            echo $commingSoonSongTitle;
                                    }
                                    ?>
                                </a>	<?php
                                if ('T' == $value['Song']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a title="<?php echo $this->getTextEncode($value['Song']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Song']['Genre']) ?>">
                                    <?php
                                    $commingSoonSongArtistTitle = $this->getTextEncode($value['Song']['Artist']);
                                    if (strlen($commingSoonSongArtistTitle) > 20)
                                        echo substr($commingSoonSongArtistTitle, 0, 20) . "...";
                                    else
                                        echo $commingSoonSongArtistTitle;
                                    ?>
                                </a>
                            </div>
                        </div>

                        <?php
                        if ($sr_no % 2 == 1 || $sr_no == ($total_songs - 1))
                        {
                            ?> </li> <?php } ?>

                    <?php
                    $sr_no++;
                }
                ?>

            </ul>
        </div> <!-- end #coming-soon-singles-grid -->
        <div id="coming-soon-videos-grid" class="clearfix horiz-scroll">
            <ul class="clearfix" style="width:3333px;">										
                <?php
                $total_videos = count($coming_soon_videos);
                $sr_no = 0;
                foreach ($coming_soon_videos as $key => $value)
                {

                    //hide song if library block the explicit content
                    if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                    {
                        continue;
                    }

                    if ($sr_no >= 20)
                        break;
                    ?>
                    <?php
                    if ($sr_no % 2 == 0)
                    {
                        ?><li> <?php } ?>
                        <div class="video-detail">
                            <div class="video-cover-container">
                                <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <img  src="<?php echo $value['videoAlbumImage']; ?>"  alt="<?php echo $this->getValidText($this->getTextEncode($value['Video']['Artist']) . ' - ' . $this->getTextEncode($value['Video']['VideoTitle'])); ?>" width="275" height="162" />
                                </a>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">

                                    </a>
                                    <div class="wishlist-popover">	
                                        <?php
                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                        ?>


                                    </div>
                                <?php } ?>
                            </div>
                            <div class="video-title">

                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <?php
                                    $commingSoonVideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);

                                    if ('T' == $value['Video']['Advisory'])
                                    {
                                        if (strlen($commingSoonVideoTitle) > 15)
                                            echo substr($commingSoonVideoTitle, 0, 15) . "...";
                                        else
                                            echo $commingSoonVideoTitle;
                                    }else
                                    {
                                        if (strlen($commingSoonVideoTitle) > 20)
                                            echo substr($commingSoonVideoTitle, 0, 20) . "...";
                                        else
                                            echo $commingSoonVideoTitle;
                                    }
                                    ?> </a><?php
                                if ('T' == $value['Video']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>

                            </div>
                            <div class="artist-name">


                                <a title="<?php echo $this->getTextEncode($value['Video']['Artist']); ?>" href="javascript:void(0)">
                                    <?php
                                    if (strlen($value['Video']['Artist']) > 20)
                                        echo substr($value['Video']['Artist'], 0, 20) . "...";
                                    else
                                        echo $value['Video']['Artist'];
                                    ?></a>
                            </div>
                        </div>

                        <?php
                        if ($sr_no % 2 == 1 || $sr_no == ($total_videos - 1))
                        {
                            ?> </li> <?php } ?>

                    <?php
                    $sr_no++;
                }
                ?>

            </ul>
        </div><!-- end videos grid -->

    </div> <!-- end coming soon -->

    <div class="whats-happening">
        <header>
            <h3>News</h3>

        </header>



        <div id="whats-happening-grid" class="horiz-scroll">
            <ul class="clearfix" style="width:4400px;">
                <?php
                $count = 1;
                foreach ($news as $key => $value)
                {
                    $newsText = str_replace('<div', '<p', $value['News']['body']);
                    $newsText = str_replace('</div>', '</p>', $newsText);
                    ?>
                    <li>
                        <div class="post">
                            <div class="post-header-image">
                                <a href="javascript:void(0);"><img src ='<?php echo $cdnPath . 'news_image/' . $value['News']['image_name']; ?>' style="width:417px;height:196px;" alt="<?php echo $this->getValidText($value['News']['subject']); ?>" /></a>
                            </div>
                            <div class="post-title">
                                <a href="javascript:void(0);"><?php echo $this->getValidText($value['News']['subject']); ?></a>
                            </div>
                            <div class="post-date">
                                <?php echo $value['News']['place'] ?> : <?php echo date("F d, Y", strtotime($value['News']['created'])) ?>
                            </div>

                            <div class="post-excerpt">
                                <?php echo $newsText; ?>
                            </div>
                        </div>
                    </li>

                    <?php
                    if ($count == 10)
                        break;
                    $count++;
                }
                ?>
            </ul>



        </div>

</section> <!-- end .news -->	