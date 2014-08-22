</div>
<!-- end .content -->


</div>
<!-- end .content-wrapper -->





<footer class="site-footer">
	<div class="footer-content">
		<div class="legal">&copy; 2013 Library Ideas, LLC All Rights Reserved
		</div>
		<nav class="footer-nav">
			<ul class="clearfix">
				<li><?php echo $html->link(__('Home', true), array('controller' => 'homes', 'action' => 'index')); ?>
				</li>
				<li><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index')); ?>
				</li>
				<li><?php echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' => 'us_top_10')); ?>
				</li>
				<li><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' => 'new_releases')); ?>
				</li>
				<li><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' => 'view')); ?>
				</li>
				<li><?php echo $html->link(__('Terms & Conditions', true), array('controller' => 'homes', 'action' => 'terms')); ?>
				</li>
				<li class="last-child"><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?>
				</li>
			</ul>
		</nav>
		<div class="languages">
			<?php $this->getTextEncode(__('Also available in')); ?>
			<?php
			if ($language)
			{
				$language = $language->getLanguage();
				$i = 1;
				foreach ($language as $k => $v)
				{
                                        $current_page   =   $this->here;

					if(strstr($current_page, '/users/'))        // If Login Page
                                        {                                            
                                            ?>
                                                <a style="color: #A1A7AE;padding-left:10px;padding-right:10px;" class="no-ajaxy" href="<?php echo $current_page."?langType=".$k ?>" id="<?php echo $k; ?>">
                                                <?php echo $this->getTextEncode($v); ?>
                                                </a>
                                             <?php
                                        }
                                        else            // For other pages
                                        {
                                            echo '<a style="color: #A1A7AE;padding-left:10px;padding-right:10px;" href="javascript:void(0)" id=' . $k . ' onClick="changeLang(' . $k . ');">';
                                            echo $this->getTextEncode($v);
                                            echo '</a>';
                                        }
                                        
					?>
			
			<?php			
			if ($i > 0 && $i < count($language))
			{
				echo "| ";
			}
			$i++;
				}
			}
			?>

		</div>
	</div>
</footer>
<?php if ($this->Session->read("patron") && $this->Session->read('library_type') == '2') { ?>
	<div class="filler"></div>
<?php } ?>


<?php
if ($this->Session->read("patron"))
{
	?>
<?php
if ($this->Session->read('library_type') == '2')
{
	echo $javascript->link(array('streaming.js'));
	?>
<div class="player-wrapper">
	<div class="fmp_container">
		<div id="no_flash" style="display: none;">
			<h2>
				You need to install Adobe Flash in order to play songs. Please click
				here to <a href="http://get.adobe.com/flashplayer/"> Download now.</a>
			</h2>
		</div>
		<div id="alt"></div>
	</div>

</div>
<?php } ?>
<?php } ?>


<script type="text/javascript">

                $(document).ready(function() {

                    $("#alt").hide();
                    $("#no_flash").hide();

                    <?php
                    if ($this->Session->read("patron"))
                    {
                        if ($this->Session->read('library_type') == '2')
                        {
                            ?>
                                //for player initialization
                                if (swfobject !== 'undefined') {

                                    if (swfobject.hasFlashPlayerVersion("9.0.115"))
                                    {
                                        $("#alt").show();
                                    }
                                    else
                                    {
                                        $("#no_flash").show();
                                    }
                                }
                            <?php
                        }?>

                        var params = {allowscriptaccess: "always", menu: "false", bgcolor: "000000"};
                        var attributes = {id: "audioplayer"};

                        swfobject.embedSWF("<?php echo $this->webroot; ?>swf/audioplayer.swf", "audioflash", "1", "0", "9.0.0", "<?php echo $this->webroot; ?>swf/xi.swf", {}, params, attributes);
                    <?php }
                    ?>

                });

                //for google anlytics

                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 			 	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  				ga('create', 'UA-16162084-1', 'auto');
  				ga('send', 'pageview');
</script>
