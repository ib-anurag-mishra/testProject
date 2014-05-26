<?php
echo $session->flash();
ini_set("session.cookie_lifetime", "0"); // 0 means "until the browser is closed 
?>
<section class="news">
<?php 
$cacheReadNationalTop100 = $this->Home->nationalTop100( $nationalTopDownload, $nationalTopAlbumsDownload );

$nationalTopDownloadCount = count( $nationalTopDownload );

$libId = $this->Session->read('library');
$patId = $this->Session->read('patron');
for ($i = 0; $i < $nationalTopDownloadCount; $i++) {
	$replaceString = '';
	if ($this->Session->read("patron")) {

		if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1) {

			if ('T' == $nationalTopDownload[$i]['Song']['Advisory']) {

				$song_title = $nationalTopDownload[$i]['Song']['SongTitle'] . '(Explicit)';
			} else {
				$song_title = $nationalTopDownload[$i]['Song']['SongTitle'];
			}

			$replaceString	.=  $this->Queue->getNationalsongsStreamNowLabel($nationalTopDownload[$i]['Full_Files']['CdnPath'],$nationalTopDownload[$i]['Full_Files']['SaveAsName'], $song_title, $nationalTopDownload[$i]['Song']['ArtistText'], $nationalTopDownload[$i]['Song']['FullLength_Duration'], $nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);

		} else if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {

			$replaceString	.= $html->image('/img/news/top-100/preview-off.png', array("class" => "preview", "style" => "cursor:pointer;display:block;", "id" => "play_audio" . $i, "onClick" => 'playSample(this, "' . $i . '", ' . $nationalTopDownload[$i]['Song']['ProdID'] . ', "' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) . '", "' . $this->webroot . '");'));
			$replaceString	.= $html->image('ajax-loader.gif', array("alt" => "Loading Sample", "class" => "preview", "title" => "Loading Sample", "style" => "cursor:pointer;display:none;", "id" => "load_audio" . $i));
			$replaceString	.= $html->image('stop.png', array("alt" => "Stop Sample", "class" => "preview", "title" => "Stop Sample", "style" => "cursor:pointer;display:none;", "id" => "stop_audio" . $i, "onClick" => 'stopThis(this, "' . $i . '");'));
		}
		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_streaming';
		$cacheReadNationalTop100 = str_replace( $searchString, $replaceString, $cacheReadNationalTop100 );
		 
		$replaceString = '';
		if ($nationalTopDownload[$i]['Country']['SalesDate'] <= date('Y-m-d')) {
			 
			if ($libraryDownload == '1' && $patronDownload == '1') {
				 
				if ($this->Session->read('downloadVariArray')) {
					$downloadsUsed = $this->Download->getDownloadResults($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type']);
				} else {
					$downloadsUsed = $this->Download->getDownloadfind($nationalTopDownload[$i]['Song']['ProdID'], $nationalTopDownload[$i]['Song']['provider_type'], $libId, $patId, Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'));
				}
				 
				if ($downloadsUsed > 0) {
					$nationalTopDownload[$i]['Song']['status'] = 'avail';
				} else {
					$nationalTopDownload[$i]['Song']['status'] = 'not';
				}
				 
				if ($nationalTopDownload[$i]['Song']['status'] != 'avail') {
					 
					$replaceString	.= "<span class='top-100-download-now-button'>
					<form method='Post' id='form" . $nationalTopDownload[$i]['Song']['ProdID'] . "' action='/homes/userDownload' class='suggest_text1'>
					<input type='hidden' name='ProdID' value='" . $nationalTopDownload[$i]['Song']['ProdID'] . "' />
					<input type='hidden' name='ProviderType' value='" . $nationalTopDownload[$i]['Song']['provider_type'] . "' />
					<span class='beforeClick' style='cursor:pointer;' id='wishlist_song_" . $nationalTopDownload[$i]['Song']['ProdID'] . "'>
					<![if !IE]>
					<a href='javascript:void(0);' class='add-to-wishlist no-ajaxy top-10-download-now-button'
					title='" . __('IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.', true) . "'
					onclick='return wishlistDownloadOthersHome(\"" . $nationalTopDownload[$i]['Song']['ProdID'] ."\", \"0\", \"" . $nationalTopDownload[$i]['Full_Files']['CdnPath'] . "\", \"" . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] ."\", \"" . $nationalTopDownload[$i]['Song']['provider_type'] ."\");'>
					" . __('Download Now', true) . "</a>
					<![endif]>
					<!--[if IE]>
					<a id='song_download_" . $nationalTopDownload[$i]['Song']['ProdID'] ."'
					class='no-ajaxy top-10-download-now-button'
					title='IMPORTANT: Please note that once you press `Download Now` you have used up one of your downloads, regardless of whether you then press `Cancel` or not.'
					onclick='wishlistDownloadIEHome(\'" . $nationalTopDownload[$i]['Song']['ProdID'] ."\', \'0\' , \'" . $nationalTopDownload[$i]['Song']['provider_type'] ."\', \'" . $nationalTopDownload[$i]['Full_Files']['CdnPath'] ."\', \'" . $nationalTopDownload[$i]['Full_Files']['SaveAsName'] ."\');'
					href='javascript:void(0);'>" . __('Download Now', true) . "</a>
					<![endif]-->
					</span>
					<span class='afterClick' id='downloading_" . $nationalTopDownload[$i]['Song']['ProdID'] ."' style='display:none;'><a  class='add-to-wishlist'  >" . __('Please Wait..', true) ."
					<span id='wishlist_loader_" . $nationalTopDownload[$i]['Song']['ProdID'] ."' style='float:right;padding-right:8px;padding-top:2px;'>" . $html->image('ajax-loader_black.gif') ."</span> </a> </span>
					</form>
					</span>";
				} else {
					$replaceString	.= '<a class="top-100-download-now-button" href="/homes/my_history" title="' . __("You have already downloaded this song. Get it from your recent downloads", true) .'">' . __('Downloaded', true) .'</a>';
				}
			} else {
				$replaceString	.= '<a class="top-100-download-now-button" href="javascript:void(0);">' . __("Limit Met", true) .'</a>';
			}
		} else {
			$replaceString	.= '<a class="top-100-download-now-button" href="javascript:void(0);">
			<span title="' . __("Coming Soon", true) .' (';
			if (isset($nationalTopDownload[$i]['Country']['SalesDate']))
			{
				$replaceString	.= date("F d Y", strtotime($nationalTopDownload[$i]['Country']['SalesDate']));
			}
			$replaceString	.= ' )">
			' . __("Coming Soon", true) .'
			</span>
			</a>';
		}
		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_download';
		$cacheReadNationalTop100 = str_replace( $searchString, $replaceString, $cacheReadNationalTop100 );
		 
		$replaceString = '';
		 
		$replaceString	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
		<div class="wishlist-popover">
		<input type="hidden" id="' . $nationalTopDownload[$i]["Song"]["ProdID"] .'" value="song"/>';
		 
		if ($this->Session->read('library_type') == 2 && $nationalTopDownload[$i]['Country']['StreamingSalesDate'] <= date('Y-m-d') && $nationalTopDownload[$i]['Country']['StreamingStatus'] == 1) {
			$replaceString	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
		}
		 
		$wishlistInfo = $wishlist->getWishlistData($nationalTopDownload[$i]["Song"]["ProdID"]);
		 
		$replaceString	.= $wishlist->getWishListMarkup($wishlistInfo, $nationalTopDownload[$i]["Song"]["ProdID"], $nationalTopDownload[$i]["Song"]["provider_type"]);
		 
		$replaceString	.= ' </div>';
		 
		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_wishlist';
		$cacheReadNationalTop100 = str_replace( $searchString, $replaceString, $cacheReadNationalTop100 );

	} else {

		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_streaming';
		$cacheReadNationalTop100 = str_replace( $searchString, '', $cacheReadNationalTop100 );

		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_wishlist';
		$cacheReadNationalTop100 = str_replace( $searchString, '', $cacheReadNationalTop100 );

		$searchString = $nationalTopDownload[$i]['Song']['ProdID'] . '_' . $nationalTopDownload[$i]['Song']['provider_type'] . '_download';
		$cacheReadNationalTop100 = str_replace( $searchString, '<a class="top-100-download-now-button" href="/users/redirection_manager"> ' . __("Login", true) .'</a>', $cacheReadNationalTop100 );
	}
}

