<?php
/*
	 File Name : advance_search.ctp
	 File Description : View page for advance search
	 Author : m68interactive
 */
function createPagination($html, $currentPage, $facetPage, $type='listing', $totalPages, $pageLimitToShow, $queryString=null){
	$queryString = html_entity_decode($queryString);
	if($totalPages > 1){

		$part = floor($pageLimitToShow/2);
		if($type == 'listing'){
			if(1 != $currentPage){
				$pagination_str .=	$html->link('<<'.__('previous', true), "/search/index/".($currentPage-1).'/'.$facetPage.'/'.$queryString);
			}
			else{
				$pagination_str .=	"&lt&ltprevious";
			}
		} else if($type == 'block'){
			if(1 != $facetPage){
				$pagination_str .=	$html->link('<<'.__('previous', true), "/search/index/".$currentPage.'/'.($facetPage-1).'/'.$queryString);
			}
			else{
				$pagination_str .=	"&lt&ltprevious";
			}
		}

		$pagination_str .= "&nbsp;";
		if($type == 'listing'){
			if($currentPage <= $part){
				$fromPage = 1;
			$topage = $currentPage + ($pageLimitToShow - $currentPage);
				$topage = (($topage <= $totalPages)?$topage:$totalPages);
			} elseif($currentPage >= ($totalPages - $part)){
				$fromPage = ($currentPage >= $totalPages)?$totalPages-($pageLimitToShow-1):(($currentPage - ($pageLimitToShow - ($totalPages - $currentPage)))+1);
				$topage = $totalPages;
				$fromPage = (($fromPage > 1)?$fromPage:1);
			} else {
				$fromPage = $currentPage - $part;
				$topage = $currentPage + $part;
			}
		} else if($type == 'block'){
			if($facetPage <= $part){
				$fromPage = 1;
				$topage = $facetPage + ($pageLimitToShow - $facetPage);
				$topage = (($topage <= $totalPages)?$topage:$totalPages);
			} elseif($facetPage >= ($totalPages - $part)){
				$fromPage = ($facetPage >= $totalPages)?$totalPages-($pageLimitToShow-1):(($facetPage - ($pageLimitToShow - ($totalPages - $facetPage)))+1);
				$topage = $totalPages;
				$fromPage = (($fromPage > 1)?$fromPage:1);
			} else {
				$fromPage = $facetPage - $part;
				$topage = $facetPage + $part;
			}
		}

		for($pageCount=$fromPage;$pageCount<=$topage;$pageCount++){
			if($type == 'listing'){
				if($currentPage == $pageCount){
					$pagination_str .= $pageCount;
				} else {
					$pagination_str .= $html->link($pageCount, '/search/index/'.($pageCount).'/'.$facetPage.'/'.$queryString);
				}
			} else if($type == 'block'){
				if($facetPage == $pageCount){
					$pagination_str .= $pageCount;
				} else {
					$pagination_str .= $html->link($pageCount, '/search/index/'.$currentPage.'/'.$pageCount.'/'.$queryString);
				}
			}
			$pagination_str .= "&nbsp;";
		}
		$pagination_str .= "&nbsp;";

		if($type == 'listing'){
			if($currentPage != $totalPages ){
				$pagination_str .=	$html->link(__('next', true).'>>', '/search/index/'.($currentPage+1).'/'.$facetPage.'/'.$queryString);
			}
			else{
				$pagination_str .=	"next&gt&gt";
			}
		} else if($type == 'block'){
			if($facetPage != $totalPages ){
				$pagination_str .=	$html->link(__('next', true).'>>', '/search/index/'.$currentPage.'/'.($facetPage+1).'/'.$queryString);
			}
			else{
				$pagination_str .=	"next&gt&gt";
			}
		}

	}
	else{
		$pagination_str = '';
	}

	return $pagination_str;
}

function truncate_text($text, $char_count, $obj = null){
	
  if(strlen($text) > $char_count) {
		$modified_text = substr($text, 0, $char_count);
		$modified_text = substr($modified_text, 0, strrpos($modified_text, " ", 0));
		$modified_text = substr($modified_text, 0, $char_count) . "...";
	}
	else {
		$modified_text = $text;
	}

  return $obj->getTextEncode($modified_text); 

}

