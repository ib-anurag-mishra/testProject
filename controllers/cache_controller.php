<?php
class CacheController extends AppController {
    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song','Album');

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
		$territoryNames = array('US','CA','AU','IT','NZ');
		for($i=0;$i<count($territoryNames);$i++){
			$territory = $territoryNames[$i];
			echo $territory;
			$this->Genre->Behaviors->attach('Containable');
			$this->Genre->recursive = 2;
			$genreAll = $this->Genre->find('all',array(
						'conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $territory)
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
			echo date("Y-m-d H:i:s");
			$sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.id) AS countProduct FROM `downloads` AS `Download`WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$territory."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 105";
			$natTopDownloaded = $this->Album->query($sql);
			echo count($natTopDownloaded);
			$nationalTopDownload = array();
			foreach($natTopDownloaded as $k => $v){
					$data = array();
					$this->Song->recursive = 2;
					$data =  $this->Song->find('first',array('conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $territory,"Song.DownloadStatus" => 1,"Song.ProdID" => $v['Download']['ProdID'],"Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type"),
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
							), 'limit'=> '10'
							)
					);
					if(count($data) > 1){
						$nationalTopDownload[] = $data;
					}
			}
			echo Cache::write("national".$territory, $nationalTopDownload);

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



			$genres = array('Pop' , 'Rock' , 'Country' , 'Classical' );

			foreach($genres as $genre)
			{
				$genre_data = array();
				echo $territory;
				$genre_query = "SELECT downloads.ProdID, COUNT(DISTINCT downloads.id) AS countProduct FROM `downloads`,Songs WHERE downloads.ProdID = Songs.ProdID AND Songs.Genre LIKE '%".$genre."%' AND Songs.Territory LIKE '%".$territory."%' GROUP BY downloads.ProdID ORDER BY countProduct DESC LIMIT 10";
				$genredata = $this->Album->query($genre_query);
				foreach($genredata as $k => $v){
						$this->Song->recursive = 2;
						$data =  $this->Song->find('first',array('conditions' =>
								array('and' =>
									array(
										array("Song.DownloadStatus" => 1,"Song.ProdID" => $v['downloads']['ProdID']),
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
								), 'limit'=> '10'
								)
						);
						if(count($data) > 1){
							$genre_data[] = $data;
						}
				}
				echo Cache::write($genre.$territory, $genre_data);

			}

      echo "Starting to cache Artist Browsing Data for each genre for ".$territory;
      $country = $territory;
      $condition = "";
      $gcondition = array("Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');
      $this->paginate = array(
        'conditions' => $gcondition,
        'fields' => array('Song.ArtistText'),
        'contain' => array(
          'Country' => array(
            'fields' => array(
              'Country.Territory'
            )
          ),
          'Genre' => array(
            'fields' => array(
              'Genre.Genre'
            )
          ),
        ),
        'extra' => array('chk' => 1),
        'order' => 'Song.ArtistText ASC',
        'limit' => '60',
        'cache' => 'yes',
        'check' => 2
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
        $this->Song->Behaviors->attach('Containable');
        $this->Song->recursive = 0;
        $gcondition = array("Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText');
        $this->paginate = array(
          'conditions' => $gcondition,
          'fields' => array('Song.ArtistText'),
          'contain' => array(
            'Country' => array(
              'fields' => array(
                'Country.Territory'
              )
            ),
            'Genre' => array(
              'fields' => array(
                'Genre.Genre'
              )
            ),
          ),
          'extra' => array('chk' => 1),
          'order' => 'Song.ArtistText ASC',
          'limit' => '60',
          'cache' => 'yes',
          'check' => 2
        );
        $allArtists = $this->paginate('Song');
        echo count($allArtists)." All Artists ".$alphabet."-US<BR>";
      }
      foreach($genreAll as $genreRow){
        $genre = addslashes($genreRow['Genre']['Genre']);
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
          $this->Song->Behaviors->attach('Containable');
          $this->Song->recursive = 0;
          $this->paginate = array(
              'conditions' => array("Song.provider_type = Genre.provider_type","Song.provider_type = Country.provider_type" ,"Genre.Genre = '$genre'",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
              'fields' => array('Song.ArtistText'),
              'contain' => array(
              'Country' => array(
                'fields' => array(
                  'Country.Territory'
                  )),
              'Genre' => array(
                'fields' => array(
                    'Genre.Genre'
                  )),
              ),
              'extra' => array('chk' => 1),
              'order' => 'Song.ArtistText ASC',
              'limit' => '60', 'cache' => 'yes','check' => 2
              );
          $this->Song->unbindModel(array('hasOne' => array('Participant')));
          $allArtists = $this->paginate('Song');
          echo count($allArtists)." ".$genre." ".$alphabet."US<BR>";
        }
      }
		}
		exit;
	}
}
