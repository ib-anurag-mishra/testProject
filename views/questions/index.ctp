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
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                    ?>
                </div>
		<header>
			<h2><?php __('FAQs');?></h2>
		</header>
		<div class="faq-container">
                    
                        <?php $Title = "";
                        
                            foreach ($questions as $question): 
                                
                                $questiontitleText = $this->getTextEncode($question['Section']['title']);
                                $questionansText = $this->getTextEncode($question['Question']['answer']);
                                $questionquText = $this->getTextEncode($question['Question']['question']);
                                
                               if($Title != $question['Section']['title']) 
                               {?>
                                       <h3><?php echo $questiontitleText; ?></h3>
                                       
                               <?
                                       if($Title=='')   
                                       {
                                           echo '<ul>';
                                       }
                                       else
                                       {
                                           echo '</ul><ul>';
                                       }
                               }
                               ?>			
                                       <li><a href="#" class=""><?php echo strip_tags($questionquText); ?></a>
                                           <p><?php echo str_replace(array("<li>","</li>","<ul>","</ul>"), array("<p>","</p>","",""), $questionansText); ?></li>
                               <?php $Title = $question['Section']['title']; ?>
                        <?php endforeach; ?>
                                       <?php 
                                                if($Title!='') 
                                                    echo '</ul>';
                                                    
                                       ?>
                </div>
	</section>