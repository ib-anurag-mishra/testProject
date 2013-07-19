<?php
/*
	 File Name : my_wishlist.ctp 
	 File Description : View page for wishlist information
	 Author : m68interactive
 */
?>
<?php

if ($this->Session->read('Config.language') == 'en') {
    $setLang = 'en';
} else {
    $setLang = 'es';
}
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

<script lenguage="javascript">
   var languageSet = '<?php echo $setLang; ?>';  
</script>
<section class="my-wishlist-page">
		
		<div class="breadcrumbs"><?php
	$html->addCrumb('My Wishlist', '/homes/my_wishlist');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?></div>
		<header class="clearfix">
			<h2>My Wishlist</h2>
			<div class="faq-link">Need help? Visit our <?php echo $html->link(__('FAQ section.', true), array('controller' => 'questions', 'action' =>'index')); ?></div>
		</header>
		<div class="instructions">
			<p>
				In the event that your library exceeds its download budget for the week, you will see "add to wishlist" in place of the "download now" command. Adding your music to the wishlist will place you in a "first come, first serve" line to get more music when it becomes available, which is at midnight Sunday Eastern Time (U.S.). At that point your music is on hold for you for 24 hours (so no need to set your alarm clock) for you to proactively download. You should visit the Wishlist area on the top part of the home page to see the music that you requested, and if it is available.
			</p>
			<p>
				If you do not see the "download now" command in the Wish List area, it means so many people were waiting in line that you need to check back on a subsequent Monday.
			</p>
		</div>
				<nav class="my-wishlist-filter-container clearfix">
					<?php 
            if($sort == 'date'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="date-filter-button filter active">Date</div>
                <?php } else { ?>
                    <div class="date-filter-button filter active toggled">Date</div>
                <?php } 
            } else {
                ?>
                <div class="date-filter-button filter ">Date</div>
            <?php
            }
            if($sort == 'song'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="song-filter-button filter active">Song</div>
                <?php } else { ?>
                    <div class="song-filter-button filter active toggled">Song</div>
                <?php } 
            } else {
                ?>
			<div class="song-filter-button filter">Song</div>
            <?php
            }
            ?>
			<div class="music-filter-button tab">Music</div>
			<div class="video-filter-button tab">Video</div>
		<?php
            if($sort == 'artist'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="artist-filter-button filter active" style="width:106px">Artist</div>
                <?php } else { ?>
                    <div class="artist-filter-button filter active toggled" style="width:106px">Artist</div>
                <?php } 
            } else {
                ?>
			<div class="artist-filter-button filter" style="width:106px">Artist</div>
            <?php
            }
            if($sort == 'album'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="album-filter-button filter active">Album</div>
                <?php } else { ?>
                    <div class="album-filter-button filter active toggled">Album</div>
                <?php } 
            } else {
                ?>
			<div class="album-filter-button filter">Album</div>
            <?php
            }
            ?>  
			<div class="download-button filter">Download</div>
			
		</nav>
		<div class="my-wishlist-shadow-container">
			<div class="my-wishlist-scrollable">
				<div class="row-container">
				<?php

         if(is_array($wishlistResults) && count($wishlistResults) > 0){ 
	
            for($i = 0; $i < count($wishlistResults); $i++) {
		
			
	?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date('Y-m-d',strtotime($wishlistResults[$i]['wishlists']['created'])); ?></div>
					<div class="small-album-container">                                     
                                
						<a class="preview" href="#"></a>
					</div>
					<div class="song-title">
                                        <?php 
						if (strlen($wishlistResults[$i]['wishlists']['track_title']) >= 48) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['track_title'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['track_title'], 0, 48)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResults[$i]['wishlists']['track_title']);
					 	}
					?></div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="/artists/view/<?=base64_encode($wishlistResults[$i]['Song']['ArtistText']);?>/<?= $wishlistResults[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($wishlistResults[$i]['Song']['provider_type']);?>">
                                         <?php
						if (strlen($wishlistResults[$i]['wishlists']['album']) >= 24) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['album'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['album'], 0, 24)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResults[$i]['wishlists']['album']);
						}
						
                                          ?>
                                            </a></div>
					<div class="artist-name"><a href="/artists/album/<?= base64_encode($wishlistResults[$i]['Song']['ArtistText']); ?>">
                                         <?php
						if (strlen($wishlistResults[$i]['wishlists']['artist']) >= 19) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResults[$i]['wishlists']['artist'])).'">' .$this->getTextEncode(substr($wishlistResults[$i]['wishlists']['artist'], 0, 19)) . '...</span>';
						} else {
							$ArtistName = $wishlistResults[$i]['wishlists']['artist'];
							echo $this->getTextEncode($ArtistName);
						}
						
                                         ?>
                                            </a></div>
					
				<div class="wishlist-popover">
						<!--	
						<a class="remove-song" href="#">Remove Song</a>
						<a class="make-cover-art" href="#">Make Cover Art</a>
						-->
                                                <?php if( $this->Session->read('library_type') == 2 ){ ?>
                                                    <a class="add-to-playlist" href="#">Add To Queue</a>
                                                <?php } ?>
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						<?php if( $this->Session->read('library_type') == 2 ){ ?>
						<div class="playlist-options">
							<ul>
								<li><a href="#">Create New Queue</a></li>
								<li><a href="#">Playlist 1</a></li>
								<li><a href="#">Playlist 2</a></li>
								<li><a href="#">Playlist 3</a></li>
								<li><a href="#">Playlist 4</a></li>
								<li><a href="#">Playlist 5</a></li>
								
								
							</ul>
						</div>
						<?php } ?>
					</div>
						<div class="download">
                                            
                                    <?php										
                                            $productInfo = $song->getDownloadData($wishlistResults[$i]['wishlists']['ProdID'],$wishlistResults[$i]['wishlists']['provider_type']);
                                            if($libraryDownload == '1' && $patronDownload == '1'){
                                                    $songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                                                    $finalSongUrl = Configure::read('App.Music_Path').$songUrl;
                                                    $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
                                    ?>
							<p>
								<span class="beforeClick" id="wishlist_song_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>">
									<![if !IE]>
										<a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['wishlists']['id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>" , "<?php echo $wishlistResults[$i]['wishlists']["provider_type"]; ?>");'><?php __('Download');?></a>
									<![endif]>
									<!--[if IE]>
									<a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadIE("<?php echo $wishlistResults[$i]['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResults[$i]['Wishlist']['id']; ?>" , "<?php echo $wishlistResults[$i]['Wishlist']["provider_type"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
									<![endif]-->							
								</span>
								<span class="afterClick" id="downloading_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:left;"><?php __('Please Wait..');?></span>
								<span id="wishlist_loader_<?php echo $wishlistResults[$i]['wishlists']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
                                    <?php	}else{ ?>
                                                    <p><?php __("Limit Met");?></p>
                                            <?php
                                            }
                                    ?>
                                            
                                        </div>						
					<div class="delete-btn"></div>
				</div>
				<?php 

           }

        }else{
            
            echo 	'<p><?php __("You have no songs in your wishlist.");?></p>';
            
        }


        ?>
				</div>
			</div>
		</div>
			<!--(this is the html for the videos) -->
		<div class="my-video-wishlist-shadow-container" style="display:none;">
			<div class="my-video-wishlist-scrollable">
				<div class="row-container">
				<?php
                if(count($wishlistResultsVideos) != 0)
                {
                    //$i = 1;
                    foreach($wishlistResultsVideos as $key => $wishlistResultsVideo):
                    /*$class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }*/
                ?>
				
				<div class="row clearfix">
					<div class="date"><?php echo date("Y-m-d",strtotime($wishlistResultsVideo['WishlistVideo']['created'])); ?></div>
					<div class="small-album-container">
						<?php
                        $videoImage = shell_exec('perl files/tokengen ' . 'sony_test/'.$wishlistResultsVideo['File']['CdnPath']."/".$wishlistResultsVideo['File']['SourceURL']);
                        $videoImageUrl = Configure::read('App.Music_Path').$videoImage;
                        ?>
                        <img src="<?php echo $videoImageUrl; ?>" alt="video-cover" width="67" height="40" />
						<!-- <a class="preview" href="#"></a> -->
					</div>
					<div class="song-title">
                    <?php 
						if (strlen($wishlistResultsVideo['WishlistVideo']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($wishlistResultsVideo['WishlistVideo']['track_title']).'">' .substr($wishlistResultsVideo['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $wishlistResultsVideo['WishlistVideo']['track_title']; 
					 	}
					?>
                    </div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#"><?php echo $wishlistResultsVideo['Video']['Title'];  ?></a></div>
					<div class="artist-name"><a href="#">
                    <?php
						if (strlen($wishlistResultsVideo['WishlistVideo']['artist']) >= 19) {
							echo '<span title="'.htmlentities($wishlistResultsVideo['WishlistVideo']['artist']).'">' .substr($wishlistResultsVideo['WishlistVideo']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $wishlistResultsVideo['WishlistVideo']['artist'];
							echo $ArtistName;
						}
						
					?></a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download">
                        <a href="#">
                            
                        <p>
                        <?php
                            $productInfo = $mvideo->getDownloadData($wishlistResultsVideo['WishlistVideo']['ProdID'],$wishlistResultsVideo['WishlistVideo']['provider_type']);
                            $videoUrl = shell_exec('perl files/tokengen ' . 'sony_test/' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalVideoUrl = Configure::read('App.Music_Path').$videoUrl;
							$finalVideoUrlArr = str_split($finalVideoUrl, ceil(strlen($finalVideoUrl)/3));
                            ?>
                            <span class="beforeClick" id="download_song_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>">
								<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
									<a href='#' onclick='return historyDownloadOthers("<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['library_id']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['patron_id']; ?>", "<?php echo urlencode($finalVideoUrlArr[0]);?>", "<?php echo urlencode($finalVideoUrlArr[1]);?>", "<?php echo urlencode($finalVideoUrlArr[2]);?>");'><?php __('Download');?></a>
								<?php } else {?>
								<!--[if IE]>
									<a onclick='return historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['library_id']; ?>","<?php echo $wishlistResultsVideo['WishlistVideo']['patron_id']; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download');?></a> 										
								<![endif]-->
								<?php } ?>
							</span>
							<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
							<span id="download_loader_<?php echo $wishlistResultsVideo['WishlistVideo']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
                       </p></a>
                    </div>
		    <div class="delete-btn"></div>
				</div>
				<?php
                    endforeach;
                    }else{
                echo 	'<tr><td valign="top"><p>';?><?php echo __("No downloaded songs from this week or last week."); ?><?php echo '</p></td></tr>';
                }
				?>
				</div>
			</div>
		</div>

	</section>