//Code for check Sales date
function Get_Sales_date($sales_date_array, $country){
	$Sales_date = '';
	if(is_array($sales_date_array)){
		foreach($sales_date_array as $TerritorySalesDate) {
			$Territory_date_array = explode("_", $TerritorySalesDate);
			if(is_array($sales_date_array)){
				$Territory = $Territory_date_array[0];

			}

			if($country == $Territory){
				$Sales_date = $Territory_date_array[1];
				break;
			}
		}
	}

	return $Sales_date ;
}
?>
<link type="text/css" rel="stylesheet" href="/css/advanced_search.css">
<script src="/js/advanced_search.js"></script>
<div class="breadCrumb">
<?php
	/*$html->addCrumb(__('Search Results', true), '/search/index');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');*/
?>
</div>

<!-- Search Form -->
<?php
if('' != $keyword){
?>

	<div	class="fullWidth" id="resultsSummary">
		<div class="search_result_text">
			<h3>Results for your search "<?php echo $keyword; ?>" </h3>
		</div>
<?php
	if(($type == 'all' )){
			echo $str =<<<STR
			<div	id="hide_blocks">
				<a href="#" onclick="javascript:advanced_search_show_hide('hide_div')">Hide</a>
			</div>
			<div	id="show_blocks" >
				<a href="#" onclick="javascript:advanced_search_show_hide('show_div')">Show</a>
			</div>
STR;
		}
?>
	</div>
	<!-- Search Form End-->

<!-- Added code for all search-->
<?php
if(!empty($type) && !($type == 'all' )){

	

}
else{


?>
<!-- leftColblock Start -->
<div	id="leftColblock">
				<div	id="leftColblockWrapper">
<?php
/********************************************Album block started*********************************************************************************/

	$str_all_blocks = '';

			$counter=0;
			$album_div =<<<STR
				 <div	class="results" id="albumblock">
					<h2	class="heading">
						<span class="h2Wrapper">Albums</span>
					</h2>
STR;

			if(!empty($albumData)){
				foreach($albumData as $palbum){
					$albumDetails = $album->getImage($palbum->ReferenceID);
					if(!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])){
						$albumArtwork = shell_exec('perl files/tokengen ' . $albumDetails[0]['Files']['CdnPath']."/".$albumDetails[0]['Files']['SourceURL']);
						$image = Configure::read('App.Music_Path').$albumArtwork;
					} else {
						$image = 'no-image.jpg';
					}
					if($page->isImage($image)) {
						//Image is a correct one
					}
					else {

					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
					}

					if($counter%2==0){
						$class = 'albumblockC1';
					} else {
						$class = 'albumblockC2';
					}

					if($counter%2==0){
						if($counter==0){
						$album_outer_div =<<<STR
							<div	id ="albumblockR1">
STR;
						}
						else {
							$album_inner_div = '';
							$album_outer_div .=<<<STR
							<div	id ="albumblockR2">
STR;
						}
					}

					$album_title = truncate_text($palbum->Title, 30, $this);
					$title = urlencode($palbum->Title);
					$album_genre = str_replace('"','',$palbum->Genre);
					$tilte = urlencode($palbum->Title);
					$album_label = $palbum->Label;
          $linkArtistText = str_replace('/','@',base64_encode($palbum->ArtistText));
          $linkProviderType = base64_encode($palbum->provider_type);
          $ReferenceId = $palbum->ReferenceID;
          if($palbum->AAdvisory == 'T'){
              $explicit = '<font class="explicit"> (Explicit)</font><br />';
          } else {
              $explicit = '';
          }
		  if(!empty($album_label)){
			$album_label_str = "Label: " . truncate_text($album_label, 32, $this);
		  }
		  else{
			$album_label_str = "";
		  }
					$album_inner_div .=<<<STR
					<div	class ="$class">
						<a	href="/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"><img class="art" height="75" width="100" src="$image"> </a>
						<div class="albumblockArtistexts">
							<a class="albumblockArtisLink" href="/artists/view/$linkArtistText/$ReferenceId/$linkProviderType" title="$palbum->Title">$album_title</a>
							<br />
                                                        $explicit
							Genre: $album_genre
							<br />
							<span	class="stats">$album_label_str</span>
						</div>
					</div>
STR;

					$counter++;
					if($counter%2==0 || $counter == count($albumData)){
						$album_outer_div =<<<STR
							$album_outer_div
							$album_inner_div
						</div>
STR;
					}
					if($counter%4==0 || $counter == count($albumData)){
						$album_outer_div .=<<<STR
						<div><span class="more_link">
						<a	href="/search/index?q=$keyword&type=album">See more albums</a>
						</span></div>
STR;
					}
				}
				}
				else {
				$album_outer_div .=<<<STR
				<ul>
					<li style='color:red'>No Album Found</li>
				</ul>
STR;
				}


	echo $str_all_blocks .=<<<STR
								$album_div
							$album_outer_div
							</div>

