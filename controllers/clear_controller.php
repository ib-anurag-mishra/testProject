<?php
/*
 File Name : count_controller.php
 File Description : Controller page for writting the memcache key.
 Author : m68interactive
 */
class ClearController extends AppController {
  var $name = 'Clear';
  var $autoLayout = false;
  var $uses = array('Album','Download','Song','Genre');

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
      $key = 'library' . $id;
      $check = Cache::delete($key);
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
    if(!empty($country) && in_array($country,$countriesArray)){
      if(!empty($genre) && in_array($genre,$genresArray)){             
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
    ( (Song.DownloadStatus = '1') AND (Song.ProdID IN ($ids)) AND (Song.provider_type = Genre.provider_type) ) AND (Country.Territory = '$country') AND Country.SalesDate != '' AND Country.SalesDate < NOW() AND 1 = 1
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

  function restoremobilenationalforcountry($country){
	set_time_limit(0);
	$this->autoRender = false;
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
	if(!empty($country) && in_array($country,$countriesArray)){
	  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct FROM `downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 10";
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
	  $this->Song->recursive = 2;
	  $data =  $this->Song->find('all',array('conditions' =>
		array('and' =>
		  array(
			array("Country.Territory = '$country'","Song.DownloadStatus = '1'","Song.ProdID IN($ids)","Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type"),
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
		), 'limit'=> '10', 'order' => array("field(Song.ProdID,$ids)")));

	  if(!empty($data)){
		Cache::write("NationalTop10_".$country.'_WebService', $data);
	  } else {
		echo "Unable to update key";
	  }
	}
  }

  function restoremobiletopsongsforcountry($country){
	set_time_limit(0);
	$this->autoRender = false;
    $countriesArray = array('US' , 'AU' , 'CA' , 'IT' , 'NZ');
	if(!empty($country) && in_array($country,$countriesArray)){
	  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct FROM `downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 105";
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
	  $this->Song->recursive = 2;
	  $data =  $this->Song->find('all',array('conditions' =>
		array('and' =>
		  array(
			array("Country.Territory = '$country'","Song.DownloadStatus = '1'","Song.ProdID IN($ids)","Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type"),
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
		), 'limit'=> '100', 'order' => array("field(Song.ProdID,$ids)")));

	  if(!empty($data)){
		Cache::write("NationalTop100_".$country.'_WebService', $data);
	  } else {
		echo "Unable to update key";
	  }
	}
  }


  function restoremobiletopartistforcountry($country){
    set_time_limit(0);
    $this->autoRender = false;
    $territoryNames = array('US','CA','AU','IT','NZ');
    if(!empty($country) && in_array($country, $territoryNames)){
      for($i=0;$i<count($territoryNames);$i++){
        $song_data = array(); $genredata = array();
        $territory = $territoryNames[$i];
        $genre_query = "SELECT Songs.ArtistText, downloads.ProdID, COUNT(downloads.id) AS countProduct FROM downloads
                      INNER JOIN Songs ON downloads.ProdID = Songs.ProdID
                      INNER JOIN countries ON countries.ProdID = Songs.ProdID
                      WHERE countries.Territory = '".$territory."'
                      GROUP BY Songs.ArtistText ORDER BY countProduct DESC LIMIT 100";
        $genredata = $this->Album->query($genre_query);
        $ids = '';
        foreach($genredata as $val){
          if(empty($ids)){
            $ids .= $val['downloads']['ProdID'];
          } else {
            $ids .= ','.$val['downloads']['ProdID'];
          }
        }
        $str_ids = $ids;
        $ids = "'" . str_replace(',', "','", $ids) . "'";
        $limit = substr_count($str_ids, ',');
        $limit = $limit + 1;
        $data = array();
        $sql = "SELECT Song.ProdID, Song.ReferenceID, Song.SongTitle, Song.ArtistText FROM Songs AS Song  WHERE Song.DownloadStatus = '1' AND Song.ProdID IN ($ids)  ORDER BY field(Song.ProdID, $ids) ASC  LIMIT $limit";
        $data = $this->Song->query($sql);
        if(!empty($data)){
          Cache::write('TopArtist_'.$territory . '_WebService', $data);
        } else {
          echo "Unable to update cache";
        }
      }
    }
  }
}
?>
