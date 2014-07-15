<section class="my-top-100-page">
	<div class="breadcrumbs">
		<?php
			$html->addCrumb(__('My Library Top 10', true), '/homes/my_lib_top_10');
			echo $html->getCrumbs(' > ', __('Home', true), '/homes');
		?>
	</div>
	<header class="clearfix"> <h2> <?php echo __('My Library Top 10', true); ?> </h2> </header>
	<h3>Albums</h3>
	<div class="album-shadow-container">
		<div class="album-scrollable horiz-scroll carousel">
			<ul style="width:1650px;">
				<?php
				$count = 1;
				if ( isset( $topDownload_albums ) && is_array( $topDownload_albums ) && count($topDownload_albums) > 0 ):
					foreach ( $topDownload_albums as $key => $value ):
						if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T')) {
							continue; //hide song if library block the explicit content
						}
				?>
				<li>
					<div class="album-container">
						<?php echo $html->link($html->image($value['album_img'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false)); ?>
						<div class="top-10-ranking">
							<?php echo $count; ?>
						</div>
						<?php
						if ( isset( $patronId ) && ! empty( $patronId ) ):
						?>
							<input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album" data-provider="<?= $value["Albums"]["provider_type"] ?>" />
						<?php 
							if ( isset( $libraryType ) && $libraryType == 2 && !empty($value['albumSongs'][$value['Albums']['ProdID']] ) ):
								echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
								echo $this->Html->link( '', 'javascript:void(0)', array('class' => 'playlist-menu-icon no-ajaxy toggleable') );
						?>
								<ul><li><?php echo $this->Html->link('Create New Playlist...', '#', array('class' => 'create-new-playlist'))?></li></ul>
						<?php
							endif;
						endif;
						?>
					</div>
					<div class="album-title">
						<?php 
							$albumTitle = $this->Home->trimString( $value['Albums']['AlbumTitle'], 20 );
							$albumTitle = $this->Home->explicitContent( $value['Albums']['Advisory'], $albumTitle, true );

							echo $this->Html->link( $albumTitle, array( 'controller' => 'artists', 'action' => 'view', base64_encode( $value['Song']['ArtistText'] ), $value['Song']['ReferenceID'], base64_encode( $value['Song']['provider_type'] ) ), array( 'title' => $this->getValidText( $this->getTextEncode( $value['Albums']['AlbumTitle'] ) ) ) );
						?>
					</div>
					<div class="artist-name">
						<?php echo $this->Html->link( $this->Home->trimString( $value['Song']['Artist'], 32 ), array( 'controller' => 'artists', 'action' => 'album', str_replace( '/', '@', base64_encode( $value['Song']['ArtistText'] ) ), base64_encode( $value['Genre']['Genre'] ) ), array( 'title' => $this->getValidText( $this->getTextEncode( $value['Song']['Artist'] ) ) ) );?>
					</div>
				</li>
				<?php
						$count++;
					endforeach;
				else:
				?>
					<li><span style="font-size:14px;">Sorry,there are no downloads.</span></li>
		<?php endif; ?>
			</ul>
		</div>
		<button class="left-scroll-button"  type="button"></button>
        <button class="right-scroll-button" type="button"></button>
	</div>
	<h3>Songs</h3>
	<div class="songs-shadow-container">
		<div class="songs-scrollable carousel horiz-scroll">
			<ul style="width:1650px;">
				<?php
				$count = 1;
				if ( isset( $top_10_songs ) && is_array( $top_10_songs ) && count($top_10_songs) > 0 ):
					foreach ( $top_10_songs as $key => $value ):
						if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T')) {
							continue; //hide song if library block the explicit content
						}

						if ($count > 10)
							break;
				?>
				<li>
					<div class="song-container">
						<?php echo $this->Html->link( $this->Html->image( $value['songs_img'], array( 'alt' => $this->getValidText($value['Song']['Artist'] . ' - ' . $value['Song']['SongTitle'] ), 'class' => 'lazy', 'width' => '250', 'height' => '250' ) ), array( 'controller' => 'artists', 'action' => 'view',base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'] ) ), array( 'escape' => false ) );?>
						<div class="top-10-ranking"> <?php echo $count; ?> </div>
						<?php
						if ( isset( $patronId ) && ! empty( $patronId ) ):
							if ( isset( $libraryType ) && $libraryType == 2 && $value['Country']['StreamingSalesDate'] <= date('Y-m-d') && $value['Country']['StreamingStatus'] == 1) {

								$song_title = $this->Home->explicitContent( $value['Song']['Advisory'], $value['Song']['SongTitle'] );
								echo $this->Queue->getStreamNowLabel($value['streamUrl'], $song_title, $value['Song']['ArtistText'], $value['totalseconds'], $value['Song']['ProdID'], $value['Song']['provider_type']);

							} else if ($value['Country']['SalesDate'] <= date('Y-m-d')) {
								echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;border: 0px solid;", "id" => "play_audio" . $key, "onClick" => 'playSample(this, "' . $key . '", ' . $value['Song']['ProdID'] . ', "' . base64_encode($value['Song']['provider_type']) . '", "' . $this->webroot . '");'));
								echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "load_audio" . $key));
								echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "stop_audio" . $key, "onClick" => 'stopThis(this, "' . $key . '");'));
							}

							if ($value['Country']['SalesDate'] <= date('Y-m-d')):
								if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1'):
									$productInfo = $song->getDownloadData($value['Song']['ProdID'], $value['Song']['provider_type']);

									if( isset( $downloadVariArray ) && ! empty( $downloadVariArray ) ) {
										$downloadsUsed = $this->Download->getDownloadResults($value['Song']['ProdID'], $value['Song']['provider_type']);
									} else {
										$downloadsUsed = $this->Download->getDownloadfind($value['Song']['ProdID'], $value['Song']['provider_type'], $libraryId, $patronId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
									}

									if ( !( $downloadsUsed > 0 ) ):
										$title = 'IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.';
						?>
										<div class="top-10-download-now-button">
											<form method="Post" id="form<?php echo $value["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
												<input type="hidden" name="ProdID" value="<?php echo $value["Song"]["ProdID"]; ?>" /> 
												<input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" /> 
												<span class="beforeClick" style="cursor: pointer;" id="wishlist_song_<?php echo $value["Song"]["ProdID"]; ?>"> 
													<![if !IE]>
														<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'add-to-wishlist no-ajaxy', 'title' => $title, 'onclick' => "return wishlistDownloadOthersHome('{$value["Song"]['ProdID']}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$value["Song"]["provider_type"]}')", 'escape' => false ) );?>
													<![endif]>
													<!--[if IE]>
                                                        <?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy', 'title' => $title, 'onclick' => "return wishlistDownloadIEHome( '{$value["Song"]['ProdID']}', '0', '{$value["Song"]["provider_type"]}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')", 'escape' => false ) );?>
                                                    <![endif]-->
												</span> 
												<span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display: none;">
													<span class="add-to-wishlist"><?php __("Please Wait..."); ?>
														<span id="wishlist_loader_<?php echo $value["Song"]["ProdID"]; ?>" style="float: right; padding-right: 3px; padding-top: 4px;"><?php echo $html->image('ajax-loader_black.gif'); ?> </span>
													</span>
												</span>
											</form>
										</div>
								<?php else:
										echo $this->Html->link( $this->Html->tag( 'label', 'Downloaded', array('class' => 'dload', 'style' => 'width: 120px; cursor: pointer;', 'title' => 'You have already downloaded this song. Get it from your recent downloads') ), array( 'controller' => 'homes', 'action' => 'my_history'), array('class' => 'top-10-download-now-button song-downloaded', 'escape' => false ) );
									endif;
								else:
									if ( isset( $libraryDownload ) && $libraryDownload != '1' ) {
										$libraryInfo = $library->getLibraryDetails( $libraryId );
										$wishlistCount = $wishlist->getWishlistCount();
										if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
											echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'top-10-download-now-button' ) );
										} else {
											$wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
											echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
										}
									} else {
										echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'top-10-download-now-button download-limit-met' ) );
									}
								endif;
							else:
								$salesDate = '';
								if (isset($value['Country']['SalesDate'])) {
									$salesDate = date("F d Y", strtotime($value['Country']['SalesDate']));
								}
								echo $this->Html->link( $this->Html->tag('span', 'Coming Soon', array( 'title' => '(' . $salesDate . ')' ) ), 'javascript:void(0)', array('class' => 'top-10-download-now-button', 'escape' => false ) );
							endif;
						endif;

						if ( isset( $patronId ) && ! empty( $patronId ) ):
							echo $this->Html->link( '', 'javascript:void(0)', array('class' => 'playlist-menu-icon no-ajaxy toggleable') );
							
						?>
							<ul> <li><?php echo $this->Html->link('Create New Playlist...', '#', array('class' => 'create-new-playlist'))?></li> </ul>
							<input type="hidden" id="<?php echo $value["Song"]["ProdID"]; ?>" value="song" data-provider="<?php echo $value["Song"]["provider_type"]; ?>" />
							<?php echo $this->Html->link('', '#', array('class' => 'wishlist-icon toggleable no-ajaxy', 'title' => 'Add to Wishlist'))?>
					<?php endif; ?>
					</div>
					<div class="album-title">
						<?php 
							$songTitle = $this->Home->trimString( $value['Song']['SongTitle'], 20 );
							$songTitle = $this->Home->explicitContent( $value['Song']['Advisory'], $songTitle, true );
							echo $this->Html->link( $songTitle, array( 'controller' => 'artists', 'action' => 'view', base64_encode( $value['Song']['ArtistText'] ), $value['Song']['ReferenceID'], base64_encode( $value['Song']['provider_type'] ) ), array( 'title' => $this->getValidText( $this->getTextEncode( $value['Song']['SongTitle'] ) ) ) );
						?>
					</div>
					<div class="artist-name">
						<?php echo $this->Html->link( $this->Home->trimString( $value['Song']['Artist'], 32 ), array( 'controller' => 'artists', 'action' => 'album', str_replace( '/', '@', base64_encode( $value['Song']['ArtistText'] ) ), base64_encode( $value['Genre']['Genre'] ) ), array( 'title' => $this->getValidText( $this->getTextEncode( $value['Song']['Artist'] ) ) ) );?>
					</div>
				</li>
				<?php
					$count++;
					endforeach;
				else:
				?>
					<li><span style="font-size:14px;">Sorry,there are no downloads.</span></li>
		<?php  endif; ?>
			</ul>
		</div>
		<button class="left-scroll-button"  type="button"></button>
        <button class="right-scroll-button" type="button"></button>
	</div>
	<h3>Videos</h3>
	<div class="videos-shadow-container">
		<div class="videos-scrollable horiz-scroll carousel">
			<ul>
				<?php
				$count = 1;
				if ( isset( $topDownload_videos_data ) && is_array( $topDownload_videos_data ) && count($topDownload_videos_data) > 0 ):
					foreach ($topDownload_videos_data as $key => $value):
						if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T')) {
							continue; //hide song if library block the explicit content
						}
				?>
				<li>
					<div class="video-container">
						<?php echo $this->Html->link( $this->Html->image( $value['videoAlbumImage'], array( 'alt' => $this->getValidText($value['Video']['Artist'] . ' - ' . $value['Video']['VideoTitle'] ), 'width' => '423', 'height' => '250' ) ), array( 'controller' => 'videos', 'action' => 'details', $value['Video']['ProdID'] ), array( 'escape' => false ) );?>
						<div class="top-10-ranking"> <?php echo $count; ?> </div>
						<?php 
							if ( isset( $patronId ) && ! empty( $patronId ) ):
								echo $this->Html->link('', 'javascript:void(0)', array('class' => 'preview'));
								
								if ($value['Country']['SalesDate'] <= date('Y-m-d')):
									if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1'):
									$productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
									$downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libraryId, $patronId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));

									if ( !( $downloadsUsed > 0 ) ):
										$title = 'IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.';
						?>
										<div class="mylib-top-10-video-download-now-button">
											<form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
												<input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" /> 
												<input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" /> 
												<span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>"> 
													<![if !IE]>
														<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'title' => $title, 'onclick' => "return wishlistVideoDownloadOthersToken('{$value['Video']['ProdID']}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$value['Video']['provider_type']}')", 'escape' => false ) ); ?>
													<![endif]>
													<!--[if IE]>
				                                        <?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'title' => $title, 'onclick' => "wishlistVideoDownloadIEToken('{$value['Video']['ProdID']}', '0', '{$value['Video']['provider_type']}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')", 'escape' => false ) ); ?>
				                                    <![endif]-->
												</span>
												<span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display: none;"><?php __('Please Wait...&nbsp&nbsp'); ?> </span>
												<span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display: none; float: right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?> </span>
											</form>
										</div>
							<?php   else:
										echo $this->Html->link( $this->Html->tag( 'label', 'Downloaded', array('class' => 'dload', 'style' => 'width: 120px; cursor: pointer;', 'title' => 'You have already downloaded this song. Get it from your recent downloads') ), array( 'controller' => 'homes', 'action' => 'my_history'), array('class' => 'mylib-top-10-video-download-now-button', 'escape' => false ) );
									endif;
								else:
									if ( isset( $libraryDownload ) && $libraryDownload != '1' ) {
										$libraryInfo = $library->getLibraryDetails($libraryId);
										$wishlistCount = $wishlist->getWishlistCount();
										if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
											echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'mylib-top-10-video-download-now-button' ) );
										} else {
											$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
											echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
										}
									} else {
										echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'mylib-top-10-video-download-now-button' ) );
									}
								endif;
							 else:
								$salesDate = '';
								if (isset($value['Country']['SalesDate'])) {
									$salesDate = date("F d Y", strtotime($value['Country']['SalesDate']));
								}
								echo $this->Html->link( $this->Html->tag('span', 'Coming Soon', array( 'title' => 'Coming Soon (' . $salesDate . ')' ) ), 'javascript:void(0)', array('class' => 'mylib-top-10-video-download-now-button', 'escape' => false ) );
							endif;
						endif;

						if ( isset( $patronId ) && ! empty( $patronId ) ):
							echo $this->Html->link( '', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy') );
						?>
							<div class="wishlist-popover">
								<?php
									$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
									echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
								?>
							</div>
						<?php endif; ?>
					</div>
					<div class="album-title">
						<?php
							$videoTitle = $this->Home->trimString( $value['Video']['VideoTitle'], 20 );
							$videoTitle = $this->Home->explicitContent( $value['Video']['Advisory'], $videoTitle, true );
							echo $this->Html->link( $videoTitle, array( 'controller' => 'videos', 'action' => 'details', $value['Video']['ProdID'] ), array('title' => $this->getValidText( $this->getTextEncode( $value['Video']['VideoTitle'] ) ), 'escape' => false ) );
						?>
					</div>
					<div class="artist-name">
						<?php echo $this->Html->link( $this->Home->trimString( $value['Video']['Artist'], 32 ), array( 'controller' => 'artists', 'action' => 'album', str_replace( '/', '@', base64_encode( $value['Video']['ArtistText'] ) ), base64_encode( $value['Genre']['Genre'] ) ), array( 'title' => $this->getValidText( $this->getTextEncode( $value['Video']['Artist'] ) ) ) );?>
					</div>
				</li>
				<?php
				$count++;
					endforeach;
				else: ?>
					<li><span style="font-size:14px;">Sorry,there are no downloads.</span></li>
		<?php 	endif; ?>
			</ul>
		</div>
		<button class="left-scroll-button"  type="button"></button>
        <button class="right-scroll-button" type="button"></button>
	</div>
</section>