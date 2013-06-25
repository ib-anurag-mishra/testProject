<?php
/*
	 File Name : index.ctp
	 File Description : View page for genre index
	 Author : m68interactive
 */
?>

<script src="<? echo $this->webroot; ?>app/webroot/js/jquery.js"></script> 
                            

            <link rel="stylesheet" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/forms.css" />                   
            <link rel="shortcut icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico">
            <link rel="icon" href="<? echo $this->webroot; ?>app/webroot/favicon.ico">
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/styles.less" />	


            <link rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mep-feature-playlist-custom.css" />
            <link rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mediaelementplayer-custom.css" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/template.less" />            
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/genres.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/login.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/news.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/videos.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/individual-videos.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/genres.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/now-streaming.less" />
            <link rel="stylesheet/less" type="text/css" href="<? echo $this->webroot; ?>app/webroot/css/albums.less" />
            
            

            
            


            <script src="<? echo $this->webroot; ?>app/webroot/js/less.js"></script>
            <script src="<? echo $this->webroot; ?>app/webroot/js/modernizr.custom.js"></script> 
<?php
function ieversion()
{
	  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
	  if(!isset($reg[1])) {
		return -1;
	  } else {
		return floatval($reg[1]);
	  }
}
$ieVersion =  ieversion();
?>

	<div height="400px" style="color:blue;">
    <?php
	$totalRows = count($genresAll);
