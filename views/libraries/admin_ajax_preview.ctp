<?php
/*
 File Name : admin_ajax_preview.ctp
 File Description : View page for ajax preview
 Author : m68interactive
 */	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	<title>
	Freegal Music : Your New Music Library :Preview</title>
<link href="/favicon.ico" type="image/x-icon" rel="icon" /><link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
<style>
@charset "UTF-8";

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
	background: <?php echo $library_bgcolor; ?>;
	margin: 0px;
	padding: 0px;
	text-align: center;
	color: <?php echo $library_text_color; ?>;
}

#container { 
	width: 965px;
	min-height: 500px;
	margin: 0 auto;
	text-align: left;
	background-color: <?php echo $library_content_bgcolor; ?>;
}

#header {  
	height:60px;
	background:#333 url(/img/header.png) no-repeat;
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
        color:<?php echo $library_links_color; ?>;
        text-decoration: none;
}

#header_right a:hover {
        text-decoration: underline;
        color:<?php echo $library_links_hover_color; ?>;
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
        background-color: <?php echo $library_content_bgcolor; ?>;
        border: 1px solid #999;
}

#ticker {
        width:939px;
        height:20px;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color: <?php echo $library_boxheader_text_color; ?>;
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
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        line-height:19px;
}

#suggestionsBox {
    width:305px;
        height:288px;
        padding-top:15px;
    text-align: left;
    background-color: <?php echo $library_content_bgcolor; ?>;
	color: <?php echo $library_links_color; ?>;
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
        color:<?php echo $library_links_color; ?>;
}

.suggest_text a:hover {
        text-decoration: none;
        color:<?php echo $library_links_hover_color; ?>;
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
        color:<?php echo $library_links_hover_color; ?>;
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
        background-color: <?php echo $library_content_bgcolor; ?>;
        border: 1px solid #999;
}

#newly_added {
        float:left;
        width:300px;
        height:215px;
        margin:0 0 10px 16px;
        background-color: <?php echo $library_content_bgcolor; ?>;
        border: 1px solid #999;
}

#artist_search {
        font-size:13px;
        clear:both;
        width:620px;
        height:35px;
        text-align:center;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        margin: 0 0 0 16px;
        display:block;
}

#artist_search a {
        text-decoration: none;
        color:<?php echo $library_links_color; ?>;
}

#artist_search a:hover {
/*		text-decoration: underline;*/
        color:<?php echo $library_links_hover_color; ?>;
}
#artist_links a {
        text-decoration: none;
        color:<?php echo $library_box_header_color; ?>;
}

#artist_links a:hover {
        color:<?php echo $library_box_hover_color; ?>;
}
.links a {
        text-decoration: none;
        color:<?php echo $library_box_header_color; ?> !important;
}

.links a:hover {
        color:<?php echo $library_box_hover_color; ?> !important;
}

.download_links a {
        text-decoration: none;
        color:<?php echo $library_links_color; ?> !important;
}

.download_links a:hover {
        color:<?php echo $library_links_hover_color; ?> !important;
}
#genre_artist_search {
        font-size:13px;
        clear:both;
        width:945px;
        height:20px;
        text-align:center;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        margin:10px 10px 0;
        display:block;
}

#genre_artist_search a {
        text-decoration: none;
        color:<?php echo $library_box_header_color; ?>;
}

#genre_artist_search a:hover {
        color:<?php echo $library_box_hover_color; ?>;
}

#artist_searchBox {
    background-color: <?php echo $library_content_bgcolor; ?>;
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
	text-decoration:none;
	color:<?php echo $library_navlinks_color; ?>;
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
	color:<?php echo $library_navlinks_hover_color; ?>;
	text-decoration: underline;
}

#nav li li{
	float:none;
	padding:0 0 0px;
	display:block;
}

#nav li.hover li a, #nav li:hover li a {
        background:<?php echo $library_nav_bgcolor; ?>;
        opacity: .9; filter: alpha(opacity=90); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=90)";
	color:<?php echo $library_navlinks_color; ?>;
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
        opacity: .5; filter: alpha(opacity=50); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        color:<?php echo $library_navlinks_hover_color; ?>;
}

