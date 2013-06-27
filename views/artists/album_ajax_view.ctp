		<section class="album-detail">
				<div class="album-cover-image">
					<img src="/app/webroot/img/genres/album-detail-cover.jpg" alt="album-detail-cover" width="250" height="250" />
				</div>
				<!-- <a href="#" class="more-by">More by Al Lee, Ben Andrews</a> -->
				<div class="album-title">13 Shades Of Blue, Best Of Mapleshade Vol. 2</div>
				<div class="album-genre">Genre: <span><a href="#">Blues</a></span></div>
				<div class="album-label">Label: <span>Mapleshade Records</span></div>
				
			</section>
			<section class="tracklist-container">
				
				<div class="tracklist-header"><span class="song">Song</span><span class="artist">Artist</span><span class="time">Time</span></div>
				<?php
					$tracklist_array = array('Grow Up','Swagger Jagger','End Up Here','Want U Back','With Ur Love','Behind The Music','Oath','Beautiful People','End Up Here','Want U Back','With Ur Love','Behind The Music','Oath','Beautiful People');
					
					for($a=0;$a<count($tracklist_array);$a++) {
				?>	
					
					<div class="tracklist">
						<a href="#" class="preview"></a>
						<div class="song"><?php echo $tracklist_array[$a]; ?></div>
						<div class="artist"><a href="#">Al Lee, Ben Andrews</a></div>
						<div class="time">3:27</div>
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
					}
				?>
				
						
					
			</section>