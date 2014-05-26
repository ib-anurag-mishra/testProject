<?php
/*
 File Name : count_controller.php
 File Description : Controller page for writting the memcache key.
 Author : m68interactive
 */  
class ClearController extends AppController {
  var $name = 'Clear';
  var $autoLayout = false;
  var $uses = array('Album','Download','Song','Genre', 'Library','Artist','Country', 'LatestDownload','StreamingHistory', 'StreamingRecords');

  
  
  
    
  function cachekey($key){

    if(!empty($key)){
      $this->autoRender = false;
      $check = Cache::delete($key);
      if($check == true){
        echo "Cache cleared";
      } else {
        echo "Cache not cleared";
      }
    }
  }
  
  function library($id){
    $this->autoRender = false;
    if(!empty($id)) {
      $key = 'library' . $id;
      $check = Cache::delete($key);
      if($check == true){
        echo "Cache Cleared";
      } else {
        echo "Cache is already Cleared";
      }
    }
    else
    {
        echo "Pleae enter library ID";
    }
  }
  
  function admin_library($id){
    $this->autoRender = false;
    if(!empty($id)) {
		$memcache = new Memcache;
		$memcache->addServer(Configure::read('App.memcache_ip'), 11211);
                $memcache->addServer(Configure::read('App.memcache_ip2'), 11211);

		$key = Configure::read('App.memcache_key').'_library' . $id;
		$check = memcache_delete($memcache,$key);
		if($check == true){
			$this->Session -> setFlash( 'Cache cleared..!!', 'modal', array( 'class' => 'modal success' ) );
			$this->redirect('/admin/libraries/managelibrary');
		} else {
			$this->Session -> setFlash( 'Cache is already cleared..!!', 'modal', array( 'class' => 'modal problem' ) );
			$this->redirect('/admin/libraries/managelibrary');
		}
    }
    else
    {
        echo "Pleae enter library ID";
    }
  }  

 function restoregenre($genre,$country){
    set_time_limit(0);
		Ignore_User_Abort(True);
    $this->autoRender = false;
    ini_set("memory_limit", "1G");
    $genresArray = array('Pop' , 'Rock' , 'Country' , 'Classical' );
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
    $multiple_countries = $this->getCurrentCountryTable();
    if(!empty($country) && in_array($country,$countriesArray)){
      if(!empty($genre) && in_array($genre,$genresArray)){
        if(0 == $multiple_countries){
            $countryPrefix = '';
        } else {
            $countryPrefix = strtolower($country)."_";
        }  
        $restoregenre_query =  "
        SELECT 
            COUNT(DISTINCT latest_downloads.id) AS countProduct,
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
            Full_Files.FileID,
			PRODUCT.pid
        FROM
            latest_downloads,
            Songs AS Song
                LEFT JOIN
            {$countryPrefix}countries AS Country ON Country.ProdID = Song.ProdID
                LEFT JOIN
            File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                LEFT JOIN
            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
				LEFT JOIN
			PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
        WHERE
            latest_downloads.ProdID = Song.ProdID 
            AND latest_downloads.provider_type = Song.provider_type 
			AND (PRODUCT.provider_type = Song.provider_type)
            AND Song.Genre LIKE '%".$genre."%'
            AND Country.Territory LIKE '%".$country."%' 
            AND Country.SalesDate != '' 
            AND Country.SalesDate < NOW() 
            AND Song.DownloadStatus = '1' 
            AND created BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'
        GROUP BY latest_downloads.ProdID
        ORDER BY countProduct DESC
        LIMIT 10
        ";

        $data =   $this->Album->query($restoregenre_query);
              
        if(!empty($data)){
            Cache::write($genre.$country, $data);          
        } 
        else {
          echo "Unable to update key";
        }
      }
    }
  }


