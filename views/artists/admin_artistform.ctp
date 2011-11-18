<?php
 $this->pageTitle = 'Content'; 
 echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));       	 	
 if(empty($getData))
 {
	$getData['Featuredartist']['artist_name'] = ""; 
	$getData['Featuredartist']['id'] = "";
	$getData['Featuredartist']['territory'] = "";
	$getData['Featuredartist']['language'] = "";
	$getData['Featuredartist']['album'] = "";
 }
 if(empty($getArtistData)){
	$getArtistData = array();
 }
 if(empty($album)){
	$album = array();
 }
?>
<fieldset>
 <legend><?php echo $formHeader;?></legend>
 <div class="formFieldsContainer">
  <?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Featuredartist']['id'])); ?>
  <div class="form_steps">
   <table cellspacing="10" cellpadding="0" border="0" width="100%">
		<tr>
			<td align="right" width="390"><?php echo $form->label('Choose Territory');?></td>
			<td align="left">
				<?php
					echo $this->Form->input('territory', array('options' => array(
						'' => 'Choose Territory',
						'US' => 'US',
						'CA' => 'CA'),'label' => false, 'div' => false, 'class' => 'select_fields','default' => $getData['Featuredartist']['territory'])
					);
				?>
			</td>
		</tr>
		<tr>
		     <td align="right" width="390"><?php echo $form->label('Artist Name');?></td>
		     <td align="left"><div id="getArtist"><?php echo $form->select('artist_name', $getArtistData, $getData['Featuredartist']['artist_name'], array('label' => false, 'id' => 'artistName', 'div' => false, 'class' => 'select_fields', 'onchange' => 'getAlbum();')); ?></div></td>
	        </tr>
		
		<tr>
			<td align="right" width="390"><?php echo $form->label('Choose Album');?></td>
			<td align="left">
				<div id="getAlbum">
					<?php
						echo $form->select('album', $album, $getData['Featuredartist']['album'], array('label' => false, 'div' => false, 'class' => 'select_fields'));
					?>
				</div>
			</td>
		</tr>		
        <tr>
                 <td align="center" colspan="2"><p class="submit"><input type="submit" value="Save" /></p></td>
          </tr>
   </table>
   </div>
  </div>
</fieldset> 
<?php
 echo $form->end();
 echo $session->flash();
?>
<link type="text/css" rel="stylesheet" href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
<script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
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
	function getAlbum(){		
		var data = "Territory="+$("#ArtistTerritory").val()+"&artist="+$("#artistName").val();
		jQuery.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"admin/artists/getAlbums", // URL to request
			data: data,  // post data
			success: function(response) {
					$('#getAlbum').text('');
					$('#getAlbum').html(response);
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
		return false;
	}	
</script>