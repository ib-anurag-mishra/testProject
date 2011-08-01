<?php
/*
	 File Name : artist_search.ctp
	 File Description : View page for artist search
	 Author : m68interactive
 */
?>
<div id="artist_searchBox">
			<div class="scrollarea">
				<table cellspacing="0" cellpadding="0">
					<?php foreach($distinctArtists as $allArtists): ?>
				        <tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
							<td class='artist_line'>
								<p>
									<a href='<?php echo $this->webroot; ?>artists/view/<?php echo base64_encode($allArtists['Song']['ArtistText']); ?>'>
										<?php echo $allArtists['Song']['ArtistText'] ?>
									</a>
								</p>
							</td>
						</tr>
					<?php endforeach; ?>       
				</table>
			</div>
		</div>