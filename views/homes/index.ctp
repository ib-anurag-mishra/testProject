<?php echo $javascript->link('freegal_002');?>
<!--<div id="artist_slideshow">
	<div id="slideshow">
		<a href="artist?artist=foo_fighters"><img src="img/foofighters.png" border="0"></a>
		<a href="artist?artist=michael_jackson"><img src="img/michaeljackson.png" border="0"></a>
		<a href="artist?artist=kings_of_leon"><img src="img/kingsofleon.png" border="0"></a>
		<a href="artist?artist=pink"><img src="img/pink.png" border="0"></a>
	</div>
</div> -->
<div id="artist_slideshow">
	<div id="slideshow">
	<?php foreach($artists as $artist): ?>
	<?php
			// echo $html->link(
			// 	$html->image($artist['Artist']['artist_image'], array("alt" => $artist['Artist']['artist_name'])),
			// 	"artist/" . $artist['Artist']['artist_name'],
			// 	array('escape' => false)
			// );
	?>
			<a href="artist?artist=<?php echo $artist['Artist']['artist_name']?>"><img src="<?php echo $artist['Artist']['artist_image']?>" border="0"></a>
	<?php endforeach; ?>					
	</div>
</div>
<div id="suggestions">
	Suggestions
	<div id="suggestionsBox">
		<table cellspacing="0" cellpadding="0">
			<?php foreach($songs as $randomSongs): ?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'><a href='#'><?php echo $randomSongs['Home']['Title'] ?></a><br />
							by <a href='artist.php?artist=<?php echo $randomSongs['Home']['Artist'] ?>'><?php echo $randomSongs['Home']['Artist']?></a>
							<a href='#'><img src='img/button.png'></a>
						</p>
					</td>
				</tr>
			<?php endforeach; ?>    
		</table>
	</div>
</div>
<div id="artist_container">
	<!--<div id="featured_artist">
		<a href="artist?artist=alicia_keys"><img src="img/aliciakeys.png" border="0"></a>
		<a href="artist?artist=kat_deluna"><img src="img/katdeluna.png" border="0"></a>
		<a href="artist?artist=jordan_sparks"><img src="img/jordansparks.png" border="0"></a>
		<a href="artist?artist=t_pain"><img src="img/t_pain.png" border="0"></a>
	</div>
	  -->
	<div id="featured_artist">
	<?php foreach($featuredArtists as $featuredArtist): ?>
		<a href="artist?artist=<?php echo $featuredArtist['Featuredartist']['artist_name']?> ">
			<img src="<?php echo $featuredArtist['Featuredartist']['artist_image']?>" border="0">
		</a>
	<?php endforeach; ?>
	</div>
	<div id="newly_added">
		<?php foreach($newArtists as $newArtist): ?>
			<a href="artist?artist=<?php echo $newArtist['Newartist']['artist_name']?> ">
				<img src="<?php echo $newArtist['Newartist']['artist_image']?>" border="0">
			</a>
		<?php endforeach; ?>
		<!--<a href="artist?artist=avril_lavigne"><img src="img/avrillavigne.png" border="0"></a>
		<a href="artist?artist=carrie_underwood"><img src="img/carrieunderwood.png" border="0"></a>
		<a href="artist?artist=john_legend"><img src="img/johnlegend.png" border="0"></a>
		<a href="artist?artist=usher"><img src="img/usher.png" border="0"></a> -->
	</div>
	<div id="artist_search">
		Artist Search&nbsp;&nbsp;
		<a href="#" onclick="searchArtist('')">#</a>&nbsp;
		<a href="#" onclick="searchArtist('a')">A</a>&nbsp;
		<a href="#" onclick="searchArtist('b')">B</a>&nbsp;
		<a href="#" onclick="searchArtist('c')">C</a>&nbsp;
		<a href="#" onclick="searchArtist('d')">D</a>&nbsp;

		<a href="#" onclick="searchArtist('e')">E</a>&nbsp;
		<a href="#" onclick="searchArtist('f')">F</a>&nbsp;
		<a href="#" onclick="searchArtist('g')">G</a>&nbsp;
		<a href="#" onclick="searchArtist('h')">H</a>&nbsp;
		<a href="#" onclick="searchArtist('i')">I</a>&nbsp;
		<a href="#" onclick="searchArtist('j')">J</a>&nbsp;

		<a href="#" onclick="searchArtist('k')">K</a>&nbsp;
		<a href="#" onclick="searchArtist('l')">L</a>&nbsp;
		<a href="#" onclick="searchArtist('m')">M</a>&nbsp;
		<a href="#" onclick="searchArtist('n')">N</a>&nbsp;
		<a href="#" onclick="searchArtist('o')">O</a>&nbsp;
		<a href="#" onclick="searchArtist('p')">P</a>&nbsp;

		<a href="#" onclick="searchArtist('q')">Q</a>&nbsp;
		<a href="#" onclick="searchArtist('r')">R</a>&nbsp;
		<a href="#" onclick="searchArtist('s')">S</a>&nbsp;
		<a href="#" onclick="searchArtist('t')">T</a>&nbsp;
		<a href="#" onclick="searchArtist('u')">U</a>&nbsp;
		<a href="#" onclick="searchArtist('v')">V</a>&nbsp;

		<a href="#" onclick="searchArtist('w')">W</a>&nbsp;
		<a href="#" onclick="searchArtist('x')">X</a>&nbsp;
		<a href="#" onclick="searchArtist('y')">Y</a>&nbsp;
		<a href="#" onclick="searchArtist('z')">Z</a>
		<div id="artist_searchBox">
			<div class="scrollarea">
				<table cellspacing="0" cellpadding="0">
					<?php foreach($distinctArtists as $allArtists): ?>
				        <tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
							<td class='artist_line'>
								<p>
									<a href='artist.php?artist=<?php echo $allArtists['Physicalproduct']['ArtistText'] ?>'>
										<?php echo $allArtists['Physicalproduct']['ArtistText'] ?>
									</a>
								</p>
							</td>
						</tr>
					<?php endforeach; ?>       
				</table>
			</div>
		</div>
	</div>
</div>