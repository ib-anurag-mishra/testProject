<?php echo $html->css('colorbox', false); ?>
<?php echo $javascript->link('jquery.colorbox', false); ?>
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
							"onclick" => "show_uploaded_images('http://music.freegalmusic.com" . $catG['AlbumArtwork'] . "');"
						)
					  );
				?>
				
			</div>
			<div class="songSample">
				<?php
					if($catG['SalesDate'] <= date('Y-m-d')) {
						$finalSongUrl = "http://music.freegalmusic.com".$catG['SampleSong'];
						$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;", "id" => "play_audio".$category_key.$key, "onClick" => 'playSample(this, "play_audio'.$category_key.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$catG["ProdId"].', "'.$this->webroot.'");'));
					}
				?>
			</div>
			<div class="songData">
				<?php 
					echo '<p class="info">';
					if (strlen($catG['Song']) >= 32) { 
						echo substr($catG['Song'], 0, 32) . '...<span>' . $catG['Song'] . '</span>';
					} else {
						echo $catG['Song'];
					}
					if ($catG['Advisory'] == 'T') {
						echo '<p class="explicit"> (Explicit)</p>';
					}
					echo '</p>';
					if (strlen($catG['Artist']) >= 32) {
						$ArtistName = substr($catG['Artist'], 0, 32) . '...';
						echo '<p class="info">' . $html->link($ArtistName, array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']))) . '<span>' . $catG['Artist'] . '</span></p>';
					} else {
						echo '<p>'. $html->link($catG['Artist'], array('controller' => 'artists', 'action' => 'view', base64_encode($catG['ProdArtist']))) . '</p>';
					}
					if (strlen($catG['Album']) >= 32) {
						echo '<p class="info">' . substr($catG['Album'], 0, 32) . '...<span>' . $catG['Album'] . '</span></p>';
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
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));?>
							<p><a href='#' onclick='Javascript: userDownload("<?php echo $catG["ProdId"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'>Download Now</a></p>
						<?php		}											
						else
						{
							?>
							<p>Download Limit Has Exceeded.</p>
							<?php
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
	<p>View All Genres</p>
	<?php
		foreach($genresAll as $genre) {
			echo $html->link(ucwords($genre['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genre['Genre']['Genre'])));
			echo ' | ';
		}
	?>
</div>