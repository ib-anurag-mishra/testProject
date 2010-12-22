<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	<title>
		Freegal Music : Your New Music Library :Preview</title>
   	<link href="/favicon.ico" type="image/x-icon" rel="icon" /><link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />		
			<style>
				* {
					margin: 0px;
					padding: 0px;
				}

				.clr {
					clear:both;
				}
				img {
					border: 0px;
				}

				body  {
					font: 100% Verdana, Arial, Helvetica, sans-serif;
					background: <?php echo $bgColor; ?>;
					margin: 0px;
					padding: 0px;
					text-align: center;
					color: <?php echo $textColor; ?>;
				}

				#container { 
					width: 965px;
					min-height: 500px;
					margin: 0 auto;
					text-align: left;
					background-color: <?php echo $bgColor; ?>;
				}

				#header {  
					height:60px;
					background:#333 url(../img/header.png) no-repeat;
					border-bottom: 1px solid #999;
				}
				#lib_name {
						float:left;
						font-size: 20px;
						margin-top: 17px;
						margin-left: 10px;
						font-weight: bold;
				}
				#lib_image {
						float:left;
						font-size: 20px;
						margin-left: 10px;
						font-weight: bold;
				}
				#header_right a {
						color:<?php echo $linkColor; ?>;
						text-decoration: none;
				}

				#header_right a:hover {
						text-decoration: underline;
						color:<?php echo $linkHoverColor; ?>;
				}

				#header_right ul {
						font-size:10px;
						margin-top: 5px;
						margin-right: 10px;
						float:right;
				}

				#header_right ul li {
						list-style:none;
						text-align: right;
						line-height: 130%;
				}
					
				#content {
				/*	height: 600px;*/
				}
				#navigation {
						float:left;
						font-size: 18px;
						height: 30px;
						margin-left: 5px;
				}

				#navigation form {
						display:inline;
				}

				#artist_slideshow {
						clear:both;
						padding-top: 10px;
						display:block;
						margin:0 0 10px 10px;
				}
				#slideshow {
						display:block;
						width:942px;
						height:215px;
						background-color: <?php echo $bgColor; ?>;
						border: 1px solid #999;
				}

				#ticker {
						width:939px;
						height:20px;
						background-color:<?php echo $boxheaderBgColor; ?>;
						color: <?php echo $boxheaderTextColor; ?>;
						margin-left:10px;
						margin-bottom:5px;
						display:block;
						font-size:13px;
						padding-left:5px;
						line-height:14px;
						padding-top:3px;
				}

				ul.marquee {
						float:right;
						display: block;
						padding: 0;
						margin: 0;
						list-style: none;
						position: relative;
						overflow: hidden;
						width: 795px;
						height: 20px;
				}

				ul.marquee li {
						position: absolute;
						top: -999em;
						left: 0;
						display: block;
						white-space: nowrap;
				}
						
				#suggestions {
						clear:both;
						float:left;
						display:block;
						width:307px;
						margin-left: 10px;
						margin-top: 5px;
						font-size:13px;
						height:35px;
						text-align: center;
						background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
						line-height:19px;
				}

				#suggestionsBox {
					width:305px;
						height:288px;
						padding-top:15px;
					text-align: left;
					background-color: <?php echo $bgColor; ?>;
					color: <?php echo $linkColor; ?>;
					border: 1px solid #999;
						line-height:15px;
				}

				.hlt p {
						background-color: #E1E8EB;
						font-size:12px;
						font-weight: bold;
				}

				.suggest_text {
						position:relative;
						font-size:12px;
						margin-left:20px;
						padding-left:10px;
						padding-bottom:2px;
						border-bottom: 1px solid #999;
						width:252px;
				}

				.suggest_text img {
						position:absolute;
						top:9px;
						left:240px;
				}

				.suggest_text a {
						text-decoration: none;
						color:<?php echo $linkColor; ?>;
				}

				.suggest_text a:hover {
						text-decoration: none;
						color:<?php echo $linkHoverColor; ?>;
				}

				.info_suggest {
						position:relative;
						z-index:24;
						text-decoration: none;
				}

				.info_suggest:hover {
						z-index:25;
				}
				.info_suggest span {
						display:none;
				}

				.info_suggest:hover span {
						display:block;
						position:absolute;
						top: 35px;
						left: 25px;
						width:300px;
						border: 1px solid #CCC;
						background: #FFFDC9;
						color:<?php echo $linkHoverColor; ?>;
						text-align: center;
						font-weight: normal;
				}

				#artist_container {
						margin-top: 5px;
						float:left;
						width: 640px;
						height: 335px;
				}
						
				#featured_artist {
						float:left;
						width:300px;
						height:215px;
						margin: 0 0 10px 16px;
						background-color: <?php echo $bgColor; ?>;
						border: 1px solid #999;
				}

				#newly_added {
						float:left;
						width:300px;
						height:215px;
						margin:0 0 10px 16px;
						background-color: <?php echo $bgColor; ?>;
						border: 1px solid #999;
				}

				#artist_search {
						font-size:13px;
						clear:both;
						width:620px;
						height:35px;
						text-align:center;
						background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
						margin: 0 0 0 16px;
						display:block;
				}

				#artist_search a {
						text-decoration: none;
						color:<?php echo $linkColor; ?>;
				}

				#artist_search a:hover {
				/*		text-decoration: underline;*/
						color:<?php echo $linkHoverColor; ?>;
				}
				#artist_links a {
						text-decoration: none;
						color:<?php echo $library_box_header_color; ?>;
				}

				#artist_links a:hover {
						color:<?php echo $boxHoverColor; ?>;
				}
				.links a {
						text-decoration: none;
						color:<?php echo $boxheaderTextColor; ?> !important;
				}

				.links a:hover {
						color:<?php echo $boxHoverColor; ?> !important;
				}

				.download_links a {
						text-decoration: none;
						color:<?php echo $linkColor; ?> !important;
				}

				.download_links a:hover {
						color:<?php echo $linkHoverColor; ?> !important;
				}
				#genre_artist_search {
						font-size:13px;
						clear:both;
						width:945px;
						height:20px;
						text-align:center;
						background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
						margin:10px 10px 0;
						display:block;
				}

				#genre_artist_search a {
						text-decoration: none;
						color:<?php echo $boxheaderBgColor; ?>;
				}

				#genre_artist_search a:hover {
				/*		text-decoration: underline;*/
						color:<?php echo $boxHoverColor; ?>;
				}

				#artist_searchBox {
					background-color: <?php echo $bgColor; ?>;
					border: 1px solid #999;
					text-align:left;
					width:618px;
					height:79px;
				}

				.scrollarea {
						overflow:scroll;
						overflow-x:hidden;
						height:79px;
						margin: 0px 0 0 30px;
				}

				.artist_line {
						border-bottom: 1px solid #999;
						width:544px;
				}
					
				#footer {
					clear:both;
					width:945px;
					color:#fff;
					height: 16px;
					font-size: 10px;
					text-align:right;
					margin:0 auto;
					padding:0 10px;
					line-height:16px;
					#margin:0 0 0 20px;
				}

				#footer a {
					text-decoration: none;
					color:#fff;
				}

				#footer a:hover {
					text-decoration: underline;
				}

				/* navigation */
				#nav{
					float:left;
					clear:left;
					font:11px/14px Verdana, Arial, Helvetica, sans-serif;
					list-style:none;
					padding:0;
					margin:16px 0 0 11px;
					line-height:27px;
					position:relative;
					z-index:50;
					display:table;
				}

				#nav li{
					float:left;
					margin:0 1px;
					display:inline;
				}

				#nav a{
					float:left;
					text-align:center;
					width:144px;
				/*	background:url(../images/tab-gray.png) top left no-repeat;*/
					text-decoration:none;
					color:<?php echo $navLinksColor; ?>;
				}

				#nav li ul{
					position:absolute;
					display:none;
					width:144px;
					left:-1px;
					top:26px;
					list-style:none;
					padding:1px 0 0;
					margin:0;
					text-transform:none;
					
				}

				/* This is the top rollover */
				#nav li:hover, #nav li.hover {
					position:relative;
				}

				#nav li:hover ul, #nav li.hover ul {
					display:block;
				}

				#nav li:hover a, #nav li.hover a, #nav li.active a{ 
				/*	background:url(../images/tab-white.png) top left no-repeat; */
					color:<?php echo $navLinksHoverColor; ?>;
					text-decoration: underline;
				}

				#nav li li{
					float:none;
					padding:0 0 0px;
					display:block;
				}

				#nav li.hover li a, #nav li:hover li a {
					/*background:url(../img/nav_back80.png) top left repeat;*/
						background:<?php echo $navBgColor; ?>;
						opacity: .9; filter: alpha(opacity=90); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=90)";
					color:<?php echo $navLinksColor; ?>;
					text-align:center;
					text-decoration:none;
				}

				#nav li li a{
					text-align:left;
					padding:0 14px;
					width:118px;
					display:block;
					float:none;
				}

				#nav li.hover li.hover a, #nav li:hover li:hover a {
					/*background:url(../img/nav_back_grey.png) top left repeat;*/
						/*text-decoration: underline;*/
						opacity: .5; filter: alpha(opacity=50); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
						color:<?php echo $navLinksHoverColor; ?>;
				}

				.navigation{
					width:965px;
					float:left;
					background:<?php echo $navBgColor; ?>;
				}

				#search {
					color:#fff;
					background:<?php echo $navBgColor; ?>;
					position: relative;
				}

				.search_form {
					float:left;
					line-height:14px;
					background:#fff;
				}

				#search .ac_input {	
					background:transparent url(../img/search.png) top right no-repeat;
					line-height:14px;
				}

				#search .input {
					float:left;
				}

				#search input {
						float:left;
						line-height:14px;
				}

				.searchButton {
					background:transparent url(../img/search.png) center center no-repeat;
					line-height:14px;
					border:none;
					height:13px;
					width:12px;
					cursor:pointer;	
				} 

				#search .submit {
					float:left;
					background:<?php echo $navBgColor; ?>;
					padding: 2px 0 0 2px;
					height:18px;
				}

				/* Genre Page */
				#genre {
					float:left;
					font-size:13px;
					width:200px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 0 10px;
					line-height:20px;
				}

				#genreArtist {
					clear:both;
					float:left;
					width: 180px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 0 10px;
					line-height: 20px;
					font-size:13px;
				}

				#genreArtist a {
					text-decoration: none;
					color: <?php echo $boxheaderTextColor; ?>;
				}
				#genreComposer {
					float:left;
					width: 180px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 0 3px;
					line-height: 20px;
					font-size:13px;
				}

				#genreComposer a {
					text-decoration: none;
					color: <?php echo $boxheaderTextColor; ?>;
				}
				#genreAlbum {
					float:left;
					width: 180px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 0 3px;
					line-height: 20px;
					font-size:13px;
				}

				#genreAlbum a {
					text-decoration: none;
					color: <?php echo $boxheaderBgColor; ?>;
				}

				#genreTrack {
					float:left;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 0 3px;
					line-height: 20px;
					font-size:13px;
				}

				#genreTrack a {
					text-decoration: none;
					color: <?php echo $boxheaderBgColor; ?>;
				}

				#genreDownload {
					float:left;
					width: 109px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
						color:<?php echo $boxheaderTextColor; ?>;
					padding-left:41px;
					margin: 10px 0 0 3px;
					line-height: 20px;
					font-size:13px;
				}

				#aboutBox {
					float:left;
					font-size:13px;
					width:250px;
					height: 20px;
					background-color:<?php echo $boxheaderBgColor; ?>;
					color:<?php echo $boxheaderTextColor; ?>;
					text-align: center;
					margin: 10px 0 10px 10px;
					line-height:20px;
				}

				#aboutUs {
					clear: both;
					margin: 0 10px 10px 10px;
					font-size:13px;
					min-height:375px;
				}

				#aboutUs p {
					margin-bottom:10px;
				}

				#aboutUs a {
						text-decoration: none;
						color:<?php echo $linkColor; ?>;
				}

				#aboutUs a:hover {
						text-decoration: none;
						color:<?php echo $linkHoverColor; ?>;
				}
				#terms {
					clear: both;
					margin: 10px;
					font-size:13px;
					min-height:375px;
				}

				#terms p {
					margin-bottom:10px;
				}

				#terms a {
						text-decoration: none;
						color:<?php echo $linkColor; ?>;
				}

				#terms a:hover {
						text-decoration: none;
						color:<?php echo $linkHoverColor; ?>;
				}

				.error_div {
						margin:10px;
					font-size:13px;
						text-align:center;
						height:20px;
				}

				/** Debugging **/
				pre {
					color: #000;
					background: #f0f0f0;
					padding: 1em;
				}
				pre.cake-debug {
					background: #ffcc00;
					font-size: 120%;
					line-height: 140%;
					margin-top: 1em;
					overflow: auto;
					position: relative;
				}
				div.cake-stack-trace {
					background: <?php echo $library_content_bgcolor; ?>;
					color: #333;
					margin: 0px;
					padding: 6px;
					font-size: 120%;
					line-height: 140%;
					overflow: auto;
					position: relative;
				}
				div.cake-code-dump pre {
					position: relative;
					overflow: auto;
				}
				div.cake-stack-trace pre, div.cake-code-dump pre {
					color: #000;
					background-color: #F0F0F0;
					margin: 0px;
					padding: 1em;
					overflow: auto;
				}
				div.cake-code-dump pre, div.cake-code-dump pre code {
					clear: both;
					font-size: 12px;
					line-height: 15px;
					margin: 4px 2px;
					padding: 4px;
					overflow: auto;
				}
				div.cake-code-dump span.code-highlight {
					background-color: #ff0;
					padding: 4px;
				}
				div.code-coverage-results div.code-line {
					padding-left:5px;
					display:block;
					margin-left:10px;
				}
				div.code-coverage-results div.uncovered span.content {
					background:#ecc;
				}
				div.code-coverage-results div.covered span.content {
					background:#cec;
				}
				div.code-coverage-results div.ignored span.content {
					color:#aaa;
				}
				div.code-coverage-results span.line-num {
					color:#666;
					display:block;
					float:left;
					width:20px;
					text-align:right;
					margin-right:5px;
				}
				div.code-coverage-results span.line-num strong {
					color:#666;
				}
				div.code-coverage-results div.start {
					border:1px solid #aaa;
					border-width:1px 1px 0px 1px;
					margin-top:30px;
					padding-top:5px;
				}
				div.code-coverage-results div.end {
					border:1px solid #aaa;
					border-width:0px 1px 1px 1px;
					margin-bottom:30px;
					padding-bottom:5px;
				}
				div.code-coverage-results div.realstart {
					margin-top:0px;
				}
				div.code-coverage-results p.note {
					color:#bbb;
					padding:5px;
					margin:5px 0 10px;
					font-size:10px;
				}
				div.code-coverage-results span.result-bad {
					color: #a00;
				}
				div.code-coverage-results span.result-ok {
					color: #fa0;
				}
				div.code-coverage-results span.result-good {
					color: #0a0;
				}
				#nav li > .search_form > div.auto_complete {
					 position: absolute;
					 width: 169px;
					 background-color: white;
					 border: 1px solid #000;
					 margin: 0px;
					 padding: 0px;
					 float: left;
				}
				#nav li > .search_form > div.auto_complete > ul {
					position:absolute;
					width:169px;
					left:-1px;
					top:0px;
					list-style:none;
					padding:1px 0 0;
					margin:0;
					text-transform:none;
					display:block;
				}

				#nav li > .search_form > div.auto_complete > ul > li {
					background:url(../img/nav_back80.png) top left repeat;
					float:none;
					display:block;
					text-align:left;
					padding:5px 14px;
					width:141px;
					cursor:pointer;
					color:#fff;
				}

				#nav li > .search_form > div.auto_complete > ul > li.hover, #nav li > .search_form > div.auto_complete > ul > li:hover {
					background: url(../img/nav_back_grey.png) top left repeat;
					color:#fff; 
					text-align:left;
					cursor:pointer;
				}
			</style>
