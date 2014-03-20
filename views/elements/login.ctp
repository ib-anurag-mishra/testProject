<?php
/**
 File Name : login.php
 File Description : View page for login of the application
 Author : m68interactive
 **/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en" />
<title>Freegal Music</title>
<script type="text/javascript" src="./design/js/javascript.js"></script>
<?php echo $html->css('default');?>

</head>
<body>
	<div id="witti">

		<div id="header">
			<a href="javascript:void(0)" class="logo"><span>Admin Area</span> </a>
			<p class="userinfo">
				<strong></strong>
			</p>
			<div class="cleaner"></div>
		</div>
		<!-- header -->

		<div id="main">
			<!-- content -->
			<div id="content">
				<!-- Main contents start here -->
				<?php echo $message; ?>
				<!-- Main contents end here -->
			</div>
			<!-- content -->
			<div class="cleaner"></div>
		</div>
		<!-- main -->

		<div id="footer"></div>
		<!-- footer -->

	</div>
	<!-- witti -->
</body>
</html>
