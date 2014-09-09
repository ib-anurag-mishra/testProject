<?php $this->pageTitle = 'Content'; ?>
<div class="album_wrap">
    <legend>Top Albums Listing</legend> 
    <br/>
    <div class="album_territory">
        <div class="album_territory_left">
            <?php echo $form->label('Choose Territory');?>
        </div>
        <div class="album_territory_left">
        <?php
            echo $this->Form->input('territory', array('options' => $territories,'label' => false, 'div' => false, 'class' => 'select_fields','default' => $default_territory));
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
        </div>    
    </div>
</div>
