<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Freegal Music : Your New Music Library :'); ?>
		<?php
			if ($title_for_layout == "Homes") {
				echo substr($title_for_layout, 0, -1);
			} else {
				echo $title_for_layout;
			}
			?>
	</title>
   	<?php
		echo $this->Html->meta('icon');
		//echo $javascript->link('ImageDisableRightClick');
		/*echo $this->Html->css('freegal_styles');
		echo $this->Html->css('jquery.autocomplete');
		echo $html->css('colorbox');
		echo $javascript->link('jquery.min');
		echo $javascript->link('jquery.colorbox');
		echo $javascript->link('jquery.cycle.all');
		echo $javascript->link('curvycorners');
		echo $javascript->link('swfobject');
		echo $javascript->link('audioPlayer');
		echo $javascript->link('freegal');
		echo $javascript->link('jquery.bgiframe');
		echo $javascript->link('jquery.autocomplete');*/
	?>
                <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=swfobject.js,jquery.min.js,audioPlayer.js,freegal.js,jquery.colorbox.js,jquery.cycle.all.js,curvycorners.js,jquery.bgiframe.js,jquery.autocomplete.js"></script>
	<?php
		//echo $javascript->link('jquery-1.3.2.min');
		echo $javascript->link('qtip');
		echo $javascript->link('qtip_add');
		echo $scripts_for_layout;
		if($this->Session->read('Config.language') == 'en'){
			$setLang = 'en';
		}else{
			$setLang = 'es';
		}
		if($this->Session->read('library') && $this->Session->read('library') != '')
		{
			$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
	?>
			<link href="<?php echo $this->webroot; ?>css/freegal_styles.php?library_bgcolor=<?php echo $libraryInfo['Library']['library_bgcolor'];?>&library_content_bgcolor=<?php echo $libraryInfo['Library']['library_content_bgcolor'];?>&library_nav_bgcolor=<?php echo $libraryInfo['Library']['library_nav_bgcolor'];?>&library_boxheader_bgcolor=<?php echo $libraryInfo['Library']['library_boxheader_bgcolor'];?>&library_boxheader_text_color=<?php echo $libraryInfo['Library']['library_boxheader_text_color'];?>&library_text_color=<?php echo $libraryInfo['Library']['library_text_color'];?>&library_links_color=<?php echo $libraryInfo['Library']['library_links_color'];?>&library_links_hover_color=<?php echo $libraryInfo['Library']['library_links_hover_color'];?>&library_navlinks_color=<?php echo $libraryInfo['Library']['library_navlinks_color'];?>&library_navlinks_hover_color=<?php echo $libraryInfo['Library']['library_navlinks_hover_color'];?>&library_box_header_color=<?php echo $libraryInfo['Library']['library_box_header_color'];?>&library_box_hover_color=<?php echo $libraryInfo['Library']['library_box_hover_color'];?>" type="text/css" rel="stylesheet" />
			<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
			<script type="text/javascript">
				$(document).ready(function() {
				//	checkPatron('<?php echo $this->Session->read('library'); ?>','<?php echo $this->Session->read('patron'); ?>');
					$('#autoComplete').keypress(function(event) {
						//auto_check();
						if (event.which == '13') {
						  $('#HomeSearchForm').submit();
						}
					});
					$("#autoComplete").autocomplete("<?php echo $this->webroot; ?>search/autocomplete",
					{
						minChars: 1,
						cacheLength: 10,
						autoFill: false,
            extraParams: {
              type:$('#type111').val()
            }
					}).result(function(e, item) {
						$('#auto').attr('value', 1);
					});
					<?php
					if($this->Session->read('approved') && $this->Session->read('approved') == 'no')
					{
					?>
						$(".termsApproval").colorbox({width:"50%", inline:true, open:true, overlayClose:false, noEscape: true, href:"#termsApproval_div", onOpen:function(){$(document).unbind("keydown.cbox_close");}});
					<?php }	?>
				});
				var languageSet = '<?php echo $setLang; ?>';
				var webroot = '<?php echo $this->webroot; ?>';
				var params = {allowscriptaccess:"always", menu:"false", bgcolor:"000000"};
				var attributes = { id: "audioplayer" };
				swfobject.embedSWF("<?php echo $this->webroot; ?>swf/audioplayer.swf", "audioflash", "1", "0", "9.0.0", "<?php echo $this->webroot; ?>swf/xi.swf", {}, params, attributes);
			</script>
			<style>
				<?php
				if($this->Session->read('approved') && $this->Session->read('approved') == 'no')
				{
				?>
					#cboxClose{display:none !important;}
				<?php
				}
				?>
				#slideshow a { display: none }
				#slideshow a.first { display: block }
				#featured_artist a { display: none }
				#featured_artist a.first { display: block }
				#newly_added a { display: none }
				#newly_added a.first { display: block }
			</style>
	<?php
		}
		else {
	?>
			<link href="<?php echo $this->webroot; ?>css/freegal_styles.php" type="text/css" rel="stylesheet" />
			<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
	<?php
		}
	?>
