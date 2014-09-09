<?php $this->pageTitle = 'Content'; ?>
<script type="text/javascript">
    $(document).ready(function () { 
      $('#album_list_territory').change(function(){
            if($("#album_list_territory").val()) {
                var link = webroot + 'admin/artists/getterritorytopalbums/' + $("#album_list_territory").val();
                jQuery.ajax({
                    type: "post", // Request method: post, get
                    url: link, // URL to request
                    data: data, // post data
                    success: function(newitems) {
                        if (newitems) {
                            $('.manage_album').remove();
                            $('.album_list').append(newitems);
                        } else {
                            $('.manage_album').remove();
                            $('.album_list').append('<div class="manage_album"><div class="no_records"><b>No Records available.</b></div></div>');
                            return;
                        }
                    },
                    async: true,
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    }
                }); 
            } else {
                alert('Please select Another Territory');
                return false;
            }
      });
    });
    
</script>      
<div class="album_wrap">
    <legend>Top Albums Listing</legend> 
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
            <div class="album_artist">
                Artist Name   
            </div>
            <div class="album_terr">
                Territory
            </div>
            <div class="album_name">
                Album
            </div>
            <?php if($userTypeId !=7) { ?>
            <div class="album_edit">
                Edit
            </div>
            <div class="album_delet">
                Delete
            </div>
            <?php } ?>
        </div>
        <div class="album_clear">
        </div>
        <div class="manage_album">
          <?php if (!empty($topAlbums)) { ?>
            <ul>
           <?php foreach($topAlbums as $topAlbum){ ?>   
                    <li>
                        <div class="album_header_inner">
                            <div class="album_artist_inner">
                                <?php echo $topAlbum['TopAlbum']['artist_name'];?>   
                            </div>
                            <div class="album_terr_inner">
                                <?php echo $topAlbum['TopAlbum']['territory'];?>
                            </div>
                            <div class="album_name_inner">
                                <?php $data = $album->getAlbum($topAlbum['TopAlbum']['album']);echo $data[0]['Album']['AlbumTitle'];?>
                            </div>
                            <?php if($userTypeId !=7) { ?>
                                <div class="album_edit_inner">
                                    <?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topalbumform','id'=>$topAlbum['TopAlbum']['id']));?>
                                </div>
                                <div class="album_delet_inner">
                                    <?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topalbumdelete','id'=>$topAlbum['TopAlbum']['id']));?>
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
