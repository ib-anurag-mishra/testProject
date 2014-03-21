<head>
<style>
/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}


.wrapper {
	
	background: url(../img/player_skin/player-repeat-bkg.png) repeat-x;
	height: 102px;
	overflow: hidden;
}
.player-container {
	
	
	
	margin:10px auto;
	width: 974px;
	height: 85px;
	
	background: url(../img/player_skin/player-bkg-with-border.png);
	position: relative;
	overflow: hidden;


}



#myElement_wrapper {
	

	margin-left: 7px;
	margin-top: 7px;


}


</style>

        <?php echo $this->Html->script('jwplayer'); ?>
	<script type="text/javascript">jwplayer.key="pTfXPXvxG6Y+nMaoNAYFJkTtB3C/SseoP6V8XA==";</script>
</head>

<body>
	<div class="wrapper">
		<div class="player-container">
			<div id="myElement">Loading the player...</div>
		</div>
	</div>
	<script type="text/javascript">
	jwplayer("myElement").setup({
	    playlist:[{
		    
		    file:"DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3",
		    title:"Get Lucky",
		    description:"Daft Punk"
	    },{
		    
		    file:"JustinTimberlake_Mirrors_G0100029371261_1_1-256K_44S_2C_cbr1x.mp3",
		    title:"Mirrors",
		    description:"Justin Timberlake"		    
	    },{
		    
		   	file:"Journey_DontStopBelievin_G0100027183574_1_2-256K_44S_2C_cbr1x.mp3",
		    title:"Don't Stop Believin"	,
		    description:"Journey"
	    }],
	    
	    height: 70,
	    width: 960,
	    primary: "flash",
	    skin: "../img/player_skin/freegal-custom-skin.xml",
		listbar: {
			
			position:"right",
			size:150
		},
	});
	</script>
</body>