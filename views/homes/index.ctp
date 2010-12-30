<?php echo $javascript->link('jquery.marquee.min'); ?>
<?php echo $javascript->link('freegal_home_curvy'); ?>
<?php echo $javascript->link('jquery.corner'); ?>
<?php echo $javascript->link('freegal.home.musicbox.js'); ?>
<script type="text/javascript">
	$(document).ready(function() {
		if ($.browser.msie) {
			$('#t1').corner("top 5px").parent().css({'padding-left' : '1px', 'padding-right' : '1px', 'padding-top' : '1px'}).corner("top 5px")
			$('#t2').corner("top 5px").parent().css({'padding-left' : '1px', 'padding-right' : '1px', 'padding-top' : '1px'}).corner("top 5px")
		} else {
			$('#tb1').corner('top 5px');
			$('#tb2').corner('top 5px');
		}
	});
</script>
<div id="artist_slideshow">
	<div id="slideshow">
	<?php
		foreach($artists as $key => $artist):
				if($artist['Artist']['territory'] == $this->Session->read('territory') && $artist['Artist']['language'] == Configure::read('App.LANGUAGE')){
                    if($key == 0) {
                        echo $html->link(
                            $html->image($cdnPath.'artistimg/'.$artist['Artist']['artist_image'], array("alt" => $artist['Artist']['artist_name'], "title" => $artist['Artist']['artist_name'], "height" => "215", "width" => "942")),
                            array('controller'=>'artists', 'action'=>'view', base64_encode($artist['Artist']['artist_name'])),
                            array('class'=>'first','escape'=>false)
                        );
                    }
                    else {
                        echo $html->link(
                            $html->image($cdnPath.'artistimg/'.$artist['Artist']['artist_image'], array("alt" => $artist['Artist']['artist_name'], "title" => $artist['Artist']['artist_name'], "height" => "215", "width" => "942")),
                            array('controller'=>'artists', 'action'=>'view', base64_encode($artist['Artist']['artist_name'])),
                            array('escape'=>false)
                        );
                    }
				}
		endforeach; 
	?>					
	</div>
</div>
<div id="ticker">
	Upcoming Releases
	<ul id="marquee" class="marquee">
		<?php 
		foreach($upcoming as $newreleases):
			if($newreleases['Country']['SalesDate']){
				echo '<li>Coming ' . date("F d", strtotime($newreleases['Country']['SalesDate'])) . ' ' . $newreleases['Album']['ArtistText'] . ' - ' . $newreleases['Album']['AlbumTitle'] . '</li>';
			} else {
				echo '<li>Coming ' . $newreleases['Album']['ArtistText'] . ' - ' . $newreleases['Album']['AlbumTitle'] . '</li>';
			}
		endforeach;
		?>
	</ul>