<!--[if IE 7]>
	<style>
		#ticker {
			line-height: 16px;
		}
		ul.marquee {
			margin-top: -16px;
		}
		.genreSeeAll {
			margin-top: -20px;
		}
		#search .submit {
			padding: 0 0 0 2px;
			height:22px;
		}
	</style>
<![endif]-->
<!--[if IE 8]>
	<style>
		#search .submit {
			padding: 0 0 0 2px;
			height:20px;
		}
	</style>
<![endif]-->
	<noscript>
		<?php
			if($this->params['action'] != 'aboutus') {
				echo $html->meta(null, null, array( 'http-equiv' => 'refresh', 'content' => "0.1;url=".$this->webroot."homes/aboutus/js_err"), false);
			}
		 ?>
	</noscript>
	<script type="text/javascript">
		$().ready(function() {
			var tmpcookie = new Date();
			chkcookie = (tmpcookie.getTime() + '');
			document.cookie = "chkcookie=" + chkcookie;
			if (document.cookie.indexOf(chkcookie,0) < 0) {
				<?php if(!isset($this->params['pass']['0'])){ ?>
					location.href = "<?php echo $this->webroot; ?>homes/aboutus/cookie_err";
				<?php } ?>
			}
		});
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
	<div id="audioPixel"><div id="audioflash"></div></div>
	<?php $session->flash(); ?>
	<a class='upgradeFlash' href="#"></a>
	<div style="display:none;">
		<div id="upgradeFlash_div">
			This site requires Flash player version 9 or more to play the sample audio files.
			Please <a class="orange_link"  href="http://www.adobe.com/support/flashplayer/downloads.html" target="_blank">click here</a>
			to upgrade your Flash Player.<br /><br />
		</div>
	</div>
	<?php
	if($this->Session->read('approved') && $this->Session->read('approved') == 'no')
	{ ?>
		<a class='termsApproval' href="#"></a>
		<div style="display:none;">
			<div id="termsApproval_div">
				<div id="loaderDiv" style="display:none;position:absolute;width:100%;text-align:center;top:0;bottom:0;left:0;right:0;z-index:10000;">
					<?php echo $html->image('ajax-loader-big.gif', array('alt' => 'Loading...')); ?>
				</div>
				<b>You need to accept the terms and conditions to browse the site.</b><br />
				<div style="overflow:auto;height:200px;border: 1px solid #ccc; margin: 10px; padding: 5px; text-align: justify;"><?php echo $page->getPageContent('terms'); ?></div><br />
				<input type="button" value="Accept" onclick="Javascript: approvePatron('<?php echo $this->Session->read('library'); ?>','<?php echo $this->Session->read('patron'); ?>');"> <input type="button" value="Deny" onclick="Javascript: history.back();">
			</div>
		</div>
	<?php } ?>
	<div id="border-background" >
	<div id="container">
		<?php echo $this->element('header'); ?>
		<div id="content">
			<?php
			if($this->Session->read('library') && $this->Session->read('library') != '')
			{
				echo $this->element('navigation');
			}
			echo $content_for_layout; ?>
		</div>
		<br class="clr">
	</div>
	<?php echo $this->element('footer'); ?>
	</div>
</body>
</html>