<section class="artist-page">
	<div class="breadcrumbs">
		<?php
		$libId = $this->Session->read('library');
		$patId = $this->Session->read('patron');
		if (!empty($_SERVER['HTTP_REFERER']))
		{
			$reffer_url = $_SERVER['HTTP_REFERER'];
		}
		if (isset($genre))
		{
			$genre_text_conversion = array(
					"Children's Music" => "Children's",
					"Classic" => "Soundtracks",
					"Comedy/Humor" => "Comedy",
					"Country/Folk" => "Country",
					"Dance/House" => "Dance",
					"Easy Listening Vocal" => "Easy Listening",
					"Easy Listening Vocals" => "Easy Listening",
					"Folk/Blues" => "Folk",
					"Folk/Country" => "Folk",
					"Folk/Country/Blues" => "Folk",
					"Hip Hop Rap" => "Hip-Hop Rap",
					"Rap/Hip-Hop" => "Hip-Hop Rap",
					"Rap / Hip-Hop" => "Hip-Hop Rap",
					"Jazz/Blues" => "Jazz",
					"Kindermusik" => "Children's",
					"Miscellaneous/Other" => "Miscellaneous",
					"Other" => "Miscellaneous",
					"Age/Instumental" => "New Age",
					"Pop / Rock" => "Pop/Rock",
					"R&B/Soul" => "R&B",
					"Soundtracks" => "Soundtrack",
					"Soundtracks/Musicals" => "Soundtrack",
					"World Music (Other)" => "World Music"
			);

			$genre_crumb_name = isset($genre_text_conversion[trim($genre)]) ? $genre_text_conversion[trim($genre)] : trim($genre);

			$html->addCrumb(__('All Genre', true), '/genres/view/');
			if ($genre_crumb_name != "")
			{
				$html->addCrumb($this->getTextEncode($genre_crumb_name), '/genres/view/' . base64_encode($genre_crumb_name));
			}

			echo $html->getCrumbs(' > ', __('Home', true), '/homes');
			echo " > ";
			if (strlen($artisttext) >= 30)
			{
				$artisttext = substr($artisttext, 0, 30) . '...';
			}
			echo $this->getTextEncode($artisttext);
		}
		else
		{
			echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
			echo " > ";
			echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
			echo " > ";
			if (strlen($artisttext) >= 30)
			{
				$artisttext = substr($artisttext, 0, 30) . '...';
			}
			echo $this->getTextEncode($artisttext);
		}

		function ieversion()
		{
			ereg('MSIE ([0-9]\.[0-9])', $_SERVER['HTTP_USER_AGENT'], $reg);
			if (!isset($reg[1]))
			{
				return -1;
			}
			else
			{
				return floatval($reg[1]);
			}
		}

		$ieVersion = ieversion();
		?>
	</div>



	<header class="clearfix">
		<?php
		if (isset($artisttitle))
		{
			?>
		<h2>
			<?php echo $this->getTextEncode($artisttitle); ?>
		</h2>
		<?php
		}
		?>
		<div class="faq-link">
			Need help? Visit our <a href="/questions">FAQ section.</a>
		</div>
	</header>

	<!-- Album Section -->
	<?php
	if (!empty($albumData) || !empty($artistVideoList))
	{
		if (!empty($albumData))
		{
			?>
	<h3><?php __('Albums'); ?></h3>
	<div class="album-shadow-container">
		<div class="album-scrollable horiz-scroll">
			<ul style="width: 4500px">
				<?php
				foreach ($albumData as $album_key => $album):
				//hide album if library block the explicit content
				if (($this->Session->read('block') == 'yes') && ($album['Album']['Advisory'] == 'T'))
				{
					continue;
				}
				?>
				<li>
					<div class="album-container">
						<?php                                                         
                                                        $albumArtwork = $this->Token->artworkToken($album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']);
                                                 ?>
						<a
							href="/artists/view/<?php echo str_replace('/', '@', base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">
							<img
							src="<?php echo Configure::read('App.Music_Path') . $albumArtwork; ?>"
							width="162" height="162" alt="">
						</a>

						<?php
						if ($this->Session->read('library_type') == 2 && !empty($album['albumSongs'][$album['Album']['ProdID']]) && $this->Session->read("patron"))
						{
							echo $this->Queue->getAlbumStreamLabel($album['albumSongs'][$album['Album']['ProdID']]);
							?>
						<a class="add-to-playlist-button no-ajaxy"
							href="javascript:void(0)"></a>
						<div class="wishlist-popover">
							<input type="hidden" id="<?= $album['Album']['ProdID'] ?>"
								value="album" /> <a class="add-to-playlist"
								href="javascript:void(0)">Add To Playlist</a>
						</div>
						<?php
						}
						?>
						<a
							href="/artists/view/<?php echo str_replace('/', '@', base64_encode($artisttext)); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">


							<?php
							$image = Configure::read('App.Music_Path') . $albumArtwork;
							?>

						</a>
					</div>
					<div class="album-title">
						<a
							title="<?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>"
							href="/artists/view/<?php echo str_replace('/', '@', base64_encode($album['Album']['ArtistText'])); ?>/<?php echo $album['Album']['ProdID']; ?>/<?php echo base64_encode($album['Album']['provider_type']); ?>">

							<b> <?php
							if (strlen($album['Album']['AlbumTitle']) >= 50)
							{
								$album['Album']['AlbumTitle'] = substr($album['Album']['AlbumTitle'], 0, 50) . '...';
							}
							?> <?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>
						</b>
						</a>
					</div>
					<div class="genre">
						<?php
						echo __('Genre') . ": " . $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre'])), array("title" => $this->getTextEncode($album['Genre']['Genre']))) . '<br />';
						if ($album['Album']['ArtistURL'] != '')
						{
							echo $ArtistURL = $html->link('http://' . $album['Album']['ArtistURL'], 'http://' . $album['Album']['ArtistURL'], array('target' => 'blank', 'style' => 'word-wrap:break-word;word-break:break-word;width:160px;'));
							echo '<br />';
						}
						if ($album['Album']['Advisory'] == 'T')
						{
							echo '<span class="explicit"> (Explicit)</span>';
							echo '<br />';
						}
						?>
					</div>
					<div class="label">
						<?php
						if ($album['Album']['Label'] != '')
						{
							echo __("Label") . ': ' . $this->getTextEncode($album['Album']['Label']);
							echo '<br />';
						}
						if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown')
						{
							echo $this->getTextEncode($album['Album']['Copyright']);
						}
						?>
					</div>
				</li>
				<?php
				endforeach;
				?>
			</ul>
		</div>

		<div class="paging">
			<?php
			echo $paginator->prev('<< ' . __('Previous ', true), null, null, array('class' => 'disabled'));
			echo $paginator->numbers(array('separator' => ' '));
			echo $paginator->next(__(' Next >>', true), null, null, array('class' => 'disabled'));
			?>
		</div>
	</div>
	<?php
		}
		?>



	<!-- Videos Section  -->
	<?php
	if (!empty($artistVideoList))
	{
		?>
	<h3><?php __('Videos'); ?></h3>
	<div class="videos-shadow-container">
		<div class="videos-scrollable horiz-scroll">
			<ul style="width: 15000px;">
				<?php
				foreach ($artistVideoList as $key => $value)
				{
					?>
				<li>

					<div class="video-container">
						<a href="/videos/details/<?php echo $value["Video"]["ProdID"]; ?>">
							<img src="<?php echo trim($value['videoAlbumImage']); ?>"
							alt="jlo" width="272" height="162" />
						</a>
						<?php
						if ($this->Session->read('patron'))
						{
							if ($value['Country']['SalesDate'] <= date('Y-m-d'))
							{

								if ($libraryDownload == '1' && $patronDownload == '1')
								{
									$productInfo = $mvideo->getDownloadData($value["Video"]["ProdID"], $value["Video"]["provider_type"]);

									$downloadsUsed = $this->Videodownload->getVideodownloadfind($value['Video']['ProdID'], $value["Video"]["provider_type"], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));

									if ($downloadsUsed > 0)
									{
										$value['Video']['status'] = 'avail';
									}
									else
									{
										$value['Video']['status'] = 'not';
									}
									if ($value['Video']['status'] != 'avail')
									{
										?>
						<span class="top-100-download-now-button">
							<form method="Post" id="form<?php echo $value['Video']['ProdID']; ?>" action="/videos/download" class="suggest_text1">
								<input type="hidden" name="ProdID"
									value="<?php echo $value['Video']['ProdID']; ?>" /> <input
									type="hidden" name="ProviderType"
									value="<?php echo $value['Video']['provider_type']; ?>" /> <span
									class="beforeClick"
									id="download_video_<?php echo $value['Video']['ProdID']; ?>"> <![if !IE]>
									<a class="no-ajaxy" href="javascript:void(0);"
									title="<?php __('IMPORTANT:  Please note that once you press Download Now you have used up one of your downloads, regardless of whether you then press Cancel or not.'); ?>"
									onclick='return wishlistVideoDownloadOthersToken("<?php echo $value['Video']['ProdID']; ?>", "0", "<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>", "<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>", "<?php echo $value['Video']['provider_type']; ?>");'><label
										class="top-10-download-now-button"><?php __('Download Now'); ?>
									</label>
								</a> <![endif]> <!--[if IE]>
                                                                        <label class="top-10-download-now-button"><a class="no-ajaxy" title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick="wishlistVideoDownloadIEToken('<?php echo $value['Video']['ProdID']; ?>','0','<?php echo $value['Video']['provider_type']; ?>', '<?php echo $productInfo[0]['Full_Files']['CdnPath']; ?>', '<?php echo $productInfo[0]['Full_Files']['SaveAsName']; ?>');" href="javascript:void(0);"><?php __('Download Now'); ?></a></label>
                                                                <![endif]-->
								</span> <span class="afterClick"
									id="vdownloading_<?php echo $value['Video']['ProdID']; ?>"
									style="display: none;"><label
									class="top-10-download-now-button"><?php __('Please Wait...&nbsp&nbsp'); ?>
								</label>
								</span> <span
									id="vdownload_loader_<?php echo $value['Video']['ProdID']; ?>"
									style="display: none; float: right;"><?php echo $html->image('ajax-loader_black.gif', array('style' => 'margin-top:-20px;width:16px;height:16px;')); ?>
								</span>
							</form>
						</span>
						<?php
									}
									else
									{
										?>
						<a class="top-100-download-now-button" href='/homes/my_history'><label
							class="top-10-download-now-button"
							style="width: 120px; cursor: pointer;"
							title='<?php __("You have already downloaded this song. Get it from your recent downloads"); ?>'><?php __('Downloaded'); ?>
						</label>
						</a>
						<?php
									}
								}
								else
								{

									if ($libraryDownload != '1')
									{
										$libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
										$wishlistCount = $wishlist->getWishlistCount();
										if ($libraryInfo['Library']['library_user_download_limit'] <= $wishlistCount)
										{
											?>
						<a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?>
						</a>
						<?php
										}
										else
										{
											$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
											echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value['Video']["provider_type"]);
										}
									}
									else
									{
										?>
						<a class="top-10-download-now-button" href="javascript:void(0);"><?php __("Limit Met"); ?>
						</a>
						<?php
									}
								}
							}
							else
							{
								?>
						<a class="top-100-download-now-button" href="javascript:void(0);"><span
							title='<?php __("Coming Soon"); ?> ( <?php
                                                if (isset($value['Country']['SalesDate']))
                                                {
                                                    echo date("F d Y", strtotime($value['Country']['SalesDate']));
                                                }
                                                ?> )'><?php __("Coming Soon"); ?>
						</span>
						</a>
						<?php
							}
						}
						else
						{
							?>
						<a class="top-10-download-now-button"
							href='/users/redirection_manager'> <?php __("Login"); ?>
						</a>


						<?php
						}
						if ($this->Session->read("patron"))
						{
							?>

						<a class="add-to-playlist-button no-ajaxy"
							href="javascript:void(0)"></a>

						<div class="wishlist-popover">
							<?php
							$wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
							echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value['Video']["provider_type"]);
							?>
						</div>
						<?php } ?>



					</div> <?php
					$title_song_replace = str_replace('"', '', $this->getTextEncode($value['Video']['VideoTitle']));
					?>
					<div class="song-title">
						<a title="<?php echo $title_song_replace; ?>"
							href="javascript:void(0);"> <?php
							if (strlen($value['Video']['VideoTitle']) > 25)
								echo substr($value['Video']['VideoTitle'], 0, 25) . "...";
							else
								echo $value['Video']['VideoTitle'];
							?>
						</a>
						<?php
						if ('T' == $value['Video']['Advisory'])
						{
							?>
						<span style="color: red; display: inline;"> (Explicit)</span>
						<?php } ?>
					</div>
					<div class="genre">
						<?php echo __('Genre') . ": " . $html->link($this->getTextEncode($value['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($value['Genre']['Genre'])), array('title' => $value['Genre']['Genre'])) . '<br />'; ?>
					</div> <?php
					if (!empty($value['Video']['video_label']))
					{
						?>
					<div class="label">
						Label:
						<?php
						if (strlen($value['Video']['video_label']) > 25)
							echo substr($value['Video']['video_label'], 0, 25) . "...";
						else
							echo $value['Video']['video_label'];
						?>

					</div> <?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<?php
	}
	}
	else
	{
		echo '<span> Sorry, there are no details available for this artist.</span>';
	}
	?>
	
</section>
