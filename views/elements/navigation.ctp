<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 February, 2010
 * @package default
 **/

/**
 * Navigation file for front-end site
 **/
?>
<div class="navigation">
	
	<ul class="menu" id="nav">
		<li class="parent item1"><?php echo $html->link('Home', array('controller' => 'homes','action'=>'index'));?></li>
		<li class="parent item2"><a href="#"><span>Genre</span></a>
			<ul>

				<li class="parent item8"><?php echo $html->link('See All', array('controller' => 'genres','action'=>'view/all'));?></li>
				<?php
				foreach($genresMenu as $genreM)
				{
					$searchFor = "view/".$genreM['Genre']['Genre'];
					?>
					<li class="parent item"><?php echo $html->link($genreM['Genre']['Genre'], array('controller' => 'genres','action'=>$searchFor));?></li>
					<?php
				}
				?>
			</ul>
		</li>
		<li class="item3"><a href="/management"><span>Featured Artist</span></a>
			<ul>
				<?php
				foreach($featuredArtistMenu as $featuredArtistM)
				{
					$searchFor = "view/".$featuredArtistM['Featuredartist']['artist_name'];
					?>
					<li class="parent item"><?php echo $html->link($featuredArtistM['Featuredartist']['artist_name'], array('controller' => 'artist','action'=>$searchFor));?></li>
					<?php
				}
				?>
			</ul>
		</li>
		<li class="item4"><a href="/contact"><span>Newly Added</span></a></li>
		<li id="search">
			<form name="search_form" method="put" action="search" class="search_form">
				<input type="text" name="txtSearch" size="24" onclick="if(this.value=='Search') {this.value='';}" value="Search">
			</form>
			<a href="#">Advanced Search</a>
		</li>
	</ul>
</div>