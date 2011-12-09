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

<script type="text/javascript">
jQuery(document).ready(function() {
      load_scroller('genre-tab-<?php echo $tab_no; ?>-content');
  });
</script>
<div id="info-part">
	<div id="left-part">
		<div class="text-box vscrollable">
			<ul>
				<?php if(count($genre_info) > 0){ ?>
				<?php
					$j =0;
					$k = $tab_no * 10000;
					for($i = 0; $i < count($genre_info); $i++) {
					if($j==5){
						break;
					}
					echo "<li>";
				?>
				<span class="download">
				<?php
						if($genre_info[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
							if($libraryDownload == '1' && $patronDownload == '1') {
								$genre_info[$i]['Song']['status'] = 'avail1';
								if($genre_info[$i]['Song']['status'] != 'avail') {
									$songUrl = shell_exec('perl files/tokengen ' . $genre_info[$i]['Full_Files']['CdnPath']."/".$genre_info[$i]['Full_Files']['SaveAsName']);
									$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
									$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
									?>
									<span class="beforeClick" id="song_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>">
									<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
									<a href='#' onclick='return userDownloadOthers_top("<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
									<?php } else {?>
									<!--[if IE]>
									<label class="dload" style="width:120px;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><a style="cursor:pointer;" onclick='return userDownloadIE_top("<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a></label>
									<![endif]-->
									<?php } ?>
									</span>
									<span class="afterClick" id="downloading_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...');?></span>
									<span id="download_loader_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
									<?php	
								} else {
								?>
									<a href='/homes/my_history'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __('Downloaded'); ?></label></a>
								<?php
								}
							} else {
								if($libraryDownload != '1') {
									$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
									$wishlistCount = $wishlist->getWishlistCount();
									if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount) {
									?> 
										<?php __("Limit Exceeded");?> 
									<?php
									} else {
										$wishlistInfo = $wishlist->getWishlistData($genre_info[$i]["Song"]["ProdID"]);
										if($wishlistInfo == 'Added to Wishlist') {
										?> 
											<?php __("Added to Wishlist");?>
										<?php 
										} else { 
										?>
											<span class="beforeClick" id="wishlist<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>",this);'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
											<span class="afterClick" id="downloading_<?php echo $genre_country[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
										<?php	
										}
									}

								} else { 
								?>
									<?php __("Limit Exceeded");?>
								<?php	
								}												
							}
						} else {
						?>
							<span title='<?php __("Coming Soon");?> ( <?php if(isset($genre_country[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($genre_country[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span>
						<?php
						}?>
				</span>
					<span style="float:left;margin-left:25px;">
					<?php
						$songUrl = shell_exec('perl files/tokengen ' . $genre_info[$i]['Sample_Files']['CdnPath']."/".$genre_info[$i]['Sample_Files']['SaveAsName']);
						$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
						$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>											
					<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$k, "onClick" => 'playSample(this, "'.$k.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$genre_info[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
					<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$k)); ?>
					<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$k, "onClick" => 'stopThis(this, "'.$k.'");')); ?>										
					</span>
				<span class="song">
					<?php											
					if (strlen($genre_info[$i]['Song']['SongTitle']) >= 23 ) {
						echo '<span title="'.$genre_info[$i]['Song']['SongTitle'].'">' . substr($genre_info[$i]['Song']['SongTitle'], 0, 23) . "..." . "</span>";
					} else {
						echo $genre_info[$i]['Song']['SongTitle'];
					}
					?>				
						<span class="singer">
							<?php
								echo "<a href='/artists/album/".base64_encode($genre_info[$i]['Song']['ArtistText'])."'>".substr($genre_info[$i]['Song']['ArtistText'], 0, 23)."</a>";
							?>
						</span>										
				</span>
				<?php 
					$k++;
					}
					echo "</li>";
				}else{
					echo "No Songs Downloaded for this Genre.";
				}
				?>
				
			</ul>
		</div>
	</div>
</div>