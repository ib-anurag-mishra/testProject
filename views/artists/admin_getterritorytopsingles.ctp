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