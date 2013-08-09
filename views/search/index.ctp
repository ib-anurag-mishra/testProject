<?php
/*
  File Name : advance_search.ctp
  File Description : View page for advance search
  Author : m68interactive
 */

function createPagination($html, $currentPage, $facetPage, $type = 'listing', $totalPages, $pageLimitToShow, $queryString = null) {
    $queryString = html_entity_decode($queryString);
    if ($totalPages > 1) {

        $part = floor($pageLimitToShow / 2);
        if ($type == 'listing') {
            if (1 != $currentPage) {
                $pagination_str .= $html->link('<<' . __('previous', true), "/search/index/" . ($currentPage - 1) . '/' . $facetPage . '/' . $queryString);
            } else {
                $pagination_str .= "&lt&ltprevious";
            }
        } else if ($type == 'block') {
            if (1 != $facetPage) {
                $pagination_str .= $html->link('<<' . __('previous', true), "/search/index/" . $currentPage . '/' . ($facetPage - 1) . '/' . $queryString);
            } else {
                $pagination_str .= "&lt&ltprevious";
            }
        }

        $pagination_str .= "&nbsp;";
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

        for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++) {
            if ($type == 'listing') {
                if ($currentPage == $pageCount) {
                    $pagination_str .= $pageCount;
                } else {
                    $pagination_str .= $html->link($pageCount, '/search/index/' . ($pageCount) . '/' . $facetPage . '/' . $queryString);
                }
            } else if ($type == 'block') {
                if ($facetPage == $pageCount) {
                    $pagination_str .= $pageCount;
                } else {
                    $pagination_str .= $html->link($pageCount, '/search/index/' . $currentPage . '/' . $pageCount . '/' . $queryString);
                }
            }
            $pagination_str .= "&nbsp;";
        }
        $pagination_str .= "&nbsp;";

        if ($type == 'listing') {
            if ($currentPage != $totalPages) {
                $pagination_str .= $html->link(__('next', true) . '>>', '/search/index/' . ($currentPage + 1) . '/' . $facetPage . '/' . $queryString);
            } else {
                $pagination_str .= "next&gt&gt";
            }
        } else if ($type == 'block') {
            if ($facetPage != $totalPages) {
                $pagination_str .= $html->link(__('next', true) . '>>', '/search/index/' . $currentPage . '/' . ($facetPage + 1) . '/' . $queryString);
            } else {
                $pagination_str .= "next&gt&gt";
            }
        }
    } else {
        $pagination_str = '';
    }

    return $pagination_str;
}

