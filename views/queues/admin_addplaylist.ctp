<?php
$this->pageTitle = 'Content';
echo $this->Form->create('artist', array('type' => 'post','name' => 'artistAdminDefaultQueueForm','url' => array('controller' => 'artists', 'action' => $formAction)));
if(empty($getData))
{
	$getData['TopSingles']['artist_name'] = "";
	$getData['TopSingles']['id'] = "";
	$getData['TopSingles']['territory'] = "";
	$getData['TopSingles']['language'] = "";
	$getData['TopSingles']['album'] = "";
	$getData['TopSingles']['prod_id'] = "";
}
if(empty($getArtistData)){
	$getArtistData = array();
}
if(empty($album)){
	$album = array();
}

if(empty($songs)){
    $songs  = "";
}
?>
<fieldset>
	<legend>
		<?php echo $formHeader;?>
	</legend>
	<div class="formFieldsContainer">
		<?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['TopSingles']['id'])); ?>
		<div class="form_steps">
			<table cellspacing="10" cellpadding="0" border="0" width="100%">
				<tr>
					<td align="right" width="390"><?php echo $form->label('Playlist Name');?>
					</td>
					<td align="left">
                                            <?php echo $this->Form->input('queue_name', array('label' => false, 'div' => false, 'class' => 'select_fields','value' => $getData['TopSingles']['artist_name']));?>
					</td>
				</tr>                            
				<tr>
					<td align="right" width="390"><?php echo $form->label('Choose Territory');?>
					</td>
					<td align="left"><?php
					echo $this->Form->input('territory', array('options' => $territories,'label' => false, 'div' => false, 'class' => 'select_fields','default' => $getData['TopSingles']['territory'])
					);
					?>
					</td>
				</tr>
				<tr>
					<td align="right" width="390"><?php echo $form->label('Artist Name');?>
					</td>
					<td align="left">
						<div id="getArtist">
							<?php
							echo $this->Form->input('artist_name', array('label' => false, 'div' => false, 'class' => 'select_fields', 'value' => $getData['TopSingles']['artist_name'], 'autocomplete' => 'off'));
							?>
							<div id="AutoArtistResult-DIV"></div>
						</div>
					</td>
				</tr>

				<tr>
					<td align="right" width="390"><?php echo $form->label('Choose Album');?>
					</td>
					<td align="left">
						<div id="getAlbum">
							<?php
							echo $form->select('album', $album, $getData['TopSingles']['album'], array('label' => false, 'div' => false, 'class' => 'select_fields'));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" width="390"><?php echo $form->label('Choose Song');?>
					</td>
					<td align="left">
						<div id="getSongs">
							<?php
							echo $form->select('song', $songs, $getData['TopSingles']['songs'], array('label' => false, 'div' => false, 'class' => 'select_fields'));
							?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
        <table id="list">
            <tbody class="default_songs">
                <tr>
                        <th class="left">Artist Name</th>
                        <th class="left">Territory</th>
                        <th>Album</th>
                        <th>Song</th>
                        <th><input type="checkbox" name="maincheckbox" id="maincheckbox"
                                value="1" onClick="CheckAllChk(form,this);">Delete</th>
                </tr>

                <?php if(count($artists)) { ?>
                <?php                
                foreach($artists as $artist)
                {
                        $artistImage = $artist['Artist']['artist_image'];
                        ?>
                <tr class="songs_list">
                        <td class="left"><?php echo $artist['Artist']['artist_name'];?></td>
                        <td class="left"><?php echo $artist['Artist']['territory'];?></td>
                        <td><a
                                href="<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>"
                                rel="image"
                                onclick="javascript: show_uploaded_images('<?php echo $cdnPath.'artistimg/'.$artist['Artist']['artist_image'];?>')"><?php echo $artistImage;?>
                        </a></td>
                        <td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'createartist','id'=>$artist['Artist']['id']));?>
                        </td>
                        <td><?php echo $this->Form->input("Info. ", array('type'=>'checkbox','id'=>$artist['Artist']['id'], 'value' => $artist['Artist']['id'], 'hiddenField' => false)); ?>
                        </td>
                </tr>
                <?php
                }
                ?>
                <?php if(count($artists)) { ?> 
                <td class="left remove_options" colspan="5">
                    <span style="float: right;">
                        <table>
                                <tr>
                                        <td><?php echo $this->Form->button('Remove Selected', array('name' => 'remove_selected','label'=>'Remove Selected','onclick' => 'return m_delete(1)')); ?>
                                        </td>
                                        <td><?php echo $this->Form->button('Remove All', array('name' => 'remove_all','label'=>'Remove All','onclick' => 'return m_delete(2)')); ?>
                                        </td>
                                </tr>

                        </table>
                    </span> 
                </td>    
                    <?php } ?>                
                <?php }else{ ?>
                <tr class="no_records">
                        <td colspan="5" align="center">No Records available.</td>
                </tr>
                <?php } ?>
                <?php echo $this->Form->hidden('selectedOpt'); ?>
           </tbody>     
        </table>        
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
    
      $('#ArtistTerritory').change(function(){
 
        $('#ArtistArtistName').val('');
		$('#getSongs').text('');
        $('#getAlbum select.select_fields option').remove();
      });
		
	  

      $("#ArtistArtistName").keyup(function(event) {
        
        if(isArrowKey(event)){
        
          $('#AutoArtistResult-DIV').hide();
          $('#AutoArtistResult-DIV').empty();

          if( 0 != $('#ArtistArtistName').val().length ) {
          
            var data = "Name="+$("#ArtistArtistName").val()+"&Territory="+$("#ArtistTerritory").val();
            jQuery.ajax({
              type: "post",  // Request method: post, get
              url: webroot+"admin/artists/getPlaylistAutoArtist", // URL to request
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
                    getAlbum();
                  });
				  $('#ArtistAlbum').change(function(){
					$('#getSongs').val('');
					$('#getSongs select.select_fields option').remove();
        			getSongs();
	  			  });
                }
              },
              error:function (XMLHttpRequest, textStatus, errorThrown) {}
            });
            return false;
          } 
        
        }
          
      });

		

    
	function getAlbum(){		
                var artistNameText = escape($("#ArtistArtistName").val());
		var data = "Territory="+$("#ArtistTerritory").val()+"&artist="+artistNameText;
               
               
		jQuery.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"admin/artists/getAlbumsForDefaultQueues", // URL to request
			data: data,  // post data
			success: function(response) {
					$('#getAlbum').text('');
					$('#getAlbum').html(response);
                                        onAlbumUpdate();
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
		return false;
	}	
  
	function getSongs(){		
        var artistNameText = escape($("#ArtistArtistName").val());
		var albumProdId = escape($('#ArtistAlbum').val());
		var data = "Territory="+$("#ArtistTerritory").val()+"&artist="+artistNameText+"&albumProdId="+albumProdId;
                    
		jQuery.ajax({
			type: "post",  // Request method: post, get
			url: webroot+"admin/artists/getAlbumStreamSongs", // URL to request
			data: data,  // post data
			success: function(response) {
					$('#getSongs').text('');
					$('#getSongs').html(response);
                                        populateList();
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {}
		});
		return false;
	}


  function isArrowKey(event){
    if( (event.keyCode != 37) && (event.keyCode != 38) && (event.keyCode != 39) && (event.keyCode != 40)) {
      return true;
    } else {
      return false;
    }
  }
  
    function onAlbumUpdate()
    {
        $('#ArtistAlbum').change(function() {

            $('#getSongs').val('');
            $('#getSongs select.select_fields option').remove();
            getSongs();
        });
    }
    
    function populateList() {  
        $('#ArtistSong').change(function() {
            var artistNameText = $("#ArtistArtistName").val();
            var songProdId = escape($('#ArtistSong').val());
            var songName = $("#ArtistSong option:selected").text();
            var albumName  = $("#ArtistAlbum option:selected").text(); 
            var albumData = escape($('#ArtistAlbum').val());
            var albumProdId = albumData.split("-")[0];
            var providerType = albumData.split("-")[1];
            if(songProdId) {
                $('.no_records').remove();
                var checkremove = $('.remove_options').length;
                if(!checkremove) {
                    $('.default_songs').append('<tr class="songs_list"><td class="left">'+artistNameText+'</td><td class="left">'+$('#ArtistTerritory').val()+'</td><td>'+albumName+'</td><td>'+songName+'</td><td><input type="checkbox" value="'+albumData+'-'+songProdId+'" name="data[Info][]"></td></tr>');
                } else {
                    $('.remove_options').before('<tr class="songs_list"><td class="left">'+artistNameText+'</td><td class="left">'+$('#ArtistTerritory').val()+'</td><td>'+albumName+'</td><td>'+songName+'</td><td><input type="checkbox" value="'+albumData+'-'+songProdId+'" name="data[Info][]"></td></tr>');
                }
                if(!checkremove) {
                    $('.default_songs').append('<td colspan="5" class="left remove_options"><span style="float: right;"><table><tbody><tr><td><button onclick="return m_delete(1)" label="Remove Selected" name="remove_selected" type="submit">Remove Selected</button></td><td><button onclick="return m_delete(2)" label="Remove All" name="remove_all" type="submit">Remove All</button></td></tr></tbody></table></span></td>'); 
                }
            }
        });        
    }
    
    function m_delete(flagVar) { // Delete the contact

            var k2=0;
            if(flagVar == 1){

                for (var i=0;i<document.artistAdminDefaultQueueForm.elements.length;i++)
                {
                        var e1 = document.artistAdminDefaultQueueForm.elements[i];

                        if((e1.type=="checkbox")&&(e1.name=='data[Info][ ]'))
                        {
                                if(e1.checked==true)
                                        {
                                                k2++;
                                        }
                        }
                }

            }else{

                k2 = 1;
            }	

            if(k2==0)
            {
                    alert('Please select at least one recode for remove.');
                    return false;
            }
            else
            {
                    if(flagVar == 1){
                        var x=confirm('Are you sure you want to remove all selected records ?');
                    }else if(flagVar == 2){
                        var x=confirm('Are you sure you want to remove all records ?');
                    }

                    if(x==false)
                    {
                            return false;
                    }
                    else(x==true)
                    {

                            document.getElementById('artistSelectedOpt').value = flagVar;                        
                            return true;

                    }
            }
    }


    function CheckAllChk(theForm,maincheckname)
    {
            for(var z=0; z<theForm.length;z++)
            {
                    if(theForm[z].type =='checkbox')
                    {
                            if(maincheckname.checked == true)
                            {
                                    theForm[z].checked=true;
                            }		   
                            else
                            {
                                    theForm[z].checked=false;
                            }
                    }
            }	
    }    


</script>
<style type="text/css">
#AutoArtistResult-DIV > ul > li {
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
