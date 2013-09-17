<section class="artist-page">
<div class="breadCrumb">
	<?php
		if(!empty($_SERVER['HTTP_REFERER'])){
                    $reffer_url = $_SERVER['HTTP_REFERER'];
                }
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
                            $html->addCrumb( $this->getTextEncode($genre_crumb_name)  , '/genres/view/'.base64_encode($genre_crumb_name));
                        }
		
			echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
			echo " > ";
			if(strlen($artisttext) >= 30){
				$artisttext = substr($artisttext, 0, 30). '...';
			}
			echo $this->getTextEncode($artisttext);	

		}
		else{
			echo $html->link('Home', array('controller'=>'homes', 'action'=>'index'));
			echo " > ";
			echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
			echo " > ";
			if(strlen($artisttext) >= 30){
				$artisttext = substr($artisttext, 0, 30). '...';
			}
			echo $this->getTextEncode($artisttext);
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
<header class="clearfix">
        <?php if(isset($artisttitle)){ ?>
            <h2><?php echo $artisttitle; ?></h2>
        <?php } ?>
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
</header>
<?php
if(!empty($_SERVER['HTTP_REFERER'])){
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
}
?>
            <?php if(!empty($albumData)){ ?>
            <h3>Albums</h3>
            <div class="album-shadow-container">
            <div class="album-scrollable horiz-scroll">
                <ul>
<?php
	foreach($albumData as $album_key => $album):
            
            
             //hide album if library block the explicit content
            if(($this->Session->read('block') == 'yes') && ($album['Album']['Advisory'] =='T')) {
                continue;
            } 
            
            
            
            
?>
           
                    
                    
                    <li>
                    <div class="album-container">
                        <a href="/artists/view/<?php echo str_replace('/','@',base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID'];  ?>/<?php echo base64_encode($album['Album']['provider_type']);  ?>" >
                            <?php
                                if(empty($album['Files']['CdnPath'])){
                                    if(empty($album['Files']['SourceURL'])){
                                        mail(Configure::read('TO'),"Album Artwork","CdnPath and SourceURL missing for Album ".$album['Album']['AlbumTitle']." ProdID ".$album['Album']['ProdID']." Provider Type : ".$album['Album']['provider_type']." is missing",Configure::read('HEADERS'));
                                    } else {
                                        mail(Configure::read('TO'),"Album Artwork","CdnPath missing for Album ".$album['Album']['AlbumTitle']." ProdID ".$album['Album']['ProdID']." Provider Type : ".$album['Album']['provider_type']." ProdID ".$album['Album']['provider_type']." is missing",Configure::read('HEADERS'));
                                    }
                                }
                            ?>
                            <?php $albumArtwork = shell_exec('perl files/tokengen_artwork ' . $album['Files']['CdnPath']."/".$album['Files']['SourceURL']); ?>
                            <?php
                                    $image = Configure::read('App.Music_Path').$albumArtwork;
                                    if($page->isImage($image)) {
                                            // Image is a correct one
                                    }
                                    else {
                                            //mail(Configure::read('TO'),"Album Artwork","Album Artwork url= ".$image." for ".$album['Album']['AlbumTitle']." is missing",Configure::read('HEADERS'));
                                    }
                            ?>
                            <img src="<?php echo Configure::read('App.Music_Path').$albumArtwork; ?>" width="162" height="162">
                        </a>   
                    </div>
                    <div class="album-title">
                        <a href="/artists/view/<?php echo str_replace('/','@',base64_encode($album['Album']['ArtistText'])); ?>/<?php echo $album['Album']['ProdID'];  ?>/<?php echo base64_encode($album['Album']['provider_type']);  ?>" >

                                <b>
                                <?php
                                if(strlen($album['Album']['AlbumTitle']) >= 50){
                                        $album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50). '...';
                                }
                                ?>
                                <?php 
                                echo $this->getTextEncode($album['Album']['AlbumTitle']);?>		
                                </b>
                        </a>
                    </div>
                    <div class="genre">
                        <?php echo __('Genre').": ".$html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre']))) . '<br />';
						if ($album['Album']['ArtistURL'] != '') {
							echo $ArtistURL = $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank','style' => 'word-wrap:break-word;word-break:break-word;width:160px;'));
							
                                                       
                                                        echo '<br />';
						}
                        if($album['Album']['Advisory'] == 'T'){
                        	echo '<font class="explicit"> (Explicit)</font>';
                            echo '<br />';
                        } ?>
                    </div>
                    <div class="label">
                        <?php 
                        if ($album['Album']['Label'] != '') {
                                echo __("Label").': ' . $this->getTextEncode($album['Album']['Label']);
                                echo '<br />';
                        }
                        if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown') {
                                echo $this->getTextEncode($album['Album']['Copyright']);
                        } ?>
                    </div>
            </li>		
<?php
	endforeach;
