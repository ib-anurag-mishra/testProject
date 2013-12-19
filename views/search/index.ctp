<?php

function createPagination($html, $currentPage, $facetPage, $type = 'listing', $totalPages, $pageLimitToShow, $queryString = null)
{
    $queryString = html_entity_decode($queryString);
    if ($totalPages > 1)
    {

        $part = floor($pageLimitToShow / 2);
        if ($type == 'listing')
        {
            if (1 != $currentPage)
            {
                $pagination_str .= $html->link('<<' . __('previous', true), "/search/index/" . ($currentPage - 1) . '/' . $facetPage . '/' . $queryString);
            }
            else
            {
                $pagination_str .= "&lt&ltprevious";
            }
        }
        else if ($type == 'block')
        {
            if (1 != $facetPage)
            {
                $pagination_str .= $html->link('<<' . __('previous', true), "/search/index/" . $currentPage . '/' . ($facetPage - 1) . '/' . $queryString);
            }
            else
            {
                $pagination_str .= "&lt&ltprevious";
            }
        }

        $pagination_str .= " ";
        if ($type == 'listing')
        {
            if ($currentPage <= $part)
            {
                $fromPage = 1;
                $topage = $currentPage + ($pageLimitToShow - $currentPage);
                $topage = (($topage <= $totalPages) ? $topage : $totalPages);
            }
            elseif ($currentPage >= ($totalPages - $part))
            {
                $fromPage = ($currentPage >= $totalPages) ? $totalPages - ($pageLimitToShow - 1) : (($currentPage - ($pageLimitToShow - ($totalPages - $currentPage))) + 1);
                $topage = $totalPages;
                $fromPage = (($fromPage > 1) ? $fromPage : 1);
            }
            else
            {
                $fromPage = $currentPage - $part;
                $topage = $currentPage + $part;
            }
        }
        else if ($type == 'block')
        {
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
        }

        for ($pageCount = $fromPage; $pageCount <= $topage; $pageCount++)
        {
            if ($type == 'listing')
            {
                if ($currentPage == $pageCount)
                {
                    $pagination_str .= $pageCount;
                }
                else
                {
                    $pagination_str .= $html->link($pageCount, '/search/index/' . ($pageCount) . '/' . $facetPage . '/' . $queryString);
                }
            }
            else if ($type == 'block')
            {
                if ($facetPage == $pageCount)
                {
                    $pagination_str .= $pageCount;
                }
                else
                {
                    $pagination_str .= $html->link($pageCount, '/search/index/' . $currentPage . '/' . $pageCount . '/' . $queryString);
                }
            }
            $pagination_str .= " ";
        }
        $pagination_str .= " ";

        if ($type == 'listing')
        {
            if ($currentPage != $totalPages)
            {
                $pagination_str .= $html->link(__('next', true) . '>>', '/search/index/' . ($currentPage + 1) . '/' . $facetPage . '/' . $queryString);
            }
            else
            {
                $pagination_str .= "next&gt&gt";
            }
        }
        else if ($type == 'block')
        {
            if ($facetPage != $totalPages)
            {
                $pagination_str .= $html->link(__('next', true) . '>>', '/search/index/' . $currentPage . '/' . ($facetPage + 1) . '/' . $queryString);
            }
            else
            {
                $pagination_str .= "next&gt&gt";
            }
        }
    }
    else
    {
        $pagination_str = '';
    }

    return $pagination_str;
}

function truncate_text($text, $char_count, $obj = null)
{

    if (strlen($text) > $char_count)
    {
        $modified_text = substr($text, 0, $char_count);
        $modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
        $modified_text = substr($modified_text, 0, $char_count) . "...";
    }
    else
    {
        $modified_text = $text;
    }

    return $obj->getTextEncode($modified_text);
}

