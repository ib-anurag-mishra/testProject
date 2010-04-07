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
?>
<div id="footer">
	<?php echo $html->link('About Freegal Music', array('controller' => 'homes', 'action' => 'aboutus')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link('Terms & Conditions', array('controller' => 'homes', 'action' => 'terms')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link('FAQ', array('controller' => 'questions', 'action' => 'index')); ?>
</div>