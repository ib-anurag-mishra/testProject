<script type="text/JavaScript" src="/js/freegal_genre_curvy.js"></script>
<div id="genre">
	Search Results
</div>
<div id="genreArtist">
	<?php echo $paginator->sort('Artist ', 'Metadata.Artist', array('url' => array("?"=>$searchKey)))  . $html->image('sort_arrows.png');?>
</div>
<div id="genreAlbum">
	<?php echo $paginator->sort('Album ', 'Physicalproduct.Title', array('url' => array("?"=>$searchKey))) . $html->image('sort_arrows.png');?>
</div>
<div id="genreTrack">
	<?php echo $paginator->sort('Track ', 'Metadata.Title', array('url' => array("?"=>$searchKey))) . $html->image('sort_arrows.png');?>
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
		$i = 1;
		foreach($searchResults as $key => $searchResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
	?>
			<!-- <tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>> -->
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">
					<p class="info">
						<?php
						if (strlen($searchResult['Metadata']['Artist']) >= 19) {
							$ArtistName = substr($searchResult['Metadata']['Artist'], 0, 19) . '...';
							echo $html->link(
								$ArtistName,
								array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Physicalproduct']['ArtistText']))); ?>
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
						if ($searchResult['Metadata']['Advisory'] == 'T') {
							echo '<div class="explicit"> (Explicit)</div>';
						}
						if($searchResult['Physicalproduct']['SalesDate'] <= date('Y-m-d')) {
							$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio'][0]['Files']['CdnPath']."/".$searchResult['Audio'][0]['Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$searchResult["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");'));
							echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key));
							echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");'));
						}
					?>
					</p>
				</td>
				<td width="150" align="center">
					<?php
					if($searchResult['Physicalproduct']['SalesDate'] <= date('Y-m-d'))
					{					
						if($libraryDownload == '1' && $patronDownload == '1')
                                                {	
                                                        $songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio']['1']['Files']['CdnPath']."/".$searchResult['Audio']['1']['Files']['SaveAsName']);                                                
                                                        $finalSongUrl = "http://music.freegalmusic.com".$songUrl;
                                                        $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));?>
                                                        <p><a onclick='return userDownload("<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a><span id="download_loader_<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
                                <?php		}											
                                                else{
							if($libraryDownload != '1'){
								$wishlistInfo = $wishlist->getWishlistData($catG["ProdId"]);
								if($wishlistInfo == 'Added to Wishlist'){
									?> <p>Added to Wishlist</p>
								<?php }
								else{ ?>
									<p><span id="wishlist<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>");'>Add to wishlist</a></span><span id="wishlist_loader_<?php $searchResult["Physicalproduct"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
								<?php	
								}
							}
							else{ ?>
								<p>Limit Exceeded</p>
							<?php	
							}												
						}                                  
					}else{
						?>
						<p class="info">Coming Soon<span>Coming Soon ( <?php echo date("F d Y", strtotime($searchResult['Physicalproduct']['SalesDate'])); ?> )</span></p>
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
        <?php //$paginator->options(array('search_key' => 'Acda & De Munnik'));
        //$paginator->options(array('url' => $this->data['Home']));
        $paginator->options(array('url' => array("?"=>$searchKey)));
        ?>
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
  	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div id="genreAdvSearch">
	Can't find what you are looking for, try our <?php echo $html->link('Advanced Search', array('controller' => 'homes', 'action' => 'advance_search')); ?>.
</div>