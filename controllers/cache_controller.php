<?php
class CacheController extends AppController {
    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song', 'Album', 'Library', 'Download');

    function cacheLogin() {
			$libid = $_REQUEST['libid'];
			$patronid = $_REQUEST['patronid'];
			$date = time();
			$values = array(0 => $date, 1 => session_id());
			Cache::write("login_".$libid.$patronid, $values);
			print "success";exit;
    }
    function cacheUpdate() {
			$libid = $_REQUEST['libid'];
			$patronid = $_REQUEST['patronid'];
			$date = time();
			$values = array(0 => $date, 1 => session_id());
			Cache::write("login_".$libid.$patronid, $values);
			print "success";exit;
    }
    function cacheDelete() {
			$libid = $_REQUEST['libid'];
			$patronid = $_REQUEST['patronid'];
			Cache::delete("login_".$libid.$patronid);
			print "success";exit;
    }

  //for caching data
	function cacheGenre(){
    set_time_limit(0);
    $this->log("============".date("Y-m-d H:i:s")."===============",'debug');
    $territoryNames = array('US','CA','AU','IT','NZ');
		for($i=0;$i<count($territoryNames);$i++){
			$territory = $territoryNames[$i];
			$this->log("Starting caching for $territory",'debug');
			$this->Genre->Behaviors->attach('Containable');
			$this->Genre->recursive = 2;
			$genreAll = $this->Genre->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $territory, "Genre.Genre NOT IN('Porn Groove')")
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
			Cache::write("genre".$territory, $genreAll);
      $this->log("cache written for genre for $territory",'debug');
	  
		$country = $territory;
		if(!empty($country)){
		  $sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct FROM `downloads` AS `Download` WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$country."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 110";
		  $ids = '';
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
		countries AS Country ON (Country.ProdID = Song.ProdID) AND (Country.Territory = '$country') AND (Song.provider_type = Country.provider_type)
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
		$this->log("cache written for national top ten for $territory",'debug');
			// Checking for download status
			$featured = array();
			$ids = '';
			$featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $territory,'Featuredartist.language' => Configure::read('App.LANGUAGE')), 'recursive' => -1));
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
									array("Album.ProdID IN (".rtrim($ids,",'").")"  , "Country.Territory" => $territory , "Album.provider_type = Country.provider_type"),
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
			echo Cache::write("featured".$territory, $featured);
      $this->log("cache written for featured artists for $territory",'debug');

      $genres = array("Pop", "Rock", "Country", "Alternative", "Classical", "Gospel/Christian", "R&B", "Jazz", "Soundtracks", "Rap", "Blues", "Folk",
                    "Latin", "Children's", "Dance", "Metal/Hard Rock", "Classic Rock", "Soundtrack", "Easy Listening", "New Age");
      
			foreach($genres as $genre)
			{
				$genre_data = array();
				echo $territory;
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
            countries AS Country ON Country.ProdID = Song.ProdID
                LEFT JOIN
            File AS Sample_Files ON (Song.Sample_FileID = Sample_Files.FileID)
                LEFT JOIN
            File AS Full_Files ON (Song.FullLength_FileID = Full_Files.FileID)
				LEFT JOIN
			PRODUCT ON (PRODUCT.ProdID = Song.ProdID) 
        WHERE
            downloads.ProdID = Song.ProdID 
            AND downloads.provider_type = Song.provider_type 
            AND Song.Genre LIKE '%".mysql_real_escape_string($genre)."%'
            AND Country.Territory LIKE '%".$territory."%' 
            AND Country.SalesDate != '' 
            AND Country.SalesDate < NOW() 
            AND Song.DownloadStatus = '1' 
			AND (PRODUCT.provider_type = Song.provider_type)
            AND created BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'
        GROUP BY downloads.ProdID
        ORDER BY countProduct DESC
        LIMIT 10
        ";

        $data =   $this->Album->query($restoregenre_query);
			
        if(!empty($data)){
          	echo Cache::write($genre.$territory, $data);
        } else {
          echo "Unable to update key";
        }       

			}
      $this->log("cache written for top 10 for different genres for $territory",'debug');


      $this->log("Starting to cache Artist Browsing Data for each genre for $territory",'debug');

      $country = $territory;
      $condition = "";
      $this->Song->unbindModel(array('hasOne' => array('Participant')));
      $this->Song->unbindModel(array('hasOne' => array('Country')));
      $this->Song->unbindModel(array('hasOne' => array('Genre')));
      $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
      $this->Song->Behaviors->attach('Containable');
      $this->Song->recursive = 0;
      $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');
      $this->paginate = array(
        'conditions' => $gcondition,
        'fields' => array('DISTINCT Song.ArtistText'),
        'extra' => array('chk' => 1),
        'order' => 'TRIM(Song.ArtistText) ASC',
        'limit' => '60',
        'cache' => 'yes',
        'check' => 2,
        'all_query'=> true,
        'all_country'=> "find_in_set('\"$country\"',Song.Territory) > 0",
        'all_condition'=>((is_array($condition) && isset($condition['Song.ArtistText LIKE']))? "Song.ArtistText LIKE '".$condition['Song.ArtistText LIKE']."'":(is_array($condition)?$condition[0]:$condition))
      );
      $allArtists = $this->paginate('Song');
      for($i = 65;$i < 93;$i++){
        $alphabet = chr($i);
        if($alphabet == '[') {
          $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
        }
        elseif($i == 92) {
          $condition = "";
        }
        elseif($alphabet != '') {
          $condition = array('Song.ArtistText LIKE' => $alphabet.'%');
        }
        else {
          $condition = "";
        }
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Country')));
        $this->Song->unbindModel(array('hasOne' => array('Genre')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
        $this->Song->Behaviors->attach('Containable');
        $this->Song->recursive = 0;
        $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''","Song.ArtistText != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');
        $this->paginate = array(
          'conditions' => $gcondition,
          'fields' => array('DISTINCT Song.ArtistText'),
          'extra' => array('chk' => 1),
          'order' => 'TRIM(Song.ArtistText) ASC',
          'limit' => '60',
          'cache' => 'yes',
          'check' => 2,
          'all_query'=> true,
          'all_country'=> "find_in_set('\"$country\"',Song.Territory) > 0",
          'all_condition'=>((is_array($condition) && isset($condition['Song.ArtistText LIKE']))? "Song.ArtistText LIKE '".$condition['Song.ArtistText LIKE']."'":(is_array($condition)?$condition[0]:$condition))
        );
        $allArtists = $this->paginate('Song');
        $this->log("$totalPages cached for All Artists ".$alphabet."-".$territory,'debug');
      }

      $this->Song->bindmodel(array('hasOne'=>array(
            'Genre' => array(
              'className' => 'Genre',
              'foreignKey' => 'ProdID'
            ),
            'Country' => array(
            	'className' => 'Country',
            	'foreignKey' => 'ProdID'
            )
          )
        )
      );

      foreach($genreAll as $genreRow){
        $genre = addslashes($genreRow['Genre']['Genre']);
        $condition = "";
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $this->Song->unbindModel(array('hasOne' => array('Country')));
        $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
        $this->Song->Behaviors->attach('Containable');
        $this->Song->recursive = 0;
        $this->paginate = array(
            'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
            'fields' => array('DISTINCT Song.ArtistText'),
            'contain' => array(
              'Genre' => array(
                'fields' => array(
                  'Genre.Genre'
                )
              ),
            ),
            'extra' => array('chk' => 1),
            'order' => 'TRIM(Song.ArtistText) ASC',
            'limit' => '60', 'cache' => 'yes','check' => 2
          );
        $allArtists = $this->paginate('Song');
        $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'debug');
        for($i = 65;$i < 93;$i++){
          $alphabet = chr($i);
          if($alphabet == '[') {
            $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
          }
          elseif($i == 92) {
            $condition = "";
          }
          elseif($alphabet != '') {
            $condition = array('Song.ArtistText LIKE' => $alphabet.'%');
          }
          else {
            $condition = "";
          }
          $this->Song->unbindModel(array('hasOne' => array('Participant')));
          $this->Song->unbindModel(array('hasOne' => array('Country')));
          $this->Song->unbindModel(array('belongsTo' => array('Sample_Files','Full_Files')));
          $this->Song->Behaviors->attach('Containable');
          $this->Song->recursive = 0;
          $this->paginate = array(
            'conditions' => array("Song.provider_type = Genre.provider_type","Genre.Genre = '$genre'","find_in_set('\"$country\"',Song.Territory) > 0",'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
            'fields' => array('DISTINCT Song.ArtistText'),
            'contain' => array(
              'Genre' => array(
                'fields' => array(
                  'Genre.Genre'
                )
              ),
            ),
            'extra' => array('chk' => 1),
            'order' => 'TRIM(Song.ArtistText) ASC',
            'limit' => '60', 'cache' => 'yes','check' => 2
          );
          $this->Song->unbindModel(array('hasOne' => array('Participant')));
          $allArtists = $this->paginate('Song');
          $this->log(count($allArtists)." ".$genre." ".$alphabet."-".$territory,'debug');
        }
      }
      $this->Song->bindmodel(array('hasOne'=>array(
            'Genre' => array(
              'className' => 'Genre',
              'foreignKey' => 'ProdID'
            ),
            'Country' => array(
             	'className' => 'Country',
            	'foreignKey' => 'ProdID'
            )
          ), 'belongsTo'=>array('Sample_Files' => array(
              'className' => 'Files',
              'foreignKey' => 'Sample_FileID'
            ),
            'Full_Files' => array(
              'className' => 'Files',
              'foreignKey' => 'FullLength_FileID'
            )
          )
        )
      );
    }
    
    exit;
  }
  
  
}
