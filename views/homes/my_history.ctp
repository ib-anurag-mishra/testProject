<?php echo $javascript->link('freegal_genre_curvy'); ?>
<?php echo $session->flash();?>
<div id="genre">
	Download History
</div>
<br class="clr">
<div id="wishlistText"><?php echo $page->getPageContent('history'); ?></div>
<div id="genreArtist" style="width:200px;">
	<P>Artist</p>
</div>
<div id="genreTrack" style="width:300px;">
	<P>Track</p>
</div>
<div id="genreTrack" style="width:200px;">
	Date
</div>
<div id="genreTrack" style="width:200px;">
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
				<td width="200" valign="top">

					<?php
						if (strlen($downloadResult['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['artist']).'">' .substr($downloadResult['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $downloadResult['Download']['artist'];
							echo $ArtistName;
						}
						
					?>

				</td>
				<td width="300" valign="top">
					<?php 
						if (strlen($downloadResult['Download']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($downloadResult['Download']['track_title']).'">' .substr($downloadResult['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $downloadResult['Download']['track_title']; 
					 	}
					?>
				</td>
				<td width="200" valign="top" align="center">
					<?php 
						echo date("Y-m-d",strtotime($downloadResult['Download']['created']));							
					?>
				</td>
				<td width="200" align="center">
					<?php
						$productInfo = $song->getDownloadData($downloadResult['Download']['ProdID']);
						$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
						<p>
							<span class="beforeClick" id="download_song_<?php echo $downloadResult['Download']['ProdID']; ?>">
								<![if !IE]>
									<a href='#' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a>
								<![endif]>
								<!--[if IE]>
									<a onclick='return historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a>
								<![endif]-->
							</span>
							<span class="afterClick" style="display:none;float:left">Please Wait...</span>
							<span id="download_loader_<?php echo $downloadResult['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
						</p>
				</td>				
			</tr>
	<?php
		endforeach;
	}else{
		echo 	'<tr><td valign="top"><p>No downloaded songs from this week or last week.</p></td></tr>';
	}
	
	?>
</table>
</div>