//		$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*2)), ($i+($totalRows*3)));
		foreach ($genresAll as $genres):
				echo $html->link(ucwords($genres['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genres['Genre']['Genre'])))."</br>";
		endforeach;
//	}
    ?>
	</div>
        	<section class="genres-page">
		<div class="breadcrumbs"><span>Home</span> > <span>Genres</span></div>
		<header class="clearfix">
			<h2>Search for your favorite music.</h2>
			<div class="faq-link">Need help? Visit our <a href="#">FAQ section.</a></div>
		</header>
		<section class="genre-filter-container clearfix">
			<div class="genre-shadow-container">
				<h3>Genre</h3>
				<div class="genre-list">
					
					<ul>
						
						<li><a href="#" data-genre="All Artists" id="genre_list_item_0" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode('All'); ?>' ,'0' , '<?php echo addslashes('All');  ?>')"><?php echo __('All Artists'); ?></a></li>
						<li><a href="#" data-genre="Acapella">Acapella</a></li>
						<li><a href="#" data-genre="Acid">Acid</a></li>
						<li><a href="#" data-genre="Acid Jazz">Acid Jazz</a></li>
						<li><a href="#" data-genre="Acid Punk">Acid Punk</a></li>
						<li><a href="#" data-genre="Adult Contemporary">Adult Contemporary</a></li>
						<li><a href="#" data-genre="African">African</a></li>
						<li><a href="#" data-genre="Afro Pop">Afro Pop</a></li>
						<li><a href="#" data-genre="Alt-Country">Alt-Country</a></li>
						<li><a href="#" data-genre="Alternative">Alternative</a></li>
						<li><a href="#" data-genre="Alternative Rock">Alternative Rock</a></li>
						<li><a href="#" data-genre="Alternative/Indie">Alternative/Indie</a></li>
						<li><a href="#" data-genre="Alternative/Punk">Alternative/Punk</a></li>
						<li><a href="#" data-genre="Ambient">Ambient</a></li>
						<li><a href="#" data-genre="Americana">Americana</a></li>
						<li><a href="#" data-genre="Acid">Acid</a></li>
						<li><a href="#" data-genre="Acid Jazz">Acid Jazz</a></li>
						<li><a href="#" data-genre="Acid Punk">Acid Punk</a></li>
						<li><a href="#" data-genre="Adult Contemporary">Adult Contemporary</a></li>
						<li><a href="#" data-genre="African">African</a></li>
						<li><a href="#" data-genre="Afro Pop">Afro Pop</a></li>
						<li><a href="#" data-genre="Alt-Country">Alt-Country</a></li>
						<li><a href="#" data-genre="Alternative">Alternative</a></li>
						<li><a href="#" data-genre="Alternative Rock">Alternative Rock</a></li>
						<li><a href="#" data-genre="Alternative/Indie">Alternative/Indie</a></li>
						<li><a href="#" data-genre="Alternative/Punk">Alternative/Punk</a></li>
						<li><a href="#" data-genre="Ambient">Ambient</a></li>
						<li><a href="#" data-genre="Americana">Americana</a></li>
						<li><a href="#" data-genre="Acid">Acid</a></li>
						<li><a href="#" data-genre="Acid Jazz">Acid Jazz</a></li>
						<li><a href="#" data-genre="Acid Punk">Acid Punk</a></li>
						<li><a href="#" data-genre="Adult Contemporary">Adult Contemporary</a></li>
						<li><a href="#" data-genre="African">African</a></li>
						<li><a href="#" data-genre="Afro Pop">Afro Pop</a></li>
						<li><a href="#" data-genre="Alt-Country">Alt-Country</a></li>
						<li><a href="#" data-genre="Alternative">Alternative</a></li>
						<li><a href="#" data-genre="Alternative Rock">Alternative Rock</a></li>
						<li><a href="#" data-genre="Alternative/Indie">Alternative/Indie</a></li>
						<li><a href="#" data-genre="Alternative/Punk">Alternative/Punk</a></li>
						<li><a href="#" data-genre="Ambient">Ambient</a></li>
						<li><a href="#" data-genre="Americana">Americana</a></li>
						<li><a href="#" data-genre="Z Genre">Z Genre</a></li>
					</ul>
				</div>
			</div>
			<div class="border"></div>
			<div class="alphabetical-shadow-container">
				<h3>Artist</h3>
				<div class="alphabetical-filter">
					
					<ul>
						
						<li><a href="#" data-letter="All">ALL</a></li>
						<li><a href="#" data-letter="#">#</a></li>
						<li><a href="#" data-letter="A">A</a></li>
						<li><a href="#" data-letter="B">B</a></li>
						<li><a href="#" data-letter="C">C</a></li>
						<li><a href="#" data-letter="D">D</a></li>
						<li><a href="#" data-letter="E">E</a></li>
						<li><a href="#" data-letter="F">F</a></li>
						<li><a href="#" data-letter="G">G</a></li>
						<li><a href="#" data-letter="H">H</a></li>
						<li><a href="#" data-letter="I">I</a></li>
						<li><a href="#" data-letter="J">J</a></li>
						<li><a href="#" data-letter="K">K</a></li>
						<li><a href="#" data-letter="L">L</a></li>
						<li><a href="#" data-letter="M">M</a></li>
						<li><a href="#" data-letter="N">N</a></li>
						<li><a href="#" data-letter="O">O</a></li>
						<li><a href="#" data-letter="P">P</a></li>
						<li><a href="#" data-letter="Q">Q</a></li>
						<li><a href="#" data-letter="R">R</a></li>
						<li><a href="#" data-letter="S">S</a></li>
						<li><a href="#" data-letter="T">T</a></li>
						<li><a href="#" data-letter="U">U</a></li>
						<li><a href="#" data-letter="V">V</a></li>
						<li><a href="#" data-letter="W">W</a></li>
						<li><a href="#" data-letter="X">X</a></li>
						<li><a href="#" data-letter="Y">Y</a></li>
						<li><a href="#" data-letter="Z">Z</a></li>
					</ul>
				</div>
			</div>
			
			<div class="artist-list-shadow-container">
				<h3>&nbsp;</h3>
				<div class="artist-list">
					
					<ul>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="A.J. Croce">A.J. Croce</a></li>
						<li><a href="#" data-artist="Amanda Ford">Amanda Ford</a></li>
						<li><a href="#" data-artist="Anneliese Rothenberger">Anneliese Rothenberger</a></li>
						<li><a href="#" data-artist="Adam Cohen">Adam Cohen</a></li>
						<li><a href="#" data-artist="ZZ Top">ZZ Top</a></li>
					</ul>
				</div>
			</div>
			<div class="border"></div>
			<div class="album-list-shadow-container">
				<h3>Album</h3>
				<div class="album-list">
					<div class="album-overview-container">
						<div class="album-image selected">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
					<div class="album-overview-container">
						<div class="album-image">
							<a href="#"><img src="/app/webroot/img/genres/album-cover-small.jpg" alt="album-cover-small" width="59" height="59" /></a>
						</div>
						<div class="album-title">
							<a href="#">13 Shades Of Blue, Best Of Mapleshade Vol. 2</a>
						</div>
						<div class="album-year">
							<a href="#">(2013)</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="album-detail-container clearfix">
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
			
		</section>
		
	</section>
