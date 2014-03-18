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
        <div class="top-albums-carousel carousel">
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
                                    ?>
                                    <input type="hidden" id="<?= $value['Album']['ProdID'] ?>" value="album" data-provider="<?= $value["Album"]["provider_type"] ?>"/>
                                    <?php
                                    if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'][$value['Album']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamLabel($value['albumSongs'][$value['Album']['ProdID']], 1);
                                        ?>                                          
                                        <?php /*<button class="playlist-menu-icon toggleable"></button> */ ?>    
                                        <a class="playlist-menu-icon toggleable no-ajaxy" href="#" title="Add to a Playlist or Create a New Playlist"></a>                                   
                                        <ul>
                                            <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>

                                        </ul>   
                                        <?php
                                    }
                                    //echo $this->Wishlist->getAlbumWishListMarkup($value['Album']['ProdID'],base64_encode($value['Album']['provider_type']),base64_encode($value['Album']['ArtistText']));
                                    ?>
                                    <?php /*<button class="wishlist-icon toggleable"></button> */ ?>
                                    <a class="wishlist-icon toggleable no-ajaxy" href="#" title="Add to Wishlist"></a>
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
	<header><h2>Top Singles</h2></header>
	<div class="top-singles-carousel-container">
	<div class="top-singles-carousel carousel">
		<ul>
					<?php
								if( !empty( $top_singles ) ) {
									$count = 0;
                					foreach( $top_singles as $nationalTopSong ) {
                    					$count++;
                    					if( $count % 2 != 0 ) {
								?>
									<li>
								<?php }?>
										<div class="top-single-container">
											<div class="single-bar"></div>
											<div class="ranking"><?=$count;?></div>
											
<?php if ($this->Session->read('patron')) {
	if ($nationalTopSong['Country']['SalesDate'] <= date('Y-m-d')) {
		if ($libraryDownload == '1' && $patronDownload == '1') {
			if ($this->Session->read('downloadVariArray')) {
				$downloadsUsed = $this->Download->getDownloadResults($nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type']);
			} else {
				$downloadsUsed = $this->Download->getDownloadfind($nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
			}
			if ($downloadsUsed > 0) {
				$nationalTopSong['Song']['status'] = 'avail';
			} else {
	                         $nationalTopSong['Song']['status'] = 'not';
			}
			if ($nationalTopSong['Song']['status'] != 'avail') { ?>
				<span class="top-100-download-now-button">
                                	<form method="Post" id="form<?= $nationalTopSong["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                        	<input type="hidden" name="ProdID" value="<?= $nationalTopSong["Song"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?= $nationalTopSong["Song"]["provider_type"]; ?>" />
                                                <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?= $nationalTopSong["Song"]["ProdID"]; ?>">
                                                	<![if !IE]>
                                                        <?php /*
                                                        <a href='javascript:void(0);' class="no-ajaxy top-10-download-now-button" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>" onclick='return wishlistDownloadOthersHome("<?= $nationalTopSong["Song"]['ProdID']; ?>", "0", "<?= $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?= $nationalTopSong['Full_Files']['SaveAsName']; ?>", "<?= $nationalTopSong["Song"]["provider_type"]; ?>",1);'>
                                                                     <button class="download-icon"></button>
                                                        </a>
                                                        */ ?>
                                                        <a class="download-icon" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not." onclick='return wishlistDownloadOthersHome("<?= $nationalTopSong["Song"]['ProdID']; ?>", "0", "<?= $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?= $nationalTopSong['Full_Files']['SaveAsName']; ?>", "<?= $nationalTopSong["Song"]["provider_type"]; ?>",1);'>
                                                                     
                                                        </a>              
                                                         <![endif]>
                                                        <!--[if IE]>
                                                        <?php /*
                                                               <a id="song_download_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" 
                                                                    class="no-ajaxy top-10-download-now-button" 
                                                                    title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                    onclick='wishlistDownloadIEHome("<?php echo $nationalTopSong["Song"]['ProdID']; ?>", "0" , "<?php echo $nationalTopSong["Song"]["provider_type"]; ?>", "<?php echo $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?php echo $nationalTopSong['Full_Files']['SaveAsName']; ?>",1);' 
                                                                    href="javascript:void(0);"><button class="download-icon"></button></a>
                                                        */ ?>
                                                        <a id="song_download_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" 
                                                                    class="download-icon" 
                                                                    title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                    onclick='wishlistDownloadIEHome("<?php echo $nationalTopSong["Song"]['ProdID']; ?>", "0" , "<?php echo $nationalTopSong["Song"]["provider_type"]; ?>", "<?php echo $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?php echo $nationalTopSong['Full_Files']['SaveAsName']; ?>",1);' 
                                                                    ></a>
                                                        <![endif]-->                                                         
						</span>
                                                <span class="afterClick" id="downloading_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait.."); ?>
                                                <span id="wishlist_loader_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php echo $html->image('ajax-loader_black.gif'); ?></span> </a> </span>
					</form>
				</span>
			<?php
			} else { ?>
				<?php /*<a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><button class="download-icon song-downloaded"></button></a>*/ ?>
                <a class="download-icon song-downloaded" href='/homes/my_history' title="You have already downloaded this song. Get it from your recent downloads"></a>
			<?php
			}
		} else { ?>
			<?php /*<button class="download-icon download-limit-met"></button>*/ ?>
            <a class="download-icon download-limit-met" title="Your download limit has been met."></a>
		<?php
		}
	} else { ?>
		<a class="top-100-download-now-button" href="javascript:void(0);">
			<span> ( <?php
				if (isset($nationalTopSong['Country']['SalesDate'])) {
					echo date("F d Y", strtotime($nationalTopSong['Country']['SalesDate']));
				}
				?> )'>
				<button class="download-icon"></button>
			</span>
		</a>
	<?php
	}
?>
			<input type="hidden" id="<?= $nationalTopSong['Song']['ProdID'] ?>" value="song" data-provider="<?= $nationalTopSong['Song']['provider_type'] ?>"/>
			<?php /*<button class="wishlist-icon"></button> */ ?>
            <a class="wishlist-icon no-ajaxy" title="Add to Wishlist" hrefe="#"></a>
                        <?php    if ($this->Session->read('library_type') == 2 && $nationalTopSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopSong['Country']['StreamingStatus'] == 1) { ?>
                            <?php /*<button class="playlist-menu-icon"></button>*/ ?>
                            <a class="playlist-menu-icon no-ajaxy" href="#" title="Add to a Playlist or Create a New Playlist"></a> ?>
                            <ul>
                                <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>

                            </ul>                              
<?php                        
                        }
}                         if ($this->Session->read("patron"))
                        {
                            if ($this->Session->read('library_type') == 2 && $nationalTopSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopSong['Country']['StreamingStatus'] == 1)
                            {
                                if ('T' == $nationalTopSong['Song']['Advisory'])
                                {
                                    $song_title = $nationalTopSong['Song']['SongTitle'] . '(Explicit)';
                                }
                                else
                                {
                                    $song_title = $nationalTopSong['Song']['SongTitle'];
                                }
                                echo $this->Queue->getNationalsongsStreamNowLabel($nationalTopSong['Full_Files']['CdnPath'], $nationalTopSong['Full_Files']['SaveAsName'], $song_title, $nationalTopSong['Song']['ArtistText'], $nationalTopSong['Song']['FullLength_Duration'], $nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type']);
                            } 
                            else if ($nationalTopSong['Country']['SalesDate'] <= date('Y-m-d'))
                            {
                                echo $html->image('sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $nationalTopSong['Song']['ProdID'] . ', "' . base64_encode($nationalTopSong['Song']['provider_type']) . '", "' . $this->webroot . '");'));
                                echo $html->image('sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                echo $html->image('sample-stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));                                
                            }
                        }
                        ?>

                               
                                    <?php
                                    if (strlen($nationalTopSong['Song']['SongTitle']) >= 30)
                                    {
                                        $songTitle = $this->getTextEncode(substr($nationalTopSong['Song']['SongTitle'], 0, 30)) . "..";
                                    }
                                    else
                                    {
                                        $songTitle = $this->getTextEncode($nationalTopSong['Song']['SongTitle']);
                                    }

                                    if ('T' == $nationalTopSong['Song']['Advisory'])
                                    {
                                        if (strlen($songTitle) >= 20)
                                        {
                                            $songTitle = $this->getTextEncode(substr($nationalTopSong['Song']['SongTitle'], 0, 20)) . "..";
                                        }
                                        $songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';
                                    }
                                    ?>

                                    <div class="song-title">
                                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopSong['Song']['SongTitle'])); ?> " href="/artists/view/<?= base64_encode($nationalTopSong['Song']['ArtistText']); ?>/<?= $nationalTopSong['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopSong['Song']['provider_type']); ?>"><?php echo $this->getTextEncode($songTitle); ?></a>
                                    </div>                               
                                    <div class="artist-name">
                                            <a href="/artists/album/<?= base64_encode( $this->getTextEncode( $nationalTopSong['Song']['ArtistText'] ) ); ?>">
                                                <?php
                                                if ( strlen( $nationalTopSong['Song']['ArtistText'] ) > 30 ) {
                                                    echo $this->getValidText( $this->getTextEncode( substr( $nationalTopSong['Song']['ArtistText'], 0, 30 ) ) ) . "...";
                                                }
                                                else {
                                                echo $this->getValidText( $this->getTextEncode( $nationalTopSong['Song']['ArtistText'] ) );
                                                }
                                                ?>
                                            </a>
                                    </div>
                            </div>
                    <?php 
                            if( $count % 2 == 0 ) {
                    ?>
                    </li>
                    <?php
                            }
                    ?>
                    <?php
            }
    } else {
            ?>
            <li>No Songs Found.</li>
    <?php
            }
    ?>																											
            </ul>
    </div>
    <button class="left-scroll-button"></button>
    <button class="right-scroll-button"></button>	
