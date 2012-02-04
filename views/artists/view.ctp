<div class="breadCrumb">
<?php
	$html->addCrumb(__($artistName, true), '/artists/album/'.base64_encode($artistName));
	$html->addCrumb( $albumData[0]['Album']['AlbumTitle']  , '/artists/view/'.base64_encode($artistName).'/'.$album.'/'.base64_encode($albumData[0]['Album']['provider_type']));
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<div id="artistBox">
	<?php
	if(strlen($artistName) >= 30){
		$artistName = substr($artistName, 0, 30). '...';
	}
	?>
	<?php echo $artistName; ?>
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
<?php
	foreach($albumData as $album_key => $album):
?>
		<div id="album">
			<div class="lgAlbumArtwork">
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
				<img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" width="250" height="250" border="0">
			</div>
			<div class="albumData">
				<div class="albumBox">
					<?php
					if(strlen($album['Album']['AlbumTitle']) >= 50){
						$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
					}
					?>
					<?php echo $album['Album']['AlbumTitle'];?>				</div>
				<div class="artistInfo">
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
				<div class="songBox">
					<span class="songHeader"><?php __("Tracks");?></span>
					<span class="artistHeader"><?php __("Artist");?></span>
					<span class="timeHeader"><?php __("Time");?></span>
					<span class="downloadHeader"><?php __("Download");?></span>
				</div>
				<div id="songResults">
					<?php
					$i = 1;
					foreach($albumSongs[$album['Album']['ProdID']] as  $key => $albumSong):			
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
										if($albumSong['Country']['SalesDate'] <= date('Y-m-d')) {
											echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$album_key.$key, "onClick" => 'playSample(this, "'.$album_key.$key.'", '.$albumSong["Song"]["ProdID"].', "'.$this->webroot.'");'));
											echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$album_key.$key));
											echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$album_key.$key, "onClick" => 'stopThis(this, "'.$album_key.$key.'");'));
										}
										?>
									</p>
								</td>
								<td width="340" valign="top" align="left">
									<?php
										if (strlen($albumSong['Song']['SongTitle']) >= 40) {
											echo '<span title="'.$albumSong['Song']['SongTitle'].'">'  . substr($albumSong['Song']['SongTitle'], 0, 45) . '...</span>';
										} else {
											echo '<p>' . $albumSong['Song']['SongTitle'];
										}
										if ($albumSong['Song']['Advisory'] == 'T') {
											echo '<span class="explicit"> (Explicit)</span>';
										}
									?>
									</p>
								</td>
								<td width="125" valighn="top" align="left">
									<?php
										if (strlen($albumSong['Song']['Artist']) >= 11) {
											if(strlen($albumSong['Song']['Artist']) >= 60){
												$albumSong['Song']['Artist'] = substr($albumSong['Song']['Artist'], 0, 60). '...';
											}
											echo '<span title="'.$albumSong['Song']['Artist'].'">' . substr($albumSong['Song']['Artist'], 0, 13) . '...</span>';
										} else {
											echo '<p>' . $albumSong['Song']['Artist'] . '</p>';
										}
									?>
								<td>
								<td width="50" valign="top" align="center">
									<p><?php echo $albumSong['Song']['FullLength_Duration']?></p>
								</td>
								<td width="120" valign="top" align="left" style="padding-left:30px">
										<?php
										if($albumSong['Country']['SalesDate'] <= date('Y-m-d'))
										{
											if($libraryDownload == '1' && $patronDownload == '1')
											{	
												if($albumSong['Song']['status'] != 'avail'){
										?>
													<p>
														<form method="Post" id="form<?php echo $albumSong["Song"]["ProdID"]; ?>" action="/homes/userDownload">
															<input type="hidden" name="ProdID" value="<?php echo $albumSong["Song"]["ProdID"];?>" />
															<input type="hidden" name="ProviderType" value="<?php echo $albumSong["Song"]["provider_type"]; ?>" />
															
															<span class="beforeClick" id="song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
																<a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not.");?>' onclick='userDownloadAll(<?php echo $albumSong["Song"]["ProdID"]; ?>);'><?php __('Download Now');?></a>
															</span>
															<span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;float:left"><?php __("Please Wait...");?></span>
															<span id="download_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
														</form>													
													</p>													
									<?php	
												} else {
													?><a href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __("Downloaded");?></a><?php
												}
											}											
											else{
												if($libraryDownload != '1'){
													$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
													$wishlistCount = $wishlist->getWishlistCount();
													if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
														?> <p><?php __("Limit Exceeded");?></p> <?php
													}
													else{
														$wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);
														if($wishlistInfo == 'Added to Wishlist'){
															?> <p><?php __("Added to Wishlist");?></p>
														<?php }
														else{ ?>
															<p>
																<span class="beforeClick" id="wishlist<?php echo $albumSong["Song"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $albumSong["Song"]["ProdID"]; ?>","<?php echo $albumSong["Song"]["provider_type"]; ?>" );'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
																<span class="afterClick" style="display:none;float:left;">Please Wait...</span>
															</p>
														<?php	
														}
													}
													
												}
												else{ ?>
													<p><?php __("Limit Exceeded");?></p>
												<?php	
												}												
											}
										}else{
									?>
											<span title='<?php __("Coming Soon");?> ( <?php if(isset($albumSong['Country']['SalesDate'])){ echo 
												date("F d Y", strtotime($albumSong['Country']['SalesDate']));} ?> )'>Coming Soon</span>
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
<br class="clr">