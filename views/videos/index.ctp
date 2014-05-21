<section class="videos">
	<section class="featured-videos">
        <header class="clearfix">
        	<h3><?= __('Featured Videos', true); ?></h3>
        </header>
        <section id="featured-video-grid" class="horiz-scroll">
            <ul class="clearfix">
            <?php if ( is_array( $featuredVideos ) && count( $featuredVideos ) > 0 ):

                    $total_videos = count( $featuredVideos );
                    $sr_no = 0;

                    foreach ( $featuredVideos as $key => $featureVideo ):
                        if ( $sr_no % 2 == 0 ): ?>
							<li>
				  <?php endif; ?>
                            <div class="featured-video-detail">
                                <div class="video-thumbnail-container">
                                    <a href="/videos/details/<?=$featureVideo["FeaturedVideo"]["ProdID"]; ?>"><img src="<?=$featureVideo['videoImage']; ?>" data-original="" width="275" height="162" alt="" /></a>
                                    <?php
                                    if ( $this->Session->read( 'patron' ) ):

                                        if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):

                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind( $featureVideo['FeaturedVideo']['ProdID'], $featureVideo['Video']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );

                                            if ( $downloadsUsed > 0 ):
                                                $featureVideo['Video']['status'] = 'avail';
                                            else:
                                                $featureVideo['Video']['status'] = 'not';
                                            endif;

                                            if ($featureVideo['Video']['status'] != 'avail'):
                                    ?>
                                                <div class="featured-video-download-now-button">
                                                    <form method="Post" id="form<?=$featureVideo["FeaturedVideo"]["ProdID"]?>" action="/videos/download">
                                                        <input type="hidden" name="ProdID" value="<?=$featureVideo["FeaturedVideo"]["ProdID"]?>" />
                                                        <input type="hidden" name="ProviderType" value="<?=$featureVideo["Video"]["provider_type"]?>" />
                                                        <span class="beforeClick" id="download_video_<?=$featureVideo["FeaturedVideo"]["ProdID"]?>">
                                                            <![if !IE]>
                                                            <a href="javascript:void(0);" title="<?= __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?= $featureVideo['FeaturedVideo']['ProdID']; ?>", "0", "<?= $featureVideo['File']['CdnPath']; ?>", "<?= $featureVideo['Video_file']['SaveAsName']; ?>",  "<?= $featureVideo['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?= __('Download Now'); ?></label></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?= $featureVideo['FeaturedVideo']['ProdID']; ?>','0','<?= $featureVideo['Video']['provider_type']; ?>', '<?= $featureVideo['File']['CdnPath']; ?>', '<?= $featureVideo['Video_file']['SaveAsName']; ?>');" href="javascript:void(0);"><?= __('Download Now'); ?></a></label>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?= $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;"><?= __('Please Wait...&nbsp&nbsp'); ?></span>
                                                        <span id="vdownload_loader_<?= $featureVideo["FeaturedVideo"]["ProdID"]; ?>" style="display:none;float:right;"><?= $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </div>
                                      <?php else: ?>
                                                <a class="featured-video-download-now-button" href="/homes/my_history"><label class="dload" style="width:120px;cursor:pointer;" title='<?= __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?= __('Downloaded'); ?></label></a>
                                      <?php endif;
                                        else: ?>
                                            <a class="featured-video-download-now-button " href="javascript:void(0);"><?= __("Limit Met"); ?></a> 
                                            <?php
                                        endif;
                                        ?>
                                        <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a>
                                        <div class="wishlist-popover">
                                            <?php
                                            $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($featureVideo["FeaturedVideo"]["ProdID"]);
                                            echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $featureVideo["FeaturedVideo"]["ProdID"], $featureVideo["Video"]["provider_type"]);
                                            ?>
                                        </div>
                              <?php else: ?>
                                        <a class="featured-video-download-now-button" href='/users/redirection_manager'> <?= __("Login"); ?></a>
                              <?php endif; ?>
                                </div>
                                <div class="video-title">
                                    <a title="<?= $this->getValidText($this->getTextEncode($featureVideo['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $featureVideo["FeaturedVideo"]["ProdID"]; ?>">
                               		<?php if ( strlen( $featureVideo['Video']['VideoTitle'] ) >= 20 ):
                                            $featureVideo['Video']['VideoTitle'] = substr( $featureVideo['Video']['VideoTitle'], 0, 20 ) . '...';
                                          endif;
                                        ?>
                                        <?= $this->getTextEncode( $featureVideo['Video']['VideoTitle'] ); ?>
                                    </a> 
                                    <?php if ( isset( $featureVideo['Video']['Advisory'] ) && $featureVideo['Video']['Advisory'] == 'T'): ?>
										<span style="color: red;display: inline;"> (Explicit)</span>
									<?php endif; ?>
                                </div>
                                <div class="video-name">
                                    <?php
                                    if ( strlen( $featureVideo['Video']['ArtistText'] ) >= 20 ):
                                        $featureVideo['Video']['ArtistText'] = substr( $featureVideo['Video']['ArtistText'], 0, 20 ) . '...';
                                    endif;
                                    ?>
                                    <a title="<?= $this->getValidText( $this->getTextEncode( $featureVideo['Video']['ArtistText'] ) ); ?>" href="/artists/album/<?= base64_encode( $featureVideo['Video']['ArtistText']); ?>"><?= $this->getTextEncode($featureVideo['Video']['ArtistText'] ); ?></a>
                                </div>
                            </div>
                            <?php if ( $sr_no % 2 == 1 || $sr_no == ( $total_videos - 1 ) ): ?>
									</li>
							<?php endif; ?>
                        <?php
                        $sr_no++;
                    endforeach;
                endif;
            ?>
            </ul>
        </section>
    </section> <!-- end .featured-videos -->
    <section class="video-top-genres">
        <header class="clearfix">
            <h3><?php echo __('Top Videos', true); ?></h3>
        </header>
        <div class="video-top-genres-grid horiz-scroll" style="margin-top:26px;">
            <ul class="clearfix">
                <?php
                if ( is_array( $topVideoDownloads ) && count( $topVideoDownloads ) > 0 ):

                    $total_videos = count( $topVideoDownloads );
                    $sr_no = 0;

                    foreach ($topVideoDownloads as $key => $topDownload):
                        if ($sr_no % 2 == 0):
                ?>
							<li> 
                  <?php endif; ?>
						<div class="video-cover-container">
                        	<a href="/videos/details/<?= $topDownload["Videodownload"]["ProdID"]; ?>"><img alt="" src="<?= $topDownload['videoImage']; ?>" data-original="" width="163" height="97" /></a>
                        <?php
								if ( $this->Session->read( 'patron' ) ):
                        ?>
                                	<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)"></a> 
                          <?php else: ?>
                                    <a class="top-video-login-button" href='/users/redirection_manager'> <?= __("Login"); ?></a>
                          <?php endif; ?>
							<div class="wishlist-popover">
                          <?php
                                if ( $this->Session->read( 'patron' ) ):
                                        if ( $libraryDownload == '1' && $patronDownload == '1' ):
                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind( $topDownload['Video']['ProdID'], $topDownload['Video']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );

                                            if ( $downloadsUsed > 0 ):
                                                $topDownload['Video']['status'] = 'avail';
                                            else:
                                                $topDownload['Video']['status'] = 'not';
                                            endif;

                                            if ( $topDownload['Video']['status'] != 'avail' ):
                          ?>
												<form method="post" id="form<?= $topDownload["Video"]["ProdID"]; ?>" action="/videos/download">
                                                    <input type="hidden" name="ProdID" value="<?= $topDownload["Video"]["ProdID"]; ?>" />
                                                    <input type="hidden" name="ProviderType" value="<?= $topDownload["Video"]["provider_type"]; ?>" />
                                                    <span class="beforeClick" id="download_video_<?= $topDownload["Video"]["ProdID"]; ?>">
                                                        <![if !IE]>
                                                        <a class="no-ajaxy" href="javascript:void(0);" title="<?= __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?= $topDownload['Video']['ProdID']; ?>", "0", "<?= $topDownload['File']['CdnPath']; ?>", "<?= $topDownload['Video_file']['SaveAsName']; ?>",  "<?= $topDownload["Video"]["provider_type"]; ?>");'><label class="top-10-download-now-button"><?= __('Download Now'); ?></label></a>
                                                        <![endif]>
                                                        <!--[if IE]>
                                                                <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?= $topDownload['Video']['ProdID']; ?>','0','<?= $topDownload['Video']['provider_type']; ?>', '<?= $topDownload['File']['CdnPath']; ?>', '<?= $topDownload['Video_file']['SaveAsName']; ?>');" href="javascript:void(0);"><?= __('Download Now'); ?></a></label>
                                                        <![endif]-->
                                                    </span>
                                                    <span class="afterClick" id="vdownloading_<?= $topDownload["Video"]["ProdID"]; ?>" style="display:none;">
                                                        <?= __('Please Wait...&nbsp&nbsp'); ?>
                                                    </span>
                                                    <span id="vdownload_loader_<?= $topDownload["Video"]["ProdID"]; ?>" style="display:none;float:right;">
                                                        <?= $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?>
                                                    </span>                                                            
                                                </form>	
                                      <?php else: ?>
                                                <a class="featured-video-download-now-button " href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?></label></a>
                                      <?php endif;
                                        else: ?>
                                            <a class="featured-video-download-now-button " href="javascript:void(0);"><?php __("Limit Met"); ?></a> 
                                  <?php endif; ?>	
                                  <?php 
                                  		$wishlistInfo = $this->WishlistVideo->getWishlistVideoData( $topDownload["Video"]["ProdID"] );
                                        echo $this->WishlistVideo->getWishListVideoMarkup( $wishlistInfo, $topDownload["Video"]["ProdID"], $featureVideo["Video"]["provider_type"] );
                                  ?>
                          <?php endif; ?>
                            </div>
                        </div>
                        <div class="video-title">
							<a title="<?= $this->getValidText( $this->getTextEncode( $topDownload['Video']['VideoTitle'] ) ); ?>" href="/videos/details/<?= $topDownload["Videodownload"]["ProdID"]; ?>">
                        	<?php
                            	if ( strlen( $topDownload['Video']['VideoTitle'] ) >= 20 ):
									$topDownload['Video']['VideoTitle'] = substr( $topDownload['Video']['VideoTitle'], 0, 20 ) . '...';
                            	endif;
                        	?>
                        	<?= $this->getTextEncode( $topDownload['Video']['VideoTitle'] ); ?>
                            </a>
                      <?php if (isset($topDownload['Video']['Advisory']) && 'T' == $topDownload['Video']['Advisory']): ?> 
                      			<span style="color: red;display: inline;"> (Explicit)</span> 
                      <?php endif; ?>
                        </div>
                        <div class="video-name">
							<a title="<?= $this->getValidText( $this->getTextEncode( $topDownload['Video']['ArtistText'] ) ); ?>" href="/artists/album/<?= base64_encode( $topDownload['Video']['ArtistText'] ); ?>">
					  <?php
							if ( strlen( $topDownload['Video']['ArtistText'] ) >= 20 ):
                            	$topDownload['Video']['ArtistText'] = substr( $topDownload['Video']['ArtistText'], 0, 20 ) . '...';
                            endif;
                      ?>
                      <?= $this->getTextEncode( $topDownload['Video']['ArtistText'] ); ?>
                            </a>
						</div>
                  <?php if ( $sr_no % 2 == 1 || $sr_no == ( $total_videos - 1 ) ): ?>
                  			</li>
                  <?php endif;
                        $sr_no++;
                    endforeach;
                endif;
                ?>
            </ul>
        </div>
    </section> <!-- end .video-top-genres -->
</section> <!-- end .videos -->