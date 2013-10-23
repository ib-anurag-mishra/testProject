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
	$html->addCrumb( __('Streaming History', true), '/homes/my_history');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>          
                
                
		<header class="clearfix">
			<h2><?php echo __('Streaming History', true); ?></h2>
			<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <a href="/questions">FAQ section.</a></div>
		</header>
<!--		<div class="instructions">
			<?php //echo $page->getPageContent('history'); ?>			
		</div>-->
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
            ?>   
                        <div class="video-filter-button tab"style="cursor:pointer;"><?php echo __('Queue'); ?></div>
			<div class="music-filter-button tab"style="cursor:pointer;"><?php echo __('Music'); ?></div>
			
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
			<div class="artist-filter-button filter"style="cursor:pointer;"><?php echo __('Streaming Time'); ?></div>
			
		</nav>
		<div class="recent-downloads-shadow-container" style="display:none">
			<div class="recent-downloads-scrollable">
				<div class="row-container">
				<?php
                if(count($streamingResults) != 0)
                {
                    $i = 1;
                    foreach($streamingResults as $key => $streamingResults):
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($streamingResults['StreamingHistory']['createdOn'])); ?></div>
					<div class="small-album-container">
						
						<!-- <a class="preview" href="#"></a> -->
                        
                                                    
                       <?php
                       
                        if( $this->Session->read('library_type') == 2 && $streamingResults['Country']['StreamingSalesDate'] <= date('Y-m-d') && $streamingResults['Country']['StreamingStatus'] == 1){
                                //do the streaming work
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$streamingResults['Download']['ProdID'].', "'.base64_encode($streamingResults['Download']['provider_type']).'", "'.$this->webroot.'");')); 
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 

                        }else if($this->Session->read('library_type') == 1){
                                //do the simple player(this code will be update after discussion)
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$streamingResults['Download']['ProdID'].', "'.base64_encode($streamingResults['Download']['provider_type']).'", "'.$this->webroot.'");')); 
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                        }    
                        
                        
                        ?>
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($streamingResults['Download']['track_title']) >= 19) {
							echo '<span title="'.htmlentities($streamingResults['Download']['track_title']).'">' .substr($streamingResults['Download']['track_title'], 0, 19) . '...</span>';							
						} else {
							echo $streamingResults['Download']['track_title']; 
					 	}
					?>
                    <?php if('T' == $streamingResults['Song']['Advisory']) { ?> <span style="color: red;display: inline;font-size: 10px;"> (Explicit)</span> <?php } ?></div>
					<!-- <a class="add-to-wishlist-button" href="#"></a> -->
					<div class="album-title"><a href="/artists/view/<?=base64_encode($streamingResults['Song']['ArtistText']);?>/<?= $streamingResults['Song']['ReferenceID']; ?>/<?= base64_encode($streamingResults['Song']['provider_type']);?>">
                                             <?php 
						if (strlen($streamingResults['Song']['Title']) >= 19) {
							echo '<span title="'.htmlentities($streamingResults['Song']['Title']).'">' .substr($streamingResults['Song']['Title'], 0, 19) . '...</span>';							
						} else {
							echo $streamingResults['Song']['Title']; 
					 	}
					?>
                                            
                                            </div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($streamingResults['Song']['ArtistText']); ?>"><?php
						if (strlen($streamingResults['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($streamingResults['Download']['artist']).'">' .substr($streamingResults['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $streamingResults['Download']['artist'];
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
                                            echo $this->Queue->getQueuesList($this->Session->read('patron'),$streamingResults["Song"]["ProdID"],$streamingResults["Song"]["provider_type"],$streamingResults["Album"]["ProdID"],$streamingResults["Album"]["provider_type"]); ?>
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
                            $productInfo = $song->getDownloadData($streamingResults['Download']['ProdID'],$streamingResults['Download']['provider_type']);
                            $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
                            ?>
                                    <span class="beforeClick" id="download_song_<?php echo $streamingResults['Download']['ProdID']; ?>">
                                            <![if !IE]>
                                                    <a href='#' onclick='return historyDownloadOthers("<?php echo $streamingResults['Download']['ProdID']; ?>","<?php echo $streamingResults['Download']['library_id']; ?>","<?php echo $streamingResults['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download');?></a>
                                            <![endif]>
                                            <!--[if IE]>
                                                    <a onclick='historyDownload("<?php echo $streamingResults['Download']['ProdID']; ?>","<?php echo $streamingResults['Download']['id']; ?>","<?php echo $streamingResults['Download']['patron_id']; ?>");' href="<?php echo trim($finalSongUrl);?>"><?php __('Download');?></a>
                                            <![endif]-->
                                    </span>
                                    <span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
                                    <span id="download_loader_<?php echo $streamingResults['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
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
					?><?php if('T' == $videoDownloadResult['Video']['Advisory']) { ?> <span style="color: red;display: inline;font-size: 10px;"> (Explicit)</span> <?php } ?>
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
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded video from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>


	</section>