<?php

/*
  File Name : genres_controller.php
  File Description : Genre controller page
  Author : m68interactive
 */



Class GenresController extends AppController
{

    var $uses = array('Category', 'Files', 'Album', 'Song', 'Download');
    var $components = array('Session', 'Auth', 'Acl', 'RequestHandler', 'Downloads', 'ValidatePatron', 'Common', 'Streaming');
    var $helpers = array('Cache', 'Library', 'Page', 'Wishlist', 'Language', 'Queue');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allowedActions = array('view', 'index', 'ajax_view', 'ajax_view_pagination', 'callToAllFunctions');
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
                $albumArtwork = shell_exec('perl files/tokengen ' . $downloadData[0]['Files']['CdnPath'] . "/" . $downloadData[0]['Files']['SourceURL']);
                $sampleSongUrl = shell_exec('perl files/tokengen ' . $genre['Sample_Files']['CdnPath'] . "/" . $genre['Sample_Files']['SaveAsName']);
                $songUrl = shell_exec('perl files/tokengen ' . $genre['Full_Files']['CdnPath'] . "/" . $genre['Full_Files']['SaveAsName']);
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
       
        
       // $genreAll = Cache::read("genre" . $country,'GenreCache');      
       // if ($genreAll === false  && empty($genreAll)) {              
         //   $genreAll = $this->Common->getGenres($country);
       // }          
       
          
        //check the genre value         
        $genre = base64_decode($Genre);
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

}

?>