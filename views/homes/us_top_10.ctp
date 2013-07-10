

<section class="my-top-100-page">
		
		<div class="breadcrumbs">
                    <?php
                            
                            $html->addCrumb('US Top 10', '/homes/us_top_10');
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                    ?>
                </div>
		<header class="clearfix">
			<h2>US Top 10</h2>
			
		</header>
		<h3>Albums</h3>
		<div class="album-shadow-container">
			<div class="album-scrollable horiz-scroll">
				<ul style="width:2800px;">
					<?php
                                        

                                            

					 $count  =   1;           
					//for($d=1;$d<$count;$d++) {
                                        foreach($ustop10Albums as $key => $value){
                                            
                                             $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             $album_img =  Configure::read('App.Music_Path').$album_img;                                            					
					?>					
					<li>
						<div class="album-container">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">                                                        
                                                        <img src="<?php echo $album_img; ?>" alt="daftpunk" width="250" height="250" />
                                                        </a>
							<div class="top-10-ranking"><?php echo $count; ?></div>
							
						</div>
						<div class="album-title">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>35)
                                                                echo substr($value['Song']['SongTitle'],0,35)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Song']['Artist'])>35)
                                                                                                                    echo substr($value['Song']['Artist'],0,35)."..."; 
                                                                                                                    else echo $value['Song']['Artist'];
                                                                                                             ?>
                                                       </a>
						</div>
					</li>
					<?php
                                                $count++;
					}
					?>
				</ul>
			</div>
		</div>
		<h3>Songs</h3>
		<div class="songs-shadow-container">
			<div class="songs-scrollable horiz-scroll">
				<ul style="width:2800px;">
					<?php
                                        
					//for($d=1;$d<$count;$d++) {

                                          $count =1;
                                        foreach($nationalTopDownload as $key => $value){
                                            
                                            if($count>10) break;
                                            
                                             $songs_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             $songs_img =  Configure::read('App.Music_Path').$songs_img;
                                            
					?>
					<li>
						
						<div class="song-container">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">                                                        
                                                        <img src="<?php echo $songs_img; ?>" alt="daftpunk" width="250" height="250" />
                                                        </a>
							<div class="top-10-ranking"><?php echo $count; ?></div>
							

<?php if($this->Session->read("patron")){ ?> 
<!-- <a href="#" class="preview"></a>  -->
<?php                                  if($value['Country']['SalesDate'] <= date('Y-m-d')) {
                                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;border: 0px solid;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "'.$key.'", '.$value['Song']['ProdID'].', "'.base64_encode($value['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "load_audio".$key)); 
                                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;border: 0px solid;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");')); 
                                  }
 }
  ?>
							<?php

    if($this->Session->read('patron')) {
        if($value['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $value['Song']['status'] = 'avail1';
                    if(isset($value['Song']['status']) && ($value['Song']['status'] != 'avail')) {
                            ?>
        <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $value["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $value["Song"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $value["Song"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $value["Song"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $value["Song"]["ProdID"]; ?>");'><label class="top-10-download-now-button" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
        </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="top-100-download-now-button" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {

                if($libraryDownload != '1') {
                     ?>
                          <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>  
                <?php

                }             	
                												
            }
        } else {
        ?>
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($value['Country']['SalesDate'])){ echo date("F d Y", strtotime($value['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{
?>
     <a class="top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
      ?>


                                                                                    <?php if($this->Session->read("patron")){ ?> 
														<a class="add-to-playlist-button" href="#"></a>
                                                                                               
														<div class="wishlist-popover">
                                                                                                         <?php if( $this->Session->read('library_type') == 2 ){ ?> 
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
                                                                                               
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
                                                                                                        <?php } ?>
															
                                                                                                                        
                                                                                                                        
                                                                                                                        
                                                                                                                        <?php
                                                                                                                    
                                                                                                                    $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

                                                                                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                    ?> 
                                                                                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                    <?php 
                                                                                                                    } else { 
                                                                                                                    ?>
                                                                                                                            <span class="beforeClick" id="wishlist<?php echo $value["Song"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $value["Song"]["ProdID"]; ?>","<?php echo $value["Song"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                            <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                                                                                    <?php	
                                                                                                                    }

                                                                                                                    ?>

															
															<div class="share clearfix">
																<p>Share via</p>
																<a class="facebook" href="#"></a>
																<a class="twitter" href="#"></a>
															</div>
															
														</div>
                                                                                                    <?php } ?>
							
						</div>
						<div class="album-title">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>35)
                                                                echo substr($value['Song']['SongTitle'],0,35)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Song']['Artist'])>35)
                                                                                                                    echo substr($value['Song']['Artist'],0,35)."..."; 
                                                                                                                    else echo $value['Song']['Artist'];
                                                                                                             ?>
                                                       </a>
						</div>
					</li>
					
					<?php
                                                $count++;
					}
					
					?>
					
					
				</ul>
			</div>
		
		</div>
		<h3>Videos</h3>
		<div class="videos-shadow-container">
			<div class="videos-scrollable horiz-scroll">
				<ul style="width:4666px;">
					<?php
                                                                                
                                            $count = 1;
					//for($d=1;$d<$count;$d++) {
                                        foreach($usTop10VideoDownload as $key => $value){
                                            
                                            // $video_img = shell_exec('perl files/tokengen ' . $value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                                             //$video_img =  Configure::read('App.Music_Path').$video_img;

                                                $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                                                $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

					?>
					<li>
						
						<div class="video-container">
							<a href="javascript:void(0);">                                                        
                                                        <img src="<?php echo $videoAlbumImage; ?>" alt="jlo423x250" width="423" height="250" />
                                                        </a>                                                  
							<div class="top-10-ranking"><?php echo $count; ?></div>
							<?php if($this->Session->read("patron")){ ?> 														
                                                            <a href="#" class="preview"></a>
                                                            <?php } ?>

							

<?php

    if($this->Session->read('patron')) {
        if($value['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $value['Video']['Video']['status'] = 'avail1';
                    if($value['Video']['status'] != 'avail' ) {
                            ?>
                            <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $value["Video"]["ProdID"]; ?>");'><label class="top-10-download-now-button" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
                            </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {

            ?>
                            <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
             <?php
            }
        } else {
        ?>
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($value['Country']['SalesDate'])){ echo date("F d Y", strtotime($value['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
    ?>
							<!-- <a class="top-100-download-now-button" href="#">Download Now</a> -->
							
								
								<?php if($this->Session->read("patron")){ ?> 
														
														<a class="add-to-playlist-button" href="#"></a>
														
														<div class="wishlist-popover">
															<!--
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
															
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
															-->
															<?php

                                                                                                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);

                                                                                                                        if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                        ?> 
                                                                                                                                <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                        <?php 
                                                                                                                        } else { 
                                                                                                                        ?>
                                                                                                                                <span class="beforeClick" id="video_wishlist<?php echo $value["Video"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $value["Video"]["ProdID"]; ?>","<?php echo $value["Video"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                                <span class="afterClick" id="downloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                                                                                        <?php	
                                                                                                                        }

                                                                                                                        ?>
							
															
															<div class="share clearfix">
																<p>Share via</p>
																<a class="facebook" href="#"></a>
																<a class="twitter" href="#"></a>
															</div>
															
														</div>
                                                                                                  <?php } ?>
								
							
							
						</div>
						<div class="album-title">
							<a href="javascript:void(0);">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Video']['VideoTitle'])>35)
                                                                echo substr($value['Video']['VideoTitle'],0,35)."..."; 
                                                                else echo $value['Video']['VideoTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Video']['ArtistText'])); ?>/<?=base64_encode($value['Video']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>35)
                                                                                                                    echo substr($value['Video']['Artist'],0,35)."..."; 
                                                                                                                    else echo $value['Video']['Artist'];
                                                                                                             ?>
                                                       </a>
						</div>
					</li>
					
					<?php
                                                $count++; 
					}
					
					?>
					
					
				</ul>
			</div>
		
		</div>
	</section>