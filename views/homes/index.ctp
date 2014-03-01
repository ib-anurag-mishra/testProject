<?php if ($this->Session->read("patron")){ ?>
    <?php echo $this->Html->script('jquery-ias.min'); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery.ias({
                container : '.featured-artists-grid clearfix',
                item: '.featured-scrollset',
                pagination: '.autoscrollnav',
                next: '.next-slides',
                loader: '<img src="/img/ajax-loader-1.gif"/>',
                loaderDelay : 1000,
                history:false,
                onPageChange: function(pageNum, pageUrl, scrollOffset) {

                },
                triggerPageThreshold: 1000,
                trigger:''
            });
        });
    </script>
<?php } ?>
<?php
echo $session->flash();
ini_set("session.cookie_lifetime", "0"); // 0 means "until the browser is closed
//$this->log(" home index.ctp start", "siteSpeed");   
?>
<section class="top-albums">
    <header>
        <h2>Top Albums</h2>
    </header> 
    <div class="top-albums-carousel-container"> 
        <div class="top-albums-carousel">
            <ul class="clearfix">
                <?php
                $count = 1;
                if (is_array($nationalTopAlbums) && count($nationalTopAlbums) > 0)
                {
                    foreach ($nationalTopAlbums as $key => $value)
                    {
                        //hide song if library block the explicit content
                        if (strlen($value['Album']['AlbumTitle']) > 22)
                        {
                            $title = substr($value['Album']['AlbumTitle'], 0, 22) . "..";
                        }
                        else
                        {
                            $title = $value['Album']['AlbumTitle'];
                        }

                        if (strlen($value['Album']['ArtistText']) > 22)
                        {
                            $ArtistText = substr($value['Album']['ArtistText'], 0, 22) . "..";
                        }
                        else
                        {
                            $ArtistText = $value['Album']['ArtistText'];
                        }
                        ?>
                        <li>
                            <div class="album-cover-container">
                                <?php echo $html->link($html->image($value['topAlbumImage']), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Album']['ArtistText']), $value['Album']['ProdID'], base64_encode($value['Album']['provider_type'])), array('class' => 'first', 'escape' => false)) ?>                                                       
                                <div class="ranking"><?php echo $count; ?></div>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Album']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Album']['ProdID']], 1);
                                        ?>  
                                        <input type="hidden" id="<?= $value['Album']['ProdID'] ?>" value="album" data-provider="<?= $value["Album"]["provider_type"] ?>"/>
                                        <button class="playlist-menu-icon toggleable"></button>                                        
                                        <ul>
                                            <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>
                                         
                                        </ul>   
                                        <?php
                                    }
                                    //echo $this->Wishlist->getAlbumWishListMarkup($value['Album']['ProdID'],base64_encode($value['Album']['provider_type']),base64_encode($value['Album']['ArtistText']));
                                    ?>
                                    <button class="wishlist-icon toggleable"></button>
                                    <?php
                                }
                                ?>

                            </div>
                            <div class="album-info">
                                <p class="title"><a title="<?php echo $this->getValidText($this->getTextEncode($value['Album']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($value['Album']['ArtistText']); ?>/<?= $value['Album']['ProdID']; ?>/<?= base64_encode($value['Album']['provider_type']); ?>"><?php echo $this->getTextEncode($title); ?></a></p>
                                <p class="artist"><a title="<?php echo $this->getValidText($this->getTextEncode($value['Album']['ArtistText'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Album']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>"><?php echo $this->getTextEncode($ArtistText); ?></a></p>
                            </div>
                        </li>
                        <?php
                        if ($count == 25)
                        {
                            break;
                        }
                        $count++;
                    }
                }
                else
                {
                    echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
                }
                ?>
            </ul>                        
        </div>
        <button class="left-scroll-button"></button>
        <button class="right-scroll-button"></button>
    </div>
</section>




