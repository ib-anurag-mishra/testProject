<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>New Listing</legend>
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
	    $imgArr = explode('/',$artist['Newartist']['artist_image']);
            $artistImage = $imgArr[2];	   
            ?>
            <tr>
                <td class="left"><?php echo $artist['Newartist']['artist_name'];?></td>
                <td><a href="../<?php echo $artist['Newartist']['artist_image'];?>" rel="image" onclick="javascript: show_uploaded_images('../<?php echo $artist['Newartist']['artist_image'];?>')"><?php echo $artistImage;?></a></td>                
                <td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'addnewartist','id'=>$artist['Newartist']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'deletenewartists','id'=>$artist['Newartist']['id']));?></td>
            </tr>
            
            <?php
          }
          ?>
        </table>
  
<?php echo $session->flash();?>
  
</fieldset>
</form>

