				</div> <!-- end .content -->

			
			</div><!-- end .content-wrapper -->

<?php /*if($this->Session->read("patron")){ ?>
                        <?php if( $this->Session->read('library_type') == 2 ){ ?>
			<div class="music-player-container clearfix">

					<div class="music-player">
						<div class="player-mgmt-container">
							<button class="min-max" type="button"></button>
							
							
						</div>
						
						<div class="album-cover-art">
							<img src="<? echo $this->webroot; ?>app/webroot/img/music_player/album_cover_art.png" alt="album_cover_art" width="69" height="69">
						</div>
						<div class="album-title">
							4 - <span class="artist">Beyonce</span>
						</div>
						<audio class="fmp">  
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Journey_DontStopBelievin_G0100027183574_1_2-256K_44S_2C_cbr1x.mp3" />
<!--	   <source src="<? echo $this->webroot; ?>app/webroot/media/PnkFeaturingNateRues_JustGiveMeAReason_G010002829359t_1_4-256K_44S_2C_cbr1x.mp3" />
   <source src="<? echo $this->webroot; ?>app/webroot/media/SaraBareilles_Brave_G010002970060q_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Train_DropsOfJupiter_G010000669385g_1_3-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/WalkOffTheEarth_SomebodyThatIUsedToK_G010002768367n_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/MumfordSons_TheCave_G0100019146910_1_2-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/JustinTimberlake_Mirrors_G0100029371261_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/JustinTimberlake_SuitTieFeaturingJayZ_G010002929236q_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Adele_Skyfall_G010002882136i_1_1-256K_44S_2C_cbr1x.mp3" />  -->
 
						</audio>
						
						
					</div>

			</div>
			
<?php } }*/?>
<style>
.player {
	
	position: fixed;
	bottom: 0;
	width: 100%;
	height: 100px;
	overflow: hidden;

	
}
</style>
			
		</div>			
			
			<footer class="site-footer">
				<div class="footer-content">
					<div class="legal">
						&copy; 2013 Library Ideas, LLC  All Rights Reserved
					</div>
					<nav class="footer-nav">
					<ul class="clearfix">
		<li><?php echo $html->link(__('Home', true), array('controller' => 'homes','action'=>'index'));?></li>
		<li><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index')); ?></li>
		<li><?php echo $html->link(__('Most Popular', true), array('controller' => 'homes', 'action' => 'us_top_10')); ?></li>
		<li><?php echo $html->link(__('New Releases', true), array('controller' => 'homes', 'action' => 'new_releases')); ?></li>
		<li><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' => 'view')); ?></li>
		<li><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?></li>
					</ul>
					</nav>
					<div class="languages">
						<?php $this->getTextEncode(__('Also available in')); ?>&nbsp;
                                                <?php
                                                if($language){
                                                $language = $language->getLanguage();
                                                $i =1;
                                                foreach($language as $k => $v){
                                                        echo '<a style="color: #A1A7AE;padding-left:10px;padding-right:10px;" href="javascript:void(0)" id='.$k.' onClick="changeLang('.$k.');">';?><?php echo $this->getTextEncode($v); ?><?php echo '</a> ';
                                                        if($i > 0 && $i < count($language)){
                                                                echo "| ";
                                                        }
                                                        $i++;
                                                }
                                                }
                                                ?>
					</div>
				</div>
			</footer>
                <div class="filler" style="height:100px"></div>
	<?php //if($this->Session->read("patron")){ ?>
                                        <?php //if($this->Session->read('library_type') == '2') { ?>
                                            <!-- <div class="player">
                                                    <div class="player-container">
                                                            <div id="myElement">Loading the player...</div>
                                                    </div>
                                                <input type="hidden" name="songDetails" id="songDetails" value="" />
                                            </div> -->
                                            <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/js/swfobject.js"></script>
                                            <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/js/streaming.js"></script>
                                            <div class="player-wrapper">
                                              <div class="fmp_container">
                                                <object width="960" height="100" type="application/x-shockwave-flash" id="fmp_player" data="/app/webroot/swf/fmp.swf"></object>
                                              </div>

                                              <input type="button" id="playlist1" value="Push Playlist 1" style="display:block" onclick="pushSongs(popMostPopular);" />
                                              <input type="button" id="playlist2" value="Push Playlist 2" style="display:block" onclick="pushSongs(MechanicalBull);" />
                                              <input type="button" id="pushNewSong" value="Push New Song" style="display:block"  />
                                              <input type="button" id="clearQueue" value="Clear Queue" style="display:block" onclick="clearQueue();" />
                                            </div>
                                        <?php //} ?>
                                    <?php //} ?>
	
    <script src="<? echo $this->webroot; ?>app/webroot/js/lazyload.js"></script>
    <script src="<? echo $this->webroot; ?>app/webroot/js/site.js"></script>
    
    <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-16162084-1");
pageTracker._trackPageview();
} catch(err) {}</script>


