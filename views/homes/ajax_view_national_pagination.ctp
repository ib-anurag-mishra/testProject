<script>
    
$('.song-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-100-download-now-button').css({opacity:1});
		$(this).find('.preview').css({opacity:1});
		
	
	});
	
$('.song-cover-container').on('mouseleave',function(){



        $(this).find('.add-to-playlist-button').css({opacity:0});
        $(this).find('.top-100-download-now-button').css({opacity:0});
        $(this).find('.preview').css({opacity:0});

});

$('.video-cover-container').on('mouseenter',function(){
		

		$(this).find('.add-to-playlist-button').css({opacity:1});
		$(this).find('.top-100-download-now-button').css({opacity:1});
		
	});
	
	$('.video-cover-container').on('mouseleave',function(){
		
	

		$(this).find('.add-to-playlist-button').css({opacity:0});
		$(this).find('.top-100-download-now-button').css({opacity:0});
		
	
	});

        
</script>    

<?php

        $slNo = ($startLimit + 1);
        
        if($Type=='songs')
        {

        for($i = 0; $i < count($nationalTopDownload); $i++) { 
                                                                                          //hide song if library block the explicit content
                                                                                          if(($this->Session->read('block') == 'yes') && ($nationalTopDownload[$i]['Song']['Advisory'] =='T')) {
                                                                                              continue;
                                                                                          }
                                                                                            
											
                                                                                        
                                                                                        //$albumArtwork = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['File']['CdnPath']."/".$nationalTopDownload[$i]['File']['SourceURL']);
                                                                                        //$songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                                                                                        
//                                                                                        if($i<=9)       
//                                                                                        {
                                                                                            $lazyClass      =   '';
                                                                                            $srcImg         =   $nationalTopDownload[$i]['songAlbumImage'];   
                                                                                            $dataoriginal   =   '';  
//                                                                                        }
//                                                                                        else                //  Apply Lazy Class for images other than first 10.
//                                                                                        {
//                                                                                             $lazyClass      =   'lazy';
//                                                                                             $srcImg         =   $this->webroot.'app/webroot/img/lazy-placeholder.gif';
//                                                                                             $dataoriginal   =   $nationalTopDownload[$i]['songAlbumImage'];
//                                                                                        }
                                                                                        
                                                                                        ?>
                                                                                    
                                                                                    <?php
                                                                                        
                                                                                        
											

 /* echo $this->webroot."app/webroot/img/news/top-100/grid/bradpaisley250x250.jpg"; */ 
										?>
											<li>
												<div class="top-100-songs-detail">
													<div class="song-cover-container">
														<a href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><img class="<?php echo $lazyClass; ?>" alt="<?php echo $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']). ' - '.$this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']); ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>"  width="250" height="250" /></a>
														<div class="top-100-ranking"><?php												
												echo $slNo;
                                                                                                $slNo++;
                                                                                                
											?></div>
														
<?php if($this->Session->read("patron")){ ?> 
<!-- <a href="#" class="preview"></a>  -->
<?php           
            if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.base64_encode($nationalTopDownload[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                    echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
            }
?>
<?php } ?>


												


<?php

    if($this->Session->read('patron')) {
        if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $nationalTopDownload[$i]['Song']['status'] = 'avail1';
                    if(isset($nationalTopDownload[$i]['Song']['status']) && ($nationalTopDownload[$i]['Song']['status'] != 'avail')) {
                            ?>
        <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopDownload[$i]["Song"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='userDownloadAll("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
        </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {
                ?>
                       <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                                        
                <?php
                												
            }
        } else {
        ?>
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
      ?>



                                                                                                    <?php if($this->Session->read("patron")){ ?> 
														<a class="add-to-playlist-button" href="#"></a>
														<div class="wishlist-popover">
                                                                                                <?php if( $this->Session->read('library_type') == 2 ){
                                                                                                            echo $this->Queue->getQueuesList($this->Session->read('patron'),$nationalTopDownload[$i]["Song"]["ProdID"],$nationalTopDownload[$i]["Song"]["provider_type"],$nationalTopDownload[$i]["Albums"]["ProdID"],$nationalTopDownload[$i]["Albums"]["provider_type"]); ?>
                                                                                                            <a class="add-to-playlist" href="#">Add To Queue</a>
                                                                                                <?php } ?>
														

                                                                                                                    <?php
                                                                                                                    
                                                                                                                        $wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);

                                                                                                                        echo $wishlist->getWishListMarkup($wishlistInfo,$nationalTopDownload[$i]["Song"]["ProdID"],$nationalTopDownload[$i]["Song"]["provider_type"]);    
                                                                                                                    ?>
                                                                                                          <!--  <div class="share clearfix">
                                                                                                            <p>Share via</p>
                                                                                                           <span id="divButtons_<?php //echo $i; ?>""></span> 
                                                                                                            </div> -->
                                                                                                                    <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
														</div>
                                                                                                    <?php } ?>
													</div>

                                                                                                    <?php											
                                                                                                    if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 30 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 30)) . "..";
                                                                                                    } else {
                                                                                                            $songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
                                                                                                    }
                                                                                                    
                                                                                                  if('T' == $nationalTopDownload[$i]['Song']['Advisory']) { 
                                                                                                      if (strlen($songTitle) >= 20 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 20)) . "..";
                                                                                                      }                                                                                                     
                                                                                                      $songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';                                                                                                      
                                                                                                  }
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    ?>


                                                                                                    <?php											
                                                                                                    if (strlen($nationalTopDownload[$i]['Song']['ArtistText']) >= 30 ) {
                                                                                                            $artistText = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 30)) . "..";
                                                                                                    } else {
                                                                                                            $artistText = $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']);
                                                                                                    }
                                                                                                    ?>


													<div class="song-title">
														<a href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><?php echo $this->getTextEncode($songTitle); ?></a>
													</div>
													<div class="artist-name">                                                                                                            
														<a href="/artists/album/<?php echo base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>"><?php echo $this->getTextEncode($artistText); ?></a>
													</div>
												</div>
											</li>

                                                                                <?php 

                                                                                    }
        }
        else if($Type=='videos')
        {
         for($i = 0; $i < count($nationalTopVideoDownload); $i++) {
                                                                                            
                                                                                          //hide song if library block the explicit content
                                                                                          if(($this->Session->read('block') == 'yes') && ($nationalTopVideoDownload[$i]['Video']['Advisory'] =='T')) {
                                                                                              continue;
                                                                                          }
	
											//$albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$nationalTopVideoDownload[$i]['Image_Files']['CdnPath']."/".$nationalTopVideoDownload[$i]['Image_Files']['SourceURL']);
                                                                                        //$videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                                                                                        /* echo $this->webroot."app/webroot/img/news/top-100/grid/bradpaisley250x250.jpg"; */ 
										?>
											<li>
												<div class="top-100-video-detail">
													<div class="video-cover-container">
														<a href="/videos/details/<?php echo $nationalTopVideoDownload[$i]['Video']['ProdID']; ?>"><img src="<?php echo $nationalTopVideoDownload[$i]['videoAlbumImage']; ?>" alt="<?php echo $nationalTopVideoDownload[$i]['Video']['ArtistText'].' - '.$nationalTopVideoDownload[$i]['Video']['VideoTitle']; ?>" width="423" height="250" /></a>
														<div class="top-100-ranking"><?php
												echo $slNo;
                                                                                                $slNo++;
											?></div>
<?php if($this->Session->read("patron")){ ?> 														
<a href="#" class="preview"></a>
<?php } ?>


														


<?php

    if($this->Session->read('patron')) {
        if($nationalTopVideoDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {

                    $nationalTopVideoDownload[$i]['Video']['status'] = 'avail1';
                    if($nationalTopVideoDownload[$i]['Video']['status'] != 'avail' ) {
                            ?>
                            <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
                            </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
                    <?php
                    }

            } else {
                ?>
                <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
                <?php
             												
            }
        } else {
        ?>
            <a class="top-100-download-now-button" href="javascript:void(0);"><span title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopVideoDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopVideoDownload[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span></a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
    ?>


                                                                                                <?php if($this->Session->read("patron")){ ?> 
														
														<a class="add-to-playlist-button" href="#"></a>
														
														<div class="wishlist-popover">
															<!--
															<div class="playlist-options">
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
															
															<a class="add-to-queue" href="#">Add To Queue</a>
															<a class="add-to-playlist" href="#">Add To Playlist</a>
															-->
                                                                                                                    <?php


                                                                                                                     $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($nationalTopVideoDownload[$i]['Video']["ProdID"]);

                                                                                                                    if($wishlistInfo == 'Added to Wishlist') {
                                                                                                                    ?> 
                                                                                                                            <a class="add-to-wishlist" href="javascript:void(0);"><?php __("Added to Wishlist");?></a>
                                                                                                                    <?php 
                                                                                                                    } else { 
                                                                                                                    ?>
                                                                                                                            <span class="beforeClick" id="video_wishlist<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>"><a class="add-to-wishlist" href='JavaScript:void(0);' onclick='Javascript: addToWishlistVideo("<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>","<?php echo $nationalTopVideoDownload[$i]['Video']["provider_type"]; ?>");'><?php __("Add to Wishlist");?></a></span>
                                                                                                                            <span class="afterClick" id="downloading_<?php echo $nationalTopVideoDownload[$i]['Video']["ProdID"]; ?>" style="display:none;"><a class="add-to-wishlist" href='JavaScript:void(0);'><?php __("Please Wait...");?></a></span>
                                                                                                                    <?php	
                                                                                                                    }

                                                                                                                    ?>
                                                        									
                                                                                                                     <?php echo $this->Queue->getSocialNetworkinglinksMarkup(); ?>
															
														</div>
                                                                                                  <?php } ?>
													</div>

                                                                                                <?php											
                                                                                                    if (strlen($nationalTopVideoDownload[$i]['Video']['VideoTitle']) >= 50 ) {
                                                                                                            $songTitle = $this->getTextEncode(substr($nationalTopVideoDownload[$i]['Video']['VideoTitle'], 0, 50)) . "..";
                                                                                                    } else {
                                                                                                            $songTitle = $this->getTextEncode($nationalTopVideoDownload[$i]['Video']['VideoTitle']);
                                                                                                    }
                                                                                                ?>

                                                                                                <?php											
                                                                                                    if (strlen($nationalTopVideoDownload[$i]['Video']['ArtistText']) >= 50 ) {
                                                                                                            $ArtistText = $this->getTextEncode(substr($nationalTopVideoDownload[$i]['Video']['ArtistText'], 0, 50)) . "..";
                                                                                                    } else {
                                                                                                            $ArtistText = $this->getTextEncode($nationalTopVideoDownload[$i]['Video']['ArtistText']);
                                                                                                    }
                                                                                                ?>
			<div class="song-title">
			<!--	<a href="/artists/view/<?=base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']);?>/<?= $nationalTopVideoDownload[$i]['Video']['ReferenceID']; ?>/<?= base64_encode($nationalTopVideoDownload[$i]['Video']['provider_type']);?>"><?php echo $this->getTextEncode($songTitle);?></a> -->
                                <a href="/videos/details/<?php echo $nationalTopVideoDownload[$i]['Video']['ProdID']; ?>"><?php echo $this->getTextEncode($songTitle);?></a>
	<?php if('T' == $nationalTopVideoDownload[$i]['Video']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
			</div>
													<div class="artist-name">
														<!-- <a href="/artists/album/"<?php base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']); ?>"><?php echo $nationalTopVideoDownload[$i]['Video']['ArtistText']; ?></a> -->
                                                                                                                <a href="javascript:void(0);"><?php echo $this->getTextEncode($ArtistText); ?></a>
													</div>
												</div>
											</li>
											<?php 
											$k++;
											
        }
    }
?>