<body>
		<div id="container">
			<div id="header">
				<div id="lib_image">
					<?php
						if($imagePreview == null){
						?>
						 <div id="lib_imagetext"><label><?php echo $libraryName; ?></label></div>
						<?php
						}
						else{
						?>
						 <div id="lib_image">
						  <img src="<?php echo $imagePreview;?>" />
						 </div>
						 <div id="lib_imagetext">
						  <label><?php echo $libraryName; ?></label>
						 </div>
						<?php
						}
					?>	
				</div>
				<div id="lib_name"><?php echo $libraryName; ?></div>
				<div id="header_right">
					<ul>
						<li>
							Weekly Downloads <span id="downloads_used">0</span>/5				
							<img src="/img/question.png" alt="Download Limits" width="12" height="14" id="qtip" />&nbsp;|&nbsp;
							<a href="#">FAQ</a>&nbsp;|&nbsp;
							<a href="#">My Wishlist</a>&nbsp;|&nbsp;
							<a href="#">Recent Downloads</a>&nbsp;|&nbsp;					
							<a href="#">My Account</a>&nbsp;|&nbsp;
							<a href="#">Logout</a>				
						</li>
						<li><img src="/img/freegal_logo.png"></li>
					</ul>
				</div>
			</div>
			<div id="content">
				<div class="navigation">
					<ul class="menu" id="nav">
						<li class="parent item1"><a href="#">Home</a></li>
						<li class="parent item2"><a href="#"><span>Genre</span></a>
					<ul>
						<li class="parent item8"><a href="#">See All</a></li>
						<li class="parent item"><a href="#">Pop / Rock</a></li>
						<li class="parent item"><a href="#">Latin Rock</a></li>
						<li class="parent item"><a href="#">Classical</a></li>
						<li class="parent item"><a href="#">Children&#039;s Music</a></li>

						<li class="parent item"><a href="#">Blues</a></li>
						<li class="parent item"><a href="#">Alternative/Indie</a></li>
					</ul>
		</li>
		<li class="item3"><a href="#"><span>Featured Artist</span></a>
			<ul>
				<li class="parent item">
					<a href="#">John Mayer</a>
				</li>
			</ul>
		</li>
		<li class="item4"><a href="#"><span>Newly Added</span></a>
			<ul>
				<li class="parent item">
					<a href="#">Avril Lavigne</a>
				</li>
			</ul>
		</li>
		<li id="search">

			<!--<form name="search_form" method="post" action="homes/search" class="search_form">-->
			<form controller="Home" class="search_form" id="HomeSearchForm" method="get" action="/homes/search" accept-charset="utf-8"><div class="input text"><input name="search" type="text" size="24" id="autoComplete" value="" /></div>				<!-- <div style="float:left;">
			<input type="submit" class="searchButton" value=""></input>
			</div> -->
			<!-- </form>-->
			<div class="submit"><input type="submit" value="GO" /></div></form>			<a href="/homes/advance_search">Advanced Search</a>		</li>	
	</ul>
