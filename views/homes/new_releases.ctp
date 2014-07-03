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
		<div class="album-scrollable horiz-scroll">
            <ul style="width:27000px;">
                <?php
                $count = 1;
                if ( isset( $new_releases_albums ) && is_array( $new_releases_albums ) && count( $new_releases_albums ) > 0 ) {
	                foreach ( $new_releases_albums as $value ) {
				?>					
                    	<li>
                        	<div class="album-container">
	                            <?=$html->link( $html->image( $value['albumImage'], array("height" => "250", "width" => "250")), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false))?>
	                            <div class="top-10-ranking"><?=$count?></div>
	                            <?php
	                            if ( isset( $patronId ) && ! empty( $patronId )) {
									if ( isset( $libraryType ) && $libraryType == 2 && !empty( $value['albumSongs'][$value['Albums']['ProdID']] ) ) {
	                                    echo $this->Queue->getAlbumStreamNowLabel($value['albumSongs'][$value['Albums']['ProdID']]);
								?>
	                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
	                                    <div class="wishlist-popover">
	                                        <input type="hidden" id="<?= $value['Albums']['ProdID'] ?>" value="album"/>
	                                        <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
	                                    </div>
								<?php
									}
	                            } else {
	                            ?>
	                                <a class="top-10-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a>
	                            <?php } ?>
                        	</div>
                        	<div class="album-title">
                        		<?php 
	                        		$albumTitle = $this->Home->trimString( $value['Albums']['AlbumTitle'], 20 );
	                        		$albumTitle = $this->Home->explicitContent( $value['Albums']['Advisory'], $albumTitle, true );
                        		?>					
	                            <a title="<?= $this->getTextEncode( $value['Albums']['AlbumTitle']); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>"> <?=$this->getTextEncode( $albumTitle )?> </a>
                        	</div>
                        	<div class="artist-name">							
	                            <a title="<?= $this->getTextEncode($value['Song']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
	                                <?=$this->getTextEncode( $this->Home->trimString( $value['Song']['Artist'], 32 ) ); ?>
	                            </a>
	                        </div>
                    	</li>
                <?php
                    	$count++;
                	}
				}
                ?>
            </ul>
		</div>
    </div>

	<h3>Videos</h3>
	<div class="videos-shadow-container">
    	<div class="videos-scrollable horiz-scroll">
			<ul style="width:44100px;">
            	<?php
	            $count = 1;
	            if ( isset( $new_releases_videos ) && is_array( $new_releases_videos ) && count( $new_releases_videos ) > 0 ) {
					foreach ( $new_releases_videos as $value ) {
				?>
						<li>
							<div class="video-container">
                        		<a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                            		<img src="<?php echo $value['videoAlbumImage']; ?>" alt="<?php echo $this->getValidText($value['Video']['Artist'] . ' - ' . $value['Video']['VideoTitle']); ?>" width="423" height="250" />
                        		</a>
		                        <div class="top-10-ranking"><?= $count; ?></div>
		                        <?php
								if ( isset( $patronId ) && ! empty( $patronId ) ) {
		                            if ( $value['Country']['SalesDate'] <= date( 'Y-m-d' ) ) {
		                                if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1') {
		
		                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value['Video']['provider_type'], $libraryId, $patronId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
		                                    $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);
		
		                                    if ( !( $downloadsUsed > 0 ) ) {
								?>
		                                        <div class="mylib-top-10-video-download-now-button">
		                                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
		                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"]; ?>" />
		                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
		                                                <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
		                                                    <![if !IE]>
		                                                    	<a class="no-ajaxy" href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?php __('Download Now'); ?></label></a>
		                                                    <![endif]>
		                                                    <!--[if IE]>
		                                                            <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a></label>
		                                                    <![endif]-->
		                                                </span>
		                                                <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp'); ?></span>
		                                                <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
		                                            </form>
		                                        </div>
								<?php
											} else {
		                        ?>
												<a class="mylib-top-10-video-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
								<?php
											}
										} else {
								?>
											<a class="mylib-top-10-video-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?></a>                    
								<?php
										}
									} else {
								?>
										<a class="mylib-top-10-video-download-now-button" href="javascript:void(0);">
											<span title='<?php __("Coming Soon"); ?> ( 
											<?php
												if ( isset( $value['Country']['SalesDate'] ) ) {
													echo date("F d Y", strtotime($value['Country']['SalesDate']));
												}
			                                ?> )'><?php __("Coming Soon"); ?>
			                                </span>
										</a>
						<?php
									}
								} else {
						?>
									<a class="mylib-top-10-video-download-now-button" href='/users/redirection_manager'> <?php __("Login"); ?></a>
						<?php
								}
							
								if ( isset( $patronId ) && ! empty( $patronId ) ) {
						?> 
									<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
			                        <div class="wishlist-popover">
			                        	<?php
			                                $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']["ProdID"]);
			                                echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value['Video']["ProdID"], $value['Video']["provider_type"]);
			                            ?>  
			                         </div>
		                 <?php 	} ?>
							</div>
		                    <div class="album-title">
		                    	<?php 
	                        		$videoTitle = $this->Home->trimString( $value['Video']['VideoTitle'], 20 );
	                        		$videoTitle = $this->Home->explicitContent( $value['Video']['Advisory'], $videoTitle, true );
                        		?>
		                        <a title="<?= $this->getValidText($value['Video']['VideoTitle']); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"> <?=$this->getTextEncode( $videoTitle )?> </a>
		                    </div>
		                    <div class="artist-name">
		                        <a title="<?php echo $this->getValidText($value['Video']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Video']['ArtistText'])); ?>/<?= base64_encode($value['Genre']['Genre']) ?>">
		                            <?=$this->getTextEncode( $this->Home->trimString( $value['Video']['Artist'], 32 ) )?>
		                        </a>
		                    </div>
						</li>
			<?php
						$count++;
					}
				}
            ?>
			</ul>
    	</div>
	</div>
</section>