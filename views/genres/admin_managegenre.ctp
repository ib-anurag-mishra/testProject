<?php 
/*
	 File Name : admin_managegenre.ctp
	 File Description : View page for managing genre
	 Author : m68interactive
 */
    $this->pageTitle = 'Content';
    echo $this->Form->create('Genre', array( 'controller' => 'Genre','action' => 'managegenre'));
    $i=0;
?>
<fieldset>
    <legend>Manage Favorite Genres</legend>
    <div class="formFieldsContainer">
        <div class="form_steps">
            <table cellspacing="10" cellpadding="0" border="0" width="100%">
            <?php
                foreach ($allGenres as $allGenre):
                    if($i%3 == 0)
                    {
                        echo "<tr><td>";        
                    }
                    else
                    {            
                        echo "<td>";
                    }
                    if(in_array($allGenre['Genre']['Genre'],$selectedGenres))
                    {
                        echo $form->checkbox('Genre.['.$allGenre['Genre']['Genre'].']', array('value' => $allGenre['Genre']['Genre'], 'checked' => True)); 
                    }
                    else
                    {
                        echo $form->checkbox('Genre.['.$allGenre['Genre']['Genre'].']', array('value' => $allGenre['Genre']['Genre'], 'checked' => False)); 
                    }   
                    echo $this->Form->label($allGenre['Genre']['Genre']);
                    if(($i+1)%3 == 0)
                    {            
                        echo "</td></tr>";        
                    }
                    else
                    {            
                        echo "</td>";
                    }
                    $i++;
                endforeach;
            ?>
                <tr>
					<?php if($userTypeId !=7) { ?>
                    <td align="center" colspan="3"><p class="submit"><input type="submit" value="Save" /></p></td>
					 <?php } ?>
                </tr>
            </table>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $session->flash();?>
