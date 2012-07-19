<?php
/*
	 File Name : advance_search.ctp
	 File Description : View page for advance search
	 Author : m68interactive
 */
?>
<link type="text/css" rel="stylesheet" href="/css/advanced_search.css">
<script src="/js/advanced_search.js"></script>
<div class="breadCrumb">
<?php
	$html->addCrumb(__('Advance Search', true), '/search/advanced_search');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>

<!-- Search Form -->
<div id="leftCol">
	<div id="leftColWrapper">
		<form method="get"><h1 ><label for="search_query">Search music on freegal.com</label></h1>
			<input type="text"  id="search_query" value="<?php echo $keyword ?>" class="query" name="q">
			<input type="hidden" value="all" name="type">
			<input type="submit" value="search">
			<ul  class="clearit" id="searchfilter">
				<li  class=" current  first "><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=all">All Music</a></li>
				<li ><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=album">Albums</a></li>
				<li ><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=artist">Artists</a></li>
				<li ><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=composer">Composers</a></li>
				<li ><a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=genre">Genres</a></li>
				<li ><a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=label">Label</a></li>
				<li ><a href="/search/advanced_search?q=<?php echo $keyword; ?>&type=song">Songs</a></li>
			</ul>
		</form>
	 </div>
</div>
<?php
if('' != $keyword){
?>

	<div  class="fullWidth" id="resultsSummary">
		<div class="search_result_text">
			<h3>Results for your search "<?php echo $keyword; ?>" </h3>
		</div>
		<div  id="hide_blocks">
			<a href="#" onclick="javascript:advanced_search_show_hide('hide_div')">Hide blocks</a>
		</div>
		<div  id="show_blocks" >
			<a href="#" onclick="javascript:advanced_search_show_hide('show_div')">Show blocks</a>
		</div>
	</div>
	<!-- Search Form End-->

<!-- leftColblock Start -->
<div  id="leftColblock">
        <div  id="leftColblockWrapper">
             <div  class="results" id="albumblock">
				<h2  class="heading">
					<span class="h2Wrapper">Albums</span>
				</h2>
        <?php
          $counter=0;
          if(!empty($albumData)){
          foreach($albumData as $album){
            if($counter%2==0){
              $class = 'albumblockC1';
            } else {
              $class = 'albumblockC2';
            }
            if($counter%2==0){
              if($counter==0){
                ?>
                <div  id ="albumblockR1">
                <?php }
                else {
                ?>
                <div  id ="albumblockR2">
                <?php
                }
            }
            ?>
						<div  class ="<?php echo $class; ?>">
							<a  href="#"><img   class="art" src="/img/discover-beyond.jpg"> </a>
							<div class="albumblockArtistexts">
								<a class="albumblockArtisLink"><?php echo substr($album->Title,0,30)."..."; ?></a>
								<br />
								<a  href="#">Genre: <?php echo str_replace('"','',$album->Genre); ?></a>
								<br />
								<span  class="stats">Label: <?php echo (($album->Label!='false')?$album->Label:''); ?>(2007)</span>
							</div>
						</div>


			  <?php
				$counter++;
				if($counter%2==0){
				  ?>
				 </div>
				 <?php
				}
				if($counter%4==0){
				  ?>
				  <span class="more_link">
					<a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=album">See more albums</a>
				  </span>
				 <?php
				}
			  }
			  } else {
				?>
				<ul>
				  <li style='color:red'>No Composers Found</li>
				</ul>
				<?php
			  }
			  ?>

				</div>

				<div  id="ComposersWrapper">
						<h2>Composers</h2>
			  <?php
			  if(!empty($composers)){
			  ?>
						<ul >
				<?php foreach($composers as $composer=>$count)
				{
				?>
							<li ><span class="left_text"><a><?php echo str_replace('"','',$composer); ?></a></span><span class="right_text">(<?php echo $count; ?>)</span></li>
				<?php
				}
				?>
			  </ul>
			  <span class="more_link"><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=composer">See more Composers</a></span>
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

				<div id="GenreWrapper">
						<h2>Genres</h2>
						<?php
			  if(!empty($genres)){
			  ?>
			  <ul>
						<?php foreach($genres as $genre=>$count)
			  {
			  ?>
				<li ><span class="left_text"><a><?php echo str_replace('"','',$genre); ?></a></span><span class="right_text">(<?php echo $count; ?>)</span></li>
						<?php
				}
			  ?>
			  </ul>
						<span class="more_link"><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=genre">See more Genres</a></span>
			  <?php
			  } else {
				?>
				<ul>
				  <li style='color:red'>No Genres Found</li>
				</ul>
				<?php
			  }
			  ?>
				</div>
			</div>
		</div>
	<!-- leftColblock End -->

	<!-- Right blocks -->

		<div  id="rightCol">
			<div   id="ArtistWrapper">
					<h2>Artists</h2>
					<?php
			  if(!empty($artists)){
			  ?>
			  <ul>
						<?php foreach($artists as $artist=>$count)
			  {
			  ?>
				<li ><span class="left_text"><a><?php echo str_replace('"','',$artist); ?></a></span><span class="right_text">(<?php echo $count; ?>)</span></li>
						<?php
				}
			  ?>
			  </ul>
					<span class="more_link"><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=artist">See more Artists</a></span>
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

			 <div  id="LabelWrapper">
				<h2>Labels</h2>
				<?php
			  if(!empty($labels)){
			  ?>
			  <ul>
						<?php foreach($labels as $label=>$count)
			  {
			  ?>
				<li ><span class="left_text"><a><?php echo (($label!="false")?$label:""); ?></a></span><span class="right_text">(<?php echo $count; ?>)</span></li>
						<?php
				}
			  ?>
			  </ul>
				<span class="more_link"><a  href="/search/advanced_search?q=<?php echo $keyword; ?>&type=lebel">See more Labels</a></span>
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


	<!-- Added for track Songs -->

	<div >
		<div  class="links" id="genreArtist">Artist<a href="#"></a></div>
		<div  class="links" id="genreAlbum">Album<a href="#"></a></div>
		<div  class="links"  id="genreTrack">Track<a href="#"></a></div>
		<div  id="genreDownload">Download</div>
	<br class="clr">
	<div id="genreResults">
		<?php if(!empty($songs)){ ?>
	  <table cellspacing="0" cellpadding="0" style="margin-left: 53px;">
			  <tbody>
		<?php foreach($songs as $song) { ?>
			<tr >
					<td width="210" valign="top" style="padding-left: 5px;">
						<p>
							<span title=""><a href="#"><?php echo str_replace('"','',$song->ArtistText); ?></a></span>
						</p>
					</td>
					<td width="210" valign="top" style="padding-left: 10px;">
						<p><a href="#"><?php echo str_replace('"','',$song->Title); ?></a></p>
					</td>
					<td valign="top" style="width: 274px; padding-left: 10px;">
						<p>
							<?php echo $song->SongTitle; ?><a href="#" class="playbutton "><img   src="http://cdn.last.fm/flatness/preview/play_indicator.png" alt="Play" class="transparent_png play_icon"></a>
						</p>
					</td>
					<td width="196" valign="top" align="center" style="padding-left: 10px;">
						<span id="song_3748486" class="beforeClick">
							<a href="#">Download Now</a>
						</span>
					</td>
				</tr>
		<?php } ?>
		</tbody></table>
		<?php } ?>
	<!-- End Added for track Songs -->
	</div>
	<div class="paging">
			<span class="disabled">&lt;&lt; previous</span>&nbsp;<span class="current">1</span> |
			<span><a href="#">2</a></span> |
			<span><a href="#">3</a></span> |
			<span><a href="#">4</a></span> | &nbsp;
			<span><a class="next" href="#">next >></a></span></div>
	</div>

<?php
	}
?>
