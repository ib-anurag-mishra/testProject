<script type="text/javascript" src="/js/ajaxify-html5.js"></script>
<?php

if (count($artistList) > 0)
{
	for ($i = 0; $i < count($artistList); $i++)
	{

		//$ArtistName = $this->getTextEncode($artistList[$i]['Song']['ArtistText']);
                $ArtistName = $artistList[$i]['Song']['ArtistText'];
		if ($ArtistName != "")
		{
			echo " <li>";
			$selected = (str_replace('/', '@', base64_encode($artistList[$i]['Song']['ArtistText'])) == $this->Session->read('calledArtist')) ? "class='selected'" : "";
			$ArtistName = str_replace("'", '', ($ArtistName));
			$url = "artists/album_ajax/" . str_replace('/', '@', base64_encode($artistList[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
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