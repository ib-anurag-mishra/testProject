<?php echo $javascript->link('freegal_artist_curvy'); ?>
<div id="artistBox">
	<?php
	if(strlen($artistName) >= 30){
		$artistName = substr($artistName, 0, 30). '...';
	}
	?>
	<?php echo $artistName; ?>
</div>
<br class="clr">
<?php
	foreach($albumData as $album_key => $album):
?>
		<div id="album">
			<div class="lgAlbumArtwork">
				<?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Graphic']['Files']['CdnPath']."/".$album['Graphic']['Files']['SourceURL']); ?>
				<img src="http://music.freegalmusic.com<?php echo $albumArtwork; ?>" width="250" height="250" border="0">
			</div>
			<div class="albumData">
				<div class="albumBox">
					<?php
					if(strlen($album['Physicalproduct']['Title']) >= 50){
						$album['Physicalproduct']['Title'] = substr($album['Physicalproduct']['Title'], 0, 50). '...';
					}
					?>
					<?php echo $album['Physicalproduct']['Title'];?>				</div>
				<div class="artistInfo">
					<?php
						echo $html->link(__('Genre: ').$album['Genre']['Genre'], array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) . '<br />';
						if ($album['Metadata']['ArtistURL'] != '') {
							echo $html->link('http://' . $album['Metadata']['ArtistURL'], 'http://' . $album['Metadata']['ArtistURL'], array('target' => 'blank'));
							echo '<br />';
						}
						if ($album['Metadata']['Label'] != '') {
							echo 'Label: ' . $album['Metadata']['Label'];
							echo '<br />';
						}
						if ($album['Metadata']['Copyright'] != '' && $album['Metadata']['Copyright'] != 'Unknown') {
							echo $album['Metadata']['Copyright'];
						}
					?>
				</div>
				<div class="songBox">
					<span class="songHeader">Tracks</span>
					<span class="artistHeader">Artist</span>
					<span class="timeHeader">Time</span>
					<span class="downloadHeader">Download</span>
				</div>
				<div id="songResults">
					<?php
					$i = 1;
					foreach($albumSongs[$album['Physicalproduct']['ReferenceID']] as  $key => $albumSong):			
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr <?php echo $class; ?>>
								<td width="20" valign="top" align="center">
									<p>
									<?php
										if($albumSong['Physicalproduct']['SalesDate'] <= date('Y-m-d')) {
											$songUrl = shell_exec('perl files/tokengen ' . $albumSong['Audio'][0]['Files']['CdnPath']."/".$albumSong['Audio'][0]['Files']['SaveAsName']);
											$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
											$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
											echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$album_key.$key, "onClick" => 'playSample(this, "'.$album_key.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$albumSong["Physicalproduct"]["ProdID"].', "'.$this->webroot.'");'));
											echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$album_key.$key));
											echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$album_key.$key, "onClick" => 'stopThis(this, "'.$album_key.$key.'");'));
										}
										?>
									</p>
								</td>
								<td width="340" valign="top" align="left">
									<p>
									<?php
										if (strlen($albumSong['Metadata']['Title']) >= 40) {
											echo '<span title="'.htmlentities($albumSong['Metadata']['Title']).'">'  . substr($albumSong['Metadata']['Title'], 0, 40) . '...</span>';
										} else {
											echo '<p>' . $albumSong['Metadata']['Title'];
										}
										if ($albumSong['Metadata']['Advisory'] == 'T') {
											echo '<span class="explicit"> (Explicit)</span>';
										}
									?>
									</p>
								</td>
								<td width="125" valighn="top" align="left">
									<?php
										if (strlen($albumSong['Metadata']['Artist']) >= 11) {
											if(strlen($albumSong['Metadata']['Artist']) >= 60){
												$albumSong['Metadata']['Artist'] = substr($albumSong['Metadata']['Artist'], 0, 60). '...';
											}
											echo '<span title="'.htmlentities($albumSong['Metadata']['Artist']).'">' . substr($albumSong['Metadata']['Artist'], 0, 11) . '...</span>';
										} else {
											echo '<p>' . $albumSong['Metadata']['Artist'] . '</p>';
										}
									?>
								<td>
								<td width="50" valign="top" align="center">
									<p><?php echo $albumSong['Audio']['1']['Duration']?></p>
								</td>
								<td width="150" valign="top" align="center">
									<?php
										if($albumSong['Physicalproduct']['SalesDate'] <= date('Y-m-d'))
										{
											if($libraryDownload == '1' && $patronDownload == '1')
											{	
									?>
												<p>
													<a href='#' title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownload("<?php echo $albumSong['Physicalproduct']['ProdID'];?>");'>Download Now</a>
													<span id="download_loader_<?php echo $albumSong["Physicalproduct"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
												</p>
									<?php		}											
											else{
												if($libraryDownload != '1'){
													$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
													$wishlistCount = $wishlist->getWishlistCount();
													if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
														?> <p>Limit Exceeded</p> <?php
													}
													else{
														$wishlistInfo = $wishlist->getWishlistData($albumSong["Physicalproduct"]["ProdID"]);
														if($wishlistInfo == 'Added to Wishlist'){
															?> <p>Added to Wishlist</p>
														<?php }
														else{ ?>
															<p><span id="wishlist<?php echo $albumSong["Physicalproduct"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $albumSong["Physicalproduct"]["ProdID"]; ?>");'>Add to wishlist</a></span><span id="wishlist_loader_<?php echo $albumSong["Physicalproduct"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
														<?php	
														}
													}
													
												}
												else{ ?>
													<p>Limit Exceeded</p>
												<?php	
												}												
											}
										}else{
									?>
											<span title='Coming Soon ( <?php echo 
												date("F d Y", strtotime($albumSong['Physicalproduct']['SalesDate'])); ?> )'>Coming Soon</span>
									<?php
										}
									?>	
								</td>
							</tr>
						</table>
					<?php
					endforeach;
					?>
				</div>
			</div>
		</div>
<?php
	endforeach;
?>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>