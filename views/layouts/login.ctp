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
<script type="text/javascript" src="<?php echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.min.js,jquery.tools.min.js"></script>	
<script type="text/javascript">
	var webroot = '<?php echo $this->webroot; ?>';
	function changeLang(type,page){
		$("#loadingDiv").show();
		var language = type;
		var data = "lang="+language;
		$.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"users/"+page, // URL to request
			data: data,  // post data
			success: function(response) {
				var msg = response.substring(0,5);
				if(msg == 'error')
				{
					alert("There was an error while saving your request.");
					location.reload();
					return false;
				}
				else
				{
					if(navigator.appName == 'Microsoft Internet Explorer'){
						location.reload();
					}else{
						$('#container').html('');
						$('#footer').html('');
						$('#container').html(response);
					}
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
	}
	function changeLang_password(type,page){
		$("#loadingDiv").show();
		var language = type;
		var data = "lang="+language;
		$.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"homes/"+page, // URL to request
			data: data,  // post data
			success: function(response) {
				var msg = response.substring(0,5);
				if(msg == 'error')
				{
					alert("There was an error while saving your request.");
					location.reload();
					return false;
				}
				else
				{
					$('#main').html('');
					$('#footer').html('');
				//	$('#container').html(response);
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
	}
	
jQuery(document).ready(function() {
	$("#loadingDiv").hide();
});	
</script>	
</head>
<body>
<div id="container">
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
	<div id="main">
		<?php
			echo $session->flash();
			echo $session->flash('auth');				
		?>	
		<div class="main-holder">
			<div class="visual">
				<img src="/img/img1.png" alt="image description" class="decor pos1" width="161" height="158" />
				<img src="/img/img2.png" alt="image description" class="decor pos2" width="153" height="148" />
				<img src="/img/img3.png" alt="image description" class="decor pos3" width="181" height="180" />
				<img src="/img/img4.png" alt="image description" class="decor pos4" width="184" height="180" />
				<img src="/img/img5.png" alt="image description" class="decor pos5" width="153" height="150" />
				<img src="/img/img6.png" alt="image description" class="decor pos6" width="170" height="167" />
			</div>
			<div id="content">
				<div class="popup">
					<div class="popup-t"></div>
					<div class="popup-c">
						<h1 class="logo"><a href="#">Freegal music</a></h1>
				<!-- Main contents start here -->
				<?php echo $content_for_layout; ?>
				<div id="loginText">
				<div id="loadingDiv" style="z-index: 100;position:absolute;left:40%; right:40%;top:45%;text-align:center;">
					<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
				</div>
				</div>
				<!-- Main contents end here -->
				</div>
				<div class="popup-b"></div>
				</div>
				<?php echo $page->getPageContent('login'); ?>
				<ul class="ad-list">
					<li><a href="http://www.iodalliance.com/"><img src="/img/ad1.jpg" alt="image description" width="60" height="32" /></a></li>
					<li><a href="http://www.sonymusic.com/"><img src="/img/ad2.jpg" alt="image description" width="43" height="47" /></a></li>
					<li><a href="http://www.libraryideas.com/"><img src="/img/ad3.jpg" alt="image description" width="88" height="44" /></a></li>
				</ul>
			</div>
		</div>
	</div>
		<div id="footer">
			<div id="copyright">
				&copy; 2011 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
			</div>
		</div>
	</div>
<!--[if IE 7]>	
	<style>
		.forgot_password a {
			color:#666666;
			float:right;
			margin:2px 40px 0 57px !important;
			text-decoration:none; 
		}
	</style>
<![endif]-->
</div>
</body>
</html>