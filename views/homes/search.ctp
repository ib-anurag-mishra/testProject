<?php
/*
	 File Name : search.ctp
	 File Description : View page for  search
	 Author : m68interactive
 */
?>
<div class="breadCrumb">
<?php
	$html->addCrumb('Search Results');	
	echo $html->getCrumbs('&nbsp>&nbsp;','Home','/homes');
?>
</div>
<div style="float:left;width:100%;">
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
<?php
if(count($searchResults) != 0){
?>
<div id="genreArtist" class="links" <?php if(isset($composer)){ ?> style="width:192px;" <?php }else{ ?> style="width:215px;" <?php } ?>>
	<?php echo $paginator->sort(__("Artist") , 'Song.Artist', array('url' => array("?"=>$searchKey)))  . $paginator->sort('`', 'Song.Artist', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<?php if(isset($composer)){?>
<div id="genreComposer" class="links" <?php if(isset($composer)){ ?> style="width:180px;" <?php }else{ ?> style="width:225px;" <?php } ?>><?php __("Composer");?></div>
<?php } ?>
<div id="genreAlbum" class="links" <?php if(isset($composer)){ ?> style="width:192px;" <?php }else{ ?> style="width:215px;" <?php } ?>>
	<?php echo $paginator->sort(__("Album") , 'Song.Title', array('url' => array("?"=>$searchKey))) . $paginator->sort('`', 'Song.Title', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreTrack" class="links" <?php if(isset($composer)){ ?> style="width:192px;" <?php }else{ ?> style="width:291px;" <?php } ?>>
	<?php echo $paginator->sort(__("Track") , 'Song.SongTitle', array('url' => array("?"=>$searchKey))) . $paginator->sort('`',  'Song.SongTitle', array('url' => array("?"=>$searchKey), 'id' => 'sort_arrows'));?>
</div>
<div id="genreDownload" style="width:190px"><?php __("Download");?></div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($searchResults) != 0)
	{
		$i = 1;
		foreach($searchResults as $key => $searchResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		//	if($searchResult['Country']['Territory'] == $this->Session->read('territory')){
	?>
			<tr style="margin-left:68px" <?php echo $class; ?>>
				<td width="210" valign="top">
					<p>
						<?php
							$name = $searchResult['Song']['ArtistText'];
							if (strlen($searchResult['Song']['ArtistText']) >= 19) {
								$ArtistName = substr($searchResult['Song']['ArtistText'], 0, 22) . '..';
								if (strlen($searchResult['Song']['ArtistText']) >= 60) {
									$searchResult['Song']['ArtistText'] = substr($searchResult['Song']['ArtistText'], 0, 60) . '...';
								}
								echo '<span title="'.htmlentities($searchResult['Song']['ArtistText']).'">'.$html->link($ArtistName, array('controller' => 'artists', 'action' => 'album', base64_encode($name))).'</span>';
						?>
						<?php
							} else {
								$ArtistName = $searchResult['Song']['ArtistText'];
								echo $html->link($ArtistName, array('controller' => 'artists', 'action' => 'album', base64_encode($name)));
							}
						?>
					</p>
				</td>
				<?php if(isset($composer)){?>
				<td width="180" valign="top">
					<p>
						<?php
						if (strlen($searchResult['Participant']['Name']) >= 17) {
							$ArtistName = substr($searchResult['Participant']['Name'], 0, 17) . '...';
							echo '<span title="'.htmlentities($searchResult['Participant']['Name']).'">'.$ArtistName.'</span>';
						?>
						<?php
						} else {
							$ArtistName = $searchResult['Participant']['Name'];
							echo $ArtistName;
						} 
					?>
					</p>
				</td>
				<?php } ?>
				<td width="210" valign="top">
					<a href="/artists/view/<?php echo base64_encode($searchResult['Song']['ArtistText']); ?>/<?php echo $searchResult['Song']['ReferenceID'];  ?>" >
					<p>
					<?php
						if (strlen($searchResult['Song']['Title']) >= 19) {
							echo '<span title="'.htmlentities($searchResult['Song']['Title'], ENT_QUOTES, "UTF-8").'">' . substr(htmlentities($searchResult['Song']['Title'], ENT_NOQUOTES, "UTF-8"), 0, 19) . '...' . '</span>'; 
						} else { 
							echo $searchResult['Song']['Title'];
						}
						
					?>
					</p>
					</a>
				</td>
				<td <?php if(isset($composer)){ ?> style="width:230px;" <?php }else{ ?> style="width:274px;" <?php } ?> valign="top">
					<p>
					<?php 
						if (strlen($searchResult['Song']['SongTitle']) > 25) {
							echo '<span title="'.htmlentities($searchResult['Song']['SongTitle'], ENT_QUOTES, "UTF-8").'">' . substr(htmlentities($searchResult['Song']['SongTitle'], ENT_NOQUOTES, "UTF-8"), 0, 25) . '...</span>';
						} else {
							echo $searchResult['Song']['SongTitle']; 
					 	}
						if ($searchResult['Song']['Advisory'] == 'T') {
							echo '<font class="explicit"> (Explicit)</font>';
						}
						if($searchResult['Country']['SalesDate'] <= date('Y-m-d')) {
							$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Sample_Files']['CdnPath']."/".$searchResult['Sample_Files']['SaveAsName']);
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
							echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "style" => "cursor:pointer;display:block;", "id" => "play_audio".$key, "onClick" => 'playSample(this, "'.$key.'", "'.urlencode($finalSongUrlArr[0]).'", "'.urlencode($finalSongUrlArr[1]).'", "'.urlencode($finalSongUrlArr[2]).'", '.$searchResult["Song"]["ProdID"].', "'.$this->webroot.'");'));
							echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio".$key));
							echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio".$key, "onClick" => 'stopThis(this, "'.$key.'");'));
						}
					?>
					</p>
				</td>
				<td width="196" align="center" style="padding-left:10px" valign="top">
					<?php
						if($searchResult['Country']['SalesDate'] <= date('Y-m-d'))
						{
							if($libraryDownload == '1' && $patronDownload == '1') {
								if($searchResult['Song']['status'] != 'avail'){
									$songUrl = shell_exec('perl files/tokengen ' . $searchResult['Full_Files']['CdnPath']."/".$searchResult['Full_Files']['SaveAsName']);
									$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
									$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
						 ?>
									<p>
										<span class="beforeClick" id="song_<?php echo $searchResult["Song"]["ProdID"]; ?>">
											<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
												<a href='#' title='<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not.");?>' onclick='return userDownloadOthers("<?php echo $searchResult["Song"]["ProdID"]; ?>","<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download Now');?></a>
											<?php } else {?>
											<!--[if IE]>
												<a title='<?php __("IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not.");?>' onclick='return userDownloadIE("<?php echo $searchResult["Song"]["ProdID"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
											<![endif]-->
											<?php } ?>
										</span>
										<span class="afterClick" id="downloading_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;float:left"><?php __("Please Wait...");?></span>
										<span id="download_loader_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
									</p>
					<?php		}else {
									?><a href='/homes/my_history' title='<?php __("You have already downloaded this song. Get it from your recent downloads");?>'><?php __("Downloaded");?></a><?php
								}
							}
                            else {
								if($libraryDownload != '1'){
									$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
                                    $wishlistCount = $wishlist->getWishlistCount();
                                    if($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount){
                    ?>
										<p><?php __("Limit Exceeded");?></p>
					<?php
									}
                                    else{
										$wishlistInfo = $wishlist->getWishlistData($searchResult["Song"]["ProdID"]);
										if($wishlistInfo == 'Added to Wishlist'){
									?>
											<p><?php __("Added to Wishlist");?></p>
								<?php 	}
										else { ?>
											<p>
											<span class="beforeClick" id="wishlist<?php echo $searchResult["Song"]["ProdID"]; ?>"><a href='#' onclick='Javascript: addToWishlist("<?php echo $searchResult["Song"]["ProdID"]; ?>",this);'><?php __("Add to wishlist");?></a></span><span id="wishlist_loader_<?php echo $searchResult["Song"]["ProdID"]; ?>" style="display:none;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
											<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>

											</p>
								<?php
										}
                                    }
							}
							else { ?>
								<p><?php __("Limit Exceeded");?></p>
							<?php
							}
						}
					}
					else {
					?>
						<span title='<?php __("Coming Soon");?> ( <?php echo date("F d Y", strtotime($searchResult['Country']['SalesDate'])); ?> )'><?php __("Coming Soon");?></span>
					<?php
					}
					?>
				</td>
			</tr>
	<?php
	//	}
		endforeach;
	}
	else {
		echo '<td width="180" valign="top"><p><?php __("No records found");?></p></td>';
	}
	?>
</table>
</div>
<div class="paging">
    <?php
		if(isset($composer)){
			$searchKey = $searchKey."&search_type=".$composer;
		}
        $paginator->options(array('url' => array("?"=>$searchKey)));
    ?>
	<?php 
		echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
		echo "&nbsp;";
		echo $paginator->numbers();
		echo "&nbsp;";
		echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
	?>
</div>
<?php
	}
	else {
		echo '<table><tr><td width="180" valign="top"><p><div class="paging">';
		echo __("No records found");
		echo '</div><br class="clr"></td></tr></table>';
	}
?>
</div>