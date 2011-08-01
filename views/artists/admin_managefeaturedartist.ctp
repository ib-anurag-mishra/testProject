<?php
/*
 File Name : admin_managefeaturedartist.php
 File Description : View page for manage featured artist
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Content'; ?>
<form>
<fieldset>
<legend>Featured Artists Listing</legend>
  <table id="list">
          <tr>            
            <th class="left">Artist Name</th>
            <th class="left">Territory</th>			
            <th>Artist image</th>
            <th>Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($artists as $artist)
          {
            $artistImage = $artist['Featuredartist']['artist_image'];	   
            ?>
            <tr>
                <td class="left"><?php echo $artist['Featuredartist']['artist_name'];?></td>
                <td class="left"><?php echo $artist['Featuredartist']['territory'];?></td>
				<td><a href="<?php echo $cdnPath.'featuredimg/'.$artist['Featuredartist']['artist_image'];?>" rel="image" onclick="javascript: show_uploaded_images('<?php echo $cdnPath.'featuredimg/'.$artist['Featuredartist']['artist_image'];?>')"><?php echo $artistImage;?></a></td>                
                <td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'artistform','id'=>$artist['Featuredartist']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'delete','id'=>$artist['Featuredartist']['id']));?></td>
            </tr>
            
            <?php
          }
          ?>
        </table>
	<br class="clr" />
	<div class="paging">
	      <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	| 	<?php echo $paginator->numbers();?>
	      <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
	</div>
<?php echo $session->flash();?>
</fieldset>
</form>