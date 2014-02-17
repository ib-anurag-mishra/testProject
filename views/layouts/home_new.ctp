<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <?php
        echo $this->Html->css(
                array(
                    'freegal_styles',
                    'jquery.autocomplete',
                    'colorbox',
                    'freegal40'    
                )
        );

        echo $javascript->link(
            array(
                'freegal40-libraries',
                'freegal',
                'site.js',
                'recent-downloads',
     //           'ajaxify-html5.js',
                'html5shiv',
                'freegal40-libraries',
                'freegal40-site'    
                )
            );
        if ($this->Session->read('lId') && $this->Session->read('lId') != '')
        {
            echo $this->Html->css('styles');
        }    
        ?>
    </head>

    <body>
        <div class="wrapper">
            <?php echo $this->element('header_new'); ?>
            <?php echo $this->element('top_navigation'); ?>    
            <div class="content-wrapper clearfix">
                <?php echo $this->element('left_navigation'); ?>
                <section class="content">
                    <?php //echo $content_for_layout; ?>
                </section>
            </div>
            <?php echo $this->element('footer_new'); ?>
        </div>
    </body>
</html>
            