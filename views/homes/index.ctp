<?php
echo $session->flash();
ini_set( 'session.cookie_lifetime', '0' ); // 0 means "until the browser is closed
?>
<section class="top-albums">
	<header> <h2>Top Albums</h2> </header>
	<div class="top-albums-carousel-container">
		<div class="top-albums-carousel carousel">
			<ul class="clearfix">
				<?php
				$count = 1;
				if ( isset( $nationalTopAlbums ) && is_array( $nationalTopAlbums ) && count( $nationalTopAlbums ) > 0 ):
					foreach ( $nationalTopAlbums as $key => $value ):

						$title 		= $this->Home->trimString( $value['Album']['AlbumTitle'], 22 );
						$artistText = $this->Home->trimString( $value['Album']['ArtistText'], 22 );
						
						$action = array( 'controller' => 'artists', 
										 'action' => 'view', 
										  base64_encode( $value['Album']['ArtistText'] ), 
										  $value['Album']['ProdID'], 
										  base64_encode( $value['Album']['provider_type'] ) 
									);
						$style = array( 'class' => 'first', 'escape' => false );
				?>
						<li>
							<div class="album-cover-container">
								<?= $html->link( $html->image( $value['topAlbumImage'] ), $action, $style ); ?>
								<div class="ranking"> <?= $count; ?> </div>
								<?php 
									if ( isset( $patron ) && ! empty( $patron ) ): 
								?>
										<input type="hidden" id="<?= $value['Album']['ProdID'] ?>" value="album" data-provider="<?= $value["Album"]["provider_type"] ?>" />
								<?php
										if ( isset( $libraryType ) && $libraryType == 2 && ! empty( $value['albumSongs'][$value['Album']['ProdID']] ) ):
											echo $this->Queue->getAlbumStreamLabel( $value['albumSongs'][$value['Album']['ProdID']], 1 );
											echo $this->Html->link('', '#', array( 'class' => 'playlist-menu-icon toggleable no-ajaxy', 'title' => 'Add to a Playlist or Create a New Playlist'));
								?>
											<ul> <li> <?php echo $this->Html->link('Create New Playlist...', '#', array('class' => 'create-new-playlist'));?> </li> </ul>
								<?php 
										endif;
										echo $this->Html->link('', '#', array( 'class' => 'wishlist-icon toggleable no-ajaxy', 'title' => 'Add to Wishlist'));
									endif;
								?>
							</div>
							<div class="album-info">
								<p class="title">
								<?php 
									$hrefTitle    = $this->getValidText( $value['Album']['AlbumTitle'] );
									$hrefArtist	  = base64_encode( $value['Album']['ArtistText'] );
									$providerType = base64_encode( $value['Album']['provider_type'] );

									echo $this->Html->link( $this->getTextEncode( $title ), array( 'controller' => 'artists', 'action' => 'view', $hrefArtist, $value['Album']['ProdID'], $providerType ), array( 'title' => $hrefTitle ) );
								?>
								</p>
								<p class="artist">
								<?php 
									$hrefTitle = $this->getValidText( $value['Album']['ArtistText'] );
								 	echo $this->Html->link( $this->getTextEncode($artistText), array( 'controller' => 'artists', 'action' => 'album', str_replace('/', '@', $hrefArtist ), base64_encode($value['Genre']['Genre']) ), array('title' => $hrefTitle ));
								?>
								</p>
							</div>
						</li>
				<?php
						if ( $count === 25 ) {
							break;
						}
		
						$count++;
					endforeach;
				else:
					echo '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
				endif;
				?>
			</ul>
		</div>
		<button class="left-scroll-button"></button>
		<button class="right-scroll-button"></button>
	</div>
</section>

