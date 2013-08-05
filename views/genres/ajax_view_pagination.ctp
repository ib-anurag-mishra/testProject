	                                   
    <?php

    if(count($genres) > 0){                                                    
        for ($i = 0; $i < count($genres); $i++) {
                echo " <li>";
                $ArtistName = $this->getTextEncode($genres[$i]['Song']['ArtistText']);                                                       
                $url = "artists/album_ajax/" . str_replace('/','@',base64_encode($genres[$i]['Song']['ArtistText'])) . "/" . base64_encode($genre);
                echo "<a onclick=\"showAllAlbumsList('".$url."')\" data-artist='".$ArtistName."' style='cursor:pointer;' data-letter='".totalPages."'>";
                echo wordwrap($ArtistName, 35, "<br />\n", TRUE);
                echo '</a>';
                echo '</li>';                                                                    
        }
    }
    ?>
					