.navigation{
	width:965px;
	float:left;
	background:<?php echo $library_nav_bgcolor; ?>;
}

#search {
	color:#fff;
	background:<?php echo $library_nav_bgcolor; ?>;
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
	background:<?php echo $library_nav_bgcolor; ?>;
	padding: 2px 0 0 2px;
	height:18px;
}

/* Genre Page */
#genre {
	float:left;
	font-size:13px;
	width:200px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 10px;
	line-height:20px;
}

#genreArtist {
	clear:both;
	float:left;
	width: 180px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 10px;
	line-height: 20px;
	font-size:13px;
}

#genreArtist a {
	text-decoration: none;
	color: <?php echo $library_box_header_color; ?>;
}
#genreComposer {
	float:left;
	width: 180px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 3px;
	line-height: 20px;
	font-size:13px;
}

#genreComposer a {
	text-decoration: none;
	color: <?php echo $library_box_header_color; ?>;
}
#genreAlbum {
	float:left;
	width: 180px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 3px;
	line-height: 20px;
	font-size:13px;
}

#genreAlbum a {
	text-decoration: none;
	color: <?php echo $library_box_header_color; ?>;
}

#genreTrack {
	float:left;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 3px;
	line-height: 20px;
	font-size:13px;
}

#genreTrack a {
	text-decoration: none;
	color: <?php echo $library_box_header_color; ?>;
}

#genreDownload {
	float:left;
	width: 109px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
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
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
    color:<?php echo $library_boxheader_text_color; ?>;
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
        color:<?php echo $library_links_color; ?>;
}

#aboutUs a:hover {
        text-decoration: none;
        color:<?php echo $library_links_hover_color; ?>;
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
        color:<?php echo $library_links_color; ?>;
}

#terms a:hover {
        text-decoration: none;
        color:<?php echo $library_links_hover_color; ?>;
}

.error_div {
        margin:10px;
	font-size:13px;
        text-align:center;
        height:20px;
}

#genreResults {
	margin-left:10px;
	margin-bottom:10px;
	font-size:13px;
	min-height:375px;
}

#genreResults a {
	text-decoration: none;
	color: <?php echo $library_links_color; ?>;
}

#genreResults a:hover {
	text-decoration: none;
	color:<?php echo $library_links_hover_color; ?>;
}
#genreResults tr {
	display: block;
	margin-left: 10px;
}

#genreResults td {
//	border-left: 1px solid #FFF;
//	border-right: 1px solid #fff;
//	border-bottom: 1px solid #E1E8EB;
	padding: 5px 0 5px 0;
}

#genreResults img {
	float:right;
	margin-right:10px;
}

#genreViewAllBox {
	float:left;
	width: 200px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 3px;
	line-height: 20px;
	font-size:13px;
}

.info {
	position:relative;
	z-index:24;
	text-decoration: none;
}

.info:hover {
	z-index:25;
}
.info span {
	display:none;
}

.info:hover span {
	display:block;
	position:absolute;
	top: 25px;
	left: 25px;
	width:300px;
	border: 1px solid #CCC;
	background: #FFFDC9;
	color:<?php echo $library_links_color; ?>;
	text-align: center;
	font-weight: normal;
}

#genreAdvSearch {
	float:right;
	font-size:13px;
	margin:0 10px 10px 0;
	width:420px;
}

#genreAdvSearch a {
	text-decoration: none;
	color:<?php echo $library_links_color; ?>;
}

#genreAdvSearch a:hover {
	text-decoration: underline;
}

.altrow {
	background-color: #F3F3F3;
}

#genreAll {
	font-size: 13px;
	line-height: 20px;
}

.genretl {
	float:left;
        min-height: 300px;
}

.genreAlltl {
	width:460px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	margin: 10px 0 0 10px;
}

#genreAllSongtl {
	width:460px;
	margin: 0 0 0 10px;
}

.genretr {
	float:right;
        min-height: 300px;
}

.genreAlltr {
	width:460px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	margin: 10px 10px 0 0;
}