</div>
<div id="sug" class="suggestions">
	<div id="tabsugg">
		<div id="tb1"><div id="t1" class="active"><a href="javascript:filterTD('tab1');"><?php echo (__('MyLib Top 10', true));?></a></div></div>
		<div id="tb2"><div id="t2" class="nonactive"><a href="javascript:filterTD('tab2');"><?php echo (__('National Top 10', true));?></a></div></div>
	</div>
	<div id="sugtab" class="tab_container">
		<div id="tab1" class="tab_content" style="display:block;">
			<?php if(count($songs) > 0){ ?>
			<table cellspacing="0" cellpadding="0" id="musicbox">
			<?php
				$j =0;
				for($i = 0; $i < count($songs); $i++) {
				if($j==10){
					break;
				}
			?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'>
							<?php
							$slNo = ($i + 1);
							echo $slNo.". ";
							if (strlen($songs[$i]['Song']['SongTitle']) >= 28 ) {
								echo '<span title="'.$songs[$i]['Song']['SongTitle'].'">' . substr($songs[$i]['Song']['SongTitle'], 0, 28) . "..." . "</span>";
							} else {
								echo $songs[$i]['Song']['SongTitle'];
							}
							?>
							<br />
							by&nbsp;
							<?php
							if (strlen($songs[$i]['Song']['Artist']) >= 24 ) {
									echo '<span title="'.$songs[$i]['Song']['Artist'].'">' . $html->link(substr($songs[$i]['Song']['Artist'], 0, 24) . "...", array(
									'controller' => 'artists',
									'action' => 'view',base64_encode($songs[$i]['Song']['ArtistText']),$songs[$i]['Song']['ReferenceID']
									)
								) . "</span>";
							} else {
								echo $html->link($songs[$i]['Song']['Artist'], array(
									'controller' => 'artists',
									'action' => 'view',base64_encode($songs[$i]['Song']['ArtistText']),$songs[$i]['Song']['ReferenceID']
									)
								);
							}
							$songUrl = shell_exec('perl files/tokengen ' . $songs[$i]['Sample_Files']['CdnPath']."/".$songs[$i]['Sample_Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							?>
							<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$songs[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
							<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); ?>
							<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); ?>
							<?php
							echo "<br/>";
							if($songs[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
								if($libraryDownload == '1' && $patronDownload == '1') {	
									if($songs[$i]['Song']['status'] != 'avail') {
										$songUrl = shell_exec('perl files/tokengen ' . $songs[$i]['Full_Files']['CdnPath']."/".$songs[$i]['Full_Files']['SaveAsName']);
										$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
										$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
										?>
										<span class="beforeClick" id="song_<?php echo $songs[$i]["Song"]["ProdID"]; ?>">
										<![if !IE]>
										<a href='#' onclick='return userDownloadOthers("<?php echo $songs[$i]["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><label class="dload" style="width:120px;cursor:pointer;" title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.'>Download Now</label></a>
										<![endif]>
										<!--[if IE]>
										<a style="cursor:pointer;" onclick='return userDownloadIE("<?php echo $songs[$i]["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'><label class="dload" style="width:120px;" title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.'>Download Now</label></a>
										<![endif]-->
										</span>
										<span class="afterClick" id="downloading_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;">Please Wait...</span>
										<span id="download_loader_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
										<?php	
									} else {
									?>
										<a href='/homes/my_history' title='You have already downloaded this song. Get it from your recent downloads'>Downloaded</a>
									<?php
									}
								} else {
									if($libraryDownload != '1') {
										$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
										$wishlistCount = $wishlist->getWishlistCount();
										if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
										?> 
											Limit Exceeded 
										<?php
										} else {
											$wishlistInfo = $wishlist->getWishlistData($songs[$i]["Song"]["ProdID"]);
											if($wishlistInfo == 'Added to Wishlist') {
											?> 
												Added to Wishlist
											<?php 
											} else { 
											?>
												<span class="beforeClick" id="wishlist<?php echo $songs[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $songs[$i]["Song"]["ProdID"]; ?>",this);'>Add to Wishlist</a></span><span id="wishlist_loader_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
												<span class="afterClick" id="downloading_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;">Please Wait...</span>
											<?php	
											}
										}

									} else { 
									?>
										Limit Exceeded
									<?php	
									}												
								}
							} else {
							?>
								<span title='Coming Soon ( <?php if(isset($songs[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($songs[$i]['Country']['SalesDate']));} ?> )'>Coming Soon</span>
							<?php
							}
							?>
						</p>
					</td>
				</tr>
			<?php 
				$j++;
			} 
			?>
			</table>
			<?php } ?>
		</div>
		<div id="tab2" class="tab_content" style="display:none;">
			<table cellspacing="0" cellpadding="0" id="musicbox">
			<?php
				$j =0;
				for($i = 0; $i < count($nationalTopDownload); $i++) {
				$newCount = ($i + 10);
				if($j==10){
					break;
				}
			?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'>
							<?php
							$slNo = ($i + 1);
							echo $slNo.". ";
							if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 28 ) {
								echo '<span title="'.$nationalTopDownload[$i]['Song']['SongTitle'].'">' . substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 28) . "..." . "</span>";
							} else {
								echo $nationalTopDownload[$i]['Song']['SongTitle'];
							}
							?>
							<br />
							by&nbsp;
							<?php
							if (strlen($nationalTopDownload[$i]['Song']['Artist']) >= 24 ) {
									echo '<span title="'.$nationalTopDownload[$i]['Song']['Artist'].'">' . $html->link(substr($nationalTopDownload[$i]['Song']['Artist'], 0, 24) . "...", array(
									'controller' => 'artists',
									'action' => 'view',base64_encode($nationalTopDownload[$i]['Song']['ArtistText']),$nationalTopDownload[$i]['Song']['ReferenceID']
									)
								) . "</span>";
							} else {
								echo $html->link($nationalTopDownload[$i]['Song']['Artist'], array(
									'controller' => 'artists',
									'action' => 'view',base64_encode($nationalTopDownload[$i]['Song']['ArtistText']),$nationalTopDownload[$i]['Song']['ReferenceID']
									)
								);
							}
							$songUrl = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['Sample_Files']['CdnPath']."/".$nationalTopDownload[$i]['Sample_Files']['SaveAsName']);
							$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							?>
							<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$newCount, "onClick" => 'playSample(this, "'.$newCount.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
							<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$newCount)); ?>
							<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$newCount, "onClick" => 'stopThis(this, "'.$newCount.'");')); ?>
							<?php
							echo "<br/>";
							if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
								if($libraryDownload == '1' && $patronDownload == '1') {	
									if($nationalTopDownload[$i]['Song']['status'] != 'avail') {
										$songUrl = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['Full_Files']['CdnPath']."/".$nationalTopDownload[$i]['Full_Files']['SaveAsName']);
										$finalSongUrl = "http://music.freegalmusic.com".$songUrl;
										$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
										?>
										<span class="beforeClick" id="song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
										<![if !IE]>
										<a href='#' onclick='return userDownloadOthers("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><label class="dload" style="width:120px;cursor:pointer;" title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.'>Download Now</label></a>
										<![endif]>
										<!--[if IE]>
										<a style="cursor:pointer;" onclick='return userDownloadIE("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'><label class="dload" style="width:120px;" title='IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.'>Download Now</label></a>
										<![endif]-->
										</span>
										<span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;">Please Wait...</span>
										<span id="download_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
										<?php	
									} else {
									?>
										<a href='/homes/my_history' title='You have already downloaded this song. Get it from your recent downloads'>Downloaded</a>
									<?php
									}
								} else {
									if($libraryDownload != '1') {
										$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
										$wishlistCount = $wishlist->getWishlistCount();
										if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
										?> 
											Limit Exceeded 
										<?php
										} else {
											$wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);
											if($wishlistInfo == 'Added to Wishlist') {
											?> 
												Added to Wishlist
											<?php 
											} else { 
											?>
												<span  class="beforeClick" id="wishlist<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>",this);'>Add to Wishlist</a></span><span id="wishlist_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
												<span class="afterClick" style="display:none;">Please Wait...</span>
											<?php	
											}
										}

									} else { 
									?>
										Limit Exceeded
									<?php	
									}												
								}
							} else {
							?>
								<span title='Coming Soon ( <?php if(isset($nationalTopDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));} ?> )'>Coming Soon</span>
							<?php
							}
							?>
						</p>
					</td>
				</tr>
			<?php 
				$j++;
			} 
			?>
			</table>
		</div>
	</div>
</div>
<div id="artist_container">
    <div id="featured_artist">
            <?php
            foreach($featuredArtists as $key => $featuredArtist):
				if($featuredArtist['Featuredartist']['territory'] == $this->Session->read('territory') && $featuredArtist['Featuredartist']['language'] == Configure::read('App.LANGUAGE')){
					if($key == 0) {
						echo $html->link(
							$html->image($cdnPath.'featuredimg/'.$featuredArtist['Featuredartist']['artist_image'], array("alt" => "Featured Arstist", "height" => "215", "width" => "300")),
							array('controller'=>'artists', 'action'=>'view', base64_encode($featuredArtist['Featuredartist']['artist_name'])),
							array('class'=>'first','escape'=>false)
						);
					}
					else {
						echo $html->link(
							$html->image($cdnPath.'featuredimg/'.$featuredArtist['Featuredartist']['artist_image'], array("alt" => "Featured Arstist", "height" => "215", "width" => "300")),
							array('controller'=>'artists', 'action'=>'view', base64_encode($featuredArtist['Featuredartist']['artist_name'])),
							array('escape'=>false)
						);
					}
				}
            endforeach;
            ?>
    </div>
    <div id="newly_added">
            <?php
            foreach($newArtists as $key => $newArtist):
				if($newArtist['Newartist']['territory'] == $this->Session->read('territory') && $newArtist['Newartist']['language'] == Configure::read('App.LANGUAGE')){			
					if($key == 0) {
						echo $html->link(
							$html->image($cdnPath.'newartistimg/'.$newArtist['Newartist']['artist_image'], array("alt" => "Newly Added Artist", "height" => "215", "width" => "300")),
							array('controller'=>'artists', 'action'=>'view', base64_encode($newArtist['Newartist']['artist_name'])),
							array('class'=>'first','escape'=>false)
						);
					}
					else {
						echo $html->link(
							$html->image($cdnPath.'newartistimg/'.$newArtist['Newartist']['artist_image'], array("alt" => "Newly Added Artist", "height" => "215", "width" => "300")),
							array('controller'=>'artists', 'action'=>'view', base64_encode($newArtist['Newartist']['artist_name'])),
							array('escape'=>false)
						);
					}
				}
            endforeach;
            ?>
    </div>
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
                        <?php foreach($distinctArtists as $allArtists): ?>
                            <tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
                                <td class='artist_line'>
                                    <p>
                                        <?php
                                            echo $html->link($allArtists['Song']['ArtistText'], array(
                                                    'controller' => 'artists',
                                                    'action' => 'view',
                                                    base64_encode($allArtists['Song']['ArtistText']))
                                            );
                                        ?>
                                    </p>
                                </td>
                            </tr>
                        <?php endforeach; ?>       
                    </table>
            </div>
        </div>
    </div>
</div>
<style>
.suggestions {
	clear:both;
	float:left;
	display:block;
	width:308px;
	margin-left: 10px;
	margin-top: 5px;
	font-size:13px;
	height:642px;
	text-align: left;
	line-height:19px;
}
.tab_container {
	border-left: 1px solid #999;
	border-right: 1px solid #999;
	border-bottom: 1px solid #999;
	clear: both;
	float: left; 
	width: 100%;
	background: #fff;
	padding-bottom: 1px;
	-moz-border-radius-bottomright: 5px;
	-khtml-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-khtml-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.tab_content {
	height:600px;
	padding: 5px;
}

#tb1, #tb2 {
	float:left;
	width:153px;
	background-color:#999;
	color:<?php echo $library_boxheader_text_color; ?>;
	border-left: 1px solid #999;
	border-right: 1px solid #999;
	border-top: 1px solid #999;
}
#tb1 >.active, #tb2 >.active{
	background-color:#fff;
	color:#000;
	-moz-border-radius-topright: 5px;
	-khtml-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-khtml-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	border-bottom:0px;
	color:<?php echo $library_boxheader_bgcolor; ?>;
}
#tb1 >.nonactive, #tb2 >.nonactive{
	background-color:<?php echo $library_boxheader_bgcolor; ?>;
	color:#000;
	-moz-border-radius-topright: 5px;
	-khtml-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-khtml-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	border-bottom:0px;
	color:<?php echo $library_boxheader_bgcolor; ?>;
}
#sep {
	float:left;
}
#tb1 a, #tb2 a {
	text-decoration: none;
	color:<?php echo $library_box_header_color; ?>;
}
#tb1 >.active a, #tb2 >.active a{
	text-decoration: none;
	color:<?php echo $library_boxheader_bgcolor; ?>;
}
#tb1 a:hover, #tb2 a:hover{
	text-decoration: none;
	color:<?php echo $library_box_hover_color; ?>;
}
</style>