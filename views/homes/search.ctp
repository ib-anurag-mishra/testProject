<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	Search Results
</div>
<div id="genreArtist">
	<?php echo $paginator->sort('Artist', 'Metadata.Artist');?>
</div>
<div id="genreAlbum">
	<?php echo $paginator->sort('Album', 'Physicalproduct.Title');?>
</div>
<div id="genreTrack">
	<?php echo $paginator->sort('Track', 'Metadata.Title');?>
</div>
<div id="genreDownload">
	Download
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($searchResults) != 0)
	{
		// $i = 1;
		foreach($searchResults as $key => $searchResult):
			$class = null;
			// if ($i++ % 2 == 0) {
			// 				$class = ' class="altrow"';
			// 			}
			
			
	?>
			<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>>
				<td width="180" valign="top">
					<p class="info">
						<?php
						if (strlen($searchResult['Metadata']['Artist']) >= 19) {
							$ArtistName = substr($searchResult['Metadata']['Artist'], 0, 19) . '...';
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Metadata']['Artist']))); ?>
							<span><?php echo $searchResult['Metadata']['Artist']; ?></span>
						<?php
						} else {
							$ArtistName = $searchResult['Metadata']['Artist'];
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Physicalproduct']['ArtistText'])));
						} 
					?>
					</p>
				</td>
				<td width="200" valign="top">
					<p class="info">
					<?php
						if (strlen($searchResult['Physicalproduct']['Title']) >= 24) {
							echo substr($searchResult['Physicalproduct']['Title'], 0, 24) . '...<span>' . $searchResult['Physicalproduct']['Title'] . '</span>'; 
						} else { 
							echo $searchResult['Physicalproduct']['Title'];
						}
						if ($searchResult['Metadata']['Advisory'] == 'T') {
							echo '<div class="explicit"> (Explicit)</div>';
						}
					?>
					</p>
				</td>
				<td width="400" valign="top">
					<p class="info">
					<?php 
						if (strlen($searchResult['Metadata']['Title']) >= 48) {
							echo substr($searchResult['Metadata']['Title'], 0, 48) . '...<span>' . $searchResult['Metadata']['Title'] . '</span>';
						} else {
							echo $searchResult['Metadata']['Title']; 
					 	}
						if($searchResult['Physicalproduct']['SalesDate'] <= date('Y-m-d')) {
							$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio'][0]['Files']['CdnPath']."/".$searchResult['Audio'][0]['Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "play_audio'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$searchResult["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");'));
						}
					?>
					</p>
				</td>
				<td width="150" align="center">
					<?php
					if($searchResult['Physicalproduct']['SalesDate'] <= date('Y-m-d'))
					{					
						$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio']['1']['Files']['CdnPath']."/".$searchResult['Audio']['1']['Files']['SaveAsName']);
						?>
						<p><a href='http://music.freegalmusic.com<?php echo $songUrl; ?>'>Download Now</a></p>
					<?php
					}else{
						?>
						<p class="info">Coming Soon<span>Coming Soon ( <?php echo $searchResult['Physicalproduct']['SalesDate']; ?>)</span></p>
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