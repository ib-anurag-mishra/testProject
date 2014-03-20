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
		echo $this->Html->css('jquery.autocomplete');
		echo $html->css('colorbox');
	?>
                <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=jquery.min.js,jquery.colorbox.js,jquery.cycle.all.js,curvycorners.js,audioPlayer.js,freegal.js,jquery.bgiframe.js,"></script>
        <?php
            if ($this->Session->read("patron"))
            {
                if ($this->Session->read('library_type') == '2')
                {  ?>      
                    <script type="text/javascript" src="/js/swfobject.js" charset="utf-8"></script>
       <?php    }    

            } 
        ?>                 
               
	<?php
		echo $scripts_for_layout;
	?>
		<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=jquery.autocomplete.css,colorbox.css" />
		<link href="<?php echo $this->webroot; ?>css/freegal_styles.php" type="text/css" rel="stylesheet">
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
	</style>
<![endif]-->
  
</head>
<body>
	<div id="audioPlayer"></div>
	<?php $session->flash(); ?>
	<div id="container">
		<?php echo $this->element('header'); ?>
		<div id="content">
			<?php echo $content_for_layout; ?>
		</div>

	</div>
	<?php echo $this->element('footer'); ?>
</body>
</html>