<!-- Top Singles code start here -->
<section class="top-songs">
    <header>
        <h2>Top Singles</h2>
    </header>

    <div class="top-songs-container clearfix">

        <!-- top header of the grid -->
        <div class="header-container">
            <button class="top-songs-filter-icon"></button>
            <div class="top-songs-filter-menu">
                <ul>
                    <li><a href="#">All Genres</a></li>
                    <li><a href="#">Rock</a></li>
                    <li><a href="#">Country</a></li>
                    <li><a href="#">Pop</a></li>
                </ul>
            </div>
            <div class="song-header">Song</div>
            <div class="song-border header-border"></div>
            <div class="artist-header">Artist</div>
            <div class="artist-border header-border"></div>
            <div class="album-header">Album</div>
            <div class="album-border header-border"></div>
            <div class="time-header">Time</div>
            <?php
            if ($this->Session->read("patron"))
            {
                ?>
                <button class="multi-select-icon"></button>
                <section class="options-menu">
                    <ul>
                        <li><a class="select-all" href="#">Select All</a></li>
                        <li><a class="clear-all" href="#">Clear All</a></li>										
                        <li><a  class="add-all-to-wishlist"href="#">Add to Wishlist</a></li>
                        <li><a class="add-to-playlist" href="#">Add to Playlist</a></li>
                    </ul>
                    <ul class="playlist-menu">

                    </ul>
                </section>
                <?php
            }
            ?>
        </div>

        <!-- showing the songs list -->
        <div class="rows-container">
            <?php
            if (!empty($nationalTopDownload))
            {
                $count = 0;
                foreach ($nationalTopDownload as $nationalTopSong)
                {
                    $count++;
                    ?>
                    <div class="row">
                        <?php
                        if ($this->Session->read("patron"))
                        {
                            ?>
                            <button class="play-btn"></button>
                            <?php
                        }
                        ?>
                        <div class="ranking"><?= $count ?></div>
                        <div class="song-name">
                            <a href="#">
                                <?php
                                if (strlen($nationalTopSong['Song']['SongTitle']) > 25)
                                    echo $this->getValidText($this->getTextEncode(substr($nationalTopSong['Song']['SongTitle'], 0, 25))) . "...";
                                else
                                    echo $this->getValidText($this->getTextEncode($nationalTopSong['Song']['SongTitle']));
                                ?>
                            </a>
                        </div>
                        <div class="artist-name">
                            <a href="#">
                                <?php
                                if (strlen($nationalTopSong['Song']['ArtistText']) > 30)
                                    echo $this->getValidText($this->getTextEncode(substr($nationalTopSong['Song']['ArtistText'], 0, 30))) . "...";
                                else
                                    echo $this->getValidText($this->getTextEncode($nationalTopSong['Song']['ArtistText']));
                                ?>
                            </a>
                        </div>
                        <div class="album-name">
                            <a href="#">
                                <?php
                                if (strlen($nationalTopSong['Song']['Title']) > 30)
                                    echo $this->getValidText($this->getTextEncode(substr($nationalTopSong['Song']['Title'], 0, 30))) . "...";
                                else
                                    echo $this->getValidText($this->getTextEncode($nationalTopSong['Song']['Title']));
                                ?>
                            </a>
                        </div>
                        <div class="time"><?= $nationalTopSong['Song']['FullLength_Duration'] ?></div>

                        <?php
                        if ($this->Session->read("patron"))
                        {
                            if ($this->Session->read('library_type') == 2)
                            {
                                ?>
                                <button class="menu-btn"></button>
                                
                                <section class="options-menu">
                                    <input type="hidden" id="<?= $nationalTopSong["Song"]["ProdID"] ?>" value="song" data-provider="<?= $nationalTopSong["Song"]["provider_type"] ?>"/>
                                    <ul>
                                        <li><a href="#">Download</a></li>
                                        <li><a class="add-to-wishlist" href="#">Add to Wishlist</a></li>
                                        <li><a class="add-to-playlist" href="#">Add to Playlist</a></li>
                                    </ul>
                                    <ul class="playlist-menu">
                                        <li><a href="#">Create New Playlist</a></li>                                                                 
                                    </ul>											
                                </section>
                                <input type="checkbox" class="row-checkbox">
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
            else
            {
                ?>
                <div class="row"> No Songs Found. </div>
                <?php
            }
            ?>
        </div>


        <!-- pagination part of the grid -->
        <div class="pagination-container">
            <!--            <button class="beginning"></button>
                        <button class="prev"></button>
                        <button class="page-1">1</button>
                        <button class="page-2">2</button>
                        <button class="page-3">3</button>
                        <button class="page-4">4</button>
                        <button class="page-5">5</button>
                        <button class="next"></button>
                        <button class="last"></button>-->
        </div>
    </div>




</section>
<!-- Top Singles code end here -->



<section class="featured-artists">
    <h2>Featured Artists &amp; Composers</h2>
    <div class="featured-artists-grid clearfix">
        <div class="featured-scrollset">
            <?php
            // $this->log("index.ctp featuredArtists start", "siteSpeed");  
            $count = 1;
            $cnt = 0;
            foreach ($featuredArtists as $k => $v)
            {
                if($cnt%5 == 0){
                    echo "</div><div class='featured-scrollset'>";
                }                

                //$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
                //$image =  Configure::read('App.Music_Path').$albumArtwork;
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
            <div class="featured-grid-item">
                <a href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])); ?></a>
                <div class="featured-grid-menu">
                    <div class="featured-artist-name">
                        <?php echo $this->getTextEncode($ArtistText); ?>
                    </div>
                    <div class="featured-album-name">
                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $this->getTextEncode($title); ?></a>
                    </div>
                    <div class="featured-artist-ctas">
                                    <?php if ($this->Session->read("patron"))
                                          {
                                                if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                                                {
                                                    echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'][$v['Album']['ProdID']],2);
                                                }
                                          }      
                                        ?>                     
                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])); ?>" class="more-by-artist" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($v['Album']['ArtistText'])); ?>/<?= base64_encode($v['Genre']['Genre']) ?>"><?php echo $this->getTextEncode($ArtistText); ?></a>
                    </div>
                </div>
            </div>
            <?php 
            $cnt++;
            if($count == 10){
                break;
            }
            $count++;
            } ?>
        </div>
        <?php if(count($featuredArtists) >= 10){ ?>
        <div class="autoscrollnav">
              <a href="<?php echo $this->Html->Url(array('controller'=>'homes','action'=>'feature_ajaxlisting')); ?>" class="next-slides">2</a>
       </div>
        <?php } ?>        
    </div>
</section>

<style>
    div[class*='page']   
    {
        display: none;
    }  
</style>