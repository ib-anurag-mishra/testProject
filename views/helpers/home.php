<?php

class HomeHelper extends AppHelper {
	
	var $uses 	 = array( 'Home' );
	var $helpers = array( 'Session', 'Queue', 'Dataencode', 'Html', 'Wishlist' );
	
	public function nationalTop100( $nationalTopDownload, $nationalTopAlbumsDownload ) {
	
		$territory = $this->Session->read( 'territory' );
	
		$cacheReadNationalTop100 = Cache::read( 'homes_national_top_100' . $territory );
	
		if( $cacheReadNationalTop100 === false ) {
	
			$cacheWriteNationalTop100 = '<div class="top-100">
			<header><h3>' . __( 'National Top 100', true ) . '</h3></header>
			<nav class="top-100-nav">
			<ul>
			<li><a href="#top-100-songs" id="songsIDVal" class="active no-ajaxy hp-tabs" data-category-type="songs" onclick="showHideGrid(\'songs\')">Songs</a></li>
			<li><a href="#top-100-videos" id="videosIDVal" class="no-ajaxy hp-tabs" data-category-type="videos" onclick="showHideGrid(\'videos\')">Albums</a></li>
			</ul>
			</nav>
			<div class="grids active">
			<div id="top-100-songs-grid" class="top-100-grids horiz-scroll active">
			<ul style="width:27064px;">';
	
			if ( is_array( $nationalTopDownload ) && count( $nationalTopDownload ) > 0 ) {

				$nationalTopDownloadCount = count( $nationalTopDownload );
	
				for ( $i = 0; $i < $nationalTopDownloadCount; $i++ ) {
	
					//hide song if library block the explicit content
					if ( ( $this->Session->read( 'block' ) == 'yes' ) && ( $nationalTopDownload[$i]['Song']['Advisory'] == 'T' ) ) {
						continue;
					}
	
					if ( $i <= 9 ) {
						$lazyClass 	  = '';
						$srcImg 	  = $nationalTopDownload[$i]['songAlbumImage'];
						$dataoriginal = '';
					} else {                //  Apply Lazy Class for images other than first 10.
	
						$lazyClass 	  = 'lazy';
						$srcImg 	  = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
						$dataoriginal = $nationalTopDownload[$i]['songAlbumImage'];
					}
	
					$cacheWriteNationalTop100	.= '<li>
					<div class="top-100-songs-detail">
					<div class="song-cover-container">
					<a href="/artists/view/' . base64_encode( $nationalTopDownload[$i]['Song']['ArtistText'] ) .'/' . $nationalTopDownload[$i]['Song']['ReferenceID'] .'/' . base64_encode( $nationalTopDownload[$i]['Song']['provider_type'] ) .'">
					<img class="' . $lazyClass .'" alt="' . $this->Dataencode->getValidText($this->Dataencode->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']) . ' - ' . $this->Dataencode->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])) .'" src="' . $srcImg .'" data-original="' . $dataoriginal .'"  width="250" height="250" /></a>
					<div class="top-100-ranking">' .($i + 1) . '</div>';

					//placeholder for streaming
					$cacheWriteNationalTop100	.=  $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_streaming';
	
					//placeholder for download 
					$cacheWriteNationalTop100	.=  $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_download';
	
					//Placholder for wishlist
					$cacheWriteNationalTop100	.=  $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_wishlist';

					$cacheWriteNationalTop100	.= '</div>';
	
					if ( 'T' == $nationalTopDownload[$i]['Song']['Advisory'] ) {
	
						if ( strlen( $nationalTopDownload[$i]['Song']['SongTitle'] ) >= 20 ) {
							$songTitle = $this->Dataencode->getTextEncode( substr( $nationalTopDownload[$i]['Song']['SongTitle'], 0, 20 ) ) . "..";
						}
	
						$songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';

					} else if ( strlen( $nationalTopDownload[$i]['Song']['SongTitle'] ) >= 30 ) {
						$songTitle = $this->Dataencode->getTextEncode( substr( $nationalTopDownload[$i]['Song']['SongTitle'], 0, 30 ) ) . "..";
					} else {
						$songTitle = $this->Dataencode->getTextEncode( $nationalTopDownload[$i]['Song']['SongTitle'] );
					}
	
					if ( strlen( $nationalTopDownload[$i]['Song']['ArtistText'] ) >= 30 ) {
						$artistText = $this->Dataencode->getTextEncode( substr( $nationalTopDownload[$i]['Song']['ArtistText'], 0, 30 ) ) . "..";
					} else {
						$artistText = $this->Dataencode->getTextEncode( $nationalTopDownload[$i]['Song']['ArtistText'] );
					}

					$cacheWriteNationalTop100	.= '<div class="song-title">
					<a title="' . $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $nationalTopDownload[$i]['Song']['SongTitle'] ) ) .'" href="/artists/view/' . base64_encode( $nationalTopDownload[$i]['Song']['ArtistText'] ) .'/' . $nationalTopDownload[$i]['Song']['ReferenceID'] .'/' . base64_encode( $nationalTopDownload[$i]['Song']['provider_type'] ) .'">' . $this->Dataencode->getTextEncode( $songTitle ) .'</a>
					</div>
					<div class="artist-name">
					<a title="' . $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $nationalTopDownload[$i]['Song']['ArtistText'] ) ) .'" href="/artists/album/' . base64_encode( $nationalTopDownload[$i]['Song']['ArtistText'] ) .'">' . $this->Dataencode->getTextEncode( $artistText ) .'</a>
					</div>
					</div>
					</li>';
				}
			}

			$cacheWriteNationalTop100	.= '</ul>
			</div>
			<div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
			<ul style="width:27100px;">';

			$count = 1;

			if ( is_array( $nationalTopAlbumsDownload ) && count( $nationalTopAlbumsDownload ) > 0 ) {

				foreach ( $nationalTopAlbumsDownload as $key => $value ) {

					//hide song if library block the explicit content
					if ( ( $this->Session->read( 'block') == 'yes' ) && ( $value['Albums']['Advisory'] == 'T' ) ) {
						continue;
					}

					$cacheWriteNationalTop100	.= '<li>
					<div class="album-container">';
	
					if ( $count <= 10 ) {
						$lazyClass 	  = '';
						$srcImg 	  = $value['songAlbumImage'];
						$dataoriginal = '';
					} else {               //  Apply Lazy Class for images other than first 10.
						$lazyClass 	  = 'lazy';
						$srcImg 	  = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
						$dataoriginal = $value['songAlbumImage'];
					}

					$cacheWriteNationalTop100	.= $this->Html->link( $this->Html->image( $srcImg, array( "height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal ) ), array( 'controller' => 'artists', 'action' => 'view', base64_encode( $value['Song']['ArtistText'] ), $value['Song']['ReferenceID'], base64_encode( $value['Song']['provider_type'] ) ), array( 'class' => 'first', 'escape' => false ) );
					$cacheWriteNationalTop100	.= '<div class="top-100-ranking">' . $count . '</div>';

					//Placeholder for Albums
					$cacheWriteNationalTop100	.=  $value['Song']['ProdID'] . '_' . $value['Song']['provider_type'] . '_albums';
	
					$cacheWriteNationalTop100	.= '</div>
					<div class="album-title">
					<a title="' . $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $value['Albums']['AlbumTitle'] ) ) .'" href="/artists/view/' . base64_encode( $value['Song']['ArtistText'] ) .'/' . $value['Song']['ReferenceID'] .'/' . base64_encode( $value['Song']['provider_type'] ) .'">';
	
					if ( strlen( $value['Albums']['AlbumTitle'] ) > 20 ) {
						$cacheWriteNationalTop100	.= $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( substr( $value['Albums']['AlbumTitle'], 0, 20 ) ) ) . "...";
					} else {
						$cacheWriteNationalTop100	.= $value['Albums']['AlbumTitle'];
					}

					$cacheWriteNationalTop100	.= '</a>';

					if ( 'T' == $value['Albums']['Advisory'] ) {
						$cacheWriteNationalTop100	.= '<span style="color: red;display: inline;"> (Explicit)</span>';
					}

					$cacheWriteNationalTop100	.= '</div>
					<div class="artist-name">
					<a title="' . $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $value['Song']['Artist'] ) ) .'" href="/artists/album/' . str_replace( '/', '@', base64_encode( $value['Song']['ArtistText'] ) ) .'/' . base64_encode( $value['Song']['Genre'] ) .'">';
	
					if ( strlen( $value['Song']['Artist'] ) > 32 ) {
						$cacheWriteNationalTop100	.= $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( substr( $value['Song']['Artist'], 0, 32 ) ) ) . "...";
					} else {
						$cacheWriteNationalTop100	.= $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $value['Song']['Artist'] ) );
					}

					$cacheWriteNationalTop100	.= '</a>
					</div>
					</li>';
	
					$count++;
				}
				
				$cacheWriteNationalTop100	.= '</ul></div></div></div>';
				
				Cache::delete( 'homes_national_top_100' . $territory );
				Cache::write( 'homes_national_top_100' . $territory, $cacheWriteNationalTop100 );

			} else {	
				$cacheWriteNationalTop100	.= '<span style="font-size:14px;">Sorry,there are no downloads.<span>';
				$cacheWriteNationalTop100	.= '</ul></div></div></div>';
			}

			$cacheReadNationalTop100 = $cacheWriteNationalTop100;
		}

