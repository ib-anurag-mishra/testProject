<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	<?php echo $genre; ?>
</div>
<div id="genreArtist">
	Artist
</div>
<div id="genreAlbum">
	Album
</div>
<div id="genreTrack">
	Track
</div>
<div id="genreDownload">
	Download
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if($genres != 0)
	{
		$i = 1;
		foreach($genres as $genre):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			
			
	?>
			<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php echo $class; ?>>
				<td width="180" valign="top">
					<p><?php echo $html->link($genre['Metadata']['Artist'], array('controller' => 'artists', 'action' => 'view', $genre['Metadata']['Artist'])); ?></p>
				</td>
				<td width="200" valign="top">
					<p><?php echo $albumData[$genre['Physicalproduct']['ReferenceID']]; ?></p>
				</td>
				<td width="400" valign="top">
					<p><a href="#" class="info"><?php echo $genre['Metadata']['Title']; ?><span><?php echo $genre['Metadata']['Title']; ?></span></a><a href='#'><img src='/img/button.png'></a></p>
				</td>
				<td width="150" align="center">
					<?php
					if($genre['ProductOffer']['SalesTerritory']['SALES_START_DATE'] <= date('Y-m-d'))
					{
						?>
						<p>Download Now</p>
						<?php
					}else{
						?>
						<p>Comming Soon( <?php echo $genre['ProductOffer']['SalesTerritory']['SALES_START_DATE']; ?>)</p>
						<?php
					}
					?>
				</td>
			</tr>
	<?php
		endforeach;
	}else{
		echo '<td width="180" valign="top">
					<p>No records found</p>
				</td>';
	}
	
	?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div id="genreAdvSearch">
	Can't find what you are looking for, try our <?php echo $html->link('Advanced Search', array('controller' => 'home', 'action' => 'advsearch')); ?>.
</div>