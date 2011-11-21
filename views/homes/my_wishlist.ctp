<?php
/*
	 File Name : my_wishlist.ctp
	 File Description : View page for wishlist information
	 Author : m68interactive
 */
?>
<?php echo $javascript->link('freegal_genre_curvy'); ?>
<?php echo $session->flash();?>
<div id="genre">
	<?php __("Wishlist");?>
</div>
<br class="clr">
<div id="wishlistText"><?php echo $page->getPageContent('wishlist'); ?></div>
<div id="genreArtist">
	<P><?php __("Artist");?></p>
</div>
<div id="genreAlbum">
	<P><?php __("Album");?></p>
</div>
<div id="genreTrack" style="width:250px;">
	<P><?php __("Track");?></p>
</div>
<div id="genreDownload">
	<?php __("Download");?>
</div>
<div id="genreDownload">
	
</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0">
	<?php
	if(count($wishlistResults) != 0)
	{
		$i = 1;
		foreach($wishlistResults as $key => $wishlistResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
	?>
			<!-- <tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>> -->
			<tr <?php echo $class; ?>>
				<td width="180" valign="top">

					<?php
						if (strlen($wishlistResult['Wishlist']['artist']) >= 19) {
							echo '<span title="'.htmlentities($wishlistResult['Wishlist']['artist']).'">' .substr($wishlistResult['Wishlist']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $wishlistResult['Wishlist']['artist'];
							echo $ArtistName;
						}
						
					?>

				</td>
				<td width="200" valign="top">
					<?php
						if (strlen($wishlistResult['Wishlist']['album']) >= 24) {
							echo '<span title="'.htmlentities($wishlistResult['Wishlist']['album']).'">' .substr($wishlistResult['Wishlist']['album'], 0, 24) . '...</span>';							
						} else { 
							echo $wishlistResult['Wishlist']['album'];
						}
						
					?>
				</td>
				<td width="250" valign="top">
					<?php 
						if (strlen($wishlistResult['Wishlist']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($wishlistResult['Wishlist']['track_title']).'">' .substr($wishlistResult['Wishlist']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $wishlistResult['Wishlist']['track_title']; 
					 	}
					?>
				</td>
				<td width="150" align="center">
					<?php										
						$productInfo = $song->getDownloadData($wishlistResult['Wishlist']['ProdID']);
						if($libraryDownload == '1' && $patronDownload == '1'){
							$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
							<p>
								<span class="beforeClick" id="wishlist_song_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>">
									<![if !IE]>
										<a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download Now');?></a>
									<![endif]>
									<!--[if IE]>
									<a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadIE("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
									<![endif]-->							
								</span>
								<span class="afterClick" id="downloading_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:left"><?php __('Please Wait...');?></span>
								<span id="wishlist_loader_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
					<?php	}
						else{ ?>
							<p><?php __("Limit Exceeded");?></p>
						<?php
						}
					?>
				</td>
				<td width="150" align="center">
					<?php echo $html->link('Remove', array('controller' => 'homes', 'action' => 'removeWishlistSong', 'id'=>$wishlistResult['Wishlist']['id'])); ?>
				</td>	
			</tr>
	<?php
		endforeach;
	}else{
		echo 	'<tr><td width="280" valign="top"><p><?php __("You have no songs in your wishlist.");?></p></td></tr>';
	}
	
	?>
</table>
</div>