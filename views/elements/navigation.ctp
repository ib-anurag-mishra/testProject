<?php
/**
	File Name : navigation.php
	File Description : View page for navigation file for front-end site
	Author : m68interactive
 **/

/**
 * Navigation file for front-end site
 **/
if($this->Session->read('library') && $this->Session->read('library') != '')
{
	$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
	$downloadCount = $download->getDownloadDetails($this->Session->read('library'),$this->Session->read('patron'));
	if($libraryInfo['Library']['library_unlimited'] != "1" && $libraryInfo['Library']['library_authentication_method'] == "user_account"){
		$width = 125;
		
	}elseif($libraryInfo['Library']['library_unlimited'] == "1" && $libraryInfo['Library']['library_authentication_method'] == "user_account"){
		$width = 150;
	}else{
		$width = 168;
	}
}
 ?>

<style>
body {
	margin:0;
	color:#000;
	font:13px Tahoma, Arial, Helvetica, sans-serif;
	background:#797979;
	min-width:965px;
}
img {border-style:none;}
a {
	text-decoration:underline;
	color:#0080ff;
}
a:hover {text-decoration:none;}
input,
textarea,
select {
	font:12px Arial, Helvetica, sans-serif;
	vertical-align:middle;
	color:#000;
}
form, fieldset {
	margin:0;
	padding:0;
	border-style:none;
}
#wrapper {
	width:100%;
	overflow:hidden;
	position:relative;
}
.hidden {
	position:absolute;
	left:-9999px;
	top:auto;
	width:1px;
	height:1px;
	overflow:hidden;
}
.skip {
	position:absolute;
	left:-10000px;
	top:auto;
	width:1px;
	height:1px;
	overflow:hidden;
}
.skip:focus {
	position:static;
	width:auto;
	height:auto;
} 
.btn-support {
	width:47px;
	height:132px;
	overflow:hidden;
	text-indent:-9999px;
	background:url(../img/btn-support.png) no-repeat;
	position:absolute;
	top:250px;
	right:0;
	z-index:100;
}
.w1 {
	width:965px;
	background:#fff;
	margin:0 auto;
	overflow:hidden;
}
#header {
	text-align:right;
	width:100%;
	color:#9a9a9a;
	font-size:13px;
}
#header .top-box {
	overflow:hidden;
	height:50%;
	background:#fefefe; 
	padding:25px 5px 0 25px;
}
#header p {margin:0;}
.logo {
	margin:-25px 0 0;
	width:350px;
	height:100px;
	overflow:hidden;
	float:left;
	cursor:pointer;
	text-align:left;
}
.logo a {
	height:100%;
	display:block;
}
.social {
	padding:0;
	margin:-11px 0 0 22px;
	list-style:none;
	float:right;
	text-align:left;
}
.social li {
	float:left;
	width:34px;
	padding:0 13px 0 0;
}
.social li a {
	width:34px;
	height:34px;
	overflow:hidden;
	text-indent:-9999px;
	display:block;
}
#nav {
	padding:0;
	margin:0;
	list-style:none;
	overflow:hidden;
	width:100%;
	font:15px/17px Tahoma, Arial, sans-serif;
	background:#004080;
	margin: 5px 0 0;
}
#nav li {width:<?php echo $width.'px';?>;}

#nav li.first-child {background:none;}

#nav li a {
	color:#fff;
	float:left;
	text-align:center;
	text-decoration:none;
}
#nav li a:hover {text-decoration:underline;}
</style>

<div id="header">
	<div class="top-box">
		<ul class="social">
		<?php
		if($libraryInfo['Library']['twiter_icon'] != "") {
		?>		
			<li><a href="<?php echo $libraryInfo['Library']['twiter_icon'];?>" class="twitter">twitter</a></li>
		<?php 
			} 
		?>
		<?php
		if($libraryInfo['Library']['facebook_icon'] != "") {
		?>		
			<li><a href="<?php echo $libraryInfo['Library']['facebook_icon'];?>" class="facebook">facebook</a></li>
		<?php 
			} 
		?>
		<?php
		if($libraryInfo['Library']['youtube_icon'] != "") {
		?>		
			<li><a href="<?php echo $libraryInfo['Library']['youtube_icon'];?>" class="youtube">youtube</a></li>
		<?php 
			} 
		?>			
		</ul>
		<h1 class="logo">
		<?php
		if($libraryInfo['Library']['library_image_name'] != "") {
		?>
				<?php
				if($libraryInfo['Library']['library_home_url'] != "") {
				?>
					<a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>" target="_blank"><img src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>"></a>
				<?php
				}else{
				?>
					<img src="<?php echo str_replace("test","prod",$cdnPath); ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>">
				<?php
				}
				?>
		<?php
		}
		?>
		</h1>
		<p>Weekly Downloads <span id='downloads_used'><?php echo $downloadCount; ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?></p>
	</div>
	<ul id="nav">
		<li class="first-child" ><?php echo $html->link(__('Home', true), array('controller' => 'homes','action'=>'index') , array('class' => 'navigation_item') );?></li>
		<li><?php echo $html->link(__('Genres', true), array('controller' => 'genres','action'=>'view'));?></li>
		<li><?php echo $html->link(__('News', true), array('controller' => 'news','action'=>'index'));?></li>
		<?php if($libraryInfo['Library']['library_unlimited'] != "1"){ ?>
		<li><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' => 'my_wishlist')); ?></li>
		<?php } ?>
		<?php if($libraryInfo['Library']['library_authentication_method'] == "user_account"){ ?>
		<li><?php echo $html->link(__('My Account', true), array('controller' => 'users', 'action' => 'my_account')); ?></li>
		<?php } ?>		
		<li><?php echo $html->link(__('My History', true), array('controller' => 'homes', 'action' => 'my_history')); ?></li>
		<li><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?></li>
		<li><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));?></li>
	</ul>
</div>