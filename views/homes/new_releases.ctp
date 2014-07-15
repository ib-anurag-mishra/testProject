<section class="my-top-100-page">
	<div class="breadcrumbs">
        <?php
	        $html->addCrumb('New Releases', '/homes/new_releases');
	        echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
        ?>
    </div>
    <header> <h2><?php __('New Releases'); ?></h2> </header>
    <h3>Albums</h3>
    <div class="album-shadow-container">
		 <div class="album-scrollable horiz-scroll carousel">
            <ul style="width:16500px;">
                <?php
                $count = 1;
                if ( isset( $new_releases_albums ) && is_array( $new_releases_albums ) && count( $new_releases_albums ) > 0 ):
	                foreach ( $new_releases_albums as $value ):
				?>					
                    	<li>
                        	<div class="album-container">
	                            <?=$html->link( $html->image( $value['albumImage'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))?>
	                            <div class="top-10-ranking"><?=$count?></div>
	                            <?php
	                            if ( isset( $patronId ) && ! empty( $patronId )):
									if ( isset( $libraryType ) && $libraryType == 2 && !empty( $value['albumSongs'][$value['Albums']['ProdID']] ) ):
	                                    echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
									 	echo $this->Html->link('', 'javascript:void(0)', array('class' => 'playlist-menu-icon add-to-playlist-button no-ajaxy'));
								?>
                                    <ul> <li><?php echo $this->Html->link('Create New Playlist...', '#', array('class' => 'create-new-playlist'))?></li> </ul>
								<?php
									endif;
	                           	endif;
	                           	?>
                        	</div>
                        	<div class="album-title">
                        		<?php 
	                        		$albumTitle = $this->Home->trimString( $value['Albums']['AlbumTitle'], 20 );
	                        		$albumTitle = $this->Home->explicitContent( $value['Albums']['Advisory'], $albumTitle, true );
	                        		
	                        		echo $this->Html->link( $this->getTextEncode( $albumTitle ), array( 'controller' => 'artists', 'action' => 'view', base64_encode( $value['Song']['ArtistText'] ), $value['Song']['ReferenceID'], base64_encode( $value['Song']['provider_type'] ) ), array( 'title' => $this->getTextEncode( $value['Albums']['AlbumTitle']) ) );
                        		?>					
                        	</div>
                        	<div class="artist-name">							
	                            <?php echo $this->Html->link( $this->getTextEncode( $this->Home->trimString( $value['Song']['Artist'], 32 ) ), array( 'controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($value['Song']['ArtistText'])), base64_encode($value['Genre']['Genre']) ), array( 'title' => $this->getTextEncode($value['Song']['Artist']) ) );?>
	                        </div>
                    	</li>
                <?php
                    	$count++;
                	endforeach;
				endif;
                ?>
            </ul>
		</div>
		<button class="left-scroll-button"  type="button"></button>
        <button class="right-scroll-button" type="button"></button>
    </div>

	<h3>Videos</h3>
	<div class="videos-shadow-container">
    	<div class="videos-scrollable horiz-scroll carousel">
			<ul style="width:29100px;">
            	<?php
	            $count = 1;
	            if ( isset( $new_releases_videos ) && is_array( $new_releases_videos ) && count( $new_releases_videos ) > 0 ):
					foreach ( $new_releases_videos as $value ):
				?>
						<li>
							<div class="video-container">
                        		<?php echo $this->Html->link( $this->Html->image( $value['videoAlbumImage'], array( 'alt' => $this->getValidText($value['Video']['Artist'] . ' - ' . $value['Video']['VideoTitle'] ), 'width' => '423', 'height' => '250' ) ), array( 'controller' => 'videos', 'action' => 'details', $value['Video']['ProdID'] ), array( 'escape' => false ) );?>
		                        <div class="top-10-ranking"><?= $count; ?></div>
		                        <?php
								if ( isset( $patronId ) && ! empty( $patronId ) ):
		                            if ( $value['Country']['SalesDate'] <= date( 'Y-m-d' ) ):
		                                if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1'):
		
		                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libraryId, $patronId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
		                                    $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
		
		                                    if ( !( $downloadsUsed > 0 ) ):
		                                    	$title = 'IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.';
								?>
		                                        <div class="mylib-top-10-video-download-now-button">
		                                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
		                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
		                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
		                                                <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
		                                                    <![if !IE]>
		                                                    	<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'top-10-download-now-button no-ajaxy', 'title' => $title, 'onclick' => "return wishlistVideoDownloadOthersToken(('{$value['Video']['ProdID']}', '0', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}', '{$value["Video"]["provider_type"]}')", 'escape' => false ) );?>
		                                                    <![endif]>
		                                                    <!--[if IE]>
		                                                    	<?php echo $this->Html->link( 'Download Now', 'javascript:void(0)', array( 'class' => 'top-10-download-now-button no-ajaxy', 'title' => $title, 'onclick' => "return wishlistVideoDownloadIEToken( '{$value["Video"]['ProdID']}', '0', '{$value["Video"]["provider_type"]}', '{$productInfo[0]['Full_Files']['CdnPath']}', '{$productInfo[0]['Full_Files']['SaveAsName']}')", 'escape' => false ) );?>
		                                                    <![endif]-->
		                                                </span>
		                                                <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
		                                                <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
		                                            </form>
		                                        </div>
								<?php
											else:
												echo $this->Html->link( $this->Html->tag( 'label', 'Downloaded', array('class' => 'dload', 'style' => 'width: 120px; cursor: pointer;', 'title' => 'You have already downloaded this song. Get it from your recent downloads') ), array( 'controller' => 'homes', 'action' => 'my_history'), array('class' => 'mylib-top-10-video-download-now-button', 'escape' => false ) );
											endif;
										else:
											echo $this->Html->link( 'Limit Met', 'javascript:void(0)', array( 'class' => 'mylib-top-10-video-download-now-button' ) );
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
									echo $this->Html->link('', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy'));
						?>
			                        <div class="wishlist-popover">
			                        	<?php
			                                $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']["ProdID"]);
			                                echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']["ProdID"], $value['Video']["provider_type"]);
			                            ?>  
			                         </div>
		                 <?php 	endif; ?>
							</div>
		                    <div class="album-title">
		                    	<?php 
	                        		$videoTitle = $this->Home->trimString( $value['Video']['VideoTitle'], 20 );
	                        		$videoTitle = $this->Home->explicitContent( $value['Video']['Advisory'], $videoTitle, true );
	                        		
	                        		echo $this->Html->link( $this->getTextEncode( $videoTitle ), array( 'controller' => 'videos', 'action' => 'details', $value['Video']['ProdID'] ), array( 'title' => $this->getValidText($value['Video']['VideoTitle']),'escape' => false ) );
                        		?>
		                    </div>
		                    <div class="artist-name">
		                    <?php echo $this->Html->link( $this->getTextEncode( $this->Home->trimString( $value['Video']['Artist'], 32 ) ), array( 'controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($value['Video']['ArtistText'])), base64_encode($value['Genre']['Genre']) ), array( 'title' => $this->getTextEncode($value['Video']['Artist']) ) );?>
		                    </div>
						</li>
			<?php
						$count++;
					endforeach;
				endif;
            ?>
			</ul>
    	</div>
    	<button class="left-scroll-button"  type="button"></button>
        <button class="right-scroll-button" type="button"></button>
	</div>
</section>