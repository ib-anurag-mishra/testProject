<div class="manage_album" id="top_albums">
  <?php if (!empty($topAlbums)) { ?>
    <ul>
   <?php foreach($topAlbums as $topAlbum){ ?>   
            <li id="top_album_<?php echo $topAlbum['TopAlbum']['id']; ?>" value="<?php echo $topAlbum['TopAlbum']['id']; ?>">
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