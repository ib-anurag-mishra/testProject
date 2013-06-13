<?php
if(count($genres) == 0)
{
	echo "No artists found!";
	exit;
}

?>
<div id="genre_artist_search" style="overflow-y: hidden;">
<?php __('Artist Search'); ?>&nbsp;&nbsp;
 <?php echo $html->link('ALL',array('controller' => 'genres', 'action' => 'view', base64_encode($genre)));?>&nbsp;
 <?php echo $html->link('#',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'spl'));?>&nbsp;
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
<table cellspacing="0" cellpadding="0" border="0" width = "733px">
<?php
if(count($genres) > 0){
	$totalRows = ceil(count($genres)/3);
	for ($i = 0; $i < $totalRows; $i++) {
		$class = null;
		if ($i % 2 != 0) {
			$class = ' class="altrow"';
		}
		echo "<tr" . $class . ">";
		$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*(1.5))));
		foreach ($counters as $counter):
			if($counter < count($genres)) {

				echo "<td width='250'><p>";
				if (strlen($genres[$counter]['Song']['ArtistText']) >= 30) {
					$ArtistName = substr($genres[$counter]['Song']['ArtistText'], 0, 30) . '...';
					echo '<span title="'.$genres[$counter]['Song']['ArtistText'].'">' . $html->link(
						$this->getTextEncode($ArtistName),
						array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($genres[$counter]['Song']['ArtistText'])). '/'. base64_encode($genre))) . '</span>'; ?>
				<?php
				} else {
					$ArtistName = $genres[$counter]['Song']['ArtistText'];
					echo $html->link(
						$this->getTextEncode($ArtistName),
						array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($genres[$counter]['Song']['ArtistText'])).  '/'.base64_encode($genre)));
				}
				echo '</p></td>';
			}
		endforeach;
		echo '</tr>';
	}
}else{
	echo "<tr><td>No Results Found</td></tr>";
}
?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?><br />
</div>