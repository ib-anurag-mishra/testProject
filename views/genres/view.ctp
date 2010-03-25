<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	<?php echo $genre; ?>
</div>
<div id="genreArtist">
	<?php echo $paginator->sort('Artist ', 'Metadata.Artist') . $html->image('sort_arrows.png'); ?>
</div>
<div id="genreAlbum">
	<?php echo $paginator->sort('Album ', 'Physicalproduct.Title') . $html->image('sort_arrows.png'); ?>
</div>
<div id="genreTrack">
	<?php echo $paginator->sort('Track ', 'Metadata.Title') . $html->image('sort_arrows.png');?>
</div>
<div id="genreDownload">
	Download
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($genres) != 0)
	{
		// $i = 1;
		foreach($genres as $key => $genre):		
			$class = null;
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
								array('controller' => 'artists', 'action' => 'view', base64_encode($genre['Physicalproduct']['ArtistText']))); ?>
							<span><?php echo $genre['Metadata']['Artist']; ?></span>
						<?php
						} else {
							$ArtistName = $genre['Metadata']['Artist'];
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($genre['Physicalproduct']['ArtistText'])));
						} 
					?>
					</p>
				</td>
				<td width="200" valign="top">
					<p class="info">
					<?php
						if (strlen($albumData[$genre['Physicalproduct']['ReferenceID']]) >= 24) {
							echo substr($albumData[$genre['Physicalproduct']['ReferenceID']], 0, 24) . '...<span>' . $albumData[$genre['Physicalproduct']['ReferenceID']] . '</span>'; 
						} else { 
							echo $albumData[$genre['Physicalproduct']['ReferenceID']];
						} 
					?>
					</p>
				</td>
				<td width="400" valign="top">
					<p class="info">
					<?php
						if (strlen($genre['Metadata']['Title']) >= 46) {
							echo substr($genre['Metadata']['Title'], 0, 46) . '...<span>' . $genre['Metadata']['Title'] . '</span>';
						} else {
							echo $genre['Metadata']['Title']; 
					 	} 

						if ($genre['Metadata']['Advisory'] == 'T') {
							echo '<p class="explicit"> (Explicit)</p>';
						}
					
					if($genre['Physicalproduct']['SalesDate'] <= date('Y-m-d')) {
						$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio'][0]['Files']['CdnPath']."/".$genre['Audio'][0]['Files']['SaveAsName']);
						$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
						$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "play_audio'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$genre["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");'));
					}
				?>
					</p>
				</td>
				<td width="150" align="center">
					<?php
					if($genre['Physicalproduct']['SalesDate'] <= date('Y-m-d'))
					{						
						if($libraryDownload == '1' && $patronDownload == '1')
						{	
							$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['1']['Files']['CdnPath']."/".$genre['Audio']['1']['Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));?>
							<p><a href='#' onclick='Javascript: userDownload("<?php echo $genre["Physicalproduct"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a></p>
					<?php	}											
						else
						{
							?>
							<p>Limit Exceeded</p>
							<?php
						}

					/*	$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['1']['Files']['CdnPath']."/".$genre['Audio']['1']['Files']['SaveAsName']);
						?>
						<p><a href='http://music.freegalmusic.com<?php echo $songUrl; ?>'>Download Now</a></p>
						<?php	*/					
					}else{
						?>
						<p class="info">Coming Soon<span>Coming Soon ( <?php echo date("F d Y", strtotime($genre['Physicalproduct']['SalesDate'])); ?> )</span></p>
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
	Can't find what you are looking for, try our <?php echo $html->link('Advanced Search', array('controller' => 'home', 'action' => 'advance_search')); ?>.
</div>