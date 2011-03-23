<?php echo $javascript->link('freegal_genreall_curvy', false); ?>
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
<div id="genreAll">
	<?php
	$i = 0;
	foreach($categories as $category_key => $category)
	{		
		if($i%2 == '0')
		{?>
		
		<div class="genretl">
			<div class="genreAlltl">
				<span class="genreTitle"><?php echo $category['Genre']; ?></span>
				<span class="genreSeeAll links">
					<?php echo $html->link('See All', array('controller' => 'genres', 'action' => 'view', base64_encode($category['Genre']))); ?>
				</span>
			</div>
			<div id="genreAllSongtl">
		<?php }
		else
		{?>
			<div class="genretr">
			<div class="genreAlltr links">
				<span class="genreTitle"><?php echo $category['Genre']; ?></span>
				<span class="genreSeeAll">
					<?php echo $html->link('See All', array('controller' => 'genres', 'action' => 'view', base64_encode($category['Genre']))); ?>
				</span>
			</div>
			<div id="genreAllSongtr">
		<?php }
		
		$j = 0;
		foreach($category as $key => $catG)
		{
			if($j < 3)
			{?>
			<div class="smAlbumArtwork">
				<?php echo $html->link(
						$html->image('http://music.freegalmusic.com' . $catG['AlbumArtwork'], array(
							"alt" => "Album Artwork", 
							"width" => "60", 
							"height" => "60"
						)),
						'http://music.freegalmusic.com' . $catG['AlbumArtwork'], array(
							'escape' => false, 
							"rel" => "image", 
							"onclick" => "show_uploaded_images();"
						)
					  );
				?>
				
			</div>
			<div class="songSample">
				<?php
					if($catG['SalesDate'] <= date('Y-m-d')) {
						$finalSongUrl = "http://music.freegalmusic.com".$catG['SampleSong'];
						$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$category_key.$key, "onClick" => 'playSample(this, "'.$category_key.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$catG["ProdId"].', "'.$this->webroot.'");'));
						echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$category_key.$key));
						echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$category_key.$key, "onClick" => 'stopThis(this, "'.$category_key.$key.'");'));
					}
				?>
			</div>
			<div class="songData">
				<?php 
					echo '<p>';
					if (strlen($catG['Song']) >= 30) { 
						echo '<span title="'.htmlentities($catG['Song']).'">' . substr($catG['Song'], 0, 30) . '...</span>';
					} else {
						echo $catG['Song'];
					}
					if ($catG['Advisory'] == 'T') {
						echo '<p class="explicit"> (Explicit)</p>';
					}
					echo '</p>';
					if (strlen($catG['Artist']) >= 30) {
						$ArtistName = substr($catG['Artist'], 0, 30) . '...';
						echo '<span title="'.htmlentities($catG['Artist']).'">' . $html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']))) . '</span>';
					} else {
						echo '<p>'. $html->link($catG['Artist'], array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']), $catG['ReferenceId'])) . '</p>';
					}
					if (strlen($catG['Album']) >= 28) {
						echo '<span title="'.htmlentities($catG['Album']).'">' . substr($catG['Album'], 0, 28) . '...</span>';
					} else {
						echo '<p>'. $catG['Album'] . '</p>';
					}
				?>
			</div>
			<div class="songDownload">
				<?php
					if($catG['SalesDate'] <= date('Y-m-d'))
					{
						if($libraryDownload == '1' && $patronDownload == '1')
						{
							if($catG['status'] != 'avail'){
								$finalSongUrl = "http://music.freegalmusic.com".$catG['SongUrl'];
								$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							?>
								<p>
									<span class="beforeClick" id="song_<?php echo $catG["ProdId"]; ?>">
										<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
											<div class="download_links_<?php echo $catG["ProdId"]; ?>"><a href='#' title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadOthers_safari("<?php echo $catG["ProdId"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a></div>
										<?php } else {?>
										<!--[if IE]>
											<div class="download_links_<?php echo $catG["ProdId"]; ?>"><a title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.' onclick='return userDownloadIE("<?php echo $catG["ProdId"]; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a></div>
										<![endif]-->
										<?php } ?>
									</span>
									<span class="afterClick" id="downloading_<?php echo $catG["ProdId"]; ?>" style="display:none;float:left">Please Wait...</span>
									<span id="download_loader_<?php echo $catG["ProdId"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
								</p>
							<?php
							}else {
									?><a href='/homes/my_history' title='You have already downloaded this song. Get it from your recent downloads'>Downloaded</a><?php
							}
						}
						else{
							if($libraryDownload != '1'){
								$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
								$wishlistCount = $wishlist->getWishlistCount();
								if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
									?> <p>Limit Exceeded</p> <?php
								}
								else{
									$wishlistInfo = $wishlist->getWishlistData($catG["ProdId"]);
									if($wishlistInfo == 'Added to Wishlist'){
										?> <p>Added to Wishlist</p>
									<?php }
									else{ ?>
										<p>
										<span class="beforeClick" id="wishlist<?php echo $catG["ProdId"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $catG["ProdId"]; ?>",this);'>Add to wishlist</a></span><span id="wishlist_loader_<?php echo $catG["ProdId"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
										<span class="afterClick" style="display:none;float:left">Please Wait...</span>
										</p>
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
						<span title="Coming Soon (<?php echo $catG['SalesDate']; ?>)"> Coming Soon </span>
						<?php
					}
					?>
			</div>
			<br class="clr">
				<?php
			}
			$j++;
		}?>
		</div>
		</div>
		<?php $i++;
	}?>
</div>
<br class="clr">
<div id="genreViewAll">
	<div id="genreViewAllBox">
		View All Genres
	</div>
	<br class="clr" />
	<table cellspacing="10" cellpadding="0" border="0" width="100%">
    <?php
	$totalRows = ceil(count($genresAll)/4);
	for ($i = 0; $i < $totalRows; $i++) {
		echo "<tr>";
		$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*2)), ($i+($totalRows*3)));
		foreach ($counters as $counter):
			if($counter < count($genresAll)) {
				echo "<td>".$html->link(ucwords($genresAll[$counter]['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genresAll[$counter]['Genre']['Genre'])))."</td>";
			}
		endforeach;
		echo '</tr>';
	}
    ?>
    </table>
</div>