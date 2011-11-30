<div class="breadCrumb">
	<?php
		echo $html->link('Home', array('controller'=>'homes', 'action'=>'index'));
		echo " > ";
		echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
		echo " > ";
		if(strlen($artistName) >= 30){
			$artistName = substr($artistName, 0, 30). '...';
		}
		echo $artistName;
	?>
	
	<?php
	function ieversion()
	{
		  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
		  if(!isset($reg[1])) {
			return -1;
		  } else {
			return floatval($reg[1]);
		  }
	}
	$ieVersion =  ieversion();
	?>	
</div>
<br class="clr">
<div style="padding-left:46px;padding-right:40px;" >
<table  width="100%">
<?php
$i = 0;
	foreach($albumData as $album_key => $album):
	if($i == 0){
		echo "<tr>";
	}
	$i++;
?>
		<td valign="top" >
		<a href="/artists/view/<?php echo base64_encode($album['Album']['ArtistText']); ?>/<?php echo $album['Album']['ProdID'];  ?>" >
		<div id="album_list_page" style="float:left">
			<div class="album_lgAlbumArtwork">
				<?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']); ?>
				<?php
					$image = Configure::read('App.Music_Path').$albumArtwork;
					if($page->isImage($image)) {
						//Image is a correct one
					}
					else {
						
					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
					}
				?>
				<img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" width="100" height="100" border="0">
			</div>
			<div class="albumData">
				<div class="albumlistBox">
					<b>
					<?php
					if(strlen($album['Album']['AlbumTitle']) >= 50){
						$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
					}
					?>
					<?php echo $album['Album']['AlbumTitle'];?>		
					</b>
				</div>
				<div class="album_artistInfo">
					<?php
						echo __('Genre').": ".$html->link($album['Genre']['Genre'], array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) . '<br />';
						if ($album['Album']['ArtistURL'] != '') {
							echo $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank'));
							echo '<br />';
						}
						if ($album['Album']['Label'] != '') {
							echo __("Label").': ' . $album['Album']['Label'];
							echo '<br />';
						}
						if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {
							echo $album['Album']['Copyright'];
						}
					?>
				</div>
			</div>
		</div>
		</a>
		</td>
<?php
if($i == 3){
	echo "</tr>";
	$i = 0;
}
	endforeach;
?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<br class="clr">