<?php echo $javascript->link('freegal_genre_curvy'); ?>
<div id="genre">
	<?php echo $genre; ?>	
</div>
<!-- <div id="genreArtist">
	<?php // echo $paginator->sort('Artist ', 'Metadata.Artist') . $html->image('sort_arrows.png'); ?>
</div> -->
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
	<table cellspacing="0" cellpadding="0" border="0">
	<?php
	$totalRows = ceil(count($genres)/3);
	for ($i = 0; $i < $totalRows; $i++) {
		$class = null;
		if ($i % 2 != 0) {
			$class = ' class="altrow"';
		}
		echo "<tr" . $class . ">";
		if($i < count($genres)) {
			echo "<td width='308'><p class='info'>";
			if (strlen($genres[$i]['Physicalproduct']['ArtistText']) >= 38) {
				$ArtistName = substr($genres[$i]['Physicalproduct']['ArtistText'], 0, 38) . '...';
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i]['Physicalproduct']['ArtistText']))); ?>
				<span><?php echo $genres[$i]['Physicalproduct']['ArtistText']; ?></span>
			<?php
			} else {
				$ArtistName = $genres[$i]['Physicalproduct']['ArtistText'];
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i]['Physicalproduct']['ArtistText'])));
			}
			echo '</p></td>';
		}
		if(($i+($totalRows*1)) < count($genres)) {
			echo '<td width="308"><p class="info">';
			if (strlen($genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText']) >= 38) {
				$ArtistName = substr($genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText'], 0, 38) . '...';
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText']))); ?>
				<span><?php echo $genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText']; ?></span>
			<?php
			} else {
				$ArtistName = $genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText'];
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i+($totalRows*1)]['Physicalproduct']['ArtistText'])));
			}
			echo '</p></td>';
		}
		if(($i+($totalRows*2)) < count($genres)) {
			echo '<td width="308"><p class="info">';
			if (strlen($genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText']) >= 38) {
				$ArtistName = substr($genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText'], 0, 38) . '...';
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText']))); ?>
				<span><?php echo $genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText']; ?></span>
			<?php
			} else {
				$ArtistName = $genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText'];
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$i+($totalRows*2)]['Physicalproduct']['ArtistText'])));
			}
			echo '</p></td>';
		}
		echo '</tr>';
	}
	/*	$i=0;
		$j=1;
        foreach ($genres as $genre):
		$class = null;
		if ($j++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		//$artistInfo = $metadata->getArtistDetails($genre['Metadata']['Artist']);
		if($i%3 == 0) {
		    echo "<tr" . $class . "><td width='308'>";
		} else {
		    echo "<td width='308'>";
		}
		echo '<p class="info">';
		if (strlen($genre['Physicalproduct']['ArtistText']) >= 38) {
			$ArtistName = substr($genre['Physicalproduct']['ArtistText'], 0, 38) . '...';
			echo $html->link(
				$ArtistName, 
				array('controller' => 'artists', 'action' => 'view', base64_encode($genre['Physicalproduct']['ArtistText']))); ?>
			<span><?php echo $genre['Physicalproduct']['ArtistText']; ?></span>
		<?php
		} else {
			$ArtistName = $genre['Physicalproduct']['ArtistText'];
			echo $html->link(
				$ArtistName, 
				array('controller' => 'artists', 'action' => 'view', base64_encode($genre['Physicalproduct']['ArtistText'])));
		}
		echo '</p>';
		if(($i+1)%3 == 0) {
		    echo "</td></tr>";        
		} else {            
		    echo "</td>";
		}
		$i++;
        endforeach;*/
	?>
	</table>
	
	
	<!-- <table cellspacing="0" cellpadding="0">
		<?php
		// if(count($genres) != 0)
		{
			// $i = 1;
			// foreach($genres as $key => $genre):		
				// $class = null;
				// $artistInfo = $metadata->getArtistDetails($genre['Metadata']['Artist']);			
				// if ($i++ % 2 == 0) {
				// 				$class = ' class="altrow"';
				// 			}
				
				
		?>
				<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>>
					<td width="180" valign="top">
						<p class="info">
							<?php
							// if (strlen($genre['Metadata']['Artist']) >= 19) {
								// $ArtistName = substr($genre['Metadata']['Artist'], 0, 19) . '...';
								// echo $html->link(
									// $ArtistName,
									// array('controller' => 'artists', 'action' => 'view', base64_encode($artistInfo['Physicalproduct']['ArtistText']))); ?>
								<span><?php // echo $genre['Metadata']['Artist']; ?></span>
							<?php
							// } else {
								// $ArtistName = $genre['Metadata']['Artist'];
								// echo $html->link(
									// $ArtistName,
									// array('controller' => 'artists', 'action' => 'view', base64_encode($artistInfo['Physicalproduct']['ArtistText'])));
							} 
						?>
						</p>
					</td>				
				</tr>
		<?php
			// endforeach;
		// }else{
			// echo '<td width="180" valign="top">
						// <p>No records found</p>
					// </td>';
		// }
		
		?>
	</table> -->
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?><br />
</div>
<div id="genreAdvSearch">
	Can't find what you are looking for, try our <?php echo $html->link('Advanced Search', array('controller' => 'homes', 'action' => 'advance_search')); ?>.
</div>