<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Artist Listing</legend>
  <table id="list">
          <tr>            
            <th class="left">Artist Name</th>
            <th>Artist image</th>
            <th>Edit</th>
	    <th>Delete</th>
          </tr>
          <?php
          foreach($artists as $artist)
          {   
	    $imgArr = explode('/',$artist['Artist']['artist_image']);
            $artistImage = $imgArr[2];	   
            ?>
            <tr>
                <td class="left"><?php echo $artist['Artist']['artist_name'];?></td>
                <td><a href="../../<?php echo $artist['Artist']['artist_image'];?>" rel="image" onclick="javascript: show_uploaded_images('../../<?php echo $artist['Artist']['artist_image'];?>')"><?php echo $artistImage;?></a></td>                
                <td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'createartist','id'=>$artist['Artist']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'deleteartists','id'=>$artist['Artist']['id']));?></td>
            </tr>
            
            <?php
          }
          ?>
        </table>
</fieldset>
<?php echo $session->flash();?>
</form>

