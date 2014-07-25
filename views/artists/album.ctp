<section class="artist-page">
	<div class="breadcrumbs">
		<?php
			if ( isset( $genre ) ) {
				$genre_text_conversion = array(
						"Children's Music" 		=> "Children's",
						"Classic" 		   		=> "Soundtracks",
						"Comedy/Humor" 	   		=> "Comedy",
						"Country/Folk" 	   		=> "Country",
						"Dance/House"			=> "Dance",
						"Easy Listening Vocal"  => "Easy Listening",
						"Easy Listening Vocals" => "Easy Listening",
						"Folk/Blues" 			=> "Folk",
						"Folk/Country" 			=> "Folk",
						"Folk/Country/Blues" 	=> "Folk",
						"Hip Hop Rap" 			=> "Hip-Hop Rap",
						"Rap/Hip-Hop" 			=> "Hip-Hop Rap",
						"Rap / Hip-Hop" 		=> "Hip-Hop Rap",
						"Jazz/Blues" 			=> "Jazz",
						"Kindermusik" 			=> "Children's",
						"Miscellaneous/Other" 	=> "Miscellaneous",
						"Other" 				=> "Miscellaneous",
						"Age/Instumental" 		=> "New Age",
						"Pop / Rock" 			=> "Pop/Rock",
						"R&B/Soul" 				=> "R&B",
						"Soundtracks" 			=> "Soundtrack",
						"Soundtracks/Musicals" 	=> "Soundtrack",
						"World Music (Other)" 	=> "World Music"
				);

				$genre_crumb_name = isset( $genre_text_conversion[trim( $genre )] ) ? $genre_text_conversion[trim( $genre )] : trim( $genre );

				$html->addCrumb( __( 'All Genre', true ), '/genres/view/' );

				if ( $genre_crumb_name != "" ) {
					$html->addCrumb( $this->getTextEncode( $genre_crumb_name ), '/genres/view/?genre=' .$genre_crumb_name );
				}

				echo $html->getCrumbs(' > ', __('Home', true), '/homes');
				echo " > ";

				if ( $this->getTextEncode( $artisttext ) ) {
					$artisttext = $this->getTextEncode( $artisttext );
				}

				if ( strlen( $artisttext ) >= 30 ) {
					$artisttext = substr( $artisttext, 0, 30 ) . '...';
				}
				
				echo $artisttext;
			} else {
				echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
				echo " > ";
				echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
				echo " > ";

	            if( $this->getTextEncode( $artisttext ) ){
					$artisttext = $this->getTextEncode($artisttext);
				}

				if ( strlen( $artisttext ) >= 30 ) {
					$artisttext = substr( $artisttext, 0, 30 ) . '...';
				}

				echo $artisttext;
			}
		?>
	</div>
	<header class="clearfix">
		<?php if ( isset( $artisttitle ) ): ?>
				<h2>
		<?php
				if ( $this->getTextEncode( $artisttitle ) ) {
					echo $this->getTextEncode( $artisttitle );
				} else {
					echo $artisttitle;
				}
		?>
				</h2>
		<?php endif; ?>
		<div class="faq-link"> Need help? Visit our <?php echo $this->Html->link( 'FAQ section.', array( 'controller' => 'questions', 'action' => 'index' ) );?></div>
	</header>

	<!-- Album Section -->
	<?php
		if ( isset( $albumData ) && is_array( $albumData ) && count( $albumData ) > 0 ):
			$albumFlag = 0;
	?>
			<h3>Albums</h3>
			<div class="album-shadow-container">
				<div class="album-scrollable horiz-scroll">
					<ul style="width: 4500px">
					<?php
						foreach ( $albumData as $album ):

							//hide album if library block the explicit content
							if ( ( $this->Session->read( 'block' ) == 'yes' ) && ( $album['Album']['Advisory'] == 'T' ) ) {
								continue;
							}
					?>
							<li>
								<div class="album-container">
								<?php  
									$albumArtwork = $this->Token->artworkToken( $album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL'] ); 
									echo $this->Html->link( $this->Html->image( Configure::read('App.Music_Path') . $albumArtwork, array( 'width' => 162, 'height' => 162 ) ), array( 'controller' => 'artists', 'action' => 'view', str_replace('/', '@', base64_encode($artisttext)), $album['Album']['ProdID'], base64_encode($album['Album']['provider_type']) ), array( 'escape' => false) );

									if ( $libraryType == 2 && !empty( $album['albumSongs'][$album['Album']['ProdID']] ) && isset( $patronId ) ):
										echo $this->Queue->getAlbumStreamLabel($album['albumSongs'][$album['Album']['ProdID']]);
										echo $this->Html->link( '', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy'));				
								?>
										<div class="wishlist-popover">
											<input type="hidden" id="<?= $album['Album']['ProdID'] ?>" value="album" />
											<?php echo $this->Html->link( 'Add To Playlist', 'javascript:void(0)', array( 'class' => 'add-to-playlist' ) );?>
										</div>
							<?php endif; ?>
								</div>
								<div class="album-title">
								<?php
									$albumTitle = $album['Album']['AlbumTitle'];
									if (strlen( $albumTitle ) >= 50) {
										$albumTitle = substr($albumTitle, 0, 50) . '...';
									}
			
									echo $this->Html->link( $this->Html->tag( 'b', $this->getTextEncode( $albumTitle ) ), array( 'controller' => 'artists', 'action' => 'view', str_replace('/', '@', base64_encode($album['Album']['ArtistText'])), $album['Album']['ProdID'], base64_encode($album['Album']['provider_type']) ), array( 'title' => $this->getTextEncode($album['Album']['AlbumTitle']), 'escape' => false) );
								?>
								</div>
								<div class="genre">
									<?php
										echo __('Genre') . ": " . $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', '?genre='.$album['Genre']['Genre']), array("title" => $this->getTextEncode($album['Genre']['Genre']))) . '<br />';

										if ($album['Album']['ArtistURL'] != '') {
											echo $ArtistURL = $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank', 'style' => 'word-wrap:break-word;word-break:break-word;width:160px;'));
											echo '<br />';
										}
		
										if ($album['Album']['Advisory'] == 'T') {
											echo '<span class="explicit"> (Explicit)</span>';
											echo '<br />';
										}
									?>
								</div>
								<div class="label">
									<?php
										if ($album['Album']['Label'] != '') {
											echo __("Label") . ': ' . $this->getTextEncode($album['Album']['Label']);
											echo '<br />';
										}
		
										if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {
											echo $this->getTextEncode($album['Album']['Copyright']);
										}
									?>
								</div>
							</li>
				  <?php endforeach; ?>
					</ul>
				</div>

				<div class="paging">
					<?php
					echo $paginator->prev('<< ' . __('Previous ', true), null, null, array('class' => 'disabled'));
					echo $paginator->numbers(array('separator' => ' '));
					echo $paginator->next(__(' Next >>', true), null, null, array('class' => 'disabled'));
					?>
				</div>
			</div>
	<?php
		else:
			$albumFlag = 1;
		endif;
	?>

	<!-- Videos Section  -->
	<?php
		if ( isset( $artistVideoList ) && is_array($artistVideoList) && count($artistVideoList) > 0 ):
			$videoFlag = 0;
	?>
			<h3>Videos</h3>
			<div class="videos-shadow-container">
				<div class="videos-scrollable horiz-scroll">
					<ul style="width: 15000px;">
					<?php 
						foreach ( $artistVideoList as $value ): 
					?>
							<li>
								<div class="video-container">
								<?php 
									echo $this->Html->link( $this->Html->image( trim($value['videoAlbumImage']), array( 'height' => 162, 'width' => 272, 'alt' => 'jlo' ) ), array( 'controller' => 'videos', 'action' => 'details', $value["Video"]["ProdID"] ), array('escape' => false) );

									if ( isset( $patronId ) ):
										if ($value['Country']['SalesDate'] <= date('Y-m-d')):
											if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1'):

												$productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
												$downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value["Video"]["provider_type"], $libraryId, $patronId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));

												if ( !( $downloadsUsed > 0 ) ):
													$title = 'IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.';
								?>
													<span class="top-100-download-now-button">
														<form method="Post" id="form<?php echo $value['Video']['ProdID']; ?>" action="/videos/download" class="suggest_text1">
															<input type="hidden" name="ProdID" value="<?php echo $value['Video']['ProdID']; ?>" /> 
															<input type="hidden" name="ProviderType" value="<?php echo $value['Video']['provider_type']; ?>" /> 
															<span class="beforeClick" id="download_video_<?php echo $value['Video']['ProdID']; ?>"> 
																<![if !IE]>
																	<?php $this->Html->link('Download Now', 'javascript:void(0)', array( 'title' => $title, 'class' => 'top-10-download-now-button no-ajaxy', 'onclick' => "return wishlistVideoDownloadOthersToken('{$value['Video']['ProdID']}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$value['Video']['provider_type']}')" ));?>
																<![endif]>
																<!--[if IE]>
																	<?php $this->Html->link('Download Now', 'javascript:void(0)', array( 'title' => $title, 'class' => 'top-10-download-now-button no-ajaxy', 'onclick' => "wishlistVideoDownloadIEToken('{$value['Video']['ProdID']}', '0', '{$value['Video']['provider_type']}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')" ));?>
                                                                <![endif]-->
															</span>
															<span class="afterClick" id="vdownloading_<?php echo $value['Video']['ProdID']; ?>" style="display: none;">
																<label class="top-10-download-now-button"><?php __('Please Wait...&nbsp&nbsp'); ?> </label>
															</span>
															<span id="vdownload_loader_<?php echo $value['Video']['ProdID']; ?>" style="display: none; float: right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?> </span>
														</form>
													</span>
										  <?php 
												else: 
										  			echo $this->Html->link( 'Downloaded', array( 'controller' => 'homes', 'action' => 'my_history' ), array( 'title' => 'You have already downloaded this song. Get it from your recent downloads', 'class' => 'top-100-download-now-button top-10-download-now-button', 'style' => 'width: 120px; cursor: pointer;' ) );
												endif;
											else:
												if ( isset( $libraryDownload ) && $libraryDownload != '1') {

													$libraryInfo = $library->getLibraryDetails( $libraryId );
													$wishlistCount = $wishlist->getWishlistCount();

													if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
														echo $this->Html->link('Limit Met', 'javascript:void(0)', array('class' => 'top-10-download-now-button'));
													} else {
														$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
														echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value['Video']["provider_type"]);
													}
												} else {
													echo $this->Html->link('Limit Met', 'javascript:void(0)', array('class' => 'top-10-download-now-button'));
												}
											endif;
										else:
											if (isset($value['Country']['SalesDate'])) {
												$salesDate = date("F d Y", strtotime($value['Country']['SalesDate']));
											}
											echo $this->Html->link( $this->Html->tag('span', 'Comming Soon', array('title' => 'Comming Soon (' . $salesDate . ')')), 'javascript:void(0)', array('class' => 'top-100-download-now-button'));
										endif;
									endif;

									if ( isset( $patronId ) ):
										echo $this->Html->link('', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy'));
							?>
										<div class="wishlist-popover">
											<?php
												$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
												echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value['Video']["provider_type"]);
											?>
										</div>
							<?php 	endif; ?>
								</div>
								<?php $title_song_replace = str_replace('"', '', $this->getTextEncode($value['Video']['VideoTitle'])); ?>
								<div class="song-title">
								<?php
									$videoTitle = $value['Video']['VideoTitle'];

									if ( strlen( $videoTitle ) > 25 ) {
										$videoTitle = $this->getTextEncode(substr( $videoTitle, 0, 25 ) . "...");
									} else {
										$videoTitle = $this->getTextEncode( $videoTitle );
									}

									echo $this->Html->link( $videoTitle, 'javascript:void(0)', array('title' => $title_song_replace ));

									if ('T' == $value['Video']['Advisory']):
								?>
									<span style="color: red; display: inline;"> (Explicit)</span>
							  <?php endif; ?>
								</div>
								<div class="genre">
									<?php echo __('Genre') . ": " . $html->link($this->getTextEncode($value['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view','?genre='.$value['Genre']['Genre']), array('title' => $value['Genre']['Genre'])) . '<br />'; ?>
								</div> 
							 <?php 
							 	if ( !empty( $value['Video']['video_label'] ) ):
							 		if (strlen($value['Video']['video_label']) > 25) {
							 			$videoLabel = substr($value['Video']['video_label'], 0, 25) . "...";
							 		} else {
							 			$videoLabel = $value['Video']['video_label'];
							 		}
							 ?>
									<div class="label"> Label: <?=$videoLabel?> </div> 
						<?php 	endif; ?>
							</li>
				<?php 	endforeach; ?>
					</ul>
				</div>
			</div>
	<?php
		else:
			$videoFlag = 1;
		endif;

		if ( $albumFlag === 1 && $videoFlag === 1 ): ?>
		<span> Sorry, there are no details available for this artist.</span>
<?php 	endif; ?>
</section>