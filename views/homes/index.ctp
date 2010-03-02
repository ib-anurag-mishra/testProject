<?php echo $javascript->link('freegal_002'); ?>
<div id="artist_slideshow">
	<div id="slideshow">
	<?php
		foreach($artists as $artist):
			echo $html->image(substr($artist['Artist']['artist_image'], 4), array(
				"alt" => $artist['Artist']['artist_name'],
				'url' => array(
					'controller' => 'artists',
					'action' => 'view',
					$artist['Artist']['artist_name']
					)
				)
			);
		endforeach; 
	?>					
	</div>
</div>
<div id="suggestions">
	Suggestions
	<div id="suggestionsBox">
		<table cellspacing="0" cellpadding="0">
			<?php foreach($songs as $randomSongs): ?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'>
							<?php
								if (strlen($randomSongs['Home']['Title']) >= 28 ) {
									echo $html->link(substr($randomSongs['Home']['Title'], 0, 28) . "...", array(
										'controller' => 'artists', 
										'action' => 'view', 
										$randomSongs['Home']['Title']
										)
									);
								} else {
									echo $html->link($randomSongs['Home']['Title'], array(
										'controller' => 'artists', 
										'action' => 'view', 
										$randomSongs['Home']['Title']
										)
									);
								}
							?>
							<br />
							by&nbsp;
							<?php
								echo $html->link($randomSongs['Home']['Artist'], array(
									'controller' => 'artists',
									'action' => 'view',
									$randomSongs['Home']['Artist']
									)
								);
							?>
							<?php echo $html->image('button.png', array("alt" => "Play Sample")); ?>
						</p>
					</td>
				</tr>
			<?php endforeach; ?>    
		</table>
	</div>
</div>
<div id="artist_container">
	<div id="featured_artist">
		<?php 
		foreach($featuredArtists as $featuredArtist):
			echo $html->image(substr($featuredArtist['Featuredartist']['artist_image'], 4), array(
				"alt" => "Featured Arstist",
				'url' => array(
					'controller' => 'artist',
					'action' => 'view',
					$featuredArtist['Featuredartist']['artist_name']
					)
				)
			);
		endforeach;
		?>
	</div>
	<div id="newly_added">
		<?php 
		foreach($newArtists as $newArtist):
			echo $html->image(substr($newArtist['Newartist']['artist_image'], 4), array(
				"alt" => "Newly Added Artist", 
				'url' => array(
					'controller' => 'artists',
					'action' => 'view',
					$newArtist['Newartist']['artist_name']
					)
				)
			);
		endforeach;
		?>
	</div>
	<div id="artist_search">
		Artist Search&nbsp;&nbsp;
		<a href="artist_search?letter=num">#</a>&nbsp;
		<a href="artist_search?letter=a">A</a>&nbsp;
		<a href="artist_search?letter=b">B</a>&nbsp;
		<a href="artist_search?letter=c">C</a>&nbsp;
		<a href="artist_search?letter=d">D</a>&nbsp;

		<a href="artist_search?letter=e">E</a>&nbsp;
		<a href="artist_search?letter=f">F</a>&nbsp;
		<a href="artist_search?letter=g">G</a>&nbsp;
		<a href="artist_search?letter=h">H</a>&nbsp;
		<a href="artist_search?letter=i">I</a>&nbsp;
		<a href="artist_search?letter=j">J</a>&nbsp;

		<a href="artist_search?letter=k">K</a>&nbsp;
		<a href="artist_search?letter=l">L</a>&nbsp;
		<a href="artist_search?letter=m">M</a>&nbsp;
		<a href="artist_search?letter=n">N</a>&nbsp;
		<a href="artist_search?letter=o">O</a>&nbsp;
		<a href="artist_search?letter=p">P</a>&nbsp;

		<a href="artist_search?letter=q">Q</a>&nbsp;
		<a href="artist_search?letter=r">R</a>&nbsp;
		<a href="artist_search?letter=s">S</a>&nbsp;
		<a href="artist_search?letter=t">T</a>&nbsp;
		<a href="artist_search?letter=u">U</a>&nbsp;
		<a href="artist_search?letter=v">V</a>&nbsp;

		<a href="artist_search?letter=w">W</a>&nbsp;
		<a href="artist_search?letter=x">X</a>&nbsp;
		<a href="artist_search?letter=y">Y</a>&nbsp;
		<a href="artist_search?letter=z">Z</a>
		<div id="artist_searchBox">
			<div class="scrollarea">
				<table cellspacing="0" cellpadding="0">
					<?php foreach($distinctArtists as $allArtists): ?>
				        <tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
							<td class='artist_line'>
								<p>
									<?php
										echo $html->link($allArtists['Physicalproduct']['ArtistText'], array(
											'controller' => 'artists',
											'action' => 'view',
											$allArtists['Physicalproduct']['ArtistText'])
										);
									?>
								</p>
							</td>
						</tr>
					<?php endforeach; ?>       
				</table>
			</div>
		</div>
	</div>
</div>