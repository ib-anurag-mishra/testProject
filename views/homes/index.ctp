
						
						<section class="news">
							<div class="top-100">
								<header>
									<h3>National Top 100</h3>
									
								</header>
								<nav class="top-100-nav">
									<ul>
										<!--
										<li>
											<a href="#top-100-albums" data-category-type="albums">Albums</a>
										</li>
										-->
										<li>
											<a href="#top-100-songs" class="active" data-category-type="songs">Songs</a>
										</li>
										<li>
											<a href="#top-100-videos" data-category-type="videos">Videos</a>
										</li>
									</ul>
									
									
									
								</nav>
								<div class="grids">
									
									<div id="top-100-songs-grid" class="top-100-grids horiz-scroll">
										<ul style="width:27064px;">

                                                                               <?php if(is_array($nationalTopDownload) && count($nationalTopDownload) > 0){ ?>

										<?php
											$j = 0;
											$k = 2000;
											for($i = 0; $i < count($nationalTopDownload); $i++) {
											if($j==5){
												break;
											}
											$albumArtwork = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['File']['CdnPath']."/".$nationalTopDownload[$i]['File']['SourceURL']);
                                                                                        $songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

 /* echo $this->webroot."app/webroot/img/news/top-100/grid/bradpaisley250x250.jpg"; */ 
										?>
											<li>
												<div class="top-100-songs-detail">
													<div class="song-cover-container">
														<a href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $songAlbumImage; ?>"  width="250" height="250" /></a>
														<div class="top-100-ranking"><?php
												$slNo = ($i + 1);
												echo $slNo;
											?></div>
														
<?php if($this->Session->read("patron")){ ?> 
<!-- <a href="#" class="preview"></a>  -->
<?php           
            if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.base64_encode($nationalTopDownload[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                    echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
            }
?>
<?php } ?>


												


<?php

    if($this->Session->read('patron')) {
        if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $nationalTopDownload[$i]['Song']['status'] = 'avail1';
                    if(isset($nationalTopDownload[$i]['Song']['status']) && ($nationalTopDownload[$i]['Song']['status'] != 'avail')) {
                            ?>
        <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopDownload[$i]["Song"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
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
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
      ?>



                                                                                                    <?php if($this->Session->read("patron")){ ?> 
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
                                                                                                <?php if( $this->Session->read('library_type') == 2 ){ ?> 
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
                                                                                                <?php } ?>
														

                                                                                                                    <?php
                                                                                                                    
                                                                                                                    $wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);

                                                                                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                    ?> 
                                                                                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                    <?php 
                                                                                                                    } else { 
                                                                                                                    ?>
                                                                                                                            <span class="beforeClick" id="wishlist<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>","<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                            <span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
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

                                                                                                    <?php											
                                                                                                    if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 35 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
                                                                                                    }
                                                                                                    ?>


                                                                                                    <?php											
                                                                                                    if (strlen($nationalTopDownload[$i]['Song']['ArtistText']) >= 35 ) {
                                                                                                            $artistText = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $artistText = $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']);
                                                                                                    }
                                                                                                    ?>


													<div class="song-title">
														<a href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><?php echo $songTitle; ?></a>
													</div>
													<div class="artist-name">
														<a href="/artists/album/"<?php base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>"><?php echo $artistText; ?></a>
													</div>
												</div>
											</li>

                                                                                    <?php 
											$k++;
											}
                                                                                    }
                                                                                     ?>	
										</ul>
									</div>
									<div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
										<ul style="width:47000px;">

                                                    <?php if(is_array($nationalTopVideoDownload) && count($nationalTopVideoDownload) > 0){ ?>

										<?php
											$j = 0;
											$k = 2000;
											for($i = 0; $i < count($nationalTopVideoDownload); $i++) {
	
											$albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$nationalTopVideoDownload[$i]['Image_Files']['CdnPath']."/".$nationalTopVideoDownload[$i]['Image_Files']['SourceURL']);
                                                                                        $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

 /* echo $this->webroot."app/webroot/img/news/top-100/grid/bradpaisley250x250.jpg"; */ 
										?>
											<li>
												<div class="top-100-video-detail">
													<div class="video-cover-container">
														<a href="javascript:void(0);"><img src="<?php echo $videoAlbumImage; ?>" alt="jlo423x250" width="423" height="250" /></a>
														<div class="top-100-ranking"><?php
												$slNo = ($i + 1);
												echo $slNo;
											?></div>
<?php if($this->Session->read("patron")){ ?> 														
<a href="#" class="preview"></a>
<?php } ?>


														


<?php

    if($this->Session->read('patron')) {
        if($nationalTopVideoDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $nationalTopVideoDownload[$i]['Video']['status'] = 'avail1';
                    if($nationalTopVideoDownload[$i]['Video']['status'] != 'avail' ) {
                            ?>
                            <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
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
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopVideoDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopVideoDownload[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
    ?>


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


                                                                                                                     $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($nationalTopVideoDownload[$i]['Video']["ProdID"]);

                                                                                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                    ?> 
                                                                                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                    <?php 
                                                                                                                    } else { 
                                                                                                                    ?>
                                                                                                                            <span class="beforeClick" id="video_wishlist<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>","<?php echo $nationalTopVideoDownload[$i]['Video']["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                            <span class="afterClick" id="downloading_<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
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

                                                                                                <?php											
                                                                                                    if (strlen($nationalTopVideoDownload[$i]['Video']['VideoTitle']) >= 50 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopVideoDownload[$i]['Video']['VideoTitle'], 0, 50)) . "..";
                                                                                                    } else {
                                                                                                            $songTitle = $this->getTextEncode($nationalTopVideoDownload[$i]['Video']['VideoTitle']);
                                                                                                    }
                                                                                                ?>

                                                                                                <?php											
                                                                                                    if (strlen($nationalTopVideoDownload[$i]['Video']['ArtistText']) >= 50 ) {
                                                                                                            $ArtistText = $this->getTextEncode(substr($nationalTopVideoDownload[$i]['Video']['ArtistText'], 0, 50)) . "..";
                                                                                                    } else {
                                                                                                            $ArtistText = $this->getTextEncode($nationalTopVideoDownload[$i]['Video']['ArtistText']);
                                                                                                    }
                                                                                                ?>
													<div class="song-title">
													<!--	<a href="/artists/view/<?=base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']);?>/<?= $nationalTopVideoDownload[$i]['Video']['ReferenceID']; ?>/<?= base64_encode($nationalTopVideoDownload[$i]['Video']['provider_type']);?>"><?php echo $songTitle;?></a> -->
                                                                                                        <a href="javascript:void(0);"><?php echo $songTitle;?></a>
													</div>
													<div class="artist-name">
														<!-- <a href="/artists/album/"<?php base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']); ?>"><?php echo $nationalTopVideoDownload[$i]['Video']['ArtistText']; ?></a> -->
                                                                                                                <a href="javascript:void(0);"><?php echo $ArtistText; ?></a>
													</div>
												</div>
											</li>
											<?php 
											$k++;
											}
                                                                                    }
                                                                                     ?>	
										</ul>
									</div>
								</div> <!-- end .grids -->
								
							</div>
							<div class="featured">
								<header>
									<h3>Featured</h3>
								</header>
								<div class="featured-grid horiz-scroll">
									<ul style="width:3690px;">
										<?php
								foreach($featuredArtists as $k => $v){
								
									$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
									$image =  Configure::read('App.Music_Path').$albumArtwork;
									if(strlen($v['Album']['AlbumTitle']) > 14){
										$title = substr($v['Album']['AlbumTitle'], 0, 14)."..";
									}else{
										$title = $v['Album']['AlbumTitle'];
									}

                                                                        if(strlen($v['Album']['ArtistText']) > 14){
										$ArtistText = substr($v['Album']['ArtistText'], 0, 14)."..";
									}else{
										$ArtistText = $v['Album']['ArtistText'];
									}


									?>								
										
										<li>
											<div class="featured-album-detail">
												<div class="album-cover-container">												

                                                                        <a href="/artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"><?php echo $html->image($image,array("height" => "77", "width" => "84"));?></a>


												</div>
												<div class="album-title">
													<a href="/artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"><?php echo $title; ?></a>
												</div>


												<div class="artist-name">
													<a href="/artists/album/<?php echo str_replace('/','@',base64_encode($v['Album']['ArtistText'])); ?>/<?=base64_encode($v['Genre']['Genre'])?>"><?php echo $ArtistText; ?></a>
												</div>
											</div>
										</li>
										
								<?php
								}
								?>	
										
										
									</ul>
								</div>
							</div><!-- end .featured -->
							<div class="coming-soon">
								<header class="clearfix">
									<h3>Coming Soon</h3>
									
	
								</header>
								<div class="coming-soon-filter-container clearfix">
									<nav class="category-filter">
										<ul class="clearfix">
											<!-- <li><a href="#coming-soon-album-grid">Albums</a></li> -->
											<li><a href="#coming-soon-singles-grid" class="active" >Singles</a></li>
											<li><a href="#coming-soon-videos-grid">Videos</a></li>
										</ul>
										
									</nav>
									<!-- <a href="#" class="view-all">View All</a> -->
	
								</div>
								<?php 
                                                                        
                                                                ?>
								
								
								<div id="coming-soon-singles-grid" class="horiz-scroll">
									<ul class="clearfix">
                                                                            <?php  
                                                                            $total_songs = count($coming_soon_rs);
                                                                            $sr_no = 0;

                                                                            foreach($coming_soon_rs as $key => $value)
                                                                            {     
                                                                            $cs_img_url = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                                            $cs_songImage =  Configure::read('App.Music_Path').$cs_img_url;
                                                                            
                                                                              if($sr_no>=20) break;
                                                                          
                                                                              ?>
										<?php if($sr_no%2==0) {?><li> <?php }?>
											<div class="single-detail">
												<div class="single-cover-container">
																										
                                                                                                        <a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                                                                        <img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $cs_songImage; ?>" alt="pitbull162x162" width="162" height="162" /></a>
                                                                                                         
                                                                                                <?php if($this->Session->read("patron")){ ?> 													
                                                                                                <a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover"> 
                                           

                                                                                                            <div class="share clearfix">
                                                                                                                    <p>Share via</p>
                                                                                                                    <a class="facebook" href="#"></a>
                                                                                                                    <a class="twitter" href="#"></a>
                                                                                                            </div>
														
													</div>

                                                                                                <?php } ?>
												</div>
												<div class="song-title">
													<a href="artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
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
											</div>
											
										<?php if($sr_no%2==1 || $sr_no==($total_songs-1)) {?> </li> <?php } ?>

                                                                        <?php
                                                                                $sr_no++;
                                                                        }
                                                                        ?>
										
									</ul>
								</div> <!-- end #coming-soon-singles-grid -->
									<div id="coming-soon-videos-grid" class="clearfix horiz-scroll">
									<ul class="clearfix" style="width:3333px;">										
                                                                            <?php                                                                              
                                                                            $total_videos = count($coming_soon_videos);
                                                                            $sr_no = 0;
                                                                            foreach($coming_soon_videos as $key => $value)
                                                                            {     
                                                                            //$cs_img_url = shell_exec('perl files/tokengen ' . $value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                                                                           // $cs_songImage =  Configure::read('App.Music_Path').$cs_img_url;

                                                                           $albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                                                                           $videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                                                                            if($sr_no>=20) break;
?>
                                                                            <?php if($sr_no%2==0) {?><li> <?php }?>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="javascript:void(0);">
                                                                                                        <img class="lazy" src="<?php echo $videoAlbumImage; ?>"  alt="rockband275x162" width="275" height="162" />
                                                                                                        </a>
												<?php if($this->Session->read("patron")){ ?> 
                                                                                                        <a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">														
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
                                                                                                <?php } ?>
												</div>
												<div class="video-title">

                                                                                                        <a href="javascript:void(0);">
                                                                                                            <?php
                                                                                                                    if(strlen($value['Video']['VideoTitle'])>20)
                                                                                                                    echo substr($value['Video']['VideoTitle'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['VideoTitle'];
                                                                                                         ?> </a>

												<!--	 <a href="artists/view/<?=base64_encode($value['Video']['ArtistText']);?>/<?= $value['Video']['ProdID']; ?>/<?= base64_encode($value['Video']['provider_type']);?>">
                                                                                                            <?php
                                                                                                                    if(strlen($value['Video']['VideoTitle'])>20)
                                                                                                                    echo substr($value['Video']['VideoTitle'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['VideoTitle'];
                                                                                                             ?> </a> -->
												</div>
												<div class="artist-name">


                                                                                                            <a href="javascript:void(0)">
                                                                                                         <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>20)
                                                                                                                    echo substr($value['Video']['Artist'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['Artist'];
                                                                                                             ?></a>

												<!--	<a href="artists/album/<?php echo str_replace('/','@',base64_encode($value['Video']['ArtistText'])); ?>/<?=base64_encode($value['Video']['Genre'])?>">
                                                                                                         <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>20)
                                                                                                                    echo substr($value['Video']['Artist'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['Artist'];
                                                                                                             ?></a>  -->
												</div>
											</div>
											
										<?php if($sr_no%2==1 || $sr_no==($total_videos-1)) {?> </li> <?php } ?>

                                                                                <?php
                                                                                        $sr_no++; 
                                                                                }
                                                                                ?>
										
									</ul>
								</div><!-- end videos grid -->
																
							</div> <!-- end coming soon -->
							
							<div class="whats-happening">
								<header>
									<h3>What's Happening</h3>
									<!--
									<div class="whats-happening-see-all">
										<a href="#">View All</a>
									</div>
									-->
								</header>
								
								
								
								<div id="whats-happening-grid" class="horiz-scroll">
									<ul class="clearfix" style="width:4400px;">
										<?php $count = 1;
									foreach($news as $key => $value)
									{
										  $newsText = str_replace('<div', '<p', ($value['News']['body']));
                                                                                  $newsText = str_replace('</div>', '</p>', $newsText);
									?>
										<li>
											<div class="post">
												<div class="post-header-image">
													<a href="javascript:void(0);"><img src ='<?php echo $cdnPath. 'news_image/' . $value['News']['image_name'];?>' style="width:417px;height:196px;" /></a>
												</div>
												<div class="post-title">
													<a href="javascript:void(0);"><?php echo $value['News']['subject'] ?></a>
												</div>
												<div class="post-date">
													<?php echo $value['News']['place']?> : <?php echo date( "F d, Y", strtotime($value['News']['created'])) ?>
												</div>
												<div class="post-excerpt"  id="shortNews<?php echo $value['News']['id']; ?>">
													 <?php 
                                                                                                                echo $this->getTextEncode(substr($newsText,0, 325)); 
                                                                                                                //echo $this->getTextEncode(substr($newsText,0, strpos($newsText, "</p>")+4));
                                                                                                            ?>		
													 <div class="more">
													 <?php  if(strlen($newsText) > strpos($newsText, "</p>")+4)
													  {
														?>
														<a href="javascript:void(0);" onClick="showhide('detail', '<?php echo $value['News']['id']; ?>')")">More ></a>
														<?php
													  } ?>		</div>									
												</div>
												
												<div id="detailsNews<?php echo $value['News']['id']; ?>" style="display:none" class="post-excerpt">
												<?php echo $newsText; ?>
								 				 <a href="javascript:void(0);" class="more" onClick="showhide('short', '<?php echo $value['News']['id']; ?>')">- See Less</a>
												</div>
												
											</div>
										</li>
										
										<?php
												if($count==10) break;
												$count++;
										}
									?>
									</ul>
									
									
									
								</div>
							
							
							
						</section> <!-- end .news -->	

