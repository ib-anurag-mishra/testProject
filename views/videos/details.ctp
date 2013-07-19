<section class="individual-videos-page">
		<div class="breadcrumbs"><span>Home</span> > <span>Wishlist</span></div>
		<div class="hero-container clearfix">
			<div class="hero-image-container">
                                <?php
                                        $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$VideosData[0]['File']['CdnPath']."/".$VideosData[0]['File']['SourceURL']);
                                        $videoImage = Configure::read('App.Music_Path').$videoArtwork;


                                        

                                ?>
				<img src="<?php echo $videoImage;?>" alt="<?php echo $VideosData[0]['Video']['VideoTitle']; ?>" width="555" height="323" />
<!--				<a class="download-now-button" href="#">Download Now</a>-->
        <?php
                            
                                    if($libraryDownload == '1' && $patronDownload == '1') 
                                    {

        ?>                                                
                                                        <span class="download-now-button">
                                                        <form method="Post" id="form<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                        <input type="hidden" name="ProdID" value="<?php echo $VideosData[0]["Video"]["ProdID"];?>" />
                                                        <input type="hidden" name="ProviderType" value="<?php echo $VideosData[0]["Video"]["provider_type"]; ?>" />
                                                        <span class="beforeClick" id="song_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>">
                                                        <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $VideosData[0]["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
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

                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                    ?> 
                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                    <?php 
                                                    } else { 
                                                    ?>
                                                            <span class="beforeClick" id="video_wishlist<?php echo $VideosData[0]["Video"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $VideosData[0]["Video"]["ProdID"]; ?>","<?php echo $featureVideo["Video"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                            <span class="afterClick" id="downloading_<?php echo $VideosData[0]["Video"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
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
			<div class="hero-detail">
				
				<h2 class="song-title">
					<?php echo $VideosData[0]['Video']['VideoTitle']; ?>
				</h2>
				<h3 class="artist-name">
					<a href="/artists/album/"<?php base64_encode($VideosData[0]['Video']['ArtistText']); ?>"><?php echo $VideosData[0]['Video']['ArtistText']; ?></a>
				</h3>
				<?php
                                        $duration       =    $VideosData[0]['Video']['FullLength_Duration']; 
                                        $duration_arr   = explode(":", $duration);
                                ?>
				<div class="release-information">
					<p>Release Information</p>
					<div class="release-date">Date: <?php echo date("M d, Y", strtotime($VideosData[0]['Video']['CreatedOn'])); ?></div>
					<div class="video-duration">Duration: <?php echo $duration_arr[0]." min ".$duration_arr[1]." sec"; ?></div>
					<div class="video-size">Size: 67.2 MB</div>
				</div>
			</div>
			
		</div>
		<section class="more-videos">
			<header>
				<h2>More Videos By <?php echo $VideosData[0]['Video']['ArtistText']; ?></h2>
			</header>
			<div class="more-videos-scrollable horiz-scroll">
				<ul>
					<?php						
						
						foreach($MoreVideosData as $key => $value)
						{		

                                                    //echo "<pre>"; print_r($value);
                                                    $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                    $videoImage = Configure::read('App.Music_Path').$videoArtwork;
								?>								
								<li>
									<div class="video-thumb-container">
										<img class="lazy" src="/app/webroot/img/lazy-placeholder.gif" data-original="<?php echo $videoImage; ?>" width="274" height="162" />
										<!--				<a class="download-now-button" href="#">Download Now</a>-->
                                <?php

                                                            if($libraryDownload == '1' && $patronDownload == '1') 
                                                            {

                                ?>                                                
                                                                                <span class="download-now-button">
                                                                                <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                                                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                                                <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                                                                                <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $value["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
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
											
											<!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a>  -->
                                                                                    <?php

                                                                                               $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);

                                                                                               if($wishlistInfo == 'Added to Wishlist') {
                                                                                               ?> 
                                                                                                       <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                               <?php 
                                                                                               } else { 
                                                                                               ?>
                                                                                                       <span class="beforeClick" id="video_wishlist<?php echo $value["Video"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $value["Video"]["ProdID"]; ?>","<?php echo $featureVideo["Video"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
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
										
									</div>
									<div class="song-title">
										<a href="/artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Video']['ReferenceID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
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
                                                                                <a href="/artists/album/"<?php base64_encode($VideosData['Video']['ArtistText']); ?>">
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
                                                $videoArtwork = shell_exec('perl files/tokengen ' . "sony_test/".$value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                $videoImage = Configure::read('App.Music_Path').$videoArtwork;

							?>
							
							<li>
								<div class="video-thumb-container">
									<img class="lazy" src="<?php echo $videoImage; ?>" width="274" height="162" />
									<!--				<a class="download-now-button" href="#">Download Now</a>-->
                                <?php

                                                            if($libraryDownload == '1' && $patronDownload == '1') 
                                                            {

                                ?>                                                
                                                                                <span class="download-now-button">
                                                                                <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                                                                                <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                                                                                <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                                                                                <span class="beforeClick" id="song_<?php echo $value["Video"]["ProdID"]; ?>">
                                                                                <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $value["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
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
										
										
										
										
                                                                        <!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a>  -->
                                                                            <?php

                                                                                       $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);

                                                                                       if($wishlistInfo == 'Added to Wishlist') {
                                                                                       ?> 
                                                                                               <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                       <?php 
                                                                                       } else { 
                                                                                       ?>
                                                                                               <span class="beforeClick" id="video_wishlist<?php echo $value["Video"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $value["Video"]["ProdID"]; ?>","<?php echo $featureVideo["Video"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
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
									
								</div>
								<div class="song-title">
									<a href="/artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Video']['ReferenceID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>"><?php 
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
									<a href="/artists/album/"<?php base64_encode($VideosData['Video']['ArtistText']); ?>">
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
		<section class="recommended-for-you">
			
		</section>
		
	</section>