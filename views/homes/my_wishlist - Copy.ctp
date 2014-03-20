<?php
/*
	 File Name : my_wishlist.ctp 
	 File Description : View page for wishlist information
	 Author : m68interactive
 */
?>
<style>
.txt-my-wishlist {
	height: 60px;
    left: 39px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 228px;
	background:url(../img/<?php echo $this->Session->read('Config.language'); ?>/my_wishlist.png) no-repeat;
}

#wishlist-table{
  margin-left: 35px;
}

#wishlist-table th{
  background-color: #3d3d3d;
  color: #fff;
  height: 20px;
  text-align: center;
  /*border-left: 1px solid #fff;
  border-right: 1px solid #fff;*/
}
#wishlist-table td{
  /*border-left: 1px solid #fff;
  border-right: 1px solid #fff;*/
  padding: 0 5px;
}
</style>

<div class="breadCrumb">
<?php
	$html->addCrumb('My Wishlist', '/homes/my_wishlist');
	echo $html->getCrumbs('>', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<?php echo $session->flash();?>
<div class="txt-my-wishlist">
	<?php __("Wishlist");?>
</div>

<div id="wishlistText"><?php echo $page->getPageContent('wishlist'); ?></div>
<div id="genreResults">
	<table cellspacing="2" cellpadding="0" id="wishlist-table">
	<tr>
    <th width="180">
      <p><?php __("Artist");?></p>
    </th>
    <th width="200">
      <p><?php __("Album");?></p>
    </th "150px">
    <th width="240">
      <p><?php __("Track");?></p>
    </th>
    <th width="150">
      <p><?php __("Download");?></p>
    </th>
    <th width="150">
      <p><?php __("Remove");?></p>
    </th>
  </tr>
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
			<tr <?php echo $class; ?>>
				<td width="170" valign="top">

					<?php
						if (strlen($wishlistResult['Wishlist']['artist']) >= 19) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResult['Wishlist']['artist'])).'">' .$this->getTextEncode(substr($wishlistResult['Wishlist']['artist'], 0, 19)) . '...</span>';
						} else {
							$ArtistName = $wishlistResult['Wishlist']['artist'];
							echo $this->getTextEncode($ArtistName);
						}
						
					?>

				</td>
				<td width="190" valign="top">
					<?php
						if (strlen($wishlistResult['Wishlist']['album']) >= 24) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResult['Wishlist']['album'])).'">' .$this->getTextEncode(substr($wishlistResult['Wishlist']['album'], 0, 24)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResult['Wishlist']['album']);
						}
						
					?>
				</td>
				<td width="230" valign="top">
					<?php 
						if (strlen($wishlistResult['Wishlist']['track_title']) >= 48) {
							echo '<span title="'.$this->getTextEncode(htmlentities($wishlistResult['Wishlist']['track_title'])).'">' .$this->getTextEncode(substr($wishlistResult['Wishlist']['track_title'], 0, 48)) . '...</span>';
						} else {
							echo $this->getTextEncode($wishlistResult['Wishlist']['track_title']);
					 	}
					?>
				</td>
				<td width="140" align="center">
					<?php										
						$productInfo = $song->getDownloadData($wishlistResult['Wishlist']['ProdID'],$wishlistResult['Wishlist']['provider_type']);
						if($libraryDownload == '1' && $patronDownload == '1'){
							$songUrl = shell_exec(Configure::read('App.tokengen') . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
							$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
							$finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
							<p>
								<span class="beforeClick" id="wishlist_song_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>">
									<![if !IE]>
										<a href='#' title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadOthers("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>" , "<?php echo $wishlistResult['Wishlist']["provider_type"]; ?>");'><?php __('Download Now');?></a>
									<![endif]>
									<!--[if IE]>
									<a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='return wishlistDownloadIE("<?php echo $wishlistResult['Wishlist']['ProdID']; ?>", "<?php echo $wishlistResult['Wishlist']['id']; ?>" , "<?php echo $wishlistResult['Wishlist']["provider_type"]; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a>
									<![endif]-->							
								</span>
								<span class="afterClick" id="downloading_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:left"><?php __('Please Wait...');?></span>
								<span id="wishlist_loader_<?php echo $wishlistResult['Wishlist']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
							</p>
					<?php	}
						else{ ?>
							<p><?php __("Limit Met");?></p>
						<?php
						}
					?>
				</td>
				<td width="140" align="center">
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