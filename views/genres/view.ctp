<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	<?php echo $genre; ?>
</div>
<div id="genreArtist">
	Artist
</div>
<!--<div id="genreAlbum">
	<?php //echo $paginator->sort('Album ', 'Physicalproduct.Title') . $html->image('sort_arrows.png'); ?>
</div>
<div id="genreTrack">
	<?php //echo $paginator->sort('Track ', 'Metadata.Title') . $html->image('sort_arrows.png');?>
</div>
<div id="genreDownload">
	Download
</div> -->
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($genres) != 0)
	{
		// $i = 1;
		foreach($genres as $key => $genre):		
			$class = null;
			$artistInfo = $metadata->getArtistDetails($genre['Metadata']['Artist']);			
			// if ($i++ % 2 == 0) {
			// 				$class = ' class="altrow"';
			// 			}
			
			
	?>
			<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>>
				<td width="180" valign="top">
					<p class="info">
						<?php
						if (strlen($genre['Metadata']['Artist']) >= 19) {
							$ArtistName = substr($genre['Metadata']['Artist'], 0, 19) . '...';
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($artistInfo['Physicalproduct']['ArtistText']))); ?>
							<span><?php echo $genre['Metadata']['Artist']; ?></span>
						<?php
						} else {
							$ArtistName = $genre['Metadata']['Artist'];
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($artistInfo['Physicalproduct']['ArtistText'])));
						} 
					?>
					</p>
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
<div id="genreAdvSearch">
	Can't find what you are looking for, try our <?php echo $html->link('Advanced Search', array('controller' => 'homes', 'action' => 'advance_search')); ?>.
</div>