<?php
/*
 File Name : admin_addnewartist.php
File Description : View page for adding new artist
Author : m68interactive
*/
$this->pageTitle = 'Content';
echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));
if(empty($getData))
{
	$getData['Newartist']['artist_name'] = "";
	$getData['Newartist']['id'] = "";
	$getData['Newartist']['territory'] = "";
}
if(empty($getArtistData)){
	$getArtistData = array();
}
?>
<fieldset>
	<legend>
		<?php echo $formHeader;?>
	</legend>
	<div class="formFieldsContainer">
		<?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Newartist']['id'])); ?>
		<div class="form_steps">
			<table cellspacing="10" cellpadding="0" border="0" width="100%">
				<tr>
					<td align="right" width="390"><?php echo $form->label('Choose Territory');?>
					</td>
					<td align="left"><?php
					echo $this->Form->input('territory', array('options' => array(
							'' => 'Choose Territory',
							'US' => 'US',
							'CA' => 'CA','IT' => 'IT','AU' => 'AU','NZ' => 'NZ','GB' => 'GB','IE' => 'IE'),'label' => false, 'div' => false, 'class' => 'select_fields','default' => $getData['Newartist']['territory'])
					);
					?>
					</td>
				</tr>
				<tr>
					<td align="right" width="390"><?php echo $form->label('Artist Name');?>
					</td>
					<td align="left"><div id="getArtist">
							<?php echo $form->select('artist_name', $getArtistData, $getData['Newartist']['artist_name'], array('label' => false, 'div' => false, 'class' => 'select_fields')); ?>
						</div></td>
				</tr>
				<tr>
					<td align="right" width="390"><?php echo $form->label('Artist Photo');?>
					</td>
					<td align="left"><?php echo $form->file('artist_image', array('label' => false, 'div' => false, 'class' => 'form_fields')); ?>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><p class="submit">
							<input type="submit" value="Save" />
						</p></td>
				</tr>
			</table>
		</div>
	</div>
</fieldset>
<?php
echo $form->end();
echo $session->flash();
?>
<link
	type="text/css" rel="stylesheet"
	href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
<script
	type="text/javascript"
	src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $("#ArtistTerritory").change(function() {
			var data = "Territory="+$("#ArtistTerritory").val();
			jQuery.ajax({
				type: "post",  // Request method: post, get
				url: webroot+"admin/artists/getArtists", // URL to request
				data: data,  // post data
				success: function(response) {
						$('#getArtist').text('');
						$('#getArtist').html(response);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {}
			});
			return false;
		});
		
    });
</script>
