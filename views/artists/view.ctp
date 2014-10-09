<section class="albums-page">
	<section class="album-detail-container clearfix">
		<div class="breadcrumbs">
			<span><?php
			$genre_crumb_name = $this->Genre->genreBreadcrumb($genre);
			$html->addCrumb(__('All Genre', true), '/genres/view/');

			if ($genre_crumb_name != "")
			{
				$html->addCrumb($this->getTextEncode($genre_crumb_name), '/genres/view/' .base64_encode($genre_crumb_name));
			}

			$html->addCrumb(__($this->getTextEncode($artistName), true), '/artists/album/' . str_replace('/', '@', base64_encode($artistName)) . '/' . base64_encode($genre));
			$html->addCrumb($this->getTextEncode($albumData[0]['Album']['AlbumTitle']), '/artists/view/' . str_replace('/', '@', base64_encode($artistName)) . '/' . $album . '/' . base64_encode($albumData[0]['Album']['provider_type']));
			echo $html->getCrumbs(' > ', __('Home', true), '/homes');
			?>
			</span>
		</div><?php 

		if (count($albumData) > 0):
			
			foreach ($albumData as $album_key => $album): ?>
		
		<section class="album-detail">
			
			<div class="album-cover-image">
				
				<?php  $albumArtwork = $this->Token->artworkToken($album['Files']['CdnPath'] . "/" . $album['Files']['SourceURL']);
				
				echo $this->Html->image(Configure::read('App.Music_Path') . $albumArtwork, array('alt' => 'album-detail-cover', 'width' => '250', 'height' => '250'));

				if ($this->Session->read('library_type') == 2 && !empty($album['albumSongs'][$album['Album']['ProdID']]) && $this->Session->read("patron")):
					echo $this->Queue->getAlbumStreamLabel($album['albumSongs'][$album['Album']['ProdID']],0,$album['Album']['ProdID']);
					echo $this->Form->hidden('empty', array('value' => 'album', 'id' => $album['Album']['ProdID'], 'name' => false, 'data-provider' => $album["Album"]["provider_type"]));
					echo $this->Html->link('', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy'));
					?>
				
				<?php /*<div class="wishlist-popover"> */?>
					<?php
					// echo $this->Form->hidden('empty', array('value' => 'album', 'id' => $album['Album']['ProdID'], 'name' => false));
					/*echo $this->Html->link('Add To Playlist', 'javascript:void(0)', array('class' => 'add-to-playlist'));*/
					?>
				<?php /*</div>*/ ?>
				<ul>
					<li><a href="#" class="create-new-playlist"><?php __('Create New Playlist'); ?>...</a></li>

				</ul> 
				<a class="wishlist-icon toggleable no-ajaxy" href="#" title="Add to Wishlist"></a>
				<?php endif; ?>

			</div>
			
			<div class="release-info"><?php __('Release Information'); ?></div>

			<div class="album-genre">
				<?php echo __('Genre') . ": "; ?>
				<span> <?php
				echo $html->link($this->getTextEncode($album['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($album['Genre']['Genre'])), array("title" => $this->getTextEncode($album['Genre']['Genre'])));

				if ($album['Album']['Advisory'] == 'T'):

					echo '<br />';
					echo '<font class="explicit"> (' . __('Explicit', true) . ')</font>';
				endif;
				?>
				</span>
			</div>

			<div class="album-label">
				<?php echo __('Label') . ": "; ?>
				<span> <?php
				if ($album['Album']['Label'] != ''):
					echo $this->getTextEncode($album['Album']['Label']);
				endif;
				?>
				</span>
			</div>

			<div class="release-detail">
				<?php
				if ($album['Album']['Copyright'] != '' && $album['Album']['Copyright'] != 'Unknown'):
					echo $this->getTextEncode($album['Album']['Copyright']);
				endif;
				?>
			</div>

		</section>
		<section class="tracklist-container">
			<div class="button-container">
				<div class="play-album-btn">
					<span></span>
				</div>

			</div>
			<div class="album-title">

				<?php echo $this->getTextEncode($album['Album']['AlbumTitle']); ?>
			</div>
			<div class="artist-name">
				<?php
				$artistNames = $artistName;

                if($this->getTextEncode($artistName)):
                   $artistName= $this->getTextEncode($artistName);
                endif;
                                
                                
				if (strlen($artistName) >= 90):
				    $artistName = substr($artistName, 0, 90) . ' ...';
				endif;

				echo $this->Html->link($this->getTextEncode($artistName), array('controller' => 'artists', 'action' => 'album', base64_encode($albumSongs[$album['Album']['ProdID']][0]['Song']['Artist'])), array('title' => $this->getTextEncode($artistNames)));
				?>

			</div>
			<div class="tracklist-header">
				<span class="song"><?php __('Song'); ?></span>
				<span class="artist"><?php __('Artist'); ?></span>
				<span class="time"><?php __('Time'); ?></span>
			</div>

			<?php

			foreach ($albumSongs[$album['Album']['ProdID']] as $key => $albumSong):

			//hide song if library block the explicit content
			if (($this->Session->read('block') == 'yes') && ($albumSong['Song']['Advisory'] == 'T')):
				continue;
			endif;
			
			if ($this->Session->read('library_type') == 2):				
                
                $filePath = $this->Token->streamingToken($albumSong['Full_Files']['CdnPath'] . "/" . $albumSong['Full_Files']['SaveAsName']);

				if (!empty($filePath)):
					$songPath = explode(':', $filePath);
					$streamUrl = trim($songPath[1]);
					$albumSong['streamUrl'] = $streamUrl;
					$albumSong['totalseconds'] = $this->Queue->getSeconds($albumSong['Song']['FullLength_Duration']);
				endif;
			
			endif; ?>

			<div class="tracklist">

				<?php
				//check the song streaming status
				$streamingFlag = 0;

				if ($this->Session->read('library_type') == 2 && $albumSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $albumSong['Country']['StreamingStatus'] == 1):
					$streamingFlag = 1;
				endif;

				$class = '';
				$cs = '';
				if ($this->Session->read("patron")):

					if ($this->Session->read('library_type') == 2 && $albumSong['Country']['StreamingSalesDate'] <= date('Y-m-d') && $albumSong['Country']['StreamingStatus'] == 1):

						if ('T' == $albumSong['Song']['Advisory']):
							$song_title = $albumSong['Song']['SongTitle'] . '(Explicit)';
						else:
							$song_title = $albumSong['Song']['SongTitle'];
						endif;

						echo $html->image('play.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'loadSong("' . $albumSong['streamUrl'] . '", "' . base64_encode($song_title) . '","' . base64_encode($albumSong['Song']['ArtistText']) . '",' . $albumSong['totalseconds'] . ',"' . $albumSong['Song']['ProdID'] . '","' . $albumSong['Song']['provider_type'] . '");'));

						if (!empty($albumSong['streamUrl']) || !empty($song_title)):
							$playItem = array('playlistId' => 0, 'songId' => $albumSong['Song']['ProdID'], 'providerType' => $albumSong['Song']['provider_type'], 'label' => $song_title, 'songTitle' => $song_title, 'artistName' => $albumSong['Song']['ArtistText'], 'songLength' => $albumSong['totalseconds'], 'data' => $albumSong['streamUrl']);
							$jsonPlayItem = json_encode($playItem);
							$jsonPlayItem = str_replace("\/", "/", $jsonPlayItem);
							$playListData[] = $jsonPlayItem;
						endif;

					elseif ($albumSong['Country']['SalesDate'] <= date('Y-m-d')):
						
						echo $html->image('play.png', array("alt" => "Play Sample", "title" => "Play Sample", "class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $album_key . $key, "onClick" => 'playSample(this, "' . $album_key . $key . '", ' . $albumSong["Song"]["ProdID"] . ', "' . base64_encode($albumSong["Song"]["provider_type"]) . '", "' . $this->webroot . '");'));
						echo $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "title" => "Loading Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $album_key . $key));
						echo $html->image('stop.png', array("alt" => "Stop Sample", "title" => "Stop Sample", "class" => "preview", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $album_key . $key, "onClick" => 'stopThis(this, "' . $album_key . $key . '");'));
					
					endif;
					
					$class = ' logged_in';

					$cs = '';
					
					if (($albumSong['Country']['SalesDate'] > date('Y-m-d') ) && ($albumSong['Country']['DownloadStatus'] == 1)):
						$cs = ' cs';
					endif;
				
				endif;
				?>

				<div class="song<?php echo $class; echo $cs; ?>">
					<?php

					// if (strlen($albumSong['Song']['SongTitle']) >= 44):
					
					// 	echo $this->Html->tag('a', substr($albumSong['Song']['SongTitle'], 0, 44) . '...', array('style' => 'text-decoration:none;', 'title' => $this->getTextEncode($albumSong['Song']['SongTitle'])));
					
					// else:
					
					// 	if($this->getTextEncode($albumSong['Song']['SongTitle'])):
					// 		echo $this->Html->tag('a', $this->getTextEncode($albumSong['Song']['SongTitle']), array('style' => 'text-decoration:none;', 'title' => $this->getTextEncode($albumSong['Song']['SongTitle'])));
					// 	else:
					// 		echo $this->Html->tag('a', $albumSong['Song']['SongTitle'], array('style' => 'text-decoration:none;', 'title' => $albumSong['Song']['SongTitle']));
					// 	endif;
							
					// endif;



					
					if($this->getTextEncode($albumSong['Song']['SongTitle'])):
						echo $this->Html->tag('a', $this->getTextEncode($albumSong['Song']['SongTitle']), array('style' => 'text-decoration:none;', 'title' => $this->getTextEncode($albumSong['Song']['SongTitle'])));
					else:
						echo $this->Html->tag('a', $albumSong['Song']['SongTitle'], array('style' => 'text-decoration:none;', 'title' => $albumSong['Song']['SongTitle']));
					endif;
					

					if ($albumSong['Song']['Advisory'] == 'T'):
						echo '<span class="explicit"> (' . __('Explicit', true) . ')</span>';
					endif;?>
				</div>
				<?php
				//check the artist value exist or not
				$artistTextLenght = strlen($albumSong['Song']['Artist']);
				$artistTextValue =$albumSong['Song']['Artist'];

				if($this->getTextEncode($artistTextValue)):
					$artistTextValue = $this->getTextEncode($artistTextValue);
				endif;

				/*
				if ($artistTextLenght >= 30):
					$artistTextValue = substr($albumSong['Song']['Artist'], 0, 30) . '...';
				endif; 
				*/
				?>
				
				<div class="artist">
					<?php echo $this->Html->link($artistTextValue, array('controller' => 'artists', 'action' => 'album', base64_encode($albumSong['Song']['Artist'])), array('title' => $artistTextValue));?>
				</div>

				<div class="time">
					<?php echo $this->Song->getSongDurationTime($albumSong['Song']['FullLength_Duration']); ?>
				</div>
				<?php
				if ($this->Session->read('patron')):
				
				echo $this->Html->link('', 'javascript:void(0)', array('class' => 'add-to-playlist-button no-ajaxy'));

				?>

				<div class="wishlist-popover">
					
				<?php
				echo $this->Form->hidden('empty', array('value' => 'song', 'id' => $albumSong["Song"]["ProdID"], 'name' => false));
					
					if (($albumSong['Country']['SalesDate'] <= date('Y-m-d') ) && ($albumSong['Country']['DownloadStatus'] == 1)):

						if ($libraryDownload == '1' && $patronDownload == '1'):
							
							if ($albumSong['Song']['status'] != 'avail'):
					
					echo $this->Form->create(null, array(
						'type' => 'post',
						'url' => array(
							'controller' => 'homes',
							'action' => 'userDownload'
						),
						'id' => 'form' . $albumSong["Song"]["ProdID"],
						'encoding' => null
					));
					
					echo $this->Form->hidden('empty', array('value' => $albumSong["Song"]["ProdID"], 'name' => 'ProdID', 'id' => false));
					echo $this->Form->hidden('empty', array('value' => $albumSong["Song"]["provider_type"], 'name' => 'ProviderType', 'id' => false));
					
					?>
						<span class="beforeClick" style="cursor: pointer;" id="wishlist_song_<?php echo $albumSong["Song"]["ProdID"]; ?>">
							<![if !IE]>
							<?php
							echo $this->Html->link('Download Now', 'javascript:void(0)', array(
								'class' => 'add-to-wishlist',
								'title' => __('"IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not."', true),
								'onclick' => 'return wishlistDownloadOthersHome("' . $albumSong["Song"]['ProdID'] . '", "0", "' . $albumSong['Full_Files']['CdnPath'] . '", "' . $albumSong['Full_Files']['SaveAsName'] . '", "' . $albumSong["Song"]["provider_type"] . '");'
							));
							?>
							<![endif]>
                                                        <!--[if IE]>
                                                                <a title="IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press 'Cancel' or not." onclick='wishlistDownloadIEHome("<?php echo $albumSong["Song"]['ProdID']; ?>", "0" , "<?php echo $albumSong["Song"]["provider_type"]; ?>", "<?php echo $albumSong['Full_Files']['CdnPath']; ?>", "<?php echo $albumSong['Full_Files']['SaveAsName']; ?>");' href="javascript:void(0);"><?php __('Download Now'); ?></a>
                                                        <![endif]-->                                                        
							
						</span>
						
						<span class="afterClick" id="downloading_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="display: none;">
							<a class="add-to-wishlist"><?php __('Please Wait'); ?>...
								<span id="wishlist_loader_<?php echo $albumSong["Song"]["ProdID"]; ?>" style="float: right; padding-right: 8px; padding-top: 2px;">
									<?php echo $html->image('ajax-loader_black.gif'); ?>
								</span>
							</a>
						</span>

					<?php echo $this->Form->end(); ?>
					<?php
							else:
					echo $this->Html->link(__('Downloaded', true), array('controller' => 'homes', 'action' => 'my_history'), array('class' => 'add-to-wishlist', 'title' => __('You have already downloaded this song. Get it from your recent downloads', true)));
							endif;
						else:
					echo $this->Html->link(__('Limit Met', true), 'javascript:void(0)', array('class' => 'add-to-wishlist'));
						endif;
					
					elseif (($albumSong['Country']['SalesDate'] <= date('Y-m-d') ) && ($albumSong['Country']['DownloadStatus'] == 0)):

					echo $this->Html->tag('a', 'Not Allowed', array('id' => 'not-allowed'));

					else:
						
						$comingSoonDate = __('Coming Soon', true);
						if (isset($albumSong['Country']['SalesDate'])):
	                        $comingSoonDate = __('Coming Soon', true) . ' ( ' . date("F d Y", strtotime($albumSong['Country']['SalesDate'])) . ' )';
	                    endif;

					echo $this->Html->link(__('Coming Soon', true), 'javascript:void(0)', array('class' => 'add-to-wishlist', 'title' => $comingSoonDate));

					endif;
					
					if ($streamingFlag == 1): 
						echo $this->Html->link(__('Add To Playlist', true), 'javascript:void(0)', array('class' => 'add-to-playlist'));
					endif;
			
					$wishlistInfo = $wishlist->getWishlistData($albumSong["Song"]["ProdID"]);

					echo $wishlist->getWishListMarkup($wishlistInfo, $albumSong["Song"]["ProdID"], $albumSong["Song"]["provider_type"]);
					?>
				</div><?php
				
				else:
					
				echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'redirection_manager'), array('class' => 'genre-download-now-button'));

				endif; ?>

			</div><?php

			endforeach;

			if (!empty($playListData)): ?>
			
			<div id="playlist_data" style="display: none;">	
				<?php $playList = implode(',', $playListData);
				if (!empty($playList)):
					echo '[' . $playList . ']';
				endif; ?>
			</div><?php

			endif; ?>

		</section><?php

		endforeach;
		
		else:
			echo '<span>' . __('Sorry, there are no more details available.', true) . '</span>';
		endif;?>
	</section>
</section> <!-- close class="albums-page" -->
