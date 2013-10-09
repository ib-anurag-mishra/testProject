<section class="queue-detail-page">
            <?php if(!empty($queue_list_array)){ ?>
		<div class="breadcrumbs"><span>Home</span> > <span>Saved Queues</span> > <span>Queue #1</span></div>
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
				<div class="faq-link"><?php echo __('Need help? Visit our', true); ?>  <a href="#">FAQ section</a>.</div>
				<div class="button-container">
					<div class="play-queue-btn"></div>
					<div class="gear-icon no-ajaxy"></div>
				</div>
				<div class="queue-options">
					<a class="rename-queue" href="#" onclick="queueModifications();">Rename Queue</a>	
					<a class="delete-queue" href="#" onclick="queueModifications();">Delete Queue</a>
					<div class="share clearfix">
						<p>Share via</p>
						<a class="facebook" href="#"></a>
						<a class="twitter" href="#"></a>
					</div>
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
					?>
					
					<div class="row clearfix">
						<!-- <a class="preview" href="#"></a>  -->
                                            <?php

                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.$value['Songs']['SongTitle'].'","'.$value['Songs']['ArtistText'].'","'.$value['Songs']['ProdID'].'","'.$value['Songs']['provider_type'].'");')); 
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

                                            ?>
                                                <?php if(!empty($value['Songs']['ProdID'])){ ?>
                                                    <div id="play_item_<?php echo $i;?>"style="display:none;"><?php echo $value['Songs']['ProdID'].','.$value['Songs']['provider_type']; ?></div>
						<?php } ?>
                                                <div class="song-title"><?php echo $value['Songs']['SongTitle']?></div>
                                                <?php											
                                                if (strlen($value['Songs']['ArtistText']) >= 30 ) {
                                                        $artistText = $this->getTextEncode(substr($value['Songs']['ArtistText'], 0, 30)) . "..";
                                                } else {
                                                        $artistText = $this->getTextEncode($value['Songs']['ArtistText']);
                                                }
                                                ?>                                                
						<a class="add-to-wishlist-button no-ajaxy" href="#"></a>
						<div class="album-title">
                                                    <a href="/artists/album/<?php echo base64_encode($value['Songs']['ArtistText']); ?>"><?php echo $value['Albums']['AlbumTitle']; ?></a>                                                
                                                </div>
						<div class="artist-name">
                                                    <a href="/artists/view/<?=base64_encode($value['Songs']['ArtistText']);?>/<?= $value['Songs']['ReferenceID']; ?>/<?= base64_encode($value['Songs']['provider_type']);?>"><?php echo $artistText; ?></a>                                                
                                                </div>
						<div class="time"><?php echo $value['Songs']['FullLength_Duration']?></div>
						<div class="wishlist-popover">
								
                                                <?php
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
                                                 ?>
                                                        <?php if( $this->Session->read('library_type') == 2 ){
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
                                                        <form method="Post" name="form_rename<?php echo $value["Songs"]["ProdID"]; ?>" action="/queuelistdetails/index/<?php echo $queue_id; ?>" class="suggest_text1">
                                                        <input type="hidden" name="Pdid" value="<?php echo $value["QueueDetail"]["id"];?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />
                                                        <input type="hidden" name="hdn_remove_song" value="1" />
                                                        <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                        <a  href='javascript:document.form_rename<?php echo $value["Songs"]["ProdID"]; ?>.submit()' ><label class="dload" style="width:120px;cursor:pointer;"><?php __('Remove Song');?></label></a>
                                                        </span>
                                                        </form>
                                                        <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
						</div>
					</div>
					<?php
                                            if(!empty($value['streamUrl']) || !empty($value['Songs']['SongTitle'])){
                                                $playItem = array('label' => $value['Songs']['SongTitle'],'title' => $value['Songs']['SongTitle'],'artistName' => $value['Songs']['ArtistText'],'data' => $value['streamUrl']);
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
                                                    echo '['.$playList.']';
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