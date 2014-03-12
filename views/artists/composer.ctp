<?php 

function truncate_text($text, $char_count, $obj = null, $truncateByWord = true) {

    if (strlen($text) > $char_count) {
        $modified_text = substr($text, 0, $char_count);
        if ($truncateByWord == true) {
            $modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
        }
        $modified_text = substr($modified_text, 0, $char_count) . "...";
    } else {
        $modified_text = $text;
    }

    return $obj->getTextEncode($modified_text);
}



?>

<section class="composer-page">
    <div class="breadcrumbs">
        <?php
            echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
            echo " > ";
            echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
            if(!empty($artisttext)){
                echo " > ";
                if (strlen($artisttext) >= 30)
                {
                    $artisttext = substr($artisttext, 0, 30) . '...';
                }
                echo $this->getTextEncode($artisttext);
            }
        ?>
    </div>
    <br class="clr">
    <header class="clearfix">
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
    </header> 
    <h3>Albums</h3>
    <div class="composer-albums">
            <?php 
            if (!empty($albumData)) {
                $i = 0;
                foreach ($albumData as $palbum) {
                    $albumDetails = $album->getImage($palbum->ReferenceID);

                    //$albumDetails = $album->getImage($palbum->ReferenceID);

                    if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])) {
                        $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
                        $image = Configure::read('App.Music_Path') . $albumArtwork;
                    } else {
                        $image = 'no-image.jpg';
                    }
                    if ($page->isImage($image)) {
                        //Image is a correct one
                    } else {

                        //	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
                    }
                    $album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
                    $album_genre = str_replace('"', '', $palbum->Genre);
                    $album_label = $palbum->Label;
                    $tilte = urlencode($palbum->Title);
                    $linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
                    $linkProviderType = base64_encode($palbum->provider_type);
                    if (!empty($album_label)) {
                        $album_label_str = "Label: " . truncate_text($this->getTextEncode($album_label), 32, $this);
                    } else {
                        $album_label_str = "";
                    }
                    $ReferenceId = $palbum->ReferenceID;
                    if ($palbum->AAdvisory == 'T') {
                        $explicit = '<font class="explicit"> (Explicit)</font><br />';
                    } else {
                        $explicit = '';
                    }
                    ?>         
                        <div class="album-detail-container">
                            <div class="cover-image">
                                <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                   title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" />
                                </a>
                            </div>
                            <div class="album-info">
                                <div class="album-title"><strong><a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>" ><?php echo $album_title; ?> <?php echo $explicit; ?></a></strong></div>
                                <div class="artist">by 
                                    <a class="more-by-artist" 
                                       href="/artists/album/<?php echo str_replace('/', '@', base64_encode($palbum->ArtistText)); ?>/<?= base64_encode($album_genre) ?>">
                                                <?php echo $this->getTextEncode($palbum->ArtistText); ?>
                                    </a>                                                        

                                </div>
                                <div class="genre">Genre: <a href="javascript:void(0)"><?php echo $album_genre; ?></a></div>
                                                <?php
                                                if ($this->Session->read("patron")) {
                                                    if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) {
                                                        //echo $this->Queue->getAlbumStreamNowLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]);
                                                        echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 3);
                                                    }
                                                    ?>
                                    <button class="menu-btn"></button>
                                    <section class="options-menu">
                                        <input type="hidden" id="<?= $ReferenceId ?>" value="album" data-provider="<?= $palbum->provider_type ?>"/>
                                        <ul>
                                            <li>
                                        <?php
                                        $wishlistInfo = $wishlist->getWishlistData($nationalTopSong['Song']['ProdID']);

                                        if ($wishlistInfo == 'Added To Wishlist') {
                                            ?>
                                                    <a href="#">Added to Wishlist</a>
                                            <?php
                                        } else {
                                            ?>
                                                    <span class="beforeClick" id="wishlist<?= $palbum->ReferenceID ?>" > <a class="add-to-wishlist no-ajaxy" href="#">Add to Wishlist</a> </span>
                                                    <span class="afterClick" style="display:none;"><a class="add-to-wishlist" href="JavaScript:void(0);">Please Wait...</a></span>
                                        <?php
                                    }
                                    ?>
                                            </li>
                                    <?php if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) { ?> 
                                                <li><a class="add-to-playlist no-ajaxy" href="#">Add to Playlist</a></li>
                                            </ul>
                                            <ul class="playlist-menu">
                                                <li><a href="#">Create New Playlist</a></li>                                                                 
                                            </ul>
                                    <?php } ?>    
                                    </section>
                                    <?php }
                                ?>                                                        
                            </div>
                        </div>
                                <?php
                                $i++;
                            }
                            ?> 
                    <section style="position: relative; width: 866px; right: 21px;" class="search-results-songs-page"> 
                        <div class="pagination-container">
                    <?php
                    $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                    $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                    echo $pagination_str;
                    ?>
                        </div>
                <?php } else { ?>
                    <div class="album-detail-container">
                        <div style="color:red; padding:50px; ">
                            <span>No Albums Found</span>
                        </div> 
                    </div>    
                <?php } ?>
    </div>    
</section>