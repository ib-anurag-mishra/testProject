<section class="videos">
							
							
							<section class="featured-videos">
								
								<header class="clearfix">
									
									<h3>Featured Videos</h3>
									
								</header>
								<section id="featured-video-grid" class="horiz-scroll">
									<ul class="clearfix">
										
											
											
										
									<?php
									
										$featured_video_array = array('images/videos/featured-videos/featured-video-aerosmith-274x162.jpg','images/videos/featured-videos/featured-video-aliciakeys-274x162.jpg','images/videos/featured-videos/featured-video-calvinharris-274x162.jpg','images/videos/featured-videos/featured-video-cherlloyd-274x162.jpg','images/videos/featured-videos/featured-video-kingsofleon-274x162.jpg','images/videos/featured-videos/featured-video-pink-274x162.jpg','images/videos/featured-videos/featured-video-aerosmith-274x162.jpg','images/videos/featured-videos/featured-video-aliciakeys-274x162.jpg','images/videos/featured-videos/featured-video-calvinharris-274x162.jpg','images/videos/featured-videos/featured-video-cherlloyd-274x162.jpg','images/videos/featured-videos/featured-video-kingsofleon-274x162.jpg','images/videos/featured-videos/featured-video-pink-274x162.jpg');
										
										for($c=0;$c<count($featured_video_array);$c+=2) {
											
											
											?>
											<li>
												<div class="featured-video-detail">
													<div class="video-thumbnail-container">
														<a href="#"><img class="lazy" src="images/lazy-placeholder.gif" data-original="<?php echo $featured_video_array[$c]; ?>" width="275" height="162" /></a>
														
														<a class="featured-video-download-now-button" href="#">Download Now</a>
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
													<div class="video-title">
														<a href="#">Planet Pit</a>
													</div>
													<div class="video-name">
														<a href="#">Pitbull</a>
													</div>
												</div>
												<div class="featured-video-detail">
													<div class="video-thumbnail-container">
														<a href="#"><img class="lazy" src="images/lazy-placeholder.gif" data-original="<?php echo $featured_video_array[$c+1]; ?>" width="275" height="162" /></a>
														
														<a class="featured-video-download-now-button" href="#">Download Now</a>
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
													<div class="video-title">
														<a href="#">Planet Pit</a>
													</div>
													<div class="video-name">
														<a href="#">Pitbull</a>
													</div>
												</div>
											
											</li>
											
											<?php
											
											
										}
										
										
									
									?>
									</ul>
								</section>
							</section> <!-- end .featured-videos -->
							<section class="video-top-genres">
								<header class="clearfix">
									<h3>Top Videos</h3>
									
								</header>
								
								<div class="video-top-genres-grid horiz-scroll">
									
									<ul class="clearfix">
										<?php
										$featured_video_array = array(
											'images/videos/top-genres/zztop-163x97.jpg',
											'images/videos/top-genres/ryanleslie-163x97.jpg',
											'images/videos/top-genres/pink-163x97.jpg',
											'images/videos/top-genres/kingsofleon-163x97.jpg',
											'images/videos/top-genres/girl-163x97.jpg',
											'images/videos/top-genres/zztop-163x97.jpg',
											'images/videos/top-genres/ryanleslie-163x97.jpg',
											'images/videos/top-genres/pink-163x97.jpg',
											'images/videos/top-genres/kingsofleon-163x97.jpg',
											'images/videos/top-genres/girl-163x97.jpg',
											'images/videos/top-genres/zztop-163x97.jpg',
											'images/videos/top-genres/ryanleslie-163x97.jpg',
											'images/videos/top-genres/pink-163x97.jpg',
											'images/videos/top-genres/kingsofleon-163x97.jpg',
											'images/videos/top-genres/girl-163x97.jpg',
											'images/videos/top-genres/zztop-163x97.jpg',
											'images/videos/top-genres/ryanleslie-163x97.jpg',
											'images/videos/top-genres/pink-163x97.jpg',
											'images/videos/top-genres/kingsofleon-163x97.jpg',
											'images/videos/top-genres/girl-163x97.jpg'
											
										);
										
										for($c=0;$c<count($featured_video_array);$c+=2) {
										?>
										
											<li>
												
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="images/lazy-placeholder.gif" data-original="<?php echo $featured_video_array[$c]; ?>" width="163" height="97" /></a>
													<a class="top-video-download-now-button" href="#">Download Now</a>
													<a class="add-to-playlist-button" href="#"></a>
													<div class="wishlist-popover">
														
														<a class="download-now" href="#">Download Now</a>
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
													
												</div>
												<div class="video-title">
													<a href="#">Planet Pit</a>
												</div>
												<div class="video-name">
													<a href="#">Pitbull</a>
												</div>
												<div class="video-cover-container">
													<a href="#"><img class="lazy" src="images/lazy-placeholder.gif" data-original="<?php echo $featured_video_array[$c+1]; ?>" width="163" height="97" /></a>
													<a class="add-to-playlist-button" href="#"></a>
													<div class="wishlist-popover">
														
														<a class="download-now" href="#">Download Now</a>
														<a class="add-to-wishlist" href="#">Add To Wishlist</a>
														
														<div class="share clearfix">
															<p>Share via</p>
															<a class="facebook" href="#"></a>
															<a class="twitter" href="#"></a>
														</div>
														
													</div>
												</div>
												<div class="video-title">
													<a href="#">Planet Pit</a>
												</div>
												<div class="video-name">
													<a href="#">Pitbull</a>
												</div>
											</li>
										
										<?php
										
										}
										
										?>
										
										
									</ul>
								</div>
							</section> <!-- end .video-top-genres -->

							
							
							
							
							
							
							
						</section> <!-- end .videos -->
						