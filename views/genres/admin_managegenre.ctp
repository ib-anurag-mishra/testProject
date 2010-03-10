<?php 
    $this->pageTitle = 'Admin';
    echo $this->Form->create('Genre', array( 'controller' => 'Genre','action' => 'managegenre'));
    foreach ($allGenres as $allGenre):
    if(in_array($allGenre['Genre']['Genre'],$selectedGenres))
    {
        echo $form->checkbox('Genre.['.$allGenre['Genre']['Genre'].']', array('value' => $allGenre['Genre']['Genre'], 'checked' => True)); 
    }
    else
    {
        echo $form->checkbox('Genre.['.$allGenre['Genre']['Genre'].']', array('value' => $allGenre['Genre']['Genre'], 'checked' => False)); 
    }   
    echo $this->Form->label($allGenre['Genre']['Genre']);?><br/>    
    <?php endforeach; ?>
    <p class="submit"><input type="submit" value="Save" /></p>
    <?php echo $this->Form->end();
?>
