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
					base64_encode($artist['Artist']['artist_name'])
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
			<?php foreach($songs as $key => $randomSongs): ?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'>
							<?php
								if (strlen($randomSongs['Metadata']['Title']) >= 28 ) {
									echo $html->link(substr($randomSongs['Metadata']['Title'], 0, 28) . "...", array(
										'controller' => 'artists', 
										'action' => 'view', 
										$randomSongs['Metadata']['Title']
										)
									);
								} else {
									echo $html->link($randomSongs['Metadata']['Title'], array(
										'controller' => 'artists', 
										'action' => 'view', 
										$randomSongs['Metadata']['Title']
										)
									);
								}
							?>
							<br />
							by&nbsp;
							<?php
								echo $html->link($randomSongs['Metadata']['Artist'], array(
									'controller' => 'artists',
									'action' => 'view',
									base64_encode($randomSongs['Metadata']['Artist'])
									)
								);
								$songUrl = shell_exec('perl files/tokengen ' . $randomSongs['Audio'][0]['Files']['CdnPath']."/".$randomSongs['Audio'][0]['Files']['SaveAsName']);
								$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
								$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							?>
							<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "play_audio'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$randomSongs["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");')); ?>
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
					base64_encode($featuredArtist['Featuredartist']['artist_name'])
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
					base64_encode($newArtist['Newartist']['artist_name'])
					)
				)
			);
		endforeach;
		?>
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
									<?php
										echo $html->link($allArtists['Physicalproduct']['ArtistText'], array(
											'controller' => 'artists',
											'action' => 'view',
											base64_encode($allArtists['Physicalproduct']['ArtistText']))
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