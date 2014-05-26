<?php
/*
 File Name : admin_createartist.php
File Description : View page for create artist
Author : m68interactive
*/
$this->pageTitle = 'Content';
echo $form->create('Artist', array( 'controller' => 'Artist','action' => $formAction,'enctype' => 'multipart/form-data'));
if(empty($getData))
{
	$getData['Artist']['artist_name'] = "";
	$getData['Artist']['id'] = "";
	$getData['Artist']['territory'] = "";
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
		<?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Artist']['id'])); ?>
		<div class="form_steps">
			<table cellspacing="10" cellpadding="0" border="0" width="100%">
				<tr>
					<td align="right" width="390"><?php echo $form->label('Choose Territory');?>
					</td>
					<td align="left"><?php
					echo $this->Form->input('territory', array('options' => $territories,'label' => false, 'div' => false, 'class' => 'select_fields','default' => $getData['Artist']['territory'])
					);
					?>
					</td>
				</tr>
				<tr>
					<td align="right" width="390"><?php echo $form->label('Artist Name');?>
					</td>
					<td align="left"><div id="getArtist">
							<?php 

							echo $this->Form->input('artist_name', array('label' => false, 'div' => false, 'class' => 'select_fields', 'value' => $getData['Artist']['artist_name'], 'autocomplete' => 'off'));

							?>
							<div id="AutoArtistResult-DIV"></div>
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
<?php echo $form->end(); ?>
<?php echo $session->flash();?>
<link
	type="text/css" rel="stylesheet"
	href="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/css&amp;f=flick/jquery-ui-1.8.custom.css" />
<script
	type="text/javascript"
	src="<? echo $this->webroot; ?>app/webroot/min/b=app/webroot/js&amp;f=datepicker/jquery.ui.core.js,datepicker/jquery.ui.widget.js,datepicker/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
    
      $('#ArtistTerritory').change(function(){
        $('#ArtistArtistName').val('');
      });
      
      $("#ArtistArtistName").keyup(function(event) {
      
        if(isArrowKey(event)){
          
          $('#AutoArtistResult-DIV').hide();
          $('#AutoArtistResult-DIV').empty();
      
          if( 0 != $('#ArtistArtistName').val().length ) {
            
            var data = "Name="+$("#ArtistArtistName").val()+"&Territory="+$("#ArtistTerritory").val();
            
            jQuery.ajax({
              type: "post",  // Request method: post, get
              url: webroot+"admin/artists/getAutoArtist", // URL to request
              data: data,  // post data
              success: function(response) {
                  
                if(0 !== response.length){
                  
                  $('#AutoArtistResult-DIV').html(response);
                  $('#AutoArtistResult-DIV').slideDown('slow');
                  $("#AutoArtistResult-DIV ul li:odd").css('background-color', 'silver');
                  $("#AutoArtistResult-DIV ul li").hover(function(){
                    $(this).css('font-weight', 'bold');
                  });
                  $("#AutoArtistResult-DIV ul li").mouseout(function(){
                    $(this).css('font-weight', 'normal');
                  });
                  $("#AutoArtistResult-DIV ul li").click(function(){
                    $('#ArtistArtistName').val($(this).text());
                    $('#AutoArtistResult-DIV').hide();
                    $('#AutoArtistResult-DIV').empty();
                  });
                }
              },
              error:function (XMLHttpRequest, textStatus, errorThrown) {}
            }); 
            return false;
          }
        }          
      });
          
    });
    
    
    function isArrowKey(event){
      if( (event.keyCode != 37) && (event.keyCode != 38) && (event.keyCode != 39) && (event.keyCode != 40)) {
        return true;
      } else {
        return false;
      }
    }
  
</script>

<style type="text/css">
#AutoArtistResult-DIV >ul > li {
	cursor: pointer;
	padding-left: 5px;
}

#AutoArtistResult-DIV {
	background: none repeat scroll 0 0 #FFFFFF;
	border: 1px solid #000000;
	display: block;
	font: 80% Verdana, Arial, Helvetica, sans-serif;
	margin-left: 20px;
	position: absolute;
	width: 210px;
	color: #000000;
	display: none;
}
</style>
