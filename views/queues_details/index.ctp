<section class="queue-detail-page">
		
		
		<div class="breadcrumbs"><span>Home</span> > <span>Saved Queues</span> > <span>Queue #1</span></div>
		<div class="col-container clearfix">
			<div class="col-1">
				<img src="/app/webroot/img/queue-details/generic-album-cover.jpg" width="155" height="155" />
			</div>
			<div class="col-2">
				<div class="queue-name">
					<?php echo $queue_list_array[0]['Queuelists']['PlaylistName'];?>
				</div>
				<div class="queue-length">
					<?php echo $queue_songs_count; ?> Songs
				</div>
				<div class="queue-duration">
					Duration: <?php echo $total_time; ?>
				</div>
			</div>
			<div class="col-3">
				<div class="faq-link">Need help? Visit our <a href="#">FAQ section</a>.</div>
				<div class="button-container">
					<div class="play-queue-btn"></div>
					<div class="gear-icon"></div>
				</div>
				<div class="queue-options">
					<a class="rename-queue" href="#">Rename Queue</a>	
					<a class="delete-queue" href="#">Delete Queue</a>
					<div class="share clearfix">
						<p>Share via</p>
						<a class="facebook" href="#"></a>
						<a class="twitter" href="#"></a>
					</div>
				</div>
			</div>
		</div>
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
					//for($b=0;$b<28;$b++) {
                                            foreach($queue_list_array as $key => $value)
                                            {   
					?>
					
					<div class="row clearfix">
						<a class="preview" href="#"></a>
						<div class="song-title"><?php echo $value['Songs']['SongTitle']?></div>
						<a class="add-to-wishlist-button" href="#"></a>
						<div class="album-title"><a href="#"><?php echo $value['Albums']['AlbumTitle']?></a></div>
						<div class="artist-name"><a href="#"><?php echo $value['Songs']['ArtistText']?></a></div>
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
							<a class="add-to-playlist" href="#">Add To Queue</a>
							<!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->

                                                         <?php
                                                                                                                    
                                                                    $wishlistInfo = $wishlist->getWishlistData($value["Songs"]["ProdID"]);
                                                                    
                                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                                    ?> 
                                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                    <?php 
                                                                    } else { 
                                                                    ?>
                                                                            <span class="beforeClick" id="wishlist<?php echo $value["Songs"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $value["Songs"]["ProdID"]; ?>","<?php echo $value["Songs"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                            <span class="afterClick" id="downloading_<?php echo $value["Songs"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                                    <?php	
                                                                    }

                                                       ?>
							<!--<a class="remove-song" href="#">Remove Song</a> -->
                                                        <span class="top-100-download-now-button">
                                                        <form method="Post" name="form_rename<?php echo $value["Songs"]["ProdID"]; ?>" action="/queuelistdetails/index/<?php echo $queue_id; ?>" class="suggest_text1">
                                                        <input type="hidden" name="Pdid" value="<?php echo $value["QueuelistDetails"]["Pdid"];?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $value["Songs"]["provider_type"]; ?>" />
                                                        <input type="hidden" name="hdn_remove_song" value="1" />
                                                        <span class="beforeClick" id="song_<?php echo $value["Songs"]["ProdID"]; ?>">
                                                        <a  href='javascript:document.form_rename<?php echo $value["Songs"]["ProdID"]; ?>.submit()' ><label class="dload" style="width:120px;cursor:pointer;"><?php __('Remove Song');?></label></a>
                                                        </span>
                                                        </form>

							<div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="#"></a>
								<a class="twitter" href="#"></a>
							</div>
							<div class="playlist-options">
								<ul>
									<li><a href="#" class="create-new-queue">Create New Queue</a></li>
									<li><a href="#">Queue 1</a></li>
									<li><a href="#">Queue 2</a></li>
									<li><a href="#">Queue 3</a></li>
									<li><a href="#">Queue 4</a></li>
									<li><a href="#">Queue 5</a></li>
									
									
								</ul>
							</div>
						
						</div>
					</div>
					<?php 
					}
					?>
					</div>
				</div>
			</div>
		</div>		
		
	</section>