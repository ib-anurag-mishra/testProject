<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	Search Results
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
	if($searchResults != 0)
	{
		$i = 1;
		foreach($searchResults as $searchResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			
			
	?>
			<tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php echo $class; ?>>
				<td width="180" valign="top">
					<p><?php echo $html->link($searchResult['Metadata']['Artist'], array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Metadata']['Artist']))); ?></p>
				</td>
				<td width="200" valign="top">
					<p><?php echo $searchResult['Physicalproduct']['Title']; ?></p>
				</td>
				<td width="400" valign="top">
					<p><a href="#" class="info"><?php echo $searchResult['Metadata']['Title']; ?><span><?php echo $searchResult['Metadata']['Title']; ?></span></a><a href='#'><img src='/img/button.png'></a></p>
				</td>
				<td width="150" align="center">
					<?php
					if($searchResult['ProductOffer']['SalesTerritory']['SALES_START_DATE'] <= date('Y-m-d'))
					{					
						$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio']['1']['Files']['CdnPath']."/".$searchResult['Audio']['1']['Files']['SaveAsName']);
						?>
						<p><a href='http://music.freegalmusic.com<?php echo $songUrl; ?>'>Download Now</a></p>
					<?php
					}else{
						?>
						<p>Comming Soon( <?php echo $searchResult['ProductOffer']['SalesTerritory']['SALES_START_DATE']; ?>)</p>
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