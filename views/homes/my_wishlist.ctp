<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?=$sort?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?=$sortOrder?>" />
</form>
<section class="my-wishlist-page">
    <div class="breadcrumbs">
        <?php
	        $html->addCrumb(__('My Wishlist', true), '/homes/my_wishlist');
	        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        ?>
    </div>
    <header class="clearfix">
        <h2><?php echo __('My Wishlist', true); ?></h2>
        <div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <?php echo $html->link(__('FAQ section.', true), array('controller' => 'questions', 'action' => 'index')); ?></div>
    </header>
    <div class="instructions"> <?php echo $page->getPageContent('wishlist'); ?> </div>

    <nav class="my-wishlist-filter-container clearfix">
        <?php
	        $active  = '';
	        $toggled = '';
	
	        if ( $sort == 'date' ) {
	            if ( $sortOrder == 'asc' ) {
	            	$active = 'active';
	            } else {
	            	$active  = 'active';
	            	$toggled = 'toggled';
	            }
	        }
        ?>
        <div class="date-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Date'); ?></div>
        <?php
	        $active  = '';
	        $toggled = '';
	
	        if ( $sort == 'song' ) {
	            if ( $sortOrder == 'asc' ) {
	            	$active  = 'active';
	            } else {
	            	$active  = 'active';
	            	$toggled = 'toggled';
	            }
	        }
        ?>
        <div class="song-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Song'); ?></div>
        <div class="music-filter-button tab" style="cursor:pointer;"><?php echo __('Music'); ?></div>
        <div class="video-filter-button tab" style="cursor:pointer;"><?php echo __('Video'); ?></div>
        <?php
	        $active  = '';
	        $toggled = '';

	        if ( $sort == 'artist' ) {
	            if ( $sortOrder == 'asc' ) {
	            	$active  = 'active';
	            } else {
	            	$active  = 'active';
	            	$toggled = 'toggled';
	            }
	        }
        ?>
        <div class="artist-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Artist'); ?></div>
        <?php
	        $active  = '';
	        $toggled = '';

	        if ( $sort == 'album' ) {
	            if ( $sortOrder == 'asc' ) {
	            	$active  = 'active';
	            } else {
	            	$active  = 'active';
	            	$toggled = 'toggled';
	            }
	        }
        ?>
        <div class="album-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Album'); ?></div>  
        <div class="download-button filter" ><?php echo __('Download'); ?></div>
    </nav>
    
    <div class="my-wishlist-shadow-container">
        <div class="my-wishlist-scrollable">
            <div class="row-container">
                <?php
                if ( isset( $wishlistResults ) && is_array( $wishlistResults ) && count( $wishlistResults ) > 0 ) {
                	$i = 0;
                	foreach ( $wishlistResults as $wishlistResult ) {
                ?>
                        <div class="row clearfix wishlistsong"  id="wishlistsong-<?= $wishlistResult['wishlists']['id'] . "-" . $wishlistResult['Song']['ProdID'] ?>">
                            <div class="date"><?php echo date('Y-m-d', strtotime($wishlistResult['wishlists']['created'])); ?></div>
                            <div class="small-album-container">                                     
                                <?php                                                                
                                if ( isset( $libraryType ) && $libraryType == 2 && $wishlistResult['Country']['StreamingSalesDate'] <= date('Y-m-d') && $wishlistResult['Country']['StreamingStatus'] == 1 ) {
                                    $filePath = $this->Token->streamingToken( $wishlistResult['Full_Files']['CdnPath'] . "/" . $wishlistResult['Full_Files']['SaveAsName']);

                                    if ( !empty( $filePath ) ) {
                                        $songPath = explode( ':', $filePath );
                                        $wishlistResult['streamUrl'] 	= trim( $songPath[1] );
                                        $wishlistResult['totalseconds'] = $this->Queue->getSeconds( $wishlistResult['Song']['FullLength_Duration'] );
                                    }

									$song_title = $this->Home->explicitContent( $wishlistResult['Song']['Advisory'], $wishlistResult['wishlists']['track_title'] );
                                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'loadSong("' . $wishlistResult['streamUrl'] . '", "' . base64_encode($song_title) . '","' . base64_encode($wishlistResult['wishlists']['artist']) . '",' . $wishlistResult['totalseconds'] . ',"' . $wishlistResult['Song']['ProdID'] . '","' . $wishlistResult['Song']['provider_type'] . '");'));

                                } else {
                                    //do the simple player(this code will update after discussion)
                                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $wishlistResult['Song']['ProdID'] . ', "' . base64_encode($wishlistResult['Song']['provider_type']) . '", "' . $this->webroot . '");'));
                                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                    echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                                }
                                ?>
                            </div>
                            <div class="song-title">
                            	<?php $trackTitle = $this->Home->trimString( $wishlistResult['wishlists']['track_title'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode($wishlistResult['wishlists']['track_title']); ?>" href="javascript:void(0)"> <?=$this->getTextEncode( $trackTitle )?> </a>
                            </div>

                            <div class="album-title">
                            	<?php $album = $this->Home->trimString( $wishlistResult['wishlists']['album'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode(htmlentities($wishlistResult['wishlists']['album'])); ?>" href="/artists/view/<?= base64_encode($wishlistResult['Song']['ArtistText']); ?>/<?= $wishlistResult['Song']['ReferenceID']; ?>/<?= base64_encode($wishlistResult['Song']['provider_type']); ?>"> <?=$this->getTextEncode( $album )?> </a>
                            </div>

                            <div class="artist-name">
                            	<?php $artist = $this->Home->trimString( $wishlistResult['wishlists']['artist'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode(htmlentities($wishlistResult['wishlists']['artist'])); ?>" href="/artists/album/<?= base64_encode($wishlistResult['Song']['ArtistText']); ?>"> <?=$this->getTextEncode( $artist )?> </a>
                            </div>

                            <div class="download">
                                <?php
                                $productInfo = $song->getDownloadData($wishlistResult['wishlists']['ProdID'], $wishlistResult['wishlists']['provider_type']);
                                if ( isset( $libraryDownload ) && isset( $patronDownload ) && $libraryDownload == '1' && $patronDownload == '1') {
                                ?>
                                    <p>
                                        <span class="beforeClick" id="wishlist_song_<?php echo $wishlistResult['wishlists']['ProdID']; ?>">
                                            <?php
                                            if ($wishlistResult['Country']['SalesDate'] <= date('Y-m-d') && ($wishlistResult['Country']['DownloadStatus'] == 1)) {
                                            ?>
                                                <![if !IE]>
                                                <a href='javascript:void(0);' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthersHome("<?php echo $wishlistResult['wishlists']['ProdID']; ?>", "<?php echo $wishlistResult['wishlists']['id']; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $wishlistResult['wishlists']["provider_type"]; ?>");'><?php __('Download'); ?></a>
                                                <![endif]>
                                                <!--[if IE]>
                                                        <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $wishlistResult['wishlists']['ProdID']; ?>", "<?php echo $wishlistResult['wishlists']['id']; ?>" , "<?php echo $wishlistResult['wishlists']["provider_type"]; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download'); ?></a>
                                                <![endif]-->	
                                            <?php } else { ?>
                                                <![if !IE]>
                                                	<?php __('Coming Soon'); ?>
                                                <![endif]>
                                                <!--[if IE]>
                                                	<?php __('Coming Soon'); ?>
                                                <![endif]-->							
                                            <?php } ?>
                                        </span>
                                        <span class="afterClick" id="downloading_<?php echo $wishlistResult['wishlists']['ProdID']; ?>" style="display:none;float:left;"><?php __('Please Wait..'); ?></span>
                                        <span id="wishlist_loader_<?php echo $wishlistResult['wishlists']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                    </p>
		                        <?php } else { ?>
		                                    <p><?php __("Limit Met"); ?></p>
		                        <?php } ?>
                            </div>						
                            <div class="delete-btn songdelete"></div>
                        </div>
                   <?php
                        $i++;
                    }
                } else {
                    echo __("You have no songs in your wishlist.");
                }
                ?>
            </div>
        </div>
    </div>
    <!--(this is the html for the videos) -->
    <div class="my-video-wishlist-shadow-container" style="display:none;">
        <div class="my-video-wishlist-scrollable">
            <div class="row-container">
                <?php
                if ( isset( $wishlistResultsVideos ) && is_array( $wishlistResultsVideos ) && count( $wishlistResultsVideos ) > 0 ) {
                    foreach ($wishlistResultsVideos as $wishlistResultsVideo):
                ?>
                        <div class="row clearfix" id="wishlistvideo-<?php echo $wishlistResultsVideo['WishlistVideo']['id'] . '-' . $wishlistResultsVideo['WishlistVideo']['ProdID'] ?>">
                            <div class="date"><?php echo date("Y-m-d", strtotime($wishlistResultsVideo['WishlistVideo']['created'])); ?></div>
                            <div class="small-album-container">
                                <?php
	                                $videoImage    = $this->Token->artworkToken($wishlistResultsVideo['File']['CdnPath'] . "/" . $wishlistResultsVideo['File']['SourceURL']);
	                                $videoImageUrl = Configure::read('App.Music_Path') . $videoImage;
                                ?>
                                <img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
                            </div>

                            <div class="song-title">
                            	<?php $trackTitle = $this->Home->trimString( $wishlistResultsVideo['WishlistVideo']['track_title'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode($wishlistResultsVideo['WishlistVideo']['track_title']); ?>" href="javascript:void(0)"> <?=$this->getTextEncode($trackTitle)?> </a>
                            </div>

                            <div class="album-title">
                            	<?php $videoTitle = $this->Home->trimString( $wishlistResultsVideo['Video']['Title'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode(htmlentities($wishlistResultsVideo['Video']['Title'])); ?>" href="javascript:void(0)"><?= $this->getTextEncode( $videoTitle ); ?></a>
                            </div>
                            
                            <div class="artist-name">
                            	<?php $artist = $this->Home->trimString( $wishlistResultsVideo['WishlistVideo']['artist'], 14 ); ?>
                            	<a title="<?php echo $this->getTextEncode(htmlentities($wishlistResultsVideo['WishlistVideo']['artist'])); ?>" href="/artists/album/<?= base64_encode($wishlistResultsVideo['Video']['ArtistText']); ?>"> <?=$this->getTextEncode( $artist )?></a>
                            </div>

                            <div class="download">
                                <p>
                                    <?php $productInfo = $mvideo->getDownloadData($wishlistResultsVideo['WishlistVideo']['ProdID'], $wishlistResultsVideo['WishlistVideo']['provider_type']); ?>
                                    <span class="beforeClick" id="download_video_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>">
                                        <?php if ($wishlistResultsVideo['Country']['SalesDate'] <= date('Y-m-d')) { ?>
                                            <![if !IE]>
                                            <a href="javascript:void(0);" title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>" onclick='return wishlistVideoDownloadOthersToken("<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>", "<?php echo $wishlistResultsVideo['WishlistVideo']['id']; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>",  "<?php echo $wishlistResultsVideo['WishlistVideo']["provider_type"]; ?>");'><label class="top-10-download-now-button"><?php __('Download'); ?></label></a>
                                            <![endif]>
                                            <!--[if IE]>
                                                    <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>','<?php echo $wishlistResultsVideo['WishlistVideo']['id']; ?>','<?php echo $wishlistResultsVideo['WishlistVideo']['provider_type']; ?>', '<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download'); ?></a></label>
                                            <![endif]-->
                                        <?php } else { ?>
	                                            <![if !IE]>
	                                            	<?php __('Coming Soon'); ?>
	                                            <![endif]>
	                                            <!--[if IE]>
	                                            	<?php __('Coming Soon'); ?>
	                                            <![endif]-->
                                        <?php } ?>
                                    </span>
                                    <span class="afterClick" id="vdownloading_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>"style="display:none;float:left;"><?php __("Please Wait..."); ?></span>
                                    <span id="vdownload_loader_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                </p>
                            </div>
                            <div class="delete-btn videodelete"></div>
                        </div>
               <?php
                    endforeach;
                } else {
                    echo '<tr><td valign="top"><p>';
                    echo __("You have no videos in your wishlist.");
                    echo '</p></td></tr>';
                }
               ?>
            </div>
        </div>
    </div>
</section>