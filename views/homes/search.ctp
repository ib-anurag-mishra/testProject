<?php echo $javascript->link('freegal_genre_curvy'); ?>
<div id="genre">Search Results</div>
<div style="float:left;width:955px;">
<?php
if(count($searchResults) != 0){
?>
<div id="genreArtist" class="links">
	<?php echo $paginator->sort('Artist ', 'Song.ArtistText', array('url' => array("?"=>$searchKey)))  . $paginator->sort('`', 'Song.ArtistText', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<?php if(isset($composer)){?>
<div id="genreComposer" class="links">
	<?php echo $paginator->sort('Composer ', 'Participant.Name', array('url' => array("?"=>$searchKey)))  . $paginator->sort('`', 'Participant.Name', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<?php } ?>
<div id="genreAlbum" class="links" >
	<?php echo $paginator->sort('Album ', 'Song.Title', array('url' => array("?"=>$searchKey))) . $paginator->sort('`', 'Song.Title', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreTrack" class="links" <?php if(isset($composer)){ ?> style="width:230px;" <?php }else{ ?> style="width:400px;" <?php } ?>>
	<?php echo $paginator->sort('Track ', 'Song.SongTitle', array('url' => array("?"=>$searchKey))) . $paginator->sort('`',  'Song.SongTitle', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreDownload">Download</div>
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
		//	if($searchResult['Country']['Territory'] == $this->Session->read('territory')){
	?>
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">
					<p>
						<?php
							$name = $searchResult['Song']['ArtistText'];
							if (strlen($searchResult['Song']['ArtistText']) >= 19) {
								$ArtistName = substr($searchResult['Song']['ArtistText'], 0, 22) . '..';
								if (strlen($searchResult['Song']['ArtistText']) >= 60) {
									$searchResult['Song']['ArtistText'] = substr($searchResult['Song']['ArtistText'], 0, 60) . '...';
								}
								echo '<span title="'.htmlentities($searchResult['Song']['ArtistText']).'">'.$html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($name),$searchResult['Song']['ReferenceID'])).'</span>';
						?>
						<?php
							} else {
								$ArtistName = $searchResult['Song']['ArtistText'];
								echo $html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($name),$searchResult['Song']['ReferenceID']));
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
						if (strlen($searchResult['Song']['Title']) >= 19) {
							echo '<span title="'.htmlentities($searchResult['Song']['Title'], ENT_QUOTES, "UTF-8").'">' . htmlentities(substr($searchResult['Song']['Title'], 0, 19), ENT_QUOTES, "UTF-8") . '...' . '</span>'; 
						} else { 
							echo $searchResult['Song']['Title'];
						}
						
					?>
					</p>
				</td>
				<td <?php if(isset($composer)){ ?> style="width:230px;" <?php }else{ ?> style="width:400px;" <?php } ?> valign="top">
					<p>
					<?php 
						if (strlen($searchResult['Song']['SongTitle']) >= 26) {
							echo '<span title="'.htmlentities($searchResult['Song']['SongTitle'], ENT_QUOTES, "UTF-8").'">' . htmlentities(substr($searchResult['Song']['SongTitle'], 0, 26)) . '...</span>';
						} else {
							echo $searchResult['Song']['SongTitle']; 
					 	}
						if ($searchResult['Song']['Advisory'] == 'T') {
							echo '<font class="explicit"> (Explicit)</font>';
						}
						if($searchResult['Country']['SalesDate'] <= date('Y-m-d')) {
							$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Sample_Files']['CdnPath']."/".$searchResult['Sample_Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$searchResult["Song"]["ProdID"].', "'.$this->webroot.'");'));
							echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key));
							echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");'));
						}
					?>
					</p>
				</td>
				<td width="130" align="center" style="padding-left:10px" valign="top">
					<?php
						if($searchResult['Country']['SalesDate'] <= date('Y-m-d'))
						{
							if($libraryDownload == '1' && $patronDownload == '1') {
								if($searchResult['Song']['status'] != 'avail'){
									$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Full_Files']['CdnPath']."/".$searchResult['Full_Files']['SaveAsName']);
									$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
									$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						 ?>
									<p>
										<span class="beforeClick" id="song_<?php echo $searchResult["Song"]["ProdID"]; ?>">
											<![if !IE]>
												<a href='#' title='IMPORTANT: Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadOthers("<?php echo $searchResult["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a>
											<![endif]>
											<!--[if IE]>
												<a title='IMPORTANT: Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadIE("<?php echo $searchResult["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a>
											<![endif]-->
										</span>
										<span class="afterClick" id="downloading_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;float:left">Please Wait...</span>
										<span id="download_loader_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
									</p>
					<?php		}else {
									?><a href='/homes/my_history' title='You have already downloaded this song. Get it from your recent downloads'>Downloaded</a><?php
								}
							}
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
										$wishlistInfo = $wishlist->getWishlistData($searchResult["Song"]["ProdID"]);
										if($wishlistInfo == 'Added to Wishlist'){
									?>
											<p>Added to Wishlist</p>
								<?php 	}
										else { ?>
											<p>
											<span class="beforeClick" id="wishlist<?php echo $searchResult["Song"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $searchResult["Song"]["ProdID"]; ?>",this);'>Add to wishlist</a></span><span id="wishlist_loader_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
											<span class="afterClick" style="display:none;float:left">Please Wait...</span>

											</p>
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
						<span title='Coming Soon ( <?php echo date("F d Y", strtotime($searchResult['Country']['SalesDate'])); ?> )'>Coming Soon</span>
					<?php
					}
					?>
				</td>
			</tr>
	<?php
	//	}
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
<?php
	}
	else {
		echo '<table><tr><td width="180" valign="top"><p><div class="paging">No records found</div><br class="clr"></td></tr></table>';
	}
?>
</div>