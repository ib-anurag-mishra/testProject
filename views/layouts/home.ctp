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
		echo $javascript->link('jquery.min');
		echo $javascript->link('jquery.colorbox');
		echo $javascript->link('jquery.cycle.all');
		echo $javascript->link('curvycorners');
		echo $javascript->link('swfobject');
		echo $javascript->link('audioPlayer');
		echo $javascript->link('freegal');
		echo $javascript->link('jquery.bgiframe');
		echo $javascript->link('jquery.autocomplete');
		echo $scripts_for_layout;
		$libraryInfo = $library->getLibraryDetails($_SESSION['library']);
	?>
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
		});
		
		var webroot = '<?php echo $this->webroot; ?>';	
		var params = {allowscriptaccess:"always", menu:"false", bgcolor:"000000"};
		swfobject.embedSWF("<?php echo $this->webroot; ?>swf/audioplayer.swf", "audioPlayer", "1", "0", "9.0.0", "<?php echo $this->webroot; ?>swf/xi.swf", {}, params);
	</script>
	<style>
		#slideshow a { display: none }
		#slideshow a.first { display: block }
		#featured_artist a { display: none }
		#featured_artist a.first { display: block }
		#newly_added a { display: none }
		#newly_added a.first { display: block }
	</style>
</head>
<body>
	<div id="audioPlayer"></div>
	<?php $session->flash(); ?>
	<a class='example8' href="#"></a>
	<div style="display:none;">
		<div id="upgradeFlash_div">   
			This site requires Flash player version 9 or more to play the sample audio files.
			Please <a class="orange_link"  href="http://www.adobe.com/support/flashplayer/downloads.html" target="_blank">click here</a> 
			to upgrade your Flash Player.<br /><br />
		</div>
	</div>
	<div id="container">
		<?php echo $this->element('header'); ?>
		<div id="content">
			<?php echo $this->element('navigation'); ?>
			<?php echo $content_for_layout; ?>
		</div>
		<br class="clr">
	</div>
	<?php echo $this->element('footer'); ?>
</body>
</html>