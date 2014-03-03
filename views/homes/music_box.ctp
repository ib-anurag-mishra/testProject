<?php
/*
	 File Name : music_box.ctp
	 File Description : View page for music box
	 Author : m68interactive
 */
?>
<?php
	$j =0;
	for($i = 0; $i < count($songs); $i++) {
	if($j==8){
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
				by 
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
				$songUrl = shell_exec(Configure::read('App.tokengen') . $songs[$i]['Sample_Files']['CdnPath']."/".$songs[$i]['Sample_Files']['SaveAsName']);
				$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
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