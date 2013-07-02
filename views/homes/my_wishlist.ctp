<?php
/*
	 File Name : my_wishlist.ctp 
	 File Description : View page for wishlist information
	 Author : m68interactive
 */
?>
<section class="my-wishlist-page">
		
		<div class="breadcrumbs"><?php
	$html->addCrumb('My Wishlist', '/homes/my_wishlist');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?></div>
		<header class="clearfix">
			<h2>My Wishlist</h2>
			<div class="faq-link">Need help? Visit our <a href="#">FAQ section.</a></div>
		</header>
		<div class="instructions">
			<p>
				In the event that your library exceeds its download budget for the week, you will see "add to wishlist" in place of the "download now" command. Adding your music to the wishlist will place you in a "first come, first serve" line to get more music when it becomes available, which is at midnight Sunday Eastern Time (U.S.). At that point your music is on hold for you for 24 hours (so no need to set your alarm clock) for you to proactively download. You should visit the Wishlist area on the top part of the home page to see the music that you requested, and if it is available.
			</p>
			<p>
				If you do not see the "download now" command in the Wish List area, it means so many people were waiting in line that you need to check back on a subsequent Monday.
			</p>
		</div>
		<nav class="my-wishlist-filter-container clearfix">
			<div class="date-filter-button filter"></div>
			<div class="song-filter-button filter"></div>
			<div class="music-filter-button tab"></div>
			<div class="video-filter-button tab"></div>
			<div class="artist-filter-button filter"></div>
			<div class="album-filter-button filter"></div>
			<div class="download-button filter"></div>
			
		</nav>
		<div class="my-wishlist-shadow-container">
			<div class="my-wishlist-scrollable">
				<div class="row-container">
				<?php
	if(count($wishlistResults) != 0)
	{
		
		foreach($wishlistResults as $key => $wishlistResult):
			
	?>
				<div class="row clearfix">
					<div class="date">2013-06-13</div>
					<div class="small-album-container">
						<img src="../img/playlist/small-album-cover.jpg" alt="small-album-cover" width="40" height="40" />
						<a class="preview" href="#"></a>
					</div>
					<div class="song-title">Grow Up</div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">
                                         <?php
						if (strlen($wishlistResult['Wishlist']['album']) >= 24) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResult['Wishlist']['album'])).'">' .$this->getTextEncode(substr($wishlistResult['Wishlist']['album'], 0, 24)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResult['Wishlist']['album']);
						}
						
                                          ?>
                                            </a></div>
					<div class="artist-name"><a href="#">
                                            <?php
						if (strlen($wishlistResult['Wishlist']['artist']) >= 19) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResult['Wishlist']['artist'])).'">' .$this->getTextEncode(substr($wishlistResult['Wishlist']['artist'], 0, 19)) . '...</span>';
						} else {
							$ArtistName = $wishlistResult['Wishlist']['artist'];
							echo $this->getTextEncode($ArtistName);
						}
						
                                            ?>
                                            </a></div>
					
					<div class="wishlist-popover">
						<!--	
						<a class="remove-song" href="#">Remove Song</a>
						<a class="make-cover-art" href="#">Make Cover Art</a>
						-->
                                            <?php if( $this->Session->read('library_type') == 2 ){ ?>
                                                <a class="add-to-playlist" href="#">Add To Queue</a>
                                            <?php } ?>
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						<?php if( $this->Session->read('library_type') == 2 ){ ?>
						<div class="playlist-options">
							<ul>
								<li><a href="#">Create New Queue</a></li>
								<li><a href="#">Playlist 1</a></li>
								<li><a href="#">Playlist 2</a></li>
								<li><a href="#">Playlist 3</a></li>
								<li><a href="#">Playlist 4</a></li>
								<li><a href="#">Playlist 5</a></li>
								
								
							</ul>
						</div>
						<?php } ?>
					</div>
					<div class="download">
                                            
                                        <?php										
						$productInfo = $song->getDownloadData($wishlistResult['Wishlist']['ProdID'],$wishlistResult['Wishlist']['provider_type']);
						if($libraryDownload == '1' && $patronDownload == '1'){
							$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
							<p>
								<span class="beforeClick" id="wishlist_song_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>">
									<![if !IE]>
										<a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>" , "<?php echo $wishlistResult['Wishlist']["provider_type"]; ?>");'><?php __('Download Now');?></a>
									<![endif]>
									<!--[if IE]>
									<a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadIE("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>" , "<?php echo $wishlistResult['Wishlist']["provider_type"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
									<![endif]-->							
								</span>
								<span class="afterClick" id="downloading_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:left"><?php __('Please Wait...');?></span>
								<span id="wishlist_loader_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
					<?php	}
						else{ ?>
							<p><?php __("Limit Met");?></p>
						<?php
						}
					?>
                                            
                                        </div>
				</div>
        <?php 

        endforeach;

        }else{
            
            echo 	'<tr><td width="280" valign="top"><p><?php __("You have no songs in your wishlist.");?></p></td></tr>';
            
        }


        ?>
				</div>
			</div>
		</div>
<!--		(this is the html for the videos)
		<div class="my-video-wishlist-shadow-container">
			<div class="my-video-wishlist-scrollable">
				<div class="row-container">
				<?php
				for($b=0;$b<28;$b++) {
				?>
				
				<div class="row clearfix">
					<div class="date">2013-06-13</div>
					<div class="small-album-container">
						<img src="../img/my-wishlist/video-cover.jpg" alt="video-cover" width="67" height="40" />
						 <a class="preview" href="#"></a> 
					</div>
					<div class="song-title">Grow Up</div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">Sticks and Stones</a></div>
					<div class="artist-name"><a href="#">Cher Lloyd</a></div>
					
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
				}
				?>
				</div>
			</div>
		</div>-->


	</section>
