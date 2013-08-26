<?php
/*
	 File Name : my_history.ctp 
	 File Description : View page for download history page
	 Author : m68interactive
 */

function ieversion()
{
	  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
	  if(!isset($reg[1])) {
		return -1;
	  } else {
		return floatval($reg[1]);
	  }
}
$ieVersion =  ieversion();
?>

<!-- new HTML -->
<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?php echo $sort; ?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?php echo $sortOrder; ?>" />
</form>
<section class="recent-downloads-page">
		
		
                
<div class="breadcrumbs">
<?php
	$html->addCrumb( __('Recent Downloads', true), '/homes/my_history');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>          
                
                
		<header class="clearfix">
			<h2><?php echo __('Downloads', true); ?></h2>
			<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <a href="/questions">FAQ section.</a></div>
		</header>
		<div class="instructions">
			<?php echo $page->getPageContent('history'); ?>			
		</div>
		<nav class="recent-downloads-filter-container clearfix">
			<?php 
            if($sort == 'date'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="date-filter-button filter active" style="cursor:pointer;"><?php echo __('Date'); ?></div>
                <?php } else { ?>
                    <div class="date-filter-button filter active toggled"><?php echo __('Date'); ?></div>
                <?php } 
            } else {
                ?>
                <div class="date-filter-button filter "><?php echo __('Date'); ?></div>
            <?php
            }
            if($sort == 'song'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="song-filter-button filter active"style="cursor:pointer;"><?php echo __('Song'); ?></div>
                <?php } else { ?>
                    <div class="song-filter-button filter active toggled"style="cursor:pointer;"><?php echo __('Song'); ?></div>
                <?php } 
            } else {
                ?>
			<div class="song-filter-button filter"style="cursor:pointer;"><?php echo __('Song'); ?></div>
            <?php
            }
            ?>    
                    
			<div class="music-filter-button tab active"style="cursor:pointer;"><?php echo __('Music'); ?></div>
			<div class="video-filter-button tab"style="cursor:pointer;"><?php echo __('Videos'); ?></div>
			<?php
            if($sort == 'artist'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="artist-filter-button filter active"style="cursor:pointer;"><?php echo __('Artists'); ?></div>
                <?php } else { ?>
                    <div class="artist-filter-button filter active toggled"style="cursor:pointer;"><?php echo __('Artists'); ?></div>
                <?php } 
            } else {
                ?>
			<div class="artist-filter-button filter"style="cursor:pointer;"><?php echo __('Artists'); ?></div>
            <?php
            }
            if($sort == 'album'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="album-filter-button filter active"style="cursor:pointer;"><?php echo __('Album'); ?></div>
                <?php } else { ?>
                    <div class="album-filter-button filter active toggled"style="cursor:pointer;"><?php echo __('Album'); ?></div>
                <?php } 
            } else {
                ?>
			<div class="album-filter-button filter"style="cursor:pointer;"><?php echo __('Album'); ?></div>
            <?php
            }
            ?>  
			<div class="download-button filter"style="cursor:pointer;"><?php echo __('Download'); ?></div>
			
		</nav>
		<div class="recent-downloads-shadow-container" style="display:none">
			<div class="recent-downloads-scrollable">
				<div class="row-container">
				<?php
                if(count($downloadResults) != 0)
                {
                    $i = 1;
                    foreach($downloadResults as $key => $downloadResult):
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($downloadResult['Download']['created'])); ?></div>
					<div class="small-album-container">
						
						<!-- <a class="preview" href="#"></a> -->
                        <?php
                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$downloadResult['Download']['ProdID'].', "'.base64_encode($downloadResult['Download']['provider_type']).'", "'.$this->webroot.'");')); 
                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                        ?>
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($downloadResult['Download']['track_title']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['track_title']).'">' .substr($downloadResult['Download']['track_title'], 0, 19) . '...</span>';							
						} else {
							echo $downloadResult['Download']['track_title']; 
					 	}
					?>
                    </div>
					<!-- <a class="add-to-wishlist-button" href="#"></a> -->
					<div class="album-title"><a href="/artists/view/<?=base64_encode($downloadResult['Song']['ArtistText']);?>/<?= $downloadResult['Song']['ReferenceID']; ?>/<?= base64_encode($downloadResult['Song']['provider_type']);?>">
                                             <?php 
						if (strlen($downloadResult['Song']['Title']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Song']['Title']).'">' .substr($downloadResult['Song']['Title'], 0, 19) . '...</span>';							
						} else {
							echo $downloadResult['Song']['Title']; 
					 	}
					?>
                                            
                                            </div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($downloadResult['Song']['ArtistText']); ?>"><?php
						if (strlen($downloadResult['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['artist']).'">' .substr($downloadResult['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $downloadResult['Download']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<!-- <div class="wishlist-popover">
						<!--	
						<a class="remove-song" href="#">Remove Song</a>
						<a class="make-cover-art" href="#">Make Cover Art</a>
						*/
                                        <?php
                                        if($this->Session->read('library_type') == '2'){
                                            echo $this->Queue->getQueuesList($this->Session->read('patron'),$downloadResult["Song"]["ProdID"],$downloadResult["Song"]["provider_type"],$downloadResult["Album"]["ProdID"],$downloadResult["Album"]["provider_type"]); ?>
                                            <a class="add-to-playlist" href="#">Add To Queue</a>
                                            <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                                        <?php } else {
                                                    echo $this->Queue->getSocialNetworkinglinksMarkup(); 
                                              }
                                        ?>
					</div> -->
					<div class="download">
                   
                        <p>
                            <?php
                            $productInfo = $song->getDownloadData($downloadResult['Download']['ProdID'],$downloadResult['Download']['provider_type']);
                            $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
                            ?>
                                    <span class="beforeClick" id="download_song_<?php echo $downloadResult['Download']['ProdID']; ?>">
                                            <?php if($ieVersion > 8 || $ieVersion < 0){ ?>
                                                    <a href='#' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download');?></a>
                                            <?php } else {?>
                                                 <a href='#' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download');?></a>

                                            <?php } ?>
                                    </span>
                                    <span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
                                    <span id="download_loader_<?php echo $downloadResult['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
		       </p>
                    </div>
				</div>
				<?php
                    $i++;
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded songs from this week or last week."); ?><?php echo '</p></td></tr>';
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
                if(count($videoDownloadResults) != 0)
                {
                    //$i = 1;
                    foreach($videoDownloadResults as $key => $videoDownloadResult):
                    /*$class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }*/
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($videoDownloadResult['Videodownload']['created'])); ?></div>
					<div class="small-album-container">
						<?php
                        $videoImage = shell_exec('perl files/tokengen_artwork ' . $videoDownloadResult['File']['CdnPath']."/".$videoDownloadResult['File']['SourceURL']);
                        $videoImageUrl = Configure::read('App.Music_Path').$videoImage;
                        ?>
                        <img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
						<!-- <a class="preview" href="#"></a> -->
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($videoDownloadResult['Videodownload']['track_title']) >= 22) {
							echo '<span title="'.htmlentities($videoDownloadResult['Videodownload']['track_title']).'">' .substr($videoDownloadResult['Videodownload']['track_title'], 0, 22) . '...</span>';							
						} else {
							echo $videoDownloadResult['Videodownload']['track_title']; 
					 	}
					?>
                                        </div>
					<!--<a class="add-to-wishlist-button" href="#"></a>-->
					<div class="album-title"><a href="#">
                                             <?php 
						if (strlen($videoDownloadResult['Video']['Title']) >= 22) {
							echo '<span title="'.htmlentities($videoDownloadResult['Video']['Title']).'">' .substr($videoDownloadResult['Video']['Title'], 0, 22) . '...</span>';							
						} else {
							echo $videoDownloadResult['Video']['Title']; 
					 	}
					?>
                                            </a></div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($videoDownloadResult['Video']['ArtistText']); ?>">
                    <?php
						if (strlen($videoDownloadResult['Videodownload']['artist']) >= 19) {
							echo '<span title="'.htmlentities($videoDownloadResult['Videodownload']['artist']).'">' .substr($videoDownloadResult['Videodownload']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $videoDownloadResult['Videodownload']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download">
                       
                            
                        <p>
                        <?php
                        
                            $productInfo = $mvideo->getDownloadData($videoDownloadResult['Videodownload']['ProdID'],$videoDownloadResult['Videodownload']['provider_type']);
                            $videoUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                            $finalVideoUrl = Configure::read('App.Music_Path').$videoUrl;
                            $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl)/3));
                            ?>
                            <span class="beforeClick" id="download_song_<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>">
                                                            <?php if($ieVersion > 8 || $ieVersion < 0){ ?>
                                                                    <a href='javascript:void(0)' onclick='return historyDownloadVideoOthers("<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>","<?php echo $videoDownloadResult['Videodownload']['library_id']; ?>","<?php echo $videoDownloadResult['Videodownload']['patron_id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>");'><?php __('Download');?></a>
                                                            <?php } else {?>
                                                                <a href='javascript:void(0)' onclick='return historyDownloadVideoOthers("<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>","<?php echo $videoDownloadResult['Videodownload']['library_id']; ?>","<?php echo $videoDownloadResult['Videodownload']['patron_id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>");'><?php __('Download');?></a>

                                                            <?php } ?>
							</span>
							<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
							<span id="download_loader_<?php echo $videoDownloadResult['Videodownload']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                       </p>
                    </div>
				</div>
				<?php
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded video from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>


	</section>