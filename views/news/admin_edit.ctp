<?php
/*
 File Name : admin_edit.ctp
 File Description : View page for edit questions page
 Author : m68interactive
 */

$this->pageTitle = 'Content';

	if (isset ($javascript)) {
            echo $javascript->link('tiny_mce/tiny_mce');
	}
	echo $javascript->codeBlock('tinyMCE.init({
		// General options
		mode : "textareas",
		convert_urls : false,
		theme : "advanced",
		plugins : "pagebreak, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, inlinepopups, autosave",
                                    
		// Theme options
		theme_advanced_buttons1 : "save, bold, italic, underline, strikethrough, justifyleft, justifycenter, justifyright, justifyfull, formatselect, fontselect, fontsizeselect",
		theme_advanced_buttons2 : "cut, copy, paste, pastetext, pasteword, search, replace, bullist, numlist, outdent, indent, undo, redo, link, unlink, code, insertdate, inserttime, preview, forecolor, backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
                                    
		// Example content CSS (should be your site CSS)
		//content_css : "css/freegal_admin_styles.css",
                                    
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js"
	});'
	);
?>
<div class="questions form">
<?php echo $this->Form->create('News' , array('type' => 'file') );?>
	<fieldset>
 		<legend><?php printf(__('Edit', true) . ' %s', __('News', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
	?>	
		<div class="input text" >
			<label for="NewsPlace">Language</label>			
			<select id="newsLanguage" name="data[News][language]" onchange="javascript:languagechange();">
				<?php
				
				if($_POST['language']){
					$selected_language = $_POST['language'];				
				} else {
					$selected_language = $news['News']['language'];	
				}
				foreach($languages as $k => $v){
				echo '<option value="'.$k.'" ';
				
				if($selected_language  == $k){
					echo "selected='selected'";
				}
				
				echo '>'.$v.'</option>';
				}
				?>
			</select>
		</div>
	<?php
		echo $this->Form->input('place');
		echo $this->Form->input('subject');
		echo $this->Form->input('body', array('cols' => '80', 'rows' => '20'));
		echo "<div style= 'padding:8px;width:240px;float:left;' id ='newsimagediv'>";
		echo "News image<br/>";
		echo $form->file('image_name', array('label' => false, 'div' => false));
		echo "</div>";
		echo "<div style= 'padding:8px;float:left;clear: none;' >";

		if($news['News']['image_name']){
		?>
		<img style = "height: 150px;" src = "<?php echo $cdnPath. 'news_image/' . $news['News']['image_name']; ?>" alt = '<?php echo $news['News']['subject']; ?>' />
		<?php } ?>
	</fieldset>
	<input type="hidden" id="NewsLanguageChange" value="0" name="data[News][language_change]" />
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Question.id')), null, sprintf(__('Are you sure you want to delete', true) . ' # %s?', $this->Form->value('Question.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf((__('New', true) . ' %s', __('News', true)), array('controller' => 'news', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script language="javascript" type="text/javascript">
var newsLanguage = document.getElementById('newsLanguage');
if(newsLanguage.value != 'en'){
	document.getElementById('newsimagediv').style.display = 'none';
}
else{
	document.getElementById('newsimagediv').style.display = 'block';
}
function languagechange(){
	var formId = document.getElementById('NewsAdminEditForm');
	
	var path = webroot+'admin/news/edit/<?php echo $news['News']['id']?>';
	$('#NewsLanguageChange').val('1');
	formId.setAttribute("action", path);
	formId.submit();
}
</script>