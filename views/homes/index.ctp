
<?php
/*
	 File Name : index.ctp
	 File Description : View page for home index page
	 Author : m68interactive
 */
?>


<style type="text/css">
.main-holder {
	width:100%;
	overflow:hidden;
	padding:0 0 27px;
	#padding-top:27px;
}
.gallery {
	width:578px;
	float:left;
	margin:0 6px 0 0;
	position:relative;
}
.gallery .switcher {
	padding:0;
	margin:0;
	list-style:none;
	overflow:hidden;
	width:100%;
}
.switcher li {
	float:left;
	width:15px;
	height:15px;
	margin:0 2px 0 0;
}
.switcher li a {
	display:block;
	width:15px;
	height:15px;
	overflow:hidden;
	text-indent:-9999px;
	background:#ccc;
}
.switcher li a:hover,
.switcher li.active a {background:#004080;}
.gallery-box {
	width:578px;
	position:relative;
	height:203px;
	margin:0 0 8px;
}
.gallery-box ul {
	padding:0;
	margin:0;
	list-style:none;
}
.gallery-box ul li {
	position:absolute;
	top:0;
	left:0;
	width:578px;
	height:203px;
}
.form-search {
	width:326px;
	height:159px;
	float:left;
	padding:0 0 0 18px;
	background:url(../img/bg-box02.png) no-repeat;
}
.logo-freegal {
	display:block;
	width:193px;
	height:95px;
	margin:0 auto;
	overflow:hidden;
	text-indent:-9999px;
	background:url(../img/logo-freegal.png) no-repeat;
}
.advance_search {
	width:163px;
	float:left;
	text-align:right;
	margin-top:7px;
}
.artist_browse {
  float: left;
  margin-top: 7px;
  text-align: left;
  width: 163px;
}
.advance_search a:hover {
	text-decoration:underline;
	cursor:pointer;
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
	margin:3px 0 0;
	cursor:pointer;
	background:url(../img/bg-form.gif) no-repeat 0 -67px;
}
.form-search select {
	float:left;
	width:110px;
}
.tabs-area {
	overflow:hidden;
	width:100%;
}
.carousel {
	width:100%;
	margin:0 0 23px;
	position:relative;
}
.txt-new-releases {
	position:absolute;
	top:-92px;
	right:40px;
	width:275px;
	height:98px;
	overflow:hidden;
	text-indent:-9999px;
	background:url(../img/<? echo $this->Session->read('Config.language');?>/featured_artist.gif) no-repeat;
	margin-top:5px;
}
.carousel .holder {
	width:910px;
	padding:0 15px;
	position:relative;
	overflow:hidden;
}
.carousel .prev,
.carousel .next {
	position:absolute;
	top:18px;
	left:0;
	width:14px;
	height:52px;
	text-indent:-9999px;
	overflow:hidden;
	z-index:10;
	outline:none;
	text-align:left;
	background:url(../img/arrows.png) no-repeat;
}
.carousel .next {
	right:0;
	left:auto;
	background:url(../img/arrows.png) no-repeat 100% 0;
}
.carousel-box {
	width:931px;
	float:left;
	margin:0 -10px;
}
.carousel-box-holder {
	width:910px;
	overflow:hidden;
	position:relative;
}
.carousel-box ul {
	padding:0;
	margin:0;
	list-style:none;
	width:99999px;
	overflow: hidden;
}
.carousel-box li {
	float:left;
	padding:0 22px 0 21px;
	width:90px;
}
.carousel-box .image-album {
	display:block;
	border:1px solid #838383;
	background:#959594;
	padding:2px;
	margin:0 0 2px;
}
.carousel-box .image-album img {display:block;}
.carousel-box .title-album {
	text-decoration:none;
	font-size:10px;
	line-height:10px;
	display:block;
	padding:0 0 2px;
	color:#000;
}
.carousel-box .title-album:hover {text-decoration:underline;}
.tabset {
	padding:0;
	margin:14px -12px 0 20px;
	list-style:none;
	width:246px;
	float:left;
}
.tab-content .tabset {
	width:220px;
	margin:0;
	position:relative;
	z-index:10;
	font-size:21px;
	border-right:2px solid #bbb;
}
.tabset li {
	display:block;
	margin:0 0 -4px;
}
.tab-content .tabset li {
	width:220px;
	margin:0;
	border-bottom:2px solid #bbb;
}
.tabset li a {
	display:block;
	height:50px;
	width:210px;
	padding:25px 0 0 36px;
	background:url(../img/bg-tab.png) no-repeat;
}
.tab-content .tabset li a {
	background:none;
	width:216px;
	height:45px;
	padding:20px 0 0 22px;
	text-decoration:none;
	color:#666;
}
.tab-content .tabset li a.active,
.tab-content .tabset li a:hover {
	color:#ff8000;
	background:url(../img/arrow.gif) no-repeat 100% 0;
}
.tabset li a span {
	display:block;
	height:24px;
	overflow:hidden;
	text-indent:-9999px;
}
.txt-mylib {
	width:192px;
	background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat;
}
.active .txt-mylib {background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat 0 -29px;}
.txt-national {
	width:189px;
	background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat 0 -100px;
}
.active .txt-national {background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat 0 -71px;}
.txt-top-genres {
	width:144px;
	background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat 0 -170px;
}
.active .txt-top-genres {background:url(../img/<?php echo $this->Session->read('Config.language');  ?>/txt-tab.gif) no-repeat 0 -140px;}
.tab-content {
	width:628px;
	float:left;
	position:relative;
	height:279px;
	padding:8px 0 0 26px;
	background:url(../img/bg-box.png) no-repeat;
}
.tab-content .tab-content {
	width:400px;
	padding:0;
	background:none;
	z-index:1;
}
#info-part {
	width:100%;
	overflow:hidden;
	margin-top:0;
}
#left-part {overflow:hidden;}
#left-part .text-box {
	overflow:auto;
	position:relative;
	height:265px;
	padding-right:5px;
	margin-bottom:12px;
}
.vscroll-bar {width:16px !important;}
#left-part .vscroll-line {
	left:0px !important;
	width:14px !important;
	overflow: hidden;
	background:url(../img/bg-line.gif) repeat-y;
}
#left-part .vscroll-slider{
	left:0 !important;
	cursor:pointer;
	height:32px !important;
	width:14px !important;
	background:url(../img/bg-slider.gif) no-repeat;
}
.vscroll-down,
.vscroll-up {
	height:25px;
	width:14px !important;
	cursor:pointer;
}
.scroll-content {width:292px;}
#info-part ul {
	padding:0;
	margin:0;
	font-size:13px;
	list-style:none;
}
#info-part ul li {
	overflow:hidden;
	padding:12px 24px 0 0;
	height:42px;
	width: 400px;
	vertical-align:top;
	word-spacing:-2px;
	border-bottom:2px solid #e6e6e6;
}
#info-part ul li .download {
	margin:4px 0 0;
	float:right;
	margin-right: 50px;
}
#info-part ul li .song {
	float:left;
	color:#000;
	padding:0 0 0 20px;
	text-decoration:none;
	font-size:13px;
	white-space:nowrap;
	line-height:15px;
}
#info-part ul li .singer {
	display:block;
	color:#888;
}
#info-part ul li .singer a{
	underline:none;
	color:#888;
}
.btn-more {
	width:181px;
	height:35px;
	float:left;
	margin:-52px 0 0 60px;
	text-align:center;
	color:#fff;
	text-decoration:none;
	background:url(../img/<? echo $this->Session->read('Config.language');?>/Freegal1_bluebutton.png) no-repeat;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
}
.outtaHere {
	position:absolute;
	left:-9999px;
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
	background: url(../img/bg-form.gif) no-repeat;
}
.selectArea a.selectButton {
	position: absolute;
	top: 0;
	right: 0;
	width:100%;
	height:100%;
	background: url(../img/bg-form.gif) no-repeat 100% -26px;
}
.selectArea .center{
	height: 35px;
	display:block;
	padding:0 28px 0 15px;
	background: url(../img/bg-form.gif) no-repeat -7px 0;
}
.selectArea .center img {float:left;}
.optionsDivInvisible,
.optionsDivVisible {
	position: absolute;
	background-color: #eee;
	border: 1px solid #ccc;
	z-index: 30;
	font-size: 13px;
	width: 87px !important;
}
.optionsDivScroll ul {
	height: 205px;
	overflow: auto !important;
}
.drop-dif {background:#9cc;}
.optionsDivInvisible {display: none;}
.optionsDivVisible ul {
	margin:0;
	padding:2px;
	overflow:hidden;
	list-style: none;
}
.optionsDivVisible ul li {
	float:left;
	list-style-position:outside;
	list-style:none;
	width:100%;
}
.optionsDivVisible a {
	color: #000;
	overflow:hidden;
	text-decoration: none;
	display: block;
	height:1%;
	padding: 2px 4px;
	text-align:left;
}
.optionsDivVisible a img {
	border:none;
	float:left;
}
.optionsDivVisible a:hover {text-decoration:underline;}
</style>
<?php echo $javascript->link('jquery.marquee.min'); ?>
<?php echo $javascript->link('custom_scroller.js'); ?>
<?php echo $javascript->link('jquery.main.js'); ?>
<?php echo $javascript->link('freegal.home.musicbox.js'); ?>


<?php
function ieversion()
{
	  ereg('MSIE ([0-9]\.[0-9])',$_SERVER['HTTP_USER_AGENT'],$reg);
	  if(!isset($reg[1])) {
		return -1;
	  } else {
		return floatval($reg[1]);
	  }
}
$ieVersion =  ieversion();

?>

	<div id="main">
		<div class="main-holder">
			<div class="gallery">
				<div class="gallery-box">
					<ul>
						<?php
							foreach($artists as $key => $artist):
									if($artist['Artist']['territory'] == $this->Session->read('territory') && $artist['Artist']['language'] == Configure::read('App.LANGUAGE')){
										if($key == 0) {
											echo '<li>'.$html->link(
												$html->image($cdnPath.'artistimg/'.$artist['Artist']['artist_image'], array("alt" => $artist['Artist']['artist_name'], "title" => $artist['Artist']['artist_name'], "height" => "203", "width" => "577")),
												array('controller'=>'artists', 'action'=>'album', base64_encode($artist['Artist']['artist_name'])),
												array('class'=>'first','escape'=>false)
											).'</li>';
										}
										else {
											echo '<li>'.$html->link(
												$html->image($cdnPath.'artistimg/'.$artist['Artist']['artist_image'], array("alt" => $artist['Artist']['artist_name'], "title" => $artist['Artist']['artist_name'], "height" => "203", "width" => "577")),
												array('controller'=>'artists', 'action'=>'album', base64_encode($artist['Artist']['artist_name'])),
												array('escape'=>false)
											).'</li>';
										}
									}
							endforeach;
						?>
					</ul>
				</div>
				<ul class="switcher">
					<li class="active"><a href="#">&nbsp;</a></li>
					<li><a href="#">&nbsp;</a></li>
					<li><a href="#">&nbsp;</a></li>
					<li><a href="#">&nbsp;</a></li>
					<li><a href="#">&nbsp;</a></li>
					<li><a href="#">&nbsp;</a></li>
				</ul>
			</div>
			<span action="#" class="form-search">
				<fieldset>
					<legend class="hidden">search</legend>
					<strong class="logo-freegal">freegal</strong>
					<div class="row">
						<form controller="Home" class="search_form" id="HomeSearchForm" method="get" action="/search/advanced_search" accept-charset="utf-8">
						<span class="field"><input name="q" type="text" size="24" id="autoComplete" value="" /></span>
						<span class="in">in</span>
						<select title="Artists" id="type111" name="type"><option value="artist">Artists</option><option value="song">Song</option><option value="album">Album</option><option value="genre">Genres</option><option value="composer">Composer</option><option value="label">Label</option></select>
						<input type="submit" class="submit" value="ok" />
						</form>
            <span class="artist_browse"><a href="/genres/view">A to Z Artist Browsing</a>&nbsp;&nbsp;&nbsp;</span>
						<span class="advance_search"><a href="/search/advanced_search">Advanced Search</a>&nbsp;&nbsp;&nbsp;</span>
					</div>
				</fieldset>
			</span>
		</div>
		<div class="carousel">
			<strong class="txt-new-releases">Featured</strong>
			<div class="holder">
				<a href="#" class="prev">prev</a>
				<div class="carousel-box-holder">
					<div class="carousel-box">
						<ul>
							<?php
								foreach($featuredArtists as $k => $v){
									$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
									$image =  Configure::read('App.Music_Path').$albumArtwork;
									if(strlen($v['Album']['AlbumTitle']) > 14){
										$title = substr($v['Album']['AlbumTitle'], 0, 14)."..";
									}else{
										$title = $v['Album']['AlbumTitle'];
									}
								//	$imageUrl = "/proxy/?url=".$image."&maxlength=84";
									echo "<li>".$html->link($html->image($image,array("height" => "77", "width" => "84")),
										array('controller'=>'artists', 'action'=>'view', base64_encode($v['Album']['ArtistText']), $v['Album']['ProdID'] , base64_encode($v['Album']['provider_type'])),
										array('class'=>'first','escape'=>false)).'<a class="title-album">'.$title."</a></li>";
								}
							?>
						</ul>
					</div>
				</div>
				<a href="#" class="next">next</a>
			</div>
		</div>
		<div class="tabs-area">
			<ul class="tabset">
				<li><a href="#tab-1" id="tab-1-li" class="tab"><span class="txt-mylib">MyLib Top 10</span></a></li>
				<li><a href="#tab-2" id="tab-2-li" class="tab"><span class="txt-national">National Top 100</span></a></li>
				<li><a href="#tab-3" id="tab-3-li" class="tab active"><span class="txt-top-genres">Top Genres</span></a></li>
			</ul>
			<div class="tab-content" id="tab-1">
			</div>
			<div class="tab-content" id="tab-2">
			</div>
			<div class="tab-content" id="tab-3">
				<div class="tabs-area">
					<ul class="tabset">
						<li><a href="#genre-tab-1-content" class="tab active" id="genre-tab-1" >Pop</a></li>
						<li><a href="#genre-tab-2-content" class="tab" id="genre-tab-2" >Rock</a></li>
						<li><a href="#genre-tab-3-content" class="tab" id="genre-tab-3" >Country</a></li>
						<li><a href="#genre-tab-4-content" class="tab" id="genre-tab-4" >Classical</a></li>
					</ul>
					<div class="tab-content" id="genre-tab-1-content">
						<div id="info-part">
							<div id="left-part">
								<div class="text-box vscrollable">
									<ul>
										<?php if(count($genre_pop) > 0){ ?>
										<?php
											$j =0;
											$k = 0;
											for($i = 0; $i < count($genre_pop); $i++) {
											if($j==5){
												break;
											}
											echo "<li>";
										?>
										<span class="download">
										<?php
												if($genre_pop[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
													if($libraryDownload == '1' && $patronDownload == '1') {
														$genre_pop[$i]['Song']['status'] = 'avail1';
														if($genre_pop[$i]['Song']['status'] != 'avail') {
															?>
															<form method="Post" id="form<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
															<input type="hidden" name="ProdID" value="<?php echo $genre_pop[$i]["Song"]["ProdID"];?>" />
															<input type="hidden" name="ProviderType" value="<?php echo $genre_pop[$i]["Song"]["provider_type"]; ?>" />

															<span class="beforeClick" id="song_<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>">
															<a href='javascript:void(0);' onclick='userDownloadAll("<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
															</span>
															<span class="afterClick" id="downloading_<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
															<span id="download_loader_<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-15px;margin-right:-15px')); ?></span>
															</form>
															<?php
														} else {
														?>
															<a href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
														<?php
														}
													} else {
														if($libraryDownload != '1') {
															$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
															$wishlistCount = $wishlist->getWishlistCount();
															if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
															?>
																<?php __("Limit Met");?>
															<?php
															} else {
																$wishlistInfo = $wishlist->getWishlistData($genre_pop[$i]["Song"]["ProdID"]);
																if($wishlistInfo == 'Added to Wishlist') {
																?>
																	<?php __("Added to Wishlist");?>
																<?php
																} else {
																?>
																	<span class="beforeClick" id="wishlist<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>","<?php echo $genre_pop[$i]["Song"]["provider_type"]; ?>" );'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
																	<span class="afterClick" id="downloading_<?php echo $genre_pop[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
																<?php
																}
															}

														} else {
														?>
															<?php __("Limit Met");?>
														<?php
														}
													}
												} else {
												?>
													<span title='<?php __("Coming Soon");?> ( <?php if(isset($genre_pop[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($genre_pop[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span>
												<?php
												}?>
										</span>
											<span style="float:left;margin-left:25px;">
											<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$k, "onClick" => 'playSample(this, "'.$k.'", '.$genre_pop[$i]['Song']['ProdID'].', "'.base64_encode($genre_pop[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); ?>
											<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$k)); ?>
											<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$k, "onClick" => 'stopThis(this, "'.$k.'");')); ?>
											</span>
											<span class="song">
												<?php
												if (strlen($genre_pop[$i]['Song']['SongTitle']) >= 22 ) {
													echo '<span title="'.$this->getTextEncode($genre_pop[$i]['Song']['SongTitle']).'">' . $this->getTextEncode(substr($genre_pop[$i]['Song']['SongTitle'], 0, 22)) . "..." . "</span>";
												} else {
													echo $this->getTextEncode($genre_pop[$i]['Song']['SongTitle']);
												}
												?>
												<span class="singer">
													<?php
														echo "<a href='/artists/album/".base64_encode($genre_pop[$i]['Song']['ArtistText'])."'>".$this->getTextEncode(substr($genre_pop[$i]['Song']['ArtistText'], 0, 22))."</a>";
													?>
												</span>
											</span>
										<?php
											$k++;
											}
											echo "</li>";
										}
										?>

									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-content" id="genre-tab-2-content">
					</div>
					<div class="tab-content" id="genre-tab-3-content">
					</div>
					<div class="tab-content" id="genre-tab-4-content">
					</div>
					</div></div>

			</div>

		<a href="/genres/view" class="btn-more">See All Genres</a>

	</div>
	<input type="hidden" id="genre-load-info-1" value="0" />
	<input type="hidden" id="genre-load-info-2" value="0" />
	<input type="hidden" id="genre-load-info-3" value="0" />
	<input type="hidden" id="genre-load-info-4" value="0" />
	<input type="hidden" id="my-lib-load-info" value="0" />
	<input type="hidden" id="nationsl-top-load-info" value="0" />