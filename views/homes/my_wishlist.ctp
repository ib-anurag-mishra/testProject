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

?>
<script lenguage="javascript">
   var languageSet = '<?php echo $setLang; ?>';  
</script>
<form id="sortForm" name="sortForm" method='post'>
    <input id='sort' type='hidden' name="sort" value="<?php echo $sort; ?>" />
    <input id='sortOrder' type='hidden' name="sortOrder" value="<?php echo $sortOrder; ?>" />
</form>
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
                    <div class="date-filter-button filter active"></div>
                <?php } else { ?>
                    <div class="date-filter-button filter active toggled"></div>
                <?php } 
            } else {
                ?>
                <div class="date-filter-button filter "></div>
            <?php
            }
            if($sort == 'song'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="song-filter-button filter active"></div>
                <?php } else { ?>
                    <div class="song-filter-button filter active toggled"></div>
                <?php } 
            } else {
                ?>
			<div class="song-filter-button filter"></div>
            <?php
            }
            ?>
			<div class="music-filter-button tab"></div>
			<div class="video-filter-button tab"></div>
		<?php
            if($sort == 'artist'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="artist-filter-button filter active"></div>
                <?php } else { ?>
                    <div class="artist-filter-button filter active toggled"></div>
                <?php } 
            } else {
                ?>
			<div class="artist-filter-button filter"></div>
            <?php
            }
            if($sort == 'album'){
                if($sortOrder == 'asc'){
                ?>    
                    <div class="album-filter-button filter active"></div>
                <?php } else { ?>
                    <div class="album-filter-button filter active toggled"></div>
                <?php } 
            } else {
                ?>
			<div class="album-filter-button filter"></div>
            <?php
            }
            ?>  
			<div class="download-button filter"></div>
			
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
                                            
                                        <?php
                                            $albumArtwork = shell_exec('perl files/tokengen ' . $wishlistResults[$i]['File']['CdnPath']."/".$wishlistResults[$i]['File']['SourceURL']);
                                            $songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                                        ?>
                                            
						<img src="<?=$songAlbumImage;?>" alt="small-album-cover" width="40" height="40" />
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
<!--		(this is the html for the videos)
		<div class="my-video-wishlist-shadow-container">
			<div class="my-video-wishlist-scrollable">
				<div class="row-container">
				<?php
				for($b=0;$b<28;$b++) {
				?>
				
				<div class="row clearfix">
					<div class="date">2013-06-13</div>
					<div class="small-album-container">
						<img src="../img/my-wishlist/video-cover.jpg" alt="video-cover" width="67" height="40" />
						 <a class="preview" href="#"></a> 
					</div>
					<div class="song-title">Grow Up</div>
					<a class="add-to-wishlist-button" href="#"></a>
					<div class="album-title"><a href="#">Sticks and Stones</a></div>
					<div class="artist-name"><a href="#">Cher Lloyd</a></div>
					
					<div class="wishlist-popover">
						
						<div class="share clearfix">
							<p>Share via</p>
							<a class="facebook" href="#"></a>
							<a class="twitter" href="#"></a>
						</div>
						
					</div>
					<div class="download"><a href="#">Download</a></div>
				</div>
				<?php 
				}
				?>
				</div>
			</div>
		</div>-->


	</section>