STR;
/********************************************Album block end*********************************************************************************/

?>


				<div	id="ComposersWrapper">
						<h2>Composers</h2>
				<?php
				if(!empty($composers)){
				?>
						<ul >
				<?php foreach($composers as $composer)
				{
					$tilte = urlencode($composer->Composer);
					$composer_name = truncate_text($composer->Composer, 30, $this);
				?>
							<li ><span class="left_text"><a href="/search/index?q=<?php echo $tilte;?>&type=composer" title='<?php echo $composer->Composer?>'><?php echo str_replace('"','',$composer_name); ?></a></span><span class="right_text">(<?php echo $composer->numFound; ?>)</span></li>
				<?php
				}
				?>
				</ul>
				<span class="more_link"><a	href="/search/index?q=<?php echo $keyword; ?>&type=composer">See more Composers</a></span>
				<?php
				} else {
				?>
				<ul>
					<li style='color:red'>No Composers Found</li>
				</ul>
				<?php
				}
				?>
				</div>
<?php
/********************************************Genre block started*********************************************************************************/
				$genre_wrapper_div =<<<STR
					<div id="GenreWrapper">
							<h2>Genres</h2>
STR;

				if(!empty($genres)){
					$genre_str .=<<<STR
						<ul>
STR;

					foreach($genres as $genre){
						$genre_name = str_replace('"','',$genre->Genre);
						$tilte = urlencode($genre_name);
						$genre_name_text = truncate_text($genre_name, 30, $this);
            $name = $genre->Genre;
            $count = $genre->numFound;
						$genre_list .=<<<STR
						<li ><span class="left_text"><a href="/search/index?q=$tilte&type=genre" title="$genre_name">$genre_name_text</a></span><span class="right_text">($count)</span></li>
STR;
					}

					$genre_str .=<<<STR
						$genre_list
						</ul>
						<span class="more_link"><a	href="/search/index?q=$keyword&type=genre">See more Genre</a></span>
STR;
				}
				else {
					$genre_str	=<<<STR
					<ul>
						<li style='color:red'>No Genres Found</li>
					</ul>
STR;

				}


				echo $genre_wrapper_div .=<<<STR
					$genre_str

					</div> <!-- Div GenreWrapper End-->
STR;
/********************************************Genre block end*********************************************************************************/

?>

			</div><!-- End leftColblockWrapper -->
		</div>
	<!-- leftColblock End -->

	<!-- Right blocks -->

		
<?php } ?>
<!-- End left and right blocks -->

