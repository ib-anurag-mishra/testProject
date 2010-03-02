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
				$class = ' id="altrow"';
			}
			
			
	?>
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';"<?php echo $class; ?>>
				<td width="180" valign="top">
					<p><?php echo $html->link($genre['Metadata']['Artist'], array('controller' => 'artists', 'action' => 'view', $genre['Metadata']['Artist'])); ?></p>
				</td>
				<td width="200" valign="top">
					<!-- <?php if (strlen($genre['PhysicalProduct']['Title']) >= 24) { ?>
											<p><a href="#" class="info"><?php echo substr($genre['PhysicalProduct']['Title'], 0, 24) . '...'; ?><span><?php echo $genre['PhysicalProduct']['Title']; ?></span></a></p>
										<?php } else { ?>
											<p><a href="#" class="info"><?php echo $genre['PhysicalProduct']['Title']; ?><span><?php echo $genre['PhysicalProduct']['Title']; ?></span></a></p>
										<?php } ?>
										<p><?php echo $albumData[$genre['Physicalproduct']['ReferenceID']]; ?></p> -->
				</td>
				<td width="400" valign="top">
					<p><a href="#" class="info">
					<?php if (strlen($genre['Metadata']['Title']) >= 46) { ?>
						<?php echo substr($genre['Metadata']['Title'], 0, 46) . '...'; ?><span><?php echo $genre['Metadata']['Title']; ?></span></a><?php echo $html->image('button.png', array("alt" => "Play Sample")); ?></p>
					<?php } else { ?>
						<?php echo $genre['Metadata']['Title']; ?><span><?php echo $genre['Metadata']['Title']; ?></span></a><?php echo $html->image('button.png', array("alt" => "Play Sample")); ?></p>
					<?php } ?>
				</td>
				<td width="150" align="center">
					<p>Download Now</p>
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