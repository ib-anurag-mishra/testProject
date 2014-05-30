<?php
echo $session->flash();
ini_set("session.cookie_lifetime", "0"); // 0 means "until the browser is closed
?>
<section class="news">
	<?php 
	$strNationalTop100 = $this->Home->nationalTop100( $nationalTopDownload, $nationalTopAlbumsDownload );
	$library_type 	   = $this->Session->read('library_type');

	if( is_array( $nationalTopDownload ) ) {

		$arrayCount = count( $nationalTopDownload );

		for ( $i = 0; $i < $arrayCount; $i++ ) {

			$replaceString = '';

			if ( isset( $patronId ) && !empty( $patronId ) ) {

				if ( $library_type == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date( 'Y-m-d' ) && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1 ) {

					if ( 'T' == $nationalTopDownload[$i]['Song']['Advisory'] ) {
						$song_title = $nationalTopDownload[$i]['Song']['SongTitle'] . '(Explicit)';
					} else {
						$song_title = $nationalTopDownload[$i]['Song']['SongTitle'];
					}

					$replaceString	.=  $this->Queue->getNationalsongsStreamNowLabel( $nationalTopDownload[$i]['Full_Files']['CdnPath'], $nationalTopDownload[$i]['Full_Files']['SaveAsName'], $song_title, $nationalTopDownload[$i]['Song']['ArtistText'], $nationalTopDownload[$i]['Song']['FullLength_Duration'], $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'] );

				} else if ( $nationalTopDownload[$i]['Country']['SalesDate'] <= date( 'Y-m-d' ) ) {

					$replaceString	.= $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $nationalTopDownload[$i]['Song']['ProdID'] . ', "' . base64_encode( $nationalTopDownload[$i]['Song']['provider_type'] ) . '", "' . $this->webroot . '");') );
					$replaceString	.= $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
					$replaceString	.= $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
				}

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_streaming';
				$strNationalTop100 = str_replace( $searchString, $replaceString, $strNationalTop100 );

				$replaceString = '';

				if ( $nationalTopDownload[$i]['Country']['SalesDate'] <= date( 'Y-m-d' ) ) {

					if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ) {

						if ( $this->Session->read( 'downloadVariArray' ) ) {
							$downloadsUsed = $this->Download->getDownloadResults( $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'] );
						} else {
							$downloadsUsed = $this->Download->getDownloadfind( $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );
						}

						if ( !( $downloadsUsed > 0 ) ) {

							$replaceString	.= "<span class='top-100-download-now-button'>
							<form method='Post' id='form" . $nationalTopDownload[$i]['Song']['ProdID'] . "' action='/homes/userDownload' class='suggest_text1'>
							<input type='hidden' name='ProdID' value='" . $nationalTopDownload[$i]['Song']['ProdID'] . "' />
							<input type='hidden' name='ProviderType' value='" . $nationalTopDownload[$i]['Song']['provider_type'] . "' />
							<span class='beforeClick' style='cursor:pointer;' id='wishlist_song_" . $nationalTopDownload[$i]['Song']['ProdID'] . "'>
							<![if !IE]>
							<a href='javascript:void(0);' class='add-to-wishlist no-ajaxy top-10-download-now-button'
							title='" . __('IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.', true) . "'
							onclick='return wishlistDownloadOthersHome(\"" . $nationalTopDownload[$i]['Song']['ProdID'] ."\", \"0\", \"" . $nationalTopDownload[$i]['Full_Files']['CdnPath'] . "\", \"" . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] ."\", \"" . $nationalTopDownload[$i]['Song']['provider_type'] ."\");'>
							" . __('Download Now', true) . "</a>
							<![endif]>
							<!--[if IE]>
							<a id='song_download_" . $nationalTopDownload[$i]['Song']['ProdID'] ."'
							class='no-ajaxy top-10-download-now-button'
							title='IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.'
							onclick='wishlistDownloadIEHome(\'" . $nationalTopDownload[$i]['Song']['ProdID'] ."\', \'0\' , \'" . $nationalTopDownload[$i]['Song']['provider_type'] ."\', \'" . $nationalTopDownload[$i]['Full_Files']['CdnPath'] ."\', \'" . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] ."\');'
							href='javascript:void(0);'>" . __('Download Now', true) . "</a>
							<![endif]-->
							</span>
							<span class='afterClick' id='downloading_" . $nationalTopDownload[$i]['Song']['ProdID'] ."' style='display:none;'><a  class='add-to-wishlist'  >" . __('Please Wait..', true) ."
							<span id='wishlist_loader_" . $nationalTopDownload[$i]['Song']['ProdID'] ."' style='float:right;padding-right:8px;padding-top:2px;'>" . $html->image('ajax-loader_black.gif') ."</span> </a> </span>
							</form>
							</span>";
						} else {
							$replaceString	.= '<a class="top-100-download-now-button" href="/homes/my_history" title="' . __("You have already downloaded this song. Get it from your recent downloads", true) .'">' . __('Downloaded', true) .'</a>';
						}
					} else {
						$replaceString	.= '<a class="top-100-download-now-button" href="javascript:void(0);">' . __("Limit Met", true) .'</a>';
					}
				} else {
					$replaceString	.= '<a class="top-100-download-now-button" href="javascript:void(0);">
					<span title="' . __("Coming Soon", true) .' (';

					if ( isset($nationalTopDownload[$i]['Country']['SalesDate'] ) ) {
						$replaceString	.= date( "F d Y", strtotime( $nationalTopDownload[$i]['Country']['SalesDate'] ) );
					}

					$replaceString	.= ' )">
					' . __("Coming Soon", true) .'
					</span>
					</a>';
				}

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_download';
				$strNationalTop100 = str_replace( $searchString, $replaceString, $strNationalTop100 );

				$replaceString = '';

				$replaceString	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
				<div class="wishlist-popover">
				<input type="hidden" id="' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" value="song"/>';

				if ( $library_type == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1 ) {
					$replaceString	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
				}

				$wishlistInfo = $wishlist->getWishlistData( $nationalTopDownload[$i]["Song"]["ProdID"] );

				$replaceString	.= $wishlist->getWishListMarkup( $wishlistInfo, $nationalTopDownload[$i]["Song"]["ProdID"], $nationalTopDownload[$i]["Song"]["provider_type"] );

				$replaceString	.= ' </div>';

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_wishlist';
				$strNationalTop100 = str_replace( $searchString, $replaceString, $strNationalTop100 );
			} else {

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_streaming';
				$strNationalTop100 = str_replace( $searchString, '', $strNationalTop100 );

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_wishlist';
				$strNationalTop100 = str_replace( $searchString, '', $strNationalTop100 );

				$searchString 	   = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_download';
				$strNationalTop100 = str_replace( $searchString, '<a class="top-100-download-now-button" href="/users/redirection_manager"> ' . __("Login", true) .'</a>', $strNationalTop100 );
			}
		}
	}

	if ( is_array( $nationalTopAlbumsDownload ) && count( $nationalTopAlbumsDownload ) > 0 ) {

		foreach ( $nationalTopAlbumsDownload as $key => $value ) {

			$replaceString = '';

			if ( isset( $patronId ) && !empty( $patronId ) ) {

				if ( $library_type == 2 && !empty( $value['albumSongs'] ) ) {

					$replaceString	.= $this->Queue->getNationalAlbumStreamLabel( $value['Song']['ArtistText'], $value['Albums']['ProdID'], $value['Song']['provider_type'] );
					$replaceString	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>';
				}

				$replaceString	.= '<div class="wishlist-popover">
				<input type="hidden" id="' . $value['Albums']['ProdID'] .'" value="album"/>';

				if ( $library_type == 2 && !empty( $value['albumSongs'] ) ) {
					$replaceString	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
				}

				$replaceString	.= '</div>';

				$searchString 	   = $value['Song']['ProdID'] . '_' . $value['Song']['provider_type'] . '_albums';
				$strNationalTop100 = str_replace( $searchString, $replaceString, $strNationalTop100 );

			} else {

				$replaceString	.= '<a class="top-100-download-now-button " href="/users/redirection_manager"> ' . __("Login", true) . '</a>';

				$searchString 	   = $value['Song']['ProdID'] . '_' . $value['Song']['provider_type'] . '_albums';
				$strNationalTop100 = str_replace( $searchString, $replaceString, $strNationalTop100 );
			}
		}
	}

	echo $strNationalTop100;

	$strFeaturedAlbums = $this->Home->featuredAlbums( $featuredArtists );

	if ( is_array( $featuredArtists ) && count( $featuredArtists ) > 0 ) {

		foreach ( $featuredArtists as $k => $v ) {

			$replaceString = '';

			if ( isset( $patronId ) && !empty( $patronId ) ) {

				if ( $library_type == 2 && !empty( $v['albumSongs'][$v['Album']['ProdID']] ) ) {

					$replaceString .= $this->Queue->getAlbumStreamNowLabel( $v['albumSongs'][$v['Album']['ProdID']] );

					$replaceString .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
					<div class="wishlist-popover">
					<input type="hidden" id="' . $v['Album']['ProdID'] .'" value="album"/>
					<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
					</div>';
				}
			} else {
				$replaceString .= '<a class="top-100-download-now-button" href="/users/redirection_manager">' . __("Login", true) . '</a>';
			}

			$searchString 	   = $v['Album']['ProdID'] . $v['Album']['provider_type']  . '_featured_album';
			$strFeaturedAlbums = str_replace( $searchString, $replaceString, $strFeaturedAlbums );
		}
	}

	echo $strFeaturedAlbums;

	$strComingSoonData = $this->Home->comingSoon( $coming_soon_rs, $coming_soon_videos );

	if ( is_array( $coming_soon_rs ) && count( $coming_soon_rs ) > 0 ) {

		foreach ( $coming_soon_rs as $key => $value ) {

			$replaceString = '';

			if ( isset( $patronId ) && !empty( $patronId ) ) {

				$replaceString .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
				<div class="wishlist-popover">';

				$wishlistInfo = $this->Wishlist->getWishlistData( $value["Song"]["ProdID"] );
				$replaceString .= $this->Wishlist->getWishListMarkup( $wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"] );
				$replaceString .= '</div>';
			}

			$searchString 	   = $value["Song"]["ProdID"] . $value["Song"]["provider_type"] . '_coming_soon_song';
			$strComingSoonData = str_replace( $searchString, $replaceString, $strComingSoonData );
		}
	}

	if ( is_array( $coming_soon_videos ) && count( $coming_soon_videos ) > 0 ) {

		foreach ( $coming_soon_videos as $key => $value ) {

			$replaceString = '';

			if ( isset( $patronId ) && !empty( $patronId ) ) {

				$replaceString .= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
				<div class="wishlist-popover">';

				$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
				$replaceString .= $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
				$replaceString .= '</div>';
			}

			$searchString 	   = $value['Video']['ProdID'] . $value['Video']['provider_type'] . '_coming_soon_video';
			$strComingSoonData = str_replace( $searchString, $replaceString, $strComingSoonData );
		}
	}

	echo $strComingSoonData;

	echo $this->Home->news($news);
	?>
</section>