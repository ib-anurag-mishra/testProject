<?php 
    $this->pageTitle = 'Content';
    echo $this->Form->create('Genre', array( 'controller' => 'Genre','action' => 'managegenre'));
    $i=0;
    echo "<table>";
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
    echo "</table>";?>
    <p class="submit"><input type="submit" value="Save" /></p>
    <?php echo $this->Form->end();
?>