#genreAllSongtr {
	width:460px;
}

#genrebl {
	float:left;
}

#genreAllbl {
	width:460px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	margin: 10px 0 0 10px;
}

#genreAllSongbl {
	width:460px;
	margin: 0 0 0 10px;
}

#genrebr {
	float:right;
}

#genreAllbr {
	width:460px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	margin: 10px 10px 0 0;
}

#genreAllSongbr {
	width:460px;
}

.genreTitle {
	margin-left:10px;
}

.genreSeeAll {
	float:right;
	margin-right: 40px;
	color:<?php echo $library_boxheader_text_color; ?>;
}

.genreSeeAll a {
	text-decoration: none;
	color:<?php echo $library_box_header_color; ?>;
}

.genreSeeAll a:hover {
	text-decoration: underline;
	color:<?php echo $library_box_hover_color; ?>;
}

#genreViewAll {
	margin: 10px 10px 0 10px;
	font-size:13px;
}

#genreViewAll a {
	text-decoration: none;
	color:<?php echo $library_links_color; ?>;
}

#genreViewAll a:hover {
	text-decoration: underline;
	color:<?php echo $library_links_hover_color; ?>;
}

.smAlbumArtwork {
	display:block;
	float:left;
	margin:10px 7px 0 10px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	width:60px;
	height:60px;
}

.songData {
	float:left;
	width:235px;
	margin:10px 0 0 5px;
}

.songSample img {
	float:left;
	margin-top:12px;
}

.songDownload {
	float:right;
	margin:10px 10px 0 0;
}

.songData a, .songDownload a {
	text-decoration: none;
	color:<?php echo $library_links_color; ?>;
}

.songData a:hover, .songDownload a:hover {
	text-decoration: underline;
	color:<?php echo $library_links_hover_color; ?>;
}

/* Artist Page */

#artistBox {
	float:left;
	font-size:13px;
	width:250px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 10px 10px;
	line-height:20px;
}

#album {
	clear:both;
	font-size:13px;
	padding-bottom: 25px;
	padding-top: 10px;
}

.lgAlbumArtwork {
        float:left;
        margin: 0 0 10px 10px;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        width:250px; /* to be removed */
        height:250px; /* to be removed */
}

.albumData {
        float:left;
}

.albumBox {
        width:400px;
        height: 20px;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        text-align: center;
        margin: 0 0 5px 10px;
        line-height:20px;
}

.songBox {
        width:685px;
        height: 20px;
        background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
        text-align: center;
        margin: 0 0 0 10px;
        line-height:20px;
}

#songResults {
        text-align: left;
        margin-left:10px;
		float:left;
		width:695px;
}

#songResults a {
        text-decoration: none;
        color:<?php echo $library_links_color; ?>;
}

#songResults a:hover {
        text-decoration: underline;
        color:<?php echo $library_links_hover_color; ?>;
}

#songResults img {
        margin-top:2px;
}

.songHeader {
        float:left;
        width:360px;
}

.artistHeader {
        float:left;
        width:125px;
}

.timeHeader {
        float:left;
        width:50px;
}

.downloadHeader {
        float:left;
        width:150px;
}

.artistInfo {
        margin:5px 0 10px 12px;
        width:685px;
    text-align: left;
        font-size:13px;
}

.artistInfo a {
        text-decoration: none;
        color: <?php echo $library_links_color; ?>;
}

.artistInfo a:hover {
        text-decoration: underline;
        color: <?php echo $library_links_hover_color; ?>;
}
		
.explicit {
	color: red;
	display: inline;
}
/** FAQs **/
.questions h2 {
	margin: 0 0 0 10px;
}
.question_list {
margin: 10px 10px 0 10px;
padding: 0px;
width: 875px;
font-size: 13px;
color:<?php echo $library_text_color; ?>;
}
.question {
padding: 5px 10px;
cursor: pointer;
position: relative;
//background-color:#FFCCCC;
margin:1px 0 0 100px;
font-size: 13px;
}
.answer {
padding: 5px 10px 5px;
background-color:<?php echo $library_boxheader_bgcolor; ?>;
color:<?php echo $library_boxheader_text_color; ?>;
//background-color:#d8d8d8;
margin-left: 100px;
font-size: 13px;
display:none;
}
.answer a {
	text-decoration: none;
    color: <?php echo $library_box_header_color; ?>;
}
.answer a:hover {
        text-decoration: underline;
        color: <?php echo $library_box_hover_color; ?>;
}