</div>
<div id="artist_slideshow">
	<div id="slideshow">
	</div>
</div>
<div id="ticker">
	Upcoming Releases
	<ul id="marquee" class="marquee">
</div>
<div id="suggestions">
    Suggestions
    <div id="suggestionsBox">

        <table cellspacing="0" cellpadding="0">
        		<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                    <td>
                        <p class='suggest_text'>
                            Test
                            <br />
                            by&nbsp;
                            <a href="#">Grupo Mania</a>                            
							 <img src="/img/play.png" alt="Play Sample" title="Play Sample" style="cursor:pointer;display:block;" id="play_audio0"  />                            
							 <img src="/img/ajax-loader.gif" alt="Loading Sample" title="Loading Sample" style="cursor:pointer;display:none;" id="load_audio0" />
							 <img src="/img/stop.png" alt="Stop Sample" title="Stop Sample" style="cursor:pointer;display:none;" id="stop_audio0" onClick="stopThis(this, &quot;0&quot;);" />                        </p>

                    </td>
                </tr>
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                    <td>
                        <p class='suggest_text'>
                           Test  
						   <br />
                            by&nbsp;
                            <a href="#">Grupo Mania</a>                            
							 <img src="/img/play.png" alt="Play Sample" title="Play Sample" style="cursor:pointer;display:block;" id="play_audio0"  />                            
							 <img src="/img/ajax-loader.gif" alt="Loading Sample" title="Loading Sample" style="cursor:pointer;display:none;" id="load_audio0" />
							 <img src="/img/stop.png" alt="Stop Sample" title="Stop Sample" style="cursor:pointer;display:none;" id="stop_audio0" onClick="stopThis(this, &quot;0&quot;);" />                        </p>

                    </td>
            </tr>
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                    <td>
                        <p class='suggest_text'>
                            Test 
							<br />
                            by&nbsp;
                            <a href="#">Grupo Mania</a>                            
							 <img src="/img/play.png" alt="Play Sample" title="Play Sample" style="cursor:pointer;display:block;" id="play_audio0"  />                            
							 <img src="/img/ajax-loader.gif" alt="Loading Sample" title="Loading Sample" style="cursor:pointer;display:none;" id="load_audio0" />
							 <img src="/img/stop.png" alt="Stop Sample" title="Stop Sample" style="cursor:pointer;display:none;" id="stop_audio0" onClick="stopThis(this, &quot;0&quot;);" />                        </p>

                    </td>
            </tr>
			<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                    <td>
                        <p class='suggest_text'>
                           Test
						   <br />
                            by&nbsp;
                            <a href="#">Grupo Mania</a>                            
							 <img src="/img/play.png" alt="Play Sample" title="Play Sample" style="cursor:pointer;display:block;" id="play_audio0"  />                            
							 <img src="/img/ajax-loader.gif" alt="Loading Sample" title="Loading Sample" style="cursor:pointer;display:none;" id="load_audio0" />
							 <img src="/img/stop.png" alt="Stop Sample" title="Stop Sample" style="cursor:pointer;display:none;" id="stop_audio0" onClick="stopThis(this, &quot;0&quot;);" />                        </p>

                    </td>
            </tr>
	        </table>
    </div>
