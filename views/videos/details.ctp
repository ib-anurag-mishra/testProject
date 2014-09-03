<section class="individual-videos-page">
	<div class="breadcrumbs">                
    <?php
		$html->addCrumb( __( 'Video', true ), 'javascript:void(0);' );
        echo $html->getCrumbs( '>', __( 'Home', true ), '/homes' );
	?>
    </div>
    <div class="hero-container clearfix">
        <div class="hero-image-container">
            <img src="<?= $videosData[0]['videoImage']; ?>" alt="<?= $this->getValidText($videosData[0]['Video']['VideoTitle']); ?>" width="555" height="323" />
            <?php
            if ( $this->Session->read( 'patron' ) ):

                if ( strtotime( $videosData[0]['Country']['SalesDate'] ) < time() ):
                	if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):

                        $productInfo	  = $mvideo->getDownloadData( $videosData[0]["Video"]["ProdID"], $videosData[0]["Video"]["provider_type"] );                        
                        $videoUrl		  = $this->Token->regularToken( $productInfo[0]['Full_Files']['CdnPath'] . "/" . $productInfo[0]['Full_Files']['SaveAsName'] );
                        $finalVideoUrl	  = Configure::read( 'App.Music_Path' ) . $videoUrl;
                        $finalVideoUrlArr = str_split( $finalVideoUrl, ceil( strlen( $finalVideoUrl ) / 3 ) );
                        $downloadsUsed 	  = $this->Videodownload->getVideodownloadfind( $videosData[0]['Video']['ProdID'], $videosData[0]['Video']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );
                        
                        if ( ! ( $downloadsUsed > 0 ) ):
            ?>
                            <div class="download-now-button ">
                                <form method="Post" id="form<?= $videosData[0]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                    <input type="hidden" name="ProdID" value="<?= $videosData[0]["Video"]["ProdID"]; ?>" />
                                    <input type="hidden" name="ProviderType" value="<?= $videosData[0]["Video"]["provider_type"]; ?>" />
                                    <span class="beforeClick" id="download_video_<?= $videosData[0]["Video"]["ProdID"]; ?>">
                                        <![if !IE]>
                                        	<a class="top-10-download-now-button no-ajaxy" href="javascript:void(0);" title="<?= __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?= $videosData[0]['Video']['ProdID']; ?>", "0", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>",  "<?= $videosData[0]['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?= __('Download Now'); ?></label></a>
                                        <![endif]>
                                        <!--[if IE]>
											<label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?= $videosData[0]['Video']['ProdID']; ?>','0','<?= $videosData[0]['Video']['provider_type']; ?>', '<?= $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?= __('Download Now'); ?></a></label>
                                        <![endif]-->
                                    </span>
                                    <span class="afterClick" id="vdownloading_<?= $videosData[0]["Video"]["ProdID"]; ?>" style="display:none;"><?= __('Please Wait'); ?>...&nbsp;&nbsp;</span>
                                    <span id="vdownload_loader_<?= $videosData[0]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?= $html->image( 'ajax-loader_black.gif', array( 'style' => 'margin-top:-20px;width:16px;height:16px;' ) ); ?></span>
                                </form>
                            </div>
                  <?php else: ?>
                            <a class="download-now-button top-10-download-now-button" href='/homes/my_history' title='<?= __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?= __('Downloaded'); ?></a>
                  <?php
                        endif;
                    endif;
                  ?>
                    <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                    <div class="wishlist-popover">

              			<?php $wishlistInfo = $this->WishlistVideo->getWishlistVideoData( $videosData[0]["Video"]["ProdID"] );                        
                    		  echo $this->WishlistVideo->getWishListVideoMarkup( $wishlistInfo, $videosData[0]["Video"]["ProdID"], $videosData[0]["Video"]["provider_type"] );
              			?>                                                    
                    </div>
          <?php else: ?>    
                    <span class="download-now-button top-10-download-now-button"><a href='javascript:void(0);' title='<?= __('Coming Soon'); ?>  ( <?php
                        if ( isset($videosData[0]['Country']['SalesDate'] ) ):
                        	echo date( "F d Y", strtotime( $videosData[0]['Country']['SalesDate'] ) );
                        endif;
                        ?> ) ' style="width:120px;cursor:pointer;"><?= __('Coming Soon'); ?></a></span>
		  <?php
				endif;
			else:
		  ?>
                <span class="download-now-button">
                    <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?= __("Login"); ?></a>
                </span>
      <?php endif; ?>
        </div>
        <div class="hero-detail">
            <h2 class="song-title"> <?= $this->getTextEncode(wordwrap($videosData[0]['Video']['VideoTitle'], 15, "<br />")); ?> </h2>
            <?php if ( $videosData[0]['Video']['Advisory'] == 'T'): ?> 
            	  	<span style="color: red;display: inline;"> (Explicit)</span> 
            <?php endif; ?>
            <h3 class="artist-name">
                <a title="<?= $this->getTextEncode( $videosData[0]['Video']['ArtistText'] ); ?>" href="/artists/album/<?= base64_encode( $videosData[0]['Video']['ArtistText'] ); ?>"><?= $this->getValidText( $videosData[0]['Video']['ArtistText'] ); ?></a>
            </h3>
            <?php $duration_arr = explode( ":", $videosData[0]['Video']['FullLength_Duration'] ); ?>
            <div class="release-information">
                <p><?= __('Release Information', true); ?> </p>
                <div class="release-date">Date: <?= date( "M d, Y", strtotime( $videosData[0]['Country']['SalesDate'] ) ); ?></div>
                <div class="video-duration">Duration: <?= $duration_arr[0] . " min " . $duration_arr[1] . " sec"; ?></div>
            </div>
        </div>
	</div>
    <section class="more-videos">
        <header>
            <h2><?= __('More Videos By', true); ?> <?= $this->getValidText( $videosData[0]['Video']['ArtistText'] ); ?></h2>
        </header>
        <div class="more-videos-scrollable horiz-scroll carousel">
            <ul>
            <?php
                if ( is_array( $moreVideosData ) && count( $moreVideosData ) > 0 ):

                    foreach ( $moreVideosData as $key => $value ):
			?>								
                        <li>
                            <div class="video-thumb-container">
                                <a href="/videos/details/<?= $value['Video']['ProdID']; ?>"><img alt="" class="lazy" src="<?= $value['videoAlbumImage']; ?>" data-original="" width="274" height="162" /></a>
                            <?php
                                if ( $this->Session->read( 'patron' ) ):

                                    if ( strtotime( $value['Country']['SalesDate'] ) < time() ):
                                    	
                                        if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):

                                            $downloadsUsed = $this->Videodownload->getVideodownloadfind( $value['Video']['ProdID'], $value['Video']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );

                                            if ( ! ( $downloadsUsed > 0 ) ):
                            ?>
                                                <div class="download-now-button ">
                                                    <form method="Post" id="form<?= $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?= $value["Video"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?= $value["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="download_video_<?= $value["Video"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a class="no-ajaxy top-10-download-now-button" href="javascript:void(0);" title="<?= __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?= $value['Video']['ProdID']; ?>", "0", "<?= $value['Full_Files']['CdnPath']; ?>", "<?= $value['Full_Files']['SaveAsName']; ?>",  "<?= $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?= __('Download Now'); ?></label></a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?= $value['Video']['ProdID']; ?>','0','<?= $value['Video']['provider_type']; ?>', '<?= $value['Full_Files']['CdnPath']; ?>', '<?= $value['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?= __('Download Now'); ?></a></label>
                                                            <![endif]-->
                                                        </span>
                                                        <span class="afterClick" id="vdownloading_<?= $value["Video"]["ProdID"]; ?>" style="display:none;"><?= __('Please Wait'); ?>...&nbsp;&nbsp;</span>
                                                        <span id="vdownload_loader_<?= $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?= $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                    </form>
                                                </div>
                                      <?php else: ?>
                                                <a class="download-now-button top-10-download-now-button" href='/homes/my_history' title='<?= __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?= __('Downloaded'); ?></a>
                                      <?php 
                                      		endif;
                                        endif;
                                      ?>
                                        <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                                        <div class="wishlist-popover">
                                        <?php
                                            $wishlistInfo = $this->WishlistVideo->getWishlistVideoData( $value['Video']['ProdID'] );
                                            echo $this->WishlistVideo->getWishListVideoMarkup( $wishlistInfo, $value['Video']['ProdID'], $value['Video']["provider_type"] );
                                        ?> 
                                        </div>
                              <?php else: ?>
                                        <span class="download-now-button top-10-download-now-button"><a  href='javascript:void(0);' style="width:120px;cursor:pointer;" title='<?= __('Coming Soon'); ?>  ( <?php
                                            if ( isset( $value['Country']['SalesDate'] ) ):
                                                echo date( "F d Y", strtotime( $value['Country']['SalesDate'] ) );
                                            endif;
                                            ?> ) '><?= __('Coming Soon'); ?></a></span>
                              <?php
                                    endif;
                                else:
                              ?>
                                	<span class="download-now-button">
                                        <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?= __("Login"); ?></a>
                                    </span>
                          <?php endif; ?>
							</div>
                            <div class="song-title">
                                <a title="<?= $this->getValidText( $this->getTextEncode( $value['Video']['VideoTitle'] ) ); ?>" href="/videos/details/<?= $value['Video']['ProdID']; ?>">
                                <?php
                                    if ( strlen( $value['Video']['VideoTitle'] ) >= 20 ):
                                        echo $this->getTextEncode( substr( $value['Video']['VideoTitle'], 0, 20 ) ) . "..";
                                    else:
                                        echo $this->getTextEncode( $value['Video']['VideoTitle'] );
                                    endif;
                                ?>
                                </a>
                                <?php
                                if ( 'T' == $value['Video']['Advisory'] ): ?>
                                	<span style="color: red;display: inline;"> (Explicit)</span> 
                          <?php endif; ?>
                            </div>
                            <div class="artist-name">	
                            <?php $artistText = $videosData[0]['Video']['ArtistText']; ?>
                                <a title="<?= $this->getValidText( $this->getTextEncode( $artistText ) ); ?>" href="/artists/album/<?= base64_encode( $artistText ); ?>">
                                <?php
                                    if ( strlen( $value['Video']['ArtistText'] ) >= 35 ):
                                        echo $this->getTextEncode(substr($artistText, 0, 35)) . "..";
                                    else:
                                        echo $this->getTextEncode($artistText);
                                    endif;
                                ?>
                                </a>
                            </div>
                        </li>
           <?php
                    endforeach;
                else:
                    echo 'Sorry,there are no more videos.';
                endif;
           ?>
            </ul>
        </div>
        <button class="left-scroll-button" type="button"></button>
        <button class="right-scroll-button" type="button"></button>
    </section>
    <section class="top-videos">
        <header>
            <h2>Top <span><?= $this->getTextEncode( $videoGenre ); ?></span> Videos</h2>
        </header>
        <div class="top-videos-scrollable horiz-scroll carousel">
            <ul>
            <?php
            if ( is_array( $topVideoGenreData ) && count( $topVideoGenreData ) > 0 ):

                foreach ( $topVideoGenreData as $key => $value ):
            ?>
                    <li>
                        <div class="video-thumb-container">
                            <a href="/videos/details/<?= $value['Video']['ProdID']; ?>"><img alt="" class="lazy" src="<?= $value['videoImage']; ?>" width="274" height="162" /></a>
                            <?php
                            if ( $this->Session->read( 'patron' ) ):

                                if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1' ):

                                    $productInfo   = $mvideo->getDownloadData( $value["Video"]["ProdID"], $value["Video"]["provider_type"] );
                                    $downloadsUsed = $this->Videodownload->getVideodownloadfind( $value['Video']['ProdID'], $value['Video']['provider_type'], $libraryId, $patronId, Configure::read( 'App.twoWeekStartDate' ), Configure::read( 'App.twoWeekEndDate' ) );

                                    if ( ! ( $downloadsUsed > 0 ) ):
                            ?>
                                        <div class="download-now-button ">
                                            <form method="Post" id="form<?= $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                <input type="hidden" name="ProdID" value="<?= $value["Video"]["ProdID"]; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?= $value["Video"]["provider_type"]; ?>" />
                                                <span class="beforeClick" id="download_video_<?= $value["Video"]["ProdID"]; ?>">
                                                    <![if !IE]>
                                                    	<a class="no-ajaxy top-10-download-now-button" href="javascript:void(0);" title="<?= __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?= $value['Video']['ProdID']; ?>", "0", "<?= $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?= $value['Video']['provider_type']; ?>");'><label class="top-10-download-now-button"><?= __('Download Now'); ?></label></a>
                                                    <![endif]>
                                                    <!--[if IE]>
														<label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?= $value['Video']['ProdID']; ?>','0','<?= $value['Video']['provider_type']; ?>', '<?= $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?= $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?= __('Download Now'); ?></a></label>
                                                    <![endif]-->
                                                </span>
                                                <span class="afterClick" id="vdownloading_<?= $value["Video"]["ProdID"]; ?>" style="display:none;"><?= __('Please Wait'); ?>...&nbsp;&nbsp;</span>
                                                <span id="vdownload_loader_<?= $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?= $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                            </form>
                                        </div>
                              <?php else: ?>
                                    	<a class="download-now-button " href='/homes/my_history' title='<?= __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?= __('Downloaded'); ?></a>
                              <?php
                                    endif;
                                endif;
                              ?>
                                <a class="add-to-playlist-button" href="javascript:void(0);"></a>
                                <div class="wishlist-popover">
                                <?php
                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData( $value['Video']['ProdID'] );
                                    echo $this->WishlistVideo->getWishListVideoMarkup( $wishlistInfo, $value['Video']['ProdID'], $value['Video']["provider_type"] );
                                ?>
                                </div>
                      <?php else: ?>
                                <span class="download-now-button">
                                    <a class="featured-video-download-now-button top-10-download-now-button" href='/users/redirection_manager'> <?= __("Login"); ?></a>
                                </span>
                      <?php endif; ?>
                        </div>
                        <div class="song-title">
                            <a title="<?= $this->getTextEncode($value['Video']['VideoTitle']); ?>" href="/videos/details/<?= $value['Video']['ProdID']; ?>">
                            <?php
                                if (strlen($value['Video']['VideoTitle']) >= 20):
                                    echo $this->getTextEncode( substr( $value['Video']['VideoTitle'], 0, 20 ) ) . "..";
                                else:
                                    echo $this->getTextEncode( $value['Video']['VideoTitle'] );
                                endif;
                            ?>
                            </a>
                            <?php if ( 'T' == $value['Video']['Advisory'] ): ?> 
                            	  	<span style="color: red;display: inline;"> (Explicit)</span> 
                            <?php endif; ?>
                        </div>
                        <div class="artist-name">
                            <a title="<?= $this->getValidText( $this->getTextEncode( $value['Video']['ArtistText'] ) ); ?>" href="/artists/album/<?= base64_encode( $value['Video']['ArtistText'] ); ?>">
                            <?php
                                if ( strlen( $value['Video']['ArtistText'] ) >= 35 ):
                                    echo $this->getTextEncode( substr( $value['Video']['ArtistText'], 0, 35 ) ) . "..";
                                else:
                                    echo $this->getTextEncode( $value['Video']['ArtistText'] );
                                endif;
                                ?>
                            </a>
                        </div>
                    </li>
		<?php
                endforeach;
			endif;
        ?>
            </ul>
		</div>
        <button class="left-scroll-button" type="button"></button>
        <button class="right-scroll-button" type="button"></button>
    </section>		
</section>