		return $cacheReadNationalTop100;
	}
	
	public function featuredAlbums( $featuredArtists ) {
		
		$territory = $this->Session->read( 'territory' );

		$cacheReadFeaturedAlbums = Cache::read( 'homes_featured_albums'. $territory );
		
		if( $cacheReadFeaturedAlbums === false ) {

			$cacheWriteFeaturedAlbums = '<div class="featured">
			<header>
			<h3>Featured Albums</h3>
			</header>
			<div class="featured-grid horiz-scroll">
			<ul style="width:3690px;">';
			
			foreach ( $featuredArtists as $k => $v ) {

				if ( strlen( $v['Album']['AlbumTitle'] ) > 22 ) {
					$title = substr( $v['Album']['AlbumTitle'], 0, 22 ) . "..";
				} else {
					$title = $v['Album']['AlbumTitle'];
				}

				if ( strlen( $v['Album']['ArtistText'] ) > 22 ) {
					$ArtistText = substr( $v['Album']['ArtistText'], 0, 22 ) . "..";
				} else {
					$ArtistText = $v['Album']['ArtistText'];
				}

				$cacheWriteFeaturedAlbums .= '<li>
				<div class="featured-album-detail">
				<div class="album-cover-container">
				<a href="/artists/view/' . base64_encode( $v['Album']['ArtistText'] ) .'/' . $v['Album']['ProdID'] .'/' . base64_encode( $v['Album']['provider_type'] ) .'">' . $this->Html->image( $v['featuredImage'], array( "height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'] ) ) .'</a>';

			//Placeholder for featured Alubms
				$cacheWriteFeaturedAlbums .= $v['Album']['ProdID'] . $v['Album']['provider_type']  . '_featured_album';
				
				$cacheWriteFeaturedAlbums .= '</div>
				<div class="album-title">
				<a title="' . $this->Dataencode->getValidText( $this->Dataencode->getTextEncode( $v['Album']['AlbumTitle'] ) ) .'" href="/artists/view/' . base64_encode( $v['Album']['ArtistText'] ) .'/' . $v['Album']['ProdID'] .'/' . base64_encode( $v['Album']['provider_type'] ) .'">' . $this->Dataencode->getTextEncode( $title ) .'</a>
				</div>
				<div class="artist-name">
				<a title="' . $this->Dataencode->getValidText($this->Dataencode->getTextEncode($v['Album']['ArtistText'])) .'" href="/artists/album/' . str_replace('/', '@', base64_encode($v['Album']['ArtistText'])) .'/' . base64_encode($v['Genre']['Genre']) .'">' . $this->Dataencode->getTextEncode($ArtistText) .'</a>
				</div>
				</div>
				</li>';
			}

			$cacheWriteFeaturedAlbums .= '</ul>
			</div>
			</div>';

			Cache::delete( 'homes_featured_albums' . $territory );
			Cache::write( 'homes_featured_albums' . $territory, $cacheWriteFeaturedAlbums );

			$cacheReadFeaturedAlbums = $cacheWriteFeaturedAlbums;
		}
		
		return $cacheReadFeaturedAlbums;
	}
	
	public function comingSoon( $coming_soon_rs, $coming_soon_videos ) {

		$territory = $this->Session->read( 'territory' );

		$cacheReadComingSoon = Cache::read( 'homes_coming_soon' . $territory );
		
		if( $cacheReadComingSoon === false ) {
			
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
				<img class="' . $lazyClass .'" src="' . $srcImg .'" data-original="' . $dataoriginal .'" alt="' . $this->Dataencode->getValidText($this->Dataencode->getTextEncode($value['Song']['Artist']) . ' - ' . $this->Dataencode->getTextEncode($value['Song']['SongTitle'])) .'" width="162" height="162" /></a>';
			/*PlaceHolder*/
				$cacheWriteComingSoon .= $value["Song"]["ProdID"] . $value["Song"]["provider_type"] . '_coming_soon_song';

				$cacheWriteComingSoon .= '</div>
				<div class="song-title">
				<a title="' . $this->Dataencode->getTextEncode($value['Song']['SongTitle']) .'" href="/artists/view/' . base64_encode($value['Song']['ArtistText']) .'/' . $value['Song']['ReferenceID'] .'/' . base64_encode($value['Song']['provider_type']) .'">';
			
				$commingSoonSongTitle = $this->Dataencode->getTextEncode($value['Song']['SongTitle']);
			
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
				<a title="' . $this->Dataencode->getTextEncode($value['Song']['Artist']) .'" href="/artists/album/' . str_replace('/', '@', base64_encode($value['Song']['ArtistText'])) .'/'  . base64_encode($value['Song']['Genre']) .'">';
			
				$commingSoonSongArtistTitle = $this->Dataencode->getTextEncode($value['Song']['Artist']);
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
				<img  src="' . $value['videoAlbumImage'] .'"  alt="' . $this->Dataencode->getValidText($this->Dataencode->getTextEncode($value['Video']['Artist']) . ' - ' . $this->Dataencode->getTextEncode($value['Video']['VideoTitle'])) .'" width="275" height="162" />
				</a>';
			
				/*PlaceHolder*/
				$cacheWriteComingSoon .= $value['Video']['ProdID'] . $value['Video']['provider_type'] . '_coming_soon_video';
				
				$cacheWriteComingSoon .= '</div>
				<div class="video-title">
			
				<a title="' . $this->Dataencode->getValidText($this->Dataencode->getTextEncode($value['Video']['VideoTitle'])) .'" href="/videos/details/' . $value['Video']['ProdID'] .'">';
			
				$commingSoonVideoTitle = $this->Dataencode->getTextEncode($value['Video']['VideoTitle']);
			
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
			
				<a title="' . $this->Dataencode->getTextEncode($value['Video']['Artist']) .'" href="javascript:void(0)">';
			
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
			
			Cache::delete('homes_coming_soon' . $territory);
			Cache::write('homes_coming_soon' . $territory, $cacheWriteComingSoon);
			$cacheReadComingSoon = $cacheWriteComingSoon;
		}
		
		return $cacheReadComingSoon;
	}
	
	public function news($news) {

		$territory = $this->Session->read('territory');
		$cacheReadNews = Cache::read('homes_news' . $territory);
		
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
				<a href="javascript:void(0);"><img src ="' . Configure::read('App.CDN') . 'news_image/' . $value['News']['image_name'] .'" style="width:417px;height:196px;" alt="' . $this->Dataencode->getValidText($value['News']['subject']) .'" /></a>
				</div>
				<div class="post-title">
				<a href="javascript:void(0);">' . $this->Dataencode->getValidText($value['News']['subject']) .'</a>
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
			
			Cache::delete('homes_news' . $territory);
			Cache::write('homes_news' . $territory, $cacheWriteNews);
			$cacheReadNews = $cacheWriteNews;
		}
		
		return $cacheReadNews;
	}
}
?>