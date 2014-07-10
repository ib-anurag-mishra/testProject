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
		<div class="faq-link">Need Help? Visit our <?=$this->Html->link( 'FAQ Section.','/questions') ?></div>
		<div class="search-results-heading">Results for your search <?= $keyword; ?></div>
		<div class="refine-text">Not what you're looking for? Refine your search below.</div>
		<div class="filter-container clearfix">
		
			<?php 
				if ( $type != 'all') {
					echo $this->Html->link( 'All Music', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'all' ) ), array( 'title' => 'All Music' ) );	
				} else {
					echo $this->Html->link( 'All Music', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'All Music' ) );
				}
	
				if ( $type != 'album' ) {
					echo $this->Html->link( 'Albums', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'album' ) ), array( 'title' => 'Albums' ) );
				} else {
					echo $this->Html->link( 'Albums', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Albums' ) );
				}
	
				if ( $type != 'artist' ) {
					echo $this->Html->link( 'Artists', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'artist' ) ), array( 'title' => 'Artists' ) );
				} else {
					echo $this->Html->link( 'Artists', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Artists' ) );
				}
	
				if ( $type != 'composer' ) {
					echo $this->Html->link( 'Composers', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'composer' ) ), array( 'title' => 'Composers' ) );
				} else {
					echo $this->Html->link( 'Composers', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Composers' ) );
				}
	
				if ( $type != 'genre' ) {
					echo $this->Html->link( 'Genres', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'genre' ) ), array( 'title' => 'Genres' ) );
				} else {
					echo $this->Html->link( 'Genres', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Genres' ) );
				}
	
				if ( $type != 'video' ) {
					echo $this->Html->link( 'Videos', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'video' ) ), array( 'title' => 'Videos' ) );
				} else {
					echo $this->Html->link( 'Videos', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Videos' ) );
				}
	
				if ( $type != 'song' ) {
					echo $this->Html->link( 'Songs', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'song' ) ), array( 'title' => 'Songs' ) );
				} else {
					echo $this->Html->link( 'Songs', 'javascript:void(0)', array( 'class' => 'active', 'title' => 'Songs' ) );
				}
			?>

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
					<span class="song">Song</span>
				</div>
				<div class="song-border header-border"></div>
				<div class="artist-header">
					<span class="artist">Artist</span>
				</div>
				<div class="artist-border header-border"></div>
				<div class="album-header">
					<span class="album">Album</span>
				</div>
				<div class="album-border header-border"></div>
				<div class="time-header">Time</div>
					<?php 
					if ( isset( $patronId ) && !empty( $patronId ) ):
						echo $this->Html->link( '', '#', array( 'class' => 'multi-select-icon no-ajaxy', 'title' => 'Select All, Clear All, Add to Wishlist, Add to Playlist' ) );
					?>
						<section class="options-menu">
							<ul>
								<li><?=$this->Html->link( 'Select All', '#', array( 'class' => 'select-all no-ajaxy' ) )?></li>
								<li><?=$this->Html->link( 'Clear All', '#', array( 'class' => 'clear-all no-ajaxy' ) )?></li>
								<li><?=$this->Html->link( 'Add to Wishlist', '#', array( 'class' => 'add-all-to-wishlist no-ajaxy' ) )?></li>
								<?php if ( isset( $libraryType ) && $libraryType == 2 ): ?>
										<li><?=$this->Html->link( 'Add to Playlist', '#', array( 'class' => 'add-to-playlist no-ajaxy' ) )?></li>
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
						<?=$this->Html->link( $linkSongTitle, array( 'controller' => 'artists', 'action' => 'view', $psong->ReferenceID, $providerType ), array( 'title' => $songTitle ) )?>
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
						<span title="<?= $songTitle; ?>"><?= $linkSongTitle; ?></span>
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
						<?=$this->Html->link( '', '#', array( 'class' => 'menu-btn no-ajaxy', 'title' => 'Add to Wishlist, Playlist, or Download' ) )?>
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
													<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'title' => $hrefTitle, 'onclick' => "return wishlistDownloadOthersHome('{$psong->ProdID}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$psong->provider_type}')", 'escape' => false ) );?>
												<![endif]>
												<!--[if IE]>
													<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'id' => 'song_download_' . $psong->ProdID, 'title' => $hrefTitle, 'onclick' => "wishlistDownloadIEHome('{$psong->ProdID}', '0', '{$psong->provider_type}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')", 'escape' => false ) );?>
                                                <![endif]-->
											</span>
											<span class="afterClick" id="downloading_<?= $psong->ProdID; ?>" style="display: none;">
												<span class="add-to-wishlist"><?php __("Please Wait.."); ?>
													<span id="wishlist_loader_<?= $psong->ProdID; ?>" style="float: right; padding-right: 8px; padding-top: 2px;"><?= $html->image( 'ajax-loader_black.gif' ); ?></span>
												</span> 
											</span>
										</form>
									</div> 
									<?php else: ?>
										<?=$this->Html->link( 'Downloaded', array( 'controller' => 'homes', 'action' => 'my_history' ), array( 'class' => 'top-100-download-now-button', 'title' => 'You have already downloaded this song. Get it from your recent downloads' ) )?>
									<?php
										endif;
									  else:
									?>
										<?=$this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'top-100-download-now-button' ) )?>
								<?php
									endif;
								 else:
									 $sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
									 if ( isset( $sales_date ) ) {
									 	$salesDate = date( "F d Y", strtotime( $sales_date ) );
									 }
								?>
									<?=$this->Html->link( 'Coming Soon', 'javascript:void(0)', array( 'class' => 'top-100-download-now-button', 'title' => 'Coming Soon ( ' . $salesDate . ' )' ) )?>								
								<?php endif; ?>
							</li>
							<li>
							<?php
								$wishlistInfo = $wishlist->getWishlistData( $psong->ProdID );
								if ( $wishlistInfo == 'Added To Wishlist' ):
							?> 
									<?=$this->Html->link( 'Added to Wishlist', '#' )?>
							<?php else: ?> 
								<span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
									<?=$this->Html->link( 'Add to Wishlist', '#', array( 'class' => 'add-to-wishlist no-ajaxy' ) )?>
								</span>
								<span class="afterClick" style="display: none;">
									<?=$this->Html->link( 'Please Wait...', 'JavaScript:void(0)', array( 'class' => 'add-to-wishlist' ) )?>
								</span>
						 	<?php endif; ?>
							</li>
							<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
								<li> <?=$this->Html->link( 'Add to Playlist', '#', array( 'class' => 'add-to-playlist no-ajaxy' ) )?> </li>
							</ul>
							<ul class="playlist-menu">
								<li><?=$this->Html->link( 'Create New Playlist', '#')?></li>
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
							<?=$this->Html->link( $this->Html->image( $image, array( 'alt' => $album_title, 'width' => 162, 'height' => 162 ) ), array( 'controller' => 'artists', 'action' => 'view', $linkArtistText, $palbum->ReferenceID, $linkProviderType ), array( 'title' => $this->getTextEncode( $palbum->Title ), 'escape' => false ) )?>
						</div>
						<div class="album-info">
							<div class="album-title">
								<strong> <?=$this->Html->link( $album_title . $explicit, array( 'controller' => 'artists', 'action' => 'view', $linkArtistText, $palbum->ReferenceID, $linkProviderType ), array( 'title' => $this->getTextEncode( $palbum->Title ), 'escape' => false ) )?> </strong>
							</div>
							<div class="artist">
								by
								<?php echo $this->Html->link( $this->getTextEncode( $palbum->ArtistText ), 
										array( 'controller' => 'artists', 
												'action' => 'album', 
												str_replace( '/', '@', base64_encode( $palbum->ArtistText ) ), 
												base64_encode( $album_genre ) ),
										array( 'class' => 'more-by-artist' ) );
								?>
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
													<?=$this->Html->link( 'Added to Wishlist', '#' )?>
											<?php else: ?> 
													<span class="beforeClick" id="wishlist<?= $palbum->ReferenceID ?>">
														<?=$this->Html->link( 'Add to Wishlist', '#', array( 'class' => 'add-to-wishlist no-ajaxy' ) )?>
													</span>
													<span class="afterClick" style="display: none;">
														<?=$this->Html->link( 'Please Wait...', 'JavaScript:void(0)', array( 'class' => 'add-to-wishlist' ) )?>
													</span>
											<?php endif; ?>
											</li>
											<?php if ( isset( $libraryType ) && isset( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) && $libraryType == 2 && !empty( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) ): ?>
													<li> <?=$this->Html->link( 'Add to Playlist', 'JavaScript:void(0)', array( 'class' => 'add-to-playlist no-ajaxy' ) )?></li>
										</ul>
													<ul class="playlist-menu"><li><?=$this->Html->link( 'Create New Playlist', '#' )?></li></ul>
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
					$title 			 = urlencode ($genre->Genre );
		?>
				<li>
					<?php echo $this->Html->link( $this->getTextEncode( $genre_name_text ) . ' (' . $genre->numFound . ')', 
							array( 'controller' => 'genres', 'action' => 'album', '?' => array( 'q' => $keyword, 'type' => 'album', 'filter' => $genre->Genre ) ),
							array( 'title' => $this->getTextEncode( $genre_name ) ) );
					?>
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
						$videoImage = Configure::read( 'App.Music_Path' ) . $videoArtwork;
					?>
						<?=$this->Html->link( $this->Html->image( $videoImage, array( 'alt' => 'No Image' ) ), array( 'controller' => 'videos', 'action' => 'details', $psong->ProdID ), array( 'escape' => false ) ); ?>
					</div>
					<div class="video-info">
						<div class="video-title">
							<?=$this->Html->link( $this->getTextEncode( $psong->VideoTitle ), array( 'controller' => 'videos', 'action' => 'details', $psong->ProdID ) ); ?>
						</div>
						<div class="artist">
						<?php 
							$artistText = str_replace( '/', '@', base64_encode( $psong->ArtistText ) );
							$linkArtist = $this->getTextEncode( $psong->ArtistText );
						?>
							by <?=$this->Html->link( $linkArtist, array( 'controller' => 'artists', 'action' => 'album', $artistText, base64_encode( $psong->Genre ) ) ); ?>
						</div>
						<div class="release-date">
							Released on
						<?php
							$sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
							echo date( "M d, Y", strtotime( $sales_date ) );
						?>
						</div>
						<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>
								<span class="wishlist-btn" title="Add to Wishlist" onclick='Javascript: addToWishlistVideo("<?php echo $psong->ProdID; ?>", "<?php echo $psong->provider_type; ?>", 1);'></span>
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
															<?php echo $this->Html->link( '', 'javascript:void(0)', array( 'class' => 'download-btn', 'title' => 'Download This Video', 'onclick' => "return wishlistVideoDownloadOthersToken('{$psong->ProdID}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$psong->provider_type}', 1)", 'escape' => false ) ); ?>
														<![endif]>
														<!--[if IE]>
							                                <?php echo $this->Html->link( '', 'javascript:void(0)', array( 'class' => 'download-btn', 'title' => 'Download This Video', 'onclick' => "wishlistVideoDownloadIEToken('{$psong->ProdID}', '0', '{$psong->provider_type}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', 1)", 'escape' => false ) ); ?>
							                            <![endif]-->
													</span>
													<span class="afterClick" id="vdownloading_<?= $psong->ProdID; ?>" style="display: none; float: left"></span>
													<span id="vdownload_loader_<?= $psong->ProdID; ?>" style="display: none; float: right;"><?= $html->image( 'ajax-loader_black.gif' ); ?> </span>
												</form>
											</div>
								<?php 	} else {
											echo $this->Html->link( '', array('controller' => 'homes', 'action' => 'my_history' ), array( 'title' => 'You have already download this video. Get it from your recent downloads', 'class' => 'download-btn video-downloaded') );
								 		}
									} else {
								  		echo $this->Html->link( '', '#', array( 'class' => 'download-btn download-limit-met', 'title' => 'Your download limit has been met.' ) );
									}
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

            
		<header>
			<h3 class="composers-header">
				More Composers Like <span><?php echo $keyword; ?> </span>
			</h3>
		</header>
           
			
		<div class="search-results-list">
		 <?php if (!empty($composers)) {
				?>	
			<ul>


				<?php
					foreach ( $composers as $composer ) {

						$composer_name = str_replace('"', '', $composer->Composer);
						$composer_name = $this->Search->truncateText( $composer_name, 125, $this );
						$composer_name = $this->getTextEncode($composer_name);
						if ( $composer_name != '' && true == is_numeric( $composer->numFound ) ) {
				?>
							<li><?php echo $this->Html->link( $composer_name, array( 'controller' => 'artists', 'action' => 'composer', base64_encode( $composer->Composer ), 1 ), array( 'title' => $composer_name ) )?></li>
				<?php
						}
					}
				?>
			</ul>
			<?php

			$searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
			$pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
			} else {
				?>
			<div style="color: red; padding: 50px;">
				<span>No Composers Found</span>
			</div>
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
				<?php 
					if ( isset( $albumData ) && !empty( $albumData ) ) {
						echo $this->Html->link( '', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'album' ) ), array( 'title' => 'See More Albums', 'class' => 'see-more' ) );
					}
				?>
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
									<?php echo $this->Html->link( 
												$this->Html->image( $image, array( 'alt' => $album_title, 'width' => 162, 'height' => 162 ) ),
												array( 'controller' => 'artists', 'action' => 'view', $linkArtistText, $palbum->ReferenceID, $linkProviderType ),
												array( 'title' => $this->getTextEncode( $palbum->Title ), 'escape' => false )
											);
									?>
								<?php if ( isset( $patronId ) && !empty( $patronId ) ) { ?>
										<input type="hidden" id="<?= $palbum->ReferenceID ?>" value="album" data-provider="<?= $palbum->provider_type ?>" />
							<?php
									  	if ( isset( $libraryType ) && isset( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) && $libraryType == 2 && !empty( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID] ) ) {
											echo $this->Queue->getAlbumStreamLabel( $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 1 );
											echo $this->Html->link('', '#', array( 'title' => 'Add to a Playlist or Create a New Playlist', 'class' => 'playlist-menu-icon toggleable no-ajaxy' ) );
							?>
											<ul><li><?php echo $this->Html->link( 'Create New Playlist...', '#', array( 'class' => 'create-new-playlist' ) );?></li></ul>
									<?php 
									  	} 
										
										echo $this->Html->link( '', '#', array( 'class' => 'wishlist-icon toggleable no-ajaxy', 'title' => 'Add to Wishlist' ) );
									}
									?>
									</div>
									<div class="album-info">
										<p class="title">
											<?php echo $this->Html->link( $album_title, array( 'controller' => 'artists', 'action' => 'view', $linkArtistText, $palbum->ReferenceID, $linkProviderType), array( 'title' => $this->getTextEncode( $palbum->Title ) ) );?>
										</p>
										<p class="artist">
											Genre: <span> <?php echo $this->Html->link( $album_genre, 'javascript:void(0)' );?></span>
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
				<?php 
					if ( isset( $artists ) && !empty( $artists ) ) {
						echo $this->Html->link( '', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'artist' ) ), array('class' => 'see-more', 'title' => 'See More Artists'));
				 	}
				?>
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

				<?php
                              
				if (!empty($composers)) {
					?>
				<a class="see-more"
					href="/search/index?q=<?php echo $keyword; ?>&type=composer"
					title="See More Composers"></a>
				<?php
				}
				?>
			</header>
			<div class="search-results-list">

				<?php
				if (!empty($composers)) {
					?>
				<ul>
					<?php
                                        $composerFlag = 0;
					foreach ($composers as $composer) {
						$tilte = urlencode($composer->Composer);
						$composer_name = $this->Search->truncateText($this->getTextEncode($composer->Composer), 125, $this);
						if (!empty($composer_name)) {
                                                    $composerFlag =1;
							?>
					<li><a
						href="/artists/composer/<?= base64_encode($composer->Composer); ?>/1"
						title="<?php echo $this->getTextEncode($composer->Composer) ?>"><?php echo str_replace('"', '', $this->getTextEncode($composer_name)); ?>
					</a>
					</li>
					<?php
						}
					}
                                        if($composerFlag == 0){
                                            ?>
                                        <li>
						<div style="color: red;">
							<span>No Composers Found</span>
						</div>
					</li>
                                        
                                        <?php
                                        }
					?>
				</ul>
				<?php
				} else {
					?>
				<ul>
					 <li>
						<div style="color: red;">
							<span>No Composers Found</span>
						</div>

					</li>
                                       
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results videos-results">
			<header>
				<h3 class="videos-header">Videos</h3>
				<?php 
					if ( isset( $videos ) && !empty( $videos ) ) {
						echo $this->Html->link( '', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'video' ) ), array( 'title' => 'See More Videos', 'class' => 'see-more' ) );
					} 
				?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $videos ) && is_array( $videos ) && count( $videos ) > 0 ) { ?>
				<ul>
				<?php 	foreach ( $videos as $video ) {
						$video_name_text = $this->Search->truncateText( $this->getTextEncode( $video->VideoTitle ), 125, $this );
						$name 			 = $this->getTextEncode( $video->VideoTitle );
						$video_name_text = ($name != "false") ? $video_name_text : ""
				?>
						<li> <?php echo $this->Html->link( $video_name_text, array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $video->VideoTitle, 'type' => 'video' ) ), array( 'title' => $name ) ); ?> 
						</li>
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
				<?php 
					if ( isset( $genres ) && !empty( $genres ) ) {
						echo $this->Html->link( '', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $keyword, 'type' => 'genre' ) ), array( 'title' => 'See More Genres', 'class' => 'see-more' ) );
					} 
				?>
			</header>
			<div class="search-results-list">
				<?php if ( isset( $genres ) && is_array( $genres ) && count( $genres ) ) { ?>
				<ul>
				<?php	foreach ( $genres as $genre ) {

							$genre_name 	 = str_replace( '"', '', $genre->Genre );
							$genre_name		 = $this->getTextEncode( $genre_name );
							$genre_name_text = $this->Search->truncateText( $genre_name , 125, $this );

							if ( !empty( $genre_name_text ) ) {
				?>
								<li> <?php echo $this->Html->link( $genre_name_text . ' (' . $genre->numFound . ')', array( 'controller' => 'search', 'action' => 'index', '?' => array( 'q' => $genre_name, 'type' => 'genre' ) ), array( 'title' => $genre_name ) ); ?> 
								</li>
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
						<span class="artist">Artist</span>
					</div>
					<div class="artist-border header-border"></div>
					<div class="composer-col">
						<span class="composer">Composer</span>
					</div>
					<div class="composer-border header-border"></div>
					<div class="album-col">
						<span class="album">Album</span>
					</div>
					<div class="album-border header-border"></div>
					<div class="song-col">
						<span class="song">Song</span>
					</div>
					<?php 
						if ( isset( $patronId ) && !empty( $patronId ) ) {
							echo $this->Html->link( '', '#', array( 'title' => 'Select All, Clear All, Add to Wishlist, or Add to Playlist', 'class' => 'multi-select-icon no-ajaxy' ) );
					?>
							<section class="options-menu">
								<ul>
									<li><?php echo $this->Html->link( 'Select All', '#', array( 'class' => 'select-all no-ajaxy' ) ); ?></li>
									<li><?php echo $this->Html->link( 'Clear All', '#', array( 'class' => 'clear-all no-ajaxy' ) ); ?></li>
									<li><?php echo $this->Html->link( 'Add to Wishlist', '#', array( 'class' => 'add-all-to-wishlist no-ajaxy' ) ); ?></li>
									<?php if ( isset( $libraryType ) && $libraryType == 2 ): ?>
											<li> <?php echo $this->Html->link( 'Add to Playlist', '#', array( 'class' => 'add-to-playlist no-ajaxy' ) ); ?> </li>
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
											<span title='<?= str_replace( '"', '', $this->getTextEncode( $psong->Composer ) ); ?>'><?= $this->Search->truncateText( str_replace( '"', '', $this->getTextEncode( $psong->Composer ) ), 25, $this ); ?> </span>
										</div>
										<div class="album album-name">
											<?php echo $this->Html->link( str_replace('"', '', $this->Search->truncateText($this->getTextEncode($psong->Title), 25, $this)), array( 'controller' => 'artists', 'action' => 'view', str_replace( '/', '@', base64_encode( $psong->ArtistText ) ), $psong->ReferenceID, base64_encode( $psong->provider_type ) ), array( 'title' => $this->getTextEncode($psong->Title) ) ); ?>
										</div>
										<div class="song song-name" sdtyped="<?php echo $downloadFlag . '-' . $StreamFlag . '-' . $territory; ?>">
										<?php $showSongTitle = $this->Search->truncateText( $psong->SongTitle, strlen( $psong->SongTitle ), $this ); ?>
											<span style="text-decoration: none;" title="<?= str_replace( '"', '', $this->getTextEncode( $showSongTitle ) ); ?>"><?= $this->Search->truncateText( $this->getTextEncode( $psong->SongTitle ), 21, $this ); ?>
										<?php
											if ($psong->Advisory == 'T') {
												echo '<font class="explicit"> (Explicit)</font>';
											}
										?> </span>
										</div>
										<?php if ( isset( $patronId ) && !empty( $patronId ) ) {
												echo $this->Html->link( '', '#', array( 'title' => 'Add To a Playlist, Wishlist, or Download', 'class' => 'menu-btn no-ajaxy' ) );
										?>
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
																						<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'title' => $titleSong, 'onclick' => "return wishlistDownloadOthersHome('{$psong->ProdID}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$psong->provider_type}')", 'escape' => false ) ); ?> 
																					<![endif]>
																					<!--[if IE]>
						                                                            	<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'no-ajaxy top-10-download-now-button', 'id' => 'song_download_' . $psong->ProdID, 'title' => $titleSong, 'onclick' => "wishlistDownloadIEHome('{$psong->ProdID}', '0', '{$psong->provider_type}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')", 'escape' => false ) ); ?>
		                                                                            <![endif]-->
																				</span>
																				<span class="afterClick" id="downloading_<?= $psong->ProdID; ?>" style="display: none;">
																					<span class="add-to-wishlist"><?php __("Please Wait.."); ?>
																						<span id="wishlist_loader_<?= $psong->ProdID; ?>" style="float: right; padding-right: 8px; padding-top: 2px;"><?= $html->image( 'ajax-loader_black.gif' ); ?> </span> 
																					</span>
																				</span>
																			</form>
																		</div>
														<?php 		} else {
														 				echo $this->Html->link( 'Downloaded', array( 'controller' => 'homes', 'action' => 'my_history' ), array( 'title' => 'You have already downloaded this song. Get it from your recent downloads', 'class' => 'top-100-download-now-button' ) );
																	}
																} else {
																	echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'top-100-download-now-button' ) );
																}
															} else {
																$sales_date = $this->Search->getSalesDate( $psong->TerritorySalesDate, $territory );
																if ( isset( $sales_date ) ) {
																	$sales_date = date( "F d Y", strtotime( $sales_date ) );
																}

															 	echo $this->Html->link(
																		$this->Html->tag( 'span', 'Coming Soon ( ' . $sales_date . ' )', array( 'title' => 'Coming Soon' ) ), 
																		'javascript:void(0)', array( 'class' => 'top-100-download-now-button' ) 
																	);
														 	} ?>
														</li>
														<li>
														<?php
															$wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
						
															if ( $wishlistInfo == 'Added To Wishlist' ) {
																echo $this->Html->link( 'Added to Wishlist', '#' );
															} else {
														?>
																<span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
																	<?php echo $this->Html->link( 'Add to Wishlist', '#', array( 'class' => 'add-to-wishlist no-ajaxy' ) );?>
																</span> 
																<span class="afterClick" style="display: none;">
																	<?php echo $this->Html->link( 'Please Wait...', 'JavaScript:void(0)', array( 'class' => 'add-to-wishlist' ) );?>
																</span>
														<?php
															}
														?>
														</li>
														<?php if ( isset( $libraryType ) && $libraryType == 2 && ( $StreamFlag === 1 ) ): ?>
																<li> <?php echo $this->Html->link( 'Add to Playlist', '#', array( 'class' => 'add-to-playlist no-ajaxy' ) );?> </li>
																</ul>
																<ul class="playlist-menu"> <li> <?php echo $this->Html->link( 'Create New Playlist', '#', array( 'class' => 'no-ajaxy' ) );?> </li> </ul>
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