function truncate_text($text, $char_count, $obj = null) {

    if (strlen($text) > $char_count) {
        $modified_text = substr($text, 0, $char_count);
        $modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
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
$html->addCrumb(__('Search Results', true), '/search/index');
echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
    </div>
    <header class="clearfix">


    </header>
    <section class="advanced-search">
        <form method="get" id="searchQueryForm" action="<?php echo $SERVER['PHP_SELF']; ?>">
            <input type="search" name="q" id="query" value="<?php echo $keyword; ?>"/>
            <input type="hidden" id="search_type" value="<?php echo (isset($type) && !empty($type)) ? $type : 'all' ?>" name="type">
            <input type="submit" name="submit" id="submit" value="Search" />
        </form>
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section</a>.</div>
        <ul class="clearfix">
            <li>
<?php
if ($type != 'all') {
    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=all">All Music</a>
    <?php
} else {
    ?>
                    <a	href="#" class="active">All Music</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'album') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=album">Albums</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Albums</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'artist') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=artist">Artists</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Artists</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'composer') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=composer">Composers</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Composers</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li> 
                <?php
                if ($type != 'genre') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=genre">Genres</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Genres</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'video') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=video">Videos</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Videos</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'song') {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=song">Songs</a>
                    <?php
                } else {
                    ?>
                    <a href="#" class="active">Songs</a>
                    <?php
                }
                ?>
            </li>
        </ul>

    </section>


                <?php
                if (!empty($type) && !($type == 'all')) {
                    switch ($type) {
                        case 'album':
                            ?>
                <section class="advanced-search-results-albums clearfix">
                    <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
                    <section class="advanced-albums">
                        <header class="clearfix">
                            <h5><?php __("Albums"); ?></h5>

                        </header>
                        <div class="advanced-albums-shadow-container">
                            <div class="advanced-albums-scrollable horiz-scroll">
                                <ul>

            <?php
            if (!empty($albumData)) {
                ?>
                                        <li>
                <?php
                $i = 0;
                foreach ($albumData as $palbum) {
                    $albumDetails = $album->getImage($palbum->ReferenceID);
                    $albumDetails = $album->getImage($palbum->ReferenceID);
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
                    $album_title = truncate_text($this->getTextEncode($palbum->Title), 30, $this);
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
                                                <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>"><img src="<?php echo $image; ?>" alt="<?php echo $this->getTextEncode($palbum->Title); ?>" width="162" height="162" /></a>
                                                <div class="album-title"><a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>" ><?php echo $album_title; ?> <?php echo $explicit; ?></a></div>
                                                <div class="album-genre">Genre: <span><a href="#"><?php echo $album_genre; ?></a></span></div>
                                                <div class="album-label">Label: <span><a href="#"><?php echo $album_label; ?></a></span></div>
                                                <?php
                                                $i++;
                                                if (($i % 2) == 0) {
                                                    echo "</li><li>";
                                                }
                                            }
                                            ?>
                                        </li> 
                <?php
                $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
            } else {
                ?>
                                        <li style="color:red">No Album Found</li>
                                            <?php
                                        }
                                        ?>
                                </ul>
                            </div>
                        </div>
                    </section>

                </section>
                                    <?php
                                    if (!empty($pagination_str)) {
                                        ?>
                    <div class="paging_all_block">
                <?php echo $pagination_str; ?>
                    </div>
                <?php
            }
            break;
        case 'artist':
            ?>
                <section class="advanced-search-results-artists clearfix">
                    <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
                    <section class="advanced-artists">
                        <header class="clearfix">
                            <h5><?php __("Artists"); ?></h5>
                        </header>
                        <div class="advanced-artists-shadow-container">
                            <div class="advanced-artists-scrollable">
                                <div class="row-wrapper">
                <?php
                if (!empty($artists)) {
                    ?>
                                        <div class="rows clearfix">
                <?php
                $i = 0;
                foreach ($artists as $artist) {
                    $artist_name = str_replace('"', '', $artist->ArttistText);
                    $artist_name_text = truncate_text($artist_name, 30, $this);
                    $tilte = urlencode($artist->ArtistText);
                    $count = $artist->numFound;
                    $link = $html->link(str_replace('"', '', truncate_text($artist->ArtistText, 30, $this)) . " (" . $count . ")", array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))));
                    ?>
                                                <div class="row"><?php echo $link; ?></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0) {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                            <?php
                                            $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                            $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                        } else {
                                            ?>
                                        <div class="rows clearfix" style="color:red">
                                            No Artists Found
                                        </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </section>

                </section>
                                    <?php
                                    if (!empty($pagination_str)) {
                                        ?>
                    <div class="paging_all_block">
                <?php echo $pagination_str; ?>
                    </div>
                <?php
            }
            break;
        case 'composer':
            ?>
                <section class="advanced-search-results-composers clearfix">
                    <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
                    <section class="advanced-composers">
                        <header class="clearfix">
                            <h5><?php __("Composers"); ?></h5>

                        </header>
                        <div class="advanced-composers-shadow-container">
                            <div class="advanced-composers-scrollable">
                                <div class="row-wrapper">
            <?php
            if (!empty($composers)) {
                ?>
                                        <div class="rows clearfix">
                <?php
                $i = 0;
                // print_r($composers); die;
                foreach ($composers as $composer) {
                    $composer_name = str_replace('"', '', $composer->Composer);
                    $composer_name = truncate_text($composer_name, 30, $this);
                    $tilte = urlencode($composer->Composer);
                    $name = $composer->Composer;
                    $count = $composer->numFound;
                    $name = $this->getTextEncode($name);
                    ?>
                                                <div class="row"><a href="<?php echo "/search/advanced_search?q=$tilte&type=composer"; ?>" title="<?php echo $name; ?>"><?php echo $this->getTextEncode($composer_name); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0) {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                            <?php
                                            $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                            $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                        } else {
                                            ?>
                                        <div class="rows clearfix" style="color:red">
                                            No Composers Found
                                        </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </section>

                </section>
            <?php
            if (!empty($pagination_str)) {
                ?>
                    <div class="paging_all_block">
                                        <?php echo $pagination_str; ?>
                    </div>
                <?php
            }
            break;
        case 'genre':
            ?>
                <section class="advanced-search-results-genres clearfix">
                    <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
                    <section class="advanced-genres">
                        <header class="clearfix">
                            <h5><?php __("Genres"); ?></h5>

                        </header>
                        <div class="advanced-genres-shadow-container">
                            <div class="advanced-genres-scrollable">
                                <div class="row-wrapper">
                <?php
                if (!empty($genres)) {
                    ?>
                                        <div class="rows clearfix">
                <?php
                $i = 0;
                foreach ($genres as $genre) {
                    $genre_name = str_replace('"', '', $genre->Genre);
                    $genre_name_text = truncate_text($genre_name, 30, $this);
                    $tilte = urlencode($genre->Genre);
                    $name = $genre->Genre;
                    $count = $genre->numFound;
                    ?>
                                                <div class="row"><a href="<?php echo "/search/advanced_search?q=$tilte&type=genre"; ?>" title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $this->getTextEncode($genre_name_text); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0) {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                            <?php
                                            $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                            $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                        } else {
                                            ?>
                                        <div class="rows clearfix" style="color:red">
                                            No Genres Found
                                        </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </section>

                </section>
            <?php
            if (!empty($pagination_str)) {
                ?>
                    <div class="paging_all_block">
                                        <?php echo $pagination_str; ?>
                    </div>
                <?php
            }
            break;
        case 'label':
            ?>
                <section class="advanced-search-results-labels clearfix">
                    <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
                    <section class="advanced-labels">
                        <header class="clearfix">
                            <h5><?php __("Labels"); ?></h5>

                        </header>
                        <div class="advanced-labels-shadow-container">
                            <div class="advanced-labels-scrollable">
                                <div class="row-wrapper">
                <?php
                if (!empty($labels)) {
                    ?>
                                        <div class="rows clearfix">
                <?php
                $i = 0;
                foreach ($labels as $label) {
                    $label_name = str_replace('"', '', $label->Label);
                    $label_name_text = truncate_text($label_name, 30, $this);
                    $tilte = urlencode($label->Label);
                    $name = $label->Label;
                    $count = $label->numFound;
                    ?>
                                                <div class="row"><a href="<?php echo "/search/advanced_search?q=$tilte&type=label"; ?>" title="<?php echo $this->getTextEncode($name); ?>"><?php echo $this->getTextEncode($label_name_text); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0) {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                            <?php
                                        } else {
                                            ?>
                                        <div class="rows clearfix" style="color:red">
                                            No Labels Found
                                        </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </section>

                </section>
                <<?php
                            if (!empty($pagination_str)) {
                                            ?>
                    <div class="paging_all_block">
                                        <?php echo $pagination_str; ?>
                    </div>
                <?php
            }
            break;
    }
} else if($type == 'all') {
    ?>      
        <section class="advanced-search-results row-1 clearfix">
            <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>

<?php /*********************Album Block Started****************************** */ ?>
            <section class="advanced-albums  clearfix">
                <div class="col-1">
                <header class="clearfix">
                    <h5><?php __("Albums"); ?></h5>
                    <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=album">See more albums</a></h6>
                </header>
                <div class="advanced-albums-shadow-container">
                    <div style="display:block" class="advanced-albums-scrollable horiz-scroll">
                        <ul>
    <?php
    foreach ($albumData as $palbum) {
        ?>
                                <li>
        <?php
        $albumDetails = $album->getImage($palbum->ReferenceID);
        // print_r($albumDetails); die;
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
        $album_title = truncate_text($this->getTextEncode($palbum->Title), 30, $this);
        $title = urlencode($palbum->Title);
        $album_genre = str_replace('"', '', $palbum->Genre);
        $tilte = urlencode($palbum->Title);
        $album_label = $palbum->Label;
        $linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
        $linkProviderType = base64_encode($palbum->provider_type);
        $ReferenceId = $palbum->ReferenceID;
        if ($palbum->AAdvisory == 'T') {
            $explicit = '<font class="explicit"> (Explicit)</font><br />';
        } else {
            $explicit = '';
        }
        if (!empty($album_label)) {
            $album_label_str = "Label: " . truncate_text($album_label, 32, $this);
        } else {
            $album_label_str = "";
        }
        ?>
                                    <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>"><img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" /></a>
                                    <div class="album-title"><a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>"><?php echo $album_title; ?></a></div>
                                    <div class="album-genre">Genre: <span><a href="#"><?php echo $album_genre; ?></a></span></div>
                                    <div class="album-label"><?php echo $album_label_str; ?></span></div>
                                </li>

                                    <?php
                                }
                                ?>
                        </ul>
                    </div>
                </div>
                    </div>
                <div class="col-2">
                    <?php /*     * *******************Artist Block Started****************************** */ ?>
 
            <section class="advanced-artists">
                <header class="clearfix">
                    <h5><?php __("Artists"); ?></h5>
                    <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=artist">See more artists</a></h6>
                </header>
                <div class="advanced-artists-shadow-container">
                    <div class="advanced-artists-scrollable">
            <?php
            if (!empty($artists)) {
                foreach ($artists as $artist=>$count) {
                    $tilte = urlencode($artist);
                    $artist_name_text = truncate_text($this->getTextEncode($artist), 30, $this);
                    $link = $html->link(str_replace('"', '', truncate_text($artist, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist))));
                    ?>
                                <div><?php echo $link; ?><span>(<?php echo $count; ?>)</span></div>
        <?php }
    } else {
        ?>
                            <div style='color:red'><?php __("No Artists Found"); ?></div>
                        <?php } ?>
                    </div>
                </div>

            </section>
<?php /*********************Artist Block End****************************** */ ?>       
                </div>
            </section>
            
    <?php /*     * *******************Album Block End****************************** */ ?>
        
        </section>
        <section class="advanced-search-results row-2 clearfix">
           
            
<?php /*********************Composer Block Started****************************** */ ?>			
            <section class="advanced-composers">
                <header class="clearfix">
                    <h5><?php __("Composers"); ?></h5>
                    <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=composer">See more composers</a></h6>
                </header>
                <div class="advanced-composers-shadow-container">
                    <div class="advanced-composers-scrollable">
    <?php
    if (!empty($composers)) {
        foreach ($composers as $composer) {
            $tilte = urlencode($composer->Composer);
            $composer_name = truncate_text($this->getTextEncode($composer->Composer), 30, $this);
            ?>
                                <div><a href="/search/index?q=<?php echo $tilte; ?>&type=composer" title='<?php echo $this->getTextEncode($composer->Composer) ?>'><?php echo str_replace('"', '', $this->getTextEncode($composer_name)); ?></a><span>(<?php echo $composer->numFound; ?>)</span></div>
        <?php }
    } else {
        ?>
                            <div style='color:red'><?php __("No Composers Found"); ?></div>  
                        <?php } ?>
                    </div>
                </div>
            </section>
                        <?php /*                         * *******************Composer Block End****************************** */ ?>
         <?php /*             * *******************Video Block Started****************************** */ ?>            
            <section class="advanced-videos">
                    <header class="clearfix">
                        <h5><?php __("Videos"); ?></h5>
                        <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=video">See more videos</a></h6>
                    </header>
                    <div class="advanced-videos-shadow-container">
                        <div class="advanced-videos-scrollable">
            <?php
            if (!empty($videos)) {
                foreach ($videos as $video) {
                    $tilte = urlencode($video->VideoTitle);
                    $video_name_text = truncate_text($this->getTextEncode($video->VideoTitle), 30, $this);
                    $name = $this->getTextEncode($video->VideoTitle);
                    // $count = $video->numFound;
                    ?>
                                    <div><a href="/search/index?q=<?php echo $tilte; ?>&type=video" title="<?php echo $name; ?>"><?php echo (($name != "false") ? $video_name_text : ""); ?></a></div>
                <?php }
            } else {
                ?>
                                     <div style='color:red'><?php __("No Videos Found"); ?></div>     
            <?php } ?>
                        </div>
                    </div>
                </section> 
            <?php /*             * *******************Video Block End****************************** */ ?>
                        <?php /*                         * *******************Genre Block Started****************************** */ ?>
            <section class="advanced-genres">
                <header class="clearfix">
                    <h5><?php __("Genres"); ?></h5>
                    <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=genre">See more genres</a></h6>
                </header>
                <div class="advanced-genres-shadow-container">
                    <div class="advanced-genres-scrollable">
            <?php
            if (!empty($genres)) {
                foreach ($genres as $genre) {
                    $genre_name = str_replace('"', '', $genre->Genre);
                    $tilte = urlencode($genre_name);
                    $genre_name_text = truncate_text($this->getTextEncode($genre_name), 30, $this);
                    $name = $genre->Genre;
                    $count = $genre->numFound;
                    ?>
                                <div><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $genre_name_text; ?><span>(<?php echo $count; ?>)</span></a></div>
                                <?php
                            }
                        } else {
                            ?>
                            <div style='color:red'><?php __("No Genres Found"); ?></div>  
                        <?php } ?>
                    </div>
                </div>
            </section>
                        <?php /*                         * *******************Genre Block End****************************** */ ?>

                        <?php /*                         * *******************Label Block Started****************************** */ ?>            
    <!--            <section class="advanced-labels">
                    <header class="clearfix">
                        <h5><?php __("Labels"); ?></h5>
                        <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=label">See more labels</a></h6>
                    </header>
                    <div class="advanced-labels-shadow-container">
                        <div class="advanced-labels-scrollable">
            <?php
            if (!empty($labels)) {
                foreach ($labels as $label) {
                    $tilte = urlencode($label->Label);
                    $label_name_text = truncate_text($this->getTextEncode($label->Label), 30, $this);
                    $name = $label->Label;
                    $count = $label->numFound;
                    ?>
                                    <div><a href="/search/index?q=<?php echo $tilte; ?>&type=label" title="<?php echo $name; ?>"><?php echo (($name != "false") ? $label_name_text : ""); ?> <span>(<?php echo $count; ?>)</span></a></div>
                <?php }
            } else {
                ?>
                                     <div style='color:red'><?php __("No Labels Found"); ?></div>     
            <?php } ?>
                        </div>
                    </div>
                </section> -->
            <?php /*             * *******************Label Block End****************************** */ ?>

            <?php /*             * *******************Video Block Started****************************** */ ?>            
<!--            <section class="advanced-labels">
                    <header class="clearfix">
                        <h5><?php __("Videos"); ?></h5>
                        <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=video">See more videos</a></h6>
                    </header>
                    <div class="advanced-labels-shadow-container">
                        <div class="advanced-labels-scrollable">
            <?php
            if (!empty($videos)) {
                foreach ($videos as $video) {
                    $tilte = urlencode($video->VideoTitle);
                    $video_name_text = truncate_text($this->getTextEncode($video->VideoTitle), 30, $this);
                    $name = $this->getTextEncode($video->VideoTitle);
                    // $count = $video->numFound;
                    ?>
                                    <div><a href="/search/index?q=<?php echo $tilte; ?>&type=video" title="<?php echo $name; ?>"><?php echo (($name != "false") ? $video_name_text : ""); ?></a></div>
                <?php }
            } else {
                ?>
                                     <div style='color:red'><?php __("No Videos Found"); ?></div>     
            <?php } ?>
                        </div>
                    </div>
                </section> 
            <?php /*             * *******************Video Block End****************************** */ ?>

-->
        </section>
        <?php } 
        
        if($type != 'video') {
            // echo $type; die;
            $reverseSortOrder = (($sortOrder=='asc')?'desc':'asc');
        ?>
    <section class="tracklist-container">
        <section class="tracklist-header clearfix">
            <a href="<?php echo "/search/index/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=artist&sortOrder=".(($sort=='artist')?$reverseSortOrder:'asc'); ?>"><span class="artist">Artist</span></a>
            <a href="<?php echo "/search/index/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=composer&sortOrder=".(($sort=='composer')?$reverseSortOrder:'asc'); ?>"><span class="composer">Composer</span></a>
            <a href="<?php echo "/search/index/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=album&sortOrder=".(($sort=='album')?$reverseSortOrder:'asc'); ?>"><span class="album">Album</span></a>
            <a href="<?php echo "/search/index/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=song&sortOrder=".(($sort=='song')?$reverseSortOrder:'asc'); ?>"><span class="song">Song</span></a>
            <span class="download">Download</span>
        </section>
        <div class="tracklist-shadow-container">
            <div class="tracklist-scrollable">
    <?php
    if (!empty($songs)) {
        $i = 1;
        $country = $this->Session->read('territory');
        foreach ($songs as $psong) {
            ?>
                        <div class="tracklist">
                            <!--<a href="#" class="preview"></a>-->
                        
                        <?php
                        if($this->Session->read("patron")){
                        echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode($psong->provider_type) . '", "' . $this->webroot . '");'));
                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                        }
                        if($this->Session->read("patron")) {
                            $style = '';
                        } else {
                            $style = 'style="left:10px"';
                        }
                        ?>
                            <div class="artist" <?php echo $style; ?>><?php echo $html->link(str_replace('"', '', truncate_text($psong->ArtistText, 20, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($psong->ArtistText)))); ?></div>
                            <a class="add-to-playlist-button" href="#"></a>
                            <div class="composer"><?php echo truncate_text(str_replace('"', '', $this->getTextEncode($psong->Composer)), 25, $this); ?></div>


                            <div class="wishlist-popover">	

                                <?php if( $this->Session->read("patron") && $this->Session->read('library_type') == 2 ){ ?>
                                <div class="playlist-options">
                                    <ul>
                                        <li><a href="#">Create New Queue</a></li>
                                        <li><a href="#">Playlist 1</a></li>
                                        <li><a href="#">Playlist 2</a></li>
                                        <li><a href="#">Playlist 3</a></li>
                                        <li><a href="#">Playlist 4</a></li>
                                        <li><a href="#">Playlist 5</a></li>
                                        <li><a href="#">Playlist 6</a></li>
                                        <li><a href="#">Playlist 7</a></li>
                                        <li><a href="#">Playlist 8</a></li>
                                        <li><a href="#">Playlist 9</a></li>
                                        <li><a href="#">Playlist 10</a></li>
                                    </ul>
                                </div>

                                <a class="add-to-playlist" href="#">Add To Queue</a>
                                <?php } ?>
                                <?php
                                    if($this->Session->read("patron")){
                                    $wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
                                    echo $wishlist->getWishListMarkup($wishlistInfo,$psong->ProdID,$psong->provider_type);    
                                    ?>
                                    <!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->
                                <?php
                                }
                                ?>
                                <!-- <div class="share clearfix">
                                    <p>Share via</p>
                                    <a class="facebook" href="#"></a>
                                    <a class="twitter" href="#"></a>
                                </div> -->
                            </div>
                            <!--div class="cover-art">
                                <?php
                                //$imageUrl = shell_exec('perl files/tokengen_artwork ' . $psong->ACdnPath . "/" . $psong->ASourceURL);
                                //$image = Configure::read('App.Music_Path') . $imageUrl;
                                ?>
                                <a href="/artists/view/<?php //echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?php //echo $psong->ReferenceID; ?>/<?php //echo base64_encode($psong->provider_type); ?>"><img src="<?php //echo $image; ?>" width="27" height="27" /></a> <?php /*alt="<?php echo $psong->SongTitle; ?>"*/ ?>
                            </div-->
                            <div class="album"><a href="#"><a href="/artists/view/<?php echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID; ?>/<?php echo base64_encode($psong->provider_type); ?>"><?php echo str_replace('"', '', truncate_text($this->getTextEncode($psong->Title), 15, $this)); ?></a></a></div>
                            <div class="song">
                                <?php $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>
                                <span title="<?php echo str_replace('"', '', $this->getTextEncode($showSongTitle)); ?>"><?php echo truncate_text($this->getTextEncode($psong->SongTitle), 21, $this); ?>
        <?php
        if ($psong->Advisory == 'T') {
            echo '<font class="explicit"> (Explicit)</font>';
        }
        ?>
                                </span>
                            </div>
                            <div class="download">
                                    <?php
                                    if($this->Session->read("patron")){
                                    if ($sales_date <= date('Y-m-d')) {
                                        if ($libraryDownload == '1' && $patronDownload == '1') {
                                            if ($psong->status != 'avail') {
                                                ?>
                                            <p>
                                            <form method="Post" id="form<?php echo $psong->ProdID; ?>" action="/homes/userDownload">
                                                <input type="hidden" name="ProdID" value="<?php echo $psong->ProdID; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $psong->provider_type; ?>" />
                                                <span class="beforeClick" id="song_<?php echo $psong->ProdID; ?>">
                                                    <a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not."); ?>' onclick='userDownloadAll(<?php echo $psong->ProdID; ?>);'><?php __('Download'); ?></a>
                                                </span>
                                                <span class="afterClick" id="downloading_<?php echo $psong->ProdID; ?>" style="display:none;float:left"><?php __("Please Wait..."); ?></span>
                                                <span id="download_loader_<?php echo $psong->ProdID; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                            </form>
                                            </p>
                <?php } else {
                    ?><a href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __("Downloaded"); ?></a><?php
                }
            } else {
                if ($libraryDownload != '1') {
                    $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                    $wishlistCount = $wishlist->getWishlistCount();
                    if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
                        ?>
                                                <p><?php __("Limit Met"); ?></p>
                                                <?php
                                            } else {
                                                $wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
                                                if ($wishlistInfo == 'Added to Wishlist') {
                                                    ?>
                                                    <p><?php __("Added to Wishlist"); ?></p>
                                                <?php } else {
                                                    ?>
                                                    <p>
                                                        <span class="beforeClick" id="wishlist<?php echo $psong->ProdID; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $psong->ProdID; ?>","<?php echo $song->provider_type; ?>");'><?php __("Add to wishlist"); ?></a></span><span id="wishlist_loader_<?php echo $song->ProdID; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                                        <span class="afterClick" style="display:none;float:left"><?php __("Please Wait..."); ?></span>

                                                    </p>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            ?>
                                            <p><?php __("Limit Met"); ?></p>
                    <?php
                }
            }
        } else {
            ?>
                                    <span title='<?php __("Coming Soon"); ?> ( <?php echo date("F d Y", strtotime($sales_date)); ?> )'><?php __("Coming Soon"); ?></span>
                                    <?php
                                }
                                    } else {
                                        $checklib = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
                                        if($checklib != 'www' && $checklib != 'freegal' && $checklib != '50'){
                                            echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'redirection_manager'),array('class' => 'btn'));
                                        } else {
                                            echo $this->Html->link(__('Login', true), array('controller' => 'homes', 'action' => 'chooser'),array('class' => 'btn'));
                                        }
                                    }
                                ?>
                            </div>




                        </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
            </div>
        </div>
        <div class="paging">
<?php
if (isset($type)) {
    $keyword = "?q=" . $keyword . "&type=" . $type;
}
?>
                <?php
                $keyword = $keyword . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                echo createPagination($html, $currentPage, $facetPage, 'listing', $totalPages, 7, $keyword);
                ?>
        </div>
        
    </section>
</section>
<?php } else {
    // print_r($songs); 
    ?>
<!-- for the videos -->
		<section class="tracklist-container">
			<section class="video-tracklist-header clearfix">
				<span class="artist">Artist</span>
                <span class="album">Album</span>
                <span class="video-filter-button">Video</span>
                <span class="download">Download</span>
			</section>
			<div class="video-tracklist-shadow-container">
				<div class="tracklist-scrollable">
				<?php
				
				$b=1;
				foreach($songs as $psong) {
				?>	
					<div class="tracklist">
                        <?php
                        if($this->Session->read("patron")) {
                            $style = '';
                        } else {
                            $style = 'style="left:10px"';
                        }
                        ?>
                        <div class="artist" <?php echo $style; ?>><a href="#"><?php echo $this->getTextEncode($psong->ArtistText); ?></a></div>
						<a class="add-to-playlist-button" href="#"></a>
						
						<div class="wishlist-popover">	
                            <?php
                                if($this->Session->read("patron")){
                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($psong->ProdID);
                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$psong->ProdID,$psong->provider_type);
                            ?>
							<!-- <a class="add-to-wishlist" href="#">Add To Wishlist</a> -->
							<?php
                                }
                            ?>
							<!-- <div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="#"></a>
								<a class="twitter" href="#"></a>
							</div> -->
						</div>
						<!--
						<div class="cover-art">
							<img src="images/search-results/carrieunderwood.jpg" alt="carrieunderwood" width="27" height="27" />
						</div>
						-->
                        <div class="album"><a href="#"><?php echo truncate_text($this->getTextEncode($psong->Title),25,$this); ?></a></div>
						<?php
                            //$imageUrl = shell_exec('perl files/tokengen_artwork ' . $psong->ACdnPath . "/" . $psong->ASourceURL);//"sony_test/".
                            //$image = Configure::read('App.Music_Path') . $imageUrl;
                        ?>
                        <!--div class="song"><a href="/videos/details/<?php //echo $psong->ProdID; ?>" style="float:left; margin-top:10px; padding-right:10px;"><img src="<?php //echo $image; ?>" alt="<?php //echo $this->getTextEncode($psong->SongTitle); ?>" width="34" height="27" /></a><?php ///echo $this->getTextEncode($psong->VideoTitle); ?></div-->
						<div class="download"><?php
                         if($this->Session->read("patron")){
                                    if ($sales_date <= date('Y-m-d')) {
                                        if ($libraryDownload == '1' && $patronDownload == '1') {
                                            if ($psong->status != 'avail') {
                                                ?>
                                            <p>
                                            <form method="Post" id="form<?php echo $psong->ProdID; ?>" action="/videos/download">
                                                <input type="hidden" name="ProdID" value="<?php echo $psong->ProdID; ?>" />
                                                <input type="hidden" name="ProviderType" value="<?php echo $psong->provider_type; ?>" />
                                                <span class="beforeClick" id="song_<?php echo $psong->ProdID; ?>">
                                                    <a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not."); ?>' onclick='userDownloadAll(<?php echo $psong->ProdID; ?>);'><?php __('Download'); ?></a>
                                                </span>
                                                <span class="afterClick" id="downloading_<?php echo $psong->ProdID; ?>" style="display:none;float:left"><?php __("Please Wait..."); ?></span>
                                                <span id="download_loader_<?php echo $psong->ProdID; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                            </form>
                                            </p>
                <?php } else {
                    ?><a href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __("Downloaded"); ?></a><?php
                }
            } else {
                if ($libraryDownload != '1') {
                    $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                    $wishlistCount = $wishlist->getWishlistCount();
                    if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
                        ?>
                                                <p><?php __("Limit Met"); ?></p>
                                                <?php
                                            } else {
                                                $wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
                                                if ($wishlistInfo == 'Added to Wishlist') {
                                                    ?>
                                                    <p><?php __("Added to Wishlist"); ?></p>
                                                <?php } else {
                                                    ?>
                                                    <p>
                                                        <span class="beforeClick" id="wishlist<?php echo $psong->ProdID; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $psong->ProdID; ?>","<?php echo $song->provider_type; ?>");'><?php __("Add to wishlist"); ?></a></span><span id="wishlist_loader_<?php echo $song->ProdID; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                                                        <span class="afterClick" style="display:none;float:left"><?php __("Please Wait..."); ?></span>

                                                    </p>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            ?>
                                            <p><?php __("Limit Met"); ?></p>
                    <?php
                }
            }
        } else {
            ?>
                                    <span title='<?php __("Coming Soon"); ?> ( <?php echo date("F d Y", strtotime($sales_date)); ?> )'><?php __("Coming Soon"); ?></span>
                                    <?php
                                }
                                    } else {
                                        $checklib = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'));
                                        if($checklib != 'www' && $checklib != 'freegal' && $checklib != '50'){
                                            echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'redirection_manager'),array('class' => 'btn'));
                                        } else {
                                            echo $this->Html->link(__('Login', true), array('controller' => 'homes', 'action' => 'chooser'),array('class' => 'btn'));
                                        }
                                    }
                                    ?>
                        </div>
						
						
						
				
					</div>
				<?php		
					}
				?>
				</div>
			</div>
		</section>

		
	</section>

<?php
}
?>