.answer p {
	margin-bottom: 10px;
	font-size: 13px;
}

/** Advance Search **/
#advance_search {
	min-height:400px;
	margin: 100px 0 0 340px;
}

#advance_search_box {
	float:left;
	font-size:13px;
	width:200px;
	height: 20px;
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
        color:<?php echo $library_boxheader_text_color; ?>;
	text-align: center;
	margin: 10px 0 0 10px;
	line-height:20px;
}

#advance_search form label {
	clear:both;
	float: left;
	width:100px;
	display: block;
	margin-bottom:3px;
}
#advance_search form .input {
	vertical-align: text-bottom;
	padding: 1%;
	margin-bottom: 10px;
}
#advance_search form select {
	vertical-align: text-bottom;
}
#advance_search form div.submit {
	border: 0;
	clear: both;
	margin-top: 10px;
}

/** Paging **/
div.paging {
	background:<?php echo $library_content_bgcolor; ?>;
        color:<?php echo $library_text_color; ?>;
	margin-left:10px;
	clear:both;
	float:left;
	font-size:13px;
	width:500px;
	
}
div.paging span.disabled {
	color: #ddd;
	display: inline;
}
div.paging span.current {
	color: #000;
}
div.paging span a, div.paging a {
	text-decoration: none;
	color:<?php echo $library_links_color; ?>;
}

div.paging span a:hover, div.paging a:hover {
	text-decoration: underline;
	color:<?php echo $library_links_hover_color; ?>;
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
							<img src="/img/question.png" alt="Download Limits" width="12" height="14" id="qtip" />|
							<a href="#">FAQ</a>|
							<a href="#">My Wishlist</a>|
							<a href="#">Recent Downloads</a>|					
							<a href="#">My Account</a>|
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

			<form controller="Home" class="search_form" id="HomeSearchForm" method="get"  accept-charset="utf-8"><div class="input text"><input name="search" type="text" size="24" id="autoComplete" value="" /></div>
			<div class="submit"><input type="submit" value="GO" /></div></form>			<a>Advanced Search</a>		</li>	
	</ul>
</div>
<div id="artist_slideshow">
	<div id="slideshow">
		<img src="http://music.freegalmusic.com/freegalmusic/test/EN/artistimg/SAsantana2.jpg" alt="Santana" title="Santana" height="215" width="942" />
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
                            by
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
                            by
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
                            by
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
                            by
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
		Artist Search
		<a href="#bottom">#</a>
		<a href="#bottom">A</a>
		<a href="#bottom">B</a>
		<a href="#bottom">C</a>
		<a href="#bottom">D</a>

        <a href="#bottom">E</a>
        <a href="#bottom">F</a>
        <a href="#bottom">G</a>
        <a href="#bottom">H</a>

        <a href="#bottom">I</a>
        <a href="#">J</a>
        <a href="#bottom">K</a>
        <a href="#bottom">L</a>
        <a href="#bottom">M</a>
        <a href="#bottom">N</a>

        <a href="#bottom">O</a>
        <a href="#bottom">P</a>

        <a href="#bottom">Q</a>
        <a href="#bottom">R</a>
        <a href="#bottom">S</a>
        <a href="#bottom">T</a>

        <a href="#bottom">U</a>
        <a href="#bottom">V</a>

        <a href="#bottom">W</a>
        <a href="#bottom">X</a>
        <a href="#bottom">Y</a>
        <a href="#bottom">Z</a>

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
		&copy; 2010 Library Ideas, LLCAll Rights Reserved
	</div>	
	<a>About Freegal Music</a>	|
	<a>Terms &amp; Conditions</a>	|

	<a>FAQ</a></div>
</body>
</html>