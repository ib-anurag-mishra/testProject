<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Freegal Music : The New Music Library :'); ?>
		<?php echo $title_for_layout;;?>
	</title>
	<?php echo $this->Html->meta('icon'); ?>
	<?php echo $this->Html->css('all_new'); ?>
	<?php echo $scripts_for_layout; ?>
<script type="text/javascript" src="<?php echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.min.js,jquery.tools.min.js,inputs.js"></script>
<style>
#lbOverlay {
    background-color: #000000;
    color: #FFFFFF;
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 9999;
	display:none;
}
</style>
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
						$('body').html('');
						$('#footer').html('');
						$('body').html(response);
            initInputs();
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
					if(navigator.appName == 'Microsoft Internet Explorer'){
						location.reload();
					}else{
						$('body').html('');
						$('#footer').html('');
						$('body').html(response);
            initInputs();
					}
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
	}
	
	
jQuery(document).ready(function() {
	jQuery('form').submit(function() {
		jQuery('#lbOverlay').show();
	});
	jQuery("#loadingDiv").hide();
});	




</script>	
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1 class="logo"><a href="#">freegal music</a></h1>
		</div>
		<?php
			echo $session->flash();
			echo $session->flash('auth');
                        if(isset($show_inactivelib) &&  $show_inactivelib == 1){
		?>
        </div>
            
            <?php
                        }else{
            ?>
            
	<div id="main">
			<div id="loadingDiv" style="display:none;z-index: 100;position:absolute;left:40%; right:40%;top:45%;text-align:center;">
				<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
			</div>
			<div style="width:321px;height:529px">
				<?php echo $content_for_layout; ?>
				<div class="form-area">
					<!--<h2>Welcome to the Freegal Music log in page.</h2>
					<p><strong>Freegal Music gives you access to millions of songs from over 10,000 labels including the Sony Music catalog of your country.</strong></p> -->
					<?php echo utf8_encode($page->getPageContent('login_upper')); ?>
				</div>
				</div>
			<img class="decor" src="/img/img01.jpg" width="571" height="544" alt="image description" />
			<div class="article">
				<div class="images">
					<a class="btn-iphone" target="_blank" href="http://itunes.apple.com/us/app/freegal-music/id508036345?ls=1&mt=8"><img src="/img/btn-iphone.png" width="195" height="387" alt="image description" /></a>
					<a class="btn-andriod"target="_blank" href="https://play.google.com/store/apps/details?id=com.libraryideas.freegalmusic&feature=nav_result#?t=W251bGwsMSwyLDNd"><img src="/img/btn-andriod.png" width="196" height="394" alt="image description" /></a>
				</div>
				<div class="info">
					<?php echo $page->getPageContent('login_new'); ?>
				</div>
		</div>
	</div>
	<div id="lbOverlay" style="opacity: 0.8;filter: alpha(opacity = 80); zoom:1;"><div style="text-align:center;margin-top: 253px;"><?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?><br/><br/>Please wait. Login in progress...</div></div>
		<div id="footer">
			<ul class="ad">
				<li><a target="_blank" href="http://www.iodalliance.com/"><img src="/img/ad01.gif" width="62" height="48" alt="image description" /></a></li>
				<li><a target="_blank" href="http://www.sonymusic.com/"><img src="/img/ad02.gif" width="44" height="48" alt="image description" /></a></li>
				<li><a target="_blank" href="http://www.libraryideas.com/"><img src="/img/ad03.gif" width="89" height="48" alt="image description" /></a></li>
			</ul>
			<p>&copy; 2012 Library Ideas, LLC  All Rights Reserved</p>
		</div>
	</div>
             <?php
                        }
            ?>
</body>
</html>