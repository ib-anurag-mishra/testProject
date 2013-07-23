<section class="now-streaming-page">
           <?php if(!empty($queue_list_array)){ ?>
		<div class="breadcrumbs"><span>Home</span> > <span>Now Streaming</span></div>
		<header class="clearfix">
			<h2><?php echo $queue_list_array[0]['QueueList']['queue_name'];?></h2>
			<div class="faq-link"><?php echo __('Need help? Visit our', true); ?> <a href="#">FAQ section.</a></div>
		</header>
		<div class="album-info-playlist-container clearfix">
			<div class="album-info-container">
				<div class="album-cover-container">
					
					<img src="<? echo $this->webroot; ?>app/webroot/img/playlist/album-cover.jpg" alt="album-cover" width="155" height="155" />
					<a class="add-to-playlist-button" href="#"></a>
                                        <?php //$queueList = $this->Queue->getQueuesList($this->Session->read('patron'));?>
					<div class="wishlist-popover">
						<div class="playlist-options">
							<ul>
								<li><a href="#" class="create-new-queue">Create New Queue</a></li>
                                                                <!--<?php //if(!empty($queueList)){foreach($queueList as $key => $value){ ?>
								<li><a href="#"><?php //echo $value['QueueList']['queue_name']; ?></a></li>
								<?php 
                                                                    //}
                                                                //} ?>-->
							</ul>
						</div>			
						<a class="remove-songs" href="#">Download Song</a>
						<a class="add-to-playlist" href="#">Add To Queue</a>
						
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
				
					</div>
				</div>
			</div>

			<div class="album-info">
				<p>Now Streaming</p>
				<div class="now-playing-text"><span class="now-playing-title">Grow Up</span> by <span class="now-playing-artist"><a href="#">Cher Lloyd</a></span> on <span class="now-playing-album-title"><a href="#">Sticks and Stones</a></span></div>
				<div class="release-genre">Genre: <span><a href="#">Pop</a></span></div>
				<div class="release-label">Label: <span>Columbia</span></div>
				<input type="hidden" id="hid_Plid" value="<?php echo $queue_id;?>" />
                                <input type="hidden" id="hid_playlist_name" value="<?php echo $queue_list_array[0]["QueueList"]["queue_name"];?>" />
                                <input type="hidden" id="hid_description" value="<?php echo $queue_list_array[0]["QueueList"]["description"];?>" />

			</div>
					
			<div class="gear-container">

				<div class="gear-icon">
					
				</div>
				
				<div class="queue-options">
					<a class="rename-queue" href="#" onclick="queueModifications();">Rename Queue</a>	
					<a class="delete-queue" href="#" onclick="queueModifications();">Delete Queue</a>

				</div>
				
				
			</div>
			
		</div>
                <?php
                echo $session->flash();
                ?>                
		<div class="now-playing-container">

			<nav class="playlist-filter-container clearfix">
				<div class="song-filter-button"></div>
				<div class="album-filter-button"></div>
				<div class="artist-filter-button"></div>
				<div class="time-filter-button"></div>
				
			</nav>
			<div class="playlist-shadow-container">
				<div class="playlist-scrollable">
					<div class="row-container">
					<?php                                                
					 
                                            foreach($queue_list_array as $key => $value)
                                            {

					?>
					
					<div class="row clearfix">
						<!-- <a class="preview" href="#"></a>  -->
                                            <?php

                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "'.$key.'", '.$value['Songs']['ProdID'].', "'.base64_encode($value['Songs']['provider_type']).'", "'.$this->webroot.'");')); 
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

                                            ?>
						<div class="song-title"><?php echo $value['Songs']['SongTitle']?></div>
						<a class="add-to-wishlist-button" href="#"></a>
                                                <?php
                                                if (strlen($value['Songs']['ArtistText']) >= 30 ) {
                                                        $artistText = $this->getTextEncode(substr($value['Songs']['ArtistText'], 0, 30)) . "..";
                                                } else {
                                                        $artistText = $this->getTextEncode($value['Songs']['ArtistText']);
                                                }
                                                ?> 
						<div class="album-title">
                                                    <a href="/artists/album/"<?php base64_encode($value['Songs']['ArtistText']); ?>"><?php echo $value['Albums']['AlbumTitle']; ?></a>                                                
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
					}
					?>
					</div>
				</div>
			</div>
		</div>
               <?php }else{ ?>

                <h2> There are no queues currently being played. </h2>
		
                <?php } ?>                

	</section>