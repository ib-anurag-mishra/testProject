				</div> <!-- end .content -->

			
			</div><!-- end .content-wrapper -->

<?php if($this->Session->read("patron")){ ?>
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
						   <source src="<? echo $this->webroot; ?>app/webroot/media/PnkFeaturingNateRues_JustGiveMeAReason_G010002829359t_1_4-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Journey_DontStopBelievin_G0100027183574_1_2-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/SaraBareilles_Brave_G010002970060q_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Train_DropsOfJupiter_G010000669385g_1_3-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/WalkOffTheEarth_SomebodyThatIUsedToK_G010002768367n_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/MumfordSons_TheCave_G0100019146910_1_2-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/DaftPunkFeatPharrell_GetLucky_G0100029758145_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/JustinTimberlake_Mirrors_G0100029371261_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/JustinTimberlake_SuitTieFeaturingJayZ_G010002929236q_1_1-256K_44S_2C_cbr1x.mp3" />
						   <source src="<? echo $this->webroot; ?>app/webroot/media/Adele_Skyfall_G010002882136i_1_1-256K_44S_2C_cbr1x.mp3" />  
 
						</audio>
						
						
					</div>

			</div>
			
<?php } }?>
			
		</div>			
			
			<footer class="site-footer">
				<div class="footer-content">
					<div class="legal">
						&copy; 2013 Library Ideas, LLC  All Rights Reserved
					</div>
					<nav class="footer-nav">
					<ul class="clearfix">
		<li><?php echo $html->link(__('News', true), array('controller' => 'homes','action'=>'index'));?></li>
		<li><?php echo $html->link(__('Music Videos', true), array('controller' => 'videos', 'action' => 'index')); ?></li>
		<li><?php echo $html->link(__('Most Popular', true), array('controller' => 'mostpopular', 'action' => 'view')); ?></li>
		<li><?php echo $html->link(__('New Releases', true), array('controller' => 'newreleases', 'action' => 'view')); ?></li>
		<li><?php echo $html->link(__('Genres', true), array('controller' => 'genres', 'action' => 'view')); ?></li>
		<li><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?></li>
					</ul>
					</nav>
					<div class="languages">
						<ul class="clearfix">
							<li><span>Also available in</span></li>
							<li><a href="#">English</a></li>
							<li><a href="#">Espa&ntilde;ol</a></li>
							<li><a href="#">Fran&ccedil;ais</a></li>
							<li><a href="#">Italiano</a></li>

						</ul>
					</div>
				</div>
			</footer>
	</body>
	

<script src="<? echo $this->webroot; ?>app/webroot/js/lazyload.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/site.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/us-top-10.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/my-top-10.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/site-login.js"></script>

<script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mediaelement-and-player.min.js"></script>
<script src="<? echo $this->webroot; ?>app/webroot/js/mediaelement/mep-feature-playlist-custom.js"></script>


<script src="<? echo $this->webroot; ?>app/webroot/js/my-wishlist.js"></script>

	
	
</html>