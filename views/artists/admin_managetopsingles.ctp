<?php $this->pageTitle = 'Content'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () { 
      $('#album_list_territory').change(function(){
            if($("#album_list_territory").val()) {
                var link = webroot + 'admin/artists/getterritorytopsingles/' + $("#album_list_territory").val();
                jQuery.ajax({
                    type: "post", // Request method: post, get
                    url: link, // URL to request
                    async: false,
                    success: function(newitems) {
                        if (newitems) {
                            $('.manage_album').remove();
                            $('.album_list').append(newitems);
                            loadDragDrop();
                        } else {
                            $('.manage_album').remove();
                            $('.album_list').append('<div class="manage_album"><div class="no_records"><b>No Records available.</b></div></div>');
                            return false;
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        
                    }
                }); 
            } else {
                alert('Please select Another Territory');
                return false;
            }
      });
      loadDragDrop();
      function loadDragDrop(){
        $("#top_albums").sortable({
            items: 'li',
            update: function(event, ui) {
                var result = $('#top_albums').sortable('serialize');
                var data = "topAlbumIds=" + result + "&territory=" +$("#album_list_territory").val();
                if(result) {
                    var link = webroot + 'admin/artists/saveTopsinglesSortOrder/' + $("#album_list_territory").val();
                    jQuery.ajax({
                        type: "post", // Request method: post, get
                        url: link, // URL to request
                        async: false,
                        data: result,
                        success: function(response) {
                            if(response === 'error') {
                                alert('There seems to be some problem in ordering the data.Please try again.')
                                return false;
                            }
                            return true;
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            
                        }
                    });             
                }
            }
        });          
      }
    });
    
</script>      
<div class="album_wrap">
    <legend>Top Singles Listing</legend> 
    <br/>
    <div class="album_territory">
        <div class="album_territory_left">
            <?php echo $form->label('Choose Territory');?>
        </div>
        <div class="album_territory_left">
        <?php
            echo $this->Form->input('territory', array('options' => $territories,'label' => false, 'div' => false,'id' => 'album_list_territory', 'class' => 'select_fields','default' => $default_territory));
        ?>
        </div>
        <div class="album_clear">
        </div> 
    </div>
    <br/><br/>
    <div class="album_list">
        <div class="album_header">
            <div class="song_artist">
                Artist Name   
            </div>
            <div class="song_terr">
                Territory
            </div>
            <div class="song_name">
                Album
            </div>
            <div class="song_id">
                Song
            </div>            
            <?php if($userTypeId !=7) { ?>
            <div class="song_edit">
                Edit
            </div>
            <div class="song_delet">
                Delete
            </div>
            <?php } ?>
        </div>
        <div class="album_clear">
        </div>
        <div class="manage_album" id="top_albums">
          <?php if (!empty($topSingles)) {  ?>
            <ul>
           <?php foreach($topSingles as $topSingle){ ?>   
                <li id="top_album_<?php echo $topSingle['TopSingles']['id']; ?>" value="<?php echo $topSingle['TopSingles']['id']; ?>">
                        <div class="song_header_inner">
                            <div class="song_artist_inner">
                                <?php echo $topSingle['TopSingles']['artist_name'];?>   
                            </div>
                            <div class="song_terr_inner">
                                <?php echo $topSingle['TopSingles']['territory'];?>
                            </div>
                            <div class="song_name_inner">
                                <?php $data = $album->getAlbum($topSingle['TopSingles']['album']);echo $data[0]['Album']['AlbumTitle'];?>
                            </div>
                            <div class="song_id_inner">
                                <?php echo $topSingle['TopSingles']['prod_id'];?>
                            </div>                            
                            <?php if($userTypeId !=7) { ?>
                                <div class="song_edit_inner">
                                    <?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topsingleform','id'=>$topSingle['TopSingles']['id']));?>
                                </div>
                                <div class="song_delet_inner">
                                    <?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topsingledelete','id'=>$topSingle['TopSingles']['id']));?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="album_clear">
                        </div>
                    </li>
           <?php } ?>     
            </ul>
          <?php } else { ?> 
            <div class="no_records">
                <b>No Records available.</b>
            </div>
          <?php } ?>  
        </div>    
    </div>
</div>
