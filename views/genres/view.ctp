<?php
/*
	 File Name : view.ctp
	 File Description : View page for genre view
	 Author : m68interactive
 */
?>
<style>

.vscroll-bar {width:16px !important;}
.vscroll-line {
	left:0px !important;
	width:14px !important;
	overflow: hidden;
	background:url(/img/bg-line.gif) repeat-y;
}
.vscroll-slider{
	left:0 !important;
	cursor:pointer;
	height:32px !important;
	width:14px !important;
	background:url(/img/bg-slider.gif) no-repeat;
}
.vscroll-down,
.vscroll-up {
	height:25px;
	width:14px !important;
	cursor:pointer;
}



.scroll-content {
    height: 552px;
    margin-left: 5px;
    overflow: auto;
    padding-left: 3px;
}
.form-search {
	width:326px;
	float:right;
	margin-top: -14px;
	margin-left: 40px;

}
.logo-freegal {
	display:block;
	width:193px;
	height:95px;
	margin:0 auto;
	overflow:hidden;
	text-indent:-9999px;
	background:url(/img/logo-freegal.png) no-repeat;
}
.form-search .row {
	overflow:hidden;
	width:100%;
}
.form-search .field {
	float:left;
	width:138px;
	background:#fff;
	border:1px solid #b3b3b3;
	border-color:#b3b3b3 #b3b3b3 #e0edf1 #e0edf1;
}
.form-search .field input {
	float:left;
	width:128px;
	padding:3px 5px;
	margin:0;
	background:none;
	border:0;
}
.form-search .in {
	float:left;
	text-align:center;
	width:30px;
	line-height:20px;
	font-size:15px;
	color:#666;
}
.form-search .submit {
	float:left;
	width:31px;
	height:18px;
	overflow:hidden;
	text-indent:-9999px;
	line-height:0;
	font-size:0;
	border:0;
	margin:1px 0 0;
	cursor:pointer;
	background:url(/img/bg-form.gif) no-repeat 0 -67px;
}
.form-search select {
	float:left;
	width:110px;
}
.selectArea {
	position: relative;
	height: 23px;
	float:left;
	margin:0 5px 0 0;
	padding:0;
	color:#000;
	font:13px/20px Tahoma, Arial, Helvetica, sans-serif;
}
.selectArea .left {
	position: absolute;
	top: 0;
	left:0;
	width:7px;
	height:100%;
	background: url(/img/bg-form.gif) no-repeat;
}
.selectArea a.selectButton {
	position: absolute;
	top: 0;
	right: 0;
	width:100%;
	height:100%;
	background: url(/img/bg-form.gif) no-repeat 100% -26px;
}
.selectArea .center{
	height: 35px;
	display:block;
	padding:0 28px 0 15px;
	background: url(/img/bg-form.gif) no-repeat -7px 0;
}

.genre_list_item{
	cursor: pointer;
	display:block;
}
.genre_list_item_all{
  cursor: pointer;
	display:block;
}
#ajax_genrelist_content{
	margin-left: 20px;
}
</style>
<?php echo $javascript->link('jquery.min.js'); ?>
<?php echo $javascript->link('custom_scroller.js'); ?>
<script type="text/javascript">
jQuery(document).ready(function() {
      VSA_initScrollbars();
  });
</script>
<div class="breadCrumb">
<?php

	$genre_text_conversion = array(
		"Children's Music" =>  "Children's" ,
		"Classic"  =>  "Soundtracks",
		"Comedy/Humor"  =>  "Comedy",
		"Country/Folk"  =>  "Country",
		"Dance/House"  =>  "Dance",
		"Easy Listening Vocal" => "Easy Listening",
		"Easy Listening Vocals"  =>  "Easy Listening",
		"Folk/Blues" => "Folk",
		"Folk/Country" => "Folk",
		"Folk/Country/Blues" => "Folk",
		"Hip Hop Rap" => "Hip-Hop Rap",
		"Rap/Hip-Hop" => "Hip-Hop Rap",
		"Rap / Hip-Hop" => "Hip-Hop Rap",
		"Jazz/Blues"  =>  "Jazz",
		"Kindermusik"  =>  "Children's",
		"Miscellaneous/Other" => "Miscellaneous",
		"Other" => "Miscellaneous",
		"Age/Instumental" => "New Age",
		"Pop / Rock" =>  "Pop/Rock",
		"R&B/Soul" => "R&B",
		"Soundtracks" => "Soundtrack",
		"Soundtracks/Musicals" => "Soundtrack",
		"World Music (Other)" => "World Music"
	);
	
	$genre_crumb_name = isset($genre_text_conversion[trim($genre)])?$genre_text_conversion[trim($genre)]:trim($genre);
	
	$html->addCrumb(__('All Genre', true), '/genres/view/');
	$html->addCrumb( $genre_crumb_name  , '/genres/view/'.base64_encode($genre_crumb_name));
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
	$totalRows = count($genresAll);





