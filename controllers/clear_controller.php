<?php
/*
 File Name : count_controller.php
 File Description : Controller page for writting the memcache key.
 Author : m68interactive
 */
class ClearController extends AppController {
  var $name = 'Clear';
  var $autoLayout = false;
  var $uses = array('Album','Download','Song','Genre', 'Library','Artist','Country');

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
			AND (PRODUCT.provider_type = Song.provider_type)
            AND Song.Genre LIKE '%".$genre."%'
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
	  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct, provider_type FROM `downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 110";
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
		//	print "<pre>";print_r($featured);exit;
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
	
    
}
?>
