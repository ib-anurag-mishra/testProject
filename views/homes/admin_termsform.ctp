<?php
	$this->pageTitle = 'Content'; 
	echo $this->Form->create('Home', array( 'action' => $formAction ));
	if(empty($getData))
	{
	        $getData['Home']['id'] = "";
	        $getData['Home']['page_name'] = "terms";
	        $getData['Home']['page_content'] = "";
	}
?>
<?php
	if (isset ($javascript)) {
            echo $javascript->link('tiny_mce/tiny_mce');
	}
?>
<fieldset>
	<legend><?php echo $formHeader;?></legend>
	<div class="formFieldsContainer">
            <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Home']['id'])); ?>
            <?php echo $this->Form->hidden( 'page_name', array( 'label' => false ,'value' => $getData['Home']['page_name'])); ?>
            <div class="form_steps">
                   <table cellspacing="10" cellpadding="0" border="0" width="100%">
                          <tr>
                                 <td align="right" valign="top"><?php echo $this->Form->label('Page Content:');?></td>
                                 <td align="left">
                                   <?php
                                       echo $this->Form->textarea('page_content', array('cols' => '80', 'rows' => '15', 'value' => $getData['Home']['page_content']));
                                       echo $javascript->codeBlock('tinyMCE.init({
                                                                                    // General options
                                                                                    mode : "exact",
                                                                                    elements : "HomePageContent",
                                                                                    theme : "advanced",
                                                                                    skin : "o2k7",
                                                                                    skin_variant : "black",
                                                                                    plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
                                                                    
                                                                                    // Theme options
                                                                                    //theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                                                                                    //theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                                                                                    //theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                                                                                    //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
                                                                                    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
                                                                                    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                                                                                    theme_advanced_buttons3 : "",
                                                                                    //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
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
                                                                                    media_external_list_url : "lists/media_list.js",
                                                                    
                                                                                    // Replace values for the template plugin
                                                                                    template_replace_values : {
                                                                                            username : "Some User",
                                                                                            staffid : "991234"
                                                                                    }
                                                                                });');
                                   ?>
                                 </td>
                          </tr>
			  <tr>
				<td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
			 </tr>
                   </table>
            </div>
        </div>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash(); ?>