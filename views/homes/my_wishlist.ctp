<section class="my-wishlist-page">
		
		<div class="breadcrumbs"><span>Home</span> > <span>Wishlist</span></div>
		<header class="clearfix">
			<h2>My Wishlist</h2>
			<div class="faq-link">Need help? Visit our <a href="#">FAQ section.</a></div>
		</header>
		<div class="instructions">
			<p>
				In the event that your library exceeds its download budget for the week, you will see "add to wishlist" in place of the "download now" command. Adding your music to the wishlist will place you in a "first come, first serve" line to get more music when it becomes available, which is at midnight Sunday Eastern Time (U.S.). At that point your music is on hold for you for 24 hours (so no need to set your alarm clock) for you to proactively download. You should visit the Wishlist area on the top part of the home page to see the music that you requested, and if it is available.
			</p>
			<p>
				If you do not see the "download now" command in the Wish List area, it means so many people were waiting in line that you need to check back on a subsequent Monday.
			</p>
		</div>
		<nav class="my-wishlist-filter-container clearfix">
			<div class="date-filter-button filter">Date</div>
			<div class="song-filter-button filter">Song</div>
			<div class="music-filter-button tab active">Music</div>
			<div class="video-filter-button tab">Video</div>
			<div class="artist-filter-button filter">Artist</div>
			<div class="album-filter-button filter">Album</div>
			<div class="download-button filter">Download</div>
			
		</nav>
		<div class="my-wishlist-shadow-container">
			<div class="my-wishlist-scrollable">
				<div class="row-container">
				<?php
				for($b=0;$b<28;$b++) {
				?>
				
				<div class="row clearfix">
					<div class="date">2013-06-13</div>
					<a class="preview" href="#"></a>
					<!--
					<div class="small-album-container">
						<img src="images/playlist/small-album-cover.jpg" alt="small-album-cover" width="40" height="40" />
						<a class="preview" href="#"></a>
					</div>
					-->
					<div class="song-title">Grow Up</div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">Sticks and Stones</a></div>
					<div class="artist-name"><a href="#">Cher Lloyd</a></div>
					
					<div class="wishlist-popover">
						<!--	
						<a class="remove-song" href="#">Remove Song</a>
						<a class="make-cover-art" href="#">Make Cover Art</a>
						-->
						<a class="add-to-playlist" href="#">Add To Queue</a>
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
						<div class="playlist-options">
							<ul>
								<li><a href="#" class="create-new-queue">Create New Queue</a></li>
								<li><a href="#">Queue 1</a></li>
								<li><a href="#">Queue 2</a></li>
								<li><a href="#">Queue 3</a></li>
								<li><a href="#">Queue 4</a></li>
								<li><a href="#">Queue 5</a></li>
								<li><a href="#">Queue 6</a></li>
								<li><a href="#">Queue 7</a></li>
								<li><a href="#">Queue 8</a></li>
								<li><a href="#">Queue 9</a></li>
								<li><a href="#">Queue 10</a></li>
								
								
							</ul>
						</div>
						
					</div>
					<div class="download"><a href="#">Download</a></div>
					<div class="delete-btn"></div>
				</div>
				<?php 
				}
				?>
				</div>
			</div>
		</div>
		(this is the html for the videos)
		<div class="my-video-wishlist-shadow-container">
			<div class="my-video-wishlist-scrollable">
				<div class="row-container">
				<?php
				for($b=0;$b<28;$b++) {
				?>
				
				<div class="row clearfix">
					<div class="date">2013-06-13</div>
					<div class="small-album-container">
						<img src="images/my-wishlist/video-cover.jpg" alt="video-cover" width="67" height="40" />
						<!-- <a class="preview" href="#"></a> -->
					</div>
					<div class="song-title">Grow Up</div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">Sticks and Stones</a></div>
					<div class="artist-name"><a href="#">Cher Lloyd</a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download"><a href="#">Download</a></div>
					<div class="delete-btn"></div>
				</div>
				<?php 
				}
				?>
				</div>
			</div>
		</div>


	</section>
