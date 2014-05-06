<?php

class HomeHelper extends AppHelper {
	
	var $uses = array('Home');
	
	public function nationalTop100() {

		$cacheReadNationalTop100 = Cache::read('homes_national_top_100');
		
		if( $cacheReadNationalTop100 === false) {

			$cacheWriteNationalTop100 = '
		<div class="top-100">
		<header><h3>' . __('National Top 100', true) . '</h3></header>
		<nav class="top-100-nav">
		<ul>
		<li><a href="#top-100-songs" id="songsIDVal" class="active no-ajaxy hp-tabs" data-category-type="songs" onclick="showHideGrid(\'songs\')">Songs</a></li>
		<li><a href="#top-100-videos" id="videosIDVal" class="no-ajaxy hp-tabs" data-category-type="videos" onclick="showHideGrid(\'videos\')">Albums</a></li>
		</ul>
		</nav>
		<div class="grids active">
		<div id="top-100-songs-grid" class="top-100-grids horiz-scroll active">
		<ul style="width:27064px;">';
		
		if (is_array($nationalTopDownload) && count($nationalTopDownload) > 0) {
		
			$libId = $this->Session->read('library');
			$patId = $this->Session->read('patron');
		
			$k = 2000;
			$nationalTopDownloadCount = count( $nationalTopDownload );
		
			for ($i = 0; $i < $nationalTopDownloadCount; $i++) {
		
				//hide song if library block the explicit content
				if (($this->Session->read('block') == 'yes') && ($nationalTopDownload[$i]['Song']['Advisory'] == 'T')) {
					continue;
				}
		
				if ($i <= 9) {
					$lazyClass = '';
					$srcImg = $nationalTopDownload[$i]['songAlbumImage'];
					$dataoriginal = '';
				} else {                //  Apply Lazy Class for images other than first 10.
		
					$lazyClass = 'lazy';
					$srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
					$dataoriginal = $nationalTopDownload[$i]['songAlbumImage'];
				}
		
				$cacheWriteNationalTop100	.= '<li>
				<div class="top-100-songs-detail">
				<div class="song-cover-container">
				<a href="/artists/view/' . base64_encode($nationalTopDownload[$i]['Song']['ArtistText']) .'/' . $nationalTopDownload[$i]['Song']['ReferenceID'] .'/' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) .'">
				<img class="' . $lazyClass .'" alt="' . $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']) . ' - ' . $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])) .'" src="' . $srcImg .'" data-original="' . $dataoriginal .'"  width="250" height="250" /></a>
				<div class="top-100-ranking">' .($i + 1) . '</div>';
		
				if ($this->Session->read("patron")) {
		
					if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1) {
		
						if ('T' == $nationalTopDownload[$i]['Song']['Advisory']) {
		
							$song_title = $nationalTopDownload[$i]['Song']['SongTitle'] . '(Explicit)';
						} else {
							$song_title = $nationalTopDownload[$i]['Song']['SongTitle'];
						}
		
						$cacheWriteNationalTop100	.=  $this->Queue->getNationalsongsStreamNowLabel($nationalTopDownload[$i]['Full_Files']['CdnPath'],$nationalTopDownload[$i]['Full_Files']['SaveAsName'], $song_title, $nationalTopDownload[$i]['Song']['ArtistText'], $nationalTopDownload[$i]['Song']['FullLength_Duration'], $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);
		
					} else if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
		
						$cacheWriteNationalTop100	.= $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $nationalTopDownload[$i]['Song']['ProdID'] . ', "' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) . '", "' . $this->webroot . '");'));
						$cacheWriteNationalTop100	.= $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
						$cacheWriteNationalTop100	.= $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
					}
				}
		
				if ($this->Session->read('patron')) {
		
					if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
		
						if ($libraryDownload == '1' && $patronDownload == '1') {
		
							if ($this->Session->read('downloadVariArray')) {
								$downloadsUsed = $this->Download->getDownloadResults($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);
							} else {
								$downloadsUsed = $this->Download->getDownloadfind($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
							}
		
							if ($downloadsUsed > 0) {
								$nationalTopDownload[$i]['Song']['status'] = 'avail';
							} else {
								$nationalTopDownload[$i]['Song']['status'] = 'not';
							}
		
							if ($nationalTopDownload[$i]['Song']['status'] != 'avail') {
		
								$cacheWriteNationalTop100	.= '<span class="top-100-download-now-button">
								<form method="Post" id="form' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" action="/homes/userDownload" class="suggest_text1">
								<input type="hidden" name="ProdID" value="' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" />
								<input type="hidden" name="ProviderType" value="' . $nationalTopDownload[$i]["Song"]["provider_type"] .'" />
								<span class="beforeClick" style="cursor:pointer;" id="wishlist_song_' . $nationalTopDownload[$i]["Song"]["ProdID"] .'">
								<![if !IE]>
								<a href="javascript:void(0);" class="add-to-wishlist no-ajaxy top-10-download-now-button"
								title="' . __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.") .'"
								onclick="return wishlistDownloadOthersHome(\"' . $nationalTopDownload[$i]["Song"]['ProdID'] .'\", \"0\", \"' . $nationalTopDownload[$i]['Full_Files']['CdnPath']. '\", \"' . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] .'\", \"' . $nationalTopDownload[$i]["Song"]["provider_type"] .'\");">
								' . __('Download Now') . '</a>
								<![endif]>
								<!--[if IE]>
								<a id="song_download_' . $nationalTopDownload[$i]["Song"]["ProdID"] .'"
								class="no-ajaxy top-10-download-now-button"
								title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."
								onclick="wishlistDownloadIEHome(\"' . $nationalTopDownload[$i]["Song"]['ProdID'] .'\", \"0\" , \"' . $nationalTopDownload[$i]["Song"]["provider_type"] .'\", \"' . $nationalTopDownload[$i]['Full_Files']['CdnPath'] .'\", \"' . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] .'\");"
								href="javascript:void(0);">' . __('Download Now') . '</a>
								<![endif]-->
								</span>
								<span class="afterClick" id="downloading_' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" style="display:none;"><a  class="add-to-wishlist"  >' . __("Please Wait..") .'
								<span id="wishlist_loader_' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" style="float:right;padding-right:8px;padding-top:2px;">' . $html->image('ajax-loader_black.gif') .'</span> </a> </span>
								</form>
								</span>';
							} else {
								$cacheWriteNationalTop100	.= '<a class="top-100-download-now-button" href="/homes/my_history" title="' . __("You have already downloaded this song. Get it from your recent downloads") .'">' . __('Downloaded') .'</a>';
							}
						} else {
							$cacheWriteNationalTop100	.= '<a class="top-100-download-now-button" href="javascript:void(0);">' . __("Limit Met") .'</a>';
						}
					} else {
						$cacheWriteNationalTop100	.= '<a class="top-100-download-now-button" href="javascript:void(0);">
						<span title="' . __("Coming Soon") .' (';
						if (isset($nationalTopDownload[$i]['Country']['SalesDate']))
						{
							$cacheWriteNationalTop100	.= date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));
						}
						$cacheWriteNationalTop100	.= ' )">
						' . __("Coming Soon") .'
						</span>
						</a>';
					}
				} else {
					$cacheWriteNationalTop100	.= '<a class="top-100-download-now-button" href="/users/redirection_manager"> ' . __("Login") .'</a>';
				}
		
				if ($this->Session->read("patron")) {
		
					$cacheWriteNationalTop100	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
					<div class="wishlist-popover">
					<input type="hidden" id="' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" value="song"/>';
		
					if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1) {
						$cacheWriteNationalTop100	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
					}
		
					$wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);
		
					$cacheWriteNationalTop100	.= $wishlist->getWishListMarkup($wishlistInfo, $nationalTopDownload[$i]["Song"]["ProdID"], $nationalTopDownload[$i]["Song"]["provider_type"]);
		
					$cacheWriteNationalTop100	.= ' </div>';
				}
				$cacheWriteNationalTop100	.= '</div>';
		
				if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 30) {
					$songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 30)) . "..";
				} else {
					$songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
				}
		
				if ('T' == $nationalTopDownload[$i]['Song']['Advisory']) {
		
					if (strlen($songTitle) >= 20) {
						$songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 20)) . "..";
					}
		
					$songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';
				}
		
				if (strlen($nationalTopDownload[$i]['Song']['ArtistText']) >= 30) {
					$artistText = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 30)) . "..";
				} else {
					$artistText = $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']);
				}
		
				$cacheWriteNationalTop100	.= '<div class="song-title">
				<a title="' . $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])) .'" href="/artists/view/' . base64_encode($nationalTopDownload[$i]['Song']['ArtistText']) .'/' . $nationalTopDownload[$i]['Song']['ReferenceID'] .'/' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) .'">' . $this->getTextEncode($songTitle) .'</a>
				</div>
				<div class="artist-name">
				<a title="' . $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText'])) .'" href="/artists/album/' . base64_encode($nationalTopDownload[$i]['Song']['ArtistText']) .'">' . $this->getTextEncode($artistText) .'</a>
				</div>
				</div>
				</li>';
				$k++;
			}
		}
		$cacheWriteNationalTop100	.= '</ul>
		</div>
		<div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
		<ul style="width:27100px;">';
		$count = 1;
		if (count($nationalTopAlbumsDownload) > 0) {
			foreach ($nationalTopAlbumsDownload as $key => $value) {
				//hide song if library block the explicit content
				if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T')) {
					continue;
				}
				$cacheWriteNationalTop100	.= '<li>
				<div class="album-container">';
		
				if ($count <= 10) {
					$lazyClass = '';
					$srcImg = $value['songAlbumImage'];
					$dataoriginal = '';
				} else {               //  Apply Lazy Class for images other than first 10.
					$lazyClass = 'lazy';
					$srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
					$dataoriginal = $value['songAlbumImage'];
				}
		
				$cacheWriteNationalTop100	.= $html->link($html->image($srcImg, array("height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal)), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false));
				$cacheWriteNationalTop100	.= '<div class="top-100-ranking">' . $count . '</div>';
		
				if ($this->Session->read("patron")) {
					if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'])) {
						$cacheWriteNationalTop100	.= $this->Queue->getNationalAlbumStreamLabel($value['Song']['ArtistText'],$value['Albums']['ProdID'],$value['Song']['provider_type']);
						$cacheWriteNationalTop100	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>';
					}
					$cacheWriteNationalTop100	.= '<div class="wishlist-popover">
					<input type="hidden" id="' . $value['Albums']['ProdID'] .'" value="album"/>';
		
					if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'])) {
					$cacheWriteNationalTop100	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
				}
				$cacheWriteNationalTop100	.= '</div>';
				} else {
				$cacheWriteNationalTop100	.= '<a class="top-100-download-now-button " href="/users/redirection_manager"> ' . __("Login") . '</a>';
				}
				$cacheWriteNationalTop100	.= '</div>
				<div class="album-title">
				<a title="' . $this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])) .'" href="/artists/view/' . base64_encode($value['Song']['ArtistText']) .'/' . $value['Song']['ReferenceID'] .'/' . base64_encode($value['Song']['provider_type']) .'">';
		
				if (strlen($value['Albums']['AlbumTitle']) > 20) {
				$cacheWriteNationalTop100	.= $this->getValidText($this->getTextEncode(substr($value['Albums']['AlbumTitle'], 0, 20))) . "...";
				} else {
				$cacheWriteNationalTop100	.= $value['Albums']['AlbumTitle'];
				}
				$cacheWriteNationalTop100	.= '</a>';
				if ('T' == $value['Albums']['Advisory']) {
				$cacheWriteNationalTop100	.= '<span style="color: red;display: inline;"> (Explicit)</span>';
				}
				$cacheWriteNationalTop100	.= '</div>
				<div class="artist-name">
				<a title="' . $this->getValidText($this->getTextEncode($value['Song']['Artist'])) .'" href="/artists/album/' . str_replace('/', '@', base64_encode($value['Song']['ArtistText'])) .'/' . base64_encode($value['Song']['Genre']) .'">';
		
				if (strlen($value['Song']['Artist']) > 32) {
				$cacheWriteNationalTop100	.= $this->getValidText($this->getTextEncode(substr($value['Song']['Artist'], 0, 32))) . "...";
				} else {
				$cacheWriteNationalTop100	.= $this->getValidText($this->getTextEncode($value['Song']['Artist']));
				}
				$cacheWriteNationalTop100	.= '</a>
				</div>
				</li>';
		
				$count++;
				}
				} else {
		
				$cacheWriteNationalTop100	.= '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
				}
		
				$cacheWriteNationalTop100	.= '</ul>
				</div>
				</div> <!-- end .grids -->
				</div>';

				Cache::write('homes_national_top_100', $cacheWriteNationalTop100);
				$cacheReadNationalTop100 = $cacheWriteNationalTop100;
		}
		
		return $cacheReadNationalTop100;
	}
	
	public function featuredAlbums() {

		$cacheReadFeaturedAlbums = Cache::read('homes_featured_albums');
		
		if($cacheReadFeaturedAlbums === false) {
			
			$cacheWriteFeaturedAlbums = '<div class="featured">
			<header>
			<h3>Featured Albums</h3>
			</header>
			<div class="featured-grid horiz-scroll">
			<ul style="width:3690px;">';
			
			foreach ($featuredArtists as $k => $v) {
				if (strlen($v['Album']['AlbumTitle']) > 22) {
					$title = substr($v['Album']['AlbumTitle'], 0, 22) . "..";
				} else {
					$title = $v['Album']['AlbumTitle'];
				}
			
				if (strlen($v['Album']['ArtistText']) > 22) {
					$ArtistText = substr($v['Album']['ArtistText'], 0, 22) . "..";
				} else {
					$ArtistText = $v['Album']['ArtistText'];
				}
			
				$cacheWriteFeaturedAlbums .= '<li>
				<div class="featured-album-detail">
				<div class="album-cover-container">
			
				<a href="/artists/view/' . base64_encode($v['Album']['ArtistText']) .'/' . $v['Album']['ProdID'] .'/' . base64_encode($v['Album']['provider_type']) .'">' . $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])) .'</a>';
			
				if ($this->Session->read("patron")) {
					if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']])) {
						$cacheWriteFeaturedAlbums .= $this->Queue->getAlbumStreamNowLabel($v['albumSongs'][$v['Album']['ProdID']]);
					}
			
					if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']])) {
			
						$cacheWriteFeaturedAlbums .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
						<div class="wishlist-popover">
						<input type="hidden" id="' . $v['Album']['ProdID'] .'" value="album"/>
						<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
						</div>';
					}
				} else {
					$cacheWriteFeaturedAlbums .= '<a class="top-100-download-now-button" href="/users/redirection_manager">' . __("Login") . '</a>';
				}
				$cacheWriteFeaturedAlbums .= '</div>
				<div class="album-title">
				<a title="' . $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])) .'" href="/artists/view/' . base64_encode($v['Album']['ArtistText']) .'/' . $v['Album']['ProdID'] .'/' . base64_encode($v['Album']['provider_type']) .'">' . $this->getTextEncode($title) .'</a>
				</div>
				<div class="artist-name">
				<a title="' . $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])) .'" href="/artists/album/' . str_replace('/', '@', base64_encode($v['Album']['ArtistText'])) .'/' . base64_encode($v['Genre']['Genre']) .'">' . $this->getTextEncode($ArtistText) .'</a>
				</div>
				</div>
				</li>';
			
			}
			
			$cacheWriteFeaturedAlbums .= '</ul>
			</div>
			</div><!-- end .featured -->';
			
			Cache::write('homes_featured_albums', $cacheWriteFeaturedAlbums);
			$cacheReadFeaturedAlbums = $cacheWriteFeaturedAlbums;
		}
		
		return $cacheReadFeaturedAlbums;
	}
	
	public function comingSoon() {

		$cacheReadComingSoon = Cache::read('homes_coming_soon');
		
		if($cacheReadComingSoon === false) {
			
			$cacheWriteComingSoon = '<div class="coming-soon">
			<header class="clearfix">
			<h3>Coming Soon</h3>
			</header>
			<div class="coming-soon-filter-container clearfix">
			<nav class="category-filter">
			<ul class="clearfix">
			<li><a href="#coming-soon-singles-grid" id="songsIDValComming" class="active no-ajaxy hp-tabs" onclick="showHideGridCommingSoon(\'songs\')">Songs</a></li>
			<li><a href="#coming-soon-videos-grid" id="videosIDValComming" class="no-ajaxy hp-tabs" onclick="showHideGridCommingSoon(\'videos\')">Videos</a></li>
			</ul>
			</nav>
			</div>
			<div id="coming-soon-singles-grid" class="horiz-scroll active">
			<ul class="clearfix">';
			 
			$total_songs = count($coming_soon_rs);
			$sr_no = 0;
			
			foreach ($coming_soon_rs as $key => $value) {
				//hide song if library block the explicit content
				if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T')) {
					continue;
				}
			
				if ($sr_no <= 9) {
					$lazyClass = '';
					$srcImg = $value['cs_songImage'];
					$dataoriginal = '';
				} else {                //  Apply Lazy Class for images other than first 10.
			
					$lazyClass = 'lazy';
					$srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
					$dataoriginal = $value['cs_songImage'];
				}
			
				if ($sr_no >= 20) {
					break;
				}
			
				if ($sr_no % 2 == 0) {
					$cacheWriteComingSoon .= '<li>';
				}
				$cacheWriteComingSoon .= '<div class="single-detail">
				<div class="single-cover-container">
				<a href="/artists/view/' . base64_encode($value['Song']['ArtistText']) .'/' . $value['Song']['ReferenceID'] .'/' . base64_encode($value['Song']['provider_type']) .'">
				<img class="' . $lazyClass .'" src="' . $srcImg .'" data-original="' . $dataoriginal .'" alt="' . $this->getValidText($this->getTextEncode($value['Song']['Artist']) . ' - ' . $this->getTextEncode($value['Song']['SongTitle'])) .'" width="162" height="162" /></a>';
			
				if ($this->Session->read("patron")) {
					$cacheWriteComingSoon .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">
			
					</a>
					<div class="wishlist-popover">';
			
					$wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
					$cacheWriteComingSoon .= $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
					$cacheWriteComingSoon .= '</div>';
				}
				$cacheWriteComingSoon .= '</div>
				<div class="song-title">
				<a title="' . $this->getTextEncode($value['Song']['SongTitle']) .'" href="/artists/view/' . base64_encode($value['Song']['ArtistText']) .'/' . $value['Song']['ReferenceID'] .'/' . base64_encode($value['Song']['provider_type']) .'">';
			
				$commingSoonSongTitle = $this->getTextEncode($value['Song']['SongTitle']);
			
				if ('T' == $value['Song']['Advisory']) {
			
					if (strlen($commingSoonSongTitle) > 13) {
						$cacheWriteComingSoon .= substr($commingSoonSongTitle, 0, 13) . "...";
					} else {
						$cacheWriteComingSoon .= $commingSoonSongTitle;
					}
				} else {
					if (strlen($commingSoonSongTitle) > 20) {
						$cacheWriteComingSoon .= substr($commingSoonSongTitle, 0, 20) . "...";
					} else {
						$cacheWriteComingSoon .= $commingSoonSongTitle;
					}
				}
				$cacheWriteComingSoon .= '</a>';
			
				if ('T' == $value['Song']['Advisory']) {
					$cacheWriteComingSoon .= '<span style="color: red;display: inline;"> (Explicit)</span>';
				}
			
				$cacheWriteComingSoon .= '</div>
				<div class="artist-name">
				<a title="' . $this->getTextEncode($value['Song']['Artist']) .'" href="/artists/album/' . str_replace('/', '@', base64_encode($value['Song']['ArtistText'])) .'/'  . base64_encode($value['Song']['Genre']) .'">';
			
				$commingSoonSongArtistTitle = $this->getTextEncode($value['Song']['Artist']);
				if (strlen($commingSoonSongArtistTitle) > 20) {
					$cacheWriteComingSoon .= substr($commingSoonSongArtistTitle, 0, 20) . "...";
				} else {
					$cacheWriteComingSoon .= $commingSoonSongArtistTitle;
				}
			
				$cacheWriteComingSoon .= '</a>
				</div>
				</div>';
			
				if ($sr_no % 2 == 1 || $sr_no == ($total_songs - 1)) {
					$cacheWriteComingSoon .= '</li>';
				}
				$sr_no++;
			}
			
			$cacheWriteComingSoon .= '</ul>
			</div> <!-- end #coming-soon-singles-grid -->
			<div id="coming-soon-videos-grid" class="clearfix horiz-scroll">
			<ul class="clearfix" style="width:3333px;">';
			
			$total_videos = count($coming_soon_videos);
			$sr_no = 0;
			
			foreach ($coming_soon_videos as $key => $value) {
				//hide song if library block the explicit content
				if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T')) {
					continue;
				}
			
				if ($sr_no >= 20) {
					break;
				}
			
				if ($sr_no % 2 == 0) {
					$cacheWriteComingSoon .= '<li>';
				}
				$cacheWriteComingSoon .= '<div class="video-detail">
				<div class="video-cover-container">
				<a href="/videos/details/' . $value['Video']['ProdID'] .'">
				<img  src="' . $value['videoAlbumImage'] .'"  alt="' . $this->getValidText($this->getTextEncode($value['Video']['Artist']) . ' - ' . $this->getTextEncode($value['Video']['VideoTitle'])) .'" width="275" height="162" />
				</a>';
			
				if ($this->Session->read("patron")) {
			
					$cacheWriteComingSoon .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">
			
					</a>
					<div class="wishlist-popover">';
			
					$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
					$cacheWriteComingSoon .= $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
					$cacheWriteComingSoon .= '</div>';
				}
				$cacheWriteComingSoon .= '</div>
				<div class="video-title">
			
				<a title="' . $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])) .'" href="/videos/details/' . $value['Video']['ProdID'] .'">';
			
				$commingSoonVideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
			
				if ('T' == $value['Video']['Advisory']) {
					if (strlen($commingSoonVideoTitle) > 15) {
						$cacheWriteComingSoon .= substr($commingSoonVideoTitle, 0, 15) . "...";
					} else {
						$cacheWriteComingSoon .= $commingSoonVideoTitle;
					}
				} else {
					if (strlen($commingSoonVideoTitle) > 20) {
						$cacheWriteComingSoon .= substr($commingSoonVideoTitle, 0, 20) . "...";
					} else {
						$cacheWriteComingSoon .= $commingSoonVideoTitle;
					}
				}
				$cacheWriteComingSoon .= '</a>';
			
				if ('T' == $value['Video']['Advisory']) {
					$cacheWriteComingSoon .= '<span style="color: red;display: inline;"> (Explicit)</span>';
				}
			
				$cacheWriteComingSoon .= '</div>
				<div class="artist-name">
			
				<a title="' . $this->getTextEncode($value['Video']['Artist']) .'" href="javascript:void(0)">';
			
				if (strlen($value['Video']['Artist']) > 20) {
					$cacheWriteComingSoon .= substr($value['Video']['Artist'], 0, 20) . "...";
				} else {
					$cacheWriteComingSoon .= $value['Video']['Artist'];
				}
				$cacheWriteComingSoon .= '</a>
				</div>
				</div>';
			
				if ($sr_no % 2 == 1 || $sr_no == ($total_videos - 1)) {
					$cacheWriteComingSoon .= '</li>';
				}
			
				$sr_no++;
			}
			
			$cacheWriteComingSoon .= '</ul>
			</div><!-- end videos grid -->
			
			</div> <!-- end coming soon -->';
			
			Cache::write('homes_coming_soon', $cacheWriteComingSoon);
			$cacheReadComingSoon = $cacheWriteComingSoon;
		}
		
		return $cacheReadComingSoon;
	}
	
	public function news() {

		$cacheReadNews = Cache::read('homes_news');
		
		if( $cacheReadNews === false) {
			
			$cacheWriteNews = '<div class="whats-happening">
			<header>
			<h3>News</h3>
			</header>
			
			<div id="whats-happening-grid" class="horiz-scroll">
			<ul class="clearfix" style="width:4400px;">';
			
			$count = 1;
			foreach ($news as $key => $value) {
				$newsText = str_replace('<div', '<p', $value['News']['body']);
				$newsText = str_replace('</div>', '</p>', $newsText);
			
				$cacheWriteNews .= '<li>
				<div class="post">
				<div class="post-header-image">
				<a href="javascript:void(0);"><img src ="' . $cdnPath . 'news_image/' . $value['News']['image_name'] .'" style="width:417px;height:196px;" alt="' . $this->getValidText($value['News']['subject']) .'" /></a>
				</div>
				<div class="post-title">
				<a href="javascript:void(0);">' . $this->getValidText($value['News']['subject']) .'</a>
				</div>
				<div class="post-date">
				' . $value['News']['place'] . ' : ' . date("F d, Y", strtotime($value['News']['created'])) . '
				</div>
				<div class="post-excerpt">
				' . $newsText . '
				</div>
				</div>
				</li>';
			
				if ($count == 10) {
					break;
				}
				$count++;
			}
			$cacheWriteNews .= '</ul>
			</div>
			</div>';
			
			Cache::write('homes_news', $cacheWriteNews);
			$cacheReadNews = $cacheWriteNews;
		}
		
		return $cacheReadNews;
	}
}
?>