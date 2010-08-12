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
if($this->Session->read('library') && $this->Session->read('library') != '')
{
	$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
	$downloadCount = $download->getDownloadDetails($this->Session->read('library'),$this->Session->read('patron'));
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
	<?php
	if(!$libraryInfo['Library']['show_library_name']) {
	?>
		<div id="lib_name"><?php echo $libraryInfo['Library']['library_name']; ?></div>
	<?php
	}
	?>
	<div id="header_right">
		<ul>
			<li>
				Weekly Downloads <span id="downloads_used"><?php echo $downloadCount; ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?>
				<?php 
				echo $html->image("question.png", array("alt" => "Download Limits", "width" => 12, "height" => 14, "id" => 'qtip')); ?>
				&nbsp;|&nbsp;
				<?php echo $html->link('FAQ', array('controller' => 'questions', 'action' => 'index')); ?>
				&nbsp;|&nbsp;
				<?php echo $html->link('My Wishlist', array('controller' => 'homes', 'action' => 'my_wishlist')); ?>
				&nbsp;|&nbsp;
				<?php echo $html->link('My History', array('controller' => 'homes', 'action' => 'my_history')); ?>
				<?php if ($this->Session->read('Auth.User')) { ?>
					&nbsp;|&nbsp;					
					<?php echo $html->link('My Account', array('controller' => 'users', 'action' => 'my_account'));					
				}?>
				&nbsp;|&nbsp;
				<?php echo $html->link('Logout', array('controller' => 'users', 'action' => 'logout'));?>				
			</li>
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
			<li>&nbsp;</li>
			<li><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></li>
		</ul>
	</div>
</div>
<?php
}
?>