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

				<li class="parent item8"><?php echo $html->link('See All', array('controller' => 'genres','action'=>'index'));?></li>
				<?php
					foreach($genresMenu as $genreM)
					{
						if($genreM['Category']['Language'] == Configure::read('App.LANGUAGE')){
							$searchFor = "view/" . base64_encode($genreM['Category']['Genre']);
							?>
							<li class="parent item"><?php echo $html->link($genreM['Category']['Genre'], array('controller' => 'genres','action'=>$searchFor));?></li>
							<?php
						}
					}
				?>
			</ul>
		</li>
		<li class="item3"><a href="#"><span>Featured Artist</span></a>
			<ul>
				<?php
					foreach($featuredArtistMenu as $featuredArtistM) {
						if($featuredArtistM['Featuredartist']['territory'] == $this->Session->read('territory') && $featuredArtistM['Featuredartist']['language'] == Configure::read('App.LANGUAGE')){				
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
					}
				?>
			</ul>
		</li>
		<li class="item4"><a href="#"><span>Newly Added</span></a>
			<ul>
				<?php
					foreach($newArtistMenu as $newArtistM) {
						if($newArtistM['Newartist']['territory'] == $this->Session->read('territory') && $newArtistM['Newartist']['language'] == Configure::read('App.LANGUAGE')){
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
					}
				?>
			</ul>
		</li>
		<li id="search">
			<!--<form name="search_form" method="post" action="homes/search" class="search_form">-->
			<?php
			if(isset($_REQUEST['search']) &&  $_REQUEST['search'] != "")
			{
				$search = $_REQUEST['search'];
			}
			else{				
				if(isset($_REQUEST['data']['Home']['search']) &&  $_REQUEST['data']['Home']['search'] != "")
				{
					$search = $_REQUEST['data']['Home']['search'];
				}
				else{
					$search = "";
				}
			}
			echo $this->Form->create('Home', array( 'controller' => 'Home','action' => 'search','class' => 'search_form', 'type' => 'get'));	
			      /*echo $ajax->autoComplete('autoComplete', '/homes/autoComplete',array('size' => '24', 'onclick' => 'if(this.value=="Search"){this.value="";}','value' => 'Search'))?>*/
			      echo $this->Form->input('search', array('size'=>'24', 'id'=>'autoComplete', 'label' => false, 'value' => $search));
				  echo $this->Form->hidden('auto', array('size'=>'24', 'id'=>'auto', 'name'=>'auto', 'label' => false, 'value' => 0));?>
				<!-- <div style="float:left;">
									<input type="submit" class="searchButton" value=""></input>
								</div> -->
			<!-- </form>-->
			<?php echo $this->Form->end('GO'); ?>
			<?php echo $html->link('Advanced Search', array('controller' => 'homes', 'action' => 'advance_search')); ?>
		</li>	
	</ul>
</div>