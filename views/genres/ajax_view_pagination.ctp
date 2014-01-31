	                                   
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
            echo "<a onclick=\"showAllAlbumsList('" . $url . "')\" data-artist='" . $ArtistName . "' style='cursor:pointer;'  $selected >";
            echo wordwrap($ArtistName, 35, "<br />\n", TRUE);
            echo '</a>';
            echo '</li>';
        }
    }
}
?>
					