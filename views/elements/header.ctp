<?php
/**
 * 
 *
 * @author Rob Richmond
 * @version $Id$
 * @copyright Maycreate Idea Group, 19 February, 2010
 * @package default
 **/

/**
 * Header file for home page
 **/
if(isset($_SESSION['library']) && $_SESSION['library'] != '')
{
	$libraryInfo = $library->getLibraryDetails($_SESSION['library']);

?>
<div id="header">
	<?php
	if($libraryInfo['Library']['library_image_name'] != "") {
	?>
		<div id="lib_image">
			<img src="<?php echo $this->webroot; ?>img/libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>">
		</div>
	<?php
	}
	?>
	<div id="lib_name"><?php echo $libraryInfo['Library']['library_name']; ?></div>
	<div id="header_right">
		<ul>
			<li>Weekly Downloads <span id="downloads_used"><?php echo $this->Session->read('downloadsUsed'); ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?> <a href="#"><img src="<?php echo $this->webroot; ?>img/question.png" border="0" width="12" height="14"></a> | <a href="#">FAQ</a></li>
			<li><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></li>
		</ul>
	</div>
</div>
<?php
}
else {
?>
<div id="header">
	<div id="lib_name">FreegalMusic.Com</div>
	<div id="header_right">
		<ul>
			<li><a href="#"><img src="<?php echo $this->webroot; ?>img/question.png" border="0" width="12" height="14"></a> | <a href="#">FAQ</a></li>
			<li><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></li>
		</ul>
	</div>
</div>
<?php
}
?>