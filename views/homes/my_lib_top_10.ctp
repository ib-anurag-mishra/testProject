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
       load_scroller('tab-1');
  });
</script>

<div id="info-part">
	<div id="left-part">
		<div class="text-box vscrollable" style="width:100% !important;left:-10px;">
			<ul>
				<?php if(count($songs) > 0){ ?>
				<?php
					$j =0;
					$k= 1000;
					for($i = 0; $i < count($songs); $i++) {
					if($j==5){
						break;
					}
					echo "<li style='width:596px !important'>";
				?>
				<span class="download">
				<?php
						if($songs[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
							if($libraryDownload == '1' && $patronDownload == '1') {	
							$songs[$i]['Song']['status'] = 'avail1';
								if($songs[$i]['Song']['status'] != 'avail') {
									?>									
									<form method="Post" id="form<?php echo $songs[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
									<input type="hidden" name="ProdID" value="<?php echo $songs[$i]["Song"]["ProdID"];?>" />
									<span class="beforeClick" id="song_<?php echo $songs[$i]["Song"]["ProdID"]; ?>">
									<a href='javascript:void(0);' onclick='userDownloadAll("<?php echo $songs[$i]["Song"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
									</span>
									<span class="afterClick" id="downloading_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...&nbsp&nbsp');?></span>
									<span id="download_loader_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-15px;margin-right:-15px;')); ?></span>
									</form>
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
										$wishlistInfo = $wishlist->getWishlistData($songs[$i]["Song"]["ProdID"]);
										if($wishlistInfo == 'Added to Wishlist') {
										?> 
											<?php __("Added to Wishlist");?>
										<?php 
										} else { 
										?>
											<span class="beforeClick" id="wishlist<?php echo $songs[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $songs[$i]["Song"]["ProdID"]; ?>",this);'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
											<span class="afterClick" id="downloading_<?php echo $songs[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
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
							<span title='<?php __("Coming Soon");?> ( <?php if(isset($songs[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($songs[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span>
						<?php
						}?>
				</span>
					<span class="song_url">
					<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$k, "onClick" => 'playSample(this, "'.$k.'", '.$songs[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
					<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$k)); ?>
					<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$k, "onClick" => 'stopThis(this, "'.$k.'");')); ?>
					</span>
					<span  class="sereial_no">
						<?php
							$slNo = ($i + 1);
							echo $slNo.". ";
						?>
					</span>										
					<span class="song">
						<?php										
						if (strlen($songs[$i]['Song']['SongTitle']) >= 35 ) {
							echo '<span title="'.$songs[$i]['Song']['SongTitle'].'">' . substr($songs[$i]['Song']['SongTitle'], 0, 35) . "..." . "</span>";
						} else {
							echo $songs[$i]['Song']['SongTitle'];
						}
						?>				
						<span class="singer">
							<?php
								echo "<a href='/artists/album/".base64_encode($songs[$i]['Song']['ArtistText'])."'>".substr($songs[$i]['Song']['ArtistText'], 0, 35)."</a>";
							?>
						</span>										
					</span>
				<?php 
					$k++;
					}
					echo "</li>";
				}else{
					echo "<p>No Songs downloaded yet</p>";
				}
				?>
				
			</ul>
		</div>
	</div>
</div>								