</div>
<div id="artist_container">
    <div id="featured_artist">
            <a href="#"><img src="http://music.freegalmusic.com/freegalmusic/test/EN/featuredimg/FAjohn_mayer.jpg" alt="Featured Arstist" height="215" width="300" /></a></div>
    <div id="newly_added">
            <a href="#"><img src="http://music.freegalmusic.com/freegalmusic/test/EN/newartistimg/NAavril_lavigne.jpg" alt="Newly Added Artist" height="215" width="300" /></a></div>
    <div id="artist_search">
		<div id="artist_links">
		Artist Search&nbsp;&nbsp;
		<a href="#bottom" onclick="searchArtist('special')">#</a>&nbsp;
		<a href="#bottom" onclick="searchArtist('a')">A</a>&nbsp;
		<a href="#bottom" onclick="searchArtist('b')">B</a>&nbsp;

		<a href="#bottom" onclick="searchArtist('c')">C</a>&nbsp;
		<a href="#bottom" onclick="searchArtist('d')">D</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('e')">E</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('f')">F</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('g')">G</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('h')">H</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('i')">I</a>&nbsp;
        <a href="#" onclick="searchArtist('j')">J</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('k')">K</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('l')">L</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('m')">M</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('n')">N</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('o')">O</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('p')">P</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('q')">Q</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('r')">R</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('s')">S</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('t')">T</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('u')">U</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('v')">V</a>&nbsp;

        <a href="#bottom" onclick="searchArtist('w')">W</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('x')">X</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('y')">Y</a>&nbsp;
        <a href="#bottom" onclick="searchArtist('z')">Z</a>

		</div>
        <div id="artist_searchBox">
            <div class="scrollarea">
                    <table cellspacing="0" cellpadding="0">
							<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                                <td class='artist_line'>
                                    <p>
                                        <a href="#">A Knight&#039;s Tale (Motion Picture Soundtrack)</a>                                    
									</p>
                                </td>
                            </tr>
                                                    <tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                                <td class='artist_line'>
                                    <p>
                                        <a href="#">A Lot Like Love (Music From The Motion Picture)</a>                                    
									</p>
                                </td>
                            </tr>
							<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                                <td class='artist_line'>
                                    <p>
                                        <a href="#">A Mighty Wind (Motion Picture Soundtrack)</a>                                    
									</p>
                                </td>
                            </tr>							
                    </table>
            </div>
        </div>
    </div>
</div>		
</div>
		<br class="clr">
	</div>

	<div id="footer">
	<div id="copyright" style="float:left;">
		&copy; 2010 Library Ideas, LLC&nbsp;&nbsp;All Rights Reserved
	</div>	
	<a href="/homes/aboutus">About Freegal Music</a>	&nbsp;|&nbsp;
	<a href="/homes/terms">Terms &amp; Conditions</a>	&nbsp;|&nbsp;

	<a href="/questions">FAQ</a></div>
</body>
</html>