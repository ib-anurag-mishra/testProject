<div class="breadCrumb">
	<?php
		$reffer_url = $_SERVER['HTTP_REFERER'];
		if(isset($genre)){
			$genre_text_conversion = array(
				"Children's Music" =>  "Children's" ,
				"Classic"  =>  "Soundtracks",
				"Comedy/Humor"  =>  "Comedy",
				"Country/Folk"  =>  "Country",
				"Dance/House"  =>  "Dance",
				"Easy Listening Vocal" => "Easy Listening",
				"Easy Listening Vocals"  =>  "Easy Listening",
				"Folk/Blues" => "Folk",
				"Folk/Country" => "Folk",
				"Folk/Country/Blues" => "Folk",
				"Hip Hop Rap" => "Hip-Hop Rap",
				"Rap/Hip-Hop" => "Hip-Hop Rap",
				"Rap / Hip-Hop" => "Hip-Hop Rap",
				"Jazz/Blues"  =>  "Jazz",
				"Kindermusik"  =>  "Children's",
				"Miscellaneous/Other" => "Miscellaneous",
				"Other" => "Miscellaneous",
				"Age/Instumental" => "New Age",
				"Pop / Rock" =>  "Pop/Rock",
				"R&B/Soul" => "R&B",
				"Soundtracks" => "Soundtrack",
				"Soundtracks/Musicals" => "Soundtrack",
				"World Music (Other)" => "World Music"
			);
			
			$genre_crumb_name = isset($genre_text_conversion[trim($genre)])?$genre_text_conversion[trim($genre)]:trim($genre);
			
			$html->addCrumb(__('All Genre', true), '/genres/view/');
                        if($genre_crumb_name != "")
                        {
                            $html->addCrumb( $genre_crumb_name  , '/genres/view/'.base64_encode($genre_crumb_name));
                        }
		
			echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
			echo " > ";
			if(strlen($artisttext) >= 30){
				$artisttext = substr($artisttext, 0, 30). '...';
			}
			echo $artisttext;	

		}
		else{
			echo $html->link('Home', array('controller'=>'homes', 'action'=>'index'));
			echo " > ";
			echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
			echo " > ";
			if(strlen($artisttext) >= 30){
				$artisttext = substr($artisttext, 0, 30). '...';
			}
			echo $artisttext;
		}
	?>
	
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
</div>
<br class="clr">
<?php
$explodeUrl = explode("page:", $_SERVER['HTTP_REFERER']);
$explodeUrl = explode("genres/view/", $explodeUrl[0]);
$explodeUrl[1] = str_replace("/", "", $explodeUrl[1]);

if(strpos($_SERVER['HTTP_REFERER'], "genres/view") > 0 && strpos($_SERVER['HTTP_REFERER'], "page:") == "")
{
    echo $javascript->link('backfix.min.js');
    ?>
    <script type="text/javascript">
    function goSomewhere () {
        document.location.href = '/genres/view/<?php echo base64_encode($genre_crumb_name); ?>';
    }
    bajb_backdetect.OnBack = function(){
        setTimeout('goSomewhere()', 1);
    }
    </script>
<?php
}
else if(strpos($_SERVER['HTTP_REFERER'], "genres/view") > 0 && trim(base64_encode($genre_crumb_name)) != trim($explodeUrl[1]))
{
    echo $javascript->link('backfix.min.js');
?>
    <script type="text/javascript">
    function goSomewhere () {
        document.location.href = '/genres/view/<?php echo base64_encode($genre_crumb_name); ?>';
    }
    bajb_backdetect.OnBack = function(){
        setTimeout('goSomewhere()', 1);
    }
    </script>
<?php
}
?>
<div style="padding-left:46px;padding-right:40px;" >
<table  width="100%">
<?php
$i = 0;
	foreach($albumData as $album_key => $album):
	if($i == 0){
		echo "<tr>";
	}
	$i++;
?>
		<td valign="top" >
		<div id="album_list_page" style="float:left">
			<a href="/artists/view/<?php echo str_replace('/','@',base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID'];  ?>/<?php echo base64_encode($album['Album']['provider_type']);  ?>" >
			<div class="album_lgAlbumArtwork" style="float:left">
				<?php $albumArtwork = shell_exec('perl files/tokengen ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']); ?>
				<?php
					$image = Configure::read('App.Music_Path').$albumArtwork;
					if($page->isImage($image)) {
						//Image is a correct one
					}
					else {
						
					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
					}
				?>
				<img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" width="100" height="100" border="0">
			</div>
			</a>			
			<div class="albumData" style="float:left">
				<a href="/artists/view/<?php echo str_replace('/','@',base64_encode($album['Album']['ArtistText'])); ?>/<?php echo $album['Album']['ProdID'];  ?>/<?php echo base64_encode($album['Album']['provider_type']);  ?>" >
				<div class="albumlistBox">
					<b>
					<?php
					if(strlen($album['Album']['AlbumTitle']) >= 50){
						$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
					}
					?>
					<?php echo $album['Album']['AlbumTitle'];?>		
					</b>
				</div>
				</a>
				<div class="album_artistInfo" style="float:left">
					<?php
						echo __('Genre').": ".$html->link($album['Genre']['Genre'], array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) . '<br />';
						if ($album['Album']['ArtistURL'] != '') {
							echo $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank'));
							echo '<br />';
						}
                        if($album['Album']['Advisory'] == 'T'){
                        	echo '<font class="explicit"> (Explicit)</font>';
                            echo '<br />';
                        }
						if ($album['Album']['Label'] != '') {
							echo __("Label").': ' . $album['Album']['Label'];
							echo '<br />';
						}
						if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {
							echo $album['Album']['Copyright'];
						}
					?>
				</div>
			</div>			
		</div>
		</td>
<?php
if($i == 3){
	echo "</tr>";
	$i = 0;
}
	endforeach;
?>
</table>
</div>


<?php  $pages = $this->Paginator->counter(array('format' => '%pages%')); 
if($pages > 1) {
?>

<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
    
 <?php } ?>
<br class="clr">