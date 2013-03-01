<?php
/*
	 File Name : my_history.ctp 
	 File Description : View page for download history page
	 Author : m68interactive
 */
?>
<style>
.txt-my-history {
	background: url("../img/<?php echo $this->Session->read('Config.language'); ?>/my_history.png") no-repeat scroll 0 0 transparent;
    height: 62px;
    left: 35px;
    overflow: hidden;
    position: relative;
    text-indent: -9999px;
    width: 228px;
}
#recentdownloads-table{
  margin-left: 35px;
}

#recentdownloads-table th{
  background-color: #3d3d3d;
  color: #fff;
  height: 20px;
  text-align: center;
}
#recentdownloads-table td{
    padding: 0 5px;
}

</style>
<?php echo $session->flash();?>
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
<div class="breadCrumb">
<?php
	$html->addCrumb(__('My History', true), '/homes/my_history');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>
<br class="clr">
<div class="txt-my-history">
	<?php __("Download History");?>
</div>

<div id="genreResults">
<table cellspacing="2" cellpadding="0" id="recentdownloads-table">
	<tr>
    <th width="200">
      <p><?php __("Artist");?><p>
    </th>
    <th width="300">
      <p><?php __("Track");?><p>
    </th>
    <th width="200">
      <p><?php __("Date");?><p>
    </th>
    <th width="200">
      <p><?php __("Download");?><p>
    </th>
  </tr>
	<?php
	if(count($downloadResults) != 0)
	{
		$i = 1;
		foreach($downloadResults as $key => $downloadResult):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
	?>
			<!-- <tr onmouseover="this.className = ' hlt';" onmouseout="this.className = '';" <?php // echo $class; ?>> -->
			<tr <?php echo $class; ?>>
				<td width="190" valign="top">

					<?php
						if (strlen($downloadResult['Download']['artist']) >= 19) {
							echo '<span title="'.htmlentities($downloadResult['Download']['artist']).'">' .substr($downloadResult['Download']['artist'], 0, 19) . '...</span>';							
						} else {
							$ArtistName = $downloadResult['Download']['artist'];
							echo $ArtistName;
						}
						
					?>

				</td>
				<td width="290" valign="top" style="max-width:290px;">
					<?php 
						if (strlen($downloadResult['Download']['track_title']) >= 48) {
							echo '<span title="'.htmlentities($downloadResult['Download']['track_title']).'">' .substr($downloadResult['Download']['track_title'], 0, 48) . '...</span>';							
						} else {
							echo $downloadResult['Download']['track_title']; 
					 	}
					?>
				</td>
				<td width="190" valign="top" align="center">
					<?php 
						echo date("Y-m-d",strtotime($downloadResult['Download']['created']));							
					?>
				</td>
				<td width="190" align="center">
					<?php
						$productInfo = $song->getDownloadData($downloadResult['Download']['ProdID'],$downloadResult['Download']['provider_type']);
						$songUrl = shell_exec('perl files/tokengen ' . $productInfo[0]['Full_Files']['CdnPath']."/".$productInfo[0]['Full_Files']['SaveAsName']);                                                
                                                $finalSongUrl = Configure::read('App.Music_Path').$songUrl;
                                                $finalSongUrlArr = str_split($finalSongUrl, ceil(strlen($finalSongUrl)/3));
					?>
						<p>
							<span class="beforeClick" id="download_song_<?php echo $downloadResult['Download']['ProdID']; ?>">
								<?php if($ieVersion > 8 || $ieVersion < 0){ ?>
									<a href='#' onclick='return historyDownloadOthers("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>", "<?php echo urlencode($finalSongUrlArr[0]);?>", "<?php echo urlencode($finalSongUrlArr[1]);?>", "<?php echo urlencode($finalSongUrlArr[2]);?>");'><?php __('Download Now');?></a>
								<?php } else {?>
								<!--[if IE]>
									<a onclick='return historyDownload("<?php echo $downloadResult['Download']['ProdID']; ?>","<?php echo $downloadResult['Download']['library_id']; ?>","<?php echo $downloadResult['Download']['patron_id']; ?>");' href='<?php echo $finalSongUrl; ?>'><?php __('Download Now');?></a> 										
								<![endif]-->
								<?php } ?>
							</span>
							<span class="afterClick" style="display:none;float:left"><?php __("Please Wait...");?></span>
							<span id="download_loader_<?php echo $downloadResult['Download']['ProdID']; ?>" style="display:none;float:right;"><?php echo $html->image('ajax-loader_black.gif'); ?></span>
						</p>
				</td>				
			</tr>
	<?php
		endforeach;
	}else{
		echo 	'<tr><td valign="top" colspan="4"><p>';?><?php echo __("No downloaded songs from this week or last week."); ?><?php echo '</p></td></tr>';
	}
	
	?>
</table>
</div>
</div>