?>
</div>
<script>

function load_genres(link , id_serial , genre_name)
{
	jQuery("#ajax_genrelist_content").empty().html(jQuery("#ajx_loader").html());
	jQuery('#ajax_genrelist_content').load(link);
	jQuery('.genre_list_item_all,.genre_list_item').css('font-weight' , 'normal');
	jQuery('#genre_list_item_'+id_serial).css('font-weight' , 'bold');
	jQuery(".breadCrumb").find("a:eq(2)").html(genre_name);
	jQuery(".breadCrumb").find("a:eq(2)").attr('href' , link );
	jQuery(".breadCrumb").find("a:eq(2)").attr('href' , jQuery(".breadCrumb").find("a:eq(2)").attr('href').replace('ajax_view' , 'view'));

	 jQuery("#genre_artist_search a").each(function () {
		jQuery(this).attr('href' , jQuery(this).attr('href').replace('ajax_view' , 'view'));
	});


	//setInterval('VSA_initScrollbars()' , 500);
}
function sortText(a, b) {
	var A = $(a).text(), B = $(b).text();
	if ( A < B ) return -1;
	else if ( A > B ) return 1;
	return 0;
}



 jQuery(document).ready(function() {

	var tgt = $('#genre_scroller');
  all = $(tgt.find('a.genre_list_item_all'));
	arr = $(tgt.find('a.genre_list_item').get().sort(sortText));
	tgt.empty();
	tgt.empty().append(all);
  tgt.append(arr);
	VSA_initScrollbars();


   var map = {};
	jQuery("#genre_scroller a").each(function(){
		var value = $(this).text();
		if (map[value] == null){
			map[value] = true;
		} else {
			$(this).next().remove();
			$(this).remove();
		}
	});
 });

function replaceText() {
    jQuery(".paging span a").each(function () {
		jQuery(this).attr('href' , jQuery(this).attr('href').replace('ajax_view' , 'view'));
	});


}
jQuery(document).ready(replaceText);
jQuery("#ajax_genrelist_content").ajaxStop(replaceText);
jQuery("html").ajaxStop(replaceText);
</script>
<!--span action="#" class="form-search">
	<fieldset>
		<legend class="hidden">search</legend>
		<div class="row">
			<form controller="Home" class="search_form" id="HomeSearchForm" method="get" action="/homes/search" accept-charset="utf-8">
			<span class="field"><input name="search" type="text" size="24" id="autoComplete" value="" /></span>
			<input type="hidden" name="auto" size="24" id="auto" value="0" />
			<span class="in">in</span>
			<select title="Artists" id="type111"><option value="artist">Artists</option><option value="song">Song</option><option value="album">Album</option></select>
			<input type="submit" class="submit" value="ok" />
			</form>
		</div>
	</fieldset>
</span-->
<table>
<tr>
<td style="vertical-align:top;padding-left: 14px;">
<div id="genreViewAll">
	<div id="genreViewAllBox">
		<img src="/img/<?php echo $this->Session->read('Config.language'); ?>/genre.png" height="34px" width="195px" />
	</div>
	<br class="clr" />

	  <?php
  if($isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad'))
  {
    ?><div style="height:520px; overflow:auto; overflow-y:scroll; overflow-x:scorll;-webkit-overflow-scrolling:touch"><?php
  }
  else
  {
    ?><div class="scroll-content vscrollable" id = "genre_scroller"><?php
  }
  ?>
    <a class="genre_list_item_all" style="font-weight:bold;" id="genre_list_item_0" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode('All'); ?>' ,'0' , '<?php echo addslashes('All');  ?>')"><?php echo __('All Artists'); ?></a>
    <?php
		$genre_count = 1;
    foreach ($genresAll as $genre_all):
				if($genre_all['Genre']['Genre'] != ''){
					$genre_name = isset($genre_text_conversion[trim($genre_all['Genre']['Genre'])])?$genre_text_conversion[trim($genre_all['Genre']['Genre'])]:$genre_all['Genre']['Genre'];	
					if($genre_name == $genre){
						?>
						<a class="genre_list_item" style="font-weight:bold;" id="genre_list_item_<?php echo $genre_count; ?>" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_all['Genre']['Genre']); ?>' ,'<?php echo $genre_count; ?>' , '<?php echo addslashes($genre_name);  ?>')"><?php echo $genre_name; ?></a>
						<?php
					}
					else{
						
					
						?>
						<a class="genre_list_item" id="genre_list_item_<?php echo $genre_count; ?>" style="curser:pointer" onclick="load_genres('/genres/ajax_view/<?php echo base64_encode($genre_name); ?>' , '<?php echo $genre_count; ?>' , '<?php echo addslashes($genre_name);  ?>' )" ><?php echo $genre_name; ?></a>
						<?php
					}
				}
		$genre_count++;
		endforeach;