  function restoreallgenreforcountry($country){
	set_time_limit(0);
	$this->autoRender = false;
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
	if(!empty($country) && in_array($country,$countriesArray)){
	  $genreAll = array();
	  $this->Genre->Behaviors->attach('Containable');
		$this->Genre->recursive = 2;
		$genreAll = $this->Genre->find('all',array(
		  'conditions' =>
		  array('and' =>
			array(
			  array('Country.Territory' => $country, "Genre.Genre NOT IN('Porn Groove')")
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
		  ),
		  'group' => 'Genre.Genre'
		));

	  if(!empty($genreAll)){
		Cache::write("genre".$country, $genreAll);
	  } else {
		echo "Unable to update key";
	  }
	}
  }

  function restorenationalforcountry($country){
	set_time_limit(0);
	$this->autoRender = false;
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
	if(!empty($country) && in_array($country,$countriesArray)){
	  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type FROM `latest_downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.lastWeekStartDate')."' AND '".Configure::read('App.lastWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 110";
	  $ids = '';
	  $ids_provider_type = '';
	  
	  $natTopDownloaded = $this->Album->query($sql);
	  foreach($natTopDownloaded as $natTopSong){
		if(empty($ids)){
		  $ids .= $natTopSong['Download']['ProdID'];
		  $ids_provider_type .= "(" . $natTopSong['Download']['ProdID'] .",'" . $natTopSong['Download']['provider_type'] ."')";
		} else {
		  $ids .= ','.$natTopSong['Download']['ProdID'];
		   $ids_provider_type .= ','. "(" . $natTopSong['Download']['ProdID'] .",'" . $natTopSong['Download']['provider_type'] ."')";
		}
	  }
	  $data = array();
	  $multiple_countries = $this->getCurrentCountryTable();
	  if(0 == $multiple_countries){
            $countryPrefix = '';
          } else {
            $countryPrefix = strtolower($country)."_";
          } 
	  
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
    Full_Files.FileID,
	PRODUCT.pid
FROM
    Songs AS Song
        LEFT JOIN
    File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
        LEFT JOIN
    File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
        LEFT JOIN
    Genre AS Genre ON (Genre.ProdID = Song.ProdID)
        LEFT JOIN
    {$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
		LEFT JOIN
	PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
WHERE
    ( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
GROUP BY Song.ProdID
ORDER BY FIELD(Song.ProdID,
        $ids) ASC
LIMIT 100 
	  
STR;
	  
	 $data = $this->Album->query($sql_national_100); 

	  if(!empty($data)){
		Cache::write("national".$country, $data);
	  } else {
		echo "Unable to update key";
	  }
	}
  }


function restoreallgenretemp($country){
	set_time_limit(0);
	$this->autoRender = false;
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
	if(!empty($country) && in_array($country,$countriesArray)){
	  $genreAllTemp = array();
	  $genreData = $this->Genre->query("SELECT DISTINCT(Genre), '$country' as 'Territory' FROM `Genre` WHERE Genre.Genre NOT IN('Porn Groove') ");
	  foreach($genreData as $k=>$genData){
		$genreAllTemp[$k]['Genre'] = $genData['Genre'];
		$genreAllTemp[$k]['Country'] = $genData[0];
	  }
	  if(!empty($genreAllTemp)){
		Cache::write("genre".$country, $genreAllTemp);
	  } else {
		echo "Unable to update key";
	  }
	}
  }

  
  /**
  * @restoreAllGenre
  * set all genre with all territories
  *
  * $offset
  *   starting point of array 
  * $length
  *   count of elemnets
  */
  function restoreAllGenre($offset, $length){
    
    set_time_limit(0);
		Ignore_User_Abort(True);
    $this->autoRender = false;
    ini_set("memory_limit", "1G");
    
    $genresArray = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                    "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");
    
    $genresArray = array_slice($genresArray, $offset, $length);
    
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
    
    if(!empty($genresArray)) {
    
      foreach($genresArray AS $genre ) {
        foreach($countriesArray AS $territory ) {
          $multiple_countries = $this->getCurrentCountryTable();
	  if(0 == $multiple_countries){
            $countryPrefix = '';
          } else {
            $countryPrefix = strtolower($territory)."_";
          } 
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
              Full_Files.FileID,
			  PRODUCT.pid
            FROM
              downloads,
              Songs AS Song
                  LEFT JOIN
              {$countryPrefix}countries AS Country ON Country.ProdID = Song.ProdID
                  LEFT JOIN
              File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                  LEFT JOIN
              File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
					  LEFT JOIN
			PRODUCT ON (PRODUCT.ProdID = Song.ProdID)
          WHERE
              downloads.ProdID = Song.ProdID 
              AND downloads.provider_type = Song.provider_type 
			  AND PRODUCT.provider_type = Song.provider_type
              AND Song.Genre LIKE '%".mysql_real_escape_string($genre)."%'
              AND Country.Territory LIKE '%".$territory."%' 
              AND Country.SalesDate != '' 
              AND Country.SalesDate < NOW() 
              AND Song.DownloadStatus = '1' 
              AND created BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'
          GROUP BY downloads.ProdID
          ORDER BY countProduct DESC
          LIMIT 10
          ";

          echo $restoregenre_query . '<br />';
          
          $data =   $this->Album->query($restoregenre_query);
                
          if(!empty($data)){
            Cache::write($genre.$territory, $data);    
          } 
          else {
            echo $genre.$territory . " : Unable to update key <br />";
          }
          
        }
      }
    }
    
    exit("<br />DONE<br />");
  }
  
  
  function restoreAllGenrePrint($offset, $length){
      
    $genresArray = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                    "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");
    
    $genresArray = array_slice($genresArray, $offset, $length);
    
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
    
    echo '<pre>';
    
    if(!empty($genresArray)) {
    
      foreach($genresArray AS $genre ) {
        foreach($countriesArray AS $territory ) {  
          
          
          echo "<br />  ==================================== $genre.$territory Start =============================================== <br />";   
          print_r( Cache::read($genre.$territory) );
          echo "<br /> ==================================== $genre.$territory End =============================================== <br />";  
            
        }
      }  
    }
    
    exit("<br />DONE<br />");
  
  }
  
  
  function printLibTopTenQuery() {
  
    echo '<pre>';
    
    $libraryDetails = $this->Library->find('all',array(
      'fields' => array('id', 'library_territory'),
      'conditions' => array('library_status' => 'active'),
      'recursive' => -1
      )
    );  
    
    foreach($libraryDetails AS $key => $val ) {
      
      $libId = $val['Library']['id'];
      $country = $val['Library']['library_territory'];
      
			$topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
			$ids = '';
      
      
      $ids_provider_type = '';
			foreach($topDownloaded as $k => $v){
				if(empty($ids)){
				  $ids .= $v['Download']['ProdID'];
				  $ids_provider_type .= "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['Download']['ProdID'];
				  $ids_provider_type .= ','. "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				}
			}
      
			if($ids != ''){
				$this->Song->recursive = 2;
                                $multiple_countries = $this->getCurrentCountryTable();
                                if(0 == $multiple_countries){
                                    $countryPrefix = '';
                                } else {
                                    $countryPrefix = strtolower($country)."_";
                                }
				echo $topDownloaded_query =<<<STR
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
					Full_Files.FileID,
					PRODUCT.pid
				FROM
					Songs AS Song
						LEFT JOIN
					File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
						LEFT JOIN
					File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
						LEFT JOIN
					Genre AS Genre ON (Genre.ProdID = Song.ProdID)
						LEFT JOIN
					{$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID)
				WHERE
					( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;



			$topDownload = $this->Album->query($topDownloaded_query);

			} else {
				$topDownload = array();
			}

			Cache::write("lib".$libId, $topDownload);
      
      echo "<br />  ==================================== lib$libId Start =============================================== <br />";   
      print_r(Cache::read("lib".$libId));
      echo "<br /> ==================================== lib$libId End =============================================== <br />";  
    
    }
    

    exit("<br />DONE<br />");
  }

    function showLibTopTenQuery() {
      
      echo '<pre>';
    
      $libraryDetails = $this->Library->find('all',array(
        'fields' => array('id', 'library_territory'),
        'conditions' => array('library_status' => 'active'),
        'recursive' => -1
        )
      );  
    
      foreach($libraryDetails AS $key => $val ) {
        
        $libId = $val['Library']['id'];
        
        echo "<br />  ==================================== lib$libId Start =============================================== <br />";   
        print_r(Cache::read("lib".$libId));
        echo "<br /> ==================================== lib$libId End =============================================== <br />";  
      }
      
      exit("<br />DONE<br />");
    }
    
    
  function LibTopTenQuery($libId) {
  
    echo '<pre>';
    
    $libraryDetails = $this->Library->find('all',array(
      'fields' => array('id', 'library_territory'),
      'conditions' => array('library_status' => 'active', 'id' => $libId),
      'recursive' => -1
      )
    );  
    
    foreach($libraryDetails AS $key => $val ) {
      
      $country = $val['Library']['library_territory'];
      
			$topDownloaded = $this->Download->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
			$ids = '';
      
      
      $ids_provider_type = '';
			foreach($topDownloaded as $k => $v){
				if(empty($ids)){
				  $ids .= $v['Download']['ProdID'];
				  $ids_provider_type .= "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['Download']['ProdID'];
				  $ids_provider_type .= ','. "(" . $v['Download']['ProdID'] .",'" . $v['Download']['provider_type'] ."')";
				}
			}
      
			if($ids != ''){
				$this->Song->recursive = 2;
				 $topDownloaded_query =<<<STR
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
					Full_Files.FileID,
					PRODUCT.pid
				FROM
					Songs AS Song
						LEFT JOIN
					File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
						LEFT JOIN
					File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
						LEFT JOIN
					Genre AS Genre ON (Genre.ProdID = Song.ProdID)
						LEFT JOIN
					{$countryPrefix}countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID)
				WHERE
					( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;



			$topDownload = $this->Album->query($topDownloaded_query);

			} else {
				$topDownload = array();
			}

			Cache::write("lib".$libId, $topDownload);
      
      echo "<br />  ==================================== lib$libId Start =============================================== <br />";   
      print_r(Cache::read("lib".$libId));
      echo "<br /> ==================================== lib$libId End =============================================== <br />";  
    
    }
    

    exit("<br />DONE<br />");
  }


	
    function featured_albums($territory, $language) {
		
                $multiple_countries = $this->getCurrentCountryTable();
                if(0 == $multiple_countries){
                    $this->Country->setTablePrefix('');
                } else {  
                    $this->Country->setTablePrefix(strtolower($territory)."_");
                }
          
                //featured artist slideshow
		$ids_provider_type = '';
		$ids = '';
		$featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $territory,'Featuredartist.language' => $language), 'recursive' => -1));
		foreach($featured as $k => $v){
			if($v['Featuredartist']['album'] != 0){
				if(empty($ids)){
					$ids .= $v['Featuredartist']['album'];
					$ids_provider_type .= "(" . $v['Featuredartist']['album'] .",'" . $v['Featuredartist']['provider_type'] ."')";
				} else {
					$ids .= ','.$v['Featuredartist']['album'];
					$ids_provider_type .= ','. "(" . $v['Featuredartist']['album'] .",'" . $v['Featuredartist']['provider_type'] ."')";
				}	
			}
		}
		if($ids != ''){
			$this->Album->recursive = 2;
			$featured =  $this->Album->find('all',array('conditions' =>
						array('and' =>
							array(
								array("Country.Territory" => $territory, "(Album.ProdID, Album.provider_type) IN (".rtrim($ids_provider_type,",'").")" ,"Album.provider_type = Country.provider_type"),
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
		  echo "<pre><br />  ==================================== featured$territory Start =============================================== <br />";   
		  print_r($featured);
		  echo "<br /> ==================================== featured$territory End =============================================== <br />";  
		  exit("<br />DONE<br />");

	}    
	
	
	
    function featured_artist($territory, $language) {
		
		  $ssartists = $this->Artist->find('all',array('conditions'=>array('Artist.territory' => $territory, 'Artist.language'=> $language),'fields'=>array('Artist.artist_name','Artist.artist_image','Artist.territory','Artist.language'),'limit'=>6));
		  Cache::write('ssartists_'.$territory.'_'.$language, $ssartists);

		  echo "<pre><br />  ==================================== ssartists_$territory Start =============================================== <br />";   
		  print_r($ssartists);
		  echo "<br /> ==================================== ssartists_$territory End =============================================== <br />";  
		  exit("<br />DONE<br />");

	}


    function generatePageContent($type, $language) {
		if($language == ''){
			$page = 'en';
		} 
		else {
			$page = $language;
		}
        $pageInstance = ClassRegistry::init('Page');
        $pageInstance = ClassRegistry::init('Page');

		$pageDetails = $pageInstance->find('all', array('conditions' => array('page_name' => $type, 'language' => $page)));
		Cache::write("page".$page.$type, $pageDetails);
		
        $pageDetails = Cache::read("page".$page.$type);

		  echo "<pre><br />  ==================================== ssartists_$territory Start =============================================== <br />";   
		  print_r($pageDetails);
		  echo "<br /> ==================================== ssartists_$territory End =============================================== <br />";  
		  exit("<br />DONE<br />");		
		
    }	

    
    function setLibraryDetails($id) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
		$libraryDetails  = array();

		$libraryDetails = $libraryInstance->find('first', array('conditions' => array('id' => $id)));
		Cache::write("library".$id, $libraryDetails);

		$libraryDetails = Cache::read("library".$id);
		echo "<pre><br />  ==================================== library$id Start =============================================== <br />";   
		print_r($libraryDetails);
		echo "<br /> ==================================== library$id End =============================================== <br />";  
		exit("<br />DONE<br />");	
    } 
	
    function setLibraryforALLDetails($id) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
		$libraryDetails  = array();

		$libraryDetails = $libraryInstance->find('first', array('conditions' => array('id' => $id)));
		Cache::write("library".$id, $libraryDetails);

		$libraryDetails = Cache::read("library".$id);
		echo "<pre><br />  ==================================== library$id Start =============================================== <br />";   
		print_r($libraryDetails);
		echo "<br /> ==================================== library$id End =============================================== <br />";  

    } 


	function setAllLibraryDetails() {
  
		echo '<pre>';

		$libraryDetails = $this->Library->find('all',array(
			'fields' => array('id', 'library_territory'),
			'conditions' => array('library_status' => 'active'),
			'recursive' => -1
			)
		);  
		
		$reset_lib_array = array();

		foreach($libraryDetails AS $key => $val ) {
			$libId = $val['Library']['id'];
			$libraryDetails = Cache::read("library".$libId);
			if(!(isset($libraryDetails) and (count($libraryDetails) > 0) and is_array($libraryDetails))){ 
				$reset_lib_array[] = $libId;
				$this->setLibraryforALLDetails($libId);
			}
			
			
		}
		
		echo "Reset LIB IDS";
		print_r($reset_lib_array);
		exit("<br />DONE<br />");
	}
  
  
  function checkLibTopTen($lid) {
  
		echo '<pre>';
    
		$libraryDetails = $this->Library->find('all',array(
			'fields' => array('id', 'library_territory'),
			'conditions' => array('id'=>$lid,'library_status' => 'active'),
			'recursive' => -1
		)
		); 
		
		foreach($libraryDetails AS $key => $val ) {
      
    echo "<br />  ==================================== OLD Start =============================================== <br />";
    
		$libId = $val['Library']['id'];
		$country = $val['Library']['library_territory'];
      
		$siteConfigSQL = "SELECT * from siteconfigs WHERE soption = 'maintain_ldt'";
		$siteConfigData = $this->Album->query($siteConfigSQL);
		$maintainLatestDownload = (($siteConfigData[0]['siteconfigs']['svalue']==1)?true:false);
		
    echo "start time download = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
		
    $topDownloaded = $this->LatestDownload->find('all', array('conditions' => array('library_id' => $libId,'created BETWEEN ? AND ?' => array(Configure::read('App.tenWeekStartDate'), Configure::read('App.tenWeekEndDate'))), 'group' => array('ProdID'), 'fields' => array('ProdID', 'COUNT(DISTINCT id) AS countProduct', 'provider_type'), 'order' => 'countProduct DESC', 'limit'=> '15'));
		
    echo "end time download = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
		
    $ids = '';
		$ioda_ids = array();
		$sony_ids = array();
		$sony_ids_str = '';
		$ioda_ids_str = '';
        $ids_provider_type = '';
			foreach($topDownloaded as $k => $v){
				if(empty($ids)){
				  $ids .= $v['LatestDownload']['ProdID'];
				  $ids_provider_type .= "(" . $v['LatestDownload']['ProdID'] .",'" . $v['LatestDownload']['provider_type'] ."')";
				} else {
				  $ids .= ','.$v['LatestDownload']['ProdID'];
				  $ids_provider_type .= ','. "(" . $v['LatestDownload']['ProdID'] .",'" . $v['LatestDownload']['provider_type'] ."')";
				}
				if($v['LatestDownload']['provider_type'] == 'sony'){
				  $sony_ids[] = $v['LatestDownload']['ProdID'];
				} else {
				  $ioda_ids[] = $v['LatestDownload']['ProdID'];
				}
			}
        
      if(count($topDownloaded) < 1){
        $this->log("top download is not available for library: $libId - $country", "cache");
      }
      
			if($ids != ''){
				if(!empty($sony_ids)){
					$sony_ids_str = implode(',',$sony_ids);
				}
				if(!empty($ioda_ids)){
					$ioda_ids_str = implode(',',$ioda_ids);
				}
				if(!empty($sony_ids_str) && !empty($ioda_ids_str)){
					$top_ten_condition = "((Song.ProdID IN (".$sony_ids_str.") AND Song.provider_type='sony') OR (Song.ProdID IN (".$ioda_ids_str.") AND Song.provider_type='ioda'))";
				} else if(!empty($sony_ids_str)){
					$top_ten_condition = "(Song.ProdID IN (".$sony_ids_str.") AND Song.provider_type='sony')";
				} else if(!empty($ioda_ids_str)){
					$top_ten_condition = "(Song.ProdID IN (".$ioda_ids_str.") AND Song.provider_type='ioda')";
				}
        
				echo "start time songs = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
        
				$this->Song->recursive = 2;
				 $topDownloaded_query =<<<STR
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
					Full_Files.FileID,
					PRODUCT.pid
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
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID)
				WHERE
					( (Song.DownloadStatus = '1') AND ((Song.ProdID, Song.provider_type) IN ($ids_provider_type)) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;
			$topDownload = $this->Album->query($topDownloaded_query);
	  var_dump($topDownload);
			echo "<br />end time songs = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
      echo "<br />  ==================================== OLD END =============================================== <br />";
      
      echo "<br />  ==================================== NEW START =============================================== <br />";
      echo "start time songs = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
      
      $this->Song->recursive = 2;
				 $topDownloaded_query =<<<STR
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
					Full_Files.FileID,
					PRODUCT.pid
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
						LEFT JOIN
					PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
				WHERE
					((Song.DownloadStatus = '1') AND (($top_ten_condition) AND (Song.provider_type = Genre.provider_type) AND (PRODUCT.provider_type = Song.provider_type)) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1)
				GROUP BY Song.ProdID
				ORDER BY FIELD(Song.ProdID,
						$ids) ASC
				LIMIT 10
STR;

			$topDownload = $this->Album->query($topDownloaded_query);
	  var_dump($topDownload);
      
		echo "<br />end time songs = ".time()." datetime = ".date('Y-m-d h:i:s',time())."<br/>";
    echo "<br />  ==================================== NEW END =============================================== <br />";
    
		echo "</pre>";
    
	}
  exit;
  }
  
  }
  
  
  /**
   * Authenticates user by referral_url method
   * @param $card
   * @param $pin
   * @param $library_id
   * @param $agent
   * @return AuthenticationResponseDataType[]
   */
	function referralAuthinticateTest($card, $pin, $library_id, $agent){

    $card = trim($card);
    $data['card'] = $card;
    $data['pin'] = $pin;
    $patronId = $card;
    $data['patronId'] = $patronId;


    if($card == ''){

      $response_msg = 'Card number not provided';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    elseif(strlen($card) < 5){

      $response_msg = 'Invalid Card number';
      return $this->createsAuthenticationResponseDataObject(false, $response_msg);
    }
    else{


      $library_data = $this->Library->find('first', array(
                        'fields' => array('library_authentication_num', 'mobile_auth'),
                        'conditions' => array('id' => $library_id),
                        'recursive' => -1

                      ));

      if( ('' == trim($library_data['Library']['mobile_auth'])) ) {

        $response_msg = 'Sorry, your library authentication is not supported at this time.  Please contact your library for further information.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }

      $cardNo = substr($card,0,5);
      $data['cardNo'] = $cardNo;

      $this->Library->recursive = -1;
      $this->Library->Behaviors->attach('Containable');

      $existingLibraries = $this->Library->find('all',array(
                    'conditions' => array('Library.id' => $library_id, 'library_status' => 'active',
                                          'library_authentication_method' => 'referral_url'),
                    'fields' => array('Library.id','Library.library_authentication_method','Library.library_territory','Library.library_authentication_url',
                                      'Library.library_logout_url','Library.library_territory','Library.library_user_download_limit',
                                      'Library.library_block_explicit_content','Library.library_language', 'mobile_auth'))
      );


      $library_authentication_method = $existingLibraries[0]['Library']['library_authentication_method'];
      $mobile_auth = trim($existingLibraries[0]['Library']['mobile_auth']);

      $auth_url = str_ireplace('=CARDNUMBER', '='.$data['patronId'], $mobile_auth);
      $auth_url = str_ireplace('=PIN', '='.$data['pin'], $auth_url);

      if(count($existingLibraries) == 0){

        $response_msg = 'Invalid credentials provided.';
        return $this->createsAuthenticationResponseDataObject(false, $response_msg);
      }
      else{

        $ch = curl_init($auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec ( $ch );
        curl_close($ch);

        var_dump($auth_url);  
        echo '<br />';
        var_dump($resp);
        exit;
        
        $resp = trim(strip_tags($resp));
        $resp = preg_replace("/\s+/", "", $resp);
        
        if(false === strpos(strtolower($resp), 'ok')) {
          $response_msg = 'Login Failed';
          return $this->createsAuthenticationResponseDataObject(false, $response_msg);
        } else {
          
          $response_patron_id = $this->getTmpPatronID($library_id, $card, $resp);
                    
          $token = md5(time());
          $insertArr['patron_id'] = trim($response_patron_id);
					$insertArr['library_id'] = $library_id;
					$insertArr['token'] = $token;
					$insertArr['auth_time'] = time();
					$insertArr['agent'] = $agent;
					$insertArr['auth_method'] = $library_authentication_method;
					$this->AuthenticationToken->save($insertArr);

          $patron_id = $insertArr['patron_id'];
          $response_msg = 'Login Successfull';
          return $this->createsAuthenticationResponseDataObject(true, $response_msg, $token, $patron_id);

        }



      }
    }

  }
}
