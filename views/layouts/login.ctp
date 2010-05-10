<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Freegal Music : The New Music Library :'); ?>
		<?php echo $title_for_layout;;?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('freegal_styles');
		echo $scripts_for_layout;
	?>
	<!--[if lt IE 7]>
	  	<div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
	    	<div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'>
				<a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'>
					<img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/>
				</a>
			</div>
	    	<div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
	      		<div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
	      		<div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
	        		<div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>You are using an outdated browser</div>
	        		<div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>For a better experience using this site, please upgrade to a modern web browser.</div>
	      		</div>
	      		<div style='width: 75px; float: left;'>
					<a href='http://www.firefox.com' target='_blank'>
						<img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/>
					</a>
				</div>
	      		<div style='width: 75px; float: left;'>
					<a href='http://www.browserforthebetter.com/download.html' target='_blank'>
						<img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/>
					</a>
				</div>
	      		<div style='width: 73px; float: left;'>
					<a href='http://www.apple.com/safari/download/' target='_blank'>
						<img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/>
					</a>
				</div>
	      		<div style='float: left;'>
					<a href='http://www.google.com/chrome' target='_blank'>
						<img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/>
					</a>
				</div>
	    	</div>
	  	</div>
	<![endif]-->
</head>
<body>
	<div id="container"> <!-- content -->
		<?php echo $this->element('header'); ?>
		<?php $session->flash(); ?>
		<div id="content">
			<!-- Main contents start here -->
			<?php echo $content_for_layout; ?>
			<div id="loginText">
				<p class="loginHeading">Welcome to the Freegal Music log in page</p>
				<p>How Freegal Music Works:</p>
				<ul>
					<li>Library users have a weekly download limit.  You will be able to keep track of your downloads in the upper right corner of the site. Every song has a sample clip you can listen to before you download.</li>
					<li>The library may have an overall weekly limit, too.  If your library runs out of downloads for the week, you can go to “My Wishlist” in the upper right corner and queue up for future music.</li>
					<li>The downloads on this site are all in the MP3 format with no DRM. This service will work with any MP3 player, including iPod, and can be loaded into iTunes.  It works on both PCs and Macs.</li>
					<li>Be sure to check out the browsing areas, especially Artists A to Z (bottom of page) and the genre lists (menu bar).  Click on see all genres to view dozens of categories.</li>
				</ul>
			<p>Enjoy the site!</p>
			<p class="loginSubText">Freegal Music gives you access to hundreds of thousands of songs in the Sony Music catalog. You can read more about Sony Music at <a href="http://www.sonymusic.com" target="_blank">www.SonyMusic.com</a></p>
			</div>
			<!-- Main contents end here -->
		</div><!-- content -->
	</div><!-- container -->
	<div id="footer">
		<div id="copyright">
			&copy; 2010 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
		</div>
	</div>
</body>
</html>