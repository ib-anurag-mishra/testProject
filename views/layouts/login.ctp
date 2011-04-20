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
	function english(){
		var language = 'en';
		var data = "lang="+language;
		$.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"homes/language", // URL to request
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
					$('#loginText').html(response);
					$('#spanish').css('background-color',' #3D3D3D');
					$('#english').css('background-color','#ccc');
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
	}
	function spanish(){
		var language = 'es';
		var data = "lang="+language;
		$.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"homes/language", // URL to request
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
					$('#loginText').html(response);
					$('#english').css('background-color',' #3D3D3D');
					$('#spanish').css('background-color','#ccc');					
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
	}	
</script>	
</head>
<body>
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
	<div id="container"> <!-- content -->
		<?php echo $this->element('header'); ?>
		<?php $session->flash(); ?>
		<div id="content">
			<!-- Main contents start here -->
			<?php echo $content_for_layout; ?>
			<div id="loginText"><?php echo $page->getPageContent('login'); ?></div>
			<!-- Main contents end here -->
		</div><!-- content -->
	</div><!-- container -->
	<div id="footer">
		<div id="copyright">
			&copy; 2011 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
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
</body>
</html>