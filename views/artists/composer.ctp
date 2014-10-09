<?php 

function createPagination($html,$facetPage,$totalPages,$pageLimitToShow, $queryString = null)
{
	if ($totalPages > 1)
	{

		$part = floor($pageLimitToShow / 2);
		if (1 != $facetPage)
		{
			$pagination_str .= $html->link('<button class="beginning"></button>', "/artists/composer/" . $queryString.'/1', array('escape' => FALSE));
			$pagination_str .= $html->link('<button class="prev"></button>', "/artists/composer/" . $queryString . '/' . ($facetPage - 1), array('escape' => FALSE));
		}
		else
		{
			$pagination_str .= '<button class="beginning" style="cursor:text;"></button>';
			$pagination_str .= '<button class="prev" style="cursor:text;" ></button>';
		}

		$pagination_str .= " ";

		if ($facetPage <= $part)
		{
			$fromPage = 1;
			$topage = $facetPage + ($pageLimitToShow - $facetPage);
			$topage = (($topage <= $totalPages) ? $topage : $totalPages);
		}
		elseif ($facetPage >= ($totalPages - $part))
		{
			$fromPage = ($facetPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($facetPage - ($pageLimitToShow - ($totalPages - $facetPage))) + 1);
			$topage = $totalPages;
			$fromPage = (($fromPage > 1) ? $fromPage : 1);
		}
		else
		{
			$fromPage = $facetPage - $part;
			$topage = $facetPage + $part;
		}

		$classCounter = 1;
		for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++)
		{
			if ($facetPage == $pageCount)
			{
				$pagination_str .= '<button class="page-' . $classCounter . '" style="cursor:text;background: none repeat scroll 0 0 #808080;
				color: #FFFFFF;" >' . $pageCount . '</button>';
			}
			else
			{
				$pagination_str .= $html->link('<button class="page-' . $classCounter . '">' . $pageCount . '</button>', '/artists/composer/' . $queryString . '/' . $pageCount, array('escape' => FALSE));
			}
			$pagination_str .= " ";
			$classCounter++;
		}
		$pagination_str .= " ";

		if ($facetPage != $totalPages)
		{
			$pagination_str .= $html->link('<button class="next"></button>', '/artists/composer/' . $queryString . '/' . ($facetPage + 1), array('escape' => FALSE));
			$pagination_str .= $html->link('<button class="last"></button>', '/artists/composer/' . $queryString . '/' . $totalPages, array('escape' => FALSE));

		}
		else
		{
			$pagination_str .= '<button class="next" style="cursor:text;"></button>';
			$pagination_str .= '<button class="last" style="cursor:text;"></button>';
		}
	}
	else
	{
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
?>
<section class="composer-page">
	<div class="breadcrumbs">
		<?php
		echo $html->link(__('Home', true), array('controller' => 'homes', 'action' => 'index'));
		echo " > ";
		echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
		if(!empty($composertext)){
			echo " > ";
			if (strlen($composertext) >= 30)
			{
				$composertext = substr($composertext, 0, 30) . '...';
			}
			echo $this->getTextEncode($composertext);
		}
		?>
	</div>

	<header class="clearfix">
		<div class="faq-link">
			Need help? Visit our <a href="/questions">FAQ section.</a>
		</div>
	</header>
	<h3>Albums</h3>
	<div class="composer-albums">
		<?php 
		if (!empty($albumData)) {
			$i = 0;
			foreach ($albumData as $palbum) {
				
				if ( !is_object( $palbum ) ) {
					continue;
				}

				$albumDetails = $album->getImage( $palbum->ReferenceID, $palbum->provider_type );

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
				$album_title = truncate_text($this->getTextEncode($palbum->AlbumTitle), 24, $this, false);
				$album_genre = str_replace('"', '', $palbum->Genre);
				$album_label = $palbum->Label;
				$album_copyright = $palbum->Copyright;
				$tilte = urlencode($palbum->AlbumTitle);
				$linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
				$linkProviderType = base64_encode($palbum->provider_type);
				if (!empty($album_label)) {
					$album_label_str = __('Label', true) . ": " . truncate_text($this->getTextEncode($album_label), 32, $this);
				} else {
					$album_label_str = "";
				}
				$ReferenceId = $palbum->ReferenceID;
				if ($palbum->AAdvisory == 'T') {
					$explicit = '<font class="explicit"> (' . __('Explicit', true) . ')</font><br />';
				} else {
					$explicit = '';
				}
				?>
		<div class="album-detail-container">
			<div class="cover-image">
				<a
					href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
					title="<?php echo $this->getTextEncode($palbum->AlbumTitle); ?>"> <img
					src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>"
					width="162" height="162" />
				</a>
			</div>
			<div class="album-info">
				<div class="album-title">
					<strong><a
						href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"
						title="<?php echo $this->getTextEncode($palbum->AlbumTitle); ?>"><?php echo $album_title; ?>
							<?php echo $explicit; ?> </a> </strong>
				</div>
				<div class="genre">
					<?php __('Genre'); ?>: <?php echo $html->link($this->getTextEncode($album_genre), array('controller' => 'genres', 'action' => 'view', '?genre='.$album_genre), array("title" => $this->getTextEncode($album_genre))); ?>
				</div>
				<div class="label">
					<?php __('Label'); ?>: <?php echo $album_label." ".$album_copyright; ?>
				</div>
				<?php
				if ($this->Session->read("patron")) {
					if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) {
						echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 3, $palbum->ReferenceID);
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
							?> <a href="#"><?php __('Added to Wishlist'); ?></a> <?php
						} else {
							?> <span class="beforeClick"
							id="wishlist<?= $palbum->ReferenceID ?>"> <a
								class="add-to-wishlist no-ajaxy" href="#"><?php __('Add to Wishlist'); ?></a>
						</span> <span class="afterClick" style="display: none;"><a
								class="add-to-wishlist" href="JavaScript:void(0);"><?php __('Please Wait'); ?>...</a> </span> <?php
						}
						?>
						</li>
						<?php if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) { ?>
						<li><a class="add-to-playlist no-ajaxy" href="javascript:void(0);"><?php __('Add to Playlist'); ?></a>
						</li>
					</ul>
					<ul class="playlist-menu">
						<li><a href="#"><?php __('Create New Playlist'); ?></a></li>
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
			$pagination_str = createPagination($html,$facetPage,$totalFacetPages, 5, base64_encode($composertext));
                        if(!empty($pagination_str)){ ?>
		<section style="position: relative; width: 866px; right: 21px;"
			class="search-results-songs-page">
			<div class="pagination-container">
				<?php
				echo $pagination_str;
				?>
			</div>
		</section>
		<?php } ?>
		<?php } else { ?>
		<div class="album-detail-container">
			<div style="color: red; padding: 50px;">
				<span><?php __('No Albums Found'); ?></span>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
