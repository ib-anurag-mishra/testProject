<?php
/*
 File Name : advance_search.ctp
File Description : View page for advance search
Author : m68interactive
*/

function createPagination($html, $currentPage, $facetPage, $type = 'listing', $totalPages, $pageLimitToShow, $queryString = null) {
	$queryString = html_entity_decode($queryString);
	if ($totalPages > 1) {
		$part = floor($pageLimitToShow / 2);
		if ($type == 'listing') {
			if (1 != $currentPage) {
				$pagination_str .= $html->link('<button class="beginning" ></button>', "/search/index/" . $queryString, array('escape' => FALSE));
				$pagination_str .= $html->link('<button class="prev" ></button>', "/search/index/" . ($currentPage - 1) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
			} else {
				$pagination_str .= '<button class=
				"beginning" style="cursor:text;" ></button>';
				$pagination_str .= '<button class="prev" style="cursor:text;" ></button>';
			}
		} else if ($type == 'block') {
			if (1 != $facetPage) {
				$pagination_str .= $html->link('<button class="beginning"></button>', "/search/index/" . $queryString, array('escape' => FALSE));
				$pagination_str .= $html->link('<button class="prev"></button>', "/search/index/" . $currentPage . '/' . ($facetPage - 1) . '/' . $queryString, array('escape' => FALSE));
			} else {
				$pagination_str .= '<button class="beginning" style="cursor:text;"></button>';
				$pagination_str .= '<button class="prev" style="cursor:text;" ></button>';
			}
		}

		$pagination_str .= " ";
		if ($type == 'listing') {
			if ($currentPage <= $part) {
				$fromPage = 1;
				$topage = $currentPage + ($pageLimitToShow - $currentPage);
				$topage = (($topage <= $totalPages) ? $topage : $totalPages);
			} elseif ($currentPage >= ($totalPages - $part)) {
				$fromPage = ($currentPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($currentPage - ($pageLimitToShow - ($totalPages - $currentPage))) + 1);
				$topage = $totalPages;
				$fromPage = (($fromPage > 1) ? $fromPage : 1);
			} else {
				$fromPage = $currentPage - $part;
				$topage = $currentPage + $part;
			}
		} else if ($type == 'block') {
			if ($facetPage <= $part) {
				$fromPage = 1;
				$topage = $facetPage + ($pageLimitToShow - $facetPage);
				$topage = (($topage <= $totalPages) ? $topage : $totalPages);
			} elseif ($facetPage >= ($totalPages - $part)) {
				$fromPage = ($facetPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($facetPage - ($pageLimitToShow - ($totalPages - $facetPage))) + 1);
				$topage = $totalPages;
				$fromPage = (($fromPage > 1) ? $fromPage : 1);
			} else {
				$fromPage = $facetPage - $part;
				$topage = $facetPage + $part;
			}
		}
		$classCounter = 1;
		for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++) {
			if ($type == 'listing') {
				if ($currentPage == $pageCount) {

					$pagination_str .= '<button class="page-' . $classCounter . '" style="cursor:text; background: none repeat scroll 0 0 #808080;
					color: #FFFFFF;" >' . $pageCount . '</button>';
				} else {

					$pagination_str .= $html->link('<button class="page-' . $classCounter . '">' . $pageCount . '</button>', '/search/index/' . ($pageCount) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
				}
			} else if ($type == 'block') {
				if ($facetPage == $pageCount) {

					$pagination_str .= '<button class="page-' . $classCounter . '" style="cursor:text;background: none repeat scroll 0 0 #808080;
					color: #FFFFFF;" >' . $pageCount . '</button>';
				} else {

					$pagination_str .= $html->link('<button class="page-' . $classCounter . '">' . $pageCount . '</button>', '/search/index/' . $currentPage . '/' . $pageCount . '/' . $queryString, array('escape' => FALSE));
				}
			}
			$pagination_str .= " ";
			$classCounter++;
		}
		$pagination_str .= " ";

		if ($type == 'listing') {
			if ($currentPage != $totalPages) {
				$pagination_str .= $html->link('<button class="next"></button>', '/search/index/' . ($currentPage + 1) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
				$pagination_str .= $html->link('<button class="last"></button>', '/search/index/' . $totalPages . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
			} else {
				$pagination_str .= '<button class="next" style="cursor:text;"></button>';
				$pagination_str .= '<button class="last" style="cursor:text;"></button>';
			}
		} else if ($type == 'block') {
			if ($facetPage != $totalPages) {
				$pagination_str .= $html->link('<button class="next"></button>', '/search/index/' . $currentPage . '/' . ($facetPage + 1) . '/' . $queryString, array('escape' => FALSE));
				$pagination_str .= $html->link('<button class="last"></button>', '/search/index/' . $currentPage . '/' . $totalPages . '/' . $queryString, array('escape' => FALSE));
			} else {
				$pagination_str .= '<button class="next" style="cursor:text;"></button>';
				$pagination_str .= '<button class="last" style="cursor:text;"></button>';
			}
		}
	} else {
		$pagination_str = '';
	}

	return $pagination_str;
}

function truncate_text($text, $char_count, $obj = null, $truncateByWord = true) {

	if (strlen($text) > $char_count) {
		$modified_text = substr($text, 0, $char_count);
		if ($truncateByWord == true) {
			$modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
		}
		$modified_text = substr($modified_text, 0, $char_count) . "...";
	} else {
		$modified_text = $text;
	}

	return $obj->getTextEncode($modified_text);
}

//Code for check Sales date
function Get_Sales_date($sales_date_array, $country) {
	$Sales_date = '';
	if (is_array($sales_date_array)) {
		foreach ($sales_date_array as $TerritorySalesDate) {
			$Territory_date_array = explode("_", $TerritorySalesDate);
			if (is_array($sales_date_array)) {
				$Territory = $Territory_date_array[0];
			}

			if ($country == $Territory) {
				$Sales_date = $Territory_date_array[1];
				break;
			}
		}
	}

	return $Sales_date;
}
?>
<section class="search-page">
	<div class="breadcrumbs">
		<?php
		$html->addCrumb(__('Search Results', true));
		echo $html->getCrumbs(' > ', __('Home', true), '/homes');
		?>
	</div>
	<?php
	switch ($type) {
		case 'song':
			$search_category = 'search-results-songs-page';
			break;
		case 'album':
			$search_category = 'search-results-albums-page';
			break;
		case 'genre':
			$search_category = 'search-results-genres-page';
			break;
		case 'video':
			$search_category = 'search-results-videos-page';
			break;
		case 'artist':
			$search_category = 'search-results-artists-page';
			break;
		case 'composer':
			$search_category = 'search-results-composers-page';
			break;
		case 'all':
			$search_category = 'search-results-all-page';
			break;
		default:
			break;
	}
	?>
	<section class="<?php echo $search_category; ?>">
		<div class="faq-link">
			Need Help? Visit our <a href="/questions">FAQ Section.</a>
		</div>
		<div class="search-results-heading">
			Results for your search
			<?php echo $keyword; ?>
		</div>
		<div class="refine-text">Not what you're looking for? Refine your
			search below.</div>
		<div class="filter-container clearfix">
			<?php
			if ($type != 'all') {
				?>
			<a
				href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=all">All
				Music</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">All Music</a>
			<?php
			}
			?>
			<?php
			if ($type != 'album') {
				?>
			<a
				href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=album">Albums</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Albums</a>
			<?php
			}
			?>
			<?php
			if ($type != 'artist') {
				?>
			<a
				href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=artist">Artists</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Artists</a>
			<?php
			}
			?>
			<?php
			if ($type != 'composer') {
				?>
			<a href="/search/index?q=<?php echo $keyword; ?>&type=composer">Composers</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Composers</a>
			<?php
			}
			?>
			<?php
			if ($type != 'genre') {
				?>
			<a href="/search/index?q=<?php echo $keyword; ?>&type=genre">Genres</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Genres</a>
			<?php
			}
			?>
			<?php
			if ($type != 'video') {
				?>
			<a href="/search/index?q=<?php echo $keyword; ?>&type=video">Videos</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Videos</a>
			<?php
			}
			?>
			<?php
			if ($type != 'song') {
				?>
			<a href="/search/index?q=<?php echo $keyword; ?>&type=song">Songs</a>
			<?php
			} else {
				?>
			<a href="javascript:void(0)" class="active">Songs</a>
			<?php
			}
			?>

			<div class="search-container">
				<form method="get" id="searchQueryForm"
					action="<?php echo $_SERVER['PHP_SELF']; ?>"
					onsubmit="ajaxSearchPage();
                        return false;">
					<input type="search" name="q" id="query"
						value="<?php echo urldecode($keyword); ?>" /> <input type="hidden"
						id="search_type"
						value="<?php echo (isset($type) && !empty($type)) ? $type : 'all' ?>"
						name="type"> <input type="submit" id="search-page-go" value="Go">
				</form>
			</div>
		</div>


		<?php if (!empty($type) && $type != 'all') { ?>

		<?php
		switch ($type) {
			case 'song':
				$reverseSortOrder = (($sortOrder == 'asc') ? 'desc' : 'asc');
				?>
		<div class="songs">
			<header>
				<h3 class="songs-header">Songs</h3>
			</header>

			<div class="header-container">
				<div class="song-header">
					<a
						href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=song&sortOrder=" . (($sort == 'song') ? $reverseSortOrder : 'asc'); ?>"><span
						class="song">Song</span> </a>
				</div>
				<div class="song-border header-border"></div>
				<div class="artist-header">
					<a
						href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=artist&sortOrder=" . (($sort == 'artist') ? $reverseSortOrder : 'asc'); ?>"><span
						class="artist">Artist</span> </a>
				</div>
				<div class="artist-border header-border"></div>
				<div class="album-header">
					<a
						href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=album&sortOrder=" . (($sort == 'album') ? $reverseSortOrder : 'asc'); ?>"><span
						class="album">Album</span> </a>
				</div>
				<div class="album-border header-border"></div>
				<div class="time-header">Time</div>
				<?php
				if ($this->Session->read("patron")) {
					?>
				<a class="multi-select-icon no-ajaxy" href="#"
					title="Select All, Clear All, Add to Wishlist, Add to Playlist"></a>
				<section class="options-menu">
					<ul>
						<li><a class="select-all no-ajaxy" href="#">Select All</a></li>
						<li><a class="clear-all no-ajaxy" href="#">Clear All</a></li>
						<li><a class="add-all-to-wishlist no-ajaxy" href="#">Add to
								Wishlist</a></li>
						<?php if ($this->Session->read('library_type') == 2) { ?>
						<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a>
						</li>
						<?php } ?>
					</ul>
					<ul class="playlist-menu">

					</ul>
				</section>
				<?php
				}
				?>
			</div>

			<div class="rows-container">
				<?php
				if (!empty($songs)) {
					$i = 1;
					$country = $this->Session->read('territory');
					foreach ($songs as $psong) {

						$downloadFlag = $this->Search->checkDownloadForSearch($psong->TerritoryDownloadStatus, $psong->TerritorySalesDate, $this->Session->read('territory'));
						$StreamFlag = $this->Search->checkStreamingForSearch($psong->TerritoryStreamingStatus, $psong->TerritoryStreamingSalesDate, $this->Session->read('territory'));

						//if song not allowed for streaming and not allowed for download then this song must not be display
						if ($downloadFlag === 0 && $StreamFlag === 0) {
							continue;
						}
						?>

				<div class="row">

					<?php
					if ($this->Session->read('library_type') == 2) {
						$filePath = $this->Token->streamingToken($psong->CdnPathFullStream . "/" . $psong->SaveAsNameFullStream);


						if (!empty($filePath)) {
							$songPath = explode(':', $filePath);
							$streamUrl = trim($songPath[1]);
							$psong->streamUrl = $streamUrl;
							$psong->totalseconds = $this->Queue->getSeconds($psong->FullLength_Duration);
						}
					}
					?>


					<?php
					if ($this->Session->read("patron")) {
						if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) {
							if ('T' == $psong->Advisory) {
								$song_title = $psong->SongTitle . '(Explicit)';
							} else {
								$song_title = $psong->SongTitle;
							}
							echo $this->Queue->getsearchSongsStreamNowLabel($psong->streamUrl,$song_title,$psong->ArtistText,$psong->totalseconds,$psong->ProdID,$psong->provider_type);
						} else {
							echo $html->image('sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode($psong->provider_type) . '", "' . $this->webroot . '");'));
							echo $html->image('sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
							echo $html->image('sample-stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
						}
					}

					if ($this->Session->read("patron")) {
						$style = '';
						$styleSong = '';
					} else {
						$styleSong = "style='left:12px'";
					}
					?>

					<div class="artist artist-name">
						<?php echo $html->link(str_replace('"', '', truncate_text($psong->ArtistText, 20, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($psong->ArtistText))), array('title' => $this->getTextEncode($psong->ArtistText))); ?>
					</div>
					<div class="album album-name">
						<a
							href="/artists/view/<?php echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID; ?>/<?php echo base64_encode($psong->provider_type); ?>"
							title="<?php echo $this->getTextEncode($psong->Title); ?> "><?php echo str_replace('"', '', truncate_text($this->getTextEncode($psong->Title), 25, $this)); ?>
						</a>
					</div>
					<div class="song song-name" <?php echo $styleSong; ?>
						sdtyped="<?php echo $downloadFlag . '-' . $StreamFlag . '-' . $this->Session->read('territory'); ?>">
						<?php $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>
						<a style="text-decoration: none;"
							title="<?php echo str_replace('"', '', $this->getTextEncode($showSongTitle)); ?>"><?php echo truncate_text($this->getTextEncode($psong->SongTitle), 21, $this); ?>
							<?php
							if ($psong->Advisory == 'T') {
								echo '<font class="explicit"> (Explicit)</font>';
							}
							?> </a>
					</div>
					<div class="time">
						<?php
						$timeDur = explode(':', $psong->FullLength_Duration);
						if ($timeDur[0] != "0") {
							echo ltrim($psong->FullLength_Duration, "0");
						} elseif ($timeDur[0] == "00")
						echo "0" . ltrim($psong->FullLength_Duration, "0");
						else {
							echo $psong->FullLength_Duration;
						}
						?>

					</div>
					<?php
					if ($this->Session->read("patron")) {
						?>
					<a class="menu-btn no-ajaxy" href="#"
						title="Add to Wishlist, Playlist, or Download"></a>
					<section class="options-menu">
						<input type="hidden" id="<?= $psong->ProdID ?>" value="song"
							data-provider="<?= $psong->provider_type ?>" />
						<ul>
							<li><?php
							if ($this->Session->read('patron')) {
								if ($downloadFlag === 1) {
									$productInfo = $song->getDownloadData($psong->ProdID, $psong->provider_type);

									if ($libraryDownload == '1' && $patronDownload == '1') {
										if ($psong->status != 'avail') {
											?> <span class="top-100-download-now-button">
									<form method="Post" id="form<?php echo $psong->ProdID; ?>"
										action="/homes/userDownload" class="suggest_text1">
										<input type="hidden" name="ProdID"
											value="<?php echo $psong->ProdID; ?>" /> <input type="hidden"
											name="ProviderType"
											value="<?php echo $psong->provider_type; ?>" /> <span
											class="beforeClick" style="cursor: pointer;"
											id="wishlist_song_<?php echo $psong->ProdID; ?>"> <![if !IE]>
											<a href='javascript:void(0);'
											class="no-ajaxy top-10-download-now-button"
											title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
											onclick='return wishlistDownloadOthersHome("<?php echo $psong->ProdID; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $psong->provider_type; ?>");'>
												<?php __('Download Now'); ?>
										</a> <![endif]> <!--[if IE]>
                                                                                           <a id="song_download_<?php echo $psong->ProdID; ?>" 
                                                                                                class="no-ajaxy top-10-download-now-button" 
                                                                                                title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                                                onclick='wishlistDownloadIEHome("<?php echo $psong->ProdID; ?>", "0" , "<?php echo $psong->provider_type; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' 
                                                                                                href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                                                    <![endif]-->
										</span> <span class="afterClick"
											id="downloading_<?php echo $psong->ProdID; ?>"
											style="display: none;"><a class="add-to-wishlist"><?php __("Please Wait.."); ?>
												<span id="wishlist_loader_<?php echo $psong->ProdID; ?>"
												style="float: right; padding-right: 8px; padding-top: 2px;"><?php echo $html->image('ajax-loader_black.gif'); ?>
											</span> </a> </span>
									</form>
							</span> <?php
										} else {
											?> <a class="top-100-download-now-button"
								href='/homes/my_history'
								title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?>
							</a> <?php
										}
									} else {
										?> <a class="top-100-download-now-button"
								href="javascript:void(0);"><?php __("Limit Met"); ?> </a> <?php
									}
								} else {
									?> <a class="top-100-download-now-button"
								href="javascript:void(0);"> <span
									title='<?php __("Coming Soon"); ?> ( <?php
                                                    $sales_date = Get_Sales_date($psong->TerritorySalesDate, $this->Session->read('territory'));
                                                    if (isset($sales_date)) {
                                                        echo date("F d Y", strtotime($sales_date));
                                                    }
                                                    ?> )'> <?php __("Coming Soon"); ?>
								</span>
							</a> <?php }
							?>
							</li>
							<li><?php
							$wishlistInfo = $wishlist->getWishlistData($psong->ProdID);

							if ($wishlistInfo == 'Added To Wishlist') {
								?> <a href="#">Added to Wishlist</a> <?php
							} else {
								?> <span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
									<a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
							</span> <span class="afterClick" style="display: none;"><a
									class="add-to-wishlist" href="JavaScript:void(0);">Please
										Wait...</a> </span> <?php
							}
							?>
							</li>
							<?php } ?>
							<?php if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) { ?>
							<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a>
							</li>
						</ul>
						<ul class="playlist-menu">
							<li><a href="#">Create New Playlist</a></li>
						</ul>
						<?php } ?>
					</section>
					<?php if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) { ?>
					<input type="checkbox" class="row-checkbox">
					<?php } else {
						?>
					<div class="sample-icon"></div>
					<?php
					}
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
				if (isset($type)) {
					$keyword = "?q=" . $keyword . "&type=" . $type;
				}
				?>
				<?php
				$keyword = $keyword . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
				echo createPagination($html, $currentPage, $facetPage, 'listing', $lastPage, 5, $keyword);
				?>

			</div>
		</div>
		<?php
		break;
case 'album':
	?>
		<header>
			<h3 class="albums-header">Albums</h3>

		</header>
		<?php
		if (!empty($albumData)) {
			$i = 0;
			foreach ($albumData as $palbum) {
				$albumDetails = $album->getImage($palbum->ReferenceID,$palbum->provider_type);

				if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])) {
					$albumArtwork = $this->Token->artworkToken($albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
					$image = Configure::read('App.Music_Path') . $albumArtwork;
				} else {
					$image = 'no-image.jpg';
				}
				if ($page->isImage($image)) {
					//Image is a correct one
				} else {

				}
				$album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
				$album_genre = str_replace('"', '', $palbum->Genre);
				$album_label = $palbum->Label;
				$tilte = urlencode($palbum->Title);
				$linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
				$linkProviderType = base64_encode($palbum->provider_type);
				if (!empty($album_label)) {
					$album_label_str = "Label: " . truncate_text($this->getTextEncode($album_label), 32, $this);
				} else {
					$album_label_str = "";
				}
				$ReferenceId = $palbum->ReferenceID;
				if ($palbum->AAdvisory == 'T') {
					$explicit = '<font class="explicit"> (Explicit)</font><br />';
				} else {
					$explicit = '';
				}
				?>
		<div class="album-detail-container">
			<div class="cover-image">
				<a
					href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
					title="<?php echo $this->getTextEncode($palbum->Title); ?>"> <img
					src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>"
					width="162" height="162" />
				</a>
			</div>
			<div class="album-info">
				<div class="album-title">
					<strong><a
						href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
						title="<?php echo $this->getTextEncode($palbum->Title); ?>"><?php echo $album_title; ?>
							<?php echo $explicit; ?> </a> </strong>
				</div>
				<div class="artist">
					by <a class="more-by-artist"
						href="/artists/album/<?php echo str_replace('/', '@', base64_encode($palbum->ArtistText)); ?>/<?= base64_encode($album_genre) ?>">
						<?php echo $this->getTextEncode($palbum->ArtistText); ?>
					</a>

				</div>
				<div class="genre">
					Genre:
					<?php echo $html->link($this->getTextEncode($album_genre), array('controller' => 'genres', 'action' => 'view', '?genre='.$album_genre), array("title" => $this->getTextEncode($album_genre))); ?>
				</div>
				<?php
				if ($palbum->Copyright != '' && $palbum->Copyright != 'Unknown')
                                    {  ?>
				<div class="label">
					<?php  echo $this->getTextEncode($palbum->Copyright); ?>
				</div>
				<?php }
				?>
				<?php
				if ($this->Session->read("patron")) {
					if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) {
						echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 3);
					}
					?>
				<button class="menu-btn"></button>
				<section class="options-menu">
					<input type="hidden" id="<?= $ReferenceId ?>" value="album"
						data-provider="<?= $palbum->provider_type ?>" />
					<ul>
						<li><?php
						$wishlistInfo = $wishlist->getWishlistData($nationalTopSong['Song']['ProdID']);

						if ($wishlistInfo == 'Added To Wishlist') {
							?> <a href="#">Added to Wishlist</a> <?php
						} else {
							?> <span class="beforeClick"
							id="wishlist<?= $palbum->ReferenceID ?>"> <a
								class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
						</span> <span class="afterClick" style="display: none;"><a
								class="add-to-wishlist" href="JavaScript:void(0);">Please
									Wait...</a> </span> <?php
						}
						?>
						</li>
						<?php if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) { ?>
						<li><a class="add-to-playlist no-ajaxy" href="javascript:void(0);">Add
								to Playlist</a></li>
					</ul>
					<ul class="playlist-menu">
						<li><a href="#">Create New Playlist</a></li>
					</ul>
					<?php } ?>
				</section>
				<?php }
				?>
			</div>
		</div>
		<?php
		$i++;
			}
			?>
		<section style="position: relative; width: 866px; right: 21px;"
			class="search-results-songs-page">
			<div class="pagination-container">
				<?php
				$searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
				$pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
				echo $pagination_str;
				?>

			</div>
		</section>



		<?php } else { ?>
		<div class="album-detail-container">
			<div style="color: red; padding: 50px;">
				<span>No Albums Found</span>
			</div>
		</div>
		<?php } ?>
		<?php
		break;
case 'genre':
	?>
		<header>
			<h3 class="genres-header">
				More Genres Like <span><?php echo $keyword; ?> </span>
			</h3>
		</header>
		<div class="search-results-list">
			<?php
			if (!empty($genres)) {
				?>
			<ul>
				<?php
				$i = 0;
				foreach ($genres as $genre) {
					$genre_name = str_replace('"', '', $genre->Genre);
					$genre_name_text = truncate_text($genre_name, 125, $this);
					$genre_name_text =$genre_name;
					$tilte = urlencode($genre->Genre);
					$name = $genre->Genre;
					$count = $genre->numFound;
					?>
				<li><a
					href="<?php echo "/genres/album?q=$keyword&type=album&filter=$tilte"; ?>"
					title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $this->getTextEncode($genre_name_text); ?>
						(<?php echo $count; ?>)</a></li>
				<?php
				$i++;
				}
				?>
			</ul>

			<?php
			$searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
			$pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
			} else {
				?>
			<div style="color: red; padding: 50px;">
				<span>No Genres Found</span>
			</div>
			<?php
			}
			?>

		</div>

		<?php
		break;
case 'video':
	?>
		<header>
			<h3 class="videos-header">Videos</h3>

		</header>
		<?php
		if (!empty($songs)) {
			$b = 1;
			foreach ($songs as $psong) {
				?>
		<div class="video-result-container">
			<div class="video-thumb">
				<?php                                    
				$videoArtwork = $this->Token->artworkToken($psong->ACdnPath . "/" . $psong->ASourceURL);
				$VideoImage = Configure::read('App.Music_Path') . $videoArtwork;
				?>
				<a href="/videos/details/<?php echo $psong->ProdID; ?>"><img
					src="<?php echo $VideoImage; ?>"> </a>
			</div>
			<div class="video-info">
				<div class="video-title">
					<a href="/videos/details/<?php echo $psong->ProdID; ?>"><?php echo $this->getTextEncode($psong->VideoTitle); ?>
					</a>
				</div>
				<div class="artist">
					by <a
						href="/artists/album/<?php echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?= base64_encode($psong->Genre) ?>">
						<?php echo $this->getTextEncode($psong->ArtistText); ?>
					</a>
				</div>
				<div class="release-date">
					Released on
					<?php
					$sales_date = Get_Sales_date($psong->TerritorySalesDate, $this->Session->read('territory'));
					echo date("M d, Y", strtotime($sales_date));
					?>
				</div>
				<!-- <div class="video-size">Size: 67.2 MB</div> -->
				<?php
				if ($this->Session->read("patron")) {
					?>

				<a class="wishlist-btn" title="Add to Wishlist"
					onclick='Javascript: addToWishlistVideo("<?php echo $psong->ProdID; ?>", "<?php echo $psong->provider_type; ?>", 1);'></a>
				<?php
				$sales_date = Get_Sales_date($psong->TerritorySalesDate, $this->Session->read('territory'));
				if ($sales_date <= date('Y-m-d')) {
					$productInfo = $mvideo->getDownloadData($psong->ProdID, $psong->provider_type);
					if ($libraryDownload == '1' && $patronDownload == '1') {
						if ($psong->status != 'avail') {
							?>
				<p>
				
				
				<form method="Post" id="form<?php echo $psong->ProdID; ?>"
					action="/videos/download">
					<input type="hidden" name="ProdID"
						value="<?php echo $psong->ProdID; ?>" /> <input type="hidden"
						name="ProviderType" value="<?php echo $psong->provider_type; ?>" />
					<span class="beforeClick"
						id="download_video_<?php echo $psong->ProdID; ?>"> <![if !IE]> <a
						class="download-btn" title="Download This Video"
						onclick='return wishlistVideoDownloadOthersToken("<?php echo $psong->ProdID; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $psong->provider_type; ?>", 1);'></a>
						<![endif]> <!--[if IE]>
                                                               <a class="download-btn" title="Download This Video" onclick='wishlistVideoDownloadIEToken("<?php echo $psong->ProdID; ?>", "0" , "<?php echo $psong->provider_type; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>",1);' href="javascript:void(0);"></a>
                                                        <![endif]-->
					</span> <span class="afterClick"
						id="vdownloading_<?php echo $psong->ProdID; ?>"
						style="display: none; float: left"></span> <span
						id="vdownload_loader_<?php echo $psong->ProdID; ?>"
						style="display: none; float: right;"><?php echo $html->image('ajax-loader_black.gif'); ?>
					</span>
				</form>
				</p>
				<?php
						} else {
							?>
				<a href="/homes/my_history"
					title="You have already download this video. Get it from your recent downloads"
					class="download-btn video-downloaded"></a>
				<?php
						}
                                            }else{ ?>
				<a title="Your download limit has been met."
					class="download-btn download-limit-met"></a>
				<?php }
				}
				}
				?>
			</div>
		</div>
		<?php }
		} else {
			?>

		<div style="color: red; padding: 50px;">
			<span>No Videos Found</span>
		</div>

		<?php }
		?>
		<?php
		break;

case 'artist':
	?>
		<header>
			<h3 class="artists-header">
				More Artists Like <span><?php echo $keyword; ?> </span>
			</h3>
		</header>
		<div class="search-results-list">
			<?php
			if (!empty($artists)) {
				?>
			<ul>

				<?php
				$i = 0;
				foreach ($artists as $artist) {
					$artist_name = str_replace('"', '', $artist->ArttistText);
					$artist_name_text = truncate_text($artist_name, 125, $this);
					$artist_name_text = $artist_name;
					$tilte = urlencode($artist->ArtistText);
					$count = $artist->numFound;
					$link = $html->link(str_replace('"', '', $artist->ArtistText) . " (" . $count . ")", array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))), array('title' => str_replace('"', '', $artist->ArtistText)));
					?>
				<li><?php echo $link; ?></li>
				<?php
				$i++;
				}
				?>
			</ul>

			<?php
			$searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
			$pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
			} else {
				?>
			<div style="color: red; padding: 50px;">
				<span>No Artists Found</span>
			</div>
			<?php
			}
			?>

		</div>

		<?php
		break;
