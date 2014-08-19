<?php
/*
 File Name : admin_navigation.php
File Description : View page for adfmin navigation
Author : m68interactive
*/
if ($this->Session->read('Auth.User.type_id') == 4 && $this->Session->read('Auth.User.consortium') == '') {
	?>
<ul id="menu" class="sf-menu">
	<?php
	if($library->getAuthenticationType($this->Session->read('Auth.User.id')) == "user_account") {
		?>
	<li><a href="#"
	<?php if ($this->pageTitle == "Admin") echo "class=\"current\""; ?>>Patrons</a>
		<ul>
			<li><?php echo $html->link('Add Patron', array('controller' => 'users', 'action' => 'patronform'));?>
			</li>
			<li><?php echo $html->link('Manage Patron', array('controller' => 'users', 'action' => 'managepatron'));?>
			</li>
		</ul>
	</li>
	<?php
	}
	?>
	<li><a href="#"
	<?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		<ul>
			<li><?php echo $html->link('Download Report', array('controller' => 'reports', 'action' => 'index'));?>
			</li>
			<?php if ($this->Session->read('AdminlibraryType') === '2') { ?>
			<li><?php echo $html->link('Streaming Report', array('controller' => 'reports', 'action' => 'streamingreport'));?>
			</li>
			<?php } ?>
			<?php if(isset($libraryLimited)){?>
			<li><?php echo $html->link('WishList Report', array('controller' => 'reports', 'action' => 'librarywishlistreport'));?>
			</li>
			<?php } ?>
		</ul>
	</li>

</ul>
<?php
} elseif ($this->Session->read('Auth.User.type_id') == 4 && $this->Session->read('Auth.User.consortium') != '') {
	?>
<ul id="menu" class="sf-menu">
	<li><a href="#"
	<?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		<ul>
			<li><?php echo $html->link('Library Download Report', array('controller' => 'reports', 'action' => 'index'));?>
			</li>
			<li><?php echo $html->link('Library WishList Report', array('controller' => 'reports', 'action' => 'librarywishlistreport'));?>
			</li>
			<li><?php echo $html->link('Library Consortium Report', array('controller' => 'reports', 'action' => 'consortium'));?>
			</li>
			<li><?php                                                      
			if($isHavingStreaming)
			{
				echo $html->link('Library Streaming Report', array('controller' => 'reports', 'action' => 'streamingreport'));
			}
			?>
			</li>
		</ul>
	</li>
</ul>
<?php
} elseif ($this->Session->read('Auth.User.type_id') == 1) {
	?>
<ul id="menu" class="sf-menu">
	<li><a href="#"
	<?php if ($this->pageTitle == "Admin") echo "class=\"current\""; ?>>Users</a>
		<ul>
			<li><?php echo $html->link('Add User', array('controller' => 'users', 'action' => 'userform'));?>
			</li>
			<li><?php echo $html->link('Manage User', array('controller' => 'users', 'action' => 'manageuser'));?>
			</li>
			<li><?php echo $html->link('Add Patron', array('controller' => 'users', 'action' => 'patronform'));?>
			</li>
			<li><?php echo $html->link('Manage Patron', array('controller' => 'users', 'action' => 'managepatron'));?>
			</li>
		</ul>
	</li>
	<li><a href="#"
	<?php if ($this->pageTitle == "Libraries") echo "class=\"current\""; ?>>Libraries</a>
		<ul>
			<li><?php echo $html->link('Add Library', array('controller' => 'libraries', 'action' => 'libraryform'));?>
			</li>
			<li><?php echo $html->link('Manage Library', array('controller' => 'libraries', 'action' => 'managelibrary'));?>
			</li>
			<li><?php echo $html->link('Manage Library Timezone', array('controller' => 'libraries', 'action' => 'librarytimezone'));?>
			</li>
			<li><?php echo $html->link('Add Consortium', array('controller' => 'libraries', 'action' => 'addconsortium'));?>
			</li>
			<li><?php echo $html->link('Manage Consortium', array('controller' => 'libraries', 'action' => 'consortium'));?>
			</li>
			<li><?php echo $html->link('mdlogin/mndlogin Cards', array('controller' => 'libraries', 'action' => 'card'));?>
			</li>
		</ul>
	</li>
	<li><a href="#"
	<?php if ($this->pageTitle == "Content") echo "class=\"current\""; ?>>Content</a>
		<ul>
			<li><a href="#">Artist Slideshow</a>
				<ul>
					<li><?php echo $html->link('Add Artist', array('controller' => 'artists', 'action' => 'createartist'));?>
					</li>
					<li><?php echo $html->link('Manage Slideshows', array('controller' => 'artists', 'action' => 'manageartist'));?>
					</li>
				</ul>
			</li>
			<li><a href="#">Top Albums</a>
				<ul>
					<li><?php echo $html->link('Add Top Album', array('controller' => 'artists', 'action' => 'topalbumform'));?>
					</li>
					<li><?php echo $html->link('Manage Top Albums', array('controller' => 'artists', 'action' => 'managetopalbums'));?>
					</li>
				</ul>
			</li>
			<li><a href="#">Top Singles</a>
				<ul>
					<li><?php echo $html->link('Add Top Single', array('controller' => 'artists', 'action' => 'topsingleform'));?>
					</li>
					<li><?php echo $html->link('Manage Top Singles', array('controller' => 'artists', 'action' => 'managetopsingles'));?>
					</li>
				</ul>
			</li>
			<li><a href="#">Newly Added Artist</a>
				<ul>
					<li><?php echo $html->link('Add Artist', array('controller' => 'artists', 'action' => 'addnewartist'));?>
					</li>
					<li><?php echo $html->link('Manage Newly Added Artist', array('controller' => 'artists', 'action' => 'managenewartist'));?>
					</li>
				</ul>
			</li>
			<li><a href="#">Manage Genres</a>
				<ul>
					<li><?php echo $html->link('Manage Favorite Genres', array('controller' => 'genres', 'action' => 'managegenre'));?>
					</li>
				</ul>
			</li>
			<li><a href="#">Manage Pages</a>
				<ul>
					<li><?php echo $html->link('Manage FAQs', array('controller' => 'questions', 'action' => 'index'));?>
					</li>
					<li><?php echo $html->link('Manage About Us', array('controller' => 'homes', 'action' => 'aboutusform'));?>
					</li>
					<li><?php echo $html->link('Manage Terms & Condition', array('controller' => 'homes', 'action' => 'termsform'));?>
					</li>
					<li><?php echo $html->link('Manage Download Limits', array('controller' => 'homes', 'action' => 'limitsform'));?>
					</li>
					<li><?php echo $html->link('Manage Login Screen Text', array('controller' => 'homes', 'action' => 'loginform'));?>
					</li>
					<li><?php echo $html->link('Manage Wish List Text', array('controller' => 'homes', 'action' => 'wishlistform'));?>
					</li>
					<li><?php echo $html->link('Manage Recent Downloads Text', array('controller' => 'homes', 'action' => 'historyform'));?>
					</li>
					<li><?php echo $html->link('Manage News', array('controller' => 'news', 'action' => 'index'));?>
					</li>
				</ul>
			</li>
			<li><?php echo $html->link('Site Settings', array('controller' => 'site_settings', 'action' => 'index'));?>
			</li>
			<li><?php echo $html->link('Add Language', array('controller' => 'homes', 'action' => 'language'));?>
			</li>
			<li><a href="#">Playlists</a>
				<ul>
					<li><?php echo $html->link('Add Playlist', array('controller' => 'artists', 'action' => 'addplaylist'));?>
					</li>
					<li><?php echo $html->link('Manage Playlist', array('controller' => 'artists', 'action' => 'manageplaylist'));?>
					</li>
				</ul>
			</li>                        
		</ul>
	</li>
	<li><a href="#"
	<?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		<ul>
			<li><?php echo $html->link('Library Download Report', array('controller' => 'reports', 'action' => 'index'));?>
			</li>
			<li><?php echo $html->link('Library Renewal Report', array('controller' => 'reports', 'action' => 'libraryrenewalreport'));?>
			</li>
			<li><?php echo $html->link('Library WishList Report', array('controller' => 'reports', 'action' => 'librarywishlistreport'));?>
			</li>
			<li><?php echo $html->link('Sony Sales Reports', array('controller' => 'reports', 'action' => 'sonyreports'));?>
			</li>
			<li><?php echo $html->link('Library Unlimited Report', array('controller' => 'reports', 'action' => 'unlimited'));?>
			</li>
			<li><?php echo $html->link('Library Consortium Report', array('controller' => 'reports', 'action' => 'consortium'));?>
			</li>
			<li><?php echo $html->link('Library Streaming Report', array('controller' => 'reports', 'action' => 'streamingreport'));?>
			</li>
		</ul>
	</li>
</ul>
<?php	
}elseif ($this->Session->read('Auth.User.type_id') == 6) {
	?>
<ul id="menu" class="sf-menu">
	<li><a href="#"
	<?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		<ul>
			<li><?php echo $html->link('Library Download Report', array('controller' => 'reports', 'action' => 'index'));?>
			</li>
			<li><?php echo $html->link('Library WishList Report', array('controller' => 'reports', 'action' => 'librarywishlistreport'));?>
			</li>
			<li><?php echo $html->link('Consortium Download Report', array('controller' => 'reports', 'action' => 'consortium'));?>
			</li>
			<li><?php echo $html->link('Library Streaming Report', array('controller' => 'reports', 'action' => 'streamingreport'));?>
			</li>
		</ul>
	</li>
</ul>
<?php	
} else {
	?>
<ul id="menu" class="sf-menu">
	<li><a href="#"
	<?php if ($this->pageTitle == "Reports") echo "class=\"current\""; ?>>Reports</a>
		<ul>
			<li><?php echo $html->link('Library Download Report', array('controller' => 'reports', 'action' => 'index'));?>
			</li>
			<li><?php echo $html->link('Library Renewal Report', array('controller' => 'reports', 'action' => 'libraryrenewalreport'));?>
			</li>
			<li><?php echo $html->link('Library WishList Report', array('controller' => 'reports', 'action' => 'librarywishlistreport'));?>
			</li>
			<li><?php echo $html->link('Sony Sales Reports', array('controller' => 'reports', 'action' => 'sonyreports'));?>
			</li>
			<li><?php echo $html->link('Library Unlimited Report', array('controller' => 'reports', 'action' => 'unlimited'));?>
			</li>
			<li><?php echo $html->link('Consortium Download Report', array('controller' => 'reports', 'action' => 'consortium'));?>
			</li>
			<li><?php echo $html->link('Library Streaming Report', array('controller' => 'reports', 'action' => 'streamingreport'));?>
			</li>
		</ul>
	</li>
</ul>
<?php	
}
?>