//	}
    ?>
	</div>

</div>
</td>
<td style="vertical-align:top;padding-top:10px;">

<div style="display:none;" id="ajx_loader"><img style="margin-top:200px;margin-left:270px" src="/img/ajax-loader-big.gif" ></div>

<div id="ajax_genrelist_content">
<div id="genre_artist_search" style="overflow-y: hidden;">
 <?php __('Artist Search'); ?>&nbsp;&nbsp;
 <?php echo $html->link('ALL',array('controller' => 'genres', 'action' => 'view', base64_encode($genre)));?>&nbsp;
 <?php echo $html->link('#',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'spl'));?>&nbsp;
 <?php echo $html->link('A',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'A'));?>&nbsp;
 <?php echo $html->link('B',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'B'));?>&nbsp;
 <?php echo $html->link('C',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'C'));?>&nbsp;
 <?php echo $html->link('D',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'D'));?>&nbsp;
 <?php echo $html->link('E',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'E'));?>&nbsp;
 <?php echo $html->link('F',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'F'));?>&nbsp;
 <?php echo $html->link('G',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'G'));?>&nbsp;
 <?php echo $html->link('H',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'H'));?>&nbsp;
 <?php echo $html->link('I',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'I'));?>&nbsp;
 <?php echo $html->link('J',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'J'));?>&nbsp;
 <?php echo $html->link('K',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'K'));?>&nbsp;
 <?php echo $html->link('L',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'L'));?>&nbsp;
 <?php echo $html->link('M',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'M'));?>&nbsp;
 <?php echo $html->link('N',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'N'));?>&nbsp;
 <?php echo $html->link('O',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'O'));?>&nbsp;
 <?php echo $html->link('P',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'P'));?>&nbsp;
 <?php echo $html->link('Q',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Q'));?>&nbsp;
 <?php echo $html->link('R',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'R'));?>&nbsp;
 <?php echo $html->link('S',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'S'));?>&nbsp;
 <?php echo $html->link('T',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'T'));?>&nbsp;
 <?php echo $html->link('U',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'U'));?>&nbsp;
 <?php echo $html->link('V',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'V'));?>&nbsp;
 <?php echo $html->link('W',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'W'));?>&nbsp;
 <?php echo $html->link('X',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'X'));?>&nbsp;
 <?php echo $html->link('Y',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Y'));?>&nbsp;
 <?php echo $html->link('Z',array('controller' => 'genres', 'action' => 'view', base64_encode($genre),'Z'));?>&nbsp;
</div>
<div id="genreResults">
	<table cellspacing="0" cellpadding="0" border="0" width = "733px">
	<?php
	if(count($genres) > 0){
		$totalRows = ceil(count($genres)/3);
		for ($i = 0; $i < $totalRows; $i++) {
			$class = null;
			if ($i % 2 != 0) {
				$class = ' class="altrow"';
			}
			echo "<tr" . $class . ">";
			$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*2)));
			foreach ($counters as $counter):
				if($counter < count($genres)) {

					echo "<td width='250'><p>";
					if (strlen($genres[$counter]['Song']['ArtistText']) >= 30) {
						$ArtistName = substr(htmlspecialchars($genres[$counter]['Song']['ArtistText']), 0, 30) . '...';
						echo '<span title="'.$genres[$counter]['Song']['ArtistText'].'">' . $html->link(
							$ArtistName,
							array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($genres[$counter]['Song']['ArtistText'])). '/'. base64_encode($genre))) . '</span>'; ?>
					<?php
					} else {
						$ArtistName = htmlspecialchars($genres[$counter]['Song']['ArtistText']);
						echo $html->link(
							$ArtistName,
							array('controller' => 'artists', 'action' => 'album', str_replace('/','@',base64_encode($genres[$counter]['Song']['ArtistText'])).  '/'.base64_encode($genre)));
					}
					echo '</p></td>';
				}
			endforeach;
			echo '</tr>';
		}
	}else{
		echo "<tr><td>No Results Found</td></tr>";
	}
	?>
	</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?><br />
</div>
</div>
<!--div id="genreAdvSearch">
	<?php __("Can't find what you are looking for, try our") ?>&nbsp;<?php echo $html->link(__('Advanced Search', true), array('controller' => 'homes', 'action' => 'advance_search')); ?>.
</div-->
</tr>
</table>
<?php
if($genre == 'QWxs' || $genre == 'All'){
  ?><script>jQuery('.genre_list_item_all').css('font-weight' , 'bold');</script><?php
}
else
{
  ?><script>jQuery('.genre_list_item_all').css('font-weight' , 'normal');</script><?php
}
?>
