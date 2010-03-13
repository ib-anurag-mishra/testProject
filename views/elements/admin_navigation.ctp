	<ul id="menu" class="sf-menu">
		<li>
			<a href="#" <?php if ($this->pageTitle == "Admin") echo "class=\"current\""; ?>>Users</a>
			<ul>
				<li>
					<?php echo $html->link('Add User', array('controller' => 'users', 'action' => 'userform'));?>
				</li>
				<li>
					<?php echo $html->link('Manage User', array('controller' => 'users', 'action' => 'manageuser'));?>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Libraries") echo "class=\"current\""; ?>>Libraries</a>
			<ul>
				<li>
					<?php echo $html->link('Add Library', array('controller' => 'libraries', 'action' => 'libraryform'));?>
				</li>
				<li>
					<?php echo $html->link('Manage Library', array('controller' => 'libraries', 'action' => 'managelibrary'));?>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Content") echo "class=\"current\""; ?>>Content</a>
			<ul>
				<li>
					<a href="#">Artist Slideshow</a>
					<ul>
						<li><?php echo $html->link('Add Artist', array('controller' => 'artists', 'action' => 'createartist'));?></li>
						<li><?php echo $html->link('Manage Slideshows', array('controller' => 'artists', 'action' => 'manageartist'));?></li>
					</ul>
				</li>
				<li>
					<a href="#">Featured Artist</a>
					<ul>
						<li><?php echo $html->link('Add Featured Artist', array('controller' => 'artists', 'action' => 'artistform'));?></li>
						<li><?php echo $html->link('Manage Featured Artist', array('controller' => 'artists', 'action' => 'managefeaturedartist'));?></li>
					</ul>
				</li>
				<li>
					<a href="#">Newly Added Artist</a>
					<ul>
						<li><?php echo $html->link('Add Artist', array('controller' => 'artists', 'action' => 'addnewartist'));?></li>
						<li><?php echo $html->link('Manage Newly Added Artist', array('controller' => 'artists', 'action' => 'managenewartist'));?></li>						
					</ul>
				</li>
				<li>
					<a href="#">Manage Genres</a>
					<ul>
						<li><?php echo $html->link('Manage Favorite Genres', array('controller' => 'genres', 'action' => 'managegenre'));?></li>						
					</ul>
				</li>
				<li>
					<?php echo $html->link('Manage FAQs', array('controller' => 'questions', 'action' => 'index'));?>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		</li>	
	</ul>