<!-- Top Singles code start here -->
<section class="top-songs">
	<header> <h2>Top Singles</h2> </header>
	<div class="top-singles-carousel-container">
		<div class="top-singles-carousel carousel">
			<ul>
				<?php
				if ( isset( $top_singles ) && is_array( $top_singles ) && count( $top_singles ) > 0 ):

					$count = 0;
					foreach( $top_singles as $nationalTopSong ):

						$count++;
						if( $count % 2 != 0 ):
				?>
							<li>
				  <?php endif; ?>
								<div class="top-single-container">
									<input type="hidden" id="<?= $nationalTopSong['Song']['ProdID'] ?>" value="song" data-provider="<?= $nationalTopSong['Song']['provider_type'] ?>" />
									<div class="single-bar"></div>
									<div class="ranking"> <?=$count;?> </div>
						<?php if ( ( isset( $patron ) && ! empty( $patron ) ) ):
								if ( $nationalTopSong['Country']['SalesDate'] <= date('Y-m-d' ) ):
									if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):
										if ( isset( $downloadVariArray ) && ! empty( $downloadVariArray ) ) {
											$downloadsUsed = $this->Download->getDownloadResults( $nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type'] );
										} else {
											$downloadsUsed = $this->Download->getDownloadfind( $nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type'], $libId, $patId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );
										}

										if ( !( $downloadsUsed > 0 ) ): ?>
											<div class="top-100-download-now-button">
												<form method="Post" id="form<?= $nationalTopSong["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
													<input type="hidden" name="ProdID" value="<?= $nationalTopSong["Song"]["ProdID"]; ?>" />
													<input type="hidden" name="ProviderType" value="<?= $nationalTopSong["Song"]["provider_type"]; ?>" />
													<span class="beforeClick" style="cursor: pointer;" id="wishlist_song_<?= $nationalTopSong["Song"]["ProdID"]; ?>">
														<?php 
															$hrefTitle = "IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.";
														?>
														<![if !IE]>
															<span class="download-icon" title=<?=$hrefTitle; ?> onclick='return wishlistDownloadOthersHome("<?= $nationalTopSong["Song"]['ProdID']; ?>", "0", "<?= $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?= $nationalTopSong['Full_Files']['SaveAsName']; ?>", "<?= $nationalTopSong["Song"]["provider_type"]; ?>", 1 );'> </span>
														<![endif]>
														<!--[if IE]>
                                                        	<span class="download-icon" title=<?=$hrefTitle; ?> id="song_download_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" onclick='wishlistDownloadIEHome("<?php echo $nationalTopSong["Song"]['ProdID']; ?>", "0" , "<?php echo $nationalTopSong["Song"]["provider_type"]; ?>", "<?php echo $nationalTopSong['Full_Files']['CdnPath']; ?>", "<?php echo $nationalTopSong['Full_Files']['SaveAsName']; ?>",1);'></span>
                                                        <![endif]-->
													</span>
													<span class="afterClick" id="downloading_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" style="display: none;">
														<span class="add-to-wishlist"> 
															<span id="wishlist_loader_<?php echo $nationalTopSong["Song"]["ProdID"]; ?>" style="float: right; padding-right: 8px; padding-top: 2px;"><?php echo $html->image('ajax-loader_black.gif'); ?> </span>
														</span>
													</span>
												</form>
											</div>
								<?php else:
											echo $this->Html->link('', array('controller' => 'homes', 'action' => 'my_history'), array('class' => 'download-icon song-downloaded', 'title' => 'You have already downloaded this song. Get it from your recent downloads'));
									  endif;
								else: ?>
									<span class="download-icon download-limit-met" title="Your download limit has been met."></span>
								<?php
								endif;
							else: 
								$salesDate = '';
								if ( isset( $nationalTopSong['Country']['SalesDate'] ) ) {
									$salesDate = date( "F d Y", strtotime($nationalTopSong['Country']['SalesDate'] ) );
								}

								echo $this->Html->link( $this->Html->tag('span', '(' . $salesDate . ')' . $this->Html->tag('button', '', array('class' => 'download-icon') ) ), 'javascript:void(0)', array('class' => 'top-100-download-now-button', 'escape' => false ) );
							endif;
							?>
								<span class="wishlist-icon no-ajaxy" title="Add to Wishlist"></span>
								<?php
								if ( isset( $libraryType ) && $libraryType == 2 && $nationalTopSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopSong['Country']['StreamingStatus'] == 1 ): 
									echo $this->Html->link('', '#', array('title' => 'Add to a Playlist or Create a New Playlist', 'class' => 'playlist-menu-icon no-ajaxy'));	
								?>
										<ul> <li>
											<?php echo $this->Html->link('Create New Playlist...', '#', array('class' => 'create-new-playlist'));?>
										</li> </ul>
								<?php                        
							  		endif;
								endif;
						   if ( isset( $patron ) && ! empty( $patron ) ) {
							if ( isset( $libraryType ) && $libraryType == 2 && $nationalTopSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopSong['Country']['StreamingStatus'] == 1 ) {
								
								$song_title = $this->Home->explicitContent( $nationalTopSong['Song']['Advisory'], $nationalTopSong['Song']['SongTitle'] );
								echo $this->Queue->getNationalsongsStreamNowLabel($nationalTopSong['Full_Files']['CdnPath'], $nationalTopSong['Full_Files']['SaveAsName'], $song_title, $nationalTopSong['Song']['ArtistText'], $nationalTopSong['Song']['FullLength_Duration'], $nationalTopSong['Song']['ProdID'], $nationalTopSong['Song']['provider_type']);

							} else if ( $nationalTopSong['Country']['SalesDate'] <= date( 'Y-m-d' ) ) {
								echo $html->image('sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $count, "onClick" => 'playSample(this, "' . $count . '", ' . $nationalTopSong['Song']['ProdID'] . ', "' . base64_encode($nationalTopSong['Song']['provider_type']) . '", "' . $this->webroot . '");'));
								echo $html->image('sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview play-btn", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $count));
								echo $html->image('sample-stop.png', array("alt" => "Stop Sample", "class" => "preview play-btn", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $count, "onClick" => 'stopThis(this, "' . $count . '");'));
							}
						}

						if ( 'T' == $nationalTopSong['Song']['Advisory'] ) {
							$size = 19;
						} else {
							$size = 35;
						}

						$songTitle = $this->Home->trimString( $nationalTopSong['Song']['SongTitle'], $size );
						$songTitle = $this->getTextEncode( $songTitle );
						$songTitle = $this->Home->explicitContent( $nationalTopSong['Song']['Advisory'], $songTitle, true );
						?>
						<div class="song-title">
						<?php 
							$hrefTitle    = $this->getValidText( $nationalTopSong['Song']['SongTitle'] );
							$hrefArtist   = base64_encode( $nationalTopSong['Song']['ArtistText'] );
							$providerType = base64_encode( $nationalTopSong['Song']['provider_type'] );
							
							echo $this->Html->link( $songTitle, array('controller' => 'artists', 'action' => 'view', $hrefArtist, $nationalTopSong['Song']['ReferenceID'], $providerType), array('title' => $hrefTitle, 'escape' => false ) );
						?>
						</div>
						<div class="artist-name">
							<?php echo $this->Html->link( $this->getValidText( $this->Home->trimString( $nationalTopSong['Song']['ArtistText'], 35 ) ), array('controller' => 'artists', 'action' => 'album', base64_encode( $this->getTextEncode( $nationalTopSong['Song']['ArtistText'] ) ), 'escape' => false ) );?>
						</div>
					</div>
					<?php if( $count % 2 == 0 ): ?>
							</li>
					<?php endif; ?>
			<?php
						  if( $count === 50 ) {
							break;
						  }
					endforeach;
				else:
			?>
				<li>No Songs Found.</li>
		<?php endif; ?>
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
		$count = 1;

		foreach ( $featuredArtists as $k => $v ):
			$artistText = $this->Home->trimString( $v['Featuredartist']['artist_name'], 22 );
		?>
			<div class="featured-grid-item">
				<?php echo $this->Html->link($html->image(Configure::read('App.CDN') . 'featuredimg/' . $v['Featuredartist']['artist_image'], array("height" => "77", "width" => "84", "alt" => $artistText)), array('controller' => 'artists', 'action' => 'album', base64_encode($v['Featuredartist']['artist_name'])), array('escape' => false));?>
				<div class="featured-grid-menu">
					<div class="featured-artist-name"> <?php echo $this->getTextEncode($artistText); ?> </div>
					<div class="featured-artist-ctas">
						<?php
							if ( isset( $patron ) && ! empty( $patron ) ) {
								if ( isset( $libraryType ) && $libraryType == 2 && !empty($v['albumSongs'] ) ) {
									echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'], 3 );
								}
							}

							echo $this->Html->link( $this->getTextEncode($ArtistText), array('controller' => 'artists', 'action' => 'album', base64_encode($v['Featuredartist']['artist_name'] ) ), array('title' => 'More by' . $this->getTextEncode($ArtistText), 'class' => 'more-by-artist') );
						?>
					</div>
				</div>
			</div>
			<?php
			if ( $count == 20 ) {
				break;
			}
	
			$count++;
		endforeach;
		?>
	</div>
	<span id="artist_loader" style="display: none;"> 
		<img src="<? echo $this->webroot; ?>app/webroot/img/aritst-ajax-loader.gif" style="margin: 20px auto" alt="" />
	</span>
</section>