<section class="individual-videos-page">
		<div class="breadcrumbs">
                <span>Home</span> > <span>Video</span>
                 <?php
                         
                            $html->addCrumb(__('Video', true), 'javascript:void(0);');
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                  ?>
                </div>
		<div class="hero-container clearfix">
			<div class="hero-image-container">
                                <?php
                                       // $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$VideosData[0]['File']['CdnPath']."/".$VideosData[0]['File']['SourceURL']);
                                       // $videoImage = Configure::read('App.Music_Path').$videoArtwork;


                                        

                                ?>
				<img src="<?php echo $VideosData[0]['videoImage'];?>" alt="<?php echo $VideosData[0]['Video']['VideoTitle']; ?>" width="555" height="323" />
<!--				<a class="download-now-button" href="#">Download Now</a>-->
                <?php
                            
                                   if($this->Session->read('patron'))
                                    {
                                           if(strtotime($VideosData[0]['Country']['SalesDate']) < time()){
                                            if($libraryDownload == '1' && $patronDownload == '1') 
                                            {

                ?>                                                
                                                                <span class="download-now-button">
                                                                <form method="Post" id="form<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                                <input type="hidden" name="ProdID" value="<?php echo $VideosData[0]["Video"]["ProdID"];?>" />
                                                                <input type="hidden" name="ProviderType" value="<?php echo $VideosData[0]["Video"]["provider_type"]; ?>" />
                                                                <span class="beforeClick" id="song_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>">
                                                                <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $VideosData[0]["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                                                                </span>
                                                                <span class="afterClick" id="downloading_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                                                                <span id="download_loader_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                                </form>
                                                                </span>
                            <?php

                                         }
                                   
                            ?>

                                            <a class="add-to-playlist-button" href="#"></a>
                                            <div class="wishlist-popover">
					<!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a>  -->
                                                <?php

                                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($VideosData[0]["Video"]["ProdID"]);
                                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$VideosData[0]["Video"]["ProdID"],$VideosData[0]["Video"]["provider_type"]);
                                                    echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                                ?>                                                    
                                            </div>
                                          <?php
                                            } else {
                                            ?>    
                                            <span class="download-now-button"><a  href='javascript:void(0);'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('Coming Soon');?>'><?php __('Coming Soon');?></label></a></span>
                            <?php                
                                            }
                            
                                     }
                                    else
                                    {
                                        ?>
                                            <span class="download-now-button">
                                                 <a class="featured-video-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                            </span>
                                        <?php
                                    }
                            ?>
                                            
				
			</div>
			<div class="hero-detail">
				
				<h2 class="song-title">
					<?php echo $VideosData[0]['Video']['VideoTitle']; ?>
				</h2>
				<h3 class="artist-name">
					<a href="/artists/album/<?php echo base64_encode($VideosData[0]['Video']['ArtistText']); ?>"><?php echo $VideosData[0]['Video']['ArtistText']; ?></a>
				</h3>
				<?php
                                        $duration       =    $VideosData[0]['Video']['FullLength_Duration']; 
                                        $duration_arr   = explode(":", $duration);
                                ?>
				<div class="release-information">
					<p><?php echo __('Release Information', true); ?> </p>
					<div class="release-date">Date: <?php echo date("M d, Y", strtotime($VideosData[0]['Video']['CreatedOn'])); ?></div>
					<div class="video-duration">Duration: <?php echo $duration_arr[0]." min ".$duration_arr[1]." sec"; ?></div>
					<div class="video-size">Size: 67.2 MB</div>
				</div>
			</div>
			
		</div>
		<section class="more-videos">
			<header>
				<h2><?php echo __('More Videos By', true); ?> <?php echo $VideosData[0]['Video']['ArtistText']; ?></h2>
			</header>
			<div class="more-videos-scrollable horiz-scroll">
				<ul style="width:2900px;">
					<?php						
						
						foreach($MoreVideosData as $key => $value)
						{		

                                                    //echo "<pre>"; print_r($value);
                                                    //$videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                    //$videoImage = Configure::read('App.Music_Path').$videoArtwork;
								?>								
								<li>
									<div class="video-thumb-container">
                                                                            <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><img class="lazy" src="<?php echo $value['videoImage']; ?>" data-original="" width="274" height="162" /></a>
										<!--				<a class="download-now-button" href="#">Download Now</a>-->
                                <?php
                                              if($this->Session->read('patron'))
                                              {
                                                  if(strtotime($value['Country']['SalesDate']) < time()){

                                                            if($libraryDownload == '1' && $patronDownload == '1') 
                                                            {

                                ?>                                                
                                                                                <span class="download-now-button">
                                                                                <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                                                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                                                <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                                                                                <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $value["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                                                                                </span>
                                                                                <span class="afterClick" id="downloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                                                                                <span id="download_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                                                </form>
                                                                                </span>
                                            <?php

                                                            }
                                            ?>
										<a class="add-to-playlist-button" href="#"></a>
										<div class="wishlist-popover">
											
                                                                                    <?php

                                                                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']['ProdID']);
                                                                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$value['Video']['ProdID'],$value['Video']["provider_type"]);
                                                                                        echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                                                                    ?> 
											
										</div>
                                                                <?php
                                                  } else {
                                                      ?>
                                                      <span class="download-now-button"><a  href='javascript:void(0);'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('Coming Soon');?>'><?php __('Coming Soon');?></label></a></span>
                                        <?php
                                                  }
                                                    }
                                                    else
                                                    {                                                     
                                                        ?>
                                                            <span class="download-now-button">
                                                             <a class="featured-video-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                                            </span>
                                                        <?php                                                       
                                                    }
                                              ?>
										
									</div>
									<div class="song-title">
										<a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                                                                <?php 
                                                                                                if (strlen($value['Video']['VideoTitle']) >= 35 ) {
                                                                                                            $VideoTitle = $this->getTextEncode(substr($value['Video']['VideoTitle'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $VideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
                                                                                                    }    
                                                                                                echo $VideoTitle; 
                                                                                            ?>
                                                                                </a>
									</div>
									<div class="artist-name">										
                                                                                <a href="/artists/album/<?php echo base64_encode($VideosData['Video']['ArtistText']); ?>">
                                                                                <?php 
                                                                                        if (strlen($value['Video']['ArtistText']) >= 35 ) {
                                                                                                    $VideoArtist = $this->getTextEncode(substr($value['Video']['ArtistText'], 0, 35)) . "..";
                                                                                            } else {
                                                                                                    $VideoArtist = $this->getTextEncode($value['Video']['ArtistText']);
                                                                                            }    
                                                                                        echo $VideoArtist; 
                                                                                 ?>
                                                                                </a>
									</div>
								</li>
								
								
								<?php
							
						
						}
					?>
					
				</ul>
			</div>
		</section>
		
		<section class="top-videos">
			<header>
				<h2>Top <span><?php echo $VideoGenre; ?></span> Videos</h2>
			</header>
			<div class="top-videos-scrollable horiz-scroll">
			<ul>
				<?php
					foreach($TopVideoGenreData as $key => $value)
                                        {

                                              //  echo "<pre>"; print_r($value);
                                                //$videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                //$videoImage = Configure::read('App.Music_Path').$videoArtwork;

							?>
							
							<li>
								<div class="video-thumb-container">
                                                                    <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><img class="lazy" src="<?php echo $value['videoImage']; ?>" width="274" height="162" /></a>
									<!--				<a class="download-now-button" href="#">Download Now</a>-->
                                <?php

                                                    if($this->Session->read('patron'))
                                                   {
                                
                                                            if($libraryDownload == '1' && $patronDownload == '1') 
                                                            {

                                ?>                                                
                                                                                <span class="download-now-button">
                                                                                <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                                                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                                                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                                                <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                                                                                <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $value["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                                                                                </span>
                                                                                <span class="afterClick" id="downloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                                                                                <span id="download_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                                                                                </form>
                                                                                </span>
                                            <?php

                                                            }
                                            ?>
									<a class="add-to-playlist-button" href="#"></a>
									<div class="wishlist-popover">
                                                                            <?php

                                                                                $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value['Video']['ProdID']);
                                                                                echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$value['Video']['ProdID'],$value['Video']["provider_type"]);
                                                                                echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                                                            ?>
										
									</div>
                                                                 <?php
                                                    }
                                                    else
                                                    {                                                     
                                                        ?>
                                                            <span class="download-now-button">
                                                             <a class="featured-video-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                                            </span>
                                                        <?php                                                       
                                                    }
                                              ?>
									
								</div>
								<div class="song-title">
									<a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>"><?php 
                                                                                                if (strlen($value['Video']['VideoTitle']) >= 35 ) {
                                                                                                            $VideoTitle = $this->getTextEncode(substr($value['Video']['VideoTitle'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $VideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);
                                                                                                    }    
                                                                                                echo $VideoTitle; 
                                                                                            ?>
                                                                                </a>
								</div>
								<div class="artist-name">
									<a href="/artists/album/<?php echo base64_encode($VideosData['Video']['ArtistText']); ?>">
                                                                             <?php 
                                                                                        if (strlen($value['Video']['ArtistText']) >= 35 ) {
                                                                                                    $VideoArtist = $this->getTextEncode(substr($value['Video']['ArtistText'], 0, 35)) . "..";
                                                                                            } else {
                                                                                                    $VideoArtist = $this->getTextEncode($value['Video']['ArtistText']);
                                                                                            }    
                                                                                        echo $VideoArtist; 
                                                                                 ?>
                                                                         </a>
								</div>
							</li>
							
							
							<?php
					
					
					}
				?>
				
			</ul>
		</div>
		

		</section>		
		
	</section>