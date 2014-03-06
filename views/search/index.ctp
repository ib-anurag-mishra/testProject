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
        <div class="search-results-text"><span><?php echo ((empty($albumData)) ? '0' : count($albumData)) ?></span> Albums,<span><?php echo ((empty($artists)) ? '0' : count($artists)) ?></span> Artists, <span><?php echo ((empty($composers)) ? '0' : count($composers)) ?></span> Composers, <span><?php echo ((empty($videos)) ? '0' : count($videos)) ?></span> Videos, <span><?php echo ((empty($genres)) ? '0' : count($genres)) ?></span> Genres, <span><?php echo ((empty($songs)) ? '0' : count($songs)) ?></span> Songs</div>
        <div class="refine-text">Not what you're looking for? Refine your search below.</div>
        <div class="filter-container clearfix">
                <?php
                if ($type != 'all')
                {
                    ?>
                    <a href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=all">All Music</a>
                    <?php
                }
                else
                {
                    ?>
                    <a	href="javascript:void(0)" class="active">All Music</a>
                    <?php
                }
                ?>
                <?php
                if ($type != 'album')
                {
                    ?>
                    <a href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=album">Albums</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Albums</a>
                    <?php
                }
                ?>
                <?php
                if ($type != 'artist')
                {
                    ?>
                    <a href="/search/index?q=<?php echo htmlspecialchars($keyword); ?>&type=artist">Artists</a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="javascript:void(0)" class="active">Artists</a>
                    <?php
                }
                ?>
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
                        $reverseSortOrder = (($sortOrder == 'asc') ? 'desc' : 'asc');
     ?>
		<header>
			<h3 class="songs-header">Songs</h3>
		</header>

