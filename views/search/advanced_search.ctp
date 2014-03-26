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
				$pagination_str .=	$html->link('<<'.__('previous', true), "/search/advanced_search/".($currentPage-1).'/'.$facetPage.'/'.$queryString);
			}
			else{
				$pagination_str .=	"&lt&ltprevious";
			}
		} else if($type == 'block'){
			if(1 != $facetPage){
				$pagination_str .=	$html->link('<<'.__('previous', true), "/search/advanced_search/".$currentPage.'/'.($facetPage-1).'/'.$queryString);
			}
			else{
				$pagination_str .=	"&lt&ltprevious";
			}
		}

		$pagination_str .= " ";
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
					$pagination_str .= $html->link($pageCount, '/search/advanced_search/'.($pageCount).'/'.$facetPage.'/'.$queryString);
				}
			} else if($type == 'block'){
				if($facetPage == $pageCount){
					$pagination_str .= $pageCount;
				} else {
					$pagination_str .= $html->link($pageCount, '/search/advanced_search/'.$currentPage.'/'.$pageCount.'/'.$queryString);
				}
			}
			$pagination_str .= " ";
		}
		$pagination_str .= " ";

		if($type == 'listing'){
			if($currentPage != $totalPages ){
				$pagination_str .=	$html->link(__('next', true).'>>', '/search/advanced_search/'.($currentPage+1).'/'.$facetPage.'/'.$queryString);
			}
			else{
				$pagination_str .=	"next&gt&gt";
			}
		} else if($type == 'block'){
			if($facetPage != $totalPages ){
				$pagination_str .=	$html->link(__('next', true).'>>', '/search/advanced_search/'.$currentPage.'/'.($facetPage+1).'/'.$queryString);
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
	$html->addCrumb(__('Advance Search', true), '/search/advanced_search');
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
?>
</div>

<!-- Search Form -->
<div id="leftCol">
	<div id="leftColWrapper">
		<form method="get" id="searchQueryForm"><h1 ><label for="search_query">Search music on freegalmusic.com</label></h1>
			<input type="text"	id="search_query" value="<?php echo $keyword ?>" class="query" name="q">
			<input type="hidden" id="search_type" value="<?php echo (isset($type) && !empty($type))?$type:'all' ?>" name="type">
			<input type="submit" value="search">
			<ul	class="clearit" id="searchfilter">
				<li	class=" current	first ">
          <?php
          if($type != 'all'){
            ?>
          <a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=all">All Music</a>
          <?php
          } else {
            ?>
            <a	href="javascript:void(0)" style="color:#000">All Music</a>
            <?php
          }
          ?>
        </li>
				<li >
        <?php
          if($type != 'album'){
            ?>
          <a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=album">Albums</a>
          <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Albums</a>
            <?php
          }
          ?>
        </li>
				<li >
          <?php
          if($type != 'artist'){
          ?>
          <a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=artist">Artists</a>
          <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Artists</a>
            <?php
          }
          ?>
          </li>
        <li >
          <?php
          if($type != 'composer'){
          ?>
          <a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=composer">Composers</a>
          <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Composers</a>
            <?php
          }
          ?>
          </li>
        <li >
          <?php
          if($type != 'genre'){
          ?>
          <a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=genre">Genres</a>
          <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Genres</a>
            <?php
          }
          ?>
        </li>
				<li >
          <?php
          if($type != 'label'){
          ?>
          <a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=label">Label</a>
          <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Label</a>
            <?php
          }
          ?>
        </li>
				<li  id="list_last">
          <?php
          if($type != 'song'){
          ?>
            <a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=song">Songs</a>
            <?php
          } else {
            ?>
          <a	href="javascript:void(0)" style="color:#000">Songs</a>
            <?php
          }
          ?>
          </li>
			</ul>
		</form>
	 </div>
</div>
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
				<a href="javascript:void(0)" onclick="javascript:advanced_search_show_hide('hide_div')">Hide</a>
			</div>
			<div	id="show_blocks" >
				<a href="javascript:void(0)" onclick="javascript:advanced_search_show_hide('show_div')">Show</a>
			</div>
STR;
		}
?>
	</div>
	<!-- Search Form End-->

<!-- Added code for all search-->
<?php
if(!empty($type) && !($type == 'all' )){

	$str_all_blocks = '';
	$str_all_blocks =<<<STR
						<div	id="all_block">
STR;
	switch($type){
		case 'album':
			$counter=0;
			$album_div =<<<STR
				 <div	class="results" id="album_all_block">
					<h2	class="heading">
						<span class="h2Wrapper">Albums</span>
					</h2>
STR;
			if(!empty($albumData)){
				foreach($albumData as $palbum){
					$albumDetails = $album->getImage($palbum->ReferenceID);
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
					else { /*Blank*/ }

					if($counter%3==0){
						$class = 'album_all_blockC1';
					} else	if($counter%3==1){
						$class = 'album_all_blockC2';
					}else{
						$class = 'album_all_blockC3';
					}

					if($counter%3==0 ){
						$album_outer_div .=<<<STR
							<div	class ="albumblockR">
STR;

						$album_inner_div = '';

					}

					$album_title = truncate_text($palbum->Title, 30, $this);
					$album_genre = str_replace('"','',$palbum->Genre);
					$album_label = $palbum->Label;
					$tilte = urlencode($palbum->Title);
          $linkArtistText = str_replace('/','@',base64_encode($palbum->ArtistText));
          $linkProviderType = base64_encode($palbum->provider_type);
		  if(!empty($album_label)){
			$album_label_str = "Label: " . truncate_text($album_label, 32, $this);
		  }
		  else{
			$album_label_str = "";
		  }
          $ReferenceId = $palbum->ReferenceID;
          if($palbum->AAdvisory == 'T'){
              $explicit = '<font class="explicit"> (Explicit)</font><br />';
          } else {
              $explicit = '';
          }
					$album_inner_div .=<<<STR
					<div	class ="$class">
						<a	href="/artists/view/$linkArtistText/$ReferenceId/$linkProviderType"><img height="75" width="100" class="art" src="$image"> </a>
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
					if($counter%3==0 || $counter == count($albumData)){
						$album_outer_div =<<<STR
							$album_outer_div
							$album_inner_div
						</div>
STR;
					}

				}
				$searchString = "?q=".urlencode($keyword)."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
				$pagination_str = createPagination($html, $currentPage,$facetPage,'block',$totalFacetPages,5,$searchString);
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
						</div>
						<div class="paging_all_block">
						$pagination_str
						</div>

STR;


		break;
		case 'genre':
				$genre_wrapper_div =<<<STR
					<div id="GenreallWrapper">
							<h2>Genres</h2>
STR;

				if(!empty($genres)){

					$no_of_genre = count($genres);
					$number_of_column = 3;
					$number_of_rows = ceil($no_of_genre / $number_of_column);

					$index = 0;
					$column = 1;
					$genre_no = 0;
					foreach($genres as $genre){
						$column = 1;
						$genre_no++;
						if($index == 0){
							$class = 'genre_all_block' . $column;
							$genre_str .=<<<STR
								<div class="$class">
								<ul>
STR;
						}

						$genre_name = str_replace('"','',$genre->Genre);
						$genre_name_text = truncate_text($genre_name, 30, $this);
						$tilte = urlencode($genre->Genre);
            $name = $genre->Genre;
            $count = $genre->numFound;
						$genre_list .=<<<STR
						<li ><span class="left_text"><a href="/search/advanced_search?q=$tilte&type=genre" title="$genre_name">$genre_name_text</a></span><span class="right_text">($count)</span></li>
STR;

						$index++;
						if($index	== $number_of_rows || $no_of_genre == $genre_no){
							$genre_str .=<<<STR
								$genre_list
								</ul>
								</div>
STR;
							$index = 0;
							$genre_list = '';
							$column++;
						}

					}
					$searchString = "?q=".urlencode($keyword)."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
					$pagination_str = createPagination($html, $currentPage,$facetPage,'block',$totalFacetPages,5,$searchString);
				}
				else {
					$genre_str	=<<<STR
					<ul>
						<li style='color:red'>No Genres Found</li>
					</ul>
STR;

				}
				$genre_wrapper_div .=<<<STR
					$genre_str
					</div> <!-- Div GenreWrapper End-->
STR;





				echo $str_all_blocks .=<<<STR
							$genre_wrapper_div
							</div>
							<div class="paging_all_block">
							$pagination_str
							</div>

STR;

		break;
		case 'label':

				$label_wrapper_div =<<<STR
					<div id="labelallWrapper">
							<h2>Labels</h2>
STR;

				if(!empty($labels)){

					$no_of_label = count($labels);
					$number_of_column = 3;
					$number_of_rows = ceil($no_of_label / $number_of_column);

					$index = 0;
					$column = 1;
					$label_no = 0;
					foreach($labels as $label){
						$column = 1;
						$label_no++;
						if($index == 0){
							$class = 'label_all_block' . $column;
							$label_str .=<<<STR
								<div class="$class">
								<ul>
STR;
						}

						$label_name = str_replace('"','',$label->Label);
						$label_name_text = truncate_text($label_name, 30, $this);
						$tilte = urlencode($label->Label);
            $name = $label->Label;
            $count = $label->numFound;
						$label_list .=<<<STR
						<li ><span class="left_text"><a href="/search/advanced_search?q=$tilte&type=label" title="$name">$label_name_text</a></span><span class="right_text">($count)</span></li>
STR;

						$index++;
						if($index	== $number_of_rows || $no_of_label == $label_no){
							$label_str .=<<<STR
								$label_list
								</ul>
								</div>
STR;
							$index = 0;
							$label_list = '';
							$column++;
						}

					}

					$searchString = "?q=".urlencode($keyword)."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
					$pagination_str = createPagination($html, $currentPage,$facetPage,'block',$totalFacetPages,5,$searchString);


				}
				else {
					$label_str	=<<<STR
					<ul>
						<li style='color:red'>No Label Found</li>
					</ul>
STR;

				}
				$label_wrapper_div .=<<<STR
					$label_str
					</div> <!-- Div GenreWrapper End-->
STR;

				echo $str_all_blocks .=<<<STR
							$label_wrapper_div
							</div>
							<div class="paging_all_block">
							$pagination_str
							</div>

STR;


		break;
		case 'artist':
				$artist_wrapper_div =<<<STR
					<div id="artistallWrapper">
							<h2>Artists</h2>
STR;

				if(!empty($artists)){

					$no_of_artist = count($artists);
					$number_of_column = 3;
					$number_of_rows = ceil($no_of_artist / $number_of_column);

					$index = 0;
					$column = 1;
					$artist_no = 0;
					foreach($artists as $artist){

						$column = 1;
						$artist_no++;
						if($index == 0){
							$class = 'artist_all_block' . $column;
							$artist_str .=<<<STR
								<div class="$class">
								<ul>
STR;
						}

						$artist_name = str_replace('"','',$artist->ArttistText);
						$artist_name_text = truncate_text($artist_name, 30, $this);

						$tilte = urlencode($artist->ArtistText);
            $link = $html->link(str_replace('"','',truncate_text($artist->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($artist->ArtistText))));
						$count = $artist->numFound;
            $artist_list .=<<<STR
						<li ><span class="left_text">$link</span><span class="right_text">($count)</span></li>
STR;

						$index++;
						if($index	== $number_of_rows || $no_of_artist == $artist_no){
							$artist_str .=<<<STR
								$artist_list
								</ul>
								</div>
STR;
							$index = 0;
							$artist_list = '';
							$column++;
						}

					}

					$searchString = "?q=".urlencode($keyword)."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
					$pagination_str = createPagination($html, $currentPage,$facetPage,'block',$totalFacetPages,5,$searchString);


				}
				else {
					$artist_str	=<<<STR
					<ul>
						<li style='color:red'>No Artist Found</li>
					</ul>
STR;

				}
				$artist_wrapper_div .=<<<STR
					$artist_str
					</div> <!-- Div GenreWrapper End-->
STR;

				echo $str_all_blocks .=<<<STR
							$artist_wrapper_div
							</div>
							<div class="paging_all_block">
							$pagination_str
							</div>

STR;
		break;

		case 'composer':
				$composer_wrapper_div =<<<STR
					<div id="GenreallWrapper">
							<h2>Composers</h2>
STR;

				if(!empty($composers)){

					$no_of_composer = count($composers);
					$number_of_column = 3;
					$number_of_rows = ceil($no_of_composer / $number_of_column);

					$index = 0;
					$column = 1;
					$composer_no = 0;
					foreach($composers as $composer){
						$column = 1;
						$composer_no++;
						if($index == 0){
							$class = 'composer_all_block' . $column;
							$composer_str .=<<<STR
								<div class="$class">
								<ul>
STR;
						}

						$composer_name = str_replace('"','',$composer->Composer);
						$composer_name = truncate_text($composer_name, 30, $this);
						$tilte = urlencode($composer->Composer);
            $name = $composer->Composer;
            $count = $composer->numFound;
	    $name = $this->getTextEncode($name);
						$composer_list .=<<<STR
						<li ><span class="left_text"><a href="/search/advanced_search?q=$tilte&type=composer" title='$name'>$composer_name</a></span><span class="right_text">($count)</span></li>
STR;

						$index++;
						if($index	== $number_of_rows || $no_of_composer == $composer_no){
							$composer_str .=<<<STR
								$composer_list
								</ul>
								</div>
STR;
							$index = 0;
							$composer_list = '';
							$column++;
						}

					}
					$searchString = "?q=".urlencode($keyword)."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
					$pagination_str = createPagination($html, $currentPage,$facetPage,'block',$totalFacetPages,5,$searchString);


				}
				else {
					$composer_str	=<<<STR
					<ul>
						<li style='color:red'>No composer Found</li>
					</ul>
STR;

				}
				$composer_wrapper_div .=<<<STR
					$composer_str
					</div> <!-- Div GenreWrapper End-->
STR;

				echo $str_all_blocks .=<<<STR
							$composer_wrapper_div
							</div>
							<div class="paging_all_block">
							$pagination_str
							</div>

STR;

		break;

	}

?>
<!-- All blocks div end-->
<?php

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
					else { /*Blank*/ }

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
						<a	href="/search/advanced_search?q=$keyword&type=album">See more albums</a>
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
							<li ><span class="left_text"><a href="/search/advanced_search?q=<?php echo $tilte;?>&type=composer" title='<?php echo $composer->Composer?>'><?php echo str_replace('"','',$composer_name); ?></a></span><span class="right_text">(<?php echo $composer->numFound; ?>)</span></li>
				<?php
				}
				?>
				</ul>
				<span class="more_link"><a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=composer">See more Composers</a></span>
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
						<li ><span class="left_text"><a href="/search/advanced_search?q=$tilte&type=genre" title="$genre_name">$genre_name_text</a></span><span class="right_text">($count)</span></li>
STR;
					}

					$genre_str .=<<<STR
						$genre_list
						</ul>
						<span class="more_link"><a	href="/search/advanced_search?q=$keyword&type=genre">See more Genre</a></span>
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

		<div	id="rightCol">
			<div	 id="ArtistWrapper">
					<h2>Artists</h2>
					<?php
				if(!empty($artists)){
				?>
				<ul>
						<?php foreach($artists as $artist)
				{
						
								$tilte = urlencode($artist->ArtistText);
								$artist_name_text = truncate_text($artist->ArtistText, 30, $this);
                $link = $html->link(str_replace('"','',truncate_text($artist->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($artist->ArtistText))));
				?>
				<li ><span class="left_text"><?php echo $link; ?></span><span class="right_text">(<?php echo $artist->numFound; ?>)</span></li>
						<?php
				}
				?>
				</ul>
					<span class="more_link"><a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=artist">See more Artists</a></span>
			<?php
				} else {
				?>
				<ul>
					<li style='color:red'>No Artists Found</li>
				</ul>
				<?php
				}
				?>
			</div>

			 <div	id="LabelWrapper">
				<h2>Labels</h2>
				<?php
				if(!empty($labels)){
				?>
				<ul>
						<?php foreach($labels as $label)
				{
								$tilte = urlencode($label->Label);
								$label_name_text = truncate_text($label->Label, 30, $this);
                $name = $label->Label;
                $count = $label->numFound;
				?>
				<li ><span class="left_text"><a href="/search/advanced_search?q=<?php echo $tilte;?>&type=label" '<?php echo $name; ?>'><?php echo (($name!="false")?$label_name_text:""); ?></a></span><span class="right_text">(<?php echo $count; ?>)</span></li>
						<?php
				}
				?>
				</ul>
				<span class="more_link"><a	href="/search/advanced_search?q=<?php echo $keyword; ?>&type=label">See more Labels</a></span>
			<?php
				} else {
				?>
				<ul>
					<li style='color:red'>No Labels Found</li>
				</ul>
				<?php
				}
				?>
			</div>
		</div>
<?php } ?>
<!-- End left and right blocks -->

	<!-- Added for track Songs -->
  <?php
    $reverseSortOrder = (($sortOrder=='asc')?'desc':'asc');
  ?>
	<div >
		<div	class="links" id="genreArtist" style="width:192px;">
        <a href="<?php echo "/search/advanced_search/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=artist&sortOrder=".(($sort=='artist')?$reverseSortOrder:'asc'); ?>">Artist
        <?php
          if($sort=='artist'){
            if($sortOrder=='asc'){
              echo "<img src='/img/arrow_asc.png' />";
            } else {
              echo "<img src='/img/arrow_desc.png' />";
            }
          } else {
            echo "<img src='/img/arrow_updown_white.png' />";
          }
        ?>
		</a>
    </div>
		<div	class="links" id="genreComposer" style="width:180px;">
      <a href="<?php echo "/search/advanced_search/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=composer&sortOrder=".(($sort=='composer')?$reverseSortOrder:'asc'); ?>">Composer
      <?php
        if($sort=='composer'){
          if($sortOrder=='asc'){
            echo "<img src='/img/arrow_asc.png' />";
          } else {
            echo "<img src='/img/arrow_desc.png' />";
          }
        } else {
          echo "<img src='/img/arrow_updown_white.png' />";
        }
      ?>
	  </a>
    </div>
		<div	class="links" id="genreAlbum" style="width:192px;">
      <a href="<?php echo "/search/advanced_search/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=album&sortOrder=".(($sort=='album')?$reverseSortOrder:'asc'); ?>">Album
      <?php
        if($sort=='album'){
          if($sortOrder=='asc'){
            echo "<img src='/img/arrow_asc.png' />";
          } else {
            echo "<img src='/img/arrow_desc.png' />";
          }
        } else {
          echo "<img src='/img/arrow_updown_white.png' />";
        }
      ?>
	  </a>
    </div>
		<div	class="links"	id="genreTrack" style="width:215px;">
      <a href="<?php echo "/search/advanced_search/".$currentPage."/".$facetPage."/?q=".$keyword."&type=".$type."&sort=song&sortOrder=".(($sort=='song')?$reverseSortOrder:'asc'); ?>">Track
      <?php
        if($sort=='song'){
          if($sortOrder=='asc'){
            echo "<img src='/img/arrow_asc.png' />";
          } else {
            echo "<img src='/img/arrow_desc.png' />";
          }
        } else {
          echo "<img src='/img/arrow_updown_white.png' />";
        }
      ?>
	  </a>
    </div>
		<div	id="genreDownload" style="width:203px;">Download</div>
	<br class="clr">
	<div id="genreResults">
		<?php if(!empty($songs)){ ?>
		<table cellspacing="0" cellpadding="0" style="margin-left: 15px;">
				<tbody>
		<?php $i = 0;
		$country = $this->Session->read('territory');
		foreach($songs as $psong) {

			$sales_date = Get_Sales_date($psong->TerritorySalesDate, $country);
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}

			?>
			<tr <?php echo $class; ?> style="margin-left:0px;">
					<td width="187" valign="top" style="padding-left: 5px;">
						<p>
							<span title="<?php echo str_replace('"','',$this->getTextEncode($psong->ArtistText)); ?>"><?php echo $html->link(str_replace('"','',truncate_text($psong->ArtistText, 30, $this)), array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($psong->ArtistText)))); ?></span>
						</p>
					</td> 
					<td width="170" valign="top" style="padding-left: 10px;">
						<p><span title="<?php echo str_replace('"','',$this->getTextEncode($psong->Composer)); ?>"><?php echo truncate_text(str_replace('"','',$psong->Composer), 30, $this); ?></span></p>
					</td>
					<td width="182" valign="top" style="padding-left: 10px;">
						<p><span title="<?php echo str_replace('"','',$this->getTextEncode($psong->Title)); ?>"><a href="/artists/view/<?php echo str_replace('/','@',base64_encode($psong->ArtistText)); ?>/<?php echo $psong->ReferenceID;	?>/<?php echo base64_encode($psong->provider_type);	?>"><?php echo str_replace('"','',truncate_text($psong->Title,30, $this)); ?></a></span></p>
					</td>
					<td valign="top" width="205" style="padding-left: 10px;">
						<p>
             <?php  $showSongTitle = truncate_text($psong->SongTitle, strlen($psong->SongTitle), $this); ?>

							<span title="<?php echo str_replace('"','',$showSongTitle); ?>"><?php echo truncate_text($psong->SongTitle,28, $this); ?>
              <?php if ($psong->Advisory == 'T') {
            		echo '<font class="explicit"> (Explicit)</font>';
            	}
              ?>
              </span>
							<?php
							$sampleFile = $song->getSampleFile($psong->Sample_FileID);
							$songUrl = shell_exec('perl files/tokengen ' . $sampleFile['CdnPath']."/".$sampleFile['SaveAsName']);
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$psong->ProdID.', "'.base64_encode($psong->provider_type).'", "'.$this->webroot.'");'));
							echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i));
							echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");'));
							?>
						</p>
					</td>
					<td width="203" valign="top" align="center" style="padding-left: 10px;">
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
													<a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not.");?>' onclick='userDownloadAll(<?php echo $psong->ProdID; ?>);'><?php __('Download Now');?></a>
												</span>
												<span class="afterClick" id="downloading_<?php echo $psong->ProdID; ?>" style="display:none;float:left"><?php __("Please Wait...");?></span>
												<span id="download_loader_<?php echo $psong->ProdID; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
											</form>
										</p>
							<?php		}else {
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
											if($wishlistInfo == 'Added To Wishlist'){
										?>
												<p><?php __("Added To Wishlist");?></p>
									<?php 	}
											else { ?>
												<p>
												<span class="beforeClick" id="wishlist<?php echo $psong->ProdID; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $psong->ProdID; ?>","<?php echo $song->provider_type; ?>");'><?php __("Add To Wishlist");?></a></span><span id="wishlist_loader_<?php echo $song->ProdID; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
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
						else{
							?>
								<span title='<?php __("Coming Soon");?> ( <?php echo date("F d Y", strtotime($sales_date)); ?> )'><?php __("Coming Soon");?></span>
							<?php
						}

						?>
					</td>
				</tr>
		<?php } ?>
		</tbody></table>
		<?php }
			else {
				echo '<table><tr><td width="180" valign="top"><p><div class="paging">';
				echo __("No records found");
				echo '</div><br class="clr"></td></tr></table>';
			}
		?>
	<!-- End Added for track Songs -->
	</div>
	<div class="paging">
		<?php
			if(isset($type)){
				$keyword = "?q=".$keyword."&type=".$type;
			}
		?>
	<?php
		$keyword = $keyword."&type=".$type."&sort=".$sort."&sortOrder=".$sortOrder;
		echo createPagination($html, $currentPage,$facetPage,'listing',$totalPages,7,$keyword);
	?>
</div>
<?php

	}

?>
	</div>
