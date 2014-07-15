<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?=$sort?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?=$sortOrder?>" />
</form>
<section class="recent-downloads-page">
	<div class="breadcrumbs">
		<?php
			$html->addCrumb( __('Recent Downloads', true), '/homes/my_history');
			echo $html->getCrumbs(' > ', __('Home', true), '/homes');
		?>
	</div>
	<header class="clearfix">
		<h2><?php echo __('Downloads', true); ?></h2>
		<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <a href="/questions">FAQ section.</a></div>
	</header>
	<div class="instructions"> <?php echo $page->getPageContent('history'); ?> </div>
	
	<nav class="recent-downloads-filter-container clearfix">
		<?php
			$active  = '';
			$toggled = '';

            if( $sort == 'date' ) {
                if( $sortOrder == 'asc' ) {
                	$active  = 'active';
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
	
				if( $sort == 'song' ) {
					if( $sortOrder == 'asc' ) {
	                	$active  = 'active';
					} else {
		               	$active  = 'active';
		               	$toggled = 'toggled';
	               }
	            }
            ?>    
            <div class="song-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Song'); ?></div>       

			<div class="music-filter-button tab active"style="cursor:pointer;"><?php echo __('Music'); ?></div>
			<div class="video-filter-button tab"style="cursor:pointer;"><?php echo __('Videos'); ?></div>

			<?php
				$active  = '';
				$toggled = '';

	            if( $sort == 'artist' ) {
					if( $sortOrder == 'asc' ) {
	                	$active  = 'active';
	                } else {
		                $active  = 'active';
		                $toggled = 'toggled';
	                }
				}
            ?>

            <div class="artist-filter-button filter <?=$active?> <?=$toggled?>" style="cursor:pointer;"><?php echo __('Artists'); ?></div>
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
			<div class="download-button filter"><?php echo __('Download'); ?></div>
		</nav>
	
	<div class="recent-downloads-shadow-container" style="display:none">
		<div class="recent-downloads-scrollable">
			<div class="row-container">
				<?php
                if( isset( $downloadResults ) && is_array( $downloadResults ) && count($downloadResults) > 0 ) {
                    $i = 1;
                    foreach($downloadResults as $key => $downloadResult): 
                ?>
						<div class="row clearfix">
							<div class="date"><?php echo date("Y-m-d",strtotime($downloadResult['Download']['created'])); ?></div>
							<div class="small-album-container">
                       			<?php
                        			if( isset( $libraryType ) && $libraryType == 2 && $downloadResult['Country']['StreamingSalesDate'] <= date('Y-m-d') && $downloadResult['Country']['StreamingStatus'] == 1) {

		                                $filePath = $this->Token->streamingToken($downloadResult['Full_Files']['CdnPath']."/".$downloadResult['Full_Files']['SaveAsName']);

		                                if( !empty( $filePath ) ) {

		                                    $songPath = explode(':',$filePath);
		                                    $downloadResult['streamUrl']	= trim( $songPath[1] );
		                                    $downloadResult['totalseconds'] = $this->Queue->getSeconds($downloadResult['Song']['FullLength_Duration']); 
		                                 }

		                                 $song_title = $this->Home->explicitContent( $downloadResult['Song']['Advisory'], $downloadResult['Song']['SongTitle'] );
		                                 echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'loadSong("'.$downloadResult['streamUrl'].'", "'.base64_encode($song_title).'","'.base64_encode($downloadResult['Song']['ArtistText']).'",'.$downloadResult['totalseconds'].',"'.$downloadResult['Song']['ProdID'].'","'.$downloadResult['Song']['provider_type'].'");'));

			                        } else if( isset( $libraryType ) && $libraryType == 1 ) {

			                        	//do the simple player(this code will be update after discussion)
			                            echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$downloadResult['Download']['ProdID'].', "'.base64_encode($downloadResult['Download']['provider_type']).'", "'.$this->webroot.'");')); 
			                            echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
			                            echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
			                        }
                        		?>
							</div>
							<div class="song-title">
								<?php 
									$trackTitle = $this->Home->trimString( $downloadResult['Download']['track_title'], 18 ); 
									$trackTitle = $this->getTextEncode( $trackTitle );
									$trackTitle = $this->Home->explicitContent( $downloadResult['Song']['Advisory'], $trackTitle, true );
								?>
                            	<a title="<?php echo $this->getTextEncode($downloadResult['Download']['track_title']); ?>" href="javascript:void(0)"><?=$trackTitle?> </a>
							</div>

							<div class="album-title">
								<?php $songTitle = $this->Home->trimString( $downloadResult['Song']['Title'], 18 ); ?>
                                 <a title="<?php echo $this->getTextEncode($downloadResult['Song']['Title'] ); ?>" href="/artists/view/<?=base64_encode($downloadResult['Song']['ArtistText']);?>/<?= $downloadResult['Song']['ReferenceID']; ?>/<?= base64_encode($downloadResult['Song']['provider_type']);?>"> <?=$this->getTextEncode( $songTitle )?> </a>
							</div>

							<div class="artist-name">
								<?php $artist = $this->Home->trimString( $downloadResult['Download']['artist'], 18 ); ?>
	                            <a title="<?php echo $this->getTextEncode($downloadResult['Download']['artist']); ?>" href="/artists/album/<?= base64_encode($downloadResult['Song']['ArtistText']); ?>"> <?=$this->getTextEncode( $artist )?> </a>
                             </div>

							<div class="download">           
                        		<p>
                            		<?php $productInfo = $song->getDownloadData($downloadResult['Download']['ProdID'],$downloadResult['Download']['provider_type']); ?>
                                    <span class="beforeClick" id="download_song_<?php echo $downloadResult['Download']['ProdID']; ?>">
                                            <![if !IE]>
                                                    <a href='javascript:void(0);' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath'];?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName'];?>");'><?php __('Download');?></a>
                                            <![endif]>
                                            <!--[if IE]>
                                                    <a onclick='historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo $productInfo[0]['Full_Files']['CdnPath'];?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName'];?>");' href="javascript:void(0);"><?php __('Download');?></a>
                                            <![endif]-->
                                    </span>
                                    <span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
                                    <span id="download_loader_<?php echo $downloadResult['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
								</p>
							</div>
						</div>
			<?php
                    	$i++;
					endforeach;
				} else {
                echo '<tr><td valign="top"><p>';
                echo __("No downloaded songs from this week or last week.");
                echo '</p></td></tr>';
            	}
			?>
			</div>
		</div>
	</div>

	<!-- (this is the html for the videos) -->
	<div class="recent-video-downloads-shadow-container" style="display:none">
		<div class="recent-video-downloads-scrollable">
			<div class="row-container">
				<?php
                if( isset( $videoDownloadResults ) && is_array( $videoDownloadResults ) && count($videoDownloadResults) > 0 ) {
                    foreach($videoDownloadResults as $key => $videoDownloadResult):
                ?>
						<div class="row clearfix">
							<div class="date"><?php echo date("Y-m-d",strtotime($videoDownloadResult['Videodownload']['created'])); ?></div>
							<div class="small-album-container">
								<?php                        
			                        $videoImage = $this->Token->artworkToken($videoDownloadResult['File']['CdnPath']."/".$videoDownloadResult['File']['SourceURL']);
			                        $videoImageUrl = Configure::read('App.Music_Path').$videoImage;
		                        ?>
                        		<img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
							</div>

							<div class="song-title">
								<?php 
									$trackTitle = $this->Home->trimString( $videoDownloadResult['Videodownload']['track_title'], 21 ); 
									$trackTitle = $this->getTextEncode( $trackTitle );
									$trackTitle = $this->Home->explicitContent( $videoDownloadResult['Video']['Advisory'], $trackTitle, true );
								?>
								<a title="<?php echo $this->getTextEncode($videoDownloadResult['Videodownload']['track_title']); ?>" href="javascript:void(0)"><?=$trackTitle?> </a>
							</div>

							<div class="album-title">
								<?php $videoTitle = $this->Home->trimString( $videoDownloadResult['Video']['Title'], 21 ); ?>
								<a title="<?php echo $this->getTextEncode($videoDownloadResult['Video']['Title']); ?>" href="javascript:void(0)"><?=$this->getTextEncode( $videoTitle )?></a>
							</div>

							<div class="artist-name">
								<?php $artist = $this->Home->trimString( $videoDownloadResult['Videodownload']['artist'], 18 ); ?>
								<a title="<?php echo $this->getTextEncode($videoDownloadResult['Videodownload']['artist']); ?>" href="/artists/album/<?= base64_encode($videoDownloadResult['Video']['ArtistText']); ?>"><?=$this->getTextEncode( $artist )?></a>
							</div>
					
							<div class="wishlist-popover">
								<div class="share clearfix">
									<p>Share via</p>
									<a class="facebook" href="javascript:void(0);"></a>
									<a class="twitter" href="javascript:void(0);"></a>
								</div>
							</div>
							<div class="download">
                        		<p>
			                        <?php
			                            $productInfo = $mvideo->getDownloadData($videoDownloadResult['Videodownload']['ProdID'],$videoDownloadResult['Videodownload']['provider_type']);                            
			                            $videoUrl = $this->Token->regularToken($productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);
			                            $finalVideoUrl = Configure::read('App.Music_Path').$videoUrl;
			                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl)/3));
									?>
                            		<span class="beforeClick" id="download_song_<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>">
                                    	<![if !IE]>
                                        	<a href='javascript:void(0)' onclick='return historyDownloadVideoOthers("<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>","<?php echo $videoDownloadResult['Videodownload']['library_id']; ?>","<?php echo $videoDownloadResult['Videodownload']['patron_id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>");'><?php __('Download');?></a>
                                        <![endif]>
                                        <!--[if IE]>
                                        	<a onclick='historyDownloadVideo("<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>","<?php echo $videoDownloadResult['Videodownload']['library_id']; ?>","<?php echo $videoDownloadResult['Videodownload']['patron_id']; ?>");' href="<?php echo trim($finalVideoUrl);?>"><?php __('Download');?></a>
                                        <![endif]-->
									</span>
									<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
									<span id="download_loader_<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
		                    	</p>
                    		</div>
						</div>
				<?php
                    endforeach;
				} else {
	                echo '<tr><td valign="top"><p>';
	                echo __("No downloaded video from this week or last week.");
	                echo '</p></td></tr>';
                }
				?>
			</div>
		</div>
	</div>
</section>