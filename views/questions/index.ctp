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
        <h2><?php __('FAQs'); ?></h2>
    </header>
    <div class="faq-container">
        <ul>
            <?php
            $Title = "";
            foreach ($questions as $question):

                $questiontitleText = $this->getTextEncode($question['Section']['title']);
                $questionansText = $this->getTextEncode($question['Question']['answer']);
                $questionquText = $this->getTextEncode($question['Question']['question']);

                if ($Title != $question['Section']['title'])
                {
                    ?>
                    <h3><?php echo $questiontitleText; ?></h3>
                    <?
                }
                ?>			
                <li>
                    <a href="#" class="no-ajaxy">
                        <?php echo strip_tags($questionquText); ?>
                    </a>
                    <p style="display: none;" ></p>
                    <?php echo str_replace(array("<li>", "</li>", "<ul>", "</ul>"), array("<p style='display: none;'>", "</p>", "", ""), $questionansText); ?>
                </li>

                <?php $Title = $question['Section']['title']; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.faq-container li a').on('click', function(e) {
            e.preventDefault();
            if ($(this).siblings('p').hasClass('active')) {
                $(this).siblings('p').slideUp(500).removeClass('active');
            } else {
                $('.faq-container p').slideUp(500).removeClass('active');
                $(this).siblings('p').slideDown(500).addClass('active');
            }

        });
    });
</script>