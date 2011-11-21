<?php
if(count($genres) == 0)
{
	echo "No artists found!";
	exit;
}

?>

<div id="genreResults">
<table cellspacing="0" cellpadding="0" border="0" width = "733px">
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
			
			echo "<td width='250'><p>";
			if (strlen($genres[$counter]['Song']['ArtistText']) >= 30) {
				$ArtistName = substr($genres[$counter]['Song']['ArtistText'], 0, 30) . '...';
				echo '<span title="'.$genres[$counter]['Song']['ArtistText'].'">' . $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'album', base64_encode($genres[$counter]['Song']['ArtistText']))) . '</span>'; ?>
			<?php
			} else {
				$ArtistName = $genres[$counter]['Song']['ArtistText'];
				echo $html->link(
					$ArtistName, 
					array('controller' => 'artists', 'action' => 'album', base64_encode($genres[$counter]['Song']['ArtistText'])));
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