<?php

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
            <form method="get" id="searchQueryForm">
			<input type="search" name="q" id="query" value="<?php echo $keyword; ?>"/>
            <input type="hidden" id="search_type" value="<?php echo (isset($type) && !empty($type))?$type:'all' ?>" name="type">
			<input type="submit" name="submit" id="submit" value="Search" />
            </form>
            <div class="faq-link">Need help? Visit our <a href="#">FAQ section</a>.</div>
			<ul class="clearfix">
				<li>
                    <?php
                        if($type != 'all'){
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
                        if($type != 'album'){
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
                        if($type != 'artist'){
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
                        if($type != 'composer'){
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
                        if($type != 'genre'){
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
                        if($type != 'label'){
                    ?>
                        <a href="/search/index?q=<?php echo $keyword; ?>&type=label">Label</a>
                    <?php
                        } else {
                    ?>
                        <a href="#" class="active">Label</a>
                    <?php
                        }
                    ?>
                </li>
				<li>|</li>
				<li>
                    <?php
                        if($type != 'song'){
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
if(!empty($type) && !($type == 'all')){
switch($type){
		case 'album':
        case 'artist':
        case 'genres':    
        case 'album':
        case 'album':    
}           
} else {
?>      
       <section class="advanced-search-results row-1 clearfix">
           <h4>Results for your search "<span><?php echo $keyword; ?></span>"</h4>
<?php /*********************Album Block Started*******************************/ ?>
           <section class="advanced-albums">
				<header class="clearfix">
				<h5><?php __("Album"); ?></h5>
                <h6><a href="/search/index?q=<?php echo $keyword; ?>&type=album">See more albums</a></h6>
				</header>
				<div class="advanced-albums-shadow-container">
					<div class="advanced-albums-scrollable horiz-scroll">
						<ul>
							<?php
							foreach($albumData as $palbum){
							?>
							<li>
								<?php
                                $albumDetails = $album->getImage($palbum->ReferenceID);
                                if(!empty($albumDetails[0]['Files']['CdnPath']) && !empty($albumDetails[0]['Files']['SourceURL'])){
                                    $albumArtwork = shell_exec('perl files/tokengen ' . $albumDetails[0]['Files']['CdnPath']."/".$albumDetails[0]['Files']['SourceURL']);
                                    $image = Configure::read('App.Music_Path').$albumArtwork;
                                } else {
                                    $image = 'no-image.jpg';
                                }
                                if($page->isImage($image)) {
                                    //Image is a correct one
                                } else {
                					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
                                }
                                $album_title = truncate_text($palbum->Title, 30, $this);
                                $title = urlencode($palbum->Title);
                                $album_genre = str_replace('"','',$palbum->Genre);
                                $tilte = urlencode($palbum->Title);
                                $album_label = $palbum->Label;
                                $linkArtistText = str_replace('/','@',base64_encode($palbum->ArtistText));
                                $linkProviderType = base64_encode($palbum->provider_type);
                                $ReferenceId = $palbum->ReferenceID;
                                if($palbum->AAdvisory == 'T'){
                                    $explicit = '<font class="explicit"> (Explicit)</font><br />';
                                } else {
                                    $explicit = '';
                                }
                                if(!empty($album_label)){
                                    $album_label_str = "Label: " . truncate_text($album_label, 32, $this);
                                } else {
                                    $album_label_str = "";
                                }
                                ?>
                                <a href="<?php echo "/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $album_title; ?>" width="162" height="162" /></a>
								<div class="album-title"><a href="#"><?php echo $album_title; ?></a></div>
								<div class="album-genre">Genre: <span><a href="#"><?php echo $album_genre; ?></a></span></div>
								<div class="album-label"><?php echo $album_label_str; ?></span></div>
							</li>
							
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</section>
    <?php /*********************Album Block End*******************************/ ?>
           
    <?php /*********************Artist Block Started*******************************/ ?>
			<section class="advanced-artists">
				<header class="clearfix">
					<h5><?php __("Artists"); ?></h5>
					<h6><a href="/search/index?q=<?php echo $keyword; ?>&type=artist">See more artists</a></h6>
				</header>
				<div class="advanced-artists-shadow-container">
					<div class="advanced-artists-scrollable">
						<?php 
                        if(!empty($artists)){
                            foreach($artists as $artist){
                                $tilte = urlencode($artist->ArtistText);
								$artist_name_text = truncate_text($artist->ArtistText, 30, $this);
                                $link = $html->link(str_replace('"','',truncate_text($artist->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($artist->ArtistText))));
                        ?>
                        <div><?php echo $link; ?><span>(<?php echo $artist->numFound; ?>)</span></div>
						<?php }
                        } else { ?>
                        <div style='color:red'><?php __("No Artists Found"); ?></div>
                        <?php } ?>
                    </div>
				</div>
			
			</section>
<?php /*********************Artist Block End*******************************/ ?>       
		</section>
		<section class="advanced-search-results row-2 clearfix">
<?php /*********************Composer Block Started*******************************/ ?>			
            <section class="advanced-composers">
				<header class="clearfix">
					<h5><?php __("Composers"); ?></h5>
					<h6><a href="/search/index?q=<?php echo $keyword; ?>&type=composer">See more composers</a></h6>
				</header>
				<div class="advanced-composers-shadow-container">
					<div class="advanced-composers-scrollable">
                        <?php
                        if(!empty($composers)){
                            foreach($composers as $composer)
                            {
                            $tilte = urlencode($composer->Composer);
                            $composer_name = truncate_text($composer->Composer, 30, $this);
                            ?>
                        <div><a href="/search/index?q=<?php echo $tilte;?>&type=composer" title='<?php echo $composer->Composer?>'><?php echo str_replace('"','',$composer_name); ?></a><span>(<?php echo $composer->numFound; ?>)</span></div>
						<?php }
                        } else { ?>
                          <div style='color:red'><?php __("No Composers Found"); ?></div>  
                        <?php } ?>
					</div>
				</div>
			</section>
<?php /*********************Composer Block End*******************************/ ?>
            
<?php /*********************Genre Block Started*******************************/ ?>
            <section class="advanced-genres">
				<header class="clearfix">
					<h5><?php __("Genres"); ?></h5>
					<h6><a href="/search/index?q=<?php echo $keyword; ?>&type=genre">See more genres</a></h6>
				</header>
				<div class="advanced-genres-shadow-container">
					<div class="advanced-genres-scrollable">
                        <?php if(!empty($genres)){
                                foreach($genres as $genre){
                                $genre_name = str_replace('"','',$genre->Genre);
                                $tilte = urlencode($genre_name);
                                $genre_name_text = truncate_text($genre_name, 30, $this);
                                $name = $genre->Genre;
                                $count = $genre->numFound;
                                ?>
                        <div><a href="<?php echo "/search/index?q=$tilte&type=genre"; ?>" title="<?php echo $genre_name; ?>"><?php echo $genre_name_text; ?><span>(<?php echo $count; ?>)</span></a></div>
						<?php 
                                }
                        } else { ?>
                        <div style='color:red'><?php __("No Genres Found"); ?></div>  
                        <?php } ?>
					</div>
				</div>
			</section>
<?php /*********************Genre Block End*******************************/ ?>

<?php /*********************Label Block Started*******************************/ ?>            
            <section class="advanced-labels">
				<header class="clearfix">
					<h5><?php __("Labels"); ?></h5>
					<h6><a href="/search/index?q=<?php echo $keyword; ?>&type=label">See more labels</a></h6>
				</header>
				<div class="advanced-labels-shadow-container">
					<div class="advanced-labels-scrollable">
                            <?php
                            if(!empty($labels)){
                                foreach($labels as $label)
                                {
                                    $tilte = urlencode($label->Label);
                                    $label_name_text = truncate_text($label->Label, 30, $this);
                                    $name = $label->Label;
                                    $count = $label->numFound;
                            ?>
                        <div><a href="/search/index?q=<?php echo $tilte;?>&type=label" title="<?php echo $name; ?>"><?php echo (($name!="false")?$label_name_text:""); ?> <span>(<?php echo $count; ?>)</span></a></div>
						<?php }
                            } else { ?>
                             <div style='color:red'><?php __("No Labels Found"); ?></div>     
                        <?php  } ?>
					</div>
				</div>
			</section>
<?php /*********************Label Block End*******************************/ ?>
		</section>
<?php } ?>
		<section class="tracklist-container">
			<section class="tracklist-header clearfix">
				<span class="artist"></span><span class="composer"></span><span class="album"></span><span class="song"></span><span class="download"></span>
			</section>
			<div class="tracklist-shadow-container">
				<div class="tracklist-scrollable">
				<?php
				if(!empty($songs)){
				$i=1;
				$country = $this->Session->read('territory');
                foreach($songs as $psong) {
                ?>
                    <div class="tracklist">
						<a href="#" class="preview"></a>
						<div class="artist"><?php echo $html->link(str_replace('"','',truncate_text($psong->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($psong->ArtistText)))); ?></div>
						<a class="add-to-playlist-button" href="#"></a>
						<div class="composer"><?php echo truncate_text(str_replace('"','',$psong->Composer), 30, $this); ?></div>
							
						
						<div class="wishlist-popover">	
						
						
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
							<a class="add-to-wishlist" href="#">Add To Wishlist</a>
							
							<div class="share clearfix">
								<p>Share via</p>
								<a class="facebook" href="#"></a>
								<a class="twitter" href="#"></a>
							</div>
						</div>
						<div class="cover-art">
							<img src="images/search-results/carrieunderwood.jpg" alt="carrieunderwood" width="27" height="27" />
						</div>
						<div class="album"><a href="#"><a href="/artists/view/<?php echo str_replace('/','@',base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID;	?>/<?php echo base64_encode($psong->provider_type);	?>"><?php echo str_replace('"','',truncate_text($psong->Title,30, $this)); ?></a></a></div>
						<div class="song">
                             <?php  $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>
                             <span title="<?php echo str_replace('"','',$showSongTitle); ?>"><?php echo truncate_text($psong->SongTitle,28, $this); ?>
                             <?php if ($psong->Advisory == 'T') {
                                    echo '<font class="explicit"> (Explicit)</font>';
                                }
                                ?>
                             </span>
                        </div>
						<div class="download">
                        <?php    
                        if($sales_date <= date('Y-m-d'))
						{
								if($libraryDownload == '1' && $patronDownload == '1') {
									if($psong->status != 'avail'){
									?>
										<p>
											<form method="Post" id="form<?php echo $psong->ProdID; ?>" action="/homes/userDownload">
												<input type="hidden" name="ProdID" value="<?php echo $psong->ProdID; ?>" />
												<input type="hidden" name="ProviderType" value="<?php echo $psong->provider_type; ?>" />
												<span class="beforeClick" id="song_<?php echo $psong->ProdID; ?>">
													<a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not.");?>' onclick='userDownloadAll(<?php echo $psong->ProdID; ?>);'><?php __('Download');?></a>
												</span>
												<span class="afterClick" id="downloading_<?php echo $psong->ProdID; ?>" style="display:none;float:left"><?php __("Please Wait...");?></span>
												<span id="download_loader_<?php echo $psong->ProdID; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
											</form>
										</p>
							<?php		} else {
										?><a href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __("Downloaded");?></a><?php
									}
								}
															else {
									if($libraryDownload != '1'){
										$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
																			$wishlistCount = $wishlist->getWishlistCount();
																			if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
											?>
											<p><?php __("Limit Met");?></p>
							<?php
										}
																			else{
											$wishlistInfo = $wishlist->getWishlistData($psong->ProdID);
											if($wishlistInfo == 'Added to Wishlist'){
										?>
												<p><?php __("Added to Wishlist");?></p>
									<?php 	}
											else { ?>
												<p>
												<span class="beforeClick" id="wishlist<?php echo $psong->ProdID; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $psong->ProdID; ?>","<?php echo $song->provider_type; ?>");'><?php __("Add to wishlist");?></a></span><span id="wishlist_loader_<?php echo $song->ProdID; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
												<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>

												</p>
									<?php
											}
																			}
								}
								else { ?>
									<p><?php __("Limit Met");?></p>
								<?php
								}
							}
						}
						else {
							?>
								<span title='<?php __("Coming Soon");?> ( <?php echo date("F d Y", strtotime($sales_date)); ?> )'><?php __("Coming Soon");?></span>
							<?php
						}

						?>
                        </div>
						
						
						
				
					</div>
				<?php		
					}
                }
				?>
				</div>
			</div>
		</section>
    </section>
	</div>