<?php $this->pageTitle = 'Content'; ?>
<div class="album_wrap">
    <legend>Top Albums Listing</legend>    
    <div class="album_territory">
        <div class="album_territory_left">
            <?php echo $form->label('Choose Territory');?>
        </div>
        <div class="album_territory_left">
        <?php
            echo $this->Form->input('territory', array('options' => $territories,'label' => false, 'div' => false, 'class' => 'select_fields','default' => $getData['Artist']['territory']));
        ?>
        </div>
        <div class="album_clear">
        </div> 
    </div>
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


<form>
	<fieldset>
		<legend>Top Albums Listing</legend>
		<table id="list">
			<tr>
				<th class="left">Artist Name</th>
				<th class="left">Territory</th>
				<th class="left">Album</th>
				<?php if($userTypeId !=7) { ?>
				<th>Edit</th>
				<th>Delete</th>
				<?php } ?>
			</tr>
			<?php
			foreach($topAlbums as $topAlbum)
			{
				?>
			<tr>
				<td class="left"><?php echo $topAlbum['TopAlbum']['artist_name'];?>
				</td>
				<td class="left"><?php echo $topAlbum['TopAlbum']['territory'];?>
				</td>
				<td class="left"><?php $data = $album->getAlbum($topAlbum['TopAlbum']['album']);echo $data[0]['Album']['AlbumTitle'];?>
				</td>
				<?php if($userTypeId !=7) { ?>
				<td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'topalbumform','id'=>$topAlbum['TopAlbum']['id']));?>
				</td>
				<td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'topalbumdelete','id'=>$topAlbum['TopAlbum']['id']));?>
				</td>
				<?php } ?>
			</tr>

			<?php
			}
			?>
		</table>
		<br class="clr" />
		<div class="paging">
			<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
			|
			<?php echo $paginator->numbers();?>
			<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
		</div>
		<?php echo $session->flash();?>
	</fieldset>
</form>
