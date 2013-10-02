<?php
/*
 File Name : admin_add.ctp
 File Description : View page for questions add page
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Content'; ?>
<?php
	if (isset ($javascript)) {
            echo $javascript->link('tiny_mce/tiny_mce');
	}
	echo $javascript->codeBlock('tinyMCE.init({
		// General options
		mode : "textareas",
		convert_urls : false,
		theme : "advanced",
		plugins : "pagebreak, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, inlinepopups, autosave",
		nowrap: true,
                                    
		// Theme options
		theme_advanced_buttons1 : "save, bold, italic, underline, strikethrough, justifyleft, justifycenter, justifyright, justifyfull, formatselect, fontselect, fontsizeselect",
		theme_advanced_buttons2 : "cut, copy, paste, pastetext, pasteword, search, replace, bullist, numlist, outdent, indent, undo, redo, link, unlink, code, insertdate, inserttime, preview, forecolor, backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		theme_advanced_source_editor_wrap : false,
                                    
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
<?php echo $this->Form->create('News' ,  array('type' => 'file'));?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('News', true)); ?></legend>
	<?php
		echo $this->Form->input('place');
		echo $this->Form->input('subject');
		echo $this->Form->input('body', array('cols' => '80', 'rows' => '20'));
		echo "News image<br/>";
		echo $form->file('image_name', array('label' => false, 'div' => false, 'class' => 'form_fields'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Question.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Question.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('News', true)), array('action' => 'index'));?></li>
	</ul>
</div>