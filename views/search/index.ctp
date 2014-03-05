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
                <button class="active">All Music</button>
                <button>Albums</button>
                <button>Artists</button>
                <button>Composers</button>
                <button>Videos</button>
                <button>Genres</button>
                <button class="last">Songs</button>
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
                            $search_category = 'search-results-genres-page';
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
							<h3 class="artists-header">More Artists Like Shakira</h3>
							
						</header>
						<div class="search-results-list">
							<ul>
								<li><a href="#">Shakira<span>(180)</span></a></li>
								<li><a href="#">Shakira Ramirez<span>(1)</span></a></li>
								<li><a href="#">Beyonce &amp; Shakira<span>(5)</span></a></li>
								<li><a href="#">Shakira's Karaoke Band<span>(1)</span></a></li>
								<li><a href="#">House of Shakira<span>(12)</span></a></li>
								<li><a href="#">Shakira<span>(180)</span></a></li>
								<li><a href="#">Shakira Ramirez<span>(1)</span></a></li>
								<li><a href="#">Beyonce &amp; Shakira<span>(5)</span></a></li>
								<li><a href="#">Shakira's Karaoke Band<span>(1)</span></a></li>
								<li><a href="#">House of Shakira<span>(12)</span></a></li>
								<li><a href="#">Shakira<span>(180)</span></a></li>
								<li><a href="#">Shakira Ramirez<span>(1)</span></a></li>
								<li><a href="#">Beyonce &amp; Shakira<span>(5)</span></a></li>
								<li><a href="#">Shakira's Karaoke Band<span>(1)</span></a></li>
								<li><a href="#">House of Shakira<span>(12)</span></a></li>
								<li><a href="#">Shakira<span>(180)</span></a></li>
								<li><a href="#">Shakira Ramirez<span>(1)</span></a></li>
								<li><a href="#">Beyonce &amp; Shakira<span>(5)</span></a></li>
								<li><a href="#">Shakira's Karaoke Band<span>(1)</span></a></li>
								<li><a href="#">House of Shakira<span>(12)</span></a></li>
							</ul>
						</div>

                                <?php

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


        <?php }else{ ?>

        <section class="category-results album-results">
                <header>
                        <h3 class="albums-header">Albums</h3>
                        <a class="see-more"></a>
                </header>
                <div class="search-results-all-albums-carousel">
                        <div class="search-results-albums">
                                <ul class="clearfix">
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira2.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira3.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira4.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira2.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira3.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira4.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                        <li>
                                                <div class="album-cover-container">
                                                        <a href="#"><img src="images/album-result-shakira2.jpg" /></a>
                                                        <button class="play-btn-icon toggleable"></button>
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
                                                        <p class="title"><a href="#">Oral Fixation Vol. 2</a></p>
                                                        <p class="artist">Genre: Pop</p>
                                                        <p class="label">Label: Epic</p>
                                                </div>
                                        </li>
                                </ul>
                        </div>
                        <button class="sr-albums-prev"></button>
                        <button class="sr-albums-next"></button>

                </div>
        </section>
        <section class="category-results artist-results">
                <header>
                        <h3 class="artists-header">Artists</h3>
                        <a class="see-more"></a>
                </header>
                <div class="search-results-list">
                        <ul>
                                <li><a href="#">Shakira <span>(180)</span></a></li>
                                <li><a href="#">Shakira Ramirez<span>(1)</span></a></li>
                                <li><a href="#">Beyonce &amp; Shakira<span>(5)</span></a></li>
                                <li><a href="#">Shakira's Karaoke Band<span>(1)</span></a></li>
                                <li><a href="#">House of Shakira<span>(12)</span></a></li>
                        </ul>
                </div>
        </section>
        <section class="category-results composers-results">
                <header>
                        <h3 class="composers-header">Composers</h3>
                        <a class="see-more"></a>
                </header>
                <div class="search-results-list">
                        <ul>
                                <li><a href="#">Shakira <span>(3)</span></a></li>
                                <li><a href="#">Shakira Mebarak<span>(5)</span></a></li>
                                <li><a href="#">Meberak Ripoli Shakira<span>(2)</span></a></li>
                                <li><a href="#">Graham Edwards<span>(1)</span></a></li>
                                <li><a href="#">Lester Mendez<span>(1)</span></a></li>
                        </ul>
                </div>
        </section>
        <section class="category-results videos-results">
                <header>
                        <h3 class="videos-header">Videos</h3>
                        <a class="see-more"></a>
                </header>
                <div class="search-results-list">
                        <ul>
                                <li><a href="#">Whenever, Wherever</a></li>
                                <li><a href="#">Poem To A Horse</a></li>
                                <li><a href="#">La Tortura</a></li>
                                <li><a href="#">No (featuring Gustavo Cerati)</a></li>
                                <li><a href="#">Don't Bother</a></li>
                        </ul>
                </div>
        </section>
        <section class="category-results genres-results">
                <header>
                        <h3 class="genres-header">Genres</h3>
                        <a class="see-more"></a>
                </header>
                <div class="search-results-list">
                        <ul>
                                <li><a href="#">Freestyle <span>(8)</span></a></li>
                                <li><a href="#">Rock <span>(14)</span></a></li>
                                <li><a href="#">Latin <span>(85)</span></a></li>
                                <li><a href="#">Latin Music <span>(125)</span></a></li>
                                <li><a href="#">Children's <span>(55)</span></a></li>
                        </ul>
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

