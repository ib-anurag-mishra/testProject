<?php $new_homes = '
		<section class="news">
		<div class="top-100">
		<header><h3> ' . __('National Top 100', true) . '</h3></header>
		<nav class="top-100-nav">
		<ul>
		<li><a href="#top-100-songs" id="songsIDVal" class="active no-ajaxy hp-tabs" data-category-type="songs" onclick="showHideGrid(\'songs\')">Songs</a></li>
		<li><a href="#top-100-videos" id="videosIDVal" class="no-ajaxy hp-tabs" data-category-type="videos" onclick="showHideGrid(\'videos\')">Albums</a></li>
		</ul>
		</nav>
		<div class="grids active">
		<div id="top-100-songs-grid" class="top-100-grids horiz-scroll active">
		<ul style="width:27064px;">
		';
		if (is_array($nationalTopDownload) && count($nationalTopDownload) > 0) {
			 
			$libId = $this->Session->read('library');
			$patId = $this->Session->read('patron');
		
			$k = 2000;
			$nationalTopDownloadCount = count( $nationalTopDownload );
		
			for ($i = 0; $i < $nationalTopDownloadCount; $i++) {
		
				if (($this->Session->read('block') == 'yes') && ($nationalTopDownload[$i]['Song']['Advisory'] == 'T')) {
					continue;
				}
		
				if ($i <= 9) {
					$lazyClass = '';
					$srcImg = $nationalTopDownload[$i]['songAlbumImage'];
					$dataoriginal = '';
				} else {                //  Apply Lazy Class for images other than first 10.
		
					$lazyClass = 'lazy';
					$srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
					$dataoriginal = $nationalTopDownload[$i]['songAlbumImage'];
				}
		
				$new_homes .='         <li>
				<div class="top-100-songs-detail">
				<div class="song-cover-container">
				<a href="/artists/view/' . base64_encode($nationalTopDownload[$i]['Song']['ArtistText']) . '/' . $nationalTopDownload[$i]['Song']['ReferenceID'] . '/' . base64_encode($nationalTopDownload[$i]['Song']['provider_type']) .'">
				<img class="' . $lazyClass .'" alt="' . $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']) . ' - ' . $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])) .'" src="' . $srcImg .'" data-original="'. $dataoriginal.'"  width="250" height="250" /></a>
				<div class="top-100-ranking"> ' .
				($i + 1)
				. '
				</div>
				<!-- Here we can write token1 -->
				</div>
		
				';
				if (strlen($nationalTopDownload[$i]['Song']['SongTitle']) >= 30)
				{
					$songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 30)) . "..";
				}
				else
				{
					$songTitle = $this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle']);
				}
		
				if ('T' == $nationalTopDownload[$i]['Song']['Advisory'])
				{
					if (strlen($songTitle) >= 20)
					{
						$songTitle = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['SongTitle'], 0, 20)) . "..";
					}
					$songTitle .='<span style="color: red;display: inline;"> (Explicit)</span> ';
				}
		
				if (strlen($nationalTopDownload[$i]['Song']['ArtistText']) >= 30)
				{
					$artistText = $this->getTextEncode(substr($nationalTopDownload[$i]['Song']['ArtistText'], 0, 30)) . "..";
				}
				else
				{
					$artistText = $this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText']);
				}
		
		
				$new_homes .= '<div class="song-title">
				<a title="'. $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['SongTitle'])) .'" href="/artists/view/'. base64_encode($nationalTopDownload[$i]['Song']['ArtistText']).'/'. $nationalTopDownload[$i]['Song']['ReferenceID'].'/'. base64_encode($nationalTopDownload[$i]['Song']['provider_type']).'">'. $this->getTextEncode($songTitle).'</a>
				</div>
				<div class="artist-name">
				<a title="'. $this->getValidText($this->getTextEncode($nationalTopDownload[$i]['Song']['ArtistText'])).'" href="/artists/album/'. base64_encode($nationalTopDownload[$i]['Song']['ArtistText']).'">'. $this->getTextEncode($artistText).'</a>
				</div>
				</div>
				</li>
		
				';
				$k++;
			}
		}
		
		$new_homes .= '</ul>
		</div>
		<div id="top-100-videos-grid" class="top-100-grids horiz-scroll">
		<ul style="width:27100px;">
		';
		$count = 1;
		if (count($nationalTopAlbumsDownload) > 0)
		{
			foreach ($nationalTopAlbumsDownload as $key => $value)
			{
				if (($this->Session->read('block') == 'yes') && ($value['Albums']['Advisory'] == 'T'))
				{
					continue;
				}
				 
				$new_homes .= '<li>
				<div class="album-container">
				';
				if ($count <= 10)
				{
					$lazyClass = '';
					$srcImg = $value['songAlbumImage'];
					$dataoriginal = '';
				}
				else
				{
					$lazyClass = 'lazy';
					$srcImg = $this->webroot . 'app/webroot/img/lazy-placeholder.gif';
					$dataoriginal = $value['songAlbumImage'];
				}
		
				//$new_homes .= $html->link($html->image($srcImg, array("height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal)), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false));
		
				$new_homes .= '<div class="top-100-ranking">'. $count .'</div>
				<!-- Here we can write token2 -->
				</div>
				<div class="album-title">
				<a title="'.$this->getValidText($this->getTextEncode($value['Albums']['AlbumTitle'])).'" href="/artists/view/'.base64_encode($value['Song']['ArtistText']).'/'.$value['Song']['ReferenceID'].'/'. base64_encode($value['Song']['provider_type']).'">
				';
				if (strlen($value['Albums']['AlbumTitle']) > 20)
					$new_homes .= $this->getValidText($this->getTextEncode(substr($value['Albums']['AlbumTitle'], 0, 20))) . "...";
				else
					$new_homes .= $value['Albums']['AlbumTitle'];
		
				$new_homes .= '</a> ';
				if ('T' == $value['Albums']['Advisory'])
				{
					$new_homes .=' <span style="color: red;display: inline;"> (Explicit)</span> ';
				}
				$new_homes .= '</div>
				<div class="artist-name">
				<a title="'. $this->getValidText($this->getTextEncode($value['Song']['Artist'])).'" href="/artists/album/'. str_replace('/', '@', base64_encode($value['Song']['ArtistText'])).'/'.base64_encode($value['Song']['Genre']) .'">
				';
				if (strlen($value['Song']['Artist']) > 32)
					$new_homes .= $this->getValidText($this->getTextEncode(substr($value['Song']['Artist'], 0, 32))) . "...";
				else
					$new_homes .= $this->getValidText($this->getTextEncode($value['Song']['Artist']));
		
				$new_homes .= '</a>
				</div>
				</li>
				';
				$count++;
			}
		}else
		{
		
			$new_homes .= '<span style="font-size:14px;">Sorry,there are no downloads.<span> ';
		}
		
		$new_homes .= '</ul>
		</div>
		</div> <!-- end .grids -->
		
		</div>
		</section> <!-- end .news -->';
		
		//$new_homes = Cache::read("new_homes");
		Cache::write("new_homes", $new_homes);
		$new_homes1 = Cache::read("new_homes");
		echo $new_homes1;
		/*if($new_homes === false) {
			
			$new_homes = $newHomesCache;
		}*/
?>