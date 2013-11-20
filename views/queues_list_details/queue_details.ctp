<section class="queue-detail-page">
            <?php if(!empty($queue_list_array)){ ?>
		<div class="breadcrumbs">
                    <?php
                            $html->addCrumb( __($queue_list_array[0]['QueueList']['queue_name'], true), '/queuelistdetails/queue_details/'.$queue_id);
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                    ?>
                </div>
		<div class="col-container clearfix">
			<div class="col-1">
				<img src="/app/webroot/img/queue-details/generic-album-cover.jpg" width="155" height="155" />
			</div>
			<div class="col-2">
				<div class="queue-name">
					<?php echo $queue_list_array[0]['QueueList']['queue_name'];?>
				</div>
				<div class="queue-length">
					<?php echo $queue_songs_count; ?> Songs
				</div>
				<div class="queue-duration">
					Duration: <?php echo $total_time; ?>
				</div>
                                 <input type="hidden" id="hid_Plid" value="<?php echo $queue_id;?>" />
                                <input type="hidden" id="hid_playlist_name" value="<?php echo $queue_list_array[0]["QueueList"]["queue_name"];?>" />
                                <input type="hidden" id="hid_description" value="<?php echo $queue_list_array[0]["QueueList"]["description"];?>" />
			</div>
			<div class="col-3">
				<div class="faq-link"><?php echo __('Need help? Visit our', true); ?>  <a href="javascript:void(0);">FAQ section</a>.</div>
				<div class="button-container" >
					<div class="play-queue-btn" ></div>
					<?php if($default_queue != 1){ ?>
                                            <div class="gear-icon no-ajaxy"></div>
                                        <?php } ?>
				</div>
				<div class="queue-options">
                                    <?php                                                                        
                                                if(($this->Session->read("Auth.User.type_id") == 1 && $queueType=='Default') || ($this->Session->read("Auth.User.type_id") ==1 &&  $queueType=='Custom') ||  ($this->Session->read("Auth.User.type_id") !=1 &&  $queueType=='Custom'))
                                                {
                                                    ?>
                                                        <a class="rename-queue" href="javascript:void(0);" onclick="queueModifications();">Rename Queue</a>	
                					<a class="delete-queue" href="javascript:void(0);" onclick="queueModifications();">Delete Queue</a>
                                                    <?php
                                                }
                                                
                                    ?>
					<!--<div class="share clearfix">
						<p>Share via</p>
						<a class="facebook" href="javascript:void(0);"></a>
						<a class="twitter" href="javascript:void(0);"></a>
					</div> -->
				</div>
			</div>
		</div>
                <?php
                echo $session->flash();
                ?>
		<div class="now-playing-container">

			<nav class="playlist-filter-container clearfix">
				<div class="song-filter-button">Song</div>
				<div class="album-filter-button">Album</div>
				<div class="artist-filter-button">Artist</div>
				<div class="time-filter-button">Time</div>
				
			</nav>
			<div class="playlist-shadow-container">
				<div class="playlist-scrollable">
					<div class="row-container">
					<?php
                                            $playListData = array();$i=0;
                                            foreach($queue_list_array as $key => $value)
                                            {  
                                                $i++;
                                                if (($this->Session->read('block') == 'yes') && ($value['Songs']['Advisory'] == 'T'))
                                                {
                                                    continue;
                                                }                                                
					?>
					
					<div class="row clearfix<?php echo $i;?>">
						<!-- <a class="preview" href="#"></a>  -->
                                            <?php
                                                
                                                if ('T' == $value['Songs']['Advisory'])
                                                {
                                                    if (strlen($value['Songs']['SongTitle']) >= 20)
                                                    {
                                                        $value['Songs']['SongTitle'] = $this->getTextEncode(substr($value['Songs']['SongTitle'], 0, 20)) . "..";
                                                    }
                                                    $value['Songs']['SongTitle'] .='(Explicit)';
                                                }                                            
                                            
                                                $duration = explode(':',$value['Songs']['FullLength_Duration']);
                                                $duration_in_secs = $duration[0]*60;
                                                $total_duration = $duration_in_secs+$duration[1];                                                
                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.$value['Songs']['SongTitle'].'","'.$value['Songs']['ArtistText'].'",'.$total_duration.',"'.$value['Songs']['ProdID'].'","'.$value['Songs']['provider_type'].'",'.$queue_id.');')); 
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

                                            ?>
                                                <?php if(!empty($value['Songs']['ProdID'])){ ?>
                                                    <div id="play_item_<?php echo $i;?>"style="display:none;"><?php echo $value['Songs']['ProdID'].','.$value['Songs']['provider_type']; ?></div>
						<?php } ?>
                                                <div class="song-title"><?php 
                                                    echo $value['Songs']['SongTitle'];
                                                    
                                                    ?>
                                                </div>
                                                <?php											
                                                if (strlen($value['Songs']['ArtistText']) >= 30 ) {
                                                        $artistText = $this->getTextEncode(substr($value['Songs']['ArtistText'], 0, 30)) . "..";
                                                } else {
                                                        $artistText = $this->getTextEncode($value['Songs']['ArtistText']);
                                                }
                                                ?>                                                
						<a class="add-to-wishlist-button no-ajaxy" href='javascript:void(0);'></a>
						<div class="album-title">
                                                    <a href="/artists/album/<?php echo base64_encode($value['Songs']['ArtistText']); ?>"><?php echo $value['Albums']['AlbumTitle']; ?></a>                                                
                                                </div>
						<div class="artist-name">
                                                    <a href="/artists/view/<?=base64_encode($value['Songs']['ArtistText']);?>/<?= $value['Songs']['ReferenceID']; ?>/<?= base64_encode($value['Songs']['provider_type']);?>"><?php echo $artistText; ?></a>                                                
                                                </div>
						<div class="time"><?php echo $this->Song->getSongDurationTime($value['Songs']['FullLength_Duration']); ?></div>
						<div class="wishlist-popover">
								
                                                <?php
                                                //check if this song is allowed for download
                                            if (($value['Countries']['SalesDate'] <= date('Y-m-d')) && ($value['Countries']['DownloadStatus']==1))
                                            {         
                                                
                                                if($libraryDownload == '1' && $patronDownload == '1') {

                                                  ?>
                                                <!--<a class="download-now" href="#">Download Now</a> -->
                                                <span class="top-100-download-now-button">
                                                <form method="Post" id="form<?php echo $value["Songs"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                <input type="hidden" name="ProdID" value="<?php echo $value["Songs"]["ProdID"];?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />
                                                <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $value["Songs"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                                                </span>
                                                <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                                                <span id="download_loader_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                </form>
                                                </span>
                                                 <?php

                                                         }
                                            }
                                                 ?>
                                                        <?php   if ($this->Session->read('library_type') == 2 && $value['Countries']['StreamingSalesDate'] <= date('Y-m-d') && $value['Countries']['StreamingStatus'] == 1){

                                                                    echo $this->Queue->getQueuesList($this->Session->read('patron'),$value["Songs"]["ProdID"],$value["Songs"]["provider_type"],$value["Albums"]["ProdID"],$value["Albums"]["provider_type"]); ?>
                                                                    <a class="add-to-playlist" href="#">Add To Queue</a>
                                                        <?php } ?>
							<!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->
                                                         <?php
                                                            $wishlistInfo = $wishlist->getWishlistData($value["Songs"]["ProdID"]);

                                                            echo $wishlist->getWishListMarkup($wishlistInfo,$value["Songs"]["ProdID"],$value["Songs"]["provider_type"]);    
                                                         ?>
                                                        <!--<a class="remove-song" href="#">Remove Song</a> -->
                                                        <span class="top-100-download-now-button">
                                                           <?php 
                                                                    if(($this->Session->read("Auth.User.type_id") == 1 && $queueType=='Default') || ($this->Session->read("Auth.User.type_id") ==1 &&  $queueType=='Custom') ||  ($this->Session->read("Auth.User.type_id") !=1 &&  $queueType=='Custom'))
                                                                    {
                                                           ?>
                                                                        <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                                        <a  href="JavaScript:void(0);" onclick="JavaScript:removeSong(<?php echo $value["QueueDetail"]["id"];?>,<?php echo $i; ?>)"><label class="dload" style="width:120px;cursor:pointer;"><?php __('Remove Song');?></label></a>
                                                                        </span>
                                                            <?php
                                                                    }
                                                            ?>
                                                        <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
						</div>
					</div>
					<?php
                                            if(!empty($value['streamUrl']) || !empty($value['Songs']['SongTitle'])){
                                                $playItem = array('playlistId' => $queue_id, 'songId' => $value["Songs"]["ProdID"],'providerType' => $value["Songs"]["provider_type"],  'label' => $value['Songs']['SongTitle'],'songTitle' => $value['Songs']['SongTitle'],'artistName' => $value['Songs']['ArtistText'],'songLength' => $total_duration,'data' => $value['streamUrl']);
                                                $jsonPlayItem = json_encode($playItem);
                                                $jsonPlayItem = str_replace("\/","/",$jsonPlayItem); 
                                                $playListData[] =$jsonPlayItem;
                                            }
					}
					?>
                                        <?php if(!empty($playListData)){ ?>    
                                        <div id="playlist_data" style="display:none;">
                                            <?php 
                                                $playList = implode(',', $playListData);
                                                if(!empty($playList)){
                                                    echo '['.($playList).']';
                                                }
                                            ?>
                                        </div>
                                        <?php } ?>    
					</div>
				</div>
			</div>
		</div>
               <?php }else{ ?>

                <h2> There are no songs associated with this queue </h2>
		
                <?php } ?>
	</section>

