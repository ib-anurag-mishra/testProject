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

        $this->Auth->allowedActions = array('view', 'ajax_view', 'ajax_view_pagination', 'callToAllFunctions', 'setGenres','album');
	if(($this->Session->read('Auth.User.type_id')) && (($this->Session->read('Auth.User.type_id') == 1 || $this->Session->read('Auth.User.type_id') == 7))){
              $this->Auth->allow('admin_managegenre');
 } 
	
        $libraryCheckArr = array("view", "index");
    }

    
    //just for test only
    function setGenres(){
        set_time_limit(0);   
        $country = $this->Session->read('territory');
        $this->Common->getGenres($country);
       
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
       set_time_limit(0);       
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
  
        //check the genre list cache
        $genreAll = Cache::read("allgenre" . $country,'GenreCache');
        if ($genreAll === false || empty($genreAll))
        {
            $genreAll = $this->Common->getGenres($country);
        }
        // echo $genre = base64_encode('Acid Jazz');die;
        
        //check the genre value         
         $genre = base64_decode($Genre);
         
       
        $selectedGenre = $this->Common->getGenreForSelection($genre);
      
        $genre = mysql_escape_string($genre);
        $this->set('genre', $genre);        
        
        //create the cache variable name
        $cacheVariableName = base64_encode($genre).strtolower($country).strtolower($Artist).$pageNo;
       
        
        //check cache variable are set or not
        $artistList = Cache::read($cacheVariableName,'GenreCache');
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
        $this->set('selectedGenre', $selectedGenre);

        
        
        
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
        //$genre = mysql_escape_string($genre);
        $this->set('genre', $genre); 
        
        //create the cache variable name for checking the variable already exist or not
        $cacheVariableName = base64_encode($genre).strtolower($country).strtolower($Artist).$pageNo;     
       
        $artistList = Cache::read($cacheVariableName,'GenreCache');
        //check cache variable are set or not
        if ($artistList === false)         
        {
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
       
        $artistList = Cache::read($cacheVariableName,'GenreCache');
        if ($artistList === false) {             
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
          
            $artistAll = Cache::read($filterCacheVariableName,'GenreCache');
            if($artistAll === false){
                $artistsNoAlpha[]= $alphabet;
            }else{
                if(empty($artistAll)){
                    $artistsNoAlpha[]= $alphabet;
                }
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
	$userTypeId = $this->Session->read('Auth.User.type_id');
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
	$this->set('userTypeId',$userTypeId);
        $this->layout = 'admin';
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
                $albums = $this->Solr->groupSearch($queryVar, 'album', $facetPage, $limit, 0, null, 0, $filter);
                $totalFacetCount = $albums['ngroups'];
                $arr_albumStream = array();
                foreach ($albums as $objKey => $objAlbum) {
                    if ( !is_object( $objAlbum ) ) {
            		continue;
            	}
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
      Function Name : combine_genres
      Desc : Combining similiar Genres
     */
    
    
    
     /*function combine_genres($all_genres)
    {
        $genresArrComb = array('Acid', 'Alternative','Audio Books','Children’s Music','Chinese','Christian','Comedy','Country','Dance','Deutschrock','Easy Listening','Electronic','Euro','Gospel  Christion','Hip Hop','Indian Pop / Indie Pop','J-Pop','Latin Music','Miscellaneous','MPB','New Age','Pop Rock','Rap Hip Hop','R & B / RB','Rock','Rock Espanol','Sound Tracks','Spoken Word','World Music'); 
        $resulting_arr = array();
        for($i=0; $i<count($all_genres);$i++){
            
                    for($j=0; $j<count($genresArrComb);$j++){

                        if(in_array($all_genres[$i], $genresArrComb) && strlen($all_genres[$i])===strlen($genresArrComb[$j])){      // genre is found from $genresArrComb array  
                            if(!in_array($all_genres[$i], $resulting_arr)){
                                    array_push($resulting_arr, $all_genres[$i]);
                                    break;
                                }                                                        
                        }
                        elseif((stristr($all_genres[$i], $genresArrComb[$j]) && (strlen($all_genres[$i])>$genresArrComb[$j]))){ // similiar genres are found skip them
                            break;
                        }
                        elseif(!in_array($all_genres[$i], $resulting_arr) && !in_array($all_genres[$i], $resulting_arr)){
                                    array_push($resulting_arr, $all_genres[$i]);
                                    break;
                        }
                        
                    }
                    
        }
        return $resulting_arr;
      
    }*/
    
    
    

}

?>
