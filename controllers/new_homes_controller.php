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
	//var $layout = 'home';

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
		
		/*$new_homes = Cache::read("new_homes");
		
		if($new_homes === false) {
			Cache::write("new_homes", $newHomesCache);
			$new_homes = $newHomesCache;
		}
		$this->set('new_homes', $new_homes);*/
	}
}