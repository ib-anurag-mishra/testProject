<script type="text/javascript" src="/js/ajaxify-html5.js"></script>
<?php

if (count($genres) > 0)
{
	for ($i = 0; $i < count($genres); $i++)
	{

		$ArtistName = $this->getTextEncode($genres[$i]['Song']['ArtistText']);
		if ($ArtistName != "")
		{
			echo " <li>";
			$selected = (str_replace('/', '@', base64_encode($genres[$i]['Song']['ArtistText'])) == $this->Session->read('calledArtist')) ? "class='selected'" : "";
			$ArtistName = str_replace("'", '', ($ArtistName));
			$url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($genres[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
			?>

<a href="/artists/album/<?php echo str_replace('/', '@', base64_encode($ArtistName)); ?>/<?= base64_encode($genre) ?>">
	<?php
	echo wordwrap($ArtistName, 35, "<br />\n", TRUE);
	echo '</a>';
	echo '</li>';
		}
	}
}
?>