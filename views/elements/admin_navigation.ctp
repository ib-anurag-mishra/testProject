	<ul id="menu" class="sf-menu">
		<li>
			<a href="#" <?php if ($this->pageTitle == "Users") echo "class=\"current\""; ?>>Users</a>
			<ul>
				<li>
					<?php echo $html->link('Add User', array('controller' => 'admin_homes','action'=>'userform'));?>
				</li>
				<li>
					<?php echo $html->link('Manage User', array('controller' => 'admin_homes','action'=>'manageuser'));?>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Libraries") echo "class=\"current\""; ?>>Libraries</a>
			<ul>
				<li>
					<?php echo $html->link('Add Library', array('controller' => 'Libraries','action'=>'libraryform'));?>
				</li>
				<li>
					<?php echo $html->link('Manage Library', array('controller' => 'Libraries','action'=>'managelibrary'));?>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Content") echo "class=\"current\""; ?>>Content</a>
			<ul>
				<li>
					<a href="#">Artist Slideshow</a>
					<ul>
						<li><?php echo $html->link('Add Artist', array('controller' => 'Artists','action'=>'createartist'));?></li>
						<li><?php echo $html->link('Manage Slideshows', array('controller' => 'Artists','action'=>'manageartist'));?></li>
					</ul>
				</li>
				<li>
					<a href="#">Featured Artist</a>
					<ul>
						<li><?php echo $html->link('Add Featured Artist', array('controller' => 'Artists','action'=>'artistform'));?></li>
						<li><?php echo $html->link('Manage Featured Artist', array('controller' => 'Artists','action'=>'managefeaturedartist'));?></li>
					</ul>
				</li>
				<li>
					<a href="#">Newly Added Artist</a>
					<ul>
						<li><a href="#">Add Artist</a></li>
						<li><a href="#">Manage Newly Added Artist</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" <?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		</li>	
	</ul>