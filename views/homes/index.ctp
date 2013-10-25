                                                <?php echo $session->flash(); ?>					
						<section class="news">
							<div class="top-100">
								<header>
									<h3><?php echo __('National Top 100', true); ?></h3>
									
								</header>
								<nav class="top-100-nav">
									<ul>
										<!--
										<li>
											<a href="#top-100-albums" data-category-type="albums">Albums</a>
										</li>
										-->
										<li>
                    <a href="#top-100-songs" id="songsIDVal" class="active" data-category-type="songs" onclick="showHideGrid('songs')">Songs</a>
										</li>
										<li>
                    <a href="#top-100-videos" id="videosIDVal"data-category-type="videos" onclick="showHideGrid('videos')">Videos</a>
										</li>
									</ul>
									
									
									
								</nav>
        <div class="grids active">
									
            <div id="top-100-songs-grid" class="top-100-grids horiz-scroll active">
                <ul style="width:27064px;">


                                                                               <?php if(is_array($nationalTopDownload) && count($nationalTopDownload) > 0){ ?>

										<?php
											$libId = $this->Session->read('library');
                                							$patId = $this->Session->read('patron');
											$j = 0;
											$k = 2000;
											for($i = 0; $i < count($nationalTopDownload); $i++) {                                                                                            
                                                                                          //hide song if library block the explicit content
                                                                                          if(($this->Session->read('block') == 'yes') && ($nationalTopDownload[$i]['Song']['Advisory'] =='T')) {
                                                                                              continue;
                                                                                          }
                                                                                            
											if($j==5){
												break;
											}
                                                                                        
                                                                                        //$albumArtwork = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['File']['CdnPath']."/".$nationalTopDownload[$i]['File']['SourceURL']);
                                                                                        //$songAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;
                                                                                        
                                                                                        if($i<=9)       
                                                                                        {
                                                                                            $lazyClass      =   '';
                                                                                            $srcImg         =   $nationalTopDownload[$i]['songAlbumImage'];   
                                                                                            $dataoriginal  =   '';  
                                                                                        }
                                                                                        else                //  Apply Lazy Class for images other than first 10.
                                                                                        {
                                                                                             $lazyClass      =   'lazy';
                                                                                             $srcImg         =   $this->webroot.'app/webroot/img/lazy-placeholder.gif';
                                                                                             $dataoriginal   =   $nationalTopDownload[$i]['songAlbumImage'];
                                                                                        }
                                                                                        
                                                                                        ?>
                                                                                    
                                                                                    <?php
                                                                                        
                                                                                        
											

 /* echo $this->webroot."app/webroot/img/news/top-100/grid/bradpaisley250x250.jpg"; */ 
										?>
											<li>
												<div class="top-100-songs-detail">
													<div class="song-cover-container">
														<a href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><img class="<?php echo $lazyClass; ?>" alt="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']). ' - '.$this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])); ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>"  width="250" height="250" /></a>
														<div class="top-100-ranking"><?php
												$slNo = ($i + 1);
												echo $slNo;
											?></div>
														
<?php if($this->Session->read("patron")){ 
                                            if( $this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1)                                                 
                                            {                                                
                                                echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'loadSong("'.$nationalTopDownload[$i]['streamUrl'].'", "'.$nationalTopDownload[$i]['Song']['SongTitle'].'","'.$nationalTopDownload[$i]['Song']['ArtistText'].'","'.$nationalTopDownload[$i]['totalseconds'].'","'.$nationalTopDownload[$i]['Song']['ProdID'].'","'.$nationalTopDownload[$i]['Song']['provider_type'].'");'));
                                                echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample stream", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
                                                echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
                                            }
                                            else if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) 
                                            {                                                 
                    echo $html->image('/img/news/top-100/preview-off.png', array("class" => "preview",  "style" => "cursor:pointer;display:block;", "id" => "play_audio".$i, "onClick" => 'playSample(this, "'.$i.'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.base64_encode($nationalTopDownload[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); 
                    echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$i)); 
                    echo $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$i, "onClick" => 'stopThis(this, "'.$i.'");')); 
            }
    } 

                                        
    if ($this->Session->read('patron'))
    {
        if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d') )
        {

            if($libraryDownload == '1' && $patronDownload == '1') {
		$downloadsUsed =  $this->Download->getDownloadfind($nationalTopDownload[$i]['Song']['ProdID'],$nationalTopDownload[$i]['Song']['provider_type'],$libId,$patId,Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                if($downloadsUsed > 0){
                	$nationalTopDownload[$i]['Song']['status'] = 'avail';
                } else{
                	$nationalTopDownload[$i]['Song']['status'] = 'not';
                }
                if($nationalTopDownload[$i]['Song']['status'] != 'avail') {?>
        <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopDownload[$i]["Song"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopDownload[$i]["Song"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
                            <a  href='javascript:void(0);' title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>' onclick='userDownloadAll("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>");'><?php __('Download Now');?></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
        </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></a>
                    <?php
                    }

            } else {
                ?>
                       <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a> 
                                        
                <?php
                												
            }
        } else {
        ?>
                                                <a class="top-100-download-now-button" href="javascript:void(0);">
                                                    <span title='<?php __("Coming Soon"); ?> ( <?php
                                                    if (isset($nationalTopDownload[$i]['Country']['SalesDate']))
                                                    {
                                                        echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));
                                                    }
                                                    ?> )'>
                                                              <?php __("Coming Soon"); ?>
                                                    </span>
                                                </a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
      ?>



                                        <?php
                                        if ($this->Session->read("patron"))
                                        {
                                            ?> 
                                            <a class="add-to-playlist-button no-ajaxy" href="#" ></a>
														<div class="wishlist-popover">
                                                <?php
                                                if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1)
                                                {
                                                    echo $this->Queue->getQueuesList($this->Session->read('patron'), $nationalTopDownload[$i]["Song"]["ProdID"], $nationalTopDownload[$i]["Song"]["provider_type"], $nationalTopDownload[$i]["Albums"]["ProdID"], $nationalTopDownload[$i]["Albums"]["provider_type"]);
                                                    ?>
                                                    <a class="add-to-playlist" href="#">Add To Queue</a>
                                                <?php                                                
                                                } 
                                                ?>

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
                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])); ?> " href="/artists/view/<?=base64_encode($nationalTopDownload[$i]['Song']['ArtistText']);?>/<?= $nationalTopDownload[$i]['Song']['ReferenceID']; ?>/<?= base64_encode($nationalTopDownload[$i]['Song']['provider_type']);?>"><?php echo $this->getTextEncode($songTitle); ?></a>
													</div>
													<div class="artist-name">                                                                                                            
                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText'])); ?>" href="/artists/album/<?php echo base64_encode($nationalTopDownload[$i]['Song']['ArtistText']); ?>"><?php echo $this->getTextEncode($artistText); ?></a>
													</div>
												</div>
											</li>

                                                                                    <?php 
											$k++;
											}
                                                                                    }
                                                                                     ?>	
										</ul>
									</div>
									<div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
                <ul style="width:47000px;">

                    <?php
                    if (is_array($nationalTopVideoDownload) && count($nationalTopVideoDownload) > 0)
                    {
                        ?>

										<?php
											$j = 0;
											$k = 2000;
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
														<a href="/videos/details/<?php echo $nationalTopVideoDownload[$i]['Video']['ProdID']; ?>"><img src="<?php echo $nationalTopVideoDownload[$i]['videoAlbumImage']; ?>" alt="<?php echo $this->getValidText($nationalTopVideoDownload[$i]['Video']['ArtistText'].' - '.$nationalTopVideoDownload[$i]['Video']['VideoTitle']); ?>" width="423" height="250" /></a>
														<div class="top-100-ranking"><?php
												$slNo = ($i + 1);
												echo $slNo;
											?></div>
<?php if($this->Session->read("patron")){ ?> 														
<!--<a href="#" class="preview"></a>-->
<?php } 

    if($this->Session->read('patron')) {
        if($nationalTopVideoDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) { 

            if($libraryDownload == '1' && $patronDownload == '1') {
		$downloadsUsed =  $this->Videodownload->getVideodownloadfind($nationalTopVideoDownload[$i]['Video']['ProdID'],$nationalTopVideoDownload[$i]['Video']['provider_type'],$libId,$patId,Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                    if($downloadsUsed > 0){
                      $nationalTopVideoDownload[$i]['Video']['status'] = 'avail';
                    } else{
                      $nationalTopVideoDownload[$i]['Video']['status'] = 'not';
                    }
                    if($nationalTopVideoDownload[$i]['Video']['status'] != 'avail' ) {

                            ?>
                            <span class="top-100-download-now-button">
                            <form method="Post" id="form<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" action="/videos/download" class="suggest_text1">
                            <input type="hidden" name="ProdID" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"];?>" />
                            <input type="hidden" name="ProviderType" value="<?php echo $nationalTopVideoDownload[$i]["Video"]["provider_type"]; ?>" />
                            <span class="beforeClick" id="song_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>">
                            <a  title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up two of your downloads, regardless of whether you then press "Cancel" or not.');?>' href='javascript:void(0);' onclick='videoDownloadAll("<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>");'><?php __('Download Now');?></a>
                            </span>
                            <span class="afterClick" id="downloading_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
                            <span id="download_loader_<?php echo $nationalTopVideoDownload[$i]["Video"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?></span>
                            </form>
                            </span>
                            <?php	
                    } else {
                    ?>
                            <a class="top-100-download-now-button" href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></a>
                    <?php
                    }

            } else {
                ?>
                <a class="top-100-download-now-button" href="javascript:void(0);"><?php __("Limit Met");?></a>
                <?php
             												
            }
        } else {
        ?>
            <a class="top-100-download-now-button" title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopVideoDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopVideoDownload[$i]['Country']['SalesDate']));} ?> )' href="javascript:void(0);"><?php __("Coming Soon");?></a>
        <?php
        }
}else{

?>
     <a class="top-100-download-now-button" href='/users/redirection_manager'> <?php __("Login");?></a>


    <?php
    }
    ?>


                                                                                                <?php if($this->Session->read("patron")){ ?> 
														
                                            <a class="add-to-playlist-button no-ajaxy" href="#"></a>
														
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
                                <!--	<a title="<?php echo $this->getTextEncode($nationalTopVideoDownload[$i]['Video']['VideoTitle']); ?>" href="/artists/view/<?=base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']);?>/<?= $nationalTopVideoDownload[$i]['Video']['ReferenceID']; ?>/<?= base64_encode($nationalTopVideoDownload[$i]['Video']['provider_type']);?>"><?php echo $this->getTextEncode($songTitle);?></a> -->
                                        <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopVideoDownload[$i]['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $nationalTopVideoDownload[$i]['Video']['ProdID']; ?>"><?php echo $this->getTextEncode($songTitle);?></a>
	<?php if('T' == $nationalTopVideoDownload[$i]['Video']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
			</div>
													<div class="artist-name">
														<!-- <a href="/artists/album/"<?php base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']); ?>"><?php echo $nationalTopVideoDownload[$i]['Video']['ArtistText']; ?></a> -->
                                                                                                                <a title="<?php echo $this->getValidText($this->getTextEncode($nationalTopVideoDownload[$i]['Video']['ArtistText'])); ?>" href="/artists/album/<?php echo base64_encode($nationalTopVideoDownload[$i]['Video']['ArtistText']); ?>"><?php echo $this->getTextEncode($ArtistText); ?></a>
													</div>
												</div>
											</li>
											<?php 
											$k++;
											}
                                                                                    }
                                                                                     ?>	
										</ul>
									</div>
								</div> <!-- end .grids -->
								
							</div>
							<div class="featured">
								<header>
									<h3>Featured</h3>
								</header>
								<div class="featured-grid horiz-scroll">
            <ul style="width:3690px;">
										<?php
								foreach($featuredArtists as $k => $v){
								
									//$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
									//$image =  Configure::read('App.Music_Path').$albumArtwork;
									if(strlen($v['Album']['AlbumTitle']) > 14){
										$title = substr($v['Album']['AlbumTitle'], 0, 14)."..";
									}else{
										$title = $v['Album']['AlbumTitle'];
									}

                                                                        if(strlen($v['Album']['ArtistText']) > 14){
										$ArtistText = substr($v['Album']['ArtistText'], 0, 14)."..";
									}else{
										$ArtistText = $v['Album']['ArtistText'];
									}


									?>								
										
										<li>
											<div class="featured-album-detail">
												<div class="album-cover-container">												

                                                                        <a href="/artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"><?php echo $html->image($v['featuredImage'],array("height" => "77", "width" => "84", "alt" => $ArtistText. ' - '.$v['Album']['AlbumTitle']  ));?></a>


												</div>
												<div class="album-title">
                                                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])); ?>" href="/artists/view/<?=base64_encode($v['Album']['ArtistText']);?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']);?>"><?php echo $this->getTextEncode($title); ?></a>
												</div>


												<div class="artist-name">
                                                        <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])); ?>" href="/artists/album/<?php echo str_replace('/','@',base64_encode($v['Album']['ArtistText'])); ?>/<?=base64_encode($v['Genre']['Genre'])?>"><?php echo $this->getTextEncode($ArtistText); ?></a>
												</div>
											</div>
										</li>
										
								<?php
								}
								?>	
										
										
									</ul>
								</div>
							</div><!-- end .featured -->
							<div class="coming-soon">
								<header class="clearfix">
									<h3>Coming Soon</h3>
									
	
								</header>
								<div class="coming-soon-filter-container clearfix">
									<nav class="category-filter">
										<ul class="clearfix">
											<!-- <li><a href="#coming-soon-album-grid">Albums</a></li> -->
                    <li><a href="#coming-soon-singles-grid" id="songsIDValComming" class="active" onclick="showHideGridCommingSoon('songs')">Singles</a></li>
                    <li><a href="#coming-soon-videos-grid" id="videosIDValComming" onclick="showHideGridCommingSoon('videos')">Videos</a></li>
										</ul>
										
									</nav>
									<!-- <a href="#" class="view-all">View All</a> -->
	
								</div>
								<?php 
                                                                        
                                                                ?>
								
								
        <div id="coming-soon-singles-grid" class="horiz-scroll active">
									<ul class="clearfix">
                                                                            <?php  
                                                                            $total_songs = count($coming_soon_rs);
                                                                            $sr_no = 0;

                                                                            foreach($coming_soon_rs as $key => $value)
                                                                            {     
                                                                            
                                                                            //hide song if library block the explicit content
                                                                            if(($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] =='T')) {
                                                                                continue;
                                                                            }


                                                                            //$cs_img_url = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                                                                            //$cs_songImage =  Configure::read('App.Music_Path').$cs_img_url;
                                                                            
                                                                             if($sr_no<=9)       
                                                                            {
                                                                                $lazyClass      =   '';
                                                                                $srcImg         =   $value['cs_songImage'];   
                                                                                $dataoriginal  =   '';  
                                                                            }
                                                                            else                //  Apply Lazy Class for images other than first 10.
                                                                            {
                                                                                 $lazyClass      =   'lazy';
                                                                                 $srcImg         =   $this->webroot.'app/webroot/img/lazy-placeholder.gif';
                                                                                 $dataoriginal   =   $value['cs_songImage'];
                                                                            }
                                                                            
                                                                            
                                                                              if($sr_no>=20) break;
                                                                          
                                                                              ?>
										<?php if($sr_no%2==0) {?><li> <?php }?>
											<div class="single-detail">
												<div class="single-cover-container">
																										
                                                                                                        <a href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                                                                        <img class="<?php echo $lazyClass; ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>" alt="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist']).' - '.$this->getTextEncode($value['Song']['SongTitle'])); ?>" width="162" height="162" /></a>
                                                                                                         
                                                                                                <?php if($this->Session->read("patron")){ ?> 													
                                    <a class="add-to-playlist-button no-ajaxy" href="#">
														
													</a>
													<div class="wishlist-popover"> 
                                                                                                            <?php
                                                                                                                
                                                                                                             $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
                                                                                                             echo $wishlist->getWishListMarkup($wishlistInfo,$value["Song"]["ProdID"],$value["Song"]["provider_type"]);
                                                                                                              echo $this->Queue->getSocialNetworkinglinksMarkup();
                                                                                                            ?>

                                                                                                            
														
													</div>

                                                                                                <?php } ?>
												</div>
												<div class="song-title">
                                                        <a title="<?php echo $this->getTextEncode($value['Song']['SongTitle']); ?>" href="/artists/view/<?=base64_encode($value['Song']['ArtistText']);?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']);?>">
                                                                                                            <?php //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";
                                                                                                                    
                                                                                                            $commingSoonSongTitle = $this->getTextEncode($value['Song']['SongTitle']);
                                                                                                            
                                                                                                            if('T' == $value['Song']['Advisory']) { 
                                                                                                                
                                                                                                                if(strlen($commingSoonSongTitle)>13)
                                                                                                                    echo substr($commingSoonSongTitle,0,13)."...";
                                                                                                                 else echo $commingSoonSongTitle; 
                                                                                                                     
                                                                                                            }else{
                                                                                                                if(strlen($commingSoonSongTitle)>20)
                                                                                                                    echo substr($commingSoonSongTitle,0,20)."...";
                                                                                                                else echo $commingSoonSongTitle; 
                                                                                                            }
                                                                                                            
                                                                                                                
                                                                                                                   
                                                                                                             ?>
                                                                                                        </a>	<?php if('T' == $value['Song']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
												</div>
												<div class="artist-name">
                                                        <a title="<?php echo $this->getTextEncode($value['Song']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/','@',base64_encode($value['Song']['ArtistText'])); ?>/<?=base64_encode($value['Song']['Genre'])?>">
                                                                                                        <?php 
                                                                                                                    
                                                                                                        $commingSoonSongArtistTitle = $this->getTextEncode($value['Song']['Artist']);
                                                                                                                    if(strlen($commingSoonSongArtistTitle)>20)
                                                                                                                    echo substr($commingSoonSongArtistTitle,0,20)."..."; 
                                                                                                                    else echo $commingSoonSongArtistTitle;
                                                                                                             ?>
                                                                                                        </a>
												</div>
											</div>
											
										<?php if($sr_no%2==1 || $sr_no==($total_songs-1)) {?> </li> <?php } ?>

                                                                        <?php
                                                                                $sr_no++;
                                                                        }
                                                                        ?>
										
									</ul>
								</div> <!-- end #coming-soon-singles-grid -->
									<div id="coming-soon-videos-grid" class="clearfix horiz-scroll">
									<ul class="clearfix" style="width:3333px;">										
                                                                            <?php                                                                              
                                                                            $total_videos = count($coming_soon_videos);
                                                                            $sr_no = 0;
                                                                            foreach($coming_soon_videos as $key => $value)
                                                                            {  
                                                                                
                                                                                //hide song if library block the explicit content
                                                                            if(($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] =='T')) {
                                                                                continue;
                                                                            }
                                                                                
                                                                           //$albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                                                                           //$videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                                                                            if($sr_no>=20) break;
