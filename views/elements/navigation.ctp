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
					$searchFor = "view/" . base64_encode($genreM['Genre']['Genre']);
					?>
					<li class="parent item"><?php echo $html->link($genreM['Genre']['Genre'], array('controller' => 'genres','action'=>$searchFor));?></li>
					<?php
				}
				?>
			</ul>
		</li>
		<li class="item3"><a href="#"><span>Featured Artist</span></a>
			<ul>
				<?php
				foreach($featuredArtistMenu as $featuredArtistM) {
				?>
					<li class="parent item">
						<?php echo $html->link($featuredArtistM['Featuredartist']['artist_name'], array(
							'controller' => 'artists', 
							'action'=> 'view', 
							base64_encode($featuredArtistM['Featuredartist']['artist_name'])));
						?>
					</li>
				<?php
				}
				?>
			</ul>
		</li>
		<li class="item4"><a href="#"><span>Newly Added</span></a>
			<ul>
				<?php
				foreach($newArtistMenu as $newArtistM) {
				?>
					<li class="parent item">
						<?php echo $html->link($newArtistM['Newartist']['artist_name'], array(
							'controller' => 'artists',
							'action' => 'view',
							base64_encode($newArtistM['Newartist']['artist_name']))); 
						?>
					</li>
				<?php
				}
				?>
			</ul>
		</li>
		<li id="search">
			<!--<form name="search_form" method="post" action="homes/search" class="search_form">-->
			<?php echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'search','class' => 'search_form'));	
			      echo $ajax->autoComplete('autoComplete', '/homes/autoComplete',array('size' => '24', 'onclick' => 'if(this.value=="Search"){this.value="";}','value' => 'Search'))?>
				<input type="submit" class="searchButton" value=""></input>
			<!-- </form>-->
			<?php echo $this->Form->end(); ?>
			<a href="#">Advanced Search</a>
		</li>	
	</ul>
</div>