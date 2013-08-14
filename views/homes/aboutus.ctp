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
                            $html->addCrumb('About Freegal Music', '/aboutus');
                            echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
                    ?>
                </div>
		<header><?php echo $session->flash(); ?>
			<h2><?php __('About Freegal Music');?></h2>
		</header>
		<div class="faq-container">
                    <ul>
                        <?php echo $page->getPageContent('aboutus'); ?>
                    </ul>
                </div>
	</section>