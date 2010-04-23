<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<?php echo $session->flash();?>
<div id="genre">
	Wishlist
</div>
<div id="genreArtist">
	<P>Artist</p>
</div>
<div id="genreAlbum">
	<P>Album</p>
</div>
<div id="genreTrack" style="width:250px;">
	<P>Track</p>
</div>
<div id="genreDownload">
	Download
</div>
<div id="genreDownload">
	
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($wishlistResults) != 0)
	{
		$i = 1;
		foreach($wishlistResults as $key => $wishlistResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
	?>
			<!-- <tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>> -->
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">
					<p class="info">
					<?php
						if (strlen($wishlistResult['Wishlist']['artist']) >= 19) {
							$ArtistName = substr($wishlistResult['Wishlist']['artist'], 0, 19) . '...';							
						} else {
							$ArtistName = $wishlistResult['Wishlist']['artist'];
						}
						echo $ArtistName;
					?>
					</p>
				</td>
				<td width="200" valign="top">
					<p class="info">
					<?php
						if (strlen($wishlistResult['Wishlist']['album']) >= 24) {
							echo substr($wishlistResult['Wishlist']['album'], 0, 24) . '...<span>' . $wishlistResult['Wishlist']['album'] . '</span>'; 
						} else { 
							echo $wishlistResult['Wishlist']['album'];
						}
						
					?>
					</p>
				</td>
				<td width="250" valign="top">
					<p class="info">
					<?php 
						if (strlen($wishlistResult['Wishlist']['track_title']) >= 48) {
							echo substr($wishlistResult['Wishlist']['track_title'], 0, 48) . '...<span>' . $wishlistResult['Wishlist']['track_title'] . '</span>';
						} else {
							echo $wishlistResult['Wishlist']['track_title']; 
					 	}
					?>
					</p>
				</td>
				<td width="150" align="center">
					<?php										
						$productInfo = $physicalproduct->getDownloadData($wishlistResult['Wishlist']['ProdID']);
						if($libraryDownload == '1' && $patronDownload == '1'){
							$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Audio']['1']['Files']['CdnPath']."/".$productInfo[0]['Audio']['1']['Files']['SaveAsName']);                                                
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
					?>
							<p><span id="wishlist_song_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>"><a onclick='return wishlistDownload("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>","<?php echo $wishlistResult['Wishlist']['id']; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a></span><span id="wishlist_loader_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
					<?php	}
						else{ ?>
							<p>Limit Exceeded</p>
						<?php
						}
					?>
				</td>
				<td width="150" align="center">
					<?php echo $html->link('Remove', array('controller' => 'homes', 'action' => 'removeWishlistSong', 'id'=>$wishlistResult['Wishlist']['id'])); ?>
				</td>	
			</tr>
	<?php
		endforeach;
	}else{
		echo 	'<tr><td width="280" valign="top"><p>You have no songs in your wishlist.</p></td></tr>';
	}
	
	?>
</table>
</div>