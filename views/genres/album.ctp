<?php
function createPagination($html, $currentPage, $facetPage, $type = 'listing', $totalPages, $pageLimitToShow, $queryString = null) {
    $queryString = html_entity_decode($queryString);
    if ($totalPages > 1) {
        $part = floor($pageLimitToShow / 2);
        if ($type == 'listing') {
            if (1 != $currentPage) {
                $pagination_str .= $html->link('<button class="beginning" ></button>', "/genres/album/" . $queryString, array('escape' => FALSE));
                $pagination_str .= $html->link('<button class="prev" ></button>', "/genres/album/" . ($currentPage - 1) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
            } else {
                $pagination_str .= '<button class=
"beginning" style="cursor:text;" ></button>';
                $pagination_str .= '<button class="prev" style="cursor:text;" ></button>';
            }
        } else if ($type == 'block') {
            if (1 != $facetPage) {
                $pagination_str .= $html->link('<button class="beginning"></button>', "/genres/album/" . $queryString, array('escape' => FALSE));
                $pagination_str .= $html->link('<button class="prev"></button>', "/genres/album/" . $currentPage . '/' . ($facetPage - 1) . '/' . $queryString, array('escape' => FALSE));
            } else {
                $pagination_str .= '<button class="beginning" style="cursor:text;"></button>';
                $pagination_str .= '<button class="prev" style="cursor:text;" ></button>';
            }
        }

        $pagination_str .= " ";
        if ($type == 'listing') {
            if ($currentPage <= $part) {
                $fromPage = 1;
                $topage = $currentPage + ($pageLimitToShow - $currentPage);
                $topage = (($topage <= $totalPages) ? $topage : $totalPages);
            } elseif ($currentPage >= ($totalPages - $part)) {
                $fromPage = ($currentPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($currentPage - ($pageLimitToShow - ($totalPages - $currentPage))) + 1);
                $topage = $totalPages;
                $fromPage = (($fromPage > 1) ? $fromPage : 1);
            } else {
                $fromPage = $currentPage - $part;
                $topage = $currentPage + $part;
            }
        } else if ($type == 'block') {
            if ($facetPage <= $part) {
                $fromPage = 1;
                $topage = $facetPage + ($pageLimitToShow - $facetPage);
                $topage = (($topage <= $totalPages) ? $topage : $totalPages);
            } elseif ($facetPage >= ($totalPages - $part)) {
                $fromPage = ($facetPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($facetPage - ($pageLimitToShow - ($totalPages - $facetPage))) + 1);
                $topage = $totalPages;
                $fromPage = (($fromPage > 1) ? $fromPage : 1);
            } else {
                $fromPage = $facetPage - $part;
                $topage = $facetPage + $part;
            }
        }
        $classCounter = 1;
        for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++) {
            if ($type == 'listing') {
                if ($currentPage == $pageCount) {

                    $pagination_str .= '<button class="page-' . $classCounter . '" style="cursor:text; background: none repeat scroll 0 0 #808080;
    color: #FFFFFF;" >' . $pageCount . '</button>';
                } else {

                    $pagination_str .= $html->link('<button class="page-' . $classCounter . '">' . $pageCount . '</button>', '/genres/album/' . ($pageCount) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
                }
            } else if ($type == 'block') {
                if ($facetPage == $pageCount) {

                    $pagination_str .= '<button class="page-' . $classCounter . '" style="cursor:text;background: none repeat scroll 0 0 #808080;
    color: #FFFFFF;" >' . $pageCount . '</button>';
                } else {

                    $pagination_str .= $html->link('<button class="page-' . $classCounter . '">' . $pageCount . '</button>', '/genres/album/' . $currentPage . '/' . $pageCount . '/' . $queryString, array('escape' => FALSE));
                }
            }
            $pagination_str .= " ";
            $classCounter++;
        }
        $pagination_str .= " ";

        if ($type == 'listing') {
            if ($currentPage != $totalPages) {
                $pagination_str .= $html->link('<button class="next"></button>', '/genres/album/' . ($currentPage + 1) . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
                $pagination_str .= $html->link('<button class="last"></button>', '/genres/album/' . $totalPages . '/' . $facetPage . '/' . $queryString, array('escape' => FALSE));
            } else {
                $pagination_str .= '<button class="next" style="cursor:text;"></button>';
                $pagination_str .= '<button class="last" style="cursor:text;"></button>';
            }
        } else if ($type == 'block') {
            if ($facetPage != $totalPages) {
                $pagination_str .= $html->link('<button class="next"></button>', '/genres/album/' . $currentPage . '/' . ($facetPage + 1) . '/' . $queryString, array('escape' => FALSE));
                $pagination_str .= $html->link('<button class="last"></button>', '/genres/album/' . $currentPage . '/' . $totalPages . '/' . $queryString, array('escape' => FALSE));
            } else {
                $pagination_str .= '<button class="next" style="cursor:text;"></button>';
                $pagination_str .= '<button class="last" style="cursor:text;"></button>';
            }
        }
    } else {
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

//Code for check Sales date
function Get_Sales_date($sales_date_array, $country) {
    $Sales_date = '';
    if (is_array($sales_date_array)) {
        foreach ($sales_date_array as $TerritorySalesDate) {
            $Territory_date_array = explode("_", $TerritorySalesDate);
            if (is_array($sales_date_array)) {
                $Territory = $Territory_date_array[0];
            }

            if ($country == $Territory) {
                $Sales_date = $Territory_date_array[1];
                break;
            }
        }
    }

    return $Sales_date;
}
?>

<section class="search-page">
   
 <div class="breadcrumbs">
        <?php
            echo $html->link(__('Home', true), array('controller' => 'homes', 'action' => 'index'));
            echo " > ";
            echo "<a href = '/../search/index?q=".$keyword."&type=genre' >Search Results</a>";
            if(!empty($keyword)){
                echo " > ";
                if (strlen($keyword) >= 30)
                {
                    $keyword = substr($keyword, 0, 30) . '...';
                }
                echo $this->getTextEncode($keyword);
            }
        ?>
    </div>

    <?php
    
            $search_category = 'search-results-albums-page';
           
    ?>     
    <section class="<?php echo $search_category; ?>">
        <div class="faq-link"><?php __('Need Help? Visit our'); ?> <a href="/questions"><?php __('FAQ Section'); ?>.</a></div>
	<div class="search-results-heading"><?php echo $keyword; ?></div> 
	<header>
                <h3 class="albums-header"><?php __('Albums'); ?></h3>
        </header> 
                <?php
                if (!empty($albumData)) {
		    $i = 0;
                    foreach ($albumData as $palbum) {
                        $albumDetails = $album->getImage($palbum->ReferenceID);

                        if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])) {                            
                            $albumArtwork = $this->Token->artworkToken($albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
                            $image = Configure::read('App.Music_Path') . $albumArtwork;
                        } else {
                            $image = 'no-image.jpg';
                        }
                        if ($page->isImage($image)) {
                            //Image is a correct one
                        } else { }

                        $album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
                        $album_genre = str_replace('"', '', $palbum->Genre);
                        $album_label = $palbum->Label;
			$album_copyright = $palbum->Copyright;
                        $tilte = urlencode($palbum->Title);
                        $linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
                        $linkProviderType = base64_encode($palbum->provider_type);
                        if (!empty($album_label)) {
                            $album_label_str = __('Label', true) . ": " . truncate_text($this->getTextEncode($album_label), 32, $this);
                        } else {
                            $album_label_str = "";
                        }
                        $ReferenceId = $palbum->ReferenceID;
                        if ($palbum->AAdvisory == 'T') {
                            $explicit = '<font class="explicit"> (' . __('Explicit', true) . ')</font><br />';
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
                                    <div class="genre"><?php __('Genre'); ?>: <?php echo $html->link($this->getTextEncode($album_genre), array('controller' => 'genres', 'action' => 'view', '?genre='.$album_genre), array("title" => $this->getTextEncode($album_genre))); ?> </div>
				    <div class="label"><?php __('Label'); ?>: <?php echo $album_label." ".$album_copyright; ?> </div>
                                                    <?php
                                                    if ($this->Session->read("patron")) {
                                                        if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) {
                                                            echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 3, $palbum->ReferenceID);
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
                                                        <a href="#"><?php __('Added to Wishlist'); ?></a>
                                                <?php
                                            } else {
                                                ?>
                                                        <span class="beforeClick" id="wishlist<?= $palbum->ReferenceID ?>" > <a class="add-to-wishlist no-ajaxy" href="#"><?php __('Add to Wishlist'); ?></a> </span>
                                                        <span class="afterClick" style="display:none;"><a class="add-to-wishlist" href="JavaScript:void(0);"><?php __('Please Wait'); ?>...</a></span>
                                            <?php
                                        }
                                        ?>
                                                </li>
                                        <?php if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])) { ?> 
                                                    <li><a class="add-to-playlist no-ajaxy" href="javascript:void(0);"><?php __('Add to Playlist'); ?></a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                    <li><a href="#"><?php __('Create New Playlist'); ?></a></li>                                                                 
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
                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder."&filter=".$filter;
                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                        echo $pagination_str;
                        ?>

                            </div>
                        </section>



                    <?php } else { ?>
                        <div class="album-detail-container">
                            <div style="color:red; padding:50px; ">
                                <span><?php __('No Albums Found'); ?></span>
                            </div> 
                        </div>    
                    <?php } ?>