//Code for check Sales date
function Get_Sales_date($sales_date_array, $country)
{
    $Sales_date = '';
    if (is_array($sales_date_array))
    {
        foreach ($sales_date_array as $TerritorySalesDate)
        {
            $Territory_date_array = explode("_", $TerritorySalesDate);
            if (is_array($sales_date_array))
            {
                $Territory = $Territory_date_array[0];
            }

            if ($country == $Territory)
            {
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
        <span>
            <?php
            $html->addCrumb(__('Search Results', true), '/search/index');
            echo $html->getCrumbs(' > ', __('Home', true), '/homes');
            ?>
        </span>
    </div> 

    <header class="clearfix"></header>

    <section class="advanced-search">
        <form method="get" id="searchQueryForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="ajaxSearchPage();
                return false;">
            <input type="search" name="q" id="query" value="<?php echo $keyword; ?>"/>
            <input type="hidden" id="search_type" value="<?php echo (isset($type) && !empty($type)) ? $type : 'all' ?>" name="type">
            <input type="button" name="submit" id="submit" value="Search" />
        </form>
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section</a>.</div>
        <ul class="clearfix">
            <li>
                <?php
                if ($type != 'all')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=all">All Music</a>
                    <?php
                }
                else
                {
                    ?>
                    <a	href="javascript:void(0)" class="active">All Music</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'album')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=album">Albums</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Albums</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'artist')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=artist">Artists</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Artists</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'composer')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=composer">Composers</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Composers</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li> 
                <?php
                if ($type != 'genre')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=genre">Genres</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Genres</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'video')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=video">Videos</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Videos</a>
                    <?php
                }
                ?>
            </li>
            <li>|</li>
            <li>
                <?php
                if ($type != 'song')
                {
                    ?>
                    <a href="/search/index?q=<?php echo $keyword; ?>&type=song">Songs</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Songs</a>
                    <?php
                }
                ?>
            </li>
        </ul>
    </section>


    <?php
    if (!empty($type) && !($type == 'all'))
    {
        switch ($type)
        {
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
                                    if (!empty($albumData))
                                    {
                                        ?>
                                        <li>
                                            <?php
                                            $i = 0;
                                            foreach ($albumData as $palbum)
                                            {


                                                $albumDetails = $album->getImage($palbum->ReferenceID);

                                                //$albumDetails = $album->getImage($palbum->ReferenceID);

                                                if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL']))
                                                {
                                                    $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
                                                    $image = Configure::read('App.Music_Path') . $albumArtwork;
                                                }
                                                else
                                                {
                                                    $image = 'no-image.jpg';
                                                }
                                                if ($page->isImage($image))
                                                {
                                                    //Image is a correct one
                                                }
                                                else
                                                {

                                                    //	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
                                                }
                                                $album_title = truncate_text($this->getTextEncode($palbum->Title), 30, $this);
                                                $album_genre = str_replace('"', '', $palbum->Genre);
                                                $album_label = $palbum->Label;
                                                $tilte = urlencode($palbum->Title);
                                                $linkArtistText = str_replace('/', '@', base64_encode($palbum->ArtistText));
                                                $linkProviderType = base64_encode($palbum->provider_type);
                                                if (!empty($album_label))
                                                {
                                                    $album_label_str = "Label: " . truncate_text($this->getTextEncode($album_label), 32, $this);
                                                }
                                                else
                                                {
                                                    $album_label_str = "";
                                                }
                                                $ReferenceId = $palbum->ReferenceID;
                                                if ($palbum->AAdvisory == 'T')
                                                {
                                                    $explicit = '<font class="explicit"> (Explicit)</font><br />';
                                                }
                                                else
                                                {
                                                    $explicit = '';
                                                }
                                                ?>
                                                <div class="album-cover-container">	
                                                    <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                                       title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                                        <img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" />
                                                    </a>
                                                    <?php
                                                    if ($this->Session->read("patron"))
                                                    {
                                                        if ($this->Session->read('library_type') == 2 && $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])
                                                        {
                                                            echo $this->Queue->getAlbumStreamNowLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]);
                                                        }
                                                    }
                                                    ?> 
                                                </div>
                                                <div class="album-title">
                                                    <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                                       title="<?php echo $this->getTextEncode($palbum->Title); ?>" >
                                                           <?php echo $album_title; ?> <?php echo $explicit; ?>
                                                    </a>
                                                </div>
                                                <div class="album-genre">Genre: <span><a href="javascript:void(0);"><?php echo $album_genre; ?></a></span></div>
                                                <div class="album-label">Label: <span><a href="javascript:void(0);"><?php echo $album_label; ?></a></span></div>
                                                <?php
                                                $i++;
                                                if (($i % 2) == 0)
                                                {
                                                    echo "</li><li>";
                                                }
                                            }
                                            ?>
                                        </li> 
                                        <?php
                                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
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
                if (!empty($pagination_str))
                {
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
                                    if (!empty($artists))
                                    {
                                        ?>
                                        <div class="rows clearfix">
                                            <?php
                                            $i = 0;
                                            foreach ($artists as $artist)
                                            {
                                                $artist_name = str_replace('"', '', $artist->ArttistText);
                                                $artist_name_text = truncate_text($artist_name, 30, $this);
                                                $tilte = urlencode($artist->ArtistText);
                                                $count = $artist->numFound;
                                                $link = $html->link(str_replace('"', '', truncate_text($artist->ArtistText, 30, $this)) . " (" . $count . ")", array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))), array('title' => str_replace('"', '', $artist->ArtistText)));
                                                ?>
                                                <div class="row"><?php echo $link; ?></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0)
                                                {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
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
                if (!empty($pagination_str))
                {
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
                                    if (!empty($composers))
                                    {
                                        ?>
                                        <div class="rows clearfix">
                                            <?php
                                            $i = 0;
                                            // print_r($composers); die;
                                            foreach ($composers as $composer)
                                            {
                                                $composer_name = str_replace('"', '', $composer->Composer);
                                                $composer_name = truncate_text($composer_name, 30, $this);
                                                $tilte = urlencode($composer->Composer);
                                                $name = $composer->Composer;
                                                $count = $composer->numFound;
                                                $name = $this->getTextEncode($name);
                                                ?>
                                                <div class="row"><a href="<?php echo "/search/index?q=$tilte&type=composer"; ?>" title="<?php echo $this->getTextEncode($composer_name); ?>"><?php echo $this->getTextEncode($composer_name); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0)
                                                {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
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
                if (!empty($pagination_str))
                {
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
                                    if (!empty($genres))
                                    {
                                        ?>
                                        <div class="rows clearfix">
                                            <?php
                                            $i = 0;
                                            foreach ($genres as $genre)
                                            {
                                                $genre_name = str_replace('"', '', $genre->Genre);
                                                $genre_name_text = truncate_text($genre_name, 30, $this);
                                                $tilte = urlencode($genre->Genre);
                                                $name = $genre->Genre;
                                                $count = $genre->numFound;
                                                ?>
                                                <div class="row"><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $this->getTextEncode($genre_name_text); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0)
                                                {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
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
                if (!empty($pagination_str))
                {
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
                                    if (!empty($labels))
                                    {
                                        ?>
                                        <div class="rows clearfix">
                                            <?php
                                            $i = 0;
                                            foreach ($labels as $label)
                                            {
                                                $label_name = str_replace('"', '', $label->Label);
                                                $label_name_text = truncate_text($label_name, 30, $this);
                                                $tilte = urlencode($label->Label);
                                                $name = $label->Label;
                                                $count = $label->numFound;
                                                ?>
                                                <div class="row"><a href="<?php echo "/search/index?q=$tilte&type=label"; ?>" title="<?php echo $this->getTextEncode($name); ?>"><?php echo $this->getTextEncode($label_name_text); ?> (<?php echo $count; ?>)</a></div>
                                                <?php
                                                $i++;
                                                if (($i % 3) == 0)
                                                {
                                                    echo "</div><div class='rows clearfix'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    else
                                    {
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
                if (!empty($pagination_str))
                {
                    ?>
                    <div class="paging_all_block">
                        <?php echo $pagination_str; ?>
                    </div>
                    <?php
                }
                break;
        }
    }
    else if ($type == 'all')
    {
        ?>
        <section class="advanced-search-results row-1 clearfix">
            <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>

            <?php /*             * *******************Album Block Started****************************** */ ?>

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
                                $i = 0;
                                foreach ($albumData as $palbum)
                                {
                                    ?>
                                    <li>
                                        <?php
                                        $albumDetails = $album->getImage($palbum->ReferenceID);
                                        //print_r($albumDetails); die;
                                        
                                        if (!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL']))
                                        {
                                            $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $albumDetails[0]['Files']['CdnPath'] . "/" . $albumDetails[0]['Files']['SourceURL']);
                                            $image = Configure::read('App.Music_Path') . $albumArtwork;
                                        }
                                        else
                                        {
                                            $image = 'no-image.jpg';
                                        }
                                        
                                        if ($page->isImage($image))
                                        {
                                            //Image is a correct one
                                        }
                                        else
                                        {
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
                                        
                                        
                                        if ($palbum->AAdvisory == 'T')
                                        {
                                            $explicit = '<font class="explicit"> (Explicit)</font><br />';
                                        }
                                        else
                                        {
                                            $explicit = '';
                                        }
                                        
                                        
                                        if (!empty($album_label))
                                        {
                                            $album_label_str = "Label: " . truncate_text($album_label, 32, $this);
                                        }
                                        else
                                        {
                                            $album_label_str = "";
                                        }
                                        
                                        ?>
                                        
                                        <div class="album-cover-container">	
                                            <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                               title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                                <img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" />
                                            </a>
                                            <?php
                                            if ($this->Session->read("patron"))
                                            {
                                                if ($this->Session->read('library_type') == 2 && $arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID])
                                                {
                                                    echo $this->Queue->getAlbumStreamNowLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]);
                                                }
                                            }
                                            ?> 
                                        </div>
                                        
                                        <div class="album-title">
                                            <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" 
                                               title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                                <?php echo $album_title; ?>
                                            </a>
                                        </div>
                                        
                                        <div class="album-genre">Genre: <span><a href="javascript:void(0);"><?php echo $album_genre; ?></a></span></div>
                                        
                                        <div class="album-label"><span><?php echo $album_label_str; ?></span></div>
                                        
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                 <?php /********************Artist Block Started*******************************/ ?>
                <div class="col-2">
                    <section class="advanced-artists">
                        <header class="clearfix">
                            <h5><?php __("Artists"); ?></h5>
                            <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=artist">See more artists</a></h6>
                        </header>
                        
                        
                    </section>
                </div>
                <?php /*********************Artist Block End****************************** */ ?>       
            </section>



        </section>
        <?
    }
    ?>

</section>