<div class="header-container">
							<button class="top-songs-filter-icon"></button>
							<div class="top-songs-filter-menu">
								<ul>
									<li><a href="#">All Genres</a></li>
									<li><a href="#">Rock</a></li>
									<li><a href="#">Country</a></li>
									<li><a href="#">Pop</a></li>
								</ul>
							</div>
							<div class="song-header"><a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=song&sortOrder=" . (($sort == 'song') ? $reverseSortOrder : 'asc'); ?>"><span class="song">Song</span></a></div>
							<div class="song-border header-border"></div>
							<div class="artist-header"> <a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=artist&sortOrder=" . (($sort == 'artist') ? $reverseSortOrder : 'asc'); ?>"><span class="artist">Artist</span></a></div>
							<div class="artist-border header-border"></div>
							<div class="album-header"><a href="<?php echo "/search/index/" . $currentPage . "/" . $facetPage . "/?q=" . $keyword . "&type=" . $type . "&sort=album&sortOrder=" . (($sort == 'album') ? $reverseSortOrder : 'asc'); ?>"><span class="album">Album</span></a></div>
							<div class="album-border header-border"></div>
							<div class="time-header">Time</div>
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

 <?php 
                    if (!empty($songs))
                    {
                        $i = 1;
                        $country = $this->Session->read('territory');
                        foreach ($songs as $psong)
                        {

                            $downloadFlag = $this->Search->checkDownloadForSearch($psong->TerritoryDownloadStatus, $psong->TerritorySalesDate, $this->Session->read('territory'));
                            $StreamFlag = $this->Search->checkStreamingForSearch($psong->TerritoryStreamingStatus, $psong->TerritoryStreamingSalesDate, $this->Session->read('territory'));

                            //if song not allowed for streaming and not allowed for download then this song must not be display
                            if ($downloadFlag === 0 && $StreamFlag === 0)
                            {
                                continue;
                            }
                            ?>

							<div class="row">

<?php
                                if ($this->Session->read('library_type') == 2)
                                {
                                    $filePath = shell_exec('perl files/tokengen_streaming ' . $psong->CdnPathFullStream . "/" . $psong->SaveAsNameFullStream);
                                  

                                    if (!empty($filePath))
                                    {
                                        $songPath = explode(':', $filePath);
                                        $streamUrl = trim($songPath[1]);
                                        $psong->streamUrl = $streamUrl;
                                        $psong->totalseconds = $this->Queue->getSeconds($psong->FullLength_Duration);
                                    }
                                }
                                ?>


                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    if ($this->Session->read('library_type') == 2 && ($StreamFlag === 1))
                                    {
                                        if ('T' == $psong->Advisory)
                                        {
                                            $song_title = $psong->SongTitle . '(Explicit)';
                                        }
                                        else
                                        {
                                            $song_title = $psong->SongTitle;
                                        }
                                        echo $html->image('', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'loadSong("' . $psong->streamUrl . '", "' . base64_encode($song_title) . '","' . base64_encode($psong->ArtistText) . '",' . $psong->totalseconds . ',"' . $psong->ProdID . '","' . $psong->provider_type . '");'));
                                    }
                                    else
                                    {
                                        echo $html->image('', array("class" => "preview play-btn", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $psong->ProdID . ', "' . base64_encode($psong->provider_type) . '", "' . $this->webroot . '");'));
                                        echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                        echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                                    }
                                }

                                if ($this->Session->read("patron"))
                                {
                                    $style = '';
                                    $styleSong = '';
                                }
                                else
                                {
                                   // $style = 'style="left:10px"';
                                   // $styleSong = "style='left:570px'";
                                }
                                ?>

							<!--	<button class="play-btn"></button> -->
								<div class="artist artist-name"><?php echo $html->link(str_replace('"', '', truncate_text($psong->ArtistText, 20, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/', '@', base64_encode($psong->ArtistText))), array('title' => $this->getTextEncode($psong->ArtistText))); ?></div>
								<div class="album album-name"><a href="/artists/view/<?php echo str_replace('/', '@', base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID; ?>/<?php echo base64_encode($psong->provider_type); ?>" title="<?php echo $this->getTextEncode($psong->Title); ?> "><?php echo str_replace('"', '', truncate_text($this->getTextEncode($psong->Title), 25, $this)); ?></a></div>
								 <div class="song song-name" sdtyped="<?php echo $downloadFlag . '-' . $StreamFlag . '-' . $this->Session->read('territory'); ?>">
                                    <?php $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>
                                    <a style="text-decoration:none;" title="<?php echo str_replace('"', '', $this->getTextEncode($showSongTitle)); ?>"><?php echo truncate_text($this->getTextEncode($psong->SongTitle), 21, $this); ?>
                                        <?php
                                        if ($psong->Advisory == 'T')
                                        {
                                            echo '<font class="explicit"> (Explicit)</font>';
                                        }
                                        //
                                        ?>
                                        </span>
                                </div>
								
								<div class="time">3:23</div>
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
		
 			<?php
                                   $i++;
                        	}
                    	     }
                   	 ?>						

</div>

<div class="pagination-container">
        <?php
                if (isset($type))
                {
                    $keyword = "?q=" . $keyword . "&type=" . $type;
                }
                ?>
                <?php
                $keyword = $keyword . "&type=" . $type . "&sort=" . $sort . "&sortOrder=" . $sortOrder;
                echo createPagination($html, $currentPage, $facetPage, 'listing', $totalPages, 7, $keyword);
         ?>
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
 <?php
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
                                        $i = 0;
                                        foreach($albumData as $palbum) {  
                                            
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
                                                        </a> 
                                                        <?php
                                                        if ($this->Session->read("patron"))
                                                        {
                                                            ?>
                                                            <input type="hidden" id="<?= $palbum->ReferenceID ?>" value="album" data-provider="<?= $palbum->provider_type ?>"/>
                                                            <?php
                                                            if ($this->Session->read('library_type') == 2 && !empty($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID]))
                                                            {
                                                                echo $this->Queue->getAlbumStreamLabel($arr_albumStream[$i]['albumSongs'][$palbum->ReferenceID], 1);
                                                                ?>                                          
                                                                <button class="playlist-menu-icon toggleable"></button>                                        
                                                                <ul>
                                                                    <li><a href="#" class="create-new-playlist">Create New Playlist ...</a></li>

                                                                </ul>   
                                                                <?php
                                                            }
                                                            //echo $this->Wishlist->getAlbumWishListMarkup($value['Album']['ProdID'],base64_encode($value['Album']['provider_type']),base64_encode($value['Album']['ArtistText']));
                                                            ?>
                                                            <button class="wishlist-icon toggleable"></button>
                                                            <?php
                                                        }
                                                        ?>

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

