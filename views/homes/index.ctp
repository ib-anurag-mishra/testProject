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
                            if (is_array($nationalTopDownload) && count($nationalTopAlbumsDownload) > 0) {

                                foreach ($nationalTopAlbumsDownload as $key => $value)
                                {
                                    //hide song if library block the explicit content
                                    if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
                                    {
                                        continue;
                                    }                            


                            ?>
                                        <li>
                                                <div class="album-cover-container">
                                                        <?php echo $html->link($html->image($value['songAlbumImage']), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))     ?>                                                       
                                                        <div class="ranking"><?php echo $count; ?></div>
                                                        <?php
                                                        if ($this->Session->read("patron"))
                                                        {
                                                            if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs']))
                                                            { 
                                                                $providerType = base64_encode($value['Song']['provider_type']);
                                                                $artistText = base64_encode($value['Song']['ArtistText']);                                                                
                                                             ?>  
                                                                <a onclick="javascript:loadNationalAlbumData('<?php echo $artistText; ?>',<?php echo $value['Albums']['ProdID']; ?>,'<?php echo $providerType ?>');" href="javascript:void(0);" ><button class="play-btn-icon toggleable"></button></a>
                                                       <?php }
                                                         } else { ?>
                                                               <a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> 
                                                   <?php } ?>      
                                                        <button class="playlist-menu-icon toggleable"></button>
                                                        <button class="wishlist-icon toggleable"></button>
                                                        <ul>
                                                                <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>
                                                                <li><a href="#">David's Favorites</a></li>
                                                                <li><a href="#">Pop</a></li>
                                                                <li><a href="#">Day After Christmas</a></li>
                                                                <li><a href="#">A really, really, long playlist name that is going to be long enough for two lines.</a></li>
                                                                <li><a href="#">80's</a></li>
                                                                <li><a href="#">90's</a></li>
                                                                <li><a href="#">Country</a></li>
                                                                <li><a href="#">Rock</a></li>
                                                                <li><a href="#">Metal</a></li>
                                                                <li><a href="#">Breakup Songs</a></li>
                                                                <li><a href="#">New Years</a></li>
                                                                <li><a href="#">Christmas</a></li>
                                                                <li><a href="#">Summer</a></li>
                                                                <li><a href="#">Road Trip</a></li>
                                                                <li><a href="#">Christian</a></li>
                                                                <li><a href="#">Cleaning</a></li>
                                                                <li><a href="#">Workout</a></li>
                                                                <li><a href="#">Running</a></li>
                                                                <li><a href="#">Romantic</a></li>
                                                        </ul>
                                                </div>
                                                <div class="album-info">
                                                        <p class="title"><a href="#">Planet Pit</a><span class="explicit"> (Explicit)</span></p>
                                                        <p class="artist"><a href="#">Pitbull</a></p>
                                                </div>
                                        </li>
                                <?php 
                                    $count++;
                                } 
                        } else {

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
            <button class="multi-select-icon"></button>
            <section class="options-menu">
                <ul>
                    <li><a class="select-all" href="#">Select All</a></li>
                    <li><a class="clear-all" href="#">Clear All</a></li>										
                    <li><a href="#">Add to Wishlist</a></li>
                    <li><a class="add-to-playlist" href="#">Add to Playlist</a></li>
                </ul>
                <ul class="playlist-menu">
                    <li><a href="#">Playlist 1</a></li>
                    <li><a href="#">Playlist 2</a></li>
                    <li><a href="#">Playlist 3</a></li>
                    <li><a href="#">Playlist 4</a></li>
                    <li><a href="#">Playlist 5</a></li>
                    <li><a href="#">Playlist 6</a></li>
                    <li><a href="#">Playlist 7</a></li>
                    <li><a href="#">Playlist 8</a></li>
                    <li><a href="#">Playlist 9</a></li>
                    <li><a href="#">Playlist 10</a></li>
                    <li><a href="#">Playlist 11</a></li>
                    <li><a href="#">Playlist 12</a></li>
                    <li><a href="#">Playlist 13</a></li>
                    <li><a href="#">Playlist 14</a></li>
                    <li><a href="#">Playlist 15</a></li>
                    <li><a href="#">Playlist 16</a></li>
                    <li><a href="#">Playlist 17</a></li>
                    <li><a href="#">Playlist 18</a></li>
                    <li><a href="#">Playlist 19</a></li>
                    <li><a href="#">Playlist 20</a></li>
                </ul>
            </section>
        </div>




    </div>
   <?php
//    echo '<pre>';
//    print_r($nationalTopDownload);
//    exit;
   ?>
</section>
<!-- Top Singles code end here -->



<section class="featured-artists">
        <h2>Featured Artists &amp; Composers</h2>
        <div class="featured-artists-grid clearfix">

                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>



                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg">
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-album-name">
                                        Baptized
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><a href="#" class="more-by-artist"></a>

                                </div>
                        </div>
                </div>	
                <!--						
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>

                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>



                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>							
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/pitbull.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Pitbull
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/carrie-underwood.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Carrie Underwood
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/kellyclarkson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Kelly Clarkson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/michaeljackson.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        Michael Jackson
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                <div class="featured-grid-item">
                        <img src="images/album-covers/msmr.jpg" />
                        <div class="featured-grid-menu">
                                <div class="featured-artist-name">
                                        MSMR
                                </div>
                                <div class="featured-artist-ctas">
                                        <button class="stream-artist">Stream Album</button><button class="more-by-artist"></button>

                                </div>
                        </div>
                </div>
                -->
        </div>
</section>
