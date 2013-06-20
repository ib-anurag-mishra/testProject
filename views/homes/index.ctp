
						
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
											<a href="#top-100-songs" data-category-type="songs">Songs</a>
										</li>
										<li>
											<a href="#top-100-videos" data-category-type="videos">Videos</a>
										</li>
									</ul>
									
									
									
								</nav>
								<div class="grids">
									
									<div id="top-100-songs-grid" class="top-100-grids horiz-scroll">
										<ul>

                                                        <?php if(count($nationalTopDownload) > 0){ ?>
										<?php
											$j = 0;
											$k = 2000;
											for($i = 0; $i < count($nationalTopDownload); $i++) {
											if($j==5){
												break;
											}
											$albumArtwork = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['File']['CdnPath']."/".$nationalTopDownload[$i]['File']['SourceURL']);
                                                                                        $songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
										?>
											<li>
												<div class="top-100-songs-detail">
													<div class="song-cover-container">
														<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $songAlbumImage; ?>" alt="bradpaisley250x250" width="250" height="250" /></a>
														<div class="top-100-ranking"><?php
												$slNo = ($i + 1);
												echo $slNo;
											?></div>
														<a href="#" class="preview"></a>
														<a class="top-100-download-now-button" href="#">Download Now</a>
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
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
															<a class="add-to-wishlist" href="#">Add To Wishlist</a>
															
															<div class="share clearfix">
																<p>Share via</p>
																<a class="facebook" href="#"></a>
																<a class="twitter" href="#"></a>
															</div>
															
														</div>
													</div>

                                                                                                    <?php											
                                                                                                    if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 35 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 35)) . "..";
                                                                                                    } else {
                                                                                                            $songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
                                                                                                    }
                                                                                                    ?>


													<div class="song-title">
														<a href="artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><?php echo $songTitle; ?></a>
													</div>
													<div class="artist-name">
														<a href="/artists/album/"<?php base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>"><?php echo $nationalTopDownload[$i]['Song']['ArtistText']; ?></a>
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
										<ul>
											<li>
												<div class="top-100-video-detail">
													<div class="video-cover-container">
														<a href="#"><img src="img/news/top-100/grid/calvinharris423x250.jpg" alt="calvinharris423x250" width="423" height="250" /></a>
														<div class="top-100-ranking">12</div>
														<a href="#" class="preview"></a>
														<a class="top-100-download-now-button" href="#">Download Now</a>
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
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
															<a class="add-to-wishlist" href="#">Add To Wishlist</a>
															
															<div class="share clearfix">
																<p>Share via</p>
																<a class="facebook" href="#"></a>
																<a class="twitter" href="#"></a>
															</div>
															
														</div>
													</div>
													<div class="song-title">
														<a href="#">Planet Pit</a>
													</div>
													<div class="artist-name">
														<a href="#">Pitbull</a>
													</div>
												</div>
											</li>
											
										</ul>
									</div>
								</div> <!-- end .grids -->
								
							</div>
							<div class="featured">
								<header>
									<h3>Featured</h3>
								</header>
								<div class="featured-grid horiz-scroll">
									<ul style="width:3865px;">
										<?php
								foreach($featuredArtists as $k => $v){
								
									$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
									$image =  Configure::read('App.Music_Path').$albumArtwork;
									if(strlen($v['Album']['AlbumTitle']) > 14){
										$title = substr($v['Album']['AlbumTitle'], 0, 14)."..";
									}else{
										$title = $v['Album']['AlbumTitle'];
									}


									?>								
										
										<li>
											<div class="featured-album-detail">
												<div class="album-cover-container">
												<?php echo $html->link($html->image($image,array("height" => "77", "width" => "84")),
										array('controller'=>'artists', 'action'=>'view', base64_encode($v['Album']['ArtistText']), $v['Album']['ProdID'] , base64_encode($v['Album']['provider_type'])),
										array('class'=>'first','escape'=>false))?>
													<a class="preview" href="artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"></a>
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
														<a class="download-now" href="artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>">Download Now</a>
														<a class="add-to-queue" href="#">Add To Queue</a>
														<a class="add-to-playlist" href="#">Add To Playlist</a>
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="album-title">
													<a href="artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"><?php echo $title; ?></a>
												</div>





												<div class="artist-name">
													<a href="artists/album/<?php echo str_replace('/','@',base64_encode($v['Album']['ArtistText'])); ?>/<?=base64_encode($v['Genre']['Genre'])?>"><?php echo $v['Album']['ArtistText']; ?></a>
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
											<li><a href="#coming-soon-singles-grid">Singles</a></li>
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
                                                                            

                                                                          
                                                                              ?>
										<?php if($sr_no%2==0) {?><li> <?php }?>
											<div class="single-detail">
												<div class="single-cover-container">
													
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="<?php echo $cs_songImage; ?>" alt="pitbull162x162" width="162" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="song-title">
													<a href="#">
                                                                                                            <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                                                                    if(strlen($value['Song']['SongTitle'])>20)
                                                                                                                    echo substr($value['Song']['SongTitle'],0,20)."..."; 
                                                                                                                    else echo $value['Song']['SongTitle'];
                                                                                                             ?>
                                                                                                        </a>
												</div>
												<div class="artist-name">
													<a href="#">                                                                                                        
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
									<ul class="clearfix">
										<li>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/calvinharris275x162.jpg" alt="calvinharris275x162" width="162" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/aerosmith275x162.jpg" alt="aerosmith275x162" width="162" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
										</li>
										<li>
											<div class="video-detail last">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/pink275x162.jpg" alt="pink275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/pink275x162.jpg" alt="pink275x162"  width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
										<li>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail last">
												<div class="video-cover-container">
													<a href="#"><img src="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
										</li>
										<li>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
										</li>
										<li>
											<div class="video-detail last">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles<a href="#">
												</div>
											</div>
										<li>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162"  width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
											<div class="video-detail last">
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="img/lazy-placeholder.gif" data-original="img/news/coming_soon/videos/lang275x162.jpg" alt="lang275x162" width="275" height="162" /></a>
													<a class="add-to-playlist-button" href="#">
														
													</a>
													<div class="wishlist-popover">
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Brave</a>
												</div>
												<div class="artist-name">
													<a href="#">Sara Bareilles</a>
												</div>
											</div>
										</li>
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
						
						
					<script src="<? echo $this->webroot; ?>app/webroot/js/lazyload.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/site.js"></script>

<script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mediaelement-and-player.min.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mep-feature-playlist-custom.js"></script>

	
</html>
