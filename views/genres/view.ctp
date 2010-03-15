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
		foreach($genres as $key => $genre):		
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			
			
	?>
			<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php echo $class; ?>>
				<td width="180" valign="top">
					<p class="info"><?php echo $html->link($genre['Physicalproduct']['ArtistText'], array('controller' => 'artists', 'action' => 'view', base64_encode($genre['Physicalproduct']['ArtistText']))); ?><span><?php echo $genre['Physicalproduct']['ArtistText']; ?></span></p>
				</td>
				<td width="200" valign="top">
					<p class="info">
					<?php
						if (strlen($albumData[$genre['Physicalproduct']['ReferenceID']]) >= 24) {
							echo substr($albumData[$genre['Physicalproduct']['ReferenceID']], 0, 24) . '...'; 
						} else { 
							echo $albumData[$genre['Physicalproduct']['ReferenceID']];
						} 
					?>
					<span><?php echo $albumData[$genre['Physicalproduct']['ReferenceID']]; ?></span></p>
				</td>
				<td width="400" valign="top">
					<p class="info">
					<?php
						if (strlen($genre['Metadata']['Title']) >= 48) {
							echo substr($genre['Metadata']['Title'], 0, 48) . '...';
						} else {
							echo $genre['Metadata']['Title']; 
					 	} 
					?>
					<span><?php echo $genre['Metadata']['Title']; ?></span>						
				<?php
					$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio'][0]['Files']['CdnPath']."/".$genre['Audio'][0]['Files']['SaveAsName']);
					$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
					$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "play_audio'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.count($genres).', '.$genre["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");'));
				?>
				</td>
				<td width="150" align="center">
					<?php
					if($genre['ProductOffer']['SalesTerritory']['SALES_START_DATE'] <= date('Y-m-d'))
					{
						$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['1']['Files']['CdnPath']."/".$genre['Audio']['1']['Files']['SaveAsName']);
						?>
						<p><a href='http://music.freegalmusic.com<?php echo $songUrl; ?>'>Download Now</a></p>
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