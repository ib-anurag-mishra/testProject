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
 * Footer file for home page
 **/

if ($this->Session->read('library') && $this->Session->read('library') != '') {
?>
<div id="footer">
	<div id="copyright" style="float:left;">
		&copy; 2010 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
	</div>	
	<?php echo $html->link('About Freegal Music', array('controller' => 'homes', 'action' => 'aboutus')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link('Terms & Conditions', array('controller' => 'homes', 'action' => 'terms')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link('FAQ', array('controller' => 'questions', 'action' => 'index')); ?>
</div>
<?php
}