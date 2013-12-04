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
    echo $this->Html->css('all');
		echo $scripts_for_layout;
	?>
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
<div id="main">
    <?php
			echo $session->flash();
			echo $session->flash('auth');
		?>
		<div class="main-holder">
			<div class="main-frame">
      	<h1 class="logo"><a href="javascript:void(0)">freegal music</a></h1>
				<div class="section">
          <a target="_blank" href="http://itunes.apple.com/us/app/freegal-music/id508036345?ls=1&mt=8" class="btn-iphone"><img src="/img/iphone.png" alt="image description" width="199" height="385" /></a>
					<a target="_blank" href="https://play.google.com/store/apps/details?id=com.libraryideas.freegalmusic&feature=nav_result#?t=W251bGwsMSwyLDNd" class="btn-samsung"><img src="/img/samsung.png" alt="image description" width="197" height="385" /></a>
          <?php echo $content_for_layout; ?>
        </div>
      </div>

    <div id="content">
				<?php echo $page->getPageContent('login'); ?>
        <ul class="ad-list">
					<li><a target="_blank" href="http://www.iodalliance.com/"><img src="/img/ad1.jpg" alt="image description" width="60" height="32" /></a></li>
					<li><a target="_blank" href="http://www.sonymusic.com/"><img src="/img/ad2.jpg" alt="image description" width="43" height="47" /></a></li>
					<li><a target="_blank" href="http://www.libraryideas.com/"><img src="/img/ad3.jpg" alt="image description" width="88" height="44" /></a></li>
				</ul>
		</div>
	</div>
</div>
<div id="footer">
  <p>&copy; 2011 Library Ideas, LLC  All Rights Reserved</p>
</div>
</body>
</html>