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