<?php $this->pageTitle = 'Content'; ?>
<form>
<fieldset>
<legend>Featured Artists Listing</legend>
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
	    $imgArr = explode('/',$artist['Featuredartist']['artist_image']);
            $artistImage = $imgArr[2];	   
            ?>
            <tr>
                <td class="left"><?php echo $artist['Featuredartist']['artist_name'];?></td>
                <td><a href="../../<?php echo $artist['Featuredartist']['artist_image'];?>" rel="image" onclick="javascript: show_uploaded_images('../../<?php echo $artist['Featuredartist']['artist_image'];?>')"><?php echo $artistImage;?></a></td>                
                <td><?php echo $html->link('Edit', array('controller'=>'artists','action'=>'artistform','id'=>$artist['Featuredartist']['id']));?></td>
                <td><?php echo $html->link('Delete', array('controller'=>'artists','action'=>'delete','id'=>$artist['Featuredartist']['id']));?></td>
            </tr>
            
            <?php
          }
          ?>
        </table>
<?php echo $session->flash();?>
</fieldset>
</form>