

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
							<!-- <a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['ProdID']);?>">
                                                        <img class="lazy" src="<?php echo $album_img; ?>" alt="pitbull162x162" width="250" height="250" />
                                                        </a> -->

                                                        <?php echo $html->link($html->image($album_img,array("height" => "250", "width" => "250")),
										array('controller'=>'artists', 'action'=>'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ProdID'] , base64_encode($value['Song']['provider_type'])),
										array('class'=>'first','escape'=>false))?>
							<div class="top-10-ranking"><?php echo $count; ?></div>
							
						</div>
						<div class="album-title">							
                                                        <a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>20)
                                                                echo substr($value['Song']['SongTitle'],0,20)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">							
                                                        <a href="artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
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
							<a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <img class="lazy"  src="<?php echo $songs_img; ?>" alt="pitbull162x162" width="250" height="250" />                                                        
                                                        </a>
							<div class="top-10-ranking"><?php echo $count; ?></div>
							<a href="#" class="preview"></a>
							<a class="top-10-download-now-button" href="#">Download Now</a>
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
							<a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ProdID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Song']['SongTitle'])>20)
                                                                echo substr($value['Song']['SongTitle'],0,20)."..."; 
                                                                else echo $value['Song']['SongTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
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
                                        foreach($topDownload_videos_data as $key => $value){
                                            
                                            // $video_img = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                             //$video_img =  Configure::read('App.Music_Path').$video_img;
                                             
                                              $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                              $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                                            
					?>
					<li>
						
						<div class="video-container">
							<a href="artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $value['Video']['ProdID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
                                                        <img src="<?php echo $videoAlbumImage; ?>" alt="gangstasquad" width="423" height="250" />
                                                        </a>                                                  
							<div class="top-10-ranking"><?php echo $count; ?></div>
							
							<a class="top-10-download-now-button" href="#">Download Now</a>
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
							<a href="artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $value['Video']['ProdID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
                                                        <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                if(strlen($value['Video']['VideoTitle'])>20)
                                                                echo substr($value['Video']['VideoTitle'],0,20)."..."; 
                                                                else echo $value['Video']['VideoTitle'];
                                                         ?>
                                                    </a>
						</div>
						<div class="artist-name">
							<a href="artists/album/<?php echo str_replace('/','@',base64_encode($value['Video']['ArtistText'])); ?>/<?=base64_encode($value['Video']['Genre'])?>">
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