if (count($nationalTopAlbumsDownload) > 0) {
	foreach ($nationalTopAlbumsDownload as $key => $value) {
		$replaceString = '';
		if ($this->Session->read("patron")) {
			if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'])) {
				$replaceString	.= $this->Queue->getNationalAlbumStreamLabel($value['Song']['ArtistText'],$value['Albums']['ProdID'],$value['Song']['provider_type']);
				$replaceString	.= '<a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>';
			}
			$replaceString	.= '<div class="wishlist-popover">
			<input type="hidden" id="' . $value['Albums']['ProdID'] .'" value="album"/>';

			if ($this->Session->read('library_type') == 2 && !empty($value['albumSongs'])) {
				$replaceString	.= '<a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>';
			}
			$replaceString	.= '</div>';
			 
			$searchString = $value['Song']['ProdID'] . '_' . $value['Song']['provider_type'] . '_albums';
			$cacheReadNationalTop100 = str_replace( $searchString, $replaceString, $cacheReadNationalTop100 );
		} else {
			$replaceString	.= '<a class="top-100-download-now-button " href="/users/redirection_manager"> ' . __("Login", true) . '</a>';
			$searchString = $value['Song']['ProdID'] . '_' . $value['Song']['provider_type'] . '_albums';
			$cacheReadNationalTop100 = str_replace( $searchString, $replaceString, $cacheReadNationalTop100 );
		}
	}
}

