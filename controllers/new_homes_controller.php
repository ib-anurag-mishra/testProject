<?php

class NewHomesController extends AppController {

    var $name = 'Homes';
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Form', 'Library', 'Page', 'Wishlist', 'WishlistVideo', 'Song', 'Language', 'Session', 'Mvideo', 'Download', 'Videodownload', 'Queue', 'Token');
    var $components = array('RequestHandler', 'ValidatePatron', 'Downloads', 'PasswordHelper', 'Email', 'SuggestionSong', 'Cookie', 'Session', 'Auth', 'Downloadsvideos', 'Common', 'Streaming');
    var $uses = array('Home', 'User', 'Featuredartist', 'Artist', 'Library', 'Download', 'Genre', 'Currentpatron', 'Page', 'Wishlist', 'WishlistVideo', 'Album', 'Song', 'Language', 'Searchrecord', 'LatestDownload', 'Siteconfig', 'Country', 'LatestVideodownload', 'News', 'Video', 'Videodownload', 'Zipcode', 'StreamingHistory', 'MemDatas', 'Token');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter() {

        parent::beforeFilter();

        // For super Admin while accesing convertString action
        if ($this->params['action'] == 'convertString' && ($this->Session->read('Auth.User.type_id') == 1)) {
            $pat_id = $this->Session->read('Auth.User.id');
        } else {       //  For Front End
            $pat_id = $this->Session->read('patron');
        }

        if (!empty($pat_id)) { //  After Login
            $this->Auth->allow('*');
        } else { //  Before Login 
            $this->Auth->allow('display', 'aboutus', 'index', 'us_top_10', 'chooser', 'forgot_password', 'new_releases', 'language', 'checkPatron', 'approvePatron', 'my_lib_top_10', 'checkStreamingComponent', 'terms');
        }

        $this->Cookie->name = 'baker_id';
        $this->Cookie->time = 3600; // or '1 hour'
        $this->Cookie->path = '/';
        $this->Cookie->domain = 'freegalmusic.com';
        //$this->Cookie->key = 'qSI232qs*&sXOw!';
    }
	var $layout = 'home';