<!-- Code for player -->
<!-- History.js -->
	<script src="<? echo $this->webroot; ?>app/webroot/js/jquery.history.js"></script>
	
	<!-- Ajaxify -->
	<script src="<? echo $this->webroot; ?>app/webroot/js/ajaxify-html5.js"></script>

        <?php //if ($this->Session->read('patron') && $this->Session->read('library_type') == 2){ ?>
	
  
	<!-- <script type="text/javascript" src="<? echo $this->webroot; ?>app/webroot/js/jwplayer.js"></script> -->
	<!-- <script type="text/javascript">jwplayer.key="pTfXPXvxG6Y+nMaoNAYFJkTtB3C/SseoP6V8XA==";</script> -->
	<!-- <script type="text/javascript">
            $(document).ready(function(){
                jwplayer("myElement").setup({
                        playlist:[{file:"rtmpe://streaming.libraryideas.com/libraryideas/mp3:000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?nvb=20130902132618&nva=20130902142618&token=5219efa7418cbf18c81fe",
		    title:"Get Lucky",
		    description:"Daft Punk"}],
                        height: 70,
                        width: 960,
                        primary: "flash",
                        skin: "/img/player_skin/freegal-custom-skin.xml",
                            listbar: {

                                    position:"right",
                                    size:150
                            },
                        events: {
                        onPlaylistItem: function(event) {
                        var currentItem = jwplayer("myElement").getPlaylistIndex();
                        if(currentItem != 0){
                            
                            var item = $('#play_item_'+currentItem).text();
                            if(item.length){
                                var songData = item.split(',');
                                var prodId = songData[0];
                                var providerType = songData[1];
                            }
                            var queueId = $('#hid_Plid').val();
                            var postURL = webroot+'queuelistdetails/getPlaylistData';
                            $.ajax({
                                type: "POST",
                                cache:false,
                                url: postURL,
                                data: {prodId : prodId,providerType : providerType, queueId : queueId}
                            }).done(function(data){
                                    var json = JSON.parse(data);
                                    if(json.error){
                                        alert(json.error[1]);
										if(json.error[1] != 6 ){
											jwplayer().remove();
										}
                                    }else if(json.success){
                                    }
                            })
                            .fail(function(){
                                alert('Ajax Call to Validate NextPlaylistItem has been failed');
                            });                              
                        }
                        },
                        onDisplayClick:function(){
                            if(jwplayer().getState() == 'BUFFERING'){
                                if(jwplayer().getPlaylist().length != 1){
                                    
                                    var item = $('#play_item_1').text();
                                    if(item.length){
                                        var songData = item.split(',');
                                        var prodId = songData[0];
                                        var providerType = songData[1];
                                    }
                                    var queueId = $('#hid_Plid').val();
                                    var postURL = webroot+'queuelistdetails/getPlaylistData';
                                    $.ajax({
                                        type: "POST",
                                        cache:false,
                                        url: postURL,
                                        data: {prodId : prodId,providerType : providerType, queueId : queueId}
                                    }).done(function(data){
                                            var json = JSON.parse(data);
                                            if(json.error){
                                                alert(json.error[1]);
												if(json.error[3] != 6){
													jwplayer().remove();
												}
                                            }else if(json.success){
                                            }
                                    })
                                    .fail(function(){
                                        alert('Ajax Call to Validate replay playlist has been failed');
                                    });                                      
                                }else{
                                    var postURL = webroot+'queuelistdetails/getPlaylistData';
                                    $songDetails = $('#songDetails').val().split('-');
                                    prodId = $songDetails[0];
                                    providerType = $songDetails[1];
                                    $.ajax({
                                        type: "POST",
                                        cache:false,
                                        url: postURL,
                                        data: {prodId : prodId,providerType : providerType}
                                    }).done(function(data){
                                            var json = JSON.parse(data);
                                            if(json.error){
                                                var result = json.error;
                                                alert(result[1]);
												if(result[3]!= 6){
													jwplayer().remove();
												}	
                                            }else if(json.success){
                                                jwplayer("myElement").play(true);
                                            }
                                    })
                                    .fail(function(){
                                        alert('Ajax Call to Validate song has been failed');
                                    });                                     
                                }
                              }
                           },
                           onPlaylistComplete:function(){
                                var postURL = webroot+'queuelistdetails/clearNowStreamingSession';
                                $.ajax({
                                    type: "POST",
                                    cache:false,
                                    url: postURL
                                }).done(function(data){

                                })
                                .fail(function(){
                                    alert('Ajax Call to clear now streaming session has been failed');
                                });                                
                                
                           }                          
                        },
                        repeat: false   
                    });
//                $('.play-queue-btn').click(function(){
//                    var files = [{"file":"rtmpe://streaming.libraryideas.com/libraryideas/mp3:000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?nvb=20130902132618&nva=20130902142618&token=5219efa7418cbf18c81fe","title":"Get funky","description":"Daft Punk"},{"file":"rtmpe://streaming.libraryideas.com/libraryideas/mp3:000/000/000/000/278/177/55/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3?nvb=20130902132618&nva=20130902142618&token=5219efa7418cbf18c81fe","title":"Get Lucky1","description":"Daft Punk1"}];                
//                    jwplayer("myElement").load(files); 
//                });
            });
        </script>    -->
<?php //} ?>
<!-- Code for player end -->