echo $cacheReadNationalTop100;
?>
    <div class="featured">
        <header>
            <h3>Featured Albums</h3>
        </header>
        <div class="featured-grid horiz-scroll">
            <ul style="width:3690px;">
                <?php
                // $this->log("index.ctp featuredArtists start", "siteSpeed");   
                foreach ($featuredArtists as $k => $v)
                {

                    //$albumArtwork = shell_exec('perl files/tokengen ' . $v['Files']['CdnPath']."/".$v['Files']['SourceURL']);
                    //$image =  Configure::read('App.Music_Path').$albumArtwork;
                    if (strlen($v['Album']['AlbumTitle']) > 22)
                    {
                        $title = substr($v['Album']['AlbumTitle'], 0, 22) . "..";
                    }
                    else
                    {
                        $title = $v['Album']['AlbumTitle'];
                    }

                    if (strlen($v['Album']['ArtistText']) > 22)
                    {
                        $ArtistText = substr($v['Album']['ArtistText'], 0, 22) . "..";
                    }
                    else
                    {
                        $ArtistText = $v['Album']['ArtistText'];
                    }
                    ?>								

                    <li>
                        <div class="featured-album-detail">
                            <div class="album-cover-container">												

                                <a href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $html->image($v['featuredImage'], array("height" => "77", "width" => "84", "alt" => $ArtistText . ' - ' . $v['Album']['AlbumTitle'])); ?></a>

                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                                    {
                                        echo $this->Queue->getAlbumStreamNowLabel($v['albumSongs'][$v['Album']['ProdID']]);
                                    }
                                    ?> 
                                    <?php
                                    if ($this->Session->read('library_type') == 2 && !empty($v['albumSongs'][$v['Album']['ProdID']]))
                                    {
                                        ?>
                                        <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)" ></a>
                                        <div class="wishlist-popover">    
                                            <input type="hidden" id="<?= $v['Album']['ProdID'] ?>" value="album"/>
                                            
                                            <?php 
                                            //echo $this->Queue->getQueuesListAlbums($this->Session->read('patron'), $v['albumSongs'][$v['Album']['ProdID']], $v['Album']['ProdID'], $v['Album']['provider_type']);
                                            ?>
                                            <a class="add-to-playlist" href="javascript:void(0)">Add To Playlist</a>
                                            <?php ?>

                                            <?php
                                            //$wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
                                            //echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
                                            ?>

                                            <?php //echo $this->Queue->getSocialNetworkinglinksMarkup(); ?> 
                                        </div>                                            
                                    <?php
                                    }
                                    ?>

                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a class="top-100-download-now-button " href='/users/redirection_manager'> <?php __("Login"); ?></a> 
                                <?php 
                                }
                                ?>
                            </div>
                            <div class="album-title">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['AlbumTitle'])); ?>" href="/artists/view/<?= base64_encode($v['Album']['ArtistText']); ?>/<?= $v['Album']['ProdID']; ?>/<?= base64_encode($v['Album']['provider_type']); ?>"><?php echo $this->getTextEncode($title); ?></a>
                            </div>


                            <div class="artist-name">
                                <a title="<?php echo $this->getValidText($this->getTextEncode($v['Album']['ArtistText'])); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($v['Album']['ArtistText'])); ?>/<?= base64_encode($v['Genre']['Genre']) ?>"><?php echo $this->getTextEncode($ArtistText); ?></a>
                            </div>
                        </div>
                    </li>

                    <?php
                }
               // $this->log("index.ctp featuredArtists end", "siteSpeed");   
                ?>	


            </ul>
        </div>
    </div><!-- end .featured -->
    <div class="coming-soon">
        <header class="clearfix">
            <h3>Coming Soon</h3>


        </header>
        <div class="coming-soon-filter-container clearfix">
            <nav class="category-filter">
                <ul class="clearfix">
                    <!-- <li><a href="#coming-soon-album-grid">Albums</a></li> -->
                    <li><a href="#coming-soon-singles-grid" id="songsIDValComming" class="active no-ajaxy hp-tabs" onclick="showHideGridCommingSoon('songs')">Songs</a></li>
                    <li><a href="#coming-soon-videos-grid" id="videosIDValComming" class="no-ajaxy hp-tabs" onclick="showHideGridCommingSoon('videos')">Videos</a></li>
                </ul>

            </nav>
            <!-- <a href="#" class="view-all">View All</a> -->

        </div>
        <?php ?>


        <div id="coming-soon-singles-grid" class="horiz-scroll active">
            <ul class="clearfix">
                <?php
               // $this->log("index.ctp commingsoon song start", "siteSpeed");   
                $total_songs = count($coming_soon_rs);
                $sr_no = 0;

                foreach ($coming_soon_rs as $key => $value)
                {

                    //hide song if library block the explicit content
                    if (($this->Session->read('block') == 'yes') && ($value['Song']['Advisory'] == 'T'))
                    {
                        continue;
                    }


                    //$cs_img_url = shell_exec('perl files/tokengen ' . $value['File']['CdnPath']."/".$value['File']['SourceURL']);
                    //$cs_songImage =  Configure::read('App.Music_Path').$cs_img_url;

                    if ($sr_no <= 9)
                    {
                        $lazyClass = '';
                        $srcImg = $value['cs_songImage'];
                        $dataoriginal = '';
                    }
                    else                //  Apply Lazy Class for images other than first 10.
                    {
                        $lazyClass = 'lazy';
                        $srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
                        $dataoriginal = $value['cs_songImage'];
                    }


                    if ($sr_no >= 20)
                        break;
                    ?>
                    <?php
                    if ($sr_no % 2 == 0)
                    {
                        ?><li> <?php } ?>
                        <div class="single-detail">
                            <div class="single-cover-container">

                                <a href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <img class="<?php echo $lazyClass; ?>" src="<?php echo $srcImg; ?>" data-original="<?php echo $dataoriginal; ?>" alt="<?php echo $this->getValidText($this->getTextEncode($value['Song']['Artist']) . ' - ' . $this->getTextEncode($value['Song']['SongTitle'])); ?>" width="162" height="162" /></a>

                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 													
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">

                                    </a>
                                    <div class="wishlist-popover"> 
                                        <?php
                                        $wishlistInfo = $wishlist->getWishlistData($value["Song"]["ProdID"]);
                                        echo $wishlist->getWishListMarkup($wishlistInfo, $value["Song"]["ProdID"], $value["Song"]["provider_type"]);
                                        //echo $this->Queue->getSocialNetworkinglinksMarkup();
                                        ?>
                                    </div>

                                <?php } ?>
                            </div>
                            <div class="song-title">
                                <a title="<?php echo $this->getTextEncode($value['Song']['SongTitle']); ?>" href="/artists/view/<?= base64_encode($value['Song']['ArtistText']); ?>/<?= $value['Song']['ReferenceID']; ?>/<?= base64_encode($value['Song']['provider_type']); ?>">
                                    <?php
                                    //echo "<br>Sales Date: ".Country.$value['Country']['SalesDate']."</br>";

                                    $commingSoonSongTitle = $this->getTextEncode($value['Song']['SongTitle']);

                                    if ('T' == $value['Song']['Advisory'])
                                    {

                                        if (strlen($commingSoonSongTitle) > 13)
                                            echo substr($commingSoonSongTitle, 0, 13) . "...";
                                        else
                                            echo $commingSoonSongTitle;
                                    }else
                                    {
                                        if (strlen($commingSoonSongTitle) > 20)
                                            echo substr($commingSoonSongTitle, 0, 20) . "...";
                                        else
                                            echo $commingSoonSongTitle;
                                    }
                                    ?>
                                </a>	<?php
                                if ('T' == $value['Song']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>
                            </div>
                            <div class="artist-name">
                                <a title="<?php echo $this->getTextEncode($value['Song']['Artist']); ?>" href="/artists/album/<?php echo str_replace('/', '@', base64_encode($value['Song']['ArtistText'])); ?>/<?= base64_encode($value['Song']['Genre']) ?>">
                                    <?php
                                    $commingSoonSongArtistTitle = $this->getTextEncode($value['Song']['Artist']);
                                    if (strlen($commingSoonSongArtistTitle) > 20)
                                        echo substr($commingSoonSongArtistTitle, 0, 20) . "...";
                                    else
                                        echo $commingSoonSongArtistTitle;
                                    ?>
                                </a>
                            </div>
                        </div>

                        <?php
                        if ($sr_no % 2 == 1 || $sr_no == ($total_songs - 1))
                        {
                            ?> </li> <?php } ?>

                    <?php
                    $sr_no++;
                }
               // $this->log("index.ctp commingsoon song end", "siteSpeed"); 
                ?>

            </ul>
        </div> <!-- end #coming-soon-singles-grid -->
        <div id="coming-soon-videos-grid" class="clearfix horiz-scroll">
            <ul class="clearfix" style="width:3333px;">										
                <?php
                $total_videos = count($coming_soon_videos);
                $sr_no = 0;
              //  $this->log("index.ctp commingsoon videos start", "siteSpeed"); 
                foreach ($coming_soon_videos as $key => $value)
                {

                    //hide song if library block the explicit content
                    if (($this->Session->read('block') == 'yes') && ($value['Video']['Advisory'] == 'T'))
                    {
                        continue;
                    }

                    //$albumArtwork = shell_exec('perl files/tokengen ' . 'sony_test/'.$value['Image_Files']['CdnPath']."/".$value['Image_Files']['SourceURL']);
                    //$videoAlbumImage =  Configure::read('App.Music_Path').$albumArtwork;

                    if ($sr_no >= 20)
                        break;
                    ?>
                    <?php
                    if ($sr_no % 2 == 0)
                    {
                        ?><li> <?php } ?>
                        <div class="video-detail">
                            <div class="video-cover-container">
                                <a href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <img  src="<?php echo $value['videoAlbumImage']; ?>"  alt="<?php echo $this->getValidText($this->getTextEncode($value['Video']['Artist']) . ' - ' . $this->getTextEncode($value['Video']['VideoTitle'])); ?>" width="275" height="162" />
                                </a>
                                <?php
                                if ($this->Session->read("patron"))
                                {
                                    ?> 
                                    <a class="add-to-playlist-button no-ajaxy" href="javascript:void(0)">

                                    </a>
                                    <div class="wishlist-popover">	
                                        <?php
                                        $wishlistInfo = $this->WishlistVideo->getWishlistVideoData($value["Video"]["ProdID"]);
                                        echo $this->WishlistVideo->getWishListVideoMarkup($wishlistInfo, $value["Video"]["ProdID"], $value["Video"]["provider_type"]);
                                        //echo $this->Queue->getSocialNetworkinglinksMarkup();
                                        ?>


                                    </div>
                                <?php } ?>
                            </div>
                            <div class="video-title">

                                <a title="<?php echo $this->getValidText($this->getTextEncode($value['Video']['VideoTitle'])); ?>" href="/videos/details/<?php echo $value['Video']['ProdID']; ?>">
                                    <?php
                                    $commingSoonVideoTitle = $this->getTextEncode($value['Video']['VideoTitle']);

                                    if ('T' == $value['Video']['Advisory'])
                                    {
                                        if (strlen($commingSoonVideoTitle) > 15)
                                            echo substr($commingSoonVideoTitle, 0, 15) . "...";
                                        else
                                            echo $commingSoonVideoTitle;
                                    }else
                                    {
                                        if (strlen($commingSoonVideoTitle) > 20)
                                            echo substr($commingSoonVideoTitle, 0, 20) . "...";
                                        else
                                            echo $commingSoonVideoTitle;
                                    }
                                    ?> </a><?php
                                if ('T' == $value['Video']['Advisory'])
                                {
                                    ?> <span style="color: red;display: inline;"> (Explicit)</span> <?php } ?>

                            </div>
                            <div class="artist-name">


                                <a title="<?php echo $this->getTextEncode($value['Video']['Artist']); ?>" href="javascript:void(0)">
                                    <?php
                                    if (strlen($value['Video']['Artist']) > 20)
                                        echo substr($value['Video']['Artist'], 0, 20) . "...";
                                    else
                                        echo $value['Video']['Artist'];
                                    ?></a>

                                                                                                                                    <!--	<a href="artists/album/<?php echo str_replace('/', '@', base64_encode($value['Video']['ArtistText'])); ?>/<?= base64_encode($value['Video']['Genre']) ?>">
                                <?php
                                if (strlen($value['Video']['Artist']) > 20)
                                    echo substr($value['Video']['Artist'], 0, 20) . "...";
                                else
                                    echo $value['Video']['Artist'];
                                ?></a>  -->
                            </div>
                        </div>

                        <?php
                        if ($sr_no % 2 == 1 || $sr_no == ($total_videos - 1))
                        {
                            ?> </li> <?php } ?>

                    <?php
                    $sr_no++;
                }
                // $this->log("index.ctp commingsoon videos end", "siteSpeed"); 
                ?>

            </ul>
        </div><!-- end videos grid -->

    </div> <!-- end coming soon -->

    <div class="whats-happening">
        <header>
            <h3>News</h3>
            <!--
            <div class="whats-happening-see-all">
                    <a href="#">View All</a>
            </div>
            -->
        </header>



        <div id="whats-happening-grid" class="horiz-scroll">
            <ul class="clearfix" style="width:4400px;">
                <?php
               // $this->log("index.ctp news start", "siteSpeed");
                $count = 1;
                foreach ($news as $key => $value)
                {
                    //  $newsText = str_replace('<div', '<p', strip_tags(($value['News']['body']), '<p>'));
                    $newsText = str_replace('<div', '<p', $value['News']['body']);
                    $newsText = str_replace('</div>', '</p>', $newsText);
                    ?>
                    <li>
                        <div class="post">
                            <div class="post-header-image">
                                <a href="javascript:void(0);"><img src ='<?php echo $cdnPath . 'news_image/' . $value['News']['image_name']; ?>' style="width:417px;height:196px;" alt="<?php echo $this->getValidText($value['News']['subject']); ?>" /></a>
                            </div>
                            <div class="post-title">
                                <a href="javascript:void(0);"><?php echo $this->getValidText($value['News']['subject']); ?></a>
                            </div>
                            <div class="post-date">
                                <?php echo $value['News']['place'] ?> : <?php echo date("F d, Y", strtotime($value['News']['created'])) ?>
                            </div>
                            <!-- <div class="post-excerpt"  id="shortNews<?php echo $value['News']['id']; ?>">
                            <?php
                            // echo $this->getTextEncode(substr($newsText,0, 325));                                                                                                                 
                            // echo $this->getTextEncode(substr($newsText,0, strpos($newsText, "</p>")+4));
                            ?>		
                                     <div class="more">
                            <?php
                            //if(strlen($newsText) > strpos($newsText, "</p>")+4)
                            /* if(strlen($newsText) >= 325)
                              {
                              ?>
                              <a href="javascript:void(0);" onClick="showhide('detail', '<?php echo $value['News']['id']; ?>')")">More ></a>
                              <?php
                              } */
                            ?>		</div>									
                            </div>
                            
                            <div id="detailsNews<?php //echo $value['News']['id'];         ?>" style="display:none" class="post-excerpt">
                            <?php //echo $newsText;      ?>
                             <a href="javascript:void(0);" class="more" onClick="showhide('short', '<?php //echo $value['News']['id'];          ?>')">- See Less</a>
                            </div> -->


                            <?php /* <div id="detailsNews" class="post-excerpt"> */ ?>
                            <div class="post-excerpt">
                                <?php /* echo  wordwrap($newsText, 65, "<br />\n", TRUE); */ ?>
                                <?php echo $newsText; ?>
                           <!-- <a href="javascript:void(0);" class="more" onClick="showhide('short', '<?php echo $value['News']['id']; ?>')">- See Less</a> -->
                            </div>





                        </div>
                    </li>

                    <?php
                    if ($count == 10)
                        break;
                    $count++;
                }
                // $this->log("index.ctp news end", "siteSpeed");
                ?>
            </ul>



        </div>

</section> <!-- end .news -->	

