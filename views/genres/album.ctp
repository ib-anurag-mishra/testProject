<?php 

function createPagination($html,$facetPage,$totalPages,$pageLimitToShow, $queryString = null)
{
    $queryString = html_entity_decode($queryString);
    if ($totalPages > 1)
    {

        $part = floor($pageLimitToShow / 2);
        if (1 != $facetPage)
        {
            $pagination_str .= $html->link('<<' . __('previous', true),  "/genres/album/" . ($currentPage - 1) . '/' . $facetPage . '/' . $queryString);
        }
        else
        {
            $pagination_str .= "&lt&ltprevious";
        }
        
        $pagination_str .= " ";
        
        if ($facetPage <= $part)
        {
            $fromPage = 1;
            $topage = $facetPage + ($pageLimitToShow - $facetPage);
            $topage = (($topage <= $totalPages) ? $topage : $totalPages);
        }
        elseif ($facetPage >= ($totalPages - $part))
        {
            $fromPage = ($facetPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($facetPage - ($pageLimitToShow - ($totalPages - $facetPage))) + 1);
            $topage = $totalPages;
            $fromPage = (($fromPage > 1) ? $fromPage : 1);
        }
        else
        {
            $fromPage = $facetPage - $part;
            $topage = $facetPage + $part;
        }


        for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++)
        {
            if ($facetPage == $pageCount)
            {
                $pagination_str .= $pageCount;
            }
            else
            {
                $pagination_str .= $html->link($pageCount, '/genres/album/' . ($pageCount) . '/' . $facetPage . '/' . $queryString);
            }
            $pagination_str .= " ";
        }
        $pagination_str .= " ";

        if ($facetPage != $totalPages)
        {
            $pagination_str .= $html->link(__('next', true) . '>>', '/genres/album/' . ($currentPage + 1) . '/' . $facetPage . '/' . $queryString);
        }
        else
        {
            $pagination_str .= "next&gt&gt";
        }
    }
    else
    {
        $pagination_str = '';
    }

    return $pagination_str;
}

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
            echo "<a href = '/../search/index?q=".$keyword."&type=genre' >Search Results</a>";
            if(!empty($keyword)){
                echo " > ";
                if (strlen($keyword) >= 30)
                {
                    $composertext = substr($keyword, 0, 30) . '...';
                }
                echo $this->getTextEncode($keyword);
            }
        ?>
    </div>
    <br class="clr">
    <header class="clearfix">
        <?php
        if (isset($keyword))
        {
            ?>
            <h2><?php echo $this->getTextEncode($keyword); ?></h2>
            <?php
        }
        ?>        
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
    </header>
    <!-- Album Section -->
    <?php
    if (!empty($albumData))
    {
        ?>
        <h3>Albums</h3>
        <div class="album-shadow-container">
            <div class="album-scrollable horiz-scroll">
                <ul style="width:4500px">
                    <?php
                    $i =0;
                    foreach ($albumData as $palbum) {
                        $albumDetails = $album->getImage($palbum->ReferenceID);

                        //$albumDetails = $album->getImage($palbum->ReferenceID);

                        if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])) {
                            $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
                            $image = Configure::read('App.Music_Path') . $albumArtwork;
                        } else {
                            $image = 'no-image.jpg';
                        }
                        $album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
                        $album_genre = str_replace('"', '', $palbum->Genre);
                        $album_label = $palbum->Label;
                        $tilte = urlencode($palbum->Title);
                        $linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
                        $linkProviderType = base64_encode($palbum->provider_type);
                        $ReferenceId = $palbum->ReferenceID;
                        ?>
                        <li>
                            <div class="album-container">
                                <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                   title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" />
                                </a>

                                <?php
                                if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]) && $this->Session->read("patron"))
                                {
                                    echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]);
                                    ?>
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                    <div class="wishlist-popover">
                                        <input type="hidden" id="<?= $palbum->ReferenceID ?>" value="album" data-provider="<?= $palbum->provider_type ?>"/> 
                                        <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>                                       
                                    </div>
                                    <?php
                                }
                                if($this->Session->read("patron")){ ?>
                                     <button class="wishlist-icon toggeable"></button>
                           <?php }
                                
                                
                                ?>
                            </div>
                            <div class="album-title">
                                <a title="<?php echo $this->getTextEncode($palbum->Title); ?>" 
                                   href="/artists/view/<?php echo str_replace('/', '@', base64_encode($palbum->ArtistText)); ?>/<?php echo $palbum->ReferenceID; ?>/<?php echo base64_encode($palbum->provider_type); ?>" >

                                    <b>
                                        <?php
                                        if (strlen($palbum->Title) >= 50)
                                        {
                                            $palbum->Title = substr($palbum->Title, 0, 50) . '...';
                                        }
                                        ?>
                                        <?php echo $this->getTextEncode($palbum->Title); ?>		
                                    </b>
                                </a>
                            </div>
                            <div class="genre">
                                <?php
                                echo __('Genre') . ": " . $html->link($this->getTextEncode($palbum->Genre), array('controller' => 'genres', 'action' => 'view', base64_encode($palbum->Genre)), array("title" => $this->getTextEncode($palbum->Genre))) . '<br />';
                                if ($palbum->AAdvisory == 'T') {
                                    echo '<span class="explicit"> (Explicit)</span>';
                                    echo '<br />';
                                }                                
                                ?>
                            </div>
                            <div class="label">
                                <?php
                                /*if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown')
                                {
                                    echo $this->getTextEncode($album['Album']['Copyright']);
                                } */
                                ?>
                            </div>
                        </li>
                        <?php
                          $i++;  
                       }
                    ?>
                </ul>
            </div>
            <?php 
		$searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString); 
                if(!empty($pagination_str)){ ?>    
                    <div  class="paging">
                    <?php
                       echo $pagination_str;   
                    ?>
                    </div>
                <?php } ?>
        </div>
        <?php
    }
    else
    {
        echo '<span> Sorry,there are no details available for this Composer.</span>';
    }
    ?>
    <br class="clr">
</section>
