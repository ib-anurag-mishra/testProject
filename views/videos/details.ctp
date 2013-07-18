<section class="individual-videos-page">
		<div class="breadcrumbs"><span>Home</span> > <span>Wishlist</span></div>
		<div class="hero-container clearfix">
			<div class="hero-image-container">
                                <?php
                                        $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$VideosData[0]['File']['CdnPath']."/".$VideosData[0]['File']['SourceURL']);
                                        $videoImage = Configure::read('App.Music_Path').$videoArtwork;


                                        

                                ?>
				<img src="<?php echo $videoImage;?>" alt="<?php echo $VideosData[0]['Video']['VideoTitle']; ?>" width="555" height="323" />
				<a class="download-now-button" href="#">Download Now</a>
				<a class="add-to-playlist-button" href="#"></a>
				<div class="wishlist-popover">
					<a class="add-to-wishlist" href="#">Add To Wishlist</a>
					
					<div class="share clearfix">
						<p>Share via</p>
						<a class="facebook" href="#"></a>
						<a class="twitter" href="#"></a>
					</div>
					
				</div>
				
			</div>
			<div class="hero-detail">
				
				<h2 class="song-title">
					<?php echo $VideosData[0]['Video']['VideoTitle']; ?>
				</h2>
				<h3 class="artist-name">
					<?php echo $VideosData[0]['Video']['ArtistText']; ?>
				</h3>
				<?php
                                        $duration       =    $VideosData[0]['Video']['FullLength_Duration']; 
                                        $duration_arr   = explode(":", $duration);
                                ?>
				<div class="release-information">
					<p>Release Information</p>
					<div class="release-date">Date: <?php echo date("M d, Y", strtotime($VideosData[0]['Video']['CreatedOn'])); ?></div>
					<div class="video-duration">Duration: <?php echo $duration_arr[0]." min ".$duration_arr[1]." sec"; ?></div>
					<div class="video-size">Size: 67.2 MB</div>
				</div>
			</div>
			
		</div>
		<section class="more-videos">
			<header>
				<h2>More Videos By <?php echo $VideosData[0]['Video']['ArtistText']; ?></h2>
			</header>
			<div class="more-videos-scrollable horiz-scroll">
				<ul>
					<?php						
						
						foreach($MoreVideosData as $key => $value)
						{		

                                                    //echo "<pre>"; print_r($value);
                                                    $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                    $videoImage = Configure::read('App.Music_Path').$videoArtwork;
								?>								
								<li>
									<div class="video-thumb-container">
										<img class="lazy" src="/app/webroot/img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="274" height="162" />
										<a class="download-now-button" href="#">Download Now</a>
										<a class="add-to-playlist-button" href="#"></a>
										<div class="wishlist-popover">
											
											<a class="add-to-wishlist" href="#">Add To Wishlist</a>
											
											<div class="share clearfix">
												<p>Share via</p>
												<a class="facebook" href="#"></a>
												<a class="twitter" href="#"></a>
											</div>
											
										</div>
										
									</div>
									<div class="song-title">
										<a href="#"><?php 
                                                                                                if (strlen($value['Video']['VideoTitle']) >= 35 ) {
                                                                                                            $VideoTitle = $this->getTextEncode(substr($value['Video']['VideoTitle'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $VideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
                                                                                                    }    
                                                                                                echo $VideoTitle; 
                                                                                            ?>
                                                                                </a>
									</div>
									<div class="artist-name">										
                                                                                <a href="#">
                                                                                <?php 
                                                                                        if (strlen($value['Video']['ArtistText']) >= 35 ) {
                                                                                                    $VideoArtist = $this->getTextEncode(substr($value['Video']['ArtistText'], 0, 35)) . "..";
                                                                                            } else {
                                                                                                    $VideoArtist = $this->getTextEncode($value['Video']['ArtistText']);
                                                                                            }    
                                                                                        echo $VideoArtist; 
                                                                                 ?>
                                                                                </a>
									</div>
								</li>
								
								
								<?php
							
						
						}
					?>
					
				</ul>
			</div>
		</section>
		
		<section class="top-videos">
			<header>
				<h2>Top <span><?php echo $VideoGenre; ?></span> Videos</h2>
			</header>
			<div class="top-videos-scrollable horiz-scroll">
			<ul>
				<?php
					foreach($TopVideoGenreData as $key => $value)
                                        {

                                              //  echo "<pre>"; print_r($value);
                                                $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                $videoImage = Configure::read('App.Music_Path').$videoArtwork;

							?>
							
							<li>
								<div class="video-thumb-container">
									<img class="lazy" src="/app/webroot/img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="274" height="162" />
									<a class="download-now-button" href="#">Download Now</a>
									<a class="add-to-playlist-button" href="#"></a>
									<div class="wishlist-popover">
										
										
										
										
										<a class="add-to-wishlist" href="#">Add To Wishlist</a>
										
										<div class="share clearfix">
											<p>Share via</p>
											<a class="facebook" href="#"></a>
											<a class="twitter" href="#"></a>
										</div>
										
									</div>
									
								</div>
								<div class="song-title">
									<a href="#"><?php echo $value['Video']['VideoTitle']; ?></a>
								</div>
								<div class="artist-name">
									<a href="#"><?php echo $value['Video']['ArtistText']; ?></a>
								</div>
							</li>
							
							
							<?php
					
					
					}
				?>
				
			</ul>
		</div>
		

		</section>
		<section class="recommended-for-you">
			
		</section>
		
	</section>