?>
                                                                            <?php if($sr_no%2==0) {?><li> <?php }?>
											<div class="video-detail">
												<div class="video-cover-container">
													<a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                                                                                        <img  src="<?php echo $value['videoAlbumImage']; ?>"  alt="<?php echo $this->getValidText($this->getTextEncode($value['Video']['Artist']).' - '.$this->getTextEncode($value['Video']['VideoTitle'])); ?>" width="275" height="162" />
                                                                                                        </a>
												<?php if($this->Session->read("patron")){ ?> 
                                    <a class="add-to-playlist-button no-ajaxy" href="#">
														
													</a>
													<div class="wishlist-popover">	
                                                                                                             <?php

                                                                                                                    $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                                                                                                    echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo,$value["Video"]["ProdID"],$value["Video"]["provider_type"]);
                                                                                                                    echo $this->Queue->getSocialNetworkinglinksMarkup();  
                                                                                                                ?>
														
														
													</div>
                                                                                                <?php } ?>
												</div>
												<div class="video-title">

                                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                                                                                            <?php
                                                                                                                                                                                                                                
                                                                                                            $commingSoonVideoTitle= $this->getTextEncode($value['Video']['VideoTitle']);
                                                                                                            
                                                                                                            if('T' == $value['Video']['Advisory']) {
                                                                                                                if(strlen($commingSoonVideoTitle)>15)
                                                                                                                    echo substr($commingSoonVideoTitle,0,15)."..."; 
                                                                                                                    else echo $commingSoonVideoTitle;
                                                                                                            
                                                                                                            }else{
                                                                                                                if(strlen($commingSoonVideoTitle)>20)
                                                                                                                    echo substr($commingSoonVideoTitle,0,20)."..."; 
                                                                                                                    else echo $commingSoonVideoTitle;                                                                                                                
                                                                                                            }                                                                                                                    
                                                                                                                    
                                                                                                           ?> </a><?php if('T' == $value['Video']['Advisory']) { ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>

												</div>
												<div class="artist-name">


                                                                                                            <a title="<?php echo $this->getTextEncode($value['Video']['Artist']); ?>" href="javascript:void(0)">
                                                                                                         <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>20)
                                                                                                                    echo substr($value['Video']['Artist'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['Artist'];
                                                                                                             ?></a>

												<!--	<a href="artists/album/<?php echo str_replace('/','@',base64_encode($value['Video']['ArtistText'])); ?>/<?=base64_encode($value['Video']['Genre'])?>">
                                                                                                         <?php 
                                                                                                                    if(strlen($value['Video']['Artist'])>20)
                                                                                                                    echo substr($value['Video']['Artist'],0,20)."..."; 
                                                                                                                    else echo $value['Video']['Artist'];
                                                                                                             ?></a>  -->
												</div>
											</div>
											
										<?php if($sr_no%2==1 || $sr_no==($total_videos-1)) {?> </li> <?php } ?>

                                                                                <?php
                                                                                        $sr_no++; 
                                                                                }
                                                                                ?>
										
									</ul>
								</div><!-- end videos grid -->
																
							</div> <!-- end coming soon -->
							
							<div class="whats-happening">
								<header>
									<h3>News</h3>
									<!--
									<div class="whats-happening-see-all">
										<a href="#">View All</a>
									</div>
									-->
								</header>
								
								
								
								<div id="whats-happening-grid" class="horiz-scroll">
									<ul class="clearfix" style="width:4400px;">
										<?php $count = 1;
									foreach($news as $key => $value)
									{
										//  $newsText = str_replace('<div', '<p', strip_tags(($value['News']['body']), '<p>'));
                                                                                  $newsText = str_replace('<div', '<p', $value['News']['body']);
                                                                                  $newsText = str_replace('</div>', '</p>', $newsText);
									?>
										<li>
											<div class="post">
												<div class="post-header-image">
													<a href="javascript:void(0);"><img src ='<?php echo $cdnPath. 'news_image/' . $value['News']['image_name'];?>' style="width:417px;height:196px;" alt="<?php echo $this->getValidText($value['News']['subject']); ?>" /></a>
												</div>
												<div class="post-title">
                                                                                                    <a href="javascript:void(0);"><?php echo $this->getValidText($value['News']['subject']); ?></a>
												</div>
												<div class="post-date">
													<?php echo $value['News']['place']?> : <?php echo date( "F d, Y", strtotime($value['News']['created'])) ?>
												</div>
												<!-- <div class="post-excerpt"  id="shortNews<?php echo $value['News']['id']; ?>">
													 <?php 
                                                                                                               // echo $this->getTextEncode(substr($newsText,0, 325));                                                                                                                 
                                                                                                               // echo $this->getTextEncode(substr($newsText,0, strpos($newsText, "</p>")+4));
                                                                                                            ?>		
													 <div class="more">
													 <?php  
                                                                                                            //if(strlen($newsText) > strpos($newsText, "</p>")+4)
                                                                                                           /* if(strlen($newsText) >= 325)
                                                                                                            {
                                                                                                                  ?>
                                                                                                                  <a href="javascript:void(0);" onClick="showhide('detail', '<?php echo $value['News']['id']; ?>')")">More ></a>
                                                                                                                  <?php
                                                                                                            } */ ?>		</div>									
												</div>
												
												<div id="detailsNews<?php //echo $value['News']['id']; ?>" style="display:none" class="post-excerpt">
												<?php //echo $newsText; ?>
								 				 <a href="javascript:void(0);" class="more" onClick="showhide('short', '<?php //echo $value['News']['id']; ?>')">- See Less</a>
												</div> -->
                                                                                                          
                                                                                                  
                                                                                                <?php /*<div id="detailsNews" class="post-excerpt"> */ ?>
                                                                                                <div class="post-excerpt">
                                                                                               <?php /*echo  wordwrap($newsText, 65, "<br />\n", TRUE);*/ ?>
                                                                                               <?php echo $newsText; ?>
                                                                                               <!-- <a href="javascript:void(0);" class="more" onClick="showhide('short', '<?php echo $value['News']['id']; ?>')">- See Less</a> -->
                                                                                               </div>

                                                                                                                         
                                                                                                          
                                                                                                          
												
											</div>
										</li>
										
                                                                        <?php
                                                                                        if($count==10) break;
                                                                                        $count++;
                                                                        }
									?>
                                                                        </ul>
									
									
									
								</div>
							

							
						</section> <!-- end .news -->	

<script language="javascript">
                        function showHideGrid(varType) {

                            var top_100_grids = $('.top-100-grids');
                            var top_100_songs_grid = $('#top-100-songs-grid');
                            var top_100_videos_grid = $('#top-100-videos-grid');

                            var songsIDVal = $('#songsIDVal');
                            var videosIDVal = $('#videosIDVal');

                            if (varType == 'songs') {
                                videosIDVal.removeClass('active');
                                songsIDVal.addClass('active');
                                top_100_videos_grid.removeClass('active');
                                top_100_songs_grid.addClass('active');
                            } else {
                                songsIDVal.removeClass('active');
                                videosIDVal.addClass('active');
                                top_100_songs_grid.removeClass('active');
                                top_100_videos_grid.addClass('active');
                            }
                        }

                        function showHideGridCommingSoon(varType) {

                            var top_100_grids = $('.top-100-grids');
                            var coming_soon_singles_grid = $('#coming-soon-singles-grid');
                            var coming_soon_videos_grid = $('#coming-soon-videos-grid');

                            var songsIDValComming = $('#songsIDValComming');
                            var videosIDValComming = $('#videosIDValComming');



                            if (varType == 'songs') {
                                videosIDValComming.removeClass('active');
                                songsIDValComming.addClass('active');

                                coming_soon_videos_grid.removeClass('active');
                                coming_soon_singles_grid.addClass('active');
                            } else {
                                songsIDValComming.removeClass('active');
                                videosIDValComming.addClass('active');

                                coming_soon_singles_grid.removeClass('active');
                                coming_soon_videos_grid.addClass('active');
                            }

                        }
</script>