case 'composer':
	?>
            <?php
			if (!empty($composers)) {
				?>
		<header>
			<h3 class="composers-header">
				More Composers Like <span><?php echo $keyword; ?> </span>
			</h3>
		</header>
		<div class="search-results-list">
			
			<ul>

				<?php
				$i = 0;
				foreach ($composers as $composer) {
					$composer_name = str_replace('"', '', $composer->Composer);
					$full_composer_name = $composer_name;
					$composer_name = truncate_text($composer_name, 125, $this);
					$tilte = urlencode($composer->Composer);
					$name = $composer->Composer;
					$count = $composer->numFound;
					$name = $this->getTextEncode($name);
					if ( "" !=  $this->getTextEncode($composer_name) && true == is_numeric($count) ) {
						?>
				<li><a
					href="/artists/composer/<?= base64_encode($composer->Composer); ?>/1"
					title="<?php echo $this->getTextEncode($full_composer_name); ?>"><?php echo $this->getTextEncode($composer_name); ?>
				</a></li>
				<?php
					}
					$i++;
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
			

		</div>
                <?php
		 }
		?>
		<?php
		break;
case 'all':
	$search_category = 'search-results-all-page';
	break;
default:
	break;
		}
		?>


		<?php } else { ?>

		<section class="category-results album-results">
			<header>
				<h3 class="albums-header">Albums</h3>
				<?php if (!empty($albumData)) { ?>
				<a class="see-more"
					href="/search/index?q=<?php echo $keyword; ?>&type=album"
					title="See More Albums"></a>
				<?php } ?>
			</header>
			<div class="search-results-all-albums-carousel">
				<?php if (!empty($albumData)) { ?>
				<div class="search-results-albums">
					<ul class="clearfix">
						<?php
						$i = 0;
						foreach ($albumData as $palbum) {
							?>
						<li><?php
						$albumDetails = $album->getImage($palbum->ReferenceID,$palbum->provider_type);
						if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])) {
							$albumArtwork = $this->Token->artworkToken($albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
							$image = Configure::read('App.Music_Path') . $albumArtwork;
						} else {
							$image = 'no-image.jpg';
						}
						if ($page->isImage($image)) {
							//Image is a correct one
						} else {

						}
						$album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
						$title = urlencode($palbum->Title);
						$album_genre = str_replace('"', '', $palbum->Genre);
						$tilte = urlencode($palbum->Title);
						$album_label = $palbum->Label;
						$linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
						$linkProviderType = base64_encode($palbum->provider_type);
						$ReferenceId = $palbum->ReferenceID;
						if ($palbum->AAdvisory == 'T') {
							$explicit = '<font class="explicit"> (Explicit)</font><br />';
						} else {
							$explicit = '';
						}
						if (!empty($album_label)) {
							$album_label_str = "Label: " . truncate_text($album_label, 32, $this);
						} else {
							$album_label_str = "";
						}
						?>
							<div class="album-cover-container">
								<a
									href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
									title="<?php echo $this->getTextEncode($palbum->Title); ?>"> <img
									src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>"
									width="162" height="162" />
								</a>
								<?php
								if ($this->Session->read("patron")) {
									?>
								<input type="hidden" id="<?= $palbum->ReferenceID ?>"
									value="album" data-provider="<?= $palbum->provider_type ?>" />
								<?php
								if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) {
									echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 1);
									?>
								<a class="playlist-menu-icon toggleable no-ajaxy"
									title="Add to a Playlist or Create a New Playlist" href="#"></a>
								<ul>
									<li><a href="#" class="create-new-playlist">Create New Playlist
											...</a></li>

								</ul>
								<?php
								}
								?>
								<a class="wishlist-icon toggleable no-ajaxy"
									title="Add to Wishlist" href="#"></a>
								<?php
								}
								?>

							</div>
							<div class="album-info">
								<p class="title">
									<a
										href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
										title="<?php echo $this->getTextEncode($palbum->Title); ?>"> <?php echo $album_title; ?>
									</a>
								</p>
								<p class="artist">
									Genre: <span><a href="javascript:void(0)"><?php echo $album_genre; ?>
									</a> </span>
								</p>
								<p class="label">
									<?php echo $album_label_str; ?>
								</p>
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
				<?php } else { ?>
				<ul>
					<li>
						<div style="color: red;">
							<span>No Albums Found</span>
						</div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results artist-results">
			<header>
				<h3 class="artists-header">Artists</h3>
				<?php
				if (!empty($artists)) {
					?>
				<a class="see-more"
					href="/search/index?q=<?php echo $keyword; ?>&type=artist"
					title="See More Artists"></a>
				<?php
				}
				?>
			</header>
			<div class="search-results-list">
				<?php
				if (!empty($artists)) {
					?>
				<ul>
					<?php
					foreach ($artists as $artist) {
						$tilte = urlencode($artist->ArtistText);
						$artist_name_text = truncate_text($this->getTextEncode($artist->ArtistText), 125, $this);
						$link = $html->link(str_replace('"', '', $artist->ArtistText), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))), array('title' => $this->getTextEncode($artist->ArtistText)));
						if (!empty($artist_name_text)) {
							?>
					<li><?php echo $link; ?><span>(<?php echo $artist->numFound; ?>)
					</span></li>
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
						<div style="color: red;">
							<span>No Artists Found</span>
						</div>
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
					foreach ($composers as $composer) {
						$tilte = urlencode($composer->Composer);
						$composer_name = truncate_text($this->getTextEncode($composer->Composer), 125, $this);
						if (!empty($composer_name)) {
							?>
					<li><a
						href="/artists/composer/<?= base64_encode($composer->Composer); ?>/1"
						title="<?php echo $this->getTextEncode($composer->Composer) ?>"><?php echo str_replace('"', '', $this->getTextEncode($composer_name)); ?>
					</a>
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
						<div style="color: red;">
							<span>No Composers Found</span>
						</div>
					</li>
				</ul>
				<?php
				}
				?>
			</div>
		</section>
		<section class="category-results videos-results">
			<header>
				<h3 class="videos-header">Videos</h3>
				<?php
				if (!empty($videos)) {
					?>
				<a class="see-more"
					href="/search/index?q=<?php echo $keyword; ?>&type=video"
					title="See More Videos"></a>
				<?php
				}
				?>
			</header>
			<div class="search-results-list">
				<?php
				if (!empty($videos)) {
					?>
				<ul>
					<?php
					foreach ($videos as $video) {
						$tilte = urlencode($video->VideoTitle);
						$video_name_text = truncate_text($this->getTextEncode($video->VideoTitle), 125, $this);
						$name = $this->getTextEncode($video->VideoTitle);
						?>
					<li><a href="/search/index?q=<?php echo $tilte; ?>&type=video"
						title="<?php echo $name; ?>"><?php echo (($name != "false") ? $video_name_text : ""); ?>
					</a></li>
					<?php }
					?>
				</ul>
				<?php
				} else {
					?>
				<ul>
					<li>
						<div style="color: red;">
							<span>No Videos Found</span>
						</div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results genres-results">
			<header>
				<h3 class="genres-header">Genres</h3>
				<?php
				if (!empty($genres)) {
					?>
				<a class="see-more"
					href="/search/index?q=<?php echo $keyword; ?>&type=genre"
					title="See More Genres"></a>
				<?php
				}
				?>
			</header>
			<div class="search-results-list">
				<?php
				if (!empty($genres)) {
					?>
				<ul>
					<?php
					foreach ($genres as $genre) {
						$genre_name = str_replace('"', '', $genre->Genre);
						$tilte = urlencode($genre_name);
						$genre_name_text = truncate_text($this->getTextEncode($genre_name), 125, $this);
						$name = $genre->Genre;
						$count = $genre->numFound;
						if (!empty($genre_name_text)) {
							?>
					<li><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>"
						title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $genre_name_text; ?><span>(<?php echo $count; ?>)
						</span> </a></li>
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
						<div style="color: red;">
							<span>No Genres Found</span>
						</div>
					</li>
				</ul>
				<?php } ?>
			</div>
		</section>
		<section class="category-results songs-results">
			<header>
				<h3 class="songs-header">Songs</h3>
			</header>
			<div class="songs-results-list">
				<div class="header-container">
					<div class="artist-col">
						<a
							href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=artist&sortOrder=" . (($sort == 'artist') ? $reverseSortOrder : 'asc'); ?>"><span
							class="artist">Artist</span> </a>
					</div>
					<div class="artist-border header-border"></div>
					<div class="composer-col">
						<a
							href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=composer&sortOrder=" . (($sort == 'composer') ? $reverseSortOrder : 'asc'); ?>"><span
							class="composer">Composer</span> </a>
					</div>
					<div class="composer-border header-border"></div>
					<div class="album-col">
						<a
							href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=album&sortOrder=" . (($sort == 'album') ? $reverseSortOrder : 'asc'); ?>"><span
							class="album">Album</span> </a>
					</div>
					<div class="album-border header-border"></div>
					<div class="song-col">
						<a
							href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=song&sortOrder=" . (($sort == 'song') ? $reverseSortOrder : 'asc'); ?>"><span
							class="song">Song</span> </a>
					</div>
					<?php
					if ($this->Session->read("patron")) {
						?>

					<a class="multi-select-icon no-ajaxy" href="#"
						title="Select All, Clear All, Add to Wishlist, or Add to Playlist"></a>
					<section class="options-menu">
						<ul>
							<li><a class="select-all no-ajaxy" href="#">Select All</a></li>
							<li><a class="clear-all no-ajaxy" href="#">Clear All</a></li>
							<li><a class="add-all-to-wishlist no-ajaxy" href="#">Add to
									Wishlist</a></li>
							<?php if ($this->Session->read('library_type') == 2) { ?>
							<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a>
							</li>
							<?php } ?>
						</ul>
						<ul class="playlist-menu">

						</ul>
					</section>
					<?php
					}
					?>
				</div>
				<div class="rows-container">
					<?php
					if (!empty($songs)) {
						$i = 1;
						$country = $this->Session->read('territory');
						foreach ($songs as $psong) {
							$downloadFlag = $this->Search->checkDownloadForSearch($psong->TerritoryDownloadStatus, $psong->TerritorySalesDate, $this->Session->read('territory'));
							$StreamFlag = $this->Search->checkStreamingForSearch($psong->TerritoryStreamingStatus, $psong->TerritoryStreamingSalesDate, $this->Session->read('territory'));

							//if song not allowed for streaming and not allowed for download then this song must not be display
							if ($downloadFlag === 0 && $StreamFlag === 0) {
								continue;
							}
							?>
					<div class="row">
						<?php
						if ($this->Session->read('library_type') == 2) {
							$filePath = $this->Token->streamingToken($psong->CdnPathFullStream . "/" . $psong->SaveAsNameFullStream);


							if (!empty($filePath)) {
								$songPath = explode(':', $filePath);
								$streamUrl = trim($songPath[1]);
								$psong->streamUrl = $streamUrl;
								$psong->totalseconds = $this->Queue->getSeconds($psong->FullLength_Duration);
							}
						}
						?>


						<?php
						if ($this->Session->read("patron")) {
							if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) {
								if ('T' == $psong->Advisory) {
									$song_title = $psong->SongTitle . '(Explicit)';
								} else {
									$song_title = $psong->SongTitle;
								}
								echo $this->Queue->getsearchSongsStreamNowLabel($psong->streamUrl,$song_title,$psong->ArtistText,$psong->totalseconds,$psong->ProdID,$psong->provider_type);
							} else {
								echo $html->image('sample-icon.png', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode($psong->provider_type) . '", "' . $this->webroot . '");'));
								echo $html->image('sample-loading-icon-v3.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
								echo $html->image('sample-stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
							}
						}

						if ($this->Session->read("patron")) {
							$style = '';
							$styleSong = '';
						} else {
							$style = 'style="left:18px"';
						}
						?>
						<!--      <button class="play-btn"></button> -->
						<div class="artist artist-name" <?php echo $style; ?>>
							<?php echo $html->link(str_replace('"', '', truncate_text($psong->ArtistText, 20, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($psong->ArtistText))), array('title' => $this->getTextEncode($psong->ArtistText))); ?>
						</div>
						<div class="composer composer-name">
							<a style="text-decoration: none;"
								title='<?php echo str_replace('"', '', $this->getTextEncode($psong->Composer)); ?>'><?php echo truncate_text(str_replace('"', '', $this->getTextEncode($psong->Composer)), 25, $this); ?>
							</a>
						</div>
						<div class="album album-name">
							<a
								href="/artists/view/<?php echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID; ?>/<?php echo base64_encode($psong->provider_type); ?>"
								title="<?php echo $this->getTextEncode($psong->Title); ?> "><?php echo str_replace('"', '', truncate_text($this->getTextEncode($psong->Title), 25, $this)); ?>
							</a>
						</div>
						<div class="song song-name" <?php echo $styleSong; ?>
							sdtyped="<?php echo $downloadFlag . '-' . $StreamFlag . '-' . $this->Session->read('territory'); ?>">
							<?php $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>
							<a style="text-decoration: none;"
								title="<?php echo str_replace('"', '', $this->getTextEncode($showSongTitle)); ?>"><?php echo truncate_text($this->getTextEncode($psong->SongTitle), 21, $this); ?>
								<?php
								if ($psong->Advisory == 'T') {
									echo '<font class="explicit"> (Explicit)</font>';
								}
								//
								?> </a>
						</div>
						<?php
						if ($this->Session->read("patron")) {
							?>
						<a href="#" class="menu-btn no-ajaxy"
							title="Add To a Playlist, Wishlist, or Download"></a>
						<section class="options-menu">
							<input type="hidden" id="<?= $psong->ProdID ?>" value="song"
								data-provider="<?= $psong->provider_type ?>" />
							<ul>
								<li><?php
								if ($this->Session->read('patron')) {
									if ($downloadFlag === 1) {
										$productInfo = $song->getDownloadData($psong->ProdID, $psong->provider_type);

										if ($libraryDownload == '1' && $patronDownload == '1') {
											if ($psong->status != 'avail') {
												?> <span class="top-100-download-now-button">
										<form method="Post" id="form<?php echo $psong->ProdID; ?>"
											action="/homes/userDownload" class="suggest_text1">
											<input type="hidden" name="ProdID"
												value="<?php echo $psong->ProdID; ?>" /> <input
												type="hidden" name="ProviderType"
												value="<?php echo $psong->provider_type; ?>" /> <span
												class="beforeClick" style="cursor: pointer;"
												id="wishlist_song_<?php echo $psong->ProdID; ?>"> <![if !IE]>
												<a href='javascript:void(0);'
												class="no-ajaxy top-10-download-now-button"
												title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
												onclick='return wishlistDownloadOthersHome("<?php echo $psong->ProdID; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $psong->provider_type; ?>");'>
													<?php __('Download Now'); ?>
											</a> <![endif]> <!--[if IE]>
                                                                                   <a id="song_download_<?php echo $psong->ProdID; ?>" 
                                                                                        class="no-ajaxy top-10-download-now-button" 
                                                                                        title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." 
                                                                                        onclick='wishlistDownloadIEHome("<?php echo $psong->ProdID; ?>", "0" , "<?php echo $psong->provider_type; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' 
                                                                                        href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                                            <![endif]-->
											</span> <span class="afterClick"
												id="downloading_<?php echo $psong->ProdID; ?>"
												style="display: none;"><a class="add-to-wishlist"><?php __("Please Wait.."); ?>
													<span id="wishlist_loader_<?php echo $psong->ProdID; ?>"
													style="float: right; padding-right: 8px; padding-top: 2px;"><?php echo $html->image('ajax-loader_black.gif'); ?>
												</span> </a> </span>
										</form>
								</span> <?php
											} else {
												?> <a class="top-100-download-now-button"
									href='/homes/my_history'
									title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?>
								</a> <?php
											}
										} else {
											?> <a class="top-100-download-now-button"
									href="javascript:void(0);"><?php __("Limit Met"); ?> </a> <?php
										}
									} else {
										?> <a class="top-100-download-now-button"
									href="javascript:void(0);"> <span
										title='<?php __("Coming Soon"); ?> ( <?php
                            $sales_date = Get_Sales_date($psong->TerritorySalesDate, $this->Session->read('territory'));
                            if (isset($sales_date)) {
                                echo date("F d Y", strtotime($sales_date));
                            }
                            ?> )'> <?php __("Coming Soon"); ?>
									</span>
								</a> <?php }
								?>
								</li>
								<li><?php
								$wishlistInfo = $wishlist->getWishlistData($psong->ProdID);

								if ($wishlistInfo == 'Added To Wishlist') {
									?> <a href="#">Added to Wishlist</a> <?php
								} else {
									?> <span class="beforeClick" id="wishlist<?= $psong->ProdID ?>">
										<a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a>
								</span> <span class="afterClick" style="display: none;"><a
										class="add-to-wishlist" href="JavaScript:void(0);">Please
											Wait...</a> </span> <?php
								}
								?>
								</li>
								<?php } ?>
								<?php if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) { ?>
								<li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a>
								</li>
							</ul>
							<ul class="playlist-menu">
								<li><a href="#" class="no-ajaxy">Create New Playlist</a></li>
							</ul>
							<?php } ?>
						</section>
						<?php if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1)) { ?>
						<input type="checkbox" class="row-checkbox">
						<?php } else {
							?>
						<div class="sample-icon"></div>
						<?php
						}
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
					if (isset($type)) {
						$keyword = "?q=" . $keyword . "&type=" . $type;
					}
					?>
					<?php
					$keyword = $keyword . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
					echo createPagination($html, $currentPage, $facetPage, 'listing', $lastPage, 5, $keyword);
					?>
				</div>
			</div>
		</section>
		<?php } ?>
	</section>
</section>
