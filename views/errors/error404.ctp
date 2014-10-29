
<?php echo $session->flash(); ?>
<?php
/*
<div id="aboutBox">Error Freegal Music&trade;</div>
<div id="aboutUs">
	Sorry the page you are trying to access does not exist.
</div>
*/
?>
<?php

	$url = $_SERVER['SERVER_NAME'];
	$host = explode('.', $url);
	$subdomains = array_slice($host, 0, count($host) - 2 );
	$subdomains = $subdomains[0];
?>
<div style="color: #52c6ec; font-size:24px; font-weight:bold">Page Not Found</div>
<div style="margin-top:20px;">Sorry, this page does not exist.</div>
<div style="margin-top:20px;">Here are some other useful pages:</div>
<ul style="margin-left:20px; margin-top: 20px;">

	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('Home', true), array('controller' => 'homes','action' => 'index'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Home')"));?>
	</li>
	<?php if($isMovie == 1) {
		$hostName = $_SERVER['SERVER_NAME'];
		$domain = explode('.',$hostName);
		$Concertlink = "http://$domain[0].".Configure::read('App.MoviesPath').'/listing/Q29uY2VydCBWaWRlb3M=';


	?>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('Concert Videos', true), $Concertlink, array("target" => '_blank', "onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Concert Videos')"));?>
	</li>
	<?php } ?>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' =>'index'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Music Videos')")); ?>
	</li>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php
		if($subdomains !== '' && $subdomains != 'www' && $subdomains != 'freegalmusic') { 
			echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' => 'my_lib_top_10'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Most Popular')"));
		} else { 
			if($this->Session->read("patron")) {
				echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' =>'my_lib_top_10'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Most Popular')"));
			} else { 
				echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' => 'us_top_10'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Most Popular')"));
			}
		}
		?>
	</li>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' => 'new_releases'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'New Releases')")); ?>
	</li>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' => 'view'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'Genres')")); ?>
	</li>
	<li style="margin-left:20px; margin-bottom:20px; list-style-type: disc">
		<?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index'), array("onclick" => "ga('send', 'event', '404 Nav', 'Click', 'FAQ')")); ?>
	</li>
</ul>

