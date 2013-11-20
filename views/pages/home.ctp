<?php
/*
 File Name : home.ctp
 File Description : View page for home page
 Author : m68interactive
 */
?>
<?php echo $javascript->link('freegal_home_curvy'); ?>
<div id="artist_slideshow">
	<div id="slideshow">
		<a href="artist?artist=foo_fighters"><img src="img/foofighters.png" border="0"></a>
		<a href="artist?artist=michael_jackson"><img src="img/michaeljackson.png" border="0"></a>
		<a href="artist?artist=kings_of_leon"><img src="img/kingsofleon.png" border="0"></a>
		<a href="artist?artist=pink"><img src="img/pink.png" border="0"></a>
	</div>
</div>
<div id="suggestions">
	Suggestions
	<div id="suggestionsBox">
		<table cellspacing="0" cellpadding="0">
			<?php
			while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				echo "\t "; ?><tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';"> <?php echo "\n";
				echo "\t\t<td>\n";
				if (strlen($line[Title]) >= 28) {
					echo "<p class='suggest_text'><a href='#'>".substr($line[Title],0,28)."...</a><br />\n";
				} else {
					echo "<p class='suggest_text'><a href='#'>$line[Title]</a><br />\n";
				}
				echo "by <a href='artist.php?artist=" . str_replace(" ", "_", $line[Artist]) . "'>$line[Artist]</a><a href='#'><img src='images/button.png'></a></p>";
				$i++;
				echo "\t<tr>\n";
				echo "\t\t<td>\n";
			}
			?>
		</table>
	</div>
</div>
<div id="artist_container">
	<div id="featured_artist">
		<a href="artist?artist=alicia_keys"><img src="img/aliciakeys.png" border="0"></a>
		<a href="artist?artist=kat_deluna"><img src="img/katdeluna.png" border="0"></a>
		<a href="artist?artist=jordan_sparks"><img src="img/jordansparks.png" border="0"></a>
		<a href="artist?artist=t_pain"><img src="img/t_pain.png" border="0"></a>
	</div>
	<div id="newly_added">
		<a href="artist?artist=avril_lavigne"><img src="img/avrillavigne.png" border="0"></a>
		<a href="artist?artist=carrie_underwood"><img src="img/carrieunderwood.png" border="0"></a>
		<a href="artist?artist=john_legend"><img src="img/johnlegend.png" border="0"></a>
		<a href="artist?artist=usher"><img src="img/usher.png" border="0"></a>
	</div>
	<div id="artist_search">
		Artist Search
		<a href="artist_search?letter=num">#</a>
		<a href="artist_search?letter=a">A</a>
		<a href="artist_search?letter=b">B</a>
		<a href="artist_search?letter=c">C</a>
		<a href="artist_search?letter=d">D</a>
		<a href="artist_search?letter=e">E</a>
		<a href="artist_search?letter=f">F</a>
		<a href="artist_search?letter=g">G</a>
		<a href="artist_search?letter=h">H</a>
		<a href="artist_search?letter=i">I</a>
		<a href="artist_search?letter=j">J</a>
		<a href="artist_search?letter=k">K</a>
		<a href="artist_search?letter=l">L</a>
		<a href="artist_search?letter=m">M</a>
		<a href="artist_search?letter=n">N</a>
		<a href="artist_search?letter=o">O</a>
		<a href="artist_search?letter=p">P</a>
		<a href="artist_search?letter=q">Q</a>
		<a href="artist_search?letter=r">R</a>
		<a href="artist_search?letter=s">S</a>
		<a href="artist_search?letter=t">T</a>
		<a href="artist_search?letter=u">U</a>
		<a href="artist_search?letter=v">V</a>
		<a href="artist_search?letter=w">W</a>
		<a href="artist_search?letter=x">X</a>
		<a href="artist_search?letter=y">Y</a>
		<a href="artist_search?letter=z">Z</a>
		<div id="artist_searchBox">
			<div class="scrollarea">
				<table cellspacing="0" cellpadding="0">
					<?php
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
						echo "\t "; ?><tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';"> <?php echo "\n";
						echo "\t\t<td class='artist_line'>\n";
						echo "\t\t\t<p><a href='artist.php?artist=";
						echo str_replace(" ", "_", $line[ArtistText]);
						echo "'>$line[ArtistText]</a></p>\n";
						echo "\t\t</td>\n";
						echo "\t</tr>\n";
					}	
					?>
				</table>
			</div>
		</div>
	</div>
</div>
