<section class="now-streaming-page">
		<div class="breadcrumbs"><span>Home</span> > <span>Now Streaming</span></div>
		<header class="clearfix">
			<h2>Queue #1</h2>
			<div class="faq-link">Need help? Visit our <a href="#">FAQ section.</a></div>
		</header>
		<div class="album-info-playlist-container clearfix">
			<div class="album-info-container">
				<div class="album-cover-container">
					
					<img src="<? echo $this->webroot; ?>app/webroot/img/playlist/album-cover.jpg" alt="album-cover" width="155" height="155" />
					<a class="add-to-playlist-button" href="#"></a>
					<div class="wishlist-popover">
						<div class="playlist-options">
							<ul>
								<li><a href="#" class="create-new-queue">Create New Queue</a></li>
								<li><a href="#">Queue 1</a></li>
								<li><a href="#">Queue 2</a></li>
								<li><a href="#">Queue 3</a></li>
								<li><a href="#">Queue 4</a></li>
								<li><a href="#">Queue 5</a></li>
								
							</ul>
						</div>			
						<a class="remove-songs" href="#">Download Song</a>
						<a class="add-to-playlist" href="#">Add To Queue</a>
						
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
				
					</div>
				</div>
			</div>

			<div class="album-info">
				<p>Now Streaming</p>
				<div class="now-playing-text"><span class="now-playing-title">Grow Up</span> by <span class="now-playing-artist"><a href="#">Cher Lloyd</a></span> on <span class="now-playing-album-title"><a href="#">Sticks and Stones</a></span></div>
				<div class="release-genre">Genre: <span><a href="#">Pop</a></span></div>
				<div class="release-label">Label: <span>Columbia</span></div>
				

			</div>
					
			<div class="gear-container">

				<div class="gear-icon">
					
				</div>
				
				<div class="queue-options">
					<a class="rename-queue" href="#">Rename Queue</a>	
					<a class="delete-queue" href="#">Delete Queue</a>

				</div>
				
				
			</div>
			
		</div>	

		
		<div class="now-playing-container">

			<nav class="playlist-filter-container clearfix">
				<div class="song-filter-button"></div>
				<div class="album-filter-button"></div>
				<div class="artist-filter-button"></div>
				<div class="time-filter-button"></div>
				
			</nav>
			<div class="playlist-shadow-container">
				<div class="playlist-scrollable">
					<div class="row-container">
					<?php
					for($b=0;$b<28;$b++) {
					?>
					
					<div class="row clearfix">
						<a class="preview" href="#"></a>
						<div class="song-title">Grow Up</div>
						<a class="add-to-wishlist-button" href="#"></a>
						<div class="album-title"><a href="#">Sticks and Stones</a></div>
						<div class="artist-name"><a href="#">Cher Lloyd</a></div>
						<div class="time">3:42</div>
						<div class="wishlist-popover">
								
							<a class="download-now" href="#">Download Now</a>
							<a class="add-to-wishlist" href="#">Add To Wishlist</a>
							<a class="remove-song" href="#">Remove Song</a>

							<div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="#"></a>
								<a class="twitter" href="#"></a>
							</div>
							<div class="playlist-options">
								<ul>
									<li><a href="#">Create New Queue</a></li>
									<li><a href="#">Queue 1</a></li>
									<li><a href="#">Queue 2</a></li>
									<li><a href="#">Queue 3</a></li>
									<li><a href="#">Queue 4</a></li>
									<li><a href="#">Queue 5</a></li>
									
									
								</ul>
							</div>
						
						</div>
					</div>
					<?php 
					}
					?>
					</div>
				</div>
			</div>
		</div>
		

	</section>