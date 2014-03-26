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
	echo $html->getCrumbs(' > ', __('Home', true), '/homes');
?>
</div>
	<br class="clr" />
	<div class="news_list">
		<p>
<?php
//echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
?></p>
		 <table id="list">
       <script>
       function showhide(flag, id)
       {
         if(flag=="short")
         {
            document.getElementById("shortNews"+id).style.display="block";
            document.getElementById("detailsNews"+id).style.display="none";
         }
         
         if(flag=="detail")
         {
            document.getElementById("shortNews"+id).style.display="none";
            document.getElementById("detailsNews"+id).style.display="block";
         }
       }
        </script>
          <?php
          foreach($news as $newx)
          {
            $newsText = str_replace('<div', '<p', $newx['News']['body']);
            $newsText = str_replace('</div>', '</p>', $newsText);
            ?>
            <tr>
				<td valign = 'top' class="left"> <img src ='<?php echo $cdnPath. 'news_image/' . $newx['News']['image_name'];?>' style="width:180px;height:180px;" /></td>
				<td valign = 'top' style ='padding-left: 19px;' >
					<label><h3><?php echo $this->getTextEncode($newx['News']['subject']);?></h3></label>
					<label><b><?php echo $this->getTextEncode($newx['News']['place']) . " : " . date( "F d, Y", strtotime($newx['News']['created']))  ;?></b></label>
					<div style = "padding-top:3px;">
						<div id="shortNews<?php echo $newx['News']['id']; ?>">
              <?php echo $this->getTextEncode(substr($newsText,0, strpos($newsText, "</p>")+4));?>
              <?php
              if(strlen($newsText) > strpos($newsText, "</p>")+4)
              {
                ?><a href="javascript:void(0)" onClick="showhide('detail', '<?php echo $newx['News']['id']; ?>')">+ See more</a><?php
              }
              ?>
						</div>
            <div id="detailsNews<?php echo $newx['News']['id']; ?>" style="display:none">
							<?php echo $newsText; ?>
              <a href="javascript:void(0)" onClick="showhide('short', '<?php echo $newx['News']['id']; ?>')">- See Less</a>
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