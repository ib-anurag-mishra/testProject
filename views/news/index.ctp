<?php
/*
 File Name : admin_managelibrary.ctp
 File Description : View page for edit library.
 Author : m68interactive
 */
?>
<?php $this->pageTitle = 'Libraries'; ?>
<form>

<script type="text/javascript">
$(function(){
// The height of the content block when it's not expanded
var adjustheight = 80;
// The "more" link text
var moreText = "+ See more";
// The "less" link text
var lessText = "- See Less";
// Sets the .more-block div to the specified height and hides any content that overflows
$(".more-less .more-block").css('height', adjustheight).css('overflow', 'hidden');
// The section added to the bottom of the "more-less" div
$(".more-less").append('<a href="#" class="adjust"></a>');
$("a.adjust").text(moreText);
$(".adjust").toggle(
	function() {
		$(this).parents("div:first").find(".more-block").css('height', 'auto').css('overflow', 'visible');
		// Hide the [...] when expanded
		$(this).text(lessText);
	}, 
	function() {
		$(this).parents("div:first").find(".more-block").css('height', adjustheight).css('overflow', 'hidden');
		$(this).text(moreText);
	});
});
</script> 

<div class="questions index">

<div class="breadCrumb">
<?php
	$html->addCrumb('News', '/news');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
	<br class="clr" />
	<div class="news_list">
		<p>
<?php
//echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
?></p>
		 <table id="list">
          <?php
          foreach($news as $newx)
          {
            ?>
            <tr>
				<td valign = 'top' class="left"> <img src ='/img_news/<?php echo $newx['News']['image_name'];?>' style="width:180px;height:180px;" /></td>
				<td valign = 'top' style ='padding-left: 19px;' >
					<label><?php echo $newx['News']['subject'];?></label>
					<label><b><?php echo $newx['News']['place'] . " : " . date( "F d, Y", strtotime($newx['News']['created']))  ;?></b></label>
					
					<div class="more-less" style = "padding-top:3px;">
						<div class="more-block" style="height: 80px; overflow: hidden;">
							<?php echo $newx['News']['body'];?>
						</div>
					</div>
				</td>
            </tr>  
			<tr><td colspan='2' style="height:10px;"></td></tr>
          <?php
          }
          ?>
        </table>
		
		<br class="clr" />
	<div class="paging">
	      <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	| 	<?php echo $paginator->numbers();?>
	      <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
	</div>
	</div>
</div>

<?php 
 echo $session->flash();
?>
</form>