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
		&copy; 2011 Library Ideas, LLC&nbsp;&nbsp;<?php __('All Rights Reserved') ?>
	</div>
	<div class="footerlinks">
		<div class="footerLink"><?php echo $html->link(__('About Freegal Music', true), array('controller' => 'homes', 'action' => 'aboutus')); ?></div>
		<div class="navbar">|</div>
		<div class="footerLink"><?php echo $html->link(__('Terms & Conditions', true), array('controller' => 'homes', 'action' => 'terms')); ?></div>
		<div class="navbar">|</div>
		<div class="footerLink"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?></div>
	</div>
</div>
	<span id="language">
		<?php __('Also available in'); ?>&nbsp;
		<?php
		$language = $language->getLanguage();
		$i =1;
		foreach($language as $k => $v){
			echo '<a href="javascript:void(0)" id='.$k.' onClick="changeLang('.$k.');">';?><?php echo $v;?><?php echo '</a> ';
			if($i > 0 && $i < count($language)){
				echo "| ";
			}
			$i++;
		}
		?>
	</span>
<?php
} 
else {
?>
<div id="footer">
	<div id="copyright" style="float:left;">
		&copy; 2011 Library Ideas, LLC&nbsp;&nbsp;<?php __('All Rights Reserved') ?>
	</div>
</div>
<?php } ?>