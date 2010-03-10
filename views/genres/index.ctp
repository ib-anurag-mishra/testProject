<?php echo $javascript->link('freegal_genreall_curvy'); ?>
<div id="genreAll">
	<?php
	$i = 0;
	foreach($categories as $category)
	{
		if($i%2 == '0')
		{?>
		
		<div id="genretl">
			<div id="genreAlltl">
				<span class="genreTitle"><?php echo $category['Genre']; ?></span>
				<span class="genreSeeAll"><a href="genre/<?php echo $category['Genre']; ?>">See All</a></span>
			</div>
			<div id="genreAllSongtl">
		<?php }
		else
		{?>
			<div id="genretr">
			<div id="genreAlltr">
				<span class="genreTitle"><?php echo $category['Genre']; ?></span>
				<span class="genreSeeAll"><a href="genre/<?php echo $category['Genre']; ?>">See All</a></span>
			</div>
			<div id="genreAllSongtr">
		<?php }
		
		$j = 0;
		foreach($category as $catG)
		{
			if($j < 3)
			{?>
			<div class="smAlbumArtwork">
				<a href="http://music.freegalmusic.com<?php echo $catG['AlbumArtwork']; ?>" rel="image" onclick="javascript: show_uploaded_images('http://music.freegalmusic.com<?php echo $catG['AlbumArtwork']; ?>')">
					<img src="http://music.freegalmusic.com<?php echo $catG['AlbumArtwork']; ?>" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				<?php echo $catG['Song']; ?><br />
				<a href="artist/<?php echo $catG['Artist']; ?>"><?php echo $catG['Artist']; ?></a><br />
				<?php echo $catG['Album']; ?><br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
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
	
			
	<!--		<div class="smAlbumArtwork">
				<a href="/img/album_artwork/celinedion.jpg" rel="image" onclick="javascript: show_uploaded_images('/img/album_artwork/celinedion.jpg')">
					<img src="/img/album_artwork/celinedion.jpg" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/album_artwork/chaconne.jpg" rel="image" onclick="javascript: show_uploaded_images('/img/album_artwork/chaconne.jpg')">
					<img src="/img/album_artwork/chaconne.jpg" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
		</div>
	</div>
	<div id="genretr">
		<div id="genreAlltr">
			<span class="genreTitle">Country</span>
			<span class="genreSeeAll"><a href="genre/country">See All</a></span>
		</div>
		<div id="genreAllSongtr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
		</div>
	</div>
		<br class="clr">
	<div id="genrebl">
		<div id="genreAllbl">
			<span class="genreTitle">Pop</span>
			<span class="genreSeeAll"><a href="genre/pop">See All</a></span>
		</div>
		<div id="genreAllSongbl">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
		</div>
	</div>
	<div id="genrebr">
		<div id="genreAllbr">
			<span class="genreTitle">Rap</span>
			<span class="genreSeeAll"><a href="genre/rap">See All</a></span>
		</div>
		<div id="genreAllSongbr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
			<br class="clr">
			<div class="smAlbumArtwork">
				<a href="/img/box.png" rel="image" onclick="javascript: show_uploaded_images('/img/box.png')">
					<img src="/img/box.png" width="60" height="60" border="0">
				</a>
			</div>
			<div class="songSample">
				<a href='#'><img src='/img/button.png'></a>
			</div>
			<div class="songData">
				Song Title<br />
				<a href="artist/artist">Artist</a><br />
				Album<br />
			</div>
			<div class="songDownload">
				<a href="#">Download Now</a>
			</div>
		</div>
	</div>-->
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