?>
    </ul>
  </div>
</div>                  
    <?php } ?>                        

                            <?php if(!empty($artistVideoList)){ ?>
            <h3>Videos</h3>
		<div class="videos-shadow-container">
			<div class="videos-scrollable horiz-scroll">
                            <ul>
                                <?php 
                                foreach($artistVideoList as $key => $value){
                                ?>  
					<li>
						
						<div class="video-container">
							<a href="/videos/details/<?php echo $value["Video"]["ProdID"]; ?>">                                                        
                                                        <img src="<?php echo $value['videoAlbumImage']; ?>" alt="jlo" width="272" height="162" />
                                                        </a>                                                  
<?php

    if($this->Session->read('patron')) {
        if($value['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {
                $productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"],$value["Video"]["provider_type"]);
                $videoUrl = shell_exec('perl files/tokengen '  . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                $finalVideoUrl = Configure::read('App.Music_Path').$videoUrl;
                $finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl)/3));

                    $value['Video']['status'] = 'avail1';
                    if($value['Video']['status'] != 'avail' ) {
                            ?>
                            <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $value["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $value["Video"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $value["Video"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="download_video_<?php echo $value["Video"]["ProdID"]; ?>">
                                <![if !IE]>
                                    <a title="<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?> href='#' onclick='return wishlistVideoDownloadOthers("<?php echo $$value["Video"]["ProdID"]; ?>","0", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>", "<?php echo $value["Video"]["provider_type"]; ?>");'><?php __('Download');?></a>
                                <![endif]>
                                <!--[if IE]>
                                    <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistVideoDownloadIE("<?php echo $value["Video"]["ProdID"]; ?>","0","<?php echo $value["Video"]['provider_type']; ?>");' href="<?php echo trim($finalVideoUrl);?>"><?php __('Download');?></a>
                                <![endif]-->
                            </span>
                            <span class="afterClick" id="vdownloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="vdownload_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
                            </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {

                if($libraryDownload != '1') {
                        $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                        $wishlistCount = $wishlist->getWishlistCount();
                        if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
                        ?> 
                                <a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
                        <?php
                        } else {
                                $wishlistInfo = $wishlist->getWishlistData($value["Video"]["ProdID"]);
                                if($wishlistInfo == 'Added to Wishlist') {
                                ?> 
                                        <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                <?php 
                                } else { 
                                ?>
                                        <span class="beforeClick" id="wishlist<?php echo $value["Video"]["ProdID"]; ?>"><a class="top-100-download-now-button" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $value["Video"]["ProdID"]; ?>","<?php echo $value["Video"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
                                        <span class="afterClick" id="downloading_<?php echo $value["Video"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
                                <?php	
                                }
                        }

                } else { 
                ?>
                        <a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
                <?php	
                }												
            }
        } else {
        ?>
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($value['Country']['SalesDate'])){ echo date("F d Y", strtotime($value['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-10-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
    ?>
								
								<?php if($this->Session->read("patron")){ ?> 
														
														<a class="add-to-playlist-button no-ajaxy" href="#"></a>
														
														<div class="wishlist-popover">
															<?php

                                                                                                                        $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);

                                                                                                                        if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                        ?> 
                                                                                                                                <a class="add-to-wishlist no-ajaxy" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                        <?php 
                                                                                                                        } else { 
                                                                                                                        ?>
                                                                                                                                <span class="beforeClick" id="wishlist<?php echo $value["Song"]["ProdID"]; ?>"><a class="add-to-wishlist no-ajaxy" href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $value["Song"]["ProdID"]; ?>","<?php echo $value["Song"]["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                                <span class="afterClick" id="downloading_<?php echo $value["Song"]["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                                                                                        <?php	
                                                                                                                        }

                                                                                                                        ?>														
														</div>
                                                                                                  <?php } ?>
								
							
							
						</div>
						<div class="song-title">
							<a href="javascript:void(0);">
                                                        <?php 
                                                                if(strlen($value['Video']['VideoTitle'])>25)
                                                                echo substr($value['Video']['VideoTitle'],0,25)."..."; 
                                                                else echo $value['Video']['VideoTitle'];
                                                         ?>
                                                         </a>						
                                                </div>
						<div class="genre">
							<?php echo __('Genre').": ".$html->link($this->getTextEncode($value['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($value['Genre']['Genre']))) . '<br />'; ?>
						</div>
                                                <?php if(!empty($value['Video']['video_label'])){ ?>
						<div class="label">
							Label: <?php  if(strlen($value['Video']['video_label'])>25)
                                                                echo substr($value['Video']['video_label'],0,25)."..."; 
                                                                else echo $value['Video']['video_label']; ?>
                                                        
						</div>
                                                <?php } ?>
					</li>
                                  <?php } ?>      
                            </ul>
                         </div>
                    </div>
                            <?php } ?>
            <br class="clr">
</section>