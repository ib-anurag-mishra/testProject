<?php
/*
	 File Name : my_history.ctp 
	 File Description : View page for download history page
	 Author : m68interactive
 */
?>

<!-- new HTML -->

<section class="recent-downloads-page">
		
		<div class="breadcrumbs"><span>Home</span> > <span>Recent Downloads</span></div>
		<header class="clearfix">
			<h2>Downloads</h2>
			<div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
		</header>
		<div class="instructions">
			<p>
				Once in awhile Internet Service Providers or your computer may time out, and you could experience an incomplete or problem download. Freegal Music™ provides you with the opportunity to download previously downloaded songs again, without using up a personal download, and at no cost to your Library. To download a song again, from this week or last week of your initial downloads, press the download now link below. You may do this up to 2 additional times. Once you have downloaded a song twice from the Recent Downloads page, the song titles disappear from your list because they are no longer available to you.
			</p>
			
		</div>
		<nav class="recent-downloads-filter-container clearfix">
			<div class="date-filter-button filter"></div>
			<div class="song-filter-button filter"></div>
			<div class="music-filter-button tab"></div>
			<div class="video-filter-button tab"></div>
			<div class="artist-filter-button filter"></div>
			<div class="album-filter-button filter"></div>
			<div class="download-button filter"></div>
			
		</nav>
		<div class="recent-downloads-shadow-container">
			<div class="recent-downloads-scrollable">
				<div class="row-container">
				<?php
                if(count($downloadResults) != 0)
                {
                    //$i = 1;
                    foreach($downloadResults as $key => $downloadResult):
                    /*$class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }*/
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($downloadResult['Download']['created'])); ?></div>
					<div class="small-album-container">
						<img src="/img/playlist/small-album-cover.jpg" alt="small-album-cover" width="40" height="40" />
						<a class="preview" href="#"></a>
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($downloadResult['Download']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($downloadResult['Download']['track_title']).'">' .substr($downloadResult['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $downloadResult['Download']['track_title']; 
					 	}
					?>
                    </div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">Sticks and Stones</a></div>
					<div class="artist-name"><a href="#"><?php
						if (strlen($downloadResult['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['artist']).'">' .substr($downloadResult['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $downloadResult['Download']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<div class="wishlist-popover">
						<!--	
						<a class="remove-song" href="#">Remove Song</a>
						<a class="make-cover-art" href="#">Make Cover Art</a>
						-->
						<a class="add-to-playlist" href="#">Add To Queue</a>
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
						<div class="playlist-options">
							<ul>
								<li><a href="#">Create New Playlist</a></li>
								<li><a href="#">Playlist 1</a></li>
								<li><a href="#">Playlist 2</a></li>
								<li><a href="#">Playlist 3</a></li>
								<li><a href="#">Playlist 4</a></li>
								<li><a href="#">Playlist 5</a></li>
								
								
							</ul>
						</div>
						
					</div>
					<div class="download">
                    <a href="#">
                        <p>
							<span class="beforeClick" id="download_song_<?php echo $downloadResult['Download']['ProdID']; ?>">
								<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
									<a href='#' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download');?></a>
								<?php } else {?>
								<!--[if IE]>
									<a onclick='return historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download');?></a> 										
								<![endif]-->
								<?php } ?>
							</span>
							<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
							<span id="download_loader_<?php echo $downloadResult['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
						</p>
                    </a></div>
				</div>
				<?php
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded songs from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>
		(this is the html for the videos)
		<div class="recent-video-downloads-shadow-container">
			<div class="recent-video-downloads-scrollable">
				<div class="row-container">
				<?php
                if(count($videoDownloadResults) != 0)
                {
                    //$i = 1;
                    foreach($videoDownloadResults as $key => $videoDownloadResult):
                    /*$class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }*/
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($videoDownloadResult['Videodownload']['created'])); ?></div>
					<div class="small-album-container">
						<img src="/img/my-wishlist/video-cover.jpg" alt="video-cover" width="67" height="40" />
						<!-- <a class="preview" href="#"></a> -->
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($videoDownloadResult['Videodownload']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($$videoDownloadResult['Videodownload']['track_title']).'">' .substr($$videoDownloadResult['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $videoDownloadResult['Videodownload']['track_title']; 
					 	}
					?>
                    </div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#"></a></div>
					<div class="artist-name"><a href="#">
                    <?php
						if (strlen($videoDownloadResult['Videodownload']['artist']) >= 19) {
							echo '<span title="'.htmlentities($videoDownloadResult['Videodownload']['artist']).'">' .substr($downloadResult['Videodownload']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $videoDownloadResult['Videodownload']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download"><a href="#">Download</a></div>
				</div>
				<?php
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded songs from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>


	</section>