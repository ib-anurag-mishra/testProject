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
?>
<div id="header">
	<div id="lib_name">The Public Library</div>
	<div id="header_right">
		<ul>
			<li>Weekly Downloads <?php echo $this->Session->read('downloadsUsed'); ?>/<?php echo $this->Session->read('downloadsAllotted'); ?><a href="#"><img src="<?php echo $this->webroot; ?>img/question.png" border="0" width="12" height="14"></a> | <a href="#">FAQ</a></li>
			<li><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></li>
		</ul>
	</div>
</div>