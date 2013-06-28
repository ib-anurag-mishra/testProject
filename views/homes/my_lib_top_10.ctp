

<section class="my-top-100-page">
		
		<div class="breadcrumbs"><span>Home</span> > <span>Most Popular</span> > <span>My Top 10</span></div>
		<header class="clearfix">
			<h2>My Library Top 10</h2>
			
		</header>
		<h3>Albums</h3>
		<div class="album-shadow-container">
			<div class="album-scrollable horiz-scroll">
				<ul>
					<?php
                                        
					 $count  =   1;           
					//for($d=1;$d<$count;$d++) {
                                        foreach($topDownload_albums as $key => $value){
                                            
                                             $album_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             $album_img =  Configure::read('App.Music_Path').$album_img;                                            					
					?>					
					<li>
						<div class="album-container">
							<!-- <a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['ProdID']);?>">
                                                        <img class="lazy" src="<?php echo $album_img; ?>" alt="pitbull162x162" width="250" height="250" />
                                                        </a> -->

                                                        <?php echo $html->link($html->image($album_img,array("height" => "250", "width" => "250")),
										array('controller'=>'artists', 'action'=>'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ProdID'] , base64_encode($value['Song']['provider_type'])),
										array('class'=>'first','escape'=>false))?>
							<div class="top-10-ranking"><?php echo $count; ?></div>
							
						</div>
						<div class="album-title">							
                                                        <a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>20)
                                                                echo substr($value['Song']['SongTitle'],0,20)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">							
                                                        <a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Song']['Artist'])>20)
                                                                                                                    echo substr($value['Song']['Artist'],0,20)."..."; 
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
				<ul>
					<?php
                                                
                                        $count  =   1;  
                                                                                
                                        
					//for($d=1;$d<$count;$d++) {
                                        foreach($top_10_songs as $key => $value){

                                            if($count>10) break;
                                            
                                             $songs_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             $songs_img =  Configure::read('App.Music_Path').$songs_img;
                                            
					?>
					<li>
						
						<div class="song-container">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <img class="lazy"  src="<?php echo $songs_img; ?>" alt="pitbull162x162" width="250" height="250" />                                                        
                                                        </a>
							<div class="top-10-ranking"><?php echo $count; ?></div>

<?php if($this->Session->read("patron")){ ?> <a href="#" class="preview"></a> <?php } ?>


												


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
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {

                if($libraryDownload != '1') {
                        $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                        $wishlistCount = $wishlist->getWishlistCount();
                        if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
                        ?> 
                                <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
                        <?php
                        } else {
                                $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
                                if($wishlistInfo == 'Added to Wishlist') {
                                ?> 
                                        <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                <?php 
                                } else { 
                                ?>
                                        <span class="beforeClick" id="wishlist<?php echo $value["Song"]["ProdID"]; ?>"><a class="top-100-download-now-button" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $value["Song"]["ProdID"]; ?>","<?php echo $value["Song"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
                                        <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
                                <?php	
                                }
                        }

                } else { 
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
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
      ?>


							
							<a class="add-to-playlist-button" href="#"></a>
							<div class="wishlist-popover">
								<div class="playlist-options">
									<ul>
										<li><a href="#">Create New Queue</a></li>
										<li><a href="#">Playlist 1</a></li>
										<li><a href="#">Playlist 2</a></li>
										<li><a href="#">Playlist 3</a></li>
										<li><a href="#">Playlist 4</a></li>
									</ul>
								</div>
								
								<a class="add-to-playlist" href="#">Add To Queue</a>
								<a class="add-to-wishlist" href="#">Add To Wishlist</a>
								
								<div class="share clearfix">
									<p>Share via</p>
									<a class="facebook" href="#"></a>
									<a class="twitter" href="#"></a>
								</div>
								
							</div>
							
						</div>
						<div class="album-title">
							<a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>20)
                                                                echo substr($value['Song']['SongTitle'],0,20)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Song']['Artist'])>20)
                                                                                                                    echo substr($value['Song']['Artist'],0,20)."..."; 
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
				<ul>
					<?php
                                        
                                        $count  =   1;  
                                                                                                                        
					//for($d=1;$d<$count;$d++) {
// print_r($topDownload_videos_data); die;
                                        foreach($topDownload_videos_data as $key => $value){
                                            
                                            // $video_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             //$video_img =  Configure::read('App.Music_Path').$video_img;
                                             
                                              $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                              $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                                            
					?>
					<li>
						
						<div class="video-container">
							<a href="/artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $value['Video']['ProdID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
                                                        <img src="<?php echo $videoAlbumImage; ?>" alt="gangstasquad" width="423" height="250" />
                                                        </a>                                                  
							<div class="top-10-ranking"><?php echo $count; ?></div>
							 <?php
                                if($this->Session->read('patron')) {
                                ?>
<form method="post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download">
                                    <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
									<input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                        <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                                            <a href='#' class="add-to-wishlist" title="<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.");?>" onclick='videoDownloadAll(<?php echo $value["Video"]["ProdID"]; ?>);'><?php __('Download Now');?></a>
										</span>
								</form>	
<?php } else{ ?>
<a class="top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
<?php } ?>
							<!-- <a class="top-10-download-now-button" href="#">Download Now</a> -->
							<a class="add-to-playlist-button" href="#"></a>
							<div class="wishlist-popover">
								
								<a class="add-to-wishlist" href="#">Add To Wishlist</a>
								
								<div class="share clearfix">
									<p>Share via</p>
									<a class="facebook" href="#"></a>
									<a class="twitter" href="#"></a>
								</div>
								
							</div>
							
						</div>
						<div class="album-title">
							<a href="/artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $value['Video']['ProdID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Video']['VideoTitle'])>20)
                                                                echo substr($value['Video']['VideoTitle'],0,20)."..."; 
                                                                else echo $value['Video']['VideoTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Video']['ArtistText'])); ?>/<?=base64_encode($value['Video']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>20)
                                                                                                                    echo substr($value['Video']['Artist'],0,20)."..."; 
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