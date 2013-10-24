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
                        <div class="song-filter-button filter active"style="cursor:pointer;"><?php echo __('Music'); ?></div>
			<div class="music-filter-button filter active"style="cursor:pointer;"><?php echo __('Queue'); ?></div> 
			
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
			<div class="download-button filter"style="cursor:pointer;"><?php echo __('Streaming Time'); ?></div>
			
		</nav>
		<div class="recent-downloads-shadow-container" style="display:none">
			<div class="recent-downloads-scrollable">
				<div class="row-container">
				<?php 
                if(count($streamingData) != 0)
                {
                    $i = 1;
                    foreach($streamingData as $key => $streamingArr):
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($streamingArr['StreamingHistory']['createdOn'])); ?></div>
					<div class="small-album-container">
						
						<!-- <a class="preview" href="#"></a> -->
                        
                                                    
                       <?php
                       
                        if( $this->Session->read('library_type') == 2 && $streamingArr['Country']['StreamingSalesDate'] <= date('Y-m-d') && $streamingArr['Country']['StreamingStatus'] == 1){
                                //do the streaming work
                                                            
//                                $filePath = shell_exec('perl files/tokengen_streaming '. $streamingArr['File']['CdnPath']."/".$streamingArr['File']['SourceURL']);
//
//                                if(!empty($filePath))
//                                 {
//                                    $songPath = explode(':',$filePath);
//                                    $streamUrl =  trim($songPath[1]);
//                                    $albumSong['streamUrl'] = $streamUrl;
//                                    $albumSong['totalseconds']  = $this->Queue->getSeconds($albumSong['Song']['FullLength_Duration']); 
//                                 } 
                                
                            
                            
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$streamingArr['StreamingHistory']['ProdID'].', "'.base64_encode($streamingArr['StreamingHistory']['provider_type']).'", "'.$this->webroot.'");')); 
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 

                        }else if($this->Session->read('library_type') == 1){
                                //do the simple player(this code will be update after discussion)
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$streamingArr['Download']['ProdID'].', "'.base64_encode($streamingArr['Download']['provider_type']).'", "'.$this->webroot.'");')); 
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                        }    
                        
                        
                        
                        ?>
					</div>
					<div class="song-title">
                    <?php 
						/*if (strlen($streamingArr['QueueList']['queue_name']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['QueueList']['queue_name']).'">' .substr($streamingArr['QueueList']['queue_name'], 0, 19) . '...</span>';							
						} else {
							echo $streamingArr['QueueList']['queue_name']; 
					 	}*/
                    
                                                  if (strlen($streamingArr['Song']['SongTitle']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['Song']['SongTitle']).'">' .substr($streamingArr['Song']['SongTitle'], 0, 19) . '...</span>';							
						} else {
							echo $streamingArr['Song']['SongTitle']; 
					 	}
                    
					?>
                    <?php if('T' == $streamingArr['Song']['Advisory']) { ?> <span style="color: red;display: inline;font-size: 10px;"> (Explicit)</span> <?php } ?>
                                        </div>
                                        
                                        <div style="width: 128px; position: absolute; left: 259px; top: 25px; font-size: 12px;  color: #000;">
                                     <?php 
                                               
                                     
						if (strlen($streamingArr['QueueList']['queue_name']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['QueueList']['queue_name']).'">' .substr($streamingArr['QueueList']['queue_name'], 0, 19) . '...</span>';							
						} else {
							echo $streamingArr['QueueList']['queue_name']; 
					 	}
                    
					?>
                    
                                        </div>
                                        
					<!-- <a class="add-to-wishlist-button" href="#"></a> -->
					<div class="album-title"><a href="/artists/view/<?=base64_encode($streamingArr['Album']['AlbumTitle']);?>/<?= $streamingArr['Album']['AlbumTitle']; ?>/<?= base64_encode($streamingArr['Album']['AlbumTitle']);?>">
                                             <?php 
						if (strlen($streamingArr['Album']['AlbumTitle']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['Album']['SongTitle']).'">' .substr($streamingArr['Album']['AlbumTitle'], 0, 19) . '...</span>';							
						} else {
							echo $streamingArr['Album']['AlbumTitle']; 
					 	}
					?>
                                            
                                            </div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($streamingArr['Song']['ArtistText']); ?>"><?php
						if (strlen($streamingArr['Song']['ArtistText']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['Song']['ArtistText']).'">' .substr($streamingArr['Song']['ArtistText'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $streamingArr['Song']['ArtistText'];
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
                                            echo $this->Queue->getQueuesList($this->Session->read('patron'),$streamingArr["Song"]["ProdID"],$streamingArr["Song"]["provider_type"],$streamingArr["Album"]["ProdID"],$streamingArr["Album"]["provider_type"]); ?>
                                            <a class="add-to-playlist" href="#">Add To Queue</a>
                                            <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
                                        <?php } else {
                                                    echo $this->Queue->getSocialNetworkinglinksMarkup(); 
                                              }
                                        ?>
					</div> -->
					<div class="download"><?php
						 echo $streamingArr[0]['SUM(`StreamingHistory`.`consumed_time`)'];						
					?></div>
				</div>
				<?php
                    $i++;
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No Streaming History from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>
		<!-- (this is the html for the videos) -->
		


	</section>