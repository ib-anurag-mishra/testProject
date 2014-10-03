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
                    $libId = $this->Session->read('library');
                    $patId = $this->Session->read('patron');
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
                                $downloadsUsed =  $this->Download->getDownloadfind($genre_info[$i]['Song']['ProdID'],$genre_info[$i]['Song']['provider_type'],$libId,$patId,Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
                                if($downloadsUsed > 0){
	                                $genre_info[$i]['Song']['status'] = 'avail';
                                } else{
                                	$genre_info[$i]['Song']['status'] = 'not';
                                }
								if($genre_info[$i]['Song']['status'] != 'avail') {
									?>							
									<form method="Post" id="form<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" action="/homes/userDownload" class="suggest_text1">
									<input type="hidden" name="ProdID" value="<?php echo $genre_info[$i]["Song"]["ProdID"];?>" />
									
									<input type="hidden" name="ProviderType" value="<?php echo $genre_info[$i]["Song"]["provider_type"]; ?>" />
									
									<span class="beforeClick" id="song_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>">
									<a href='javascript:void(0);' onclick='userDownloadAll("<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.');?>'><?php __('Download Now');?></label></a>
									</span>
									<span class="afterClick" id="downloading_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait');?>...&nbsp;&nbsp;</span>
									<span id="download_loader_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-15px;margin-right:-15px')); ?></span>
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
										<?php __("Limit Met");?>
									<?php
									} else {
										$wishlistInfo = $wishlist->getWishlistData($genre_info[$i]["Song"]["ProdID"]);
										if($wishlistInfo == 'Added To Wishlist') {
										?> 
											<?php __("Added To Wishlist");?>
										<?php 
										} else { 
										?>
											<span class="beforeClick" id="wishlist<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>","<?php echo $genre_info[$i]["Song"]["provider_type"]; ?>");'><?php __("Add To Wishlist");?></a></span><span id="wishlist_loader_<?php echo $genre_info[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
											<span class="afterClick" id="downloading_<?php echo $genre_country[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait"); ?>...</span>
										<?php	
										}
									}

								} else { 
								?>
									<?php __("Limit Met");?>
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
					<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$k, "onClick" => 'playSample(this, "'.$k.'", '.$genre_info[$i]['Song']['ProdID'].', "'.base64_encode($genre_info[$i]['Song']['provider_type']).'", "'.$this->webroot.'");')); ?>
					<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$k)); ?>
					<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$k, "onClick" => 'stopThis(this, "'.$k.'");')); ?>										
					</span>
				<span class="song">
					<?php											
					if (strlen($genre_info[$i]['Song']['SongTitle']) >= 23 ) {
						echo '<span title="'.$this->getTextEncode($genre_info[$i]['Song']['SongTitle']).'">' . $this->getTextEncode(substr($genre_info[$i]['Song']['SongTitle'], 0, 23)) . "..." . "</span>";
					} else {
						echo $this->getTextEncode($genre_info[$i]['Song']['SongTitle']);
					}
					?>				
						<span class="singer">
							<?php
								echo "<a href='/artists/album/".base64_encode($genre_info[$i]['Song']['ArtistText'])."'>".$this->getTextEncode(substr($genre_info[$i]['Song']['ArtistText'], 0, 23))."</a>";
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