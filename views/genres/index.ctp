<?php echo $javascript->link('freegal_genreall_curvy', false); ?>
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
				<span class="genreSeeAll">
					<?php echo $html->link('See All', array('controller' => 'genres', 'action' => 'view', base64_encode($category['Genre']))); ?>
				</span>
			</div>
			<div id="genreAllSongtl">
		<?php }
		else
		{?>
			<div class="genretr">
			<div class="genreAlltr">
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
					echo '<p class="info">';
					if (strlen($catG['Song']) >= 30) { 
						echo substr($catG['Song'], 0, 30) . '...<span>' . $catG['Song'] . '</span>';
					} else {
						echo $catG['Song'];
					}
					if ($catG['Advisory'] == 'T') {
						echo '<p class="explicit"> (Explicit)</p>';
					}
					echo '</p>';
					if (strlen($catG['Artist']) >= 30) {
						$ArtistName = substr($catG['Artist'], 0, 30) . '...';
						echo '<p class="info">' . $html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']))) . '<span>' . $catG['Artist'] . '</span></p>';
					} else {
						echo '<p>'. $html->link($catG['Artist'], array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']))) . '</p>';
					}
					if (strlen($catG['Album']) >= 30) {
						echo '<p class="info">' . substr($catG['Album'], 0, 30) . '...<span>' . $catG['Album'] . '</span></p>';
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
							$finalSongUrl = "http://music.freegalmusic.com".$catG['SongUrl'];
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						?>
							<p>
								<![if !IE]>
									<a href='#' onclick='return userDownloadOthers("<?php echo $catG["ProdId"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a>
								<![endif]>
								<!--[if IE]>
									<a onclick='return userDownloadIE("<?php echo $catG["ProdId"]; ?>");' href='<?php echo $finalSongUrl; ?>'>Download Now</a>
								<![endif]-->
								<span id="download_loader_<?php echo $catG["ProdId"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
						<?php
						}
						else{
							if($libraryDownload != '1'){
								$wishlistInfo = $wishlist->getWishlistData($catG["ProdId"]);
								if($wishlistInfo == 'Added to Wishlist'){
									?> <p>Added to Wishlist</p>
								<?php }
								else{ ?>
									<p><span id="wishlist<?php echo $catG["ProdId"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $catG["ProdId"]; ?>");'>Add to wishlist</a></span><span id="wishlist_loader_<?php echo $catG["ProdId"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span></p>
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
						<p class="info">Coming Soon<span>Coming Soon ( <?php echo $catG['SalesDate']; ?>)</span></p>
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
	<?php
		// foreach($genresAll as $genre) {
		// 			echo $html->link(ucwords($genre['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genre['Genre']['Genre'])));
		// 			echo ' | ';
		// 		}
	?>
	
	<table cellspacing="10" cellpadding="0" border="0" width="100%">
    <?php
		$i=0;
        foreach ($genresAll as $genre):
            if($i%4 == 0) {
                echo "<tr><td>";        
            } else {            
                echo "<td>";
            } 
            echo $html->link(ucwords($genre['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genre['Genre']['Genre'])));
            if(($i+1)%4 == 0) {            
                echo "</td></tr>";        
            } else {            
                echo "</td>";
            }
            $i++;
        endforeach;
    ?>
    </table>
</div>