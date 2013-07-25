<?php
	foreach($albumData as $album_key => $album):
?>
		<section class="album-detail">
			<div class="album-cover-image">
                            <?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']); ?>
				<?php
					$image = Configure::read('App.Music_Path').$albumArtwork;
					if($page->isImage($image)) {
						//Image is a correct one
					}
					else {
						
					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
					}
				?>
				<img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" alt="album-detail-cover" width="250" height="250" />
			</div>
			<div class="album-title"><?php
					if(strlen($album['Album']['AlbumTitle']) >= 50){
						$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
					}
					?>
					<?php echo $this->getTextEncode($album['Album']['AlbumTitle']);?></div>                      
                        
                        
                        
			
			<div class="album-genre"><?php echo __('Genre').": ";?><span><?php echo $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) ;
                        if($album['Album']['Advisory'] == 'T'){
                        	echo '<br />'; echo '<font class="explicit"> (Explicit)</font>';
                            
                        }?></span></div>
			<div class="album-label"><?php echo __('Label').": ";?><span><?php if ($album['Album']['Label'] != '') {
							echo $this->getTextEncode($album['Album']['Label']);}?></span></div>
			
			
		</section>




		<section class="tracklist-container">		
			
			<div class="tracklist-header"><span class="song">Song</span><span class="artist">Artist</span><span class="time">Time</span></div>
			
                            <?php
                            
					$i = 1;
					foreach($albumSongs[$album['Album']['ProdID']] as  $key => $albumSong):			
						
					?>	
				
				<div class="tracklist">
                                    
                                <?php  if($this->Session->read('patron')) {  ?>  
                                    <?php           
                                                if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
                                                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.base64_encode($nationalTopDownload[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                                                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                                                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
                                                }
                                    ?>
                                <?php } ?>
					
                                
                                
                                        <div class="song"><?php
                                                            if (strlen($albumSong['Song']['SongTitle']) >= 40) {
                                                                    echo '<span title="'.$this->getTextEncode($albumSong['Song']['SongTitle']).'">'  . $this->getTextEncode(substr($albumSong['Song']['SongTitle'], 0, 45)) . '...</span>';
                                                            } else {
                                                                    echo '<p>' . $this->getTextEncode($albumSong['Song']['SongTitle']);
                                                            }
                                                            if ($albumSong['Song']['Advisory'] == 'T') {
                                                                    echo '<span class="explicit"> (Explicit)</span>';
                                                            }
                                                    ?></div>
					<div class="artist"><a href="#"><?php
										if (strlen($albumSong['Song']['Artist']) >= 11) {
											if(strlen($albumSong['Song']['Artist']) >= 60){
												$albumSong['Song']['Artist'] = substr($albumSong['Song']['Artist'], 0, 60). '...';
											}
											echo $this->getTextEncode(substr($albumSong['Song']['Artist'], 0, 13));
										} else {
											echo $this->getTextEncode($albumSong['Song']['Artist']) ;
										}
									?></a></div>
					<div class="time"><?php echo $albumSong['Song']['FullLength_Duration']?></div>
					<a class="add-to-playlist-button" href="#"></a>
					<div class="wishlist-popover">
						<div class="playlist-options">
							<ul>
								<li><a href="#">Create New Playlist</a></li>
								<li><a href="#">Playlist 1</a></li>
								<li><a href="#">Playlist 2</a></li>
								<li><a href="#">Playlist 3</a></li>
								<li><a href="#">Playlist 4</a></li>
								<li><a href="#">Playlist 5</a></li>
								<li><a href="#">Playlist 6</a></li>
								<li><a href="#">Playlist 7</a></li>
								<li><a href="#">Playlist 8</a></li>
								<li><a href="#">Playlist 9</a></li>
								<li><a href="#">Playlist 10</a></li>
							</ul>
						</div>
						                                                
                                                
                                                <?php
                                                                    if($this->Session->read('patron')) {
										if($albumSong['Country']['SalesDate'] <= date('Y-m-d'))
										{
											if($libraryDownload == '1' && $patronDownload == '1')
											{	
												if($albumSong['Song']['status'] != 'avail'){
										?>
										<form method="Post" id="form<?php echo $albumSong["Song"]["ProdID"]; ?>" action="/homes/userDownload">
                                                                                        <input type="hidden" name="ProdID" value="<?php echo $albumSong["Song"]["ProdID"];?>" />
                                                                                        <input type="hidden" name="ProviderType" value="<?php echo $albumSong["Song"]["provider_type"]; ?>" />

                                                                                        <span class="beforeClick" style="cursor:pointer;" id="song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
                                                                                                <a href='javascript:void(0);' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.");?>" onclick='userDownloadAll(<?php echo $albumSong["Song"]["ProdID"]; ?>);'><?php __('Download Now');?></a>
                                                                                        </span>
                                                                                
                                                                                        <span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait..");?>
                                                                                        <span id="download_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php  echo  $html->image('ajax-loader_black.gif');  ?></span> </a> </span>
                                                                                           
                                                                                </form>													
												
									<?php	
												} else {
													?><a class='add-to-wishlist' href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __("Downloaded");?></a><?php
												}
											}											
											else{
											?>
                                                                                        <a class="add-to-wishlist" href="javascript:void(0)"><?php __("Limit Met");?></a>                
                                                                                        <?php
											}
										}else{
									?>
											<a class="add-to-wishlist" href="javascript:void(0)"><span title='<?php __("Coming Soon");?> ( <?php if(isset($albumSong['Country']['SalesDate'])){ echo 
												date("F d Y", strtotime($albumSong['Country']['SalesDate']));} ?> )'>Coming Soon</span></a>
									<?php
										}
									}else{
                                                                         ?>
                                                                        <a class="top-100-download-now-button" href='/users/login'> <?php __("Login");?></a>
                                                                        <?php
                                                                        }
                                                                        ?>	
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
						<a class="add-to-playlist" href="#">Add To Queue</a>
						
                                                
                                                
                                                 <?php

                                                $wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);

                                                if($wishlistInfo == 'Added to Wishlist') {
                                                ?> 
                                                        <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                <?php 
                                                } else { 
                                                ?>
                                                        <span class="beforeClick" id="wishlist<?php echo $albumSong["Song"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $albumSong["Song"]["ProdID"]; ?>","<?php echo $albumSong["Song"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                        <span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                <?php	
                                                }

                                                ?>
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
				</div>
					
			<?php
                      
                            endforeach;
					
                            
			?>
			
					
				
		</section>
            <?php
	endforeach;
        ?>