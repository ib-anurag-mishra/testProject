<?php echo $javascript->link('freegal_genre_curvy'); ?>
<div id="genre">Search Results</div>
<div style="float:left;width:955px;">
<div id="genreArtist" class="links">
	<?php echo $paginator->sort('Artist ', 'Metadata.Artist', array('url' => array("?"=>$searchKey)))  . $paginator->sort('`', 'Metadata.Artist', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<?php if(isset($composer)){?>
<div id="genreComposer" class="links">
	<?php echo $paginator->sort('Composer ', 'Participant.Name', array('url' => array("?"=>$searchKey)))  . $paginator->sort('`', 'Participant.Name', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<?php } ?>
<div id="genreAlbum" class="links" >
	<?php echo $paginator->sort('Album ', 'Physicalproduct.Title', array('url' => array("?"=>$searchKey))) . $paginator->sort('`', 'Physicalproduct.Title', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreTrack" class="links" <?php if(isset($composer)){ ?> style="width:230px;" <?php }else{ ?> style="width:400px;" <?php } ?>>
	<?php echo $paginator->sort('Track ', 'Metadata.Title', array('url' => array("?"=>$searchKey))) . $paginator->sort('`',  'Metadata.Title', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreDownload">Download</div>
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
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">
					<p>
						<?php
							if (strlen($searchResult['Metadata']['Artist']) >= 19) {
								$ArtistName = substr($searchResult['Metadata']['Artist'], 0, 22) . '..';
								if (strlen($searchResult['Metadata']['Artist']) >= 60) {
									$searchResult['Metadata']['Artist'] = substr($searchResult['Metadata']['Artist'], 0, 60) . '...';
								}
								echo '<span title="'.htmlentities($searchResult['Metadata']['Artist']).'">'.$html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Physicalproduct']['ArtistText']),$searchResult['Physicalproduct']['ReferenceID'])).'</span>';
						?>
						<?php
							} else {
								$ArtistName = $searchResult['Metadata']['Artist'];
								echo $html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($searchResult['Physicalproduct']['ArtistText']),$searchResult['Physicalproduct']['ReferenceID']));
							}
						?>
					</p>
				</td>
				<?php if(isset($composer)){?>
				<td width="180" valign="top">
					<p>
						<?php
						if (strlen($searchResult['Participant']['Name']) >= 17) {
							$ArtistName = substr($searchResult['Participant']['Name'], 0, 17) . '...';
							echo '<span title="'.htmlentities($searchResult['Participant']['Name']).'">'.$ArtistName.'</span>';
						?>
						<?php
						} else {
							$ArtistName = $searchResult['Participant']['Name'];
							echo $ArtistName;
						} 
					?>
					</p>
				</td>
				<?php } ?>
				<td width="180" valign="top">
					<p>
					<?php
						if (strlen($searchResult['Physicalproduct']['Title']) >= 19) {
							echo '<span title="'.htmlentities($searchResult['Physicalproduct']['Title']).'">' . substr($searchResult['Physicalproduct']['Title'], 0, 19) . '...' . '</span>'; 
						} else { 
							echo $searchResult['Physicalproduct']['Title'];
						}
						
					?>
					</p>
				</td>
				<td <?php if(isset($composer)){ ?> style="width:230px;" <?php }else{ ?> style="width:400px;" <?php } ?> valign="top">
					<p>
					<?php 
						if (strlen($searchResult['Metadata']['Title']) >= 25) {
							echo '<span title="'.htmlentities($searchResult['Metadata']['Title']).'">' . substr($searchResult['Metadata']['Title'], 0, 25) . '...</span>';
						} else {
							echo $searchResult['Metadata']['Title']; 
					 	}
						if ($searchResult['Metadata']['Advisory'] == 'T') {
							echo '<font class="explicit"> (Explicit)</font>';
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
							if($libraryDownload == '1' && $patronDownload == '1') {
								$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Audio']['1']['Files']['CdnPath']."/".$searchResult['Audio']['1']['Files']['SaveAsName']);
								$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
								$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
								<p>
									<![if !IE]>
										<a href='#' title='IMPORTANT: Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadOthers("<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a>
									<![endif]>
									<!--[if IE]>
										<a title='IMPORTANT: Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadIE("<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a>
									<![endif]-->
									<span id="download_loader_<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
								</p>
					<?php	}
                            else {
								if($libraryDownload != '1'){
									$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                                    $wishlistCount = $wishlist->getWishlistCount();
                                    if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
                    ?>
										<p>Limit Exceeded</p>
					<?php
									}
                                    else{
										$wishlistInfo = $wishlist->getWishlistData($searchResult["Physicalproduct"]["ProdID"]);
										if($wishlistInfo == 'Added to Wishlist'){
									?>
											<p>Added to Wishlist</p>
								<?php 	}
										else { ?>
											<p><span id="wishlist<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>");'>Add to wishlist</a></span><span id="wishlist_loader_<?php echo $searchResult["Physicalproduct"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
								<?php
										}
                                    }
							}
							else { ?>
								<p>Limit Exceeded</p>
							<?php
							}
						}
					}
					else {
					?>
						<span title='Coming Soon ( <?php echo date("F d Y", strtotime($searchResult['Physicalproduct']['SalesDate'])); ?> )'>Coming Soon</span>
					<?php
					}
					?>
				</td>
			</tr>
	<?php
		endforeach;
	}
	else {
		echo '<td width="180" valign="top"><p>No records found</p></td>';
	}
	?>
</table>
</div>
<div class="paging">
    <?php
        $paginator->options(array('url' => array("?"=>$searchKey)));
    ?>
	<?php 
		echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
		echo "&nbsp;";
		echo $paginator->numbers();
		echo "&nbsp;";
		echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
	?>
</div>