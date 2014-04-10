<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron
   Author: Maycreate
*/
App::import('Model', 'Searchrecord');
class TestsController extends AppController
{
    var $name = 'Tests';
    var $helpers = array( 'Html','Ajax','Javascript','Form', 'Library', 'Page', 'Wishlist','Song', 'Language');
    var $components = array('RequestHandler','ValidatePatron','Downloads','PasswordHelper','Email', 'SuggestionSong','Cookie');
    var $uses = array('User','Featuredartist','Artist','Library','Download','Genre','Currentpatron','Page','Wishlist','Album','Song','Language', 'Searchrecord');


    /*
     Function Name : beforeFilter
     Desc : actions that needed before other functions are getting called
    */
    function beforeFilter() {

    }

	function index() {

		$genre_id_count_array = $this->Searchrecord->find('all', array('conditions' => array('search_text' => 'a', 'type' =>'a')));
		print_r($genre_id_count_array);
	echo "here";

	exit;
		  if($_SERVER['SERVER_PORT'] == 443){
			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index');
		 }
		// Local Top Downloads functionality
		$libId = $this->Session->read('library');
		$patId = $this->Session->read('patron');
		$country = $this->Session->read('territory');
		$territory = $this->Session->read('territory');
		$nationalTopDownload = array();
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		$ids = '';
		//featured artist slideshow
		if (($artists = Cache::read("featured".$country)) === false) {
			$featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $this->Session->read('territory'),'Featuredartist.language' => Configure::read('App.LANGUAGE')), 'recursive' => -1));

			foreach($featured as $k => $v){
				 if($v['Featuredartist']['album'] != 0){
					$ids .= $v['Featuredartist']['album'].",";
				 }
			}
			if($ids != ''){
				$this->Album->recursive = 2;
				$featured =  $this->Album->find('all',array('conditions' =>
							array('and' =>
								array(
									array("Country.Territory" => $territory, "Album.ProdID IN (".rtrim($ids,",'").")" ,"Album.provider_type = Country.provider_type"),
								), "1 = 1 GROUP BY Album.ProdID"
							),

							'fields' => array(
								'Album.ProdID',
								'Album.Title',
								'Album.ArtistText',
								'Album.AlbumTitle',
								'Album.Artist',
								'Album.ArtistURL',
								'Album.Label',
								'Album.Copyright',
								'Album.provider_type'

								),
							'contain' => array(
								'Genre' => array(
									'fields' => array(
										'Genre.Genre'
										)
									),
								'Country' => array(
									'fields' => array(
										'Country.Territory'
										)
									),
								'Files' => array(
									'fields' => array(
										'Files.CdnPath' ,
										'Files.SaveAsName',
										'Files.SourceURL'
								),
							)
						), 'order' => array('Country.SalesDate' => 'desc')
					)
				);
			} else {
				$featured = array();
			}
			Cache::write("featured".$territory, $featured);
		}
		$featured = Cache::read("featured".$country);
		$this->set('featuredArtists', $featured);

		//used for gettting top downloads for Pop Genre
		if (($artists = Cache::read("pop".$country)) === false) {

          $restoregenre_query =  "
          SELECT
              COUNT(DISTINCT downloads.id) AS countProduct,
              Song.ProdID,
              Song.ReferenceID,
              Song.Title,
              Song.ArtistText,
              Song.DownloadStatus,
              Song.SongTitle,
              Song.Artist,
              Song.Advisory,
              Song.Sample_Duration,
              Song.FullLength_Duration,
              Song.provider_type,
              Song.Genre,
              Country.Territory,
              Country.SalesDate,
              Sample_Files.CdnPath,
              Sample_Files.SaveAsName,
              Full_Files.CdnPath,
              Full_Files.SaveAsName,
              Sample_Files.FileID,
              Full_Files.FileID
          FROM
              downloads,
              Songs AS Song
                  LEFT JOIN
              countries AS Country ON Country.ProdID = Song.ProdID
                  LEFT JOIN
              File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                  LEFT JOIN
              File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
          WHERE
              downloads.ProdID = Song.ProdID
              AND downloads.provider_type = Song.provider_type
              AND Song.Genre LIKE '%Pop%'
              AND Country.Territory LIKE '%".$country."%'
              AND Country.SalesDate != ''
              AND Country.SalesDate < NOW()
              AND Song.DownloadStatus = '1'
              AND created BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'
          GROUP BY downloads.ProdID
          ORDER BY countProduct DESC
          LIMIT 10
          ";

          $data =   $this->Album->query($restoregenre_query);
          if(!empty($data)){
            Cache::write("pop".$country, $data);
          }

		}
		$genre_pop = Cache::read("pop".$country);

		$this->set('genre_pop', $genre_pop);
    if(($ssartists = Cache::read('ssartists_'.$this->Session->read('territory').'_'.Configure::read('App.LANGUAGE'))) === false){
      $ssartists = $this->Artist->find('all',array('conditions'=>array('Artist.territory' => $this->Session->read('territory'), 'Artist.language'=> Configure::read('App.LANGUAGE')),'fields'=>array('Artist.artist_name','Artist.artist_image','Artist.territory','Artist.language'),'limit'=>6));
      Cache::write('ssartists_'.$this->Session->read('territory').'_'.Configure::read('App.LANGUAGE'),$ssartists);
    }
    $this->set('artists',$ssartists);
    $this->layout = 'home';
    }

	function get_genre_tab_content($tab_no , $genre){
		//Cachec results for Rock Genre

		$this -> layout = 'ajax';
		$libId = $this->Session->read('library');
		$patId = $this->Session->read('patron');
		$territory = $this->Session->read('territory');
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$this->set('libraryDownload',$libraryDownload);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('patronDownload',$patronDownload);
		$this->set('tab_no',$tab_no);

		if (($artists = Cache::read($genre.$territory)) === false) {
          $restoregenre_query =  "
          SELECT
              COUNT(DISTINCT downloads.id) AS countProduct,
              Song.ProdID,
              Song.ReferenceID,
              Song.Title,
              Song.ArtistText,
              Song.DownloadStatus,
              Song.SongTitle,
              Song.Artist,
              Song.Advisory,
              Song.Sample_Duration,
              Song.FullLength_Duration,
              Song.provider_type,
              Song.Genre,
              Country.Territory,
              Country.SalesDate,
              Sample_Files.CdnPath,
              Sample_Files.SaveAsName,
              Full_Files.CdnPath,
              Full_Files.SaveAsName,
              Sample_Files.FileID,
              Full_Files.FileID
          FROM
              downloads,
              Songs AS Song
                  LEFT JOIN
              countries AS Country ON Country.ProdID = Song.ProdID
                  LEFT JOIN
              File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                  LEFT JOIN
              File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
          WHERE
              downloads.ProdID = Song.ProdID
              AND downloads.provider_type = Song.provider_type
              AND Song.Genre LIKE '%".$genre."%'
              AND Country.Territory LIKE '%".$territory."%'
              AND Country.SalesDate != ''
              AND Country.SalesDate < NOW()
              AND Song.DownloadStatus = '1'
              AND created BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'
          GROUP BY downloads.ProdID
          ORDER BY countProduct DESC
          LIMIT 10
          ";

      $data =   $this->Album->query($restoregenre_query);
      if(!empty($data)){
          Cache::write($genre.$territory, $data);
      }


		}
		$genre_info = Cache::read($genre.$territory);

		$this->set('genre_info', $genre_info);
	}
	function my_lib_top_10()
	{
		$this -> layout = 'ajax';
		$libId = $this->Session->read('library');
		$patId = $this->Session->read('patron');
		$country = $this->Session->read('territory');
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('libraryDownload',$libraryDownload);
		$this->set('patronDownload',$patronDownload);
		if (($libDownload = Cache::read("lib".$libId)) === false) {
			$topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct'), 'order' => 'countProduct DESC', 'limit'=> '15'));
			$prodIds = '';

			foreach($topDownloaded as $k => $v){
				$prodIds .= $v['Download']['ProdID']."','";
			}

			if($prodIds != ''){
				$this->Song->recursive = 2;
				$topDownload =  $this->Song->find('all',array('conditions' =>
						array('and' =>
							array(
								array("Song.DownloadStatus" => 1,"Song.ProdID IN ('".rtrim($prodIds,",'")."')"  ,'Country.Territory' => $country,"Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type"),
							), "1 = 1 GROUP BY Song.ProdID"
						),
						'fields' => array(
							'Song.ProdID',
							'Song.ReferenceID',
							'Song.Title',
							'Song.ArtistText',
							'Song.DownloadStatus',
							'Song.SongTitle',
							'Song.Artist',
							'Song.Advisory',
							'Song.Sample_Duration',
							'Song.FullLength_Duration',
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
									'Country.SalesDate'
								)
							),
							'Sample_Files' => array(
								'fields' => array(
											'Sample_Files.CdnPath' ,
											'Sample_Files.SaveAsName'
									)
								),
							'Full_Files' => array(
								'fields' => array(
											'Full_Files.CdnPath' ,
											'Full_Files.SaveAsName'
									)
								),
						), 'order' => array('Country.SalesDate' => 'desc'),'limit'=> '10'
						)
				);
			} else {
				$topDownload = array();
			}
			Cache::write("lib".$libId, $topDownload);
		}
		$topDownload = Cache::read("lib".$libId);
		$this->set('songs',$topDownload);
	}


	function national_top_download()
	{
		$this -> layout = 'ajax';
		$libId = $this->Session->read('library');
		$territory = $this->Session->read('territory');
		$libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$this->set('libraryDownload',$libraryDownload);
		$patId = $this->Session->read('patron');
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
		$this->set('patronDownload',$patronDownload);
		// National Top Downloads functionality
		if (($national = Cache::read("national".$territory)) === false) {
		 $country = $territory;
		  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct FROM `downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 110";
		  $ids = '';
		  $natTopDownloaded = $this->Album->query($sql);
		  foreach($natTopDownloaded as $natTopSong){
			if(empty($ids)){
			  $ids .= $natTopSong['Download']['ProdID'];
			} else {
			  $ids .= ','.$natTopSong['Download']['ProdID'];
			}
		  }
		  $data = array();



		  $sql_national_100 =<<<STR
	SELECT
		Song.ProdID,
		Song.ReferenceID,
		Song.Title,
		Song.ArtistText,
		Song.DownloadStatus,
		Song.SongTitle,
		Song.Artist,
		Song.Advisory,
		Song.Sample_Duration,
		Song.FullLength_Duration,
		Song.provider_type,
		Genre.Genre,
		Country.Territory,
		Country.SalesDate,
		Sample_Files.CdnPath,
		Sample_Files.SaveAsName,
		Full_Files.CdnPath,
		Full_Files.SaveAsName,
		Sample_Files.FileID,
		Full_Files.FileID
	FROM
		Songs AS Song
			LEFT JOIN
		File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
			LEFT JOIN
		File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
			LEFT JOIN
		Genre AS Genre ON (Genre.ProdID = Song.ProdID)
			LEFT JOIN
		countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
	WHERE
		( (Song.DownloadStatus = '1') AND (Song.ProdID IN ($ids)) AND (Song.provider_type = Genre.provider_type) )  AND (Country.Territory = '$country')  AND Country.SalesDate != ''  AND Country.SalesDate < NOW() AND 1 = 1
	GROUP BY Song.ProdID
	ORDER BY FIELD(Song.ProdID,
			$ids) ASC
	LIMIT 100

STR;

			$nationalTopDownload = $this->Album->query($sql_national_100);
			// Checking for download status
			Cache::write("national".$territory, $nationalTopDownload);
		}

		$nationalTopDownload = Cache::read("national".$territory);

		$this->set('nationalTopDownload',$nationalTopDownload);
	}

    /*
     Function Name : autoComplete
     Desc : actions that is needed for auto-completeing the search
    */
    function autoComplete() {
		Configure::write('debug', 0);
		$country = $this->Session->read('territory');
		$searchKey = '';
		if(isset($_REQUEST['q']) && $_REQUEST['q'] != '') {
			$searchKey = $_REQUEST['q'];
		}
		$searchText = $searchKey;
		$this->set('searchKey','search='.urlencode($searchText));
		$searchKey = str_replace("^", " ", $searchKey);
		$searchKey = str_replace("$", " ", $searchKey);
		$searchKey = '"^'.addslashes($searchKey).'"';
		App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
		if($_REQUEST['type'] == 'album'){
			$searchParam = "@Title ".$searchKey;
		}
		else if($_REQUEST['type'] == 'artist'){
			$searchParam = "@ArtistText ".$searchKey;
		}
		else if($_REQUEST['type'] == 'composer'){
			$searchParam = "@composer ".$searchKey;
		}
		else{
			$searchParam = "@SongTitle ".$searchKey;
		}
		$sphinxFinalCondition = $searchParam." & "."@Territory '".$country."' & @DownloadStatus 1";

		$condSphinx = '';
		$sphinxSort = "";
		$sphinxDirection = "";
		$this->paginate = array('Song' => array(
						'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'extra' => 1
					));

		$searchResults = $this->paginate('Song');

		$this->set('output', $searchResults);
		$this->set('type', $_REQUEST['type']);
		$this->layout = 'ajax';
    }



    /*
     Function Name : artistSearch
     Desc : actions that is needed for auto-completeing the search
    */
    function artistSearch(){
		$country = $this->Session->read('territory');
		$this->Song->recursive = 2;
		$search = $_POST['search'];
		if($search == 'special'){
			$cond = array("ArtistText REGEXP '^[^A-Za-z]'");
		}else{
			$cond = array('ArtistText LIKE' => $search.'%');
		}
		if (($artist = Cache::read("artist".$search.$country)) === false) {
			$artistAll = $this->Song->find('all',array(
								'conditions' =>
									array('and' =>
										array(
											$cond,
											array('Country.Territory' => $country),
											array('Song.provider_type = Country.provider_type'),
											array('DownloadStatus' => 1),
											array("Song.Sample_FileID != ''")
										)
									),
								'fields' => array(
										'Song.ArtistText','Song.DownloadStatus',
										),
								'contain' => array(
										'Country' => array(
												'fields' => array(
													'Country.Territory'
												)
											),
									),
								'order' => 'Song.ArtistText',
								'group' => 'Song.ArtistText'
							));
				Cache::write("artist".$search.$country, $artistAll);
		}
		$artistAll = Cache::read("artist".$search.$country);
		$this->set('distinctArtists', $artistAll);
		$this->layout = 'ajax';
    }

   /*
     Function Name : search
     Desc : actions that is needed for advanced search
    */
    function search(){
		$country = $this->Session->read('territory');
		if ($country == 'US') {
			$nonMatchCountry = 'CA';
			$countryVal = 1;
		} else {
			$nonMatchCountry = 'US';
			$countryVal = 2;
		}
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        if($this->Session->read('block') == 'yes') {
            $cond = array('Song.Advisory' => 'F');
			$condSphinx = "@Advisory F";
        }
        else {
            $cond = "";
			$condSphinx = "";
        }
		if((isset($_REQUEST['artist']) && $_REQUEST['artist']!= '') || (isset($_REQUEST['label']) && $_REQUEST['label']!= '') || (isset($_REQUEST['composer']) && $_REQUEST['composer'] != '') || (isset($_REQUEST['song']) && $_REQUEST['song'] != '') || (isset($_REQUEST['album']) && $_REQUEST['album'] != '') || (isset($_REQUEST['genre_id']) &&  $_REQUEST['genre_id'] != '') || (isset($this->data['Test']['artist']) && $this->data['Test']['artist']!= '') || (isset($this->data['Test']['label']) && $this->data['Test']['label']!= '') || (isset($this->data['Test']['composer']) && $this->data['Test']['composer'] != '') || (isset($this->data['Test']['song']) && $this->data['Test']['song'] != '') || (isset($this->data['Test']['album']) && $this->data['Test']['album'] != '') || (isset($this->data['Test']['genre_id']) &&  $this->data['Test']['genre_id'] != '' || isset($_REQUEST['search']) && $_REQUEST['search'] != '')){
      if((isset($_REQUEST['match']) && $_REQUEST['match'] != '') || (isset($this->data['Test']['Match']) && $this->data['Test']['Match'] != '')) {
				if(isset($_REQUEST['match']) && $_REQUEST['match'] != '') {
					 if($_REQUEST['match'] == 'All') {
						$condition = "and";
						$preCondition1 = array('Song.DownloadStatus' => 1);
						$preCondition2 = array('Song.TrackBundleCount' => 0);
						$preCondition3 = array('Country.Territory' => $country);
						$sphinxCheckCondition = "&";
						$matchType = "All";

					}
					 else {
						$condition = "or";
						$preCondition1 =  "";
						$preCondition2 = "";
						$preCondition3 = "";
						$sphinxCheckCondition = "|";
						$matchType = "Any";
					}
					$artist =  $_REQUEST['artist'];
          $label =  $_REQUEST['label'];
					$composer =  $_REQUEST['composer'];
					$song =  $_REQUEST['song'];
					$album =  $_REQUEST['album'];
					$genre =  $_REQUEST['genre_id'];
				}
				if(isset($this->data['Test']['Match']) && $this->data['Test']['Match'] != '') {
					if($this->data['Test']['Match'] == 'All') {
						$condition = "and";
						$preCondition1 = array('Song.DownloadStatus' => 1);
						$preCondition2 = array('Song.TrackBundleCount' => 0);
						$preCondition3 = array('Country.Territory' => $country);
						$sphinxCheckCondition = "&";
						$matchType = "All";
					}
					else {
						$condition = "or";
						$preCondition1 =  "";
						$preCondition2 = "";
						$preCondition3 = "";
						$sphinxCheckCondition = "|";
						$matchType = "Any";
					}
					$artist =  $this->data['Test']['artist'];
          $label =  $this->data['Test']['label'];
					$composer = $this->data['Test']['composer'];
					$song =  $this->data['Test']['song'];
					$album =  $this->data['Test']['album'];
					$genre =  $this->data['Test']['genre_id'];

					$artist = str_replace("^", " ", $artist);
          $label = str_replace("^", " ", $label);
					$composer = str_replace("^", " ", $composer);
					$song = str_replace("^", " ", $song);
					$album = str_replace("^", " ", $album);

					$artist = str_replace("$", " ", $artist);
          $label = str_replace("$", " ", $label);
					$composer = str_replace("$", " ", $composer);
					$song = str_replace("$", " ", $song);
					$album = str_replace("$", " ", $album);
				}
				if($artist != '') {
					$artistSearch = array('match(Song.ArtistText) against ("+'.$artist.'*" in boolean mode)');
					$sphinxArtistSearch = '@ArtistText "'.addslashes($artist).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$artistSearch = '';
					$sphinxArtistSearch = '';
				}
        if($label != '') {
					$labelSearch = array('match(Album.Label) against ("+'.$label.'*" in boolean mode)');
					$sphinxLabelSearch = '@LabelText "'.addslashes($label).'" '.$sphinxCheckCondition.' ';
				}
        else {
					$labelSearch = "";
					$sphinxLabelSearch = "";
				}
				if($composer != '') {
					$composerSearch = array('match(Song.Composer) against ("+'.$composer.'*" in boolean mode)');
					$this->set('composer', $composer);
					$preCondition4 = array('Participant.Role' => 'Composer');
					$sphinxComposerSearch = '@Composer "'.addslashes($composer).'" '.$sphinxCheckCondition.' ';
					$role = '2';
				}
				else {
					$composerSearch = '';
					$preCondition4 = "";
					$sphinxComposerSearch = '';
					$role = '';
				}
				if($song != '') {
					$songSearch = array('match(Song.SongTitle) against ("+'.$song.'*" in boolean mode)');
					$sphinxSongSearch = '@SongTitle "'.addslashes($song).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$songSearch = '';
					$sphinxSongSearch = '';
				}
				if($album != '') {
					$albumSearch = array('match(Song.Title) against ("+'.$album.'*" in boolean mode)');
					$sphinxAlbumSearch = '@Title "'.addslashes($album).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$albumSearch = '';
					$sphinxAlbumSearch = '';
				}
				if($genre != '') {
					$genreSearch = array('match(Song.Genre) against ("+'.$genre.'*" in boolean mode)');
					$sphinxGenreSearch = '@Genre "'.addslashes($genre).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$genreSearch = '';
					$sphinxGenreSearch = '';
				}
				if($country != '') {
					$territorySearch = array('match(Song.Territory) against ("+'.$country.'*" in boolean mode)');
					$sphinxTerritorySearch = '@Territory "'.addslashes($country).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$territorySearch = '';
					$sphinxTerritorySearch = '';
				}

				$sphinxTempCondition = $sphinxArtistSearch.''.$sphinxLabelSearch.''.$sphinxComposerSearch.''.$sphinxSongSearch.''.$sphinxAlbumSearch.''.$sphinxGenreSearch;
        if($sphinxTerritorySearch != ''){
          $sphinxTempCondition = substr($sphinxTempCondition, 0, -2);
          $sphinxTempCondition = $sphinxTempCondition.' & '. $sphinxTerritorySearch;
        }

				$sphinxFinalCondition = substr($sphinxTempCondition, 0, -2);
				$sphinxFinalCondition = $sphinxFinalCondition.' & @DownloadStatus 1 & '.$condSphinx;
				if ($condSphinx == "") {
					$sphinxFinalCondition = substr($sphinxFinalCondition, 0, -2);
				}

				App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));

				$this->set('searchKey','match='.$matchType.'&artist='.urlencode($artist).'&label='.urlencode($label).'&composer='.urlencode($composer).'&song='.urlencode($song).'&album='.urlencode($album).'&genre_id='.urlencode($genre));
				if (isset($this->passedArgs['sort'])){
					$sphinxSort = $this->passedArgs['sort'];
				} else {
					$sphinxSort = "";
				}
				if (isset($this->passedArgs['direction'])){
					$sphinxDirection = $this->passedArgs['direction'];
				} else {
					$sphinxDirection = "";
				}

				$this->paginate = array('Song' => array(
							'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'cont' => $country
						));

				$searchResults = $this->paginate('Song');
				$this->Download->recursive = -1;
				foreach($searchResults as $key => $value){
						$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $value['Song']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
						if(count($downloadsUsed) > 0){
							$searchResults[$key]['Song']['status'] = 'avail';
						} else{
							$searchResults[$key]['Song']['status'] = 'not';
						}

						//Changed for show seached like composer name in composer search
						if($composer != ''){
							$composer_value = $searchResults[$key]['Song']['Composer'];
							$composer_value = str_replace('"', "", $composer_value);
							$composer_array = explode(",", $composer_value);
							$search_text = $composer;
							$coposer_text = '';
							if(is_array($composer_array )){
								foreach($composer_array as $composer_key=>$composer_value){
									$pos = stripos($composer_value, $search_text);
									if(is_numeric($pos)){
										$coposer_text = $composer_value;
										break;
									}
								}

								if('' != $coposer_text){
									$searchResults[$key]['Participant']['Name'] = $coposer_text;
								}
							}

						}


				}

				$this->set('searchResults', $searchResults);

				//Added code for log search data
				if(isset($this->data['Test']['artist']) && $this->data['Test']['artist']!= ''){
					$insertArr[] = $this->searchrecords('artist', $this->data['Test']['artist']);
				}
				if(isset($this->data['Test']['label']) && $this->data['Test']['label']!= ''){
					$insertArr[] = $this->searchrecords('label', $this->data['Test']['label']);
				}
				if(isset($this->data['Test']['composer']) && $this->data['Test']['composer']!= ''){
					$insertArr[] = $this->searchrecords('composer', $this->data['Test']['composer']);
				}
				if(isset($this->data['Test']['song']) && $this->data['Test']['song']!= ''){
					$insertArr[] = $this->searchrecords('song', $this->data['Test']['song']);
				}
				if(isset($this->data['Test']['album']) && $this->data['Test']['album']!= ''){
					$insertArr[] = $this->searchrecords('album', $this->data['Test']['album']);
				}
				if(isset($this->data['Test']['genre_id']) && $this->data['Test']['genre_id']!= ''){
					$insertArr[] = $this->searchrecords('genre_id', $this->data['Test']['genre_id']);
				}

				if(is_array($insertArr)){
					$this->Searchrecord->saveAll($insertArr);
				}

				//End Added code for log search data


			}
			else {

				//Added code for log search data

				if(isset($_REQUEST['search']) && $_REQUEST['search']!= ''){
					$insertArr[] = $this->searchrecords($_REQUEST['search_type'], $_REQUEST['search']);
				}
				$this->Searchrecord->saveAll($insertArr);

				//End Added code for log search data

				if($_REQUEST['search_type'] == 'composer'){
					$this->set('composer', "composer");
				}

				$searchKey = '';
				$auto = 0;
				if(isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
					$searchKey = $_REQUEST['search'];
				}
				if(isset($_REQUEST['auto']) && $_REQUEST['auto'] == 1) {
					$auto = 1;
				}
				if($searchKey == '') {
					$searchKey = $this->data['Test']['search'];
				}
				$searchText = $searchKey;
				$this->set('searchKey','search='.urlencode($searchText).'&auto='.$auto);

				if($_REQUEST['search_type'] == 'composer'){
					$searchtype = 'composer';
				}else if($_REQUEST['search_type'] == 'artist'){
					$searchtype = 'ArtistText';
				}else if($_REQUEST['search_type'] == 'album'){
					$searchtype = 'Title';
				}else if($_REQUEST['search_type'] == 'song'){
					$searchtype = 'SongTitle';
				}
				$this->set('searchtype', $_REQUEST['search_type']);
				if ($auto == 0) {
					$searchParam = "";
					$expSearchKeys = explode(" ", $searchKey);
					foreach ($expSearchKeys as $value) {

						$value = str_replace("^", " ", $value);
						$value = str_replace("$", " ", $value);
						$value = '"'.addslashes($value).'"';
						if ($searchParam == "") {
							$searchParam = "@".$searchtype." ".$value;
						} else {
							$searchParam = $searchParam." | @".$searchtype." ".$value;
						}
					}
				} else {
					$searchKey = str_replace("^", " ", $searchKey);
					$searchKey = str_replace("$", " ", $searchKey);
					$searchKey = '"'.addslashes($searchKey).'"';
					$searchParam = "@".$searchtype." ".$searchKey;
				}

				if(!isset($_REQUEST['composer'])) {
					$this->Song->unbindModel(array('hasOne' => array('Participant')));
				}
				App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));

				$sphinxFinalCondition = $searchParam." & "."@Territory ".$country." & @DownloadStatus 1 & ".$condSphinx;
				if ($condSphinx == "") {
					$sphinxFinalCondition = substr($sphinxFinalCondition, 0, -2);
				}

				if (isset($this->passedArgs['sort'])){
					$sphinxSort = $this->passedArgs['sort'];
				} else {
					$sphinxSort = "";
				}
				if (isset($this->passedArgs['direction'])){
					$sphinxDirection = $this->passedArgs['direction'];
				} else {
					$sphinxDirection = "";
				}


				$this->paginate = array('Song' => array(
								'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'cont' => $country
							));

				$searchResults = $this->paginate('Song');

				$this->Download->recursive = -1;
				foreach($searchResults as $key => $value){
						$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $value['Song']['ProdID'],'library_id' => $libId,'patron_id' => $patId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'limit' => '1'));
						if(count($downloadsUsed) > 0){
							$searchResults[$key]['Song']['status'] = 'avail';
						} else{
							$searchResults[$key]['Song']['status'] = 'not';
						}


						//Changed for show seached like composer name in composer search
						if($_REQUEST['search_type'] = 'composer'){
							$composer_value = $searchResults[$key]['Song']['Composer'];
							$composer_value = str_replace('"', "", $composer_value);
							$composer_array = explode(",", $composer_value);
							$search_text = $_REQUEST['search'];
							$coposer_text = '';
							if(is_array($composer_array )){
								foreach($composer_array as $composer_key=>$composer_value){
									$pos = stripos($composer_value, $search_text);
									if(is_numeric($pos)){
										$coposer_text = $composer_value;
										break;
									}
								}

								if('' != $coposer_text){
									$searchResults[$key]['Participant']['Name'] = $coposer_text;
								}
							}

						}


				}
				$this->set('searchResults', $searchResults);
			}
		} else {
			$this->set('searchResults', array());
		}
        $this->layout = 'home';
    }

	function searchrecords($type, $search_text){
		$search_text = strtolower(trim($search_text));
		$search_text  = preg_replace('/\s\s+/', ' ', $search_text);
		$insertArr['search_text'] = $search_text;
		$insertArr['type'] = $type;
		$genre_id_count_array = $this->Searchrecord->find('all', array('conditions' => array('search_text' => $search_text, 'type' => $type)));
		if(count($genre_id_count_array) > 0){
			$insertArr['count'] =$genre_id_count_array[0]['Searchrecord']['count'] + 1;
			$insertArr['id'] =$genre_id_count_array[0]['Searchrecord']['id'];
		}
		else{
			$insertArr['count'] = 1;
		}

		return $insertArr;
	}

    /*
     Function Name : userDownload
     Desc : actions that is used for updating user download
    */
    function userDownload() {
        Configure::write('debug', 0);
        $this->layout = false;
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        $prodId = $_POST['ProdID'];
		if($prodId == '' || $prodId == 0){
			$this->redirect(array('controller' => 'homes', 'action' => 'index'));
		}
		$downloadsDetail = array();

		$provider = $_POST['ProviderType'];
        $trackDetails = $this->Song->getdownloaddata($prodId , $provider );
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;
        $insertArr['artist'] = addslashes($trackDetails['0']['Song']['Artist']);
        $insertArr['track_title'] = addslashes($trackDetails['0']['Song']['SongTitle']);

		if($provider != 'sony'){
			$provider = 'ioda';
		}
		$insertArr['provider_type'] = $provider;

        $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
		$songUrl = shell_exec('perl files/tokengen ' . $trackDetails['0']['Full_Files']['CdnPath']."/".$trackDetails['0']['Full_Files']['SaveAsName']);
		$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
        if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'referral_url';
        }
        elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative';
		}
		elseif($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'mdlogin_reference';
		}
		elseif($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'mndlogin_reference';
		}
		elseif($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var';
		}
		elseif($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_name';
		}
		elseif($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https_name';
		}
		elseif($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https';
		}
		elseif($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https_wo_pin';
		}
        elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_https';
		}
		elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_wo_pin';
		}
		elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2';
		}
		elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'sip';
        }
		elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_wo_pin';
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var';
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';
		}
		elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';
		}
		elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'ezproxy';
		}
		elseif($this->Session->read('soap') && ($this->Session->read('soap') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'soap';
		}
		elseif($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'curl_method';
		}
        else{
			$insertArr['email'] = $this->Session->read('patronEmail');
			$insertArr['user_login_type'] = 'user_account';
         }
		$insertArr['user_agent'] = str_replace(";","",$_SERVER['HTTP_USER_AGENT']);
		$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->Library->setDataSource('master');
		$sql = "CALL sonyproc_ioda('".$libId."','".$patId."', '".$prodId."', '".$trackDetails['0']['Song']['ProductID']."', '".$trackDetails['0']['Song']['ISRC']."', '".addslashes($trackDetails['0']['Song']['Artist'])."', '".addslashes($trackDetails['0']['Song']['SongTitle'])."', '".$insertArr['user_login_type']."', '" .$insertArr['provider_type']."', '".$insertArr['email']."', '".addslashes($insertArr['user_agent'])."', '".$insertArr['ip']."', '".Configure::read('App.curWeekStartDate')."', '".Configure::read('App.curWeekEndDate')."',@ret)";
		$this->Library->query($sql);
		$sql = "SELECT @ret";
		$data = $this->Library->query($sql);
		$return = $data[0][0]['@ret'];
		$this->Library->setDataSource('default');
		if(is_numeric($return)){
			header("Location: ".$finalSongUrl);
			exit;
		}
		else{
			if($return == 'incld'){
				$this->Session->setFlash("You have already downloaded this song. Get it from your recent downloads.");
				$this->redirect(array('controller' => 'homes', 'action' => 'my_history'));
			}
			else{
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			}
		}
    }
    /*
     Function Name : advance_search
     Desc : actions used for showing advanced search form
    */
	function advance_search() {
        $this->layout = 'home';
		$country = $this->Session->read('territory');
		$this->Genre->Behaviors->attach('Containable');
		$this->Genre->recursive = 2;
		$this->Song->recursive = 2;
		if (($genre = Cache::read("genre".$country)) === false) {
			$results = $this->Song->find('all', array(
									'conditions'=>array(
										'Song.DownloadStatus' => 1,
										'Song.TrackBundleCount' => 0,
										'Country.Territory' => $country),
									'fields' => array('ProdID'),
									'contain' => array(
									'Country' => array(
										'fields' => array(
											'Country.Territory'
											)
										)),
									'group' => array('Genre')));
			$data='';
			foreach($results as $k => $v){
				$data .= $v['Song']['ProdID'].',';
			}
			$genreAll = $this->Genre->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $country),
									array('Genre.ProdID IN ('.rtrim($data,',').')')
								)
							),
						'fields' => array(
								'Genre.Genre'
								),
						'contain' => array(
							'Country' => array(
									'fields' => array(
											'Country.Territory'
										)
									),
						),'group' => 'Genre.Genre'
					));
			Cache::write("genre".$country, $genreAll);
		}
		$genreAll = Cache::read("genre".$country);
		$resultArr = array();
        foreach($genreAll as $genre) {
            $resultArr[$genre['Genre']['Genre']] = $genre['Genre']['Genre'];
        }
        $this->set('genres',$resultArr);
    }

    /*
     Function Name : checkPatron
     Desc : actions used for validating patron access
    */
    function checkPatron() {
		Configure::write('debug', 0);
		$this->layout = false;
		$libid = $_REQUEST['libid'];
		$patronid = $_REQUEST['patronid'];
		$patronid = str_replace("_","+",$_REQUEST['patronid']);
		$userCache = Cache::read("login_".$this->Session->read('territory')."_".$libid."_".$patronid);
		$date = time();
		$modifiedTime = $userCache[0];
		//checking form db if session exists
		$sql = mysql_query("SELECT id FROM `sessions` Where id='".session_id()."'");
		$count = mysql_num_rows($sql);
		$values = array(0 => $date, 1 => session_id());

			$date = time();
			$name = $_SERVER['SERVER_ADDR'];
			$values = array(0 => $date, 1 => session_id());
			//writing to memcache and writing to both the memcached servers
			Cache::write("login_".$this->Session->read('territory')."_".$libid."_".$patronid, $values);
			echo "success".$name;
			exit;
    }

    /*
     Function Name : approvePatron
     Desc : actions used for approve terms access
    */
    function approvePatron() {
		Configure::write('debug', 0);
		$this->layout = false;
		$libid = $_REQUEST['libid'];
		$patronid = $_REQUEST['patronid'];
		$patronid = str_replace("_","+",$_REQUEST['patronid']);
		$currentPatron = $this->Currentpatron->find('all',array('conditions' => array('libid' => $libid,'patronid' => $patronid)));
		if(count($currentPatron) > 0){
			$updateArr = array();
			$updateArr['id'] = $currentPatron[0]['Currentpatron']['id'];
			if($this->Session->read('consortium') && $this->Session->read('consortium') != ''){
				$updateArr['consortium'] = $this->Session->read('consortium');
			}
			$updateArr['is_approved'] = 'yes';
			$this->Currentpatron->save($updateArr);
			$this->Session->write('approved', 'yes');
		}
		echo "Success";
		exit;
    }

    /*
     Function Name : admin_aboutusform
     Desc : actions used for admin about us form
    */
    function admin_aboutusform() {
	if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
	    $language = $this->data['Test']['language'];
	    $this -> set( 'formAction', 'admin_aboutusform');
	    $this -> set( 'formHeader', 'Manage About Us Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => $this->data['Test']['language'])));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $language;
		$this->set('getData', $getData );
	    }
	    else {
		$getData['Test']['language'] = $language;
		$getData['Test']['id'] = null;
		$getData['Test']['page_name'] = null;
		$getData['Test']['page_content'] = null;
		$this->set('getData', $getData);
	    }
	}
	else{
	    if(isset($this->data)) {
		$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => $this->data['Test']['language'])));
		if(count($findData) == 0) {
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()){
		      $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		}
		elseif(count($findData) > 0){
		    $this->Page->id = $this->data['Test']['id'];
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()) {
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		    else {
			$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		    }
		}
	    }
	    $this->set('formAction', 'admin_aboutusform');
	    $this->set('formHeader', 'Manage About Us Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'aboutus', 'language' => 'en')));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $getPageData[0]['Page']['language'];
		$this -> set( 'getData', $getData );
	    }
	    else {
		$arr = array();
		$this->set('getData',$arr);
	    }
	}
	$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
	$this->layout = 'admin';
    }

    /*
     Function Name : admin_termsform
     Desc : actions used for admin terms form
    */
    function admin_termsform() {
	if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
	    $language = $this->data['Test']['language'];
	    $this -> set( 'formAction', 'admin_termsform');
	    $this -> set( 'formHeader', 'Manage Terms & Condition Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => $this->data['Test']['language'])));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $language;
		$this->set('getData', $getData );
	    }
	    else {
		$getData['Test']['language'] = $language;
		$getData['Test']['id'] = null;
		$getData['Test']['page_name'] = null;
		$getData['Test']['page_content'] = null;
		$this->set('getData', $getData);
	    }
	}
	else{
	    if(isset($this->data)) {
		$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => $this->data['Test']['language'])));
		if(count($findData) == 0) {
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()){
		      $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		}
		elseif(count($findData) > 0){
		    $this->Page->id = $this->data['Test']['id'];
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()) {
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		    else {
			$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		    }
		}
	    }
	    $this->set('formAction', 'admin_termsform');
	    $this->set('formHeader', 'Manage Terms & Condition Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'terms', 'language' => 'en')));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $getPageData[0]['Page']['language'];
		$this->set('getData', $getData );
	    }
	    else {
		$arr = array();
		$this->set('getData',$arr);
	    }
	}
	$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
	$this->layout = 'admin';
    }

	/*
     Function Name : admin_loginform
     Desc : actions used for admin login form
    */
    function admin_loginform() {
		if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
			$language = $this->data['Test']['language'];
			$this -> set( 'formAction', 'admin_loginform');
			$this -> set( 'formHeader', 'Manage Login Page Text' );
			$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $this->data['Test']['language'])));
			if(count($getPageData) != 0) {
			$getData['Test']['id'] = $getPageData[0]['Page']['id'];
			$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
			$getData['Test']['language'] = $language;
			$this->set('getData', $getData );
			}
			else {
			$getData['Test']['language'] = $language;
			$getData['Test']['id'] = null;
			$getData['Test']['page_name'] = null;
			$getData['Test']['page_content'] = null;
			$this->set('getData', $getData);
			}
		}
		else{
			if(isset($this->data)) {
			$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $this->data['Test']['language'])));
			if(count($findData) == 0) {
				$pageData['Page']['page_name'] = $this->data['Test']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Test']['page_content'];
				$pageData['Page']['language'] = $this->data['Test']['language'];
				$this->Page->set($pageData['Page']);
				if($this->Page->save()){
				  $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
			}
			elseif(count($findData) > 0){
				$this->Page->id = $this->data['Test']['id'];
				$pageData['Page']['page_name'] = $this->data['Test']['page_name'];
				$pageData['Page']['page_content'] = $this->data['Test']['page_content'];
				$pageData['Page']['language'] = $this->data['Test']['language'];
				$this->Page->set($pageData['Page']);
				if($this->Page->save()) {
				$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
				}
				else {
				$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
				}
			}
			}
			$this->set('formAction', 'admin_loginform');
			$this->set('formHeader', 'Manage Login Page Text' );
			$getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => 'en')));
			if(count($getPageData) != 0) {
			$getData['Test']['id'] = $getPageData[0]['Page']['id'];
			$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
			$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
			$getData['Test']['language'] = $getPageData[0]['Page']['language'];
			$this->set('getData', $getData );
			}
			else {
			$arr = array();
			$this->set('getData',$arr);
			}
		}
		$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
		$this->layout = 'admin';
	}

	/*
     Function Name : admin_wishlistform
     Desc : actions used for admin wishlist form
    */
    function admin_wishlistform() {
	if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
	    $language = $this->data['Test']['language'];
	    $this -> set( 'formAction', 'admin_wishlistform');
	    $this -> set( 'formHeader', 'Manage Wishlist Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => $this->data['Test']['language'])));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $language;
		$this->set('getData', $getData );
	    }
	    else {
		$getData['Test']['language'] = $language;
		$getData['Test']['id'] = null;
		$getData['Test']['page_name'] = null;
		$getData['Test']['page_content'] = null;
		$this->set('getData', $getData);
	    }
	}
	else{
	    if(isset($this->data)) {
		$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => $this->data['Test']['language'])));
		if(count($findData) == 0) {
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()){
		      $this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		}
		elseif(count($findData) > 0){
		    $this->Page->id = $this->data['Test']['id'];
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()) {
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		    else {
			$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		    }
		}
	    }
	    $this -> set( 'formAction', 'admin_wishlistform');
	    $this -> set( 'formHeader', 'Manage Wishlist Page Text' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'wishlist', 'language' => 'en')));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $getPageData[0]['Page']['language'];
		$this -> set( 'getData', $getData );
	    }
	    else {
		$arr = array();
		$this->set('getData',$arr);
	    }
	}
	$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
	$this->layout = 'admin';
    }


    /*
     Function Name : admin_limitsform
     Desc : actions used for admin limits form
    */
    function admin_limitsform(){
	if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
	    $language = $this->data['Test']['language'];
	    $this -> set( 'formAction', 'admin_limitsform');
	    $this -> set( 'formHeader', 'Manage Download Limits Page Content' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => $this->data['Test']['language'])));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $language;
		$this->set('getData', $getData );
	    }
	    else {
		$getData['Test']['language'] = $language;
		$getData['Test']['id'] = null;
		$getData['Test']['page_name'] = null;
		$getData['Test']['page_content'] = null;
		$this->set('getData', $getData);
	    }
	}
	else{
	    if(isset($this->data)) {
		$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => $this->data['Test']['language'])));
		if(count($findData) == 0) {
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()){
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		}
		elseif(count($findData) > 0){
		    $this->Page->id = $this->data['Test']['id'];
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()) {
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		    else {
			$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		    }
		}
	    }
	    $this -> set( 'formAction', 'admin_limitsform');
	    $this -> set( 'formHeader', 'Manage Download Limits Page Text' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'limits', 'language' => 'en')));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $getPageData[0]['Page']['language'];
		$this -> set( 'getData', $getData );
	    }
	    else {
		$arr = array();
		$this->set('getData',$arr);
	    }
	}
	$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));

	$this->layout = 'admin';
    }

    /*
     Function Name : aboutus
     Desc : actions used for User end checking for cookie and javascript enable
    */
    function aboutus() {
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
			if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')) {
				$url = $this->Session->read('referral_url');
			}
			elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')) {
				$url = $this->webroot.'users/ilogin';
			}
			elseif($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')) {
				$url = $this->webroot.'users/mdlogin';
			}
			elseif($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')) {
				$url = $this->webroot.'users/mndlogin';
			}
			elseif($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')) {
				$url = $this->webroot.'users/idlogin';
			}
			elseif($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')) {
				$url = $this->webroot.'users/ildlogin';
			}
			elseif($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')) {
				$url = $this->webroot.'users/ilhdlogin';
			}
			elseif($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')) {
				$url = $this->webroot.'users/ihdlogin';
			}
			elseif($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')) {
				$url = $this->webroot.'users/inhdlogin';
			}
			elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){
				$url = $this->webroot.'users/inhlogin';
			}
			elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')) {
				$url = $this->webroot.'users/inlogin';
			}
			elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')) {
				$url = $this->webroot.'users/indlogin';
			}
			elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){
				$url = $this->webroot.'users/slogin';
			}
			elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){
				$url = $this->webroot.'users/snlogin';
			}
			elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
				$url = $this->webroot.'users/sdlogin';
			}
			elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){
				$url = $this->webroot.'users/sndlogin';
			}
			elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
				$url = $this->webroot.'users/sso';
			}
			elseif($this->Session->read('soap') && ($this->Session->read('soap') != '')){
				$url = $this->webroot.'users/plogin';
			}
			elseif($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')){
				$url = $this->webroot.'users/clogin';
			}
			else {
			   $url = $this->webroot.'users/login';
			}
			$patronId = $this->Session->read('patron');
			$libraryId = $this->Session->read('library');
			$patronDetails = $this->Currentpatron->find('all',array('conditions' => array('patronid' => $patronId,'libid' => $libraryId)));
			if(count($patronDetails) > 0) {
			$updateTime = date( "Y-m-d H:i:s", time()-60 );
			$this->Currentpatron->id = $patronDetails[0]['Currentpatron']['id'];
			$this->Currentpatron->saveField('modified',$updateTime, false);
			}
			$this->Session->destroy();
			$this -> Session -> setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='".$url."'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
		}
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
			$this->Session->destroy();
			$this -> Session -> setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
		}
		if($this->Session->read('lib_status') == 'invalid')
		{
			$this ->Session->setFlash("The library you are trying to access is not registered with us");
			$this->Session->delete('lib_status');
		}
		if($this->Cookie->read('msg') != '')
		{
			$this ->Session->setFlash("This account is already active");
			$this->Cookie->delete('msg');
		}

		$this->layout = 'home';
    }

/*
 Function Name : aboutus
 Desc : actions used for Admin end checking for cookie and javascript enable
*/
    function admin_aboutus() {
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "js_err") {
			$url = $this->webroot.'admin/users/login';
			$this->Session->destroy();
			$this -> Session -> setFlash("Javascript is required to use this website. For the best experience, please enable javascript and <a href='".$url."'>Click Here</a> to try again. <a href='https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654' target='_blank'>Click Here</a> for the steps to enable javascript in different type of browsers.");
		}
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] == "cookie_err") {
			$this->Session->destroy();
			$this -> Session -> setFlash("Cookies must be enabled to use this site. <a href='http://www.google.com/support/accounts/bin/answer.py?&answer=61416' target='_blank'>Click Here</a> for the steps to enable cookies in the different browser types.");
		}
		$this->layout = 'admin';
    }

    /*
     Function Name : terms
     Desc : actions used for terms page
    */
    function terms(){
		$this->layout = 'home';
    }

    /*
     Function Name : limits
     Desc : actions used for limits page
    */
    function limits() {
        $this->layout = 'home';
    }

    /*
     Function Name : check_email
     Desc : check for a valid email
    */
    function check_email($email) {
        $email_regexp = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
        return eregi($email_regexp, $email);
    }

    /*
     Function Name : _sendForgotPasswordMail
     Desc : email function for forgot password
    */
    function _sendForgotPasswordMail($id, $password) {
        Configure::write('debug', 0);
        $this->Email->template = 'email/forgotPasswordEmail';
        $this->User->recursive = -1;
        $Patron = $this->User->read(null,$id);
        $this->set('Patron', $Patron);
        $this->set('password', $password);
        $this->Email->to = $Patron['User']['email'];
		$this->Email->from = Configure::read('App.adminEmail');
        $this->Email->fromName = Configure::read('App.fromName');
        $this->Email->subject = 'FreegalMusic - New Password information';
        $this->Email->smtpHostNames = Configure::read('App.SMTP');
		$this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
		$this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
		$this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
        $result = $this->Email->send();
    }

    /*
     Function Name : forgot_password
     Desc : To send mail to patrons with new password
    */
    function forgot_password() {
		if($this->Session->read('layout_option') == 'login_new'){
			$this->layout = 'login_new';
		}
		else{
        $this->layout = 'login';
		}
        $errorMsg ='';
		if(isset($_POST['lang'])){
			$language = $_POST['lang'];
			$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
			$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		}
		else{
			$this->Session->write('Config.language', 'en');
		}
        if($this->data){
            $email = $this->data['Test']['email'];
            if($email == ''){
                $errorMsg = "Please provide your email address.";
            }
            elseif(!($this->check_email($email))){
                $errorMsg = "This is not a valid email.";
            }
            else{
                $email_exists = $this->User->find('all',array('conditions' => array('email' => $email, 'type_id' => '5')));
                if(count($email_exists) == 0){
                    $errorMsg = "This is not a valid patron email.";
                }
            }
            if($errorMsg != ''){
                $this->Session->setFlash($errorMsg);
                $this->redirect($this->webroot.'homes/forgot_password');
            }
            else{
                $temp_password = $this->PasswordHelper->generatePassword(8);
                $this->User->id = $email_exists[0]['User']['id'];
                $this->data['User']['email'] = $email;
                $this->data['User']['type_id'] = '5';
                $this->data['User']['password'] = Security::hash(Configure::read('Security.salt').$temp_password);
                $this->User->set($this->data['User']);
                if($this->User->save()){
                    $this->_sendForgotPasswordMail($this->User->id, $temp_password);
                    $this->Session->setFlash("An email with your new password has been sent to your email account.");
                }
                $this->redirect($this->webroot.'homes/forgot_password');
            }
        }
    }

    /*
     Function Name : addToWishlist
     Desc : To let the patron add songs to wishlist
    */
    function addToWishlist(){
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
		//check library download
		$libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);
		//check patron download
		$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);
        $this->Library->recursive = -1;
        $libraryDetails = $this->Library->find('all',array('conditions' => array('Library.id' => $libraryId),'fields' => 'library_user_download_limit'));
        //get patron limit per week
        $patronLimit = $libraryDetails[0]['Library']['library_user_download_limit'];
        //get no of downloads for this week
        $wishlistCount =  $this->Wishlist->find('count',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        if($wishlistCount >= $patronLimit && $libraryDownload != '1' && $patronDownload != '0') {
            echo "error";
            exit;
        }
        else {
            $prodId = $_REQUEST['prodId'];
			$downloadsDetail = array();
            //get song details
			$provider = $_REQUEST['provider'];

            $trackDetails = $this->Song->getdownloaddata($prodId , $provider);
            $insertArr = Array();
            $insertArr['library_id'] = $libraryId;
            $insertArr['patron_id'] = $patronId;
            $insertArr['ProdID'] = $prodId;
            $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
            $insertArr['album'] = $trackDetails['0']['Song']['Title'];
            $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
            $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];

			if($provider != 'sony'){
				$provider = 'ioda';
			}
			$insertArr['provider_type'] = $provider;

            $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
			$insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
            //insert into wishlist table
            $this->Wishlist->save($insertArr);
            //update the libraries table
			$this->Library->setDataSource('master');
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads-1 Where id=".$libraryId;
            $this->Library->query($sql);
			$this->Library->setDataSource('default');
            echo "Success";
            exit;
        }
    }

    /*
     Function Name : my_wishlist
     Desc : To show songs present in wishlist
    */
    function my_wishlist() {
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libraryId);
		$patronDownload = $this->Downloads->checkPatronDownload($patronId,$libraryId);
        $this->set('libraryDownload',$libraryDownload);
        $this->set('patronDownload',$patronDownload);
        $wishlistResults = Array();
        $wishlistResults =  $this->Wishlist->find('all',array('conditions' => array('library_id' => $libraryId,'patron_id' => $patronId)));
        $this->set('wishlistResults',$wishlistResults);
    }

    /*
     Function Name : my_history
     Desc : To show songs user downloaded in last 2 weeks
    */
    function my_history() {
        $this->layout = 'home';
        $libraryId = $this->Session->read('library');
        $patronId = $this->Session->read('patron');
        $downloadResults = Array();
        $downloadResults =  $this->Download->find('all',array('group' => 'Download.id','conditions' => array('library_id' => $libraryId,'patron_id' => $patronId,'history < 2','created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
		$this->set('downloadResults',$downloadResults);
    }

    /*
     Function Name : removeWishlistSong
     Desc : For removing a song from wishlist page
    */
    function removeWishlistSong() {
        $deleteSongId = $this->params['named']['id'];
        $libraryId = $this->Session->read('library');
        if($this->Wishlist->delete($deleteSongId)) {
			$this->Library->setDataSource('master');
            $sql = "UPDATE `libraries` SET library_available_downloads=library_available_downloads+1 Where id=".$libraryId;
            $this->Library->query($sql);
			$this->Library->setDataSource('default');
            $this->Session->setFlash('Data deleted successfully!');
            $this->redirect('my_wishlist');
        }
		else {
			$this->Session->setFlash('Error occured while deleteting the record');
			$this->redirect('my_wishlist');
		}
    }

    /*
     Function Name : wishlistDownload
     Desc : For downloading a song in wishlist page
    */
    function wishlistDownload() {
        Configure::write('debug', 0);
        $this->layout = false;

        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
		$prodId = $_REQUEST['prodId'];
		$downloadsDetail = array();
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
		$patronDownload = $this->Downloads->checkPatronDownload($patId,$libId);
        //check for download availability
        if($libraryDownload != '1' || $patronDownload != '1'){
            echo "error";
            exit;
        }
        $id = $_REQUEST['id'];
		$provider = $_REQUEST['provider'];;
        //get details for this song
        $trackDetails = $this->Song->getdownloaddata($prodId , $provider);
        $insertArr = Array();
        $insertArr['library_id'] = $libId;
        $insertArr['patron_id'] = $patId;
        $insertArr['ProdID'] = $prodId;
        $insertArr['artist'] = $trackDetails['0']['Song']['Artist'];
        $insertArr['track_title'] = $trackDetails['0']['Song']['SongTitle'];
        $insertArr['ProductID'] = $trackDetails['0']['Song']['ProductID'];
        $insertArr['ISRC'] = $trackDetails['0']['Song']['ISRC'];
        if($this->Session->read('referral_url') && ($this->Session->read('referral_url') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'referral_url';
        }
        elseif($this->Session->read('innovative') && ($this->Session->read('innovative') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative';
		}
		elseif($this->Session->read('mdlogin_reference') && ($this->Session->read('mdlogin_reference') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'mdlogin_reference';
		}
		elseif($this->Session->read('mndlogin_reference') && ($this->Session->read('mndlogin_reference') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'mndlogin_reference';
		}
		elseif($this->Session->read('innovative_var') && ($this->Session->read('innovative_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var';
		}
		elseif($this->Session->read('innovative_var_name') && ($this->Session->read('innovative_var_name') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_name';
		}
		elseif($this->Session->read('innovative_var_https_name') && ($this->Session->read('innovative_var_https_name') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https_name';
		}
		elseif($this->Session->read('innovative_var_https') && ($this->Session->read('innovative_var_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https';
		}
		elseif($this->Session->read('innovative_var_https_wo_pin') && ($this->Session->read('innovative_var_https_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_https_wo_pin';
		}
        elseif($this->Session->read('innovative_https') && ($this->Session->read('innovative_https') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_https';
		}
		elseif($this->Session->read('innovative_wo_pin') && ($this->Session->read('innovative_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_wo_pin';
		}
		elseif($this->Session->read('sip2') && ($this->Session->read('sip2') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2';
		}
		elseif($this->Session->read('sip') && ($this->Session->read('sip') != '')){
			$insertArr['email'] = '';
            $insertArr['user_login_type'] = 'sip';
        }
		elseif($this->Session->read('innovative_var_wo_pin') && ($this->Session->read('innovative_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'innovative_var_wo_pin';
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var';
		}
		elseif($this->Session->read('sip2_var') && ($this->Session->read('sip2_var') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';
		}
		elseif($this->Session->read('sip2_var_wo_pin') && ($this->Session->read('sip2_var_wo_pin') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'sip2_var_wo_pin';
		}
		elseif($this->Session->read('ezproxy') && ($this->Session->read('ezproxy') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'ezproxy';
		}
		elseif($this->Session->read('soap') && ($this->Session->read('soap') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'soap';
		}
		elseif($this->Session->read('curl_method') && ($this->Session->read('curl_method') != '')){
			$insertArr['email'] = '';
			$insertArr['user_login_type'] = 'curl_method';
		}
        else{
			$insertArr['email'] = $this->Session->read('patronEmail');
			$insertArr['user_login_type'] = 'user_account';
        }
		$insertArr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$insertArr['ip'] = $_SERVER['REMOTE_ADDR'];
        //save to downloads table
        if($this->Download->save($insertArr)){
        //update library table
			$this->Library->setDataSource('master');
			$sql = "UPDATE `libraries` SET library_current_downloads=library_current_downloads+1,library_total_downloads=library_total_downloads+1 Where id=".$libId;
			$this->Library->query($sql);
			$this->Library->setDataSource('default');
		}
        //delete from wishlist table
        $deleteSongId = $id;
        $this->Wishlist->delete($deleteSongId);
        //get no of downloads for this week
		$this->Download->recursive = -1;
        $downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
        echo "suces|".$downloadsUsed;
		exit;
    }
    /*
     Function Name : historyDownload
     Desc : For getting download count on My History
    */
    function historyDownload() {
        Configure::write('debug', 0);
        $this->layout = false;

        $id = $_REQUEST['id'];
		$libId = $_REQUEST['libid'];
		$patId = $_REQUEST['patronid'];
		$this->Download->recursive = -1;
        $downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $id,'library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'order'=>'created DESC','limit' => '1'));
		$downloadCount =  $downloadsUsed[0]['Download']['history'];
		//check for download availability
		if($downloadCount < 2){
			$this->Download->setDataSource('master');
			$sql = "UPDATE `downloads` SET history=history+1 Where ProdID='".$id."' AND library_id = '".$libId."' AND patron_id = '".$patId."' AND history < 2 AND created BETWEEN '".Configure::read('App.twoWeekStartDate')."' AND '".Configure::read('App.twoWeekEndDate')."' ORDER BY created DESC";
			$this->Download->query($sql);
			$this->Download->setDataSource('default');
			$downloadsUsed =  $this->Download->find('all',array('conditions' => array('ProdID' => $id,'library_id' => $libId,'patron_id' => $patId,'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))),'order'=>'created DESC','limit' => '1'));
			$downloadCount =  $downloadsUsed[0]['Download']['history'];
            echo "suces|".$downloadCount;
        } else {
			echo "error";
		}
		exit;
    }
	/*
     Function Name : admin_historyform
     Desc : actions used for admin history form
    */

    function admin_historyform() {
	if(isset($this->data) && ($this->data['Test']['language_change']) == 1){
	    $language = $this->data['Test']['language'];
	    $this -> set( 'formAction', 'admin_historyform');
	    $this -> set( 'formHeader', 'Manage History Page Text' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => $this->data['Test']['language'])));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $language;
		$this->set('getData', $getData );
	    }
	    else {
		$getData['Test']['language'] = $language;
		$getData['Test']['id'] = null;
		$getData['Test']['page_name'] = null;
		$getData['Test']['page_content'] = null;
		$this->set('getData', $getData);
	    }
	}
	else{
	    if(isset($this->data)) {
		$findData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => $this->data['Test']['language'])));
		if(count($findData) == 0) {
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()){
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		}
		elseif(count($findData) > 0){
		    $this->Page->id = $this->data['Test']['id'];
		    $pageData['Page']['page_name'] = $this->data['Test']['page_name'];
		    $pageData['Page']['page_content'] = $this->data['Test']['page_content'];
		    $pageData['Page']['language'] = $this->data['Test']['language'];
		    $this->Page->set($pageData['Page']);
		    if($this->Page->save()) {
			$this->Session->setFlash('Data has been saved successfully!', 'modal', array('class' => 'modal success'));
		    }
		    else {
			$this->Session->setFlash('There was a problem saving this information', 'modal', array('class' => 'modal problem'));
		    }
		}
	    }
	    $this -> set( 'formAction', 'admin_historyform');
	    $this -> set( 'formHeader', 'Manage History Page Text' );
	    $getPageData = $this->Page->find('all', array('conditions' => array('page_name' => 'history', 'language' => 'en')));
	    if(count($getPageData) != 0) {
		$getData['Test']['id'] = $getPageData[0]['Page']['id'];
		$getData['Test']['page_name'] = $getPageData[0]['Page']['page_name'];
		$getData['Test']['page_content'] = $getPageData[0]['Page']['page_content'];
		$getData['Test']['language'] = $getPageData[0]['Page']['language'];
		$this -> set( 'getData', $getData );
	    }
	    else {
		$arr = array();
		$this->set('getData',$arr);
	    }
	}
	$this->set('languages', $this->Language->find('list', array('fields' => array('short_name', 'full_name'))));
	$this->layout = 'admin';
    }

	/*
     Function Name : music_box
     Desc : For getting Top Downloads and FreegalMusic records for home page
    */
    function music_box() {
        Configure::write('debug', 0);
        $this->layout = false;
        $callType = $_POST['type'];
		if ($callType == 'top') {
			// Top Downloads functionality
			$libId = $this->Session->read('library');
			$this->Download->recursive = -1;
			$wk = date('W')-10;

			$startDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d')-date('w'))-70, date('Y'))) . ' 00:00:00';
		$endDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d')-date('w'))+7, date('Y'))) . ' 23:59:59';
			$topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array($startDate, $endDate)), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct'), 'order' => 'countProduct DESC','limit'=> '8' ));
			$prodIds = '';
			foreach($topDownloaded as $k => $v){
				$prodIds .= $v['Download']['ProdID']."','";
			}
		} else {
			// FreegalMusic Downloads functionality
			$this->Download->recursive = -1;
			$wk = date('W')-10;
			$startDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d')-date('w'))-70, date('Y'))) . ' 00:00:00';
		$endDate = date('Y-m-d', mktime(1, 0, 0, date('m'), (date('d')-date('w'))+7, date('Y'))) . ' 23:59:59';
			$topDownloaded = $this->Download->find('all', array('conditions' => array('created BETWEEN ? AND ?' => array($startDate, $endDate)), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct'), 'order' => 'countProduct DESC','limit'=> '8' ));
			$prodIds = '';
			foreach($topDownloaded as $k => $v){
				$prodIds .= $v['Download']['ProdID']."','";
			}
		}

		if($prodIds != ''){
			$this->Song->recursive = 2;
			$topDownload =  $this->Song->find('all',array('conditions' =>
					array('and' =>
						array(
							array("Song.ProdID IN ('".rtrim($prodIds,",'")."')" ),
						), "1 = 1 GROUP BY Song.ProdID"
					),
					'fields' => array(
						'Song.ProdID',
						'Song.ReferenceID',
						'Song.Title',
						'Song.ArtistText',
						'Song.DownloadStatus',
						'Song.SongTitle',
						'Song.Artist',
						'Song.Advisory',
						'Song.Sample_Duration',
						'Song.FullLength_Duration',
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
								'Country.SalesDate'
							)
						),
						'Sample_Files' => array(
							'fields' => array(
										'Sample_Files.CdnPath' ,
										'Sample_Files.SaveAsName'
								)
							),
					), 'order' => array('Country.SalesDate' => 'desc')
					)
			);
		} else {
			$topDownload = array();
		}
		$this->set('songs',$topDownload);
    }
    /*
    Function Name : language
    Desc : actions that is invoked when a particular language is selected
   */
    function language(){
		Configure::write('debug', 0);
		$this->layout = false;
		$language = $_POST['lang'];
		$langDetail = $this->Language->find('first', array('conditions' => array('id' => $language)));
		$this->Session->write('Config.language', $langDetail['Language']['short_name']);
		$page = $this->Session->read('Config.language');
        $pageDetails = $this->Page->find('all', array('conditions' => array('page_name' => 'login', 'language' => $page)));
        if(count($pageDetails) != 0) {
            print $pageDetails[0]['Page']['page_content'];
        }
        else {
            print "Coming Soon....";
        }
		exit;
   }
    /*
    Function Name : admin_language
    Desc : Adding languages at admin end
   */
   function admin_language(){
		if (!empty($this->data)) {
			$data['Language']['id'] = '';
			$data['Language']['short_name'] = $this->data['Tests']['short_name'];
			$data['Language']['full_name'] = $this->data['Tests']['full_name'];
		    if($this->Language->save($data['Language'])){
				$this->Session->setFlash('Your Language has been saved.', 'modal', array('class' => 'modal success'));
				$this->redirect('/admin/homes/language');
			}
			else {
				$this->Session->setFlash('Your Language has not been saved.', 'modal', array('class' => 'modal failure'));
				$this->redirect('/admin/homes/language');
			}
			$this->set('languages', $this->Language->find('all'));
		}
		else {
			$this->set('languages', $this->Language->find('all'));
		}
	    $this -> set( 'formAction', 'admin_language');
	    $this -> set( 'formHeader', 'Add Languages' );
		$this->layout = 'admin';
   }
    /*
     Function Name : admin_language_activate
     Desc : For activating a Language
    */

    function admin_language_activate() {
        $languageID = $this->params['named']['id'];
        if(trim($languageID) != "" && is_numeric($languageID)) {
			$this->Language->id = $languageID;
		    $this->Language->set(array('status' => 'active'));
		    if($this->Language->save()) {
				$this->Session ->setFlash( 'Language activated successfully!', 'modal', array( 'class' => 'modal success' ) );
			}
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
        else {
            $this->Session -> setFlash( 'Error occured while activating the Langauge', 'modal', array( 'class' => 'modal problem' ) );
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
    }

    /*
     Function Name : admin_language_deactivate
     Desc : For deactivating a Language
    */

    function admin_language_deactivate() {
        $languageID = $this->params['named']['id'];
        if(trim($languageID) != "" && is_numeric($languageID)) {
			$this->Language->id = $languageID;
		    $this->Language->set(array('status' => 'inactive'));
		    if($this->Language->save()) {
				$this->Session ->setFlash( 'Language deactivated successfully!', 'modal', array( 'class' => 'modal success' ) );
			}
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
        else {
            $this->Session->setFlash('Error occured while deactivating the Language', 'modal', array('class' => 'modal problem'));
            $this->autoRender = false;
            $this->redirect('/admin/homes/language');
        }
    }

    /*
     Function Name : auto_check
     Desc : For checking if user session is Active ro Not
    */

    function auto_check() {
		$this->layout = false;
        if(!$this->Session->read('library') || !$this->Session->read('patron')){
			print "error";
			exit;
		}
		else{
			echo "success";
			exit;
		}
    }

    function convertString(){
		Configure::write('debug', 0);
		$this->layout = false;
		$str = $_POST['str'];
		echo sha1($str);
		exit;
   }
	//Used to get Sample Song url
	function userSample()
	{
		Configure::write('debug', 0);
		$this->layout = false;
		$prodId = $_POST['prodId'];
		$this->Song->recursive = 2;
		$data =  $this->Song->find('first',array('conditions' => array('Song.ProdID' =>$prodId),
												'contain' => array(
													'Sample_Files' => array(
														'fields' => array(
																	'Sample_Files.CdnPath' ,
																	'Sample_Files.SaveAsName'
															)
														)
												)
											)
										);

		$songUrl = shell_exec('perl files/tokengen ' . $data['Sample_Files']['CdnPath']."/".$data['Sample_Files']['SaveAsName']);
		$finalSongUrl = Configure::read('App.Music_Path').$songUrl;
		echo $finalSongUrl;
		exit;
	}
}