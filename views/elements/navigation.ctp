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
		<li class="parent item1"><a href="/"><span>Home</span></a></li>
		<li class="parent item2"><a href="#"><span>Genre</span></a>
			<ul>
				<li class="parent item8"><a href="genres/view/all"><span>See All</span></a></li>
				<li class="parent item9"><a href="genres/view/country"><span>Country</span></a></li>
				<li class="parent item10"><a href="genres/view/jazz"><span>Jazz</span></a></li>
				<li class="parent item11"><a href="genres/view/pop"><span>Pop</span></a></li>
				<li class="parent item12"><a href="genres/view/classical"><span>Classical</span></a></li>
			</ul>
		</li>
		<li class="item3"><a href="/management"><span>Featured Artist</span></a>
			<ul>
				<li class="parent item13"><a href="artist/billy_idol"><span>Billy Idol</span></a></li>
				<li class="parent item14"><a href="artist/black_eyed_peas"><span>Black Eyed Peas</span></a></li>
				<li class="parent item15"><a href="artist/ciara"><span>Ciara</span></a></li>
				<li class="parent item16"><a href="artist/pink"><span>P!nk</span></a></li>
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