</div>
</section>
<!-- Top Singles code end here -->


<section class="featured-artists" id="featured-artists-section">
    <h2>Featured Artists &amp; Composers</h2>
    <div class="featured-artists-grid clearfix" id="featured-artists-grid-div">
        <?php
        // $this->log("index.ctp featuredArtists start", "siteSpeed");  
        $count = 1;
        foreach ($featuredArtists as $k => $v)
        {
            //$albumArtwork = shell_exec(Configure::read('App.tokengen') . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
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
                <a href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>">
                    <?php echo $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])); ?>
                </a>
                <div class="featured-grid-menu">
                    <div class="featured-artist-name">
                        <?php echo $this->getTextEncode($ArtistText); ?>
                    </div>
                    <div class="featured-album-name">
                        <a  href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>">
                               <?php echo $this->getTextEncode($title); ?>
                        </a>
                    </div>
                    <div class="featured-artist-ctas">
                        <?php
                        if ($this->Session->read("patron"))
                        {
                            if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                            {
                                echo $this->Queue->getAlbumStreamLabel($v['albumSongs'][$v['Album']['ProdID']], 2);
                            }
                        }
                        ?>                     
                        <a title="More by <?php echo $this->getTextEncode($ArtistText); ?>" class="more-by-artist" 
                           href="/artists/album/<?php echo str_replace('/', '@', base64_encode($v['Album']['ArtistText'])); ?>/<?= base64_encode($v['Genre']['Genre']) ?>">
                               <?php echo $this->getTextEncode($ArtistText); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            if ($count == 20)
            {
                break;
            }
            $count++;
        }
        ?>
    </div>
    <span id="artist_loader" style="display:none;" >
        <img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif"  
             style="padding-left:115px;padding-bottom:25px;border:0;" alt=""/>
    </span>
</section>

<style>
    div[class*='page']   
    {
        display: none;
    }  
</style>
