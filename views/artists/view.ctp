<?php echo $javascript->link('freegal_artist_curvy'); ?>
<div id="artistBox">
	<?php echo $artistName; ?>
</div>
<br class="clr">

	<?php
	foreach($albumData as $album):
	
		?>
<div id="album">
	<div class="lgAlbumArtwork">
		<?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Graphic']['Files']['CdnPath']."/".$album['Graphic']['Files']['SourceURL']); ?>
		<img src="http://music.freegalmusic.com<?php echo $albumArtwork; ?>" width="250" height="250" border="0">
	</div>
	<div class="albumData">
		<div class="albumBox">
			<?php echo $album['Physicalproduct']['Title'];?>
		</div>
		<div class="artistInfo">
			<?php
				if ($album['Metadata']['ArtistURL'] != '') {
					echo $html->link('http://' . $album['Metadata']['ArtistURL'], 'http://' . $album['Metadata']['ArtistURL'], array('target' => 'blank'));
					echo '<br />';
				}
				if ($album['Metadata']['Label'] != '') {
					echo 'Label: ' . $album['Metadata']['Label'];
					echo '<br />';
				}
				if ($album['Metadata']['Copyright'] != '' && $album['Metadata']['Copyright'] != 'Unknown') {
					echo $album['Metadata']['Copyright'];
				}
			?>
		</div>
		<div class="songBox">
			<span class="songHeader">Tracks</span>
			<span class="timeHeader">Time</span>
			<span class="downloadHeader">Download</span>
		</div>
		<div id="songResults">
			<?php
			$i = 1;
			foreach($albumSongs[$album['Physicalproduct']['ReferenceID']] as $albumSong):			
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
			?>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr <?php echo $class; ?>>
					<td width="20" valign="top" align="center">
						<p><a href="#"><?php echo $html->image('button.png'); ?></a></p>
					</td>
					<td width="380" valign="top" align="left">
						<p><?php echo $albumSong['Metadata']['Title'];?></p>
					</td>
					<td width="50" valign="top" align="center">
						<p><?php echo $albumSong['Audio']['1']['Duration']?></p>
					</td>
					<td width="150" valign="top" align="center">
					<?php
					if($albumSong['ProductOffer']['SalesTerritory']['SALES_START_DATE'] <= date('Y-m-d'))
					{
					?>
						<p><a href='/freegal/artists/download/<?php echo $albumSong['Audio']['1']['FileID']; ?>'>Download Now</a></p>
					<?php
					}else{
						?>
							<p>Coming Soon( <?php echo $albumSong['ProductOffer']['SalesTerritory']['SALES_START_DATE']; ?>)</p>
						<?php
						}
						?>	
					</td>
				</tr>
			</table>
			<?php
			endforeach;
			?>
		</div>
	</div>
</div>
<?php
	endforeach;
	?>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<br class="clr">
