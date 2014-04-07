<?php

/*
  File Name : genres_controller.php
  File Description : Genre controller page
  Author : m68interactive
 */

ini_set('memory_limit', '2048M');

Class GenresController extends AppController
{
    var $uses = array('Category', 'Files', 'Album', 'Song', 'Download','Searchrecord','LatestVideodownload','LatestDownload','Page', 'Token');    
    var $components = array('Session', 'Auth', 'Acl', 'RequestHandler', 'Downloads', 'ValidatePatron', 'Common', 'Streaming','Solr');
    var $helpers = array('Cache', 'Library', 'Page', 'Wishlist', 'Language', 'Queue','Session','Album','Html','Session','Queue','Wishlist', 'Token');
    
    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allowedActions = array('view', 'index', 'ajax_view', 'ajax_view_pagination', 'callToAllFunctions', 'album');
        $libraryCheckArr = array("view", "index");
    }

    /*
      Function Name : index
      Desc : actions on landing page
     */

    function index()
    {
        /**
         * Fix for Genre page other than view() method is called
         * 
         */
        
        $url = explode('/', $this->params['url']['url']);
        if ($url[1] != 'view')
        {
            $this->redirect('/genres/view/');
        }

        $country = $this->Session->read('territory');
        $this->layout = 'home';
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);
        $this->Genre->Behaviors->attach('Containable');
        $this->Genre->recursive = 2;
        $genreAll = Cache::read("genre" . $country);
        if ($genreAll === false) {
        //if(1){ 
            $genreAll = $this->Common->getGenres($country);
        }
        $this->set('genresAll', $genreAll);

        $category_ids = $this->Category->find('list', array('conditions' => array('Language' => Configure::read('App.LANGUAGE')), 'fields' => 'id'));
        $rand_keys = array_rand($category_ids, 4);
        $rand_val = implode(",", $rand_keys);
        $categories = $this->Category->find('all', array(
            'conditions' => array('id IN (' . $rand_val . ')'),
            'fields' => 'Genre'));
        $i = 0;
        $j = 0;
        foreach ($categories as $category)
        {
            $genreName = $category['Category']['Genre'];
            if ($this->Session->read('block') == 'yes')
            {
                $block = 'yes';
                $cond = array('Song.Advisory' => 'F');
            }
            else
            {
                $cond = "";
                $block = 'no';
            }
            $genreDetails = Cache::read($genreName . $block);
            if ($genreDetails === false)
            {
                $this->Song->recursive = 2;
                $this->Song->Behaviors->attach('Containable');
                $genreDetails = $this->Song->find('all', array('conditions' =>
                    array('and' =>
                        array(
                            array('Genre.Genre' => $genreName),
                            array("Song.ReferenceID <> Song.ProdID"),
                            array('Song.DownloadStatus' => 1),
                            array("Song.Sample_FileID != ''"),
                            array("Song.FullLength_FIleID != ''"),
                            array('Song.provider_type = Genre.provider_type'),
                            array('Song.provider_type = Country.provider_type'),
                            array('Country.Territory' => $country),
                            array("Song.UpdateOn >" => date('Y-m-d', strtotime("-1 week"))), $cond
                        )
                    ),
                    'fields' => array(
                        'DISTINCT Song.ProdID',
                        'Song.ReferenceID',
                        'Song.Title',
                        'Song.ArtistText',
                        'Song.DownloadStatus',
                        'Song.SongTitle',
                        'Song.Artist',
                        'Song.Advisory',
                        'Song.provider_type'
                    ),
                    'contain' => array(
                        'Genre' => array(
                            'fields' => array(
                                'Genre.Genre'
                            )
                        ),
                        'Country' => array(
                            'fields' => array(
                                'Country.Territory',
                                'Country.SalesDate',
                            )
                        ),
                        'Sample_Files' => array(
                            'fields' => array(
                                'Sample_Files.CdnPath',
                                'Sample_Files.SaveAsName',
                                'Sample_Files.SourceURL'
                            ),
                        ),
                        'Full_Files' => array(
                            'fields' => array(
                                'Full_Files.CdnPath',
                                'Full_Files.SaveAsName',
                                'Full_Files.SourceURL'
                            ),
                        ),
                    ), 'limit' => '50'));
                Cache::write($genreName . $block, $genreDetails);
            }
            $finalArr = Array();
            $songArr = Array();
            if (count($genreDetails) > 3)
            {
                $rand_keys = array_rand($genreDetails, 3);
                $songArr[0] = $genreDetails[$rand_keys[0]];
                $songArr[1] = $genreDetails[$rand_keys[1]];
                $songArr[2] = $genreDetails[$rand_keys[2]];
            }
            else
            {
                $songArr = $genreDetails;
            }
            $this->Download->recursive = -1;
            foreach ($songArr as $genre)
            {
                $this->Song->recursive = 2;
                $this->Song->Behaviors->attach('Containable');
                $downloadData = $this->Album->find('all', array(
                    'conditions' => array('Album.ProdID' => $genre['Song']['ReferenceID'], 'Song.provider_type = Genre.provider_type', 'Song.provider_type = Country.provider_type'),
                    'fields' => array(
                        'Album.ProdID',
                    ),
                    'contain' => array(
                        'Files' => array(
                            'fields' => array(
                                'Files.CdnPath',
                                'Files.SaveAsName',
                                'Files.SourceURL',
                            ),
                        )
                )));
                
                $albumArtwork  = $this->Token->regularToken($downloadData[0]['Files']['CdnPath'] . "/" . $downloadData[0]['Files']['SourceURL']);                
                $sampleSongUrl = $this->Token->regularToken($genre['Sample_Files']['CdnPath'] . "/" . $genre['Sample_Files']['SaveAsName']);                
                $songUrl = $this->Token->regularToken($genre['Full_Files']['CdnPath'] . "/" . $genre['Full_Files']['SaveAsName']);
                $finalArr[$i]['Album'] = $genre['Song']['Title'];
                $finalArr[$i]['Song'] = $genre['Song']['SongTitle'];
                $finalArr[$i]['Artist'] = $genre['Song']['Artist'];
                $finalArr[$i]['ProdArtist'] = $genre['Song']['ArtistText'];
                $finalArr[$i]['Advisory'] = $genre['Song']['Advisory'];
                $finalArr[$i]['AlbumArtwork'] = $albumArtwork;
                $finalArr[$i]['SongUrl'] = $songUrl;
                $finalArr[$i]['ProdId'] = $genre['Song']['ProdID'];
                $finalArr[$i]['ReferenceId'] = $genre['Song']['ReferenceID'];
                $finalArr[$i]['provider_type'] = $genre['Song']['provider_type'];
                $finalArr[$i]['SalesDate'] = $genre['Country']['SalesDate'];
                $finalArr[$i]['SampleSong'] = $sampleSongUrl;
                $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $genre['Song']['ProdID'], 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                if (count($downloadsUsed) > 0)
                {
                    $finalArr[$i]['status'] = 'avail';
                }
                else
                {
                    $finalArr[$i]['status'] = 'not';
                }
                $i++;
            }
            $finalArray[$j] = $finalArr;
            $finalArray[$j]['Genre'] = $genreName;
            $j++;
        }
        $this->set('categories', $finalArray);
    }

    /*
    Function Name : view
    Desc : default funciton for Genre page
    *
    *
    * @param $Genre VarChar  'Genre value'
    * @param $Artist VarChar  'Artist value'
    *
    * @return void
    */
    function view($Genre = null, $Artist = null)
    {
                
        $this->layout = 'home';
       //set the default page value
        $pageNo =1;
        
        //set the selected artist value
        $slectedArtistFilter = $Artist;
        
        //get values if user login    
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $country = $this->Session->read('territory');
        if($patId){
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }
        

        //set the Genre and Artist value according to upcomming value        
        if ($Genre == '')
        {
            $Genre = "QWxs";
        }
        if ($Artist == '' || $Artist == 'All')
        {
            $Artist = "All";
            $slectedArtistFilter =$Artist;
        }
        
        $genreAll = Cache::read("genre" . $country);      
        if ($genreAll === false) {
        //if(1){             
            $genreAll = $this->Common->getGenres($country);
        }          
       
          
        //check the genre value         
        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);
        $this->set('genre', $genre);        
        
        //create the cache variable name
        $cacheVariableName = base64_encode($genre).strtolower($country).strtolower($Artist).$pageNo;
       
        
        //check cache variable are set or not
        $artistList = Cache::read($cacheVariableName);
        if ($artistList === false)
       // if(1)
        {             
               $artistList = $this->Common->getArtistText($genre,$country,$Artist,$pageNo);
        } 
        
        //prepare the array that contains all alphabets which have no any artist value for the selected changes
        $artistsNoAlpha = array();
        $artistsNoAlpha = $this->checkArtistFilter($genre, $country ,$pageNo);  
        
        //set the value for generating view
        $this->set('totalPages', 150);
        $this->set('genresAll', $genreAll); 
        $this->set('artistsNoAlpha', $artistsNoAlpha);
        $this->set('selectedAlpha', $slectedArtistFilter);
        $this->set('artistList', $artistList);
        
        
        //app\views\genres\view.ctp
             
    }

    /*
    Function Name : ajax_view
    Desc : call when user click on any Genre name on Genre page
    *
    *
    * @param $Genre VarChar  'Genre value'
    * @param $Artist VarChar  'Artist value'
    *
    * @return void
    */
    function ajax_view($Genre = null, $Artist = null)
    {
       
       
        $this->layout = 'ajax';
        
        $pageNo =1;
        
        //set the selected artist value
        $slectedArtistFilter = $Artist;
        
        //get values if user login    
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $country = $this->Session->read('territory');
        if($patId){
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }
        

         //set the Genre and Artist value according to upcomming value        
        if ($Genre == '' )
        {
            $Genre = "QWxs";
        }
        if ($Artist == '' || $Artist == 'All')
        {
            $Artist = "All";
        }
                            
        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);
        $this->set('genre', $genre); 
        
        //create the cache variable name
        $cacheVariableName = base64_encode($genre).strtolower($country).strtolower($Artist).$pageNo;     
       
        $artistList = Cache::read($cacheVariableName);
        //check cache variable are set or not
        if ($artistList === false)         
        {
            echo 'Not Exist';
                $artistList = $this->Common->getArtistText($genre,$country,$Artist,$pageNo);                
        } 
      
      
       //prepare the array that contains all alphabets which have no any artist value
        $artistsNoAlpha = array();
        $artistsNoAlpha = $this->checkArtistFilter($genre, $country ,$pageNo);
        
       // print_r($artistsNoAlpha);
        //set the value for generating view
        $this->set('totalPages', 150);
        $this->set('artistsNoAlpha', $artistsNoAlpha);
        $this->set('selectedAlpha', $slectedArtistFilter);
        $this->set('artistList', $artistList);
        
        //app\views\genres\ajax_view.ctp
    }

   /*
    Function Name : ajax_view_pagination
    Desc : call when user scoll the aritst list on Genre page
    *
    *
    * @param $Genre VarChar  'Genre value'
    * @param $Artist VarChar  'Artist value'
    *
    * @return void
    */
    function ajax_view_pagination($Genre = null, $Artist = null)
    {
         $this->layout = 'ajax';

         //pagination value for login redirect issue on genre page
         if(isset($this->params['named']['page'])){
             $pageNo = $this->params['named']['page'];
         }else{
             $pageNo =2;
         }
         
        //get values if user login       
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $country = $this->Session->read('territory');
        if($patId){
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
        }
        

         //set the Genre and Artist value according to upcomming value        
        if ($Genre == '')
        {
            $Genre = "QWxs";
        }
        if ($Artist == '' || $Artist == 'All')
        {
            $Artist = "All";
        }            
          
                     
        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);
        $this->set('genre', $genre); 
        
        $cacheVariableName = base64_encode($genre).strtolower($country).strtolower($Artist).$pageNo;       
       
        $artistList = Cache::read($cacheVariableName);
        if ($artistList === false) { 
            echo 'Not Exist';
              $artistList = $this->Common->getArtistText($genre,$country,$Artist,$pageNo);
        }
        //$this->getSortArtistList($artistList);      
      
            
        //prepare the array that contains all alphabets which have no any artist value
         $artistsNoAlpha = array();
         $artistsNoAlpha = $this->checkArtistFilter($genre, $country ,$pageNo);
        
        
        //set the value for generating view
        $this->set('totalPages', 150);
        $this->set('artistsNoAlpha', $artistsNoAlpha);
        $this->set('selectedAlpha', $Artist);
        $this->set('artistList', $artistList);
        
        //app\views\genres\ajax_view_pagination.ctp
    }
    
    /*
    Function Name : checkArtistFilter
    Desc : check the artist list filter list
    *
    *
    * @param $genre VarChar  'Genre value'
    * @param $country VarChar  'territory value'
    * @param $alphabet VarChar  'alphabets value'
    * @param $pageNo int  'page value'
    *
    * @return $artistsNoAlpha array
    */
    function checkArtistFilter($genre, $country,$pageNo)
    {
        $artistsNoAlpha = array();
        for($k = 63;$k < 91;$k++){
            $alphabet = chr($k);
            if($k==63){
                $alphabet = 'All';
            }
            if($k==64){
                $alphabet = 'spl';
            }
            $filterCacheVariableName = base64_encode($genre).strtolower($country).strtolower($alphabet).$pageNo;
          
            
            if(($artistAll = Cache::read($filterCacheVariableName)) === false){
                $artistsNoAlpha[]= $alphabet;
            }           
        } 
        return $artistsNoAlpha;
    }
    
    /*
      Function Name : getSortArtistList
      Desc : actions for admin end manage genre to add/edit genres
     */
    function getSortArtistList($allArtists){
        
        $allArtistsNew = $allArtists;

        for ($i = 0; $i < count($allArtists); $i++)
        {
            if ($allArtistsNew[$i]['Song']['ArtistText'] != "")
            {
                $allArtists[$i] = $allArtistsNew[$i];
            }
        }

        $tempArray = array();
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            $tempArray[] = trim($allArtistsNew[$i]['Song']['ArtistText']);
        }
        sort($tempArray, SORT_STRING);

        for ($i = 0; $i < count($tempArray); $i++)
        {
            $allArtists[$i]['Song']['ArtistText'] = trim($tempArray[$i]);
        }
        
        return $allArtists;
    }

    /*
      Function Name : admin_managegenre
      Desc : actions for admin end manage genre to add/edit genres
     */

    function admin_managegenre()
    {
        if ($this->data)
        {
            $this->Category->deleteAll(array('Language' => Configure::read('App.LANGUAGE')), false);
            $selectedGenres = Array();
            $i = 0;
            foreach ($this->data['Genre']['Genre'] as $k => $v)
            {
                if ($i < '8')
                {
                    if ($v != '0')
                    {
                        $selectedGenres['Genre'] = $v;
                        $selectedGenres['Language'] = Configure::read('App.LANGUAGE');
                        $this->Category->save($selectedGenres);
                        $this->Category->id = false;
                        $i++;
                    }
                }
            }
            $this->Session->setFlash('Your selection saved successfully!!', 'modal', array('class' => 'modal success'));
        }
        $this->Genre->recursive = -1;
        $allGenres = $this->Genre->find('all', array(
            'fields' => 'DISTINCT Genre',
            'order' => 'Genre')
        );
        $this->set('allGenres', $allGenres);
        $this->Category->recursive = -1;
        $selectedGenres = array();
        $selectedGenres = $this->Category->find('all', array('fields' => array('Genre'), 'conditions' => array('Language' => Configure::read('App.LANGUAGE'))));
        foreach ($selectedGenres as $selectedGenre)
        {
            $selArray[] = $selectedGenre['Category']['Genre'];
        }
        $this->set('selectedGenres', $selArray);
        $this->layout = 'admin';
    }


    function searchrecords($type, $search_text)
    {
        $search_text = strtolower(trim($search_text));
        $search_text = preg_replace('/\s\s+/', ' ', $search_text);
        $insertArr['search_text'] = $search_text;
        $insertArr['type'] = $type;
        $genre_id_count_array = $this->Searchrecord->find('all', array('conditions' => array('search_text' => $search_text, 'type' => $type)));
        if (count($genre_id_count_array) > 0)
        {
            $insertArr['count'] = $genre_id_count_array[0]['Searchrecord']['count'] + 1;
            $insertArr['id'] = $genre_id_count_array[0]['Searchrecord']['id'];
        }
        else
        {
            $insertArr['count'] = 1;
        }

        return $insertArr;
    }

	/*
      Function Name : album
      Desc : actions for genre albums 
    */
	
	function album($page = 1, $facetPage = 1)
    {
        $layout = $_GET['layout'];

        if (('' == trim($_GET['q'])) || ('' == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
        }// unset session when no params
        if ((isset($_SESSION['SearchReq'])) && ($_SESSION['SearchReq']['word'] != trim($_GET['q'])) && ($_SESSION['SearchReq']['type'] == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
            $this->redirect(array('controller' => 'search', 'action' => 'index?q=' . $_GET['q'] . '&type=' . $_GET['type']));
        }//reset session & redirect to 1st page
        if (('' != trim($_GET['q'])) && ('' != trim($_GET['type'])))
        {
            $_SESSION['SearchReq']['word'] = $_GET['q'];
            $_SESSION['SearchReq']['type'] = $_GET['type'];
        }//sets values in session

        $queryVar = null;
        $check_all = null;
        $sortVar = 'ArtistText';
        $sortOrder = 'asc';

        if (isset($_GET['q']))
        {
            $queryVar = $_GET['q']; 
        }
        if (isset($_GET['type']))
        {
            $type = $_GET['type'];
            $typeVar = $_GET['type'];
        }
        else
        {
            $typeVar = 'album';
        }
        $this->set('type', $typeVar);
		
		if(isset($_GET['filter']))
		{
			$filter = $_GET['filter'];
		}
		$this->set('filter',$filter);

        if (isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
			$sortVar = 'Title';
            
        }
        else
        {
            $sort = 'artist';
        }

        $this->set('sort', $sort);

        if (isset($_GET['sortOrder']))
        {
            $sortOrder = $_GET['sortOrder'];
            $sortOrder = (($sortOrder == 'asc' || $sortOrder == 'desc') ? $sortOrder : 'asc');
        }
        else
        {
            $sortOrder = 'asc';
        }

        $this->set('sortOrder', $sortOrder);

        if (!empty($queryVar))
        {
            //Added code for log search data
            $insertArr[] = $this->searchrecords($typeVar, $queryVar);
            $this->Searchrecord->saveAll($insertArr);
            //End Added code for log search data
            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();
            $total = 0;
            $limit = 10;

            if (!isset($page) || $page < 1)
            {
                $page = 1;
            }
            else
            {
                $page = $page;
            }

            if (!isset($facetPage) || $facetPage < 1)
            {
                $facetPage = 1;
            }
            else
            {
                $facetPage = $facetPage;
            }

            $country = $this->Session->read('territory');
            $songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country);

            $total = $this->Solr->total;
            $totalPages = ceil($total / $limit);

            if ($total != 0)
            { }
    
            $songArray = array();
            foreach ($songs as $key => $song)
            {
                $songArray[] = $song->ProdID;
            }
            
      
            $downloadsUsed = $this->LatestDownload->find('all', array('conditions' => array('LatestDownload.ProdID in (' . implode(',', $songArray) . ')', 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
   
            foreach ($songs as $key => $song)
            {
                $set = 0;
                foreach ($downloadsUsed as $downloadKey => $downloadData)
                {
                   if ($downloadData['LatestDownload']['ProdID'] == $song->ProdID)
                    {
                        $songs[$key]->status = 'avail';
                        $set = 1;
                        break;
                    }
                   
                }
                if ($set == 0)
                {
                    $songs[$key]->status = 'not';
                }
            }
            
            $this->set('songs', $songs);

            if (!empty($type))
            {
             	$limit = 12;
                $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'album', 0, $filter);
                       
                $albums = $this->Solr->groupSearch($queryVar, 'album', $facetPage, $limit, 0, null, 0, $filter);
                      
                $arr_albumStream = array();
                foreach ($albums as $objKey => $objAlbum) {
                	$arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                    	array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                    );
                }
                        
                $this->set('albumData', $albums);
                $this->set('arr_albumStream', $arr_albumStream);
                $this->set('totalFacetFound', $totalFacetCount);
                if (!empty($totalFacetCount))
                {
                    $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
                }
                else
                {
                    $this->set('totalFacetPages', 0);
                }
            }
          
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
            $this->set('total', $total);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('facetPage', $facetPage);
	  
        }
        $this->set('keyword', htmlspecialchars($queryVar));
        
        if (isset($this->params['isAjax']) && $this->params['isAjax'] && $layout == 'ajax')
        {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo $this->render();
            die;
        }
        else
        {
            $this->layout = 'home';
        }
    }
}

?>
