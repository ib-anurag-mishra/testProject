<?php
/*
  File Name : advance_search.ctp
  File Description : View page for advance search
  Author : m68interactive
 */

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

function truncate_text($text, $char_count, $obj = null, $truncateByWord = true)
{

    if (strlen($text) > $char_count)
    {
        $modified_text = substr($text, 0, $char_count);
        if($truncateByWord == true){
            $modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
        }
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

<div class="breadcrumbs">
    <?php
    $html->addCrumb(__('Search Results', true), '/search/index');
    echo $html->getCrumbs(' > ', __('Home', true), '/homes');
    ?>
</div>
<?php
    switch ($type)
    {
        case 'song':
            $search_category = 'search-results-songs-page';
            break;
        case 'album':
            $search_category = 'search-results-albums-page';
            break;
        case 'genre':
            $search_category = 'search-results-genres-page';
            break;
        case 'label':
            $search_category = 'Label';
            break;
        case 'video':
            $search_category = 'search-results-videos-page';
            break;
        case 'artist':
            $search_category = 'search-results-artists-page';
            break;
        case 'composer':
            $search_category = 'search-results-composers-page';
            break;
        case 'all':
            $search_category = 'search-results-all-page';
            break;
        default:
            break;
    }             
 ?>     
<section class="<?php echo $search_category; ?>">
        <div class="faq-link">Need Help? Visit our <a href="/questions">FAQ Section.</a></div>
        <div class="search-results-heading">Results for your search <?php echo $keyword; ?></div>
        <div class="search-results-text"><span><?php count($albumData); ?></span> Albums, <span><?php count($artists); ?></span> Artists, <span><?php count($composers); ?></span> Composers, <span><?php count($videos); ?></span> Videos, <span><?php count($genres); ?></span> Genres, <span><?php count($songs); ?></span> Songs</div>
        <div class="refine-text">Not what you're looking for? Refine your search below.</div>
        <div class="filter-container clearfix">
                <a class="active" href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=all">All Music</a>
                <a href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=album">Albums</a>
                <a href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=artist">Artists</a>
                <a href="/search/index?q=<?php echo $keyword; ?>&type=composer">Composers</a>
                <a href="/search/index?q=<?php echo $keyword; ?>&type=genre">Genres</a>
                <a href="/search/index?q=<?php echo $keyword; ?>&type=video">Videos</a>
                <a class="last" href="/search/index?q=<?php echo $keyword; ?>&type=song">Songs</a>                

                <div class="search-container">
                        <form method="get" id="searchQueryForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="ajaxSearchPage();
                                return false;">
                            <input type="search" name="q" id="query" value="<?php echo urldecode($keyword); ?>"/>
                            <input type="hidden" id="search_type" value="<?php echo (isset($type) && !empty($type)) ? $type : 'all' ?>" name="type">
                            <input type="submit" id="search-page-go" value="Go">
                        </form>                                                                
                </div>
        </div>


       <?php if(!empty($type) && $type != 'all') {  ?>

                <?php
                    switch ($type)
                    {
                        case 'song':
                           $search_category = 'search-results-songs-page';
                            break;
                        case 'album':
                            $search_category = 'search-results-albums-page';
                            break;
                        case 'genre':
                             
			     ?>
                            <header>
			       <h3 class="genres-header">More Genres Like <span><?php echo $keyword; ?></span></h3>
			    </header>
			    <div class="search-results-list">
                             <?php
                              if (!empty($genres))
                              {
                             ?>
			     <ul>
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
                                  <li><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $this->getTextEncode($genre_name_text); ?> (<?php echo $count; ?>)</a></li>
                                     <?php
                                     $i++;
                                                
                                    }
				   ?>
			           </ul>

 				   <?php
                                      $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                }
                                else
                                {
                                 ?>
                                  <div style="color:red; padding:50px; ">
                                     <span>No Genres Found</span>
                                   </div>
                                 <?php
                                  }
                                 ?>

				</div>

                                <?php
                            break;
                        case 'label':
                            $search_category = 'Label';
                            break;
                        case 'video':
                            $search_category = 'search-results-videos-page';
                            break;
                        case 'artist':
                          
				?>
                            	<header>
				<h3 class="artists-header">More Artists Like <span><?php echo $keyword; ?></span></h3>
				</header>
				<div class="search-results-list">
                                <?php
                                    if (!empty($artists))
                                    {
                                        ?>
				  <ul>
								
				<?php
                                   $i = 0;
                                   foreach ($artists as $artist){
                                  	 $artist_name = str_replace('"', '', $artist->ArttistText);
                                   	 $artist_name_text = truncate_text($artist_name, 30, $this);
                                         $tilte = urlencode($artist->ArtistText);
                                         $count = $artist->numFound;
                                         $link = $html->link(str_replace('"', '', truncate_text($artist->ArtistText, 30, $this)) . " (" . $count . ")", array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))), array('title' => str_replace('"', '', $artist->ArtistText)));
                                  ?>
					<li><?php echo $link; ?></li>
				  <?php
                                     $i++;
				     }
				   ?>
			           </ul>

 				   <?php
                                        $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
                                        ?>
                                        <div style="color:red; padding:50px; ">
                                            <span>No Artists Found</span>
                                        </div>
                                        <?php
                                    }
                                    ?>

				</div>

                                <?php

                            break;
                        case 'composer': 
			
			    ?>
                            	<header>
				<h3 class="composers-header">More Composers Like <span><?php echo $keyword; ?></span></h3>
				</header>
				<div class="search-results-list">
                                <?php
                                    if (!empty($composers))
                                    {
                                        ?>
				  <ul>
								
				<?php
                                  $i = 0;
                                  foreach ($composers as $composer)
                                  {
                                     $composer_name = str_replace('"', '', $composer->Composer);
                                     $composer_name = truncate_text($composer_name, 30, $this);
                                     $tilte = urlencode($composer->Composer);
                                     $name = $composer->Composer;
                                     $count = $composer->numFound;
                                     $name = $this->getTextEncode($name);
                                   ?>
                                    <li><a href="<?php echo "/search/index?q=$tilte&type=composer"; ?>" title="<?php echo $this->getTextEncode($composer_name); ?>"><?php echo $this->getTextEncode($composer_name); ?> (<?php echo $count; ?>)</a></li>
                                    <?php
                                               
                                     $i++;
				   }
				   ?>
			           </ul>

 				   <?php
                                      $searchString = "?q=" . urlencode($keyword) . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                                        $pagination_str = createPagination($html, $currentPage, $facetPage, 'block', $totalFacetPages, 5, $searchString);
                                    }
                                    else
                                    {
                                        ?>
                                        <div style="color:red; padding:50px; ">
                                            <span>No Composers Found</span>
                                        </div>
                                        <?php
                                    }
                                    ?>

				</div>

                                <?php
                            break;
                        case 'all':
                            $search_category = 'search-results-all-page';
                            break;
                        default:
                            break;
                    }             
                 ?>                                                 


        <?php }else{ ?>

        <section class="category-results album-results">
                <header>
                        <h3 class="albums-header">Albums</h3>
                        <a class="see-more" href="/search/index?q=<?php echo $keyword; ?>&type=album"></a>
                </header>
                <div class="search-results-all-albums-carousel">
                    <?php if(!empty($albumData)){ ?>
                        <div class="search-results-albums">
                                <ul class="clearfix">
                                    <?php 
                                        foreach($albumData as $palbum) {  
                                            $i = 0;
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
                                            $album_title = truncate_text($this->getTextEncode($palbum->Title), 24, $this, false);
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
                                                        </a>                                                        <button class="play-btn-icon toggleable"></button>
                                                        <button class="playlist-menu-icon toggleable"></button>
                                                        <button class="wishlist-icon toggleable"></button>
                                                        <ul>
                                                                <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>
                                                                <li><a href="#">David's Favorites</a></li>
                                                                <li><a href="#">Pop</a></li>
                                                                <li><a href="#">Day After Christmas</a></li>
                                                                <li><a href="#">A really, really, long playlist name that is going to be long enough for two lines.</a></li>
                                                                <li><a href="#">80's</a></li>
                                                                <li><a href="#">90's</a></li>
                                                                <li><a href="#">Country</a></li>
                                                                <li><a href="#">Rock</a></li>
                                                                <li><a href="#">Metal</a></li>
                                                                <li><a href="#">Breakup Songs</a></li>
                                                                <li><a href="#">New Years</a></li>
                                                                <li><a href="#">Christmas</a></li>
                                                                <li><a href="#">Summer</a></li>
                                                                <li><a href="#">Road Trip</a></li>
                                                                <li><a href="#">Christian</a></li>
                                                                <li><a href="#">Cleaning</a></li>
                                                                <li><a href="#">Workout</a></li>
                                                                <li><a href="#">Running</a></li>
                                                                <li><a href="#">Romantic</a></li>
                                                        </ul> 

                                                </div>
                                                <div class="album-info">
                                                        <p class="title">
                                                            <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>" title="<?php echo $this->getTextEncode($palbum->Title); ?>">
                                                                <?php echo $album_title; ?>
                                                            </a>
                                                        </p>
                                                        <p class="artist">Genre:<span><a href="javascript:void(0)"><?php echo $album_genre; ?></a></span></p>
                                                        <p class="label"><?php echo $album_label_str; ?></p>
                                                </div>
                                        </li>
                                        <?php 
                                            $i++;
                                        } ?>
                                </ul>
                        </div>
                        <button class="sr-albums-prev"></button>
                        <button class="sr-albums-next"></button>
                     <?php } else { ?>
                                <div style="color:red; padding:50px; ">
                                    <span>No Albums Found</span>
                                </div>                        
                     <?php } ?>   
                </div>
        </section>
        <section class="category-results artist-results">
                <header>
                        <h3 class="artists-header">Artists</h3>
                        <a class="see-more" href="/search/index?q=<?php echo $keyword; ?>&type=artist"></a>
                </header>
                <div class="search-results-list">
                        <?php
                        if (!empty($artists))
                        { ?>
                            <ul>
                        <?php foreach ($artists as $artist)
                            {
                                $tilte = urlencode($artist->ArtistText);
                                $artist_name_text = truncate_text($this->getTextEncode($artist->ArtistText), 30, $this);
                                $link = $html->link(str_replace('"', '', truncate_text($artist->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($artist->ArtistText))), array('title' => $this->getTextEncode($artist->ArtistText)));
                                if (!empty($artist_name_text))
                                {
                                    ?>
                                    <li><?php echo $link; ?><span>(<?php echo $artist->numFound; ?>)</span></li>
                                    <?php
                                }
                            } ?>
                            </ul>         
                    <?php }else
                        {
                            ?>
                            <div style='color:red'><?php __("No Artists Found"); ?></div>
                        <?php } ?>                    
                </div>
        </section>
        <section class="category-results composers-results">
                <header>
                        <h3 class="composers-header">Composers</h3>
                        <a class="see-more" href="/search/index?q=<?php echo $keyword; ?>&type=composer"></a>
                </header>
                <div class="search-results-list">
                    
                        <?php
                        if (!empty($composers))
                        { ?>
                            <ul>
                          <?php  foreach ($composers as $composer)
                            {
                                $tilte = urlencode($composer->Composer);
                                $composer_name = truncate_text($this->getTextEncode($composer->Composer), 30, $this);
                                if (!empty($composer_name))
                                {
                                    ?>
                                    <li>
                                        <a href="/search/index?q=<?php echo $tilte; ?>&type=composer" title="<?php echo $this->getTextEncode($composer->Composer) ?>"><?php echo str_replace('"', '', $this->getTextEncode($composer_name)); ?></a><span>(<?php echo $composer->numFound; ?>)</span>
                                    </li>
                                    <?php
                                }
                            } ?>
                            <ul>
                        <?php }
                        else
                        {
                            ?>
                            <div style='color:red'><?php __("No Composers Found"); ?></div>  
                        <?php 
                        
                        } 
                        ?>                    
                </div>
        </section>
        <section class="category-results videos-results">
                <header>
                        <h3 class="videos-header">Videos</h3>
                        <a class="see-more" href="/search/index?q=<?php echo $keyword; ?>&type=video"></a>
                </header>
                <div class="search-results-list">
                        <ul>
                                <li><a href="#">Whenever, Wherever</a></li>
                                <li><a href="#">Poem To A Horse</a></li>
                                <li><a href="#">La Tortura</a></li>
                                <li><a href="#">No (featuring Gustavo Cerati)</a></li>
                                <li><a href="#">Don't Bother</a></li>
                        </ul>
                        <?php
                        if (!empty($videos))
                        { ?>
                              <ul>   
                        <?php foreach ($videos as $video)
                            {
                                $tilte = urlencode($video->VideoTitle);
                                $video_name_text = truncate_text($this->getTextEncode($video->VideoTitle), 30, $this);
                                $name = $this->getTextEncode($video->VideoTitle);
                                // $count = $video->numFound;
                                ?>
                                <li><a href="/search/index?q=<?php echo $tilte; ?>&type=video" title="<?php echo $name; ?>"><?php echo (($name != "false") ? $video_name_text : ""); ?></a></li>
                                <?php
                            } ?>
                              </ul>   
                      <?php  }
                        else
                        {
                            ?>
                            <div style='color:red'><?php __("No Videos Found"); ?></div>     
                        <?php } ?>                    
                </div>
        </section>
        <section class="category-results genres-results">
                <header>
                        <h3 class="genres-header">Genres</h3>
                        <a class="see-more" href="/search/index?q=<?php echo $keyword; ?>&type=genre"></a>
                </header>
                <div class="search-results-list">
                        <?php
                        if (!empty($genres))
                        { ?>
                            <ul>
                        <?php    foreach ($genres as $genre)
                            {
                                $genre_name = str_replace('"', '', $genre->Genre);
                                $tilte = urlencode($genre_name);
                                $genre_name_text = truncate_text($this->getTextEncode($genre_name), 30, $this);
                                $name = $genre->Genre;
                                $count = $genre->numFound;
                                if (!empty($genre_name_text))
                                {
                                    ?>
                                    <li><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?php echo $this->getTextEncode($genre_name); ?>"><?php echo $genre_name_text; ?><span>(<?php echo $count; ?>)</span></a></li>
                                    <?php
                                }
                            }  ?>
                            </ul>
                        <?php }
                        else
                        {
                            ?>
                            <div style='color:red'><?php __("No Genres Found"); ?></div>  
                        <?php } ?>                    
                </div>
        </section>
        <section class="category-results songs-results">
                <header>
                        <h3 class="songs-header">Songs</h3>
                </header>
                <div class="songs-results-list">
                        <div class="header-container">
                                <div class="artist-col">Artist</div>
                                <div class="artist-border header-border"></div>
                                <div class="composer-col">Composer</div>
                                <div class="composer-border header-border"></div>
                                <div class="album-col">Album</div>
                                <div class="album-border header-border"></div>
                                <div class="song-col">Song</div>
                                <button class="multi-select-icon"></button>
                                <section class="options-menu">
                                        <ul>
                                                <li><a href="#" class="select-all">Select All</a></li>
                                                <li><a href="#" class="clear-all">Clear All</a></li>
                                                <li><a href="#">Add to Wishlist</a></li>
                                                <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                        </ul>
                                        <ul class="playlist-menu">

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
                                                <li><a href="#">Playlist 11</a></li>
                                                <li><a href="#">Playlist 12</a></li>
                                                <li><a href="#">Playlist 13</a></li>
                                                <li><a href="#">Playlist 14</a></li>
                                                <li><a href="#">Playlist 15</a></li>
                                                <li><a href="#">Playlist 16</a></li>
                                                <li><a href="#">Playlist 17</a></li>
                                                <li><a href="#">Playlist 18</a></li>
                                                <li><a href="#">Playlist 19</a></li>
                                                <li><a href="#">Playlist 20</a></li>
                                        </ul>
                                </section>
                        </div>
                        <div class="rows-container">
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Cold</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">So Far Away</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Is There Love In Space</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">If I Could Fly</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">True Love Never Dies</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Yellow &amp; Green</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Cold</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">So Far Away</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Is There Love In Space</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">If I Could Fly</div>
                                        <button class="menu-btn"></button>
                                        <input type="checkbox" class="row-checkbox">
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">True Love Never Dies</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Yellow &amp; Green</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>	
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Cold</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Crossfade</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">So Far Away</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Is There Love In Space</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">If I Could Fly</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">True Love Never Dies</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>
                                <div class="row">
                                        <button class="play-btn"></button>
                                        <div class="artist-name"><a href="#">Shakira</a></div>
                                        <div class="composer-name"><a href="#">Yellow &amp; Green</a></div>
                                        <div class="album-name"><a href="#">Oral Fixation Vol. 2</a></div>
                                        <div class="song-name">Soldiers</div>
                                        <button class="menu-btn"></button>
                                        <section class="options-menu">
                                                <ul>
                                                        <li><a href="#">Download</a></li>
                                                        <li><a href="#">Add to Wishlist</a></li>
                                                        <li><a href="#" class="add-to-playlist">Add to Playlist</a></li>
                                                </ul>
                                                <ul class="playlist-menu">
                                                        <li><a href="#">Create New Playlist</a></li>
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
                                                        <li><a href="#">Playlist 11</a></li>
                                                        <li><a href="#">Playlist 12</a></li>
                                                        <li><a href="#">Playlist 13</a></li>
                                                        <li><a href="#">Playlist 14</a></li>
                                                        <li><a href="#">Playlist 15</a></li>
                                                        <li><a href="#">Playlist 16</a></li>
                                                        <li><a href="#">Playlist 17</a></li>
                                                        <li><a href="#">Playlist 18</a></li>
                                                        <li><a href="#">Playlist 19</a></li>
                                                        <li><a href="#">Playlist 20</a></li>
                                                </ul>											
                                        </section>
                                        <input type="checkbox" class="row-checkbox">
                                </div>	

                        </div>
                        <div class="pagination-container">
                                <button class="beginning"></button>
                                <button class="prev"></button>
                                <button class="page-1">1</button>
                                <button class="page-2">2</button>
                                <button class="page-3">3</button>
                                <button class="page-4">4</button>
                                <button class="page-5">5</button>
                                <button class="next"></button>
                                <button class="last"></button>
                        </div>
                </div>
        </section>
        <?php } ?>
</section>                                    

