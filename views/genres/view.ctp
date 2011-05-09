<?php echo $javascript->link('freegal_genre_curvy'); ?>
<div id="genre">
	<?php echo $genre; ?>	
</div>
<br class="clr">
<div id="genre_artist_search">
 <a name="bottom"><?php __('Artist Search'); ?>&nbsp;</a>&nbsp;
 <?php echo $html->link('ALL',array('controller' => 'genres', 'action' => 'view', base64_encode($genre)));?>&nbsp;
 <?php echo $html->link('#',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'#'));?>&nbsp;
 <?php echo $html->link('A',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'A'));?>&nbsp;
 <?php echo $html->link('B',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'B'));?>&nbsp;
 <?php echo $html->link('C',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'C'));?>&nbsp;
 <?php echo $html->link('D',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'D'));?>&nbsp;
 <?php echo $html->link('E',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'E'));?>&nbsp;
 <?php echo $html->link('F',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'F'));?>&nbsp;
 <?php echo $html->link('G',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'G'));?>&nbsp;
 <?php echo $html->link('H',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'H'));?>&nbsp;
 <?php echo $html->link('I',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'I'));?>&nbsp;
 <?php echo $html->link('J',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'J'));?>&nbsp;
 <?php echo $html->link('K',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'K'));?>&nbsp;
 <?php echo $html->link('L',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'L'));?>&nbsp;
 <?php echo $html->link('M',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'M'));?>&nbsp;
 <?php echo $html->link('N',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'N'));?>&nbsp;
 <?php echo $html->link('O',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'O'));?>&nbsp;
 <?php echo $html->link('P',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'P'));?>&nbsp;
 <?php echo $html->link('Q',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Q'));?>&nbsp;
 <?php echo $html->link('R',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'R'));?>&nbsp;
 <?php echo $html->link('S',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'S'));?>&nbsp;
 <?php echo $html->link('T',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'T'));?>&nbsp;
 <?php echo $html->link('U',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'U'));?>&nbsp;
 <?php echo $html->link('V',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'V'));?>&nbsp;
 <?php echo $html->link('W',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'W'));?>&nbsp;
 <?php echo $html->link('X',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'X'));?>&nbsp;
 <?php echo $html->link('Y',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Y'));?>&nbsp;
 <?php echo $html->link('Z',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Z'));?>&nbsp;
</div>
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
		$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*2)));
		foreach ($counters as $counter):
			if($counter < count($genres)) {
				
				echo "<td width='308'><p>";
				if (strlen($genres[$counter]['Song']['ArtistText']) >= 38) {
					$ArtistName = substr($genres[$counter]['Song']['ArtistText'], 0, 38) . '...';
					echo '<span title="'.$genres[$counter]['Song']['ArtistText'].'">' . $html->link(
						$ArtistName, 
						array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$counter]['Song']['ArtistText']))) . '</span>'; ?>
				<?php
				} else {
					$ArtistName = $genres[$counter]['Song']['ArtistText'];
					echo $html->link(
						$ArtistName, 
						array('controller' => 'artists', 'action' => 'view', base64_encode($genres[$counter]['Song']['ArtistText'])));
				}
				echo '</p></td>';
			}
		endforeach;
		echo '</tr>';
	}
	?>
	</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?><br />
</div>
<div id="genreAdvSearch">
	<?php __("Can't find what you are looking for, try our") ?>&nbsp;<?php echo $html->link(__('Advanced Search', true), array('controller' => 'homes', 'action' => 'advance_search')); ?>.
</div>