<!--
Place holder.  When the queries are done we can put in foreach statements.
-->

<?php echo $javascript->link('freegal_artist_curvy'); ?>
<div id="artistBox">
	<?php echo $artistName; ?>
</div>
<br class="clr">
<div id="artistInfo">
	Website: <?php echo $artistUrl;?> <br />
	&copy;
</div>
<br class="clr">
	<?php
	foreach($albumData as $album):
		?>
<div id="album">
	<div class="lgAlbumArtwork">
		<img src="images/album_artwork/eros.jpg" width="250" height="250" border="0">
	</div>
	<div class="albumBox">
		<?php echo $album['Physicalproduct']['Title'];?>
	</div>
	<div class="songBox">
		<span class="songHeader">Tracks</span>
		<span class="timeHeader">Time</span>
		<span class="downloadHeader">Download</span>
	</div>
	<div id="songResults">
		<?php
		foreach($albumSongs[$album['Physicalproduct']['ReferenceID']] as $albumSong):
		?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';"<?php //echo $class; ?>>
				<td width="20" valign="top" align="center">
					<a href="#"><?php echo $html->image('button.png'); ?></a>
				</td>
				<td width="380" valign="top" align="left">
					<?php echo $albumSong['Metadata']['Title'];?>
				</td>
				<td width="50" valign="top" align="center">
					5:10
				</td>
				<td width="150" valign="top" align="center">
				<?php
				if($albumSong['ProductOffer']['SalesTerritory']['SALES_START_DATE'] <= date('Y-m-d'))
				{
				?>
					Download Now
				<?php
				}else{
					?>
						Comming Soon( <?php echo $albumSong['ProductOffer']['SalesTerritory']['SALES_START_DATE']; ?>)
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
<?php
	endforeach;
	?>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<br class="clr">
