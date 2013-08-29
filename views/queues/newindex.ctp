<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<style>
/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

.wrapper {
	

	width: 100%;
}

iframe {
	
	display: block;
}

.site {
	
	width: 100%;
	margin: 0 auto;
	min-height: 1500px;
	overflow: hidden;
}

.player {
	
	position: fixed;
	bottom: 0;
	width: 100%;
	height: 100px;
	overflow: hidden;

	
}
			
		</style>
	</head>
		
	<body>
		<div class="wrapper">
			<iframe class="site" src="/index"></iframe>
                                <?php if($this->Session->read("patron")){ ?>
                                        <?php if($this->Session->read('library_type') == '2') { ?>
                                            <div id="player-wrapper" onload="loadIframe('jwplayer','')">
                                                <iframe id="jwplayer" class="player" src="/queues/playqueue"></iframe>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
					
		</div>
	</body>
		

</html>