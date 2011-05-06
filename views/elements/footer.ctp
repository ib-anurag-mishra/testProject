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
	<?php echo $html->link(__('Terms & Conditions', true), array('controller' => 'homes', 'action' => 'terms')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?>
	<div id="language">
	<?php __('Also available in'); ?>&nbsp;
	<?php
	$language = $language->getLanguage();
	$i =1;
	foreach($language as $k => $v){
			echo '<a href="javascript:void(0)" id='.$k.' onClick="changeLang('.$k.');">';?><?php __($v);?><?php echo '</a> ';
			if($i == (count($language)-1)){
				echo "| ";
			}
			$i++;
	}
	?>
	</div>
</div>
<?php
}