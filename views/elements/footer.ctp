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
	<?php echo $html->link(__('About').' Freegal Music', array('controller' => 'homes', 'action' => 'aboutus')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link(__('Terms & Conditions', true), array('controller' => 'homes', 'action' => 'terms')); ?>
	&nbsp;|&nbsp;
	<?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?>
	<div id="language" align="center" style="font-size:12px;">
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
	</div>
</div>
<?php
}
?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-16162084-1");
pageTracker._trackPageview();
} catch(err) {}</script>