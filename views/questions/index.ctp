<?php
/*
  File Name : index.ctp
  File Description : View page for index
  Author : m68interactive
 */
?>
<section class="faq">
    <div class="breadcrumbs">
        <?php
        $html->addCrumb('FAQ', '/questions');
        echo $html->getCrumbs(' > ', __('Home', true), '/homes');
        ?>
    </div>
    <header>
        <h2><?php __('FAQs'); ?></h2>
    </header>
    <div class="faq-container">
        
            <?php
            $Title = "";
            foreach ($questions as $question):

                $questiontitleText = $this->getTextEncode($question['Section']['title']);
                $questionansText = $this->getTextEncode($question['Question']['answer']);
                if(!$questionansText){
                    $questionansText =$question['Question']['answer'];
                }               
                $questionquText = $this->getTextEncode($question['Question']['question']);

                if ($Title != $question['Section']['title'])
                               {?>
                                       
                                       
                               <?
                                       if($Title=='')   
                                       {
                                           // echo '<h3>'.$questiontitleText.'</h3><ul>';
                                            echo '<h3>'.$questiontitleText.'</h3>';
                                       }
                                       else
                                       {
                                           // echo '</ul><h3>'.$questiontitleText.'</h3><ul>';
                                            echo '<h3>'.$questiontitleText.'</h3>';
                                       }
                               }
                               ?>			

                             

                <?php/*
                <li class="fq"><?php echo strip_tags($questionquText); ?></li>
                
                <li class="fa"><?php echo strip_tags($questionansText); ?></li>
                */
                ?>

                <div class="fq"><?php echo strip_tags($questionquText); ?></div>
                
                <div class="fa"><?php echo strip_tags($questionansText); ?></div>                                

                <?php $Title = $question['Section']['title']; ?>
            <?php endforeach; ?>
        
    </div>
</section>