	public function index() {

		//check the server port and redirect to index page
		if ($_SERVER['SERVER_PORT'] == 443) {
			$this->redirect('http://' . $_SERVER['HTTP_HOST'] . '/index');
		}
		
		$this->layout = 'home';

		// Local Top Downloads functionality
		$libId 	   = $this->Session->read('library');
		$patId 	   = $this->Session->read('patron');
		$country   = $this->Session->read('territory');
		$territory = $this->Session->read('territory');

		$nationalTopDownload = array();

		if (!empty($patId)) {

			$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
			$patronDownload  = $this->Downloads->checkPatronDownload($patId, $libId);
	
			$this->set('libraryDownload', $libraryDownload);
			$this->set('patronDownload', $patronDownload);
		}
	
		// National Top 100 Songs slider and Downloads functionality
		if (($national = Cache::read("national" . $territory)) === false) {

			if($territory == 'US' || $territory == 'CA' || $territory == 'AU' || $territory == 'NZ') {

				$cacheFlag = $this->MemDatas->find('count',array('conditions' => array('territory'=>$territory,'vari_info != '=>'')));
				
				if($cacheFlag > 0) {

					$memDatasArr = $this->MemDatas->find('first',array('conditions' => array('territory'=>$territory)));
					$unMemDatasArr = unserialize(base64_decode($memDatasArr['MemDatas']['vari_info']));
					Cache::write("national" . $territory,$unMemDatasArr);
					$nationalTopDownload = $unMemDatasArr;
				
				} else {
					$nationalTopDownload = $this->Common->getNationalTop100($territory);
					$nationalTopDownloadSer = base64_encode(serialize($nationalTopDownload));
					$memQuery = "update mem_datas  set vari_info='".$nationalTopDownloadSer."'  where territory='".$territory."'";
					$this->MemDatas->setDataSource('master');
					$this->MemDatas->query($memQuery);
					$this->MemDatas->setDataSource('default');
				}
			} else {
				$nationalTopDownload = $this->Common->getNationalTop100($territory);
			}
		} else {
			$nationalTopDownload = Cache::read("national" . $territory);
		}

		$this->set('nationalTopDownload', $nationalTopDownload);

		// National Top 100 Albums slider
		if (($national = Cache::read("nationaltop100albums" . $territory)) === false) {
			$nationalTopAlbums = $this->Common->getNationalTop100Albums($territory);
		} else {
			$nationalTopAlbums = Cache::read("nationaltop100albums" . $territory);
		}
	$nationalTopAlbumsDownload = $nationalTopAlbums;
		$this->set('nationalTopAlbumsDownload', $nationalTopAlbums);	

		$ids = '';
		$ids_provider_type = '';

		//featured artist slideshow code start
		if (($artists = Cache::read("featured" . $country)) === false) {
			$featured = $this->Common->getFeaturedArtists($territory);
		} else {
			//fetched all the information from the cache
			$featured = Cache::read("featured" . $country);
		}
$featuredArtists = $featured;
		$this->set('featuredArtists', $featured);
	
		/*
		 Code OF NEWS Section --- START
		*/
	
		if (!$this->Session->read('Config.language') && $this->Session->read('Config.language') == '') {
			$this->Session->write('Config.language', 'en');
		}
	
		$news_rs = array();
		
		//create the cache variable name
		$newCacheVarName = "news".$this->Session->read('territory').$this->Session->read('Config.language');
		
		//first check lenguage and territory set or not
		if($this->Session->read('territory') && $this->Session->read('Config.language')) {
			
			if (($newsInfo = Cache::read($newCacheVarName)) === false) {

				//if cache not set then run the queries
				$news_rs = $this->News->find('all', array('conditions' => array('AND' => array('language' => $this->Session->read('Config.language'), 'place LIKE' => "%".$this->Session->read('territory')."%")),
						'order' => 'News.created DESC',
						'limit' => '10'
				));

				Cache::write($newCacheVarName,$news_rs);
			} else {
				//get all the information from the cache for news
				$news_rs = Cache::read($newCacheVarName);
			}
		}
$news = $news_rs;
		$this->set('news', $news_rs);

		/*
		 Code OF NEWS Section --- END
		*/
	
		/*
		 *  Code For Coming Soon --- START
		*/

		$territory = $this->Session->read('territory');
	
		if (($coming_soon = Cache::read("coming_soon_songs" . $territory)) === false) {

			$coming_soon_rs = $this->Common->getComingSoonSongs($territory);
		} else {    //  Show From Cache
			$coming_soon_rs = Cache::read("coming_soon_songs" . $territory);
		}

		$this->set('coming_soon_rs', $coming_soon_rs);
	
		// Videos
		if (($coming_soon = Cache::read("coming_soon_videos" . $territory)) === false) {

			$coming_soon_videos = $this->Common->getComingSoonVideos($territory);
		} else {    //  Show From Cache
			$coming_soon_videos = Cache::read("coming_soon_videos" . $territory);
		}

		$this->set('coming_soon_videos', $coming_soon_videos);
	
		/*
		 * Code For Coming Soon --- END
		*/
		$new_homes = '
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
		
				$new_homes .= $html->link($html->image($srcImg, array("height" => "250", "width" => "250", "class" => $lazyClass, "data-original" => $dataoriginal)), array('controller' => 'artists', 'action' => 'view', base64_encode($value['Song']['ArtistText']), $value['Song']['ReferenceID'], base64_encode($value['Song']['provider_type'])), array('class' => 'first', 'escape' => false));
		
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
		$new_homes = Cache::read("new_homes");
		
		if($new_homes === false) {
			Cache::write("new_homes", $newHomesCache);
			$new_homes = $newHomesCache;
		}
		$this->set('new_homes', $new_homes);
	}
	
	function getTextEncode($text)
	{
		// Function used only in Front End
		$text = iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
		return iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
	}
	function getValidText($text)                    // Replace Single, Double Quotes, & with HTML entities in Text
	{
		return htmlentities($this->getAdminTextEncode($text));
	}
}