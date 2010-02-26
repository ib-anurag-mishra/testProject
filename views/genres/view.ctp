<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	<?php echo $genres[0]['Genre']['Genre']; ?>
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
		$i = 1;
		foreach($genres as $genre):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' id="altrow"';
			}
	?>
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';"<?php echo $class; ?>>
				<td width="180" valign="top">
					<p><?php echo $html->link($genre['Metadata']['Artist'], array('controller' => 'artist', 'action' => 'view', $genre['Metadata']['Artist'])); ?></p>
				</td>
				<td width="200" valign="top">
					<p><?php //echo $genre[Metadata][Album]; ?></p>
				</td>
				<td width="400" valign="top">
					<p><a href="#" class="info"><?php echo $genre['Metadata']['Title']; ?><span><?php echo $genre['Metadata']['Title']; ?></span></a><a href='#'><img src='/img/button.png'></a></p>
				</td>
				<td width="150" align="center">
					<p>Download Now</p>
				</td>
			</tr>
	<?php endforeach; ?>
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