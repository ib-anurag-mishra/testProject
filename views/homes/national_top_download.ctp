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
        load_scroller('tab-2');
   });
</script>
<div id="info-part">
							<div id="left-part">
								<div class="text-box vscrollable" id='tab-2-scroller-div' style="width:100%;left: -8px;">
									<ul>
										<?php if(count($nationalTopDownload) > 0){ ?>
										<?php
											$j = 0;
											$k = 2000;
											for($i = 0; $i < 100; $i++) {
											if($j==5){
												break;
											}
											echo "<li style='width:596px'>";
										?>
										<span class="download">
										<?php
												if($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
													if($libraryDownload == '1' && $patronDownload == '1') {	
														$nationalTopDownload[$i]['Song']['status'] = 'avail1';
														if($nationalTopDownload[$i]['Song']['status'] != 'avail') {
															$songUrl = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['Full_Files']['CdnPath']."/".$nationalTopDownload[$i]['Full_Files']['SaveAsName']);
															$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
															$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
															?>
															<span class="beforeClick" id="song_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>">
															<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
															<a href='#' onclick='return userDownloadOthers_top("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><label class="dload" style="width:120px;cursor:pointer;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><?php __('Download Now');?></label></a>
															<?php } else {?>
															<!--[if IE]>
															<label class="dload" style="width:120px;" title='<?php __('IMPORTANT:  Please note that once you press "Download Now" you have used up one of your downloads, regardless of whether you then press "Cancel" or not.');?>'><a style="cursor:pointer;" onclick='return userDownloadIE_top("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a></label>
															<![endif]-->
															<?php } ?>
															</span>
															<span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __('Please Wait...');?></span>
															<span id="download_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
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
																$wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);
																if($wishlistInfo == 'Added to Wishlist') {
																?> 
																	<?php __("Added to Wishlist");?>
																<?php 
																} else { 
																?>
																	<span class="beforeClick" id="wishlist<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>"><a href='JavaScript:void(0);' onclick='Javascript: addToWishlist("<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>",this);'><?php __("Add to Wishlist");?></a></span><span id="wishlist_loader_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'padding-top:30px')); ?></span>
																	<span class="afterClick" id="downloading_<?php echo $nationalTopDownload[$i]["Song"]["ProdID"]; ?>" style="display:none;"><?php __("Please Wait...");?></span>
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
													<span title='<?php __("Coming Soon");?> ( <?php if(isset($nationalTopDownload[$i]['Country']['SalesDate'])){ echo date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));} ?> )'><?php __("Coming Soon");?></span>
												<?php
												}?>
										</span>
											<span class="song_url" >
											<?php
												$songUrl = shell_exec('perl files/tokengen ' . $nationalTopDownload[$i]['Sample_Files']['CdnPath']."/".$nationalTopDownload[$i]['Sample_Files']['SaveAsName']);
												$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
												$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
											?>											
											<?php if(isset($finalSongUrl)) {?>
											<?php echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$k, "onClick" => 'playSample(this, "'.$k.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$nationalTopDownload[$i]['Song']['ProdID'].', "'.$this->webroot.'");')); ?>
											<?php echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$k)); ?>
											<?php echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$k, "onClick" => 'stopThis(this, "'.$k.'");')); ?>										
											<?php } ?>
											</span>
										<span  class="sereial_no">
											<?php
												$slNo = ($i + 1);
												echo $slNo.". ";
											?>
										</span>											
											<span class="song">
												<?php											
												if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 35 ) {
													echo '<span title="'.$nationalTopDownload[$i]['Song']['SongTitle'].'">' . substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 35) . ".." . "</span>";
												} else {
													echo $nationalTopDownload[$i]['Song']['SongTitle'];
												}
												?>				
												<span class="singer">
													<?php
														echo "<a href='/artists/album/".base64_encode($nationalTopDownload[$i]['Song']['ArtistText'])."'>".substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 35)."</a>";
													?>
												</span>									
											</span>
										<?php 
											$k++;
											}
											echo "</li>";
										}
										?>
										
									</ul>
								</div>
							</div>
						</div>
						