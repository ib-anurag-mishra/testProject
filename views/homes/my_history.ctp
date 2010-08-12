<?php echo $javascript->link('freegal_genre_curvy'); ?>
<?php echo $session->flash();?>
<div id="genre">
	Download History
</div>
<br class="clr">
<div id="wishlistText"><?php echo $page->getPageContent('history'); ?></div>
<div id="genreArtist">
	<P>Artist</p>
</div>
<div id="genreTrack" style="width:250px;">
	<P>Track</p>
</div>
<div id="genreDownload">
	Download
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($downloadResults) != 0)
	{
		$i = 1;
		foreach($downloadResults as $key => $downloadResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
	?>
			<!-- <tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>> -->
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">

					<?php
						if (strlen($downloadResult['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['artist']).'">' .substr($downloadResult['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $downloadResult['Download']['artist'];
							echo $ArtistName;
						}
						
					?>

				</td>
				<td width="250" valign="top">
					<?php 
						if (strlen($downloadResult['Download']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($downloadResult['Download']['track_title']).'">' .substr($downloadResult['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $downloadResult['Download']['track_title']; 
					 	}
					?>
				</td>
				<td width="150" align="center">
					<?php										
						$productInfo = $physicalproduct->getDownloadData($downloadResult['Download']['ProdID']);
							$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Audio']['1']['Files']['CdnPath']."/".$productInfo[0]['Audio']['1']['Files']['SaveAsName']);                                                
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
							<p>
								<span id="download_song_<?php echo $downloadResult['Download']['id']; ?>">
									<a onClick='return historyDownload("<?php echo $downloadResult['Download']['id']; ?>",event);' href='<?php echo $finalSongUrl; ?>'>Download Now</a>							
								</span>
								<span id="download_loader_<?php echo $downloadResult['Download']['id']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
				</td>	
			</tr>
	<?php
		endforeach;
	}else{
		echo 	'<tr><td width="280" valign="top"><p>No downloaded songs for last two weeks.</p></td></tr>';
	}
	
	?>
</table>
</div>