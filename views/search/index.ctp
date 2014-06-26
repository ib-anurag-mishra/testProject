<section class="search-page">
	<div class="breadcrumbs">
		<?php $html->addCrumb( __( 'Search Results', true ) ); ?>
		<?php echo $html->getCrumbs( ' > ', __( 'Home', true ), '/homes' ); ?>
	</div>

	<?php
	$search_category = '';
	$arrayType 		 = array( 'song', 'album', 'genre', 'video', 'artist', 'composer', 'all' );
	
	if( in_array( $type, $arrayType ) ) {
		$search_category = $type == 'all' ? 'search-results-'. $type .'-page' : 'search-results-' . $type . 's-page';
	}
	?>

	<section class="<?= $search_category;?>">
		<div class="faq-link">Need Help? Visit our <a href="/questions">FAQ Section.</a></div>
		<div class="search-results-heading">Results for your search <?= $keyword; ?></div>
		<div class="refine-text">Not what you're looking for? Refine your search below.</div>
		<div class="filter-container clearfix">
		
			<?php if ( $type != 'all'): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=all">All Music</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">All Music</a>
			<?php endif; ?>
			
			<?php if ( $type != 'album' ): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=album">Albums</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Albums</a>
			<?php endif; ?>
			
			<?php if ( $type != 'artist' ): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=artist">Artists</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Artists</a>
			<?php endif; ?>
			
			<?php if ( $type != 'composer' ): ?>
				<a href="/search/index?q=<?= $keyword; ?>&type=composer">Composers</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Composers</a>
			<?php endif; ?>
			
			<?php if ( $type != 'genre' ): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=genre">Genres</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Genres</a>
			<?php endif; ?>
			
			<?php if ( $type != 'video' ): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=video">Videos</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Videos</a>
			<?php endif; ?>
			
			<?php if ( $type != 'song' ): ?>
				<a href="/search/index?q=<?= $keyword ?>&type=song">Songs</a>
			<?php else: ?>
				<a href="javascript:void(0)" class="active">Songs</a>
			<?php endif; ?>

			<div class="search-container">
				<form method="get" id="searchQueryForm" action="<?= $_SERVER['PHP_SELF']; ?>" onsubmit="ajaxSearchPage(); return false;">
					<input type="search" name="q" id="query" value="<?= urldecode( $keyword ) ?>" />
					<input type="hidden" id="search_type" value="<?= !empty( $type ) ? $type : 'all' ?>" name="type">
					<input type="submit" id="search-page-go" value="Go">
				</form>
			</div>
		</div>
		<?php 
			if ( !empty( $type ) && $type != 'all' ) {
				switch ($type) {
					case 'song':
		?>
		<div class="songs">
			<header><h3 class="songs-header">Songs</h3></header>
			<div class="header-container">
				<div class="song-header">
					<a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="song">Song</span></a>
				</div>
				<div class="song-border header-border"></div>
				<div class="artist-header">
					<a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="artist">Artist</span></a>
				</div>
				<div class="artist-border header-border"></div>
				<div class="album-header">
					<a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="album">Album</span></a>
				</div>
				<div class="album-border header-border"></div>
				<div class="time-header">Time</div>
				<?php if ( isset( $patronId ) && !empty( $patronId ) ): ?>
						<a class="multi-select-icon no-ajaxy" href="#" title="Select All, Clear All, Add to Wishlist, Add to Playlist"></a>
						<section class="options-menu">
							<ul>
								<li><a class="select-all no-ajaxy" href="#">Select All</a></li>
								<li><a class="clear-all no-ajaxy" href="#">Clear All</a></li>
								<li><a class="add-all-to-wishlist no-ajaxy" href="#">Add to Wishlist</a></li>
								<?php if ( isset( $libraryType ) && $libraryType == 2 ): ?>
										<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a></li>
								<?php endif; ?>
							</ul>
							<ul class="playlist-menu"></ul>
						</section>
				<?php endif; ?>
			</div>
			<div class="rows-container">
				<?php
				if ( isset( $songs ) && is_array( $songs ) && count( $songs ) > 0 ):
					$i = 1;
					foreach ( $songs as $psong ):

						if ( !is_object( $psong ) ) {
							continue;
						}

						$downloadFlag = $this->Search->checkSongStatusForSearch( $psong->TerritoryDownloadStatus, $psong->TerritorySalesDate, $territory );
						$StreamFlag   = $this->Search->checkSongStatusForSearch( $psong->TerritoryStreamingStatus, $psong->TerritoryStreamingSalesDate, $territory );

						//if song not allowed for streaming and not allowed for download then this song must not be display
						if ( $downloadFlag === 0 && $StreamFlag === 0 ) {
							continue;
						}
				?>
				<div class="row">
					<?php
					if ( isset( $libraryType ) &&  $libraryType == 2 ) {
						$filePath = $this->Token->streamingToken( $psong->CdnPathFullStream . "/" . $psong->SaveAsNameFullStream );

						if ( !empty( $filePath ) ) {
							$songPath			 = explode( ':', $filePath );
							$psong->streamUrl 	 = trim( $songPath[1] );
							$psong->totalseconds = $this->Queue->getSeconds( $psong->FullLength_Duration );
						}
					}

					if ( isset( $patronId ) && !empty( $patronId ) ) {
						if ( isset( $libraryType ) && $libraryType == 2 && $StreamFlag === 1 ) {
							if ( $psong->Advisory == 'T' ) {
								$song_title = $psong->SongTitle . '(Explicit)';
							} else {
								$song_title = $psong->SongTitle;
							}

							echo $this->Queue->getsearchSongsStreamNowLabel( $psong->streamUrl, $song_title, $psong->ArtistText, $psong->totalseconds, $psong->ProdID, $psong->provider_type );

						} else {
							echo $html->image( 'sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode( $psong->provider_type ) . '", "' . $this->webroot . '");'));
							echo $html->image( 'sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i ) );
							echo $html->image( 'sample-stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");' ) );
						}
						
						$styleSong = '';
					} else {
						$styleSong = "style='left:12px'";
					}
					?>

					<div class="artist artist-name">
						<?= $html->link( str_replace( '"', '', $this->Search->truncateText( $psong->ArtistText, 20, $this ) ), array( 'controller' => 'artists', 'action' => 'album', str_replace( '/', '@', base64_encode( $psong->ArtistText ) ) ), array( 'title' => $this->getTextEncode( $psong->ArtistText ) ) ); ?>
					</div>
					<div class="album album-name">
						<?php 
							$artistText    = str_replace( '/', '@', base64_encode( $psong->ArtistText ) );
							$providerType  = base64_encode( $psong->provider_type );
							$songTitle	   = $this->getTextEncode( $psong->Title );
							$linkSongTitle = str_replace( '"', '', $this->Search->truncateText( $this->getTextEncode( $psong->Title ), 25, $this ) );
						?>
						<a href="/artists/view/<?= $artistText; ?>/<?= $psong->ReferenceID; ?>/<?= $providerType; ?>" title="<?= $songTitle; ?>"><?= $linkSongTitle; ?></a>
					</div>
					<div class="song song-name" <?= $styleSong; ?> sdtyped="<?= $downloadFlag . '-' . $StreamFlag . '-' . $territory; ?>">
						<?php
							$showSongTitle = $this->Search->truncateText( $psong->SongTitle, strlen( $psong->SongTitle ), $this );
							$songTitle	   = str_replace( '"', '', $this->getTextEncode( $showSongTitle ) );
							$linkSongTitle = $this->Search->truncateText( $this->getTextEncode( $psong->SongTitle ), 21, $this );
	
							if ($psong->Advisory == 'T') {
								$linkSongTitle .= '<font class="explicit"> (Explicit)</font>';
							}
						?>
						<a style="text-decoration: none;" title="<?= $songTitle; ?>"><?= $linkSongTitle; ?></a>
					</div>
					<div class="time">
						<?php
							$timeDur = explode( ':', $psong->FullLength_Duration );

							if ( $timeDur[0] != "0" ) {
								echo ltrim( $psong->FullLength_Duration, "0" );
							} elseif ( $timeDur[0] == "00" )
								echo "0" . ltrim( $psong->FullLength_Duration, "0" );
							else {
								echo $psong->FullLength_Duration;
							}
						?>
					</div>
					<?php if ( isset( $patronId ) && !empty( $patronId ) ): ?>
						<a class="menu-btn no-ajaxy" href="#" title="Add to Wishlist, Playlist, or Download"></a>
						<section class="options-menu">
							<input type="hidden" id="<?= $psong->ProdID ?>" value="song" data-provider="<?= $psong->provider_type ?>" />
							<ul>
								<li>
								<?php
									if ( $downloadFlag === 1 ):
										$productInfo = $song->getDownloadData( $psong->ProdID, $psong->provider_type );

									if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):

										if ( $psong->status != 'avail' ):
											$hrefTitle = "IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.";
								?>
									<div class="top-100-download-now-button">
										<form method="Post" id="form<?= $psong->ProdID; ?>" action="/homes/userDownload" class="suggest_text1">
											<input type="hidden" name="ProdID" value="<?php echo $psong->ProdID; ?>" />
											<input type="hidden" name="ProviderType" value="<?= $psong->provider_type; ?>" /> 
											<span class="beforeClick" style="cursor: pointer;" id="wishlist_song_<?= $psong->ProdID; ?>">
												<![if !IE]>
													<a href='javascript:void(0);' class="no-ajaxy top-10-download-now-button" title="<?= $hrefTitle ; ?>" onclick='return wishlistDownloadOthersHome("<?= $psong->ProdID; ?>", "0", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?= $psong->provider_type; ?>");'> <?php __('Download Now'); ?> </a>
												<![endif]>
												<!--[if IE]>
													<a href="javascript:void(0);" id="song_download_<?= $psong->ProdID; ?>" class="no-ajaxy top-10-download-now-button" title="<?= $hrefTitle ; ?>" onclick='wishlistDownloadIEHome("<?= $psong->ProdID; ?>", "0" , "<?= $psong->provider_type; ?>", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>");'><?php __('Download Now'); ?></a>
                                                <![endif]-->
											</span>
											<span class="afterClick" id="downloading_<?= $psong->ProdID; ?>" style="display: none;">
												<a class="add-to-wishlist"><?php __("Please Wait.."); ?>
													<span id="wishlist_loader_<?= $psong->ProdID; ?>" style="float: right; padding-right: 8px; padding-top: 2px;"><?= $html->image( 'ajax-loader_black.gif' ); ?></span>
												</a> 
											</span>
										</form>
									</div> 
									<?php else: ?>
										<a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?> </a>
									<?php
										endif;
									  else:
									?>
										<a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?> </a>
								<?php
									endif;
								 else:
									 $sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
									 if ( isset( $sales_date ) ) {
									 	$salesDate = date( "F d Y", strtotime( $sales_date ) );
									 }
								?>
								<a class="top-100-download-now-button" href="javascript:void(0);">
									<span title='<?php __("Coming Soon"); ?> ( <?= $salesDate; ?> )'> <?php __("Coming Soon"); ?> </span>
								</a>
								<?php endif; ?>
							</li>
							<li>
							<?php
								$wishlistInfo = $wishlist->getWishlistData( $psong->ProdID );
								if ( $wishlistInfo == 'Added To Wishlist' ):
							?> 
							<a href="#">Added to Wishlist</a>
							<?php else: ?> 
								<span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
									<a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
								</span>
								<span class="afterClick" style="display: none;">
									<a class="add-to-wishlist" href="JavaScript:void(0);">Please Wait...</a>
								</span>
						 	<?php endif; ?>
							</li>
							<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
								<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a></li>
							</ul>
							<ul class="playlist-menu">
								<li><a href="#">Create New Playlist</a></li>
							</ul>
						<?php endif; ?>
					</section>
					<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
						<input type="checkbox" class="row-checkbox">
					<?php else: ?>
						<div class="sample-icon"></div>
					<?php endif;
						endif;
					?>
				</div>
				<?php
					$i++;
					endforeach;
				endif;
				?>
			</div>
			<div class="pagination-container">
				<?php
					if ( isset( $type ) ) {
						$keyword = "?q=" . $keyword . "&type=" . $type;
					}

					echo $this->Search->createPagination( $currentPage, $facetPage, 'listing', $lastPage, 5, $keyword );
				?>
			</div>
		</div>
	<?php
		break;
		case 'album':
	?>
		<header><h3 class="albums-header">Albums</h3></header>
		<?php
			if ( isset( $albumData ) && is_array( $albumData ) && count( $albumData ) > 0 ) {
				$i = 0;

				foreach ( $albumData as $palbum ) {
					$albumInfo = $this->Search->getAlbumInfo( $palbum, $this );
					extract( $albumInfo );
		?>
					<div class="album-detail-container">
						<div class="cover-image">
							<a href="<?= "/artists/view/$linkArtistText/$palbum->ReferenceID/$linkProviderType"; ?>" title="<?= $this->getTextEncode( $palbum->Title ); ?>"><img src="<?= $image; ?>" alt="<?= $album_title; ?>" width="162" height="162" /></a>
						</div>
						<div class="album-info">
							<div class="album-title">
								<strong><a href="<?= "/artists/view/$linkArtistText/$palbum->ReferenceID/$linkProviderType"; ?>" title="<?= $this->getTextEncode($palbum->Title); ?>"><?php echo $album_title; ?> <?= $explicit; ?></a></strong>
							</div>
							<div class="artist">
								by <a class="more-by-artist" href="/artists/album/<?= str_replace( '/', '@', base64_encode( $palbum->ArtistText ) ); ?>/<?= base64_encode( $album_genre ) ?>"><?= $this->getTextEncode( $palbum->ArtistText ); ?> </a>
							</div>
							<div class="genre">
								Genre:
								<?= $html->link( $this->getTextEncode( $album_genre ), array( 'controller' => 'genres', 'action' => 'view', '?genre='.$album_genre ), array( "title" => $this->getTextEncode( $album_genre ) ) ); ?>
							</div>
							<?php if ( isset( $palbum->Copyright ) && $palbum->Copyright != '' && $palbum->Copyright != 'Unknown' ): ?>
								<div class="label">
									<?= $this->getTextEncode( $palbum->Copyright ); ?>
								</div>
							<?php endif; ?>
							<?php
								if ( isset( $patronId ) && !empty( $patronId ) ) {
									if ( isset( $libraryType ) && isset( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) && $libraryType == 2 && !empty( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) ) {
										echo $this->Queue->getAlbumStreamLabel( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 3 );
									}
							?>
									<button class="menu-btn"></button>
									<section class="options-menu">
										<input type="hidden" id="<?= $palbum->ReferenceID ?>" value="album" data-provider="<?= $palbum->provider_type ?>" />
										<ul>
											<li>
											<?php
												$wishlistInfo = $wishlist->getWishlistData( $nationalTopSong['Song']['ProdID'] );

												if ( $wishlistInfo == 'Added To Wishlist' ):
											?> 
													<a href="#">Added to Wishlist</a>
											<?php else: ?> 
													<span class="beforeClick" id="wishlist<?= $palbum->ReferenceID ?>">
														<a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
													</span>
													<span class="afterClick" style="display: none;">
														<a class="add-to-wishlist" href="JavaScript:void(0);">Please Wait...</a>
													</span>
											<?php endif; ?>
											</li>
											<?php if ( isset( $libraryType ) && isset( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) && $libraryType == 2 && !empty( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) ): ?>
													<li><a class="add-to-playlist no-ajaxy" href="javascript:void(0);">Add to Playlist</a></li>
										</ul>
													<ul class="playlist-menu"><li><a href="#">Create New Playlist</a></li></ul>
											<?php endif; ?>
									</section>
							<?php } ?>
						</div>
					</div>
			<?php
					$i++;
				}
			?>
				<section style="position: relative; width: 866px; right: 21px;" class="search-results-songs-page">
					<div class="pagination-container">
					<?php
						$searchString = "?q=" . urlencode( $keyword ) . "&type=" . $type;
						echo $this->Search->createPagination( $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString );
					?>
					</div>
				</section>
		<?php } else { ?>
		<div class="album-detail-container">
			<div style="color: red; padding: 50px;">
				<span>No Albums Found</span>
			</div>
		</div>
		<?php
				}
			break;
			case 'genre':
		?>
		<header> <h3 class="genres-header"> More Genres Like <span><?= $keyword; ?> </span> </h3> </header>
		<div class="search-results-list">
		<?php
			if ( isset( $genres ) && is_array( $genres ) && count( $genres ) > 0 ) {
		?>
				<ul>
		<?php
				foreach ( $genres as $genre ) {

					$genre_name 	 = str_replace( '"', '', $genre->Genre );
					$genre_name_text = $this->Search->truncateText( $genre_name, 125, $this );
					$tilte 			 = urlencode ($genre->Genre );
		?>
				<li>
					<a href="<?= "/genres/album?q=$keyword&type=album&filter=$tilte"; ?>" title="<?= $this->getTextEncode( $genre_name ); ?>"><?= $this->getTextEncode( $genre_name_text ); ?> (<?= $genre->numFound;; ?>)</a>
				</li>
		<?php 	} ?>
			</ul>
		<?php } else { ?>
			<div style="color: red; padding: 50px;">
				<span>No Genres Found</span>
			</div>
		<?php } ?>
		</div>
		<?php
			break;
			case 'video':
		?>
		<header> <h3 class="videos-header">Videos</h3> </header>
		<?php
			if ( isset( $songs ) && is_array( $songs ) && count( $songs ) > 0 ) {
			$b = 1;
			foreach ( $songs as $psong ) {

				if ( !is_object( $psong ) ) {
					continue;
				}
		?>
				<div class="video-result-container">
					<div class="video-thumb">
					<?php                                    
						$videoArtwork = $this->Token->artworkToken( $psong->ACdnPath . "/" . $psong->ASourceURL );
						$VideoImage = Configure::read( 'App.Music_Path' ) . $videoArtwork;
					?>
						<a href="/videos/details/<?= $psong->ProdID; ?>"><img src="<?= $VideoImage; ?>"> </a>
					</div>
					<div class="video-info">
						<div class="video-title">
							<a href="/videos/details/<?= $psong->ProdID; ?>"><?= $this->getTextEncode( $psong->VideoTitle ); ?> </a>
						</div>
						<div class="artist">
						<?php 
							$artistText = str_replace( '/', '@', base64_encode( $psong->ArtistText ) );
							$linkArtist = $this->getTextEncode( $psong->ArtistText );
						?>
							by <a href="/artists/album/<?= $artistText; ?>/<?= base64_encode( $psong->Genre ) ?>"> <?= $linkArtist; ?></a>
						</div>
						<div class="release-date">
							Released on
						<?php
							$sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
							echo date( "M d, Y", strtotime( $sales_date ) );
						?>
						</div>
						<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>
								<a class="wishlist-btn" title="Add to Wishlist" onclick='Javascript: addToWishlistVideo("<?= $psong->ProdID; ?>", "<?= $psong->provider_type; ?>", 1);'></a>
						<?php
								$sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
								if ( $sales_date <= date( 'Y-m-d' ) ) {
									$productInfo = $mvideo->getDownloadData( $psong->ProdID, $psong->provider_type );
									if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ) {
										if ( $psong->status != 'avail' ) {
						?>
											<div>
												<form method="Post" id="form<?= $psong->ProdID; ?>" action="/videos/download">
													<input type="hidden" name="ProdID" value="<?= $psong->ProdID; ?>" />
													<input type="hidden" name="ProviderType" value="<?= $psong->provider_type; ?>" />
													<span class="beforeClick" id="download_video_<?= $psong->ProdID; ?>"> 
														<![if !IE]>
															<a class="download-btn" title="Download This Video" onclick='return wishlistVideoDownloadOthersToken("<?= $psong->ProdID; ?>", "0", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?= $psong->provider_type; ?>", 1);' href="javascript:void(0);"></a>
														<![endif]>
														<!--[if IE]>
							                                <a class="download-btn" title="Download This Video" onclick='wishlistVideoDownloadIEToken("<?= $psong->ProdID; ?>", "0" , "<?= $psong->provider_type; ?>", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>",1);' href="javascript:void(0);"></a>
							                            <![endif]-->
													</span>
													<span class="afterClick" id="vdownloading_<?= $psong->ProdID; ?>" style="display: none; float: left"></span>
													<span id="vdownload_loader_<?= $psong->ProdID; ?>" style="display: none; float: right;"><?= $html->image( 'ajax-loader_black.gif' ); ?> </span>
												</form>
											</div>
								<?php 	} else { ?>
											<a href="/homes/my_history" title="You have already download this video. Get it from your recent downloads" class="download-btn video-downloaded"></a>
								<?php 	}
									} else {
								 ?>
										<a title="Your download limit has been met." class="download-btn download-limit-met"></a>
								<?php }
									}
							  }
							?>
					</div>
				</div>
		<?php }
			} else {
		?>
				<div style="color: red; padding: 50px;"> <span>No Videos Found</span> </div>
		<?php }
		break;

		case 'artist':
		?>
			<header> <h3 class="artists-header"> More Artists Like <span><?= $keyword; ?> </span> </h3> </header>
			<div class="search-results-list">
			<?php if (isset( $artists ) && is_array( $artists ) && count( $artists ) > 0 ) { ?>
					<ul>
					<?php
						foreach ( $artists as $artist ) {
							$artist_name 	  = str_replace( '"', '', $artist->ArtistText );
							$artist_name_text = str_replace( '/', '@', base64_encode( $artist->ArtistText ) );
					?>
							<li><?= $html->link( $artist_name . " (" . $artist->numFound . ")", array( 'controller' => 'artists', 'action' => 'album', $artist_name_text ), array( 'title' => $artist_name ) ); ?></li>
					<?php } ?>
					</ul>
			<?php } else { ?>
			<div style="color: red; padding: 50px;"> <span>No Artists Found</span> </div>
			<?php } ?>
		</div>
	<?php
			break;
		case 'composer':
	?>
		<header> <h3 class="composers-header"> More Composers Like <span><?= $keyword; ?> </span> </h3> </header>
		<div class="search-results-list">
		<?php if ( isset( $composers ) && is_array( $composers ) && count( $composers ) > 0 ) { ?>
				<ul>
				<?php
					foreach ( $composers as $composer ) {

						$composer_name = str_replace('"', '', $composer->Composer);
						$composer_name = $this->Search->truncateText( $composer_name, 125, $this );
						$composer_name = $this->getTextEncode($composer_name);
						if ( $composer_name != '' && true == is_numeric( $composer->numFound ) ) {
				?>
							<li><a href="/artists/composer/<?= base64_encode( $composer->Composer ); ?>/1" title="<?= $composer_name; ?>"><?= $composer_name; ?> </a></li>
				<?php
						}
					}
				?>
			</ul>
			<?php
				} else {
			?>
			<div style="color: red; padding: 50px;"> <span>No Composers Found</span> </div>
			<?php
				}
			?>
		</div>
		<?php
			break;
		default:
			break;
		} // end switch case
	} else { ?>
		<section class="category-results album-results">
			<header>
				<h3 class="albums-header">Albums</h3>
				<?php if ( isset( $albumData ) && !empty( $albumData ) ): ?>
				<a class="see-more" href="/search/index?q=<?= $keyword; ?>&type=album" title="See More Albums"></a>
				<?php endif; ?>
			</header>
			<div class="search-results-all-albums-carousel">
			<?php if ( isset( $albumData ) && is_array( $albumData ) && count( $albumData ) > 0 ) { ?>
					<div class="search-results-albums">
						<ul class="clearfix">
						<?php
							$i = 0;
							foreach ( $albumData as $palbum ) {
						?>
								<li>
						<?php
								$albumInfo = $this->Search->getAlbumInfo( $palbum, $this );
								extract( $albumInfo );
						?>
								<div class="album-cover-container">
									<a href="<?= "/artists/view/$linkArtistText/$palbum->ReferenceID/$linkProviderType"; ?>" title="<?= $this->getTextEncode( $palbum->Title ); ?>"> <img src="<?= $image; ?>" alt="<?= $album_title; ?>" width="162" height="162" /> </a>
								<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>
										<input type="hidden" id="<?= $palbum->ReferenceID ?>" value="album" data-provider="<?= $palbum->provider_type ?>" />
							<?php
									  	if ( isset( $libraryType ) && isset( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) && $libraryType == 2 && !empty( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) ) {
											echo $this->Queue->getAlbumStreamLabel( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 1 );
							?>
											<a class="playlist-menu-icon toggleable no-ajaxy" title="Add to a Playlist or Create a New Playlist" href="#"></a>
											<ul><li><a href="#" class="create-new-playlist">Create New Playlist...</a></li></ul>
									<?php } ?>
										<a class="wishlist-icon toggleable no-ajaxy" title="Add to Wishlist" href="#"></a>
								<?php } ?>
									</div>
									<div class="album-info">
										<p class="title">
											<a href="<?= "/artists/view/$linkArtistText/$palbum->ReferenceID/$linkProviderType"; ?>" title="<?= $this->getTextEncode( $palbum->Title ); ?>"> <?php echo $album_title; ?> </a>
										</p>
										<p class="artist">
											Genre: <span><a href="javascript:void(0)"><?= $album_genre; ?> </a> </span>
										</p>
										<p class="label"> <?= $album_label_str; ?> </p>
									</div>
								</li>
						<?php
								$i++;
							}
						?>
					</ul>
				</div>
				<button class="sr-albums-prev"></button>
				<button class="sr-albums-next"></button>
		<?php 
			} else { 
		?>
				<ul> <li> <div style="color: red;"> <span>No Albums Found</span> </div> </li> </ul>
		<?php } ?>
			</div>
		</section>
		<section class="category-results artist-results">
			<header>
				<h3 class="artists-header">Artists</h3>
				<?php if ( isset( $artists ) && !empty( $artists ) ): ?>
				<a class="see-more" href="/search/index?q=<?= $keyword; ?>&type=artist" title="See More Artists"></a>
				<?php endif; ?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $artists ) && is_array( $artists ) && count( $artists ) > 0 ) { ?>
						<ul>
						<?php
							foreach ( $artists as $artist ) {
								$artist_name_text = $this->Search->truncateText( $this->getTextEncode( $artist->ArtistText ), 125, $this );

								if ( !empty( $artist_name_text ) ) {

									$linkArtistText	  = str_replace( '"', '', $artist->ArtistText );
									$actionArtistText = str_replace( '/', '@', base64_encode( $artist->ArtistText ) );
									$titleArtistText  = $this->getTextEncode( $artist->ArtistText );
						?>
									<li><?= $html->link( $linkArtistText, array( 'controller' => 'artists', 'action' => 'album', $actionArtistText ), array('title' => $titleArtistText ) ); ?>
										<span>(<?= $artist->numFound; ?>) </span>
									</li>
						<?php
								}
							}
						?>
						</ul>
				<?php
					} else {
				?>
				<ul>
					<li>
						<div style="color: red;"> <span>No Artists Found</span> </div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results composers-results">
			<header>
				<h3 class="composers-header">Composers</h3>
				<?php if ( isset( $composers ) && !empty( $composers ) ): ?>
				<a class="see-more" href="/search/index?q=<?= $keyword; ?>&type=composer" title="See More Composers"></a>
				<?php endif; ?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $composers ) && is_array( $composers ) && count( $composers ) > 0 ) { ?>
						<ul>
						<?php
							foreach ( $composers as $composer ) {
								$composer_name = $this->Search->truncateText( $this->getTextEncode( $composer->Composer ), 125, $this );
								if ( !empty( $composer_name ) ) {
									$composer_name = str_replace( '"', '', $composer_name );
						?>
									<li><a href="/artists/composer/<?= base64_encode( $composer->Composer ); ?>/1" title="<?= $composer_name; ?>"><?php echo str_replace( '"', '', $composer_name ); ?> </a> </li>
						<?php
								}
							}
						?>
						</ul>
				<?php } else { ?>
				<ul>
					<li>
						<div style="color: red;"> <span>No Composers Found</span> </div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results videos-results">
			<header>
				<h3 class="videos-header">Videos</h3>
				<?php if ( isset( $videos ) && !empty( $videos ) ): ?>
				<a class="see-more" href="/search/index?q=<?= $keyword; ?>&type=video" title="See More Videos"></a>
				<?php endif; ?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $videos ) && is_array( $videos ) && count( $videos ) > 0 ) { ?>
				<ul>
				<?php 	foreach ( $videos as $video ) {
						$tilte 			 = urlencode( $video->VideoTitle );
						$video_name_text = $this->Search->truncateText( $this->getTextEncode( $video->VideoTitle ), 125, $this );
						$name 			 = $this->getTextEncode( $video->VideoTitle );
				?>
						<li><a href="/search/index?q=<?= $tilte; ?>&type=video" title="<?= $name; ?>"><?= (($name != "false") ? $video_name_text : ""); ?> </a></li>
				<?php	} ?>
				</ul>
				<?php } else { ?>
				<ul>
					<li>
						<div style="color: red;"> <span>No Videos Found</span> </div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results genres-results">
			<header>
				<h3 class="genres-header">Genres</h3>
				<?php if ( isset( $genres ) && !empty( $genres ) ) { ?>
				<a class="see-more" href="/search/index?q=<?= $keyword; ?>&type=genre" title="See More Genres"></a>
				<?php } ?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $genres ) && is_array( $genres ) && count( $genres ) ) { ?>
				<ul>
				<?php	foreach ( $genres as $genre ) {

							$genre_name 	 = str_replace( '"', '', $genre->Genre );
							$genre_name		 = $this->getTextEncode( $genre_name );
							$tilte 			 = urlencode( $genre_name );
							$genre_name_text = $this->Search->truncateText( $genre_name , 125, $this );

							if ( !empty( $genre_name_text ) ) {
				?>
								<li><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?= $genre_name; ?>"><?= $genre_name_text; ?><span>(<?= $genre->numFound; ?>) </span> </a></li>
				<?php
							}
						}
				?>
				</ul>
				<?php } else { ?>
				<ul>
					<li>
						<div style="color: red;"> <span>No Genres Found</span> </div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results songs-results">
			<header> <h3 class="songs-header">Songs</h3></header>
			<div class="songs-results-list">
				<div class="header-container">
					<div class="artist-col">
						<a href="<?= "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="artist">Artist</span> </a>
					</div>
					<div class="artist-border header-border"></div>
					<div class="composer-col">
						<a href="<?= "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="composer">Composer</span> </a>
					</div>
					<div class="composer-border header-border"></div>
					<div class="album-col">
						<a href="<?= "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="album">Album</span> </a>
					</div>
					<div class="album-border header-border"></div>
					<div class="song-col">
						<a href="<?= "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type; ?>"><span class="song">Song</span> </a>
					</div>
					<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>

							<a class="multi-select-icon no-ajaxy" href="#" title="Select All, Clear All, Add to Wishlist, or Add to Playlist"></a>
							<section class="options-menu">
								<ul>
									<li><a class="select-all no-ajaxy" href="#">Select All</a></li>
									<li><a class="clear-all no-ajaxy" href="#">Clear All</a></li>
									<li><a class="add-all-to-wishlist no-ajaxy" href="#">Add to Wishlist</a></li>
									<?php if ( isset( $libraryType ) && $libraryType == 2 ): ?>
											<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a> </li>
									<?php endif; ?>
								</ul>
								<ul class="playlist-menu"></ul>
							</section>
					<?php } ?>
				</div>
				<div class="rows-container">
					<?php
							if ( isset( $songs ) && is_array( $songs ) && count( $songs ) > 0 ) {
								$i = 1;
								foreach ($songs as $psong) {
									
									if ( !is_object( $psong ) ) {
										continue;
									}

									$downloadFlag = $this->Search->checkSongStatusForSearch( $psong->TerritoryDownloadStatus, $psong->TerritorySalesDate, $territory );
									$StreamFlag   = $this->Search->checkSongStatusForSearch( $psong->TerritoryStreamingStatus, $psong->TerritoryStreamingSalesDate, $territory );

									//if song not allowed for streaming and not allowed for download then this song must not be display
									if ($downloadFlag === 0 && $StreamFlag === 0) {
										continue;
									}
					?>
									<div class="row">
									<?php
										if ( isset( $libraryType ) && $libraryType == 2 ) {
											$filePath = $this->Token->streamingToken( $psong->CdnPathFullStream . "/" . $psong->SaveAsNameFullStream );
											if ( !empty( $filePath ) ) {

												$songPath 			 = explode( ':', $filePath );
												$psong->streamUrl 	 = trim( $songPath[1] );
												$psong->totalseconds = $this->Queue->getSeconds( $psong->FullLength_Duration );
											}
										}

										if ( isset( $patronId ) && !empty( $patronId ) ) {
											if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ) {
												
												if ( $psong->Advisory == 'T' ) {
													$song_title = $psong->SongTitle . '(Explicit)';
												} else {
													$song_title = $psong->SongTitle;
												}

												echo $this->Queue->getsearchSongsStreamNowLabel( $psong->streamUrl, $song_title, $psong->ArtistText, $psong->totalseconds, $psong->ProdID, $psong->provider_type );

											} else {
												echo $html->image( 'sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode($psong->provider_type) . '", "' . $this->webroot . '");' ) );
												echo $html->image( 'sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i ) );
												echo $html->image( 'sample-stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");' ) );
											}

											$styleSong = '';
										} else {
											$styleSong = 'style="left:18px"';
										}
									?>
										<div class="artist artist-name" <?= $styleSong; ?>>
											<?= $html->link( str_replace( '"', '', $this->Search->truncateText( $psong->ArtistText, 20, $this ) ), array( 'controller' => 'artists', 'action' => 'album', str_replace( '/', '@', base64_encode( $psong->ArtistText ) ) ), array( 'title' => $this->getTextEncode( $psong->ArtistText ) ) ); ?>
										</div>
										<div class="composer composer-name">
											<a style="text-decoration: none;" title='<?= str_replace( '"', '', $this->getTextEncode( $psong->Composer ) ); ?>'><?= $this->Search->truncateText( str_replace( '"', '', $this->getTextEncode( $psong->Composer ) ), 25, $this ); ?> </a>
										</div>
										<div class="album album-name">
											<a href="/artists/view/<?= str_replace( '/', '@', base64_encode( $psong->ArtistText ) ); ?>/<?= $psong->ReferenceID; ?>/<?= base64_encode( $psong->provider_type ); ?>" title="<?php echo $this->getTextEncode($psong->Title); ?> "><?php echo str_replace('"', '', $this->Search->truncateText($this->getTextEncode($psong->Title), 25, $this)); ?> </a>
										</div>
										<div class="song song-name" sdtyped="<?php echo $downloadFlag . '-' . $StreamFlag . '-' . $territory; ?>">
										<?php $showSongTitle = $this->Search->truncateText( $psong->SongTitle, strlen( $psong->SongTitle ), $this ); ?>
											<a style="text-decoration: none;" title="<?= str_replace( '"', '', $this->getTextEncode( $showSongTitle ) ); ?>"><?= $this->Search->truncateText( $this->getTextEncode( $psong->SongTitle ), 21, $this ); ?>
										<?php
											if ($psong->Advisory == 'T') {
												echo '<font class="explicit"> (Explicit)</font>';
											}
										?> </a>
										</div>
										<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>
												<a href="#" class="menu-btn no-ajaxy" title="Add To a Playlist, Wishlist, or Download"></a>
												<section class="options-menu">
													<input type="hidden" id="<?= $psong->ProdID ?>" value="song" data-provider="<?= $psong->provider_type ?>" />
													<ul>
														<li>
														<?php
															if ( $downloadFlag === 1 ) {
																$productInfo = $song->getDownloadData( $psong->ProdID, $psong->provider_type );

																if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ) {
																	if ( $psong->status != 'avail' ) {
																		$titleSong = "IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.";
														?>
																		<div class="top-100-download-now-button">
																			<form method="Post" id="form<?= $psong->ProdID; ?>" action="/homes/userDownload" class="suggest_text1">
																				<input type="hidden" name="ProdID" value="<?= $psong->ProdID; ?>" />
																				<input type="hidden" name="ProviderType" value="<?= $psong->provider_type; ?>" />
																				<span class="beforeClick" style="cursor: pointer;" id="wishlist_song_<?= $psong->ProdID; ?>"> 
																					<![if !IE]>
																						<a href='javascript:void(0);' class="no-ajaxy top-10-download-now-button" title="<?= $titleSong; ?>" onclick='return wishlistDownloadOthersHome("<?= $psong->ProdID; ?>", "0", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?= $psong->provider_type; ?>");'> <?php __('Download Now'); ?> </a> 
																					<![endif]>
																					<!--[if IE]>
						                                                            	<a id="song_download_<?= $psong->ProdID; ?>" class="no-ajaxy top-10-download-now-button" title="<?= $titleSong; ?>" onclick='wishlistDownloadIEHome("<?= $psong->ProdID; ?>", "0" , "<?= $psong->provider_type; ?>", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
		                                                                            <![endif]-->
																				</span>
																				<span class="afterClick" id="downloading_<?= $psong->ProdID; ?>" style="display: none;">
																					<a class="add-to-wishlist"><?php __("Please Wait.."); ?>
																						<span id="wishlist_loader_<?= $psong->ProdID; ?>" style="float: right; padding-right: 8px; padding-top: 2px;"><?= $html->image( 'ajax-loader_black.gif' ); ?> </span> 
																					</a> 
																				</span>
																			</form>
																		</div>
														<?php 		} else { ?>
														 				<a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?> </a>
														<?php
																	}
																} else {
														?>
																	<a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?> </a> 
														<?php
																}
															} else {
														?>
																<a class="top-100-download-now-button" href="javascript:void(0);">
																	<span title='<?php __("Coming Soon"); ?> ( 
														<?php
						                            					$sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
						                            					if ( isset( $sales_date ) ) {
						                                					echo date( "F d Y", strtotime( $sales_date ) );
						                            					}
						                            	?> 
						                            					)'> <?php __("Coming Soon"); ?>
															</span>
														</a> 
														<?php } ?>
														</li>
														<li>
														<?php
															$wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
						
															if ( $wishlistInfo == 'Added To Wishlist' ) {
														?> 
																<a href="#">Added to Wishlist</a> 
														<?php
															} else {
														?>
																<span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
																	<a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
																</span> 
																<span class="afterClick" style="display: none;">
																	<a class="add-to-wishlist" href="JavaScript:void(0);">Please Wait...</a>
																</span>
														<?php
															}
														?>
														</li>
														<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
																<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a></li>
																</ul>
																<ul class="playlist-menu"> <li><a href="#" class="no-ajaxy">Create New Playlist</a></li> </ul>
														<?php endif; ?>
												</section>
												<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
														<input type="checkbox" class="row-checkbox">
												<?php else: ?>
														<div class="sample-icon"></div>
												<?php endif;
													}
												?>
									</div>
					<?php
								$i++;
							}
						}
					?>
				</div>
				<div class="pagination-container">
				<?php
					if ( isset( $type ) ) {
						$keyword = "?q=" . $keyword . "&type=" . $type;
					}

					echo $this->Search->createPagination( $currentPage, $facetPage, 'listing', $lastPage, 5, $keyword );
					?>
				</div>
			</div>
		</section>
		<?php } ?>
	</section>
</section>