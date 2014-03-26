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
	$html->addCrumb( __('Streaming History', true), '/homes/my_streaming_history');
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
?>
</div>          
                
                
		<header class="clearfix">
			<h2><?php echo __('Streaming History', true); ?></h2>
			<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <a href="/questions">FAQ section.</a></div>
		</header>
		<nav class="recent-downloads-filter-container clearfix">
			<?php 
            if($sort == 'date'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="date-filter-button filter active" style="cursor:pointer;width:90px;"><?php echo __('Date'); ?></div>
                <?php } else { ?>
                    <div class="date-filter-button filter active toggled" style="cursor:pointer;width:90px;"><?php echo __('Date'); ?></div>
                <?php } 
            } else {
                ?>
                <div class="date-filter-button filter " style="cursor:pointer;width:90px;"><?php echo __('Date'); ?></div>
            <?php
            }
            ?>   
                        <div class="song-filter-button" style="cursor:pointer;width:190px;"><?php echo __('Music'); ?></div>
			<div class="song-filter-button" style="cursor:pointer;width:130px;"><?php echo __('Playlist'); ?></div> 
			
			<?php
            if($sort == 'artist'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="artist-filter-button filter active"style="cursor:pointer;width:141px;"><?php echo __('Artists'); ?></div>
                <?php } else { ?>
                    <div class="artist-filter-button filter active toggled"style="cursor:pointer;width:141px;"><?php echo __('Artists'); ?></div>
                <?php } 
            } else {
                ?>
			<div class="artist-filter-button filter"style="cursor:pointer;width:141px;"><?php echo __('Artists'); ?></div>
            <?php
            }
            if($sort == 'album'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="album-filter-button filter active"style="cursor:pointer;width:122px;"><?php echo __('Album'); ?></div>
                <?php } else { ?>
                    <div class="album-filter-button filter active toggled"style="cursor:pointer;width:122px;"><?php echo __('Album'); ?></div>
                <?php } 
            } else {
                ?>
			<div class="album-filter-button filter"style="cursor:pointer;width:122px;"><?php echo __('Album'); ?></div>
            <?php
            }
            ?>  
			<div class="artist-filter-button"style="cursor:pointer;"><?php echo __('Streaming Time'); ?> (in sec.)</div>
			
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
                                                    
                       <?php
                       
                        if( $this->Session->read('library_type') == 2 && $streamingArr['Country']['StreamingSalesDate'] <= date('Y-m-d') && $streamingArr['Country']['StreamingStatus'] == 1){
                            
                                $filePath = shell_exec('perl files/tokengen_streaming '. $streamingArr['Full_Files']['CdnPath']."/".$streamingArr['Full_Files']['SaveAsName']);

                                if(!empty($filePath))
                                {
                                   $songPath = explode(':',$filePath);
                                   $streamUrl =  trim($songPath[1]);
                                   $streamingArr['streamUrl'] = $streamUrl;
                                   $streamingArr['totalseconds']  = $this->Queue->getSeconds($streamingArr['Song']['FullLength_Duration']); 
                                }
                                
                                if ('T' == $streamingArr['Song']['Advisory'])
                                {
                                       $song_title =   $streamingArr['Song']['SongTitle'].'(Explicit)';
                                }
                                else 
                                {
                                       $song_title =   $streamingArr['Song']['SongTitle'];
                                }
                            
                            
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block; left: 91px;", "id" => "play_audio".$i, "onClick" => 'loadSong("'.$streamingArr['streamUrl'].'", "'.base64_encode($song_title).'","'.base64_encode($streamingArr['Song']['ArtistText']).'",'.$streamingArr['totalseconds'].',"'.$streamingArr['Song']['ProdID'].'","'.$streamingArr['Song']['provider_type'].'");')); 
                        }else if($this->Session->read('library_type') == 1){
                                //do the simple player(this code will be update after discussion)
                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block; left: 91px;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$streamingArr['Download']['ProdID'].', "'.base64_encode($streamingArr['Download']['provider_type']).'", "'.$this->webroot.'");')); 
                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                        }    
                        
                        
                        
                        ?>
					</div>
					<div class="song-title" style="left: 115px;">
                                    <?php 
                                                  if(strlen($streamingArr['Song']['SongTitle']) >= 16) {
							echo '<span title="'.htmlentities($streamingArr['Song']['SongTitle']).'">' .$this->getTextEncode(substr($streamingArr['Song']['SongTitle'], 0, 16)) . '...</span>';							
						} else {
							echo $this->getTextEncode($streamingArr['Song']['SongTitle']); 
					 	}
                    
					?>
                                    <?php if('T' == $streamingArr['Song']['Advisory']) { ?> <span style="color: red;display: inline;font-size: 10px;"> (Explicit)</span> <?php } ?>
                                        </div>
                                        
                                        <div style="width: 128px; position: absolute; left: 292px; top: 25px; font-size: 12px;  color: rgba(0, 0, 0, 0.7); text-decoration: none;">
                                            <a href="/queuelistdetails/queue_details/<?php echo $streamingArr['QueueList']['queue_id']; ?>/<?php echo $streamingArr['QueueList']['queue_type']; ?>/<?php echo base64_encode($streamingArr['QueueList']['queue_name']); ?>">
                                     <?php 
                                               
                                                
						if (strlen($streamingArr['QueueList']['queue_name']) >= 16) {
							echo '<span title="'.htmlentities($streamingArr['QueueList']['queue_name']).'">' .$this->getTextEncode(substr($streamingArr['QueueList']['queue_name'], 0, 16)) . '...</span>';							
						} else {
							echo $this->getTextEncode($streamingArr['QueueList']['queue_name']); 
					 	}
                    
					?>
                                            </a>
                                        </div>
                                        
					<div class="album-title" style="left:561px;"><a href="/artists/view/<?= base64_encode($streamingArr['Song']['ArtistText']); ?>/<?= $streamingArr['Song']['ReferenceID']; ?>/<?= base64_encode($streamingArr['Song']['provider_type']); ?>">
                                             <?php 
						if (strlen($streamingArr['Album']['AlbumTitle']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['Album']['SongTitle']).'">' .$this->getTextEncode(substr($streamingArr['Album']['AlbumTitle'], 0, 19)) . '...</span>';							
						} else {
							echo $this->getTextEncode($streamingArr['Album']['AlbumTitle']); 
					 	}
					?>
                                            </a>
                                            </div>
					<div class="artist-name" style="left:422px;"><a href="/artists/album/<?= base64_encode($streamingArr['Song']['ArtistText']); ?>"><?php
						if (strlen($streamingArr['Song']['ArtistText']) >= 19) {
							echo '<span title="'.htmlentities($streamingArr['Song']['ArtistText']).'">' .$this->getTextEncode(substr($streamingArr['Song']['ArtistText'], 0, 19)) . '...</span>';							
						} else {
							$ArtistName = $streamingArr['Song']['ArtistText'];
							echo $this->getTextEncode($ArtistName);
						}
						
					?></a></div>
					
					<div class="download"><?php
						 echo $streamingArr[0]['StreamingTime'];						
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