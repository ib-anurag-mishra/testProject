<?php echo $javascript->link('freegal_home_curvy'); ?>
<?php echo $javascript->link('jquery.marquee.min'); ?>
<script type="text/javascript">

$(document).ready(function() {

	//Default Action
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

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
<div class="suggestions">
	<ul class="tabs">
		<li><a href="#tab1"><?php echo (__('Local Top 10', true));?></a></li>
		<li><a href="#tab2"><?php echo (__('National Top 10', true));?></a></li>

	</ul>
	<div class="tab_container">
		<div id="tab1" class="tab_content">
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
							if (strlen($songs[$i]['Song']['Title']) >= 28 ) {
								echo '<span title="'.$songs[$i]['Song']['Title'].'">' . substr($songs[$i]['Song']['Title'], 0, 28) . "..." . "</span>";
							} else {
								echo $songs[$i]['Song']['Title'];
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
						</p>
					</td>
				</tr>
			<?php 
				$j++;
			} 
			?>
			</table>
		</div>
		<div id="tab2" class="tab_content">
			<table cellspacing="0" cellpadding="0" id="musicbox">
			<?php
				$j =0;
				for($i = 0; $i < count($nationalTopDownload); $i++) {
				if($j==10){
					break;
				}
			?>
				<tr onmouseover="this.className = 'hlt';" onmouseout="this.className = '';">
					<td>
						<p class='suggest_text'>
							<?php
							if (strlen($nationalTopDownload[$i]['Song']['Title']) >= 28 ) {
								echo '<span title="'.$nationalTopDownload[$i]['Song']['Title'].'">' . substr($nationalTopDownload[$i]['Song']['Title'], 0, 28) . "..." . "</span>";
							} else {
								echo $nationalTopDownload[$i]['Song']['Title'];
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
							<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
							<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); ?>
							<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); ?>
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