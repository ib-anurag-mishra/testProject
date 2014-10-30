<section class="now-streaming-page">
           <?php if(!empty($queue_list_array) || !empty($trackDetails) || !empty($albumSongs)){ 
                    if(!empty($queue_list_array)){
               ?>
		<div class="breadcrumbs">
                <?php
                        $html->addCrumb( __('Now Streaming', true), '/queuelistdetails/now_streaming');
                        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
                ?>
                </div>
		<div class="col-container clearfix">
          
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
				
				<div class="col-3">
					<div class="faq-link"><?php echo __('Need help? Visit our', true); ?>  <a href="javascript:void(0);">FAQ section</a>.</div>
						<div class="queue-options">
                                    <?php                                    
                                    
                                                if(($this->Session->read("Auth.User.type_id") == 1 && $queueType=='Default') || ($this->Session->read("Auth.User.type_id") ==1 &&  $queueType=='Custom') ||  ($this->Session->read("Auth.User.type_id") !=1 &&  $queueType=='Custom'))
                                                {
                                                    ?>
                                                        <a class="rename-queue" href="javascript:void(0);" onclick="queueModifications();">Rename Playlist</a>	
                					<a class="delete-queue" href="javascript:void(0);" onclick="queueModifications();">Delete Playlist</a>
                                                    <?php
                                                }
                                                
                                    ?>
							<div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="javascript:void(0);"></a>
								<a class="twitter" href="javascript:void(0);"></a>
							</div>
						</div>
					</div>
				</div>
                <?php echo $session->flash(); ?>                
		
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
					 
							foreach($queue_list_array as $key => $value) {

								if (($this->Session->read('block') == 'yes') && ($value['Songs']['Advisory'] == 'T')) {
									continue;
								} ?>
					
								<div class="row clearfix">
                                            <?php
                                                
                                                if ('T' == $value['Songs']['Advisory']) {
                                                    if (strlen($value['Songs']['SongTitle']) >= 20) {
                                                        $value['Songs']['SongTitle'] = $this->getTextEncode(substr($value['Songs']['SongTitle'], 0, 20)) . "..";
                                                    }
                                                    $value['Songs']['SongTitle'] .='(Explicit)';
                                                }                                            
                                                $duration = explode(':',$value['Songs']['FullLength_Duration']);
                                                $duration_in_secs = $duration[0]*60;
                                                $total_duration = $duration_in_secs+$duration[1];
                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.base64_encode($value['Songs']['SongTitle']).'","'.base64_encode($value['Songs']['ArtistText']).'",'.$total_duration.',"'.$value['Songs']['ProdID'].'","'.$value['Songs']['provider_type'].'",'.$queue_id.');'));
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

                                            ?>
									<div class="song-title"><?php echo $value['Songs']['SongTitle']?></div>
									<a class="add-to-wishlist-button no-ajaxy" href="javascript:void(0)"></a>
                                                <?php
                                                if (strlen($value['Songs']['ArtistText']) >= 30 ) {
                                                        $artistText = $this->getTextEncode(substr($value['Songs']['ArtistText'], 0, 30)) . "..";
                                                } else {
                                                        $artistText = $this->getTextEncode($value['Songs']['ArtistText']);
                                                }
                                                ?>

						<div class="wishlist-popover">
                                            <?php

                                            if($libraryDownload == '1' && $patronDownload == '1') {  ?>                                                   
                                                    
                                                     <form method="Post" id="form<?php echo $value["Songs"]["ProdID"]; ?>" action="/homes/userDownload">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Songs"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />

                                                        <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
                                                               onclick='return wishlistDownloadOthersHome("<?php echo $value["Songs"]['ProdID']; ?>", "0", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>", "<?php echo $value["Songs"]["provider_type"]; ?>");'>
                                                                   <?php __('Download Now'); ?>
                                                            </a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $value["Songs"]['ProdID']; ?>", "0" , "<?php echo $value["Songs"]["provider_type"]; ?>", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                            <![endif]-->
                                                        </span>

                                                        <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;">
                                                            <a  class="add-to-wishlist"  >
                                                                <?php __("Please Wait"); ?>...
                                                                <span id="wishlist_loader_<?php echo $value["Songs"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;">
                                                                    <?php echo $html->image('ajax-loader_black.gif'); ?>
                                                                </span> 
                                                            </a> 
                                                        </span>
                                                    </form>
                                                  
                                            <?php
                                                   }

                                               $wishlistInfo = $wishlist->getWishlistData($value["Songs"]["ProdID"]);

                                               echo $wishlist->getWishListMarkup($wishlistInfo,$value["Songs"]["ProdID"],$value["Songs"]["provider_type"]);    
                                            ?>
                                                        <span class="top-100-download-now-button">
                                                        <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                        </span>
													</div>
													<div class="album-title">
														<a href="/artists/album/<?php echo base64_encode($value['Songs']['ArtistText']); ?>"><?php echo $value['Albums']['AlbumTitle']; ?></a>                                                
													</div>
													<div class="artist-name">
														<a href="/artists/view/<?=base64_encode($value['Songs']['ArtistText']);?>/<?= $value['Songs']['ReferenceID']; ?>/<?= base64_encode($value['Songs']['provider_type']);?>"><?php echo $artistText; ?></a>                                                
													</div>
                                                
                        <div class="time"><?php echo $this->Song->getSongDurationTime($value['Songs']['FullLength_Duration']); ?></div>                        
					</div>
					<?php 
					}
					?>
					</div>
				</div>
			</div>
		</div>
               <?php } elseif (!empty($trackDetails)) { ?>
		<div class="breadcrumbs"><span>Home</span> > <span>Now Streaming2</span></div>
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
					 
                                            foreach($trackDetails as $key => $value) {
                                                
                                                if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T')) {
                                                    continue;
                                                }                                                

					?>
					
					<div class="row clearfix">
						<?php
						if ('T' == $value['Song']['Advisory']) {
						    if (strlen($value['Song']['SongTitle']) >= 20) {
						        $value['Song']['SongTitle'] = $this->getTextEncode(substr($value['Song']['SongTitle'], 0, 20)) . "..";
						    }
						    $value['Song']['SongTitle'] .='(Explicit)';
						}                                            
						$duration = explode(':',$value['Song']['FullLength_Duration']);
						$duration_in_secs = $duration[0]*60;
						$total_duration = $duration_in_secs+$duration[1];                                                
						echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.base64_encode($value['Song']['SongTitle']).'","'.base64_encode($value['Song']['ArtistText']).'",'.$total_duration.',"'.$value['Song']['ProdID'].'","'.$value['Song']['provider_type'].'");')); 
						echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
						echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

						?>
						<div class="song-title"><?php 
                                                echo $value['Song']['SongTitle']?></div>
						<a class="add-to-wishlist-button no-ajaxy" href="javascript:void(0)"></a>
												<?php
												if (strlen($value['Song']['ArtistText']) >= 30 ) {
												        $artistText = $this->getTextEncode(substr($value['Song']['ArtistText'], 0, 30)) . "..";
												} else {
												        $artistText = $this->getTextEncode($value['Song']['ArtistText']);
												}
												?>

						<div class="wishlist-popover">
														<?php
														if($libraryDownload == '1' && $patronDownload == '1') { ?>
                                                    
                                                    <form method="Post" id="form<?php echo $value["Songs"]["ProdID"]; ?>" action="/homes/userDownload">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Songs"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />

                                                        <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
                                                               onclick='return wishlistDownloadOthersHome("<?php echo $value["Songs"]['ProdID']; ?>", "0", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>", "<?php echo $value["Songs"]["provider_type"]; ?>");'>
                                                                   <?php __('Download Now'); ?>
                                                            </a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $value["Songs"]['ProdID']; ?>", "0" , "<?php echo $value["Songs"]["provider_type"]; ?>", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                            <![endif]-->
                                                        </span>

                                                        <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;">
                                                            <a  class="add-to-wishlist"  >
                                                                <?php __("Please Wait"); ?>...
                                                                <span id="wishlist_loader_<?php echo $value["Songs"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;">
                                                                    <?php echo $html->image('ajax-loader_black.gif'); ?>
                                                                </span> 
                                                            </a> 
                                                        </span>
                                                    </form>
                                                    
                                                    
															
														<?php
														}
												
														$wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

														echo $wishlist->getWishListMarkup($wishlistInfo,$value["Song"]["ProdID"],$value["Song"]["provider_type"]);    
														?>
														<span class="top-100-download-now-button">
															<form method="Post" name="form_rename<?php echo $value["Song"]["ProdID"]; ?>" action="/queuelistdetails/index/<?php echo $queue_id; ?>" class="suggest_text1">
																<input type="hidden" name="Pdid" value="<?php echo $value["QueueDetail"]["id"];?>" />
																<input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" />
																<input type="hidden" name="hdn_remove_song" value="1" />
																<span class="beforeClick" id="song_<?php echo $value["Song"]["ProdID"]; ?>">
																</span>
															</form>
						</div>
                        <div class="album-title">
                                                    <a href="/artists/album/<?php echo base64_encode($value['Song']['ArtistText']); ?>"><?php echo $value['Albums']['AlbumTitle']; ?></a>                                                
                                                </div>
                        <div class="artist-name">
                                                    <a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>"><?php echo $artistText; ?></a>                                                
                                                </div>                                                
                                                
                        <div class="time"><?php echo $this->Song->getSongDurationTime($value['Song']['FullLength_Duration']);?></div>                        
					</div>
					<?php 
					}
					?>
					</div>
				</div>
			</div>
		</div>                
                <?php } else if(!empty($albumSongs)) { ?>
		<div class="breadcrumbs"><span>Home</span> > <span>Now Streaming3</span></div>
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
                        <?php foreach($albumSongs as $value) { ?>
			<div class="playlist-shadow-container">
				<div class="playlist-scrollable">
					<div class="row-container">
					<?php                                                
                                            if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T'))
                                            {
                                                continue;
                                            }                                                
					?>
					<div class="row clearfix">
                                            <?php
                                                if ('T' == $value['Song']['Advisory'])
                                                {
                                                    if (strlen($value['Song']['SongTitle']) >= 20)
                                                    {
                                                        $value['Song']['SongTitle'] = $this->getTextEncode(substr($value['Song']['SongTitle'], 0, 20)) . "..";
                                                    }
                                                    $value['Song']['SongTitle'] .='(Explicit)';
                                                }                                            
                                                $duration = explode(':',$value['Song']['FullLength_Duration']);
                                                $duration_in_secs = $duration[0]*60;
                                                $total_duration = $duration_in_secs+$duration[1];                                                
                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'loadSong("'.$value['streamUrl'].'", "'.base64_encode($value['Song']['SongTitle']).'","'.base64_encode($value['Song']['ArtistText']).'",'.$total_duration.',"'.$value['Song']['ProdID'].'","'.$value['Song']['provider_type'].'");')); 
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key)); 
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 

                                            ?>
						<div class="song-title"><?php 
                                                echo $value['Song']['SongTitle']?></div>
						<a class="add-to-wishlist-button no-ajaxy" href="javascript:void(0)"></a>
                                                <?php
                                                if (strlen($value['Song']['ArtistText']) >= 30 ) {
                                                        $artistText = $this->getTextEncode(substr($value['Song']['ArtistText'], 0, 30)) . "..";
                                                } else {
                                                        $artistText = $this->getTextEncode($value['Song']['ArtistText']);
                                                }
                                                ?>
						<div class="wishlist-popover">
                                                        <?php
                                                                if($libraryDownload == '1' && $patronDownload == '1') {

                                                          ?>
                                                    
                                                  <form method="Post" id="form<?php echo $value["Songs"]["ProdID"]; ?>" action="/homes/userDownload">
                                                        <input type="hidden" name="ProdID" value="<?php echo $value["Songs"]["ProdID"]; ?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />

                                                        <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                            <![if !IE]>
                                                            <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."); ?>"
                                                               onclick='return wishlistDownloadOthersHome("<?php echo $value["Songs"]['ProdID']; ?>", "0", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>", "<?php echo $value["Songs"]["provider_type"]; ?>");'>
                                                                   <?php __('Download Now'); ?>
                                                            </a>
                                                            <![endif]>
                                                            <!--[if IE]>
                                                                    <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $value["Songs"]['ProdID']; ?>", "0" , "<?php echo $value["Songs"]["provider_type"]; ?>", "<?php echo $value['Songs']['SCdnPath']; ?>", "<?php echo $value['Songs']['SSaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                            <![endif]-->
                                                        </span>

                                                        <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;">
                                                            <a  class="add-to-wishlist"  >
                                                                <?php __("Please Wait"); ?>...
                                                                <span id="wishlist_loader_<?php echo $value["Songs"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;">
                                                                    <?php echo $html->image('ajax-loader_black.gif'); ?>
                                                                </span> 
                                                            </a> 
                                                        </span>
                                                    </form>  
                                                 
                                                       
                                                         <?php
                                                                    
                                                                 }
                                                         ?>
                                                         <?php
                                                            $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

                                                            echo $wishlist->getWishListMarkup($wishlistInfo,$value["Song"]["ProdID"],$value["Song"]["provider_type"]);    
                                                         ?>
                                                        <span class="top-100-download-now-button">
                                                        <form method="Post" name="form_rename<?php echo $value["Song"]["ProdID"]; ?>" action="/queuelistdetails/index/<?php echo $queue_id; ?>" class="suggest_text1">
                                                        <input type="hidden" name="Pdid" value="<?php echo $value["QueueDetail"]["id"];?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" />
                                                        <input type="hidden" name="hdn_remove_song" value="1" />
                                                        <span class="beforeClick" id="song_<?php echo $value["Song"]["ProdID"]; ?>">
                                                        <?php /*<a  href="JavaScript:void(0);" onclick="JavaScript:removeSong(<?php echo $value["QueueDetail"]["id"];?>)"><label class="dload" style="width:120px;cursor:pointer;"><?php __('Remove Song');?></label></a>*/ ?>
                                                        </span>
                                                        </form>
						</div>
                                                <div class="album-title">
                                                                            <a href="/artists/album/<?php echo base64_encode($value['Song']['ArtistText']); ?>"><?php echo $albumTitle; ?></a>                                                
                                                                        </div>
                                                <div class="artist-name">
                                                                            <a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $albumID; ?>/<?= base64_encode($value['Song']['provider_type']);?>"><?php echo $artistText; ?></a>                                                
                                                                        </div>                                                

                                                <div class="time"><?php echo $this->Song->getSongDurationTime($value['Song']['FullLength_Duration']);?></div>                        
					</div>
                                    </div>
				</div>
			</div>
                    <?php } ?>
		</div>                     
                    
                <?php }    ?>
        <?php }else{ ?>

            <h2> <?php echo __('There are no Playlists currently being played.'); ?> </h2>

        <?php } ?>                
</section>