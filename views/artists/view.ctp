<!--<script>
    $('.add-to-playlist-button').on('click',function(e){
        e.preventDefault();
        $(this).siblings('.wishlist-popover').addClass('active');
    });
</script>-->
<section class="albums-page">
	<section class="album-detail-container clearfix">
		<div class="breadcrumbs"><span><?php
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
            $html->addCrumb( $this->getTextEncode($genre_crumb_name)  , '/genres/view/'.base64_encode($genre_crumb_name));
        }
	$html->addCrumb(__($this->getTextEncode($artistName), true), '/artists/album/'.str_replace('/','@',base64_encode($artistName)).'/'.base64_encode($genre));
	$html->addCrumb( $this->getTextEncode($albumData[0]['Album']['AlbumTitle'])  , '/artists/view/'.str_replace('/','@',base64_encode($artistName)).'/'.$album.'/'.base64_encode($albumData[0]['Album']['provider_type']));
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?></span></div>
            
            <?php
	foreach($albumData as $album_key => $album):
?>
		<section class="album-detail">
			<div class="album-cover-image">
                            <?php $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']); ?>
				<?php
					$image = Configure::read('App.Music_Path').$albumArtwork;
					if($page->isImage($image)) {
						//Image is a correct one
					}
					else {
						
					//	mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
					}
				?>
				<img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" alt="album-detail-cover" width="250" height="250" />
			</div>
			<div class="release-info">Release Information</div>
                        
                        
                        
                        
			
			<div class="album-genre"><?php echo __('Genre').": ";?><span><?php echo $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) ;
                        if($album['Album']['Advisory'] == 'T'){
                        	echo '<br />'; echo '<font class="explicit"> (Explicit)</font>';
                            
                        }?></span></div>
			<div class="album-label"><?php echo __('Label').": ";?><span><?php if ($album['Album']['Label'] != '') {
							echo $this->getTextEncode($album['Album']['Label']);}?></span></div>
			<div class="release-detail"><?php if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {
							echo $this->getTextEncode($album['Album']['Copyright']);
						}?></div>
			
		</section>
		<section class="tracklist-container">
			<div class="album-title"><?php
					if(strlen($album['Album']['AlbumTitle']) >= 50){
						$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
					}
					?>
					<?php echo $this->getTextEncode($album['Album']['AlbumTitle']);?></div>
			<div class="artist-name"><?php
	if(strlen($artistName) >= 30){
		$artistName = substr($artistName, 0, 30). '...';
	}
	?>
	<a href="/artists/album/<?php echo base64_encode($albumSongs[$album['Album']['ProdID']][0]['Song']['Artist']); ?>"><?php echo $this->getTextEncode($artistName); ?></a></div>
			<div class="tracklist-header"><span class="song">Song</span><span class="artist">Artist</span><span class="time">Time</span></div>
			
                            <?php
                            
					$i = 1;
					foreach($albumSongs[$album['Album']['ProdID']] as  $key => $albumSong):	
                                            
                                            
                                             //hide song if library block the explicit content
                                            if(($this->Session->read('block') == 'yes') && ($albumSong['Song']['Advisory'] =='T')) {
                                                continue;
                                            } 
						
					?>	
				
				<div class="tracklist">
                                    
                                   <!-- <a href="#" class="preview"></a> -->
                                   <?php
                                          if($this->Session->read("patron")){ 
                                              
                                            if($albumSong['Country']['SalesDate'] <= date('Y-m-d')) {
                                                    echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$album_key.$key, "onClick" => 'playSample(this, "'.$album_key.$key.'", '.$albumSong["Song"]["ProdID"].', "'.base64_encode($albumSong["Song"]["provider_type"]).'", "'.$this->webroot.'");'));
                                                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$album_key.$key));
                                                    echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$album_key.$key, "onClick" => 'stopThis(this, "'.$album_key.$key.'");'));
                                                }
                                            }
				?>
					</a>	
                                
                                
                                        <div class="song"><?php
                                                            if (strlen($albumSong['Song']['SongTitle']) >= 30) {
                                                                    echo '<span title="'.$this->getTextEncode($albumSong['Song']['SongTitle']).'">'  . $this->getTextEncode(substr($albumSong['Song']['SongTitle'], 0, 30)) . '...</span>';
                                                            } else {
                                                                    echo '<p>' . $this->getTextEncode($albumSong['Song']['SongTitle']);
                                                            }
                                                            if ($albumSong['Song']['Advisory'] == 'T') {
                                                                    echo '<span class="explicit"> (Explicit)</span>';
                                                            }
                                                    ?></div>
					<div class="artist"><a href="/artists/album/<?php echo base64_encode($albumSong['Song']['Artist']); ?>"><?php
										if (strlen($albumSong['Song']['Artist']) >= 11) {
											if(strlen($albumSong['Song']['Artist']) >= 60){
												$albumSong['Song']['Artist'] = substr($albumSong['Song']['Artist'], 0, 60). '...';
											}
											echo $this->getTextEncode(substr($albumSong['Song']['Artist'], 0, 13));
										} else {
											echo $this->getTextEncode($albumSong['Song']['Artist']) ;
										}
									?></a></div>
					<div class="time"><?php echo $albumSong['Song']['FullLength_Duration']?></div>
					<a class="add-to-playlist-button no-ajaxy" href="/popup"></a>
					
                                           <?php
                                             if($this->Session->read('patron')) { ?>
                                            <div class="wishlist-popover" style="top:-58px;">
                                               <?php if( $this->Session->read('library_type') == 2 ){ ?> 
                                                            <div class="playlist-options" style="margin-top:-30px;">
                                                                <ul>
                                                                        <li><a href="#">Create New Playlist</a></li>
                                                                        <li><a href="#">Playlist 1</a></li>
                                                                        <li><a href="#">Playlist 2</a></li>
                                                                        <li><a href="#">Playlist 3</a></li>
                                                                        <li><a href="#">Playlist 4</a></li>
                                                                        <li><a href="#">Playlist 5</a></li>
                                                                        <li><a href="#">Playlist 6</a></li>
                                                                        <li><a href="#">Playlist 7</a></li>
                                                                        <li><a href="#">Playlist 8</a></li>
                                                                        <li><a href="#">Playlist 9</a></li>
                                                                        <li><a href="#">Playlist 10</a></li>
                                                                </ul>
                                                            </div>
                                                            <a class="add-to-playlist" href="javascript:void(0);">Add To Queue</a>
                                               <?php  }     
                                                    if($albumSong['Country']['SalesDate'] <= date('Y-m-d'))
                                                    {
                                                            if($libraryDownload == '1' && $patronDownload == '1')
                                                            {
                                                                $productInfo = $song->getDownloadData($albumSong["Song"]["ProdID"],$albumSong["Song"]["provider_type"]);
                                                                $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                                                                $finalSongUrl = Configure::read('App.Music_Path').$songUrl;
                                                                $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));

                                                                    //$albumSong['Song']['status'] = 'avail1';
                                                                    if(isset($albumSong['Song']['status']) && ($albumSong['Song']['status'] != 'avail')) {

                                                    ?>

                                                                    <form method="Post" id="form<?php echo $albumSong["Song"]["ProdID"]; ?>" action="/homes/userDownload">
                                                                            <input type="hidden" name="ProdID" value="<?php echo $albumSong["Song"]["ProdID"];?>" />
                                                                            <input type="hidden" name="ProviderType" value="<?php echo $albumSong["Song"]["provider_type"]; ?>" />

                                                                            <span class="beforeClick" style="cursor:pointer;" id="wishlist_song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
                                                                                <![if !IE]>
                                                                                        <a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $albumSong["Song"]['ProdID']; ?>", "0", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>" , "<?php echo $albumSong["Song"]["provider_type"]; ?>");'><?php __('Download');?></a>
                                                                                <![endif]>
                                                                                <!--[if IE]>
                                                                                        <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIE("<?php echo $albumSong["Song"]['ProdID']; ?>", "0" , "<?php echo $albumSong["Song"]["provider_type"]; ?>");' href="<?php echo trim($finalSongUrl);?>"><?php __('Download');?></a>
                                                                                <![endif]-->
                                                                            </span>

                                                                            <span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display:none;"><a  class="add-to-wishlist"  ><?php __("Please Wait..");?>
                                                                            <span id="wishlist_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="float:right;padding-right:8px;padding-top:2px;"><?php  echo  $html->image('ajax-loader_black.gif');  ?></span> </a> </span>

                                                    </form>													

                                            <?php	
                                                                    } else {
                                                                            ?><a class='add-to-wishlist' href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __("Downloaded");?></a><?php
                                                                    }
                                                            }					
                                                            else{
                                                                    ?>
                                                                    <a class="add-to-wishlist" href="javascript:void(0)"><?php __("Limit Met");?></a>        
                                                                    <?php
                                                            }
                                                    }else{
                                            ?>
                                                            <a class="add-to-wishlist" href="javascript:void(0)"><span title='<?php __("Coming Soon");?> ( <?php if(isset($albumSong['Country']['SalesDate'])){ echo 
                                                                    date("F d Y", strtotime($albumSong['Country']['SalesDate']));} ?> )'><?php __('Coming Soon'); ?></span></a>
                                                    <?php
                                                    } 
                                                } else {
                                                    ?>
                                                    <div class="wishlist-popover" style="top:-21px;">
                                                    <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>
                                                    <?php
                                                }

                                                if($this->Session->read('patron')) {
                                                ?> 
                                                    <!--a class="add-to-playlist" href="#">Add To Queue</a-->
                                                    <?php
                                                    $wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);
                                                    echo $wishlist->getWishListMarkup($wishlistInfo,$albumSong["Song"]["ProdID"],$albumSong["Song"]["provider_type"]);    
                                                }
                                                ?>      
						<!--div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div-->						
					</div>
				</div>					
			<?php                      
                            endforeach;
			?>
		</section>
            <?php
	endforeach;
        ?>			
	</section>
</section>
