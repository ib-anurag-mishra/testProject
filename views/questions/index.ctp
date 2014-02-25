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
        <ul>
            <?php
            $Title = "";
            foreach ($questions as $question):

                $questiontitleText = $this->getTextEncode($question['Section']['title']);
                $questionansText = $this->getTextEncode($question['Question']['answer']);
                if(!$questionansText){
                    $questionansText = $question['Question']['answer'];
                }  
                $questionquText = $this->getTextEncode($question['Question']['question']);

                if ($Title != $question['Section']['title'])
                               {?>
                                       
                                       
                               <?
                                       if($Title=='')   
                                       {
                                           echo '<h3>'.$questiontitleText.'</h3><ul>';
                                       }
                                       else
                                       {
                                           echo '</ul><h3>'.$questiontitleText.'</h3><ul>';
                                       }
                               }
                               ?>			
                <li>
                    <a href="javascript:void(0);" class="no-ajaxy">
                        <?php echo strip_tags($questionquText); ?>
                    </a>
                    <?php /*<p style="display: none;" ></p>*/?>
                    <?php echo str_replace(array("<li>", "</li>", "<ul>", "</ul>"), array("<p style='display: none;'>", "</p>", "", ""), $questionansText); ?>
                </li>

                <?php $Title = $question['Section']['title']; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>