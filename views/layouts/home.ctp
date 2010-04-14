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
		//echo $this->Html->css('freegal_styles');
		echo $this->Html->css('jquery.autocomplete');
		echo $html->css('colorbox');
		/*echo $javascript->link('jquery.min');
		echo $javascript->link('jquery.colorbox');
		echo $javascript->link('jquery.cycle.all');
		echo $javascript->link('curvycorners');
		echo $javascript->link('swfobject');
		echo $javascript->link('audioPlayer');
		echo $javascript->link('freegal');
		echo $javascript->link('jquery.bgiframe');
		echo $javascript->link('jquery.autocomplete');*/
	?>		
                <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.min.js,jquery.colorbox.js,jquery.cycle.all.js,curvycorners.js,swfobject.js,audioPlayer.js,freegal.js,jquery.bgiframe.js,jquery.autocomplete.js"></script>
	<?php
		echo $scripts_for_layout;
		if(isset($_SESSION['library']) && $_SESSION['library'] != '')
		{
			$libraryInfo = $library->getLibraryDetails($_SESSION['library']);
	?>
			<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
			<link href="<?php echo $this->webroot; ?>css/freegal_styles.php?library_bgcolor=<?php echo $libraryInfo['Library']['library_bgcolor'];?>&library_content_bgcolor=<?php echo $libraryInfo['Library']['library_content_bgcolor'];?>&library_nav_bgcolor=<?php echo $libraryInfo['Library']['library_nav_bgcolor'];?>&library_boxheader_bgcolor=<?php echo $libraryInfo['Library']['library_boxheader_bgcolor'];?>&library_boxheader_text_color=<?php echo $libraryInfo['Library']['library_boxheader_text_color'];?>&library_text_color=<?php echo $libraryInfo['Library']['library_text_color'];?>&library_links_color=<?php echo $libraryInfo['Library']['library_links_color'];?>&library_links_hover_color=<?php echo $libraryInfo['Library']['library_links_hover_color'];?>&library_navlinks_color=<?php echo $libraryInfo['Library']['library_navlinks_color'];?>&library_navlinks_hover_color=<?php echo $libraryInfo['Library']['library_navlinks_hover_color'];?>" type="text/css" rel="stylesheet">
			<script type="text/javascript">
				$().ready(function() {
					$("#autoComplete").autocomplete("<?php echo $this->webroot; ?>homes/autoComplete",
					{
						minChars: 1,
						cacheLength: 10,
						autoFill: false
					});
					checkPatron('<?php echo $this->Session->read('library'); ?>','<?php echo $this->Session->read('patron'); ?>');
					<?php
					if(isset($_SESSION['approved']) && $_SESSION['approved'] == 'no')
					{
					?>
						$(".termsApproval").colorbox({width:"50%", inline:true, open:true, overlayClose:false, noEscape: true, href:"#termsApproval_div", onOpen:function(){$(document).unbind("keydown.cbox_close");}});
					<?php }	?>
				});
				
				var webroot = '<?php echo $this->webroot; ?>';	
				var params = {allowscriptaccess:"always", menu:"false", bgcolor:"000000"};
				swfobject.embedSWF("<?php echo $this->webroot; ?>swf/audioplayer.swf", "audioPlayer", "1", "0", "9.0.0", "<?php echo $this->webroot; ?>swf/xi.swf", {}, params);
			</script>
			<style>
				<?php
				if(isset($_SESSION['approved']) && $_SESSION['approved'] == 'no')
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
			<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
			<link href="<?php echo $this->webroot; ?>css/freegal_styles.php" type="text/css" rel="stylesheet">
	<?php
		}
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
<!--[if = IE 7]>
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
	</style>
<![endif]-->
  
</head>
<body>
	<div id="audioPlayer"></div>
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
	if(isset($_SESSION['approved']) && $_SESSION['approved'] == 'no')
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
	<div id="container">
		<?php echo $this->element('header'); ?>
		<div id="content">
			<?php
			if(isset($_SESSION['library']) && $_SESSION['library'] != '')
			{
				echo $this->element('navigation');
			}
			echo $content_for_layout; ?>
		</div>
		<br class="clr">
	</div>
	<?php echo $this->element('footer'); ?>
</body>
</html>