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
                            $html->addCrumb('The Great Fall Concert Ticket Giveway', '/great_fall_concert');
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                    ?>
                </div>
		<header><?php echo $session->flash(); ?>
			<h2><?php __('The Great Fall Concert Ticket Giveway');?></h2>
		</header>
		<div class="faq-container">
                    <ul>
                        <?php echo $page->getPageContent('great_fall_concert'); ?>
                    </ul>
                </div>
	</section>