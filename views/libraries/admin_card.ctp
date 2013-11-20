<?php
/*
 File Name : admin_card.ctp
 File Description : View page for library form
 Author : m68interactive
 */
?>
<?php
echo $session->flash();
$this->pageTitle = 'Libraries'; 
echo $this->Form->create('Libraries', array('type' => 'file')); 
?>
<fieldset>
<legend> mdlogin/mndlogin Method </legend>
<br/>
<table width="70%" style="padding-left:100px;">
<tr><td>Login Method</td><td>Library</td><td>Choose excel file</td></tr>
<tr><td>
<?php
echo $this->Form->input('Login Method', array('options' => array(
									'' => 'Select login method',
									'mdlogin'=>'mdlogin',
									'mndlogin'=>'mndlogin'
									),'label' => false));
?>
</td>
<td>
<div id="allLibrary">
<?php
if(!isset($libs))
 $libs = array();
$libs = array('' => 'Select Library') + $libs;;

echo $this->Form->input('Library', array('options' => $libs , 'label' => false)); 
									
?>
</div>
</td>
<td>
<input type="file" name="xls_sheet" />
</td>
</tr>
<tr><td colspan = '3' align = 'center'>&nbsp;&nbsp;</td></tr>
<tr width = '70%' ><td colspan = '3' align = 'center'><?php echo $this->Form->submit(); ?></td></tr>
</table>

<?php
echo $this->Form->end(); 

?>
</fieldset>
<script type="text/javascript">
    $(function() {
        $("#LibrariesLoginMethod").change(function() {
			var data = "method=" + $("#LibrariesLoginMethod").val();
			jQuery.ajax({
				type: "post",  // Request method: post, get
				url: webroot+"admin/libraries/get_libraries", // URL to request
				data: data,  // post data
				success: function(response) {
						$('#allLibrary').text('');
						$('#allLibrary').html(response);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {}
			});
			return false;
		});
		
    });

</script>