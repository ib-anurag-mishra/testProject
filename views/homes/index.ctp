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

   <?php
    echo '<pre>';
    print_r($nationalTopDownload);
    exit;
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
