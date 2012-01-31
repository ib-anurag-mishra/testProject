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
	function cacheGenre(){
		$sql = "SELECT Genre FROM categories WHERE Language = 'EN'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$genres[] = $row;
		}
		//For US
		for($j = 0;$j < count($genres);$j++){
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
				$country = 'US';
				$genre = $genres[$j]['Genre'];
				$this->paginate = array(
					  'conditions' => array("Genre.Genre = '$genre'",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
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
				echo count($allArtists)." ".$genres[$j]['Genre']." ".$alphabet."US<BR>";
			}
		}exit;
		//For CA
		for($j = 0;$j < count($genres);$j++){
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
				$country = 'CA';
				$genre = $genres[$j]['Genre'];
				$this->paginate = array(
					  'conditions' => array("Genre.Genre = '$genre'",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
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
				echo count($allArtists)." ".$genres[$j]['Genre']." ".$alphabet."CA<BR>";
			}
		}
		//For AU
		for($j = 0;$j < count($genres);$j++){
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
				$country = 'AU';
				$genre = $genres[$j]['Genre'];
				$this->paginate = array(
					  'conditions' => array("Genre.Genre = '$genre'",'Country.Territory' => $country,'Song.DownloadStatus' => 1,"Song.Sample_FileID != ''","Song.FullLength_FIleID != ''",$condition,'1 = 1 GROUP BY Song.ArtistText'),
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
				echo count($allArtists)." ".$genres[$j]['Genre']." ".$alphabet."CA<BR>";
			}
		}		
		exit();
	}
	
	//for caching data 
	
	function cacheData(){
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
			$sql = "SELECT `Download`.`ProdID`, COUNT(DISTINCT Download.ProdID) AS countProduct FROM `downloads` AS `Download`WHERE library_id IN (SELECT id FROM libraries WHERE library_territory = '".$territory."') AND `Download`.`created` BETWEEN '".Configure::read('App.tenWeekStartDate')."' AND '".Configure::read('App.curWeekEndDate')."'  GROUP BY Download.ProdID  ORDER BY `countProduct` DESC  LIMIT 100";
			$natTopDownloaded = $this->Album->query($sql);
			echo count($natTopDownloaded);
			$nationalTopDownload = array();
			foreach($natTopDownloaded as $k => $v){
					$data = array();
					$this->Song->recursive = 2;
					$data =  $this->Song->find('first',array('conditions' =>
							array('and' =>
								array(
									array('Country.Territory' => $territory,"Song.DownloadStatus" => 1,"Song.ProdID" => $v['Download']['ProdID']),
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
			$featured = $this->Featuredartist->find('all', array('conditions' => array('Featuredartist.territory' => $territory), 'recursive' => -1));
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
									array("Album.ProdID IN (".rtrim($ids,",'").")" ),
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
				$genre_query = "SELECT downloads.ProdID FROM downloads,Songs WHERE downloads.ProdID = Songs.ProdID AND Songs.Genre LIKE '%".$genre."%' AND Songs.Territory LIKE '%".$territory."%' ORDER BY downloads.created DESC LIMIT 10";
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
									'Song.FullLength_Duration'
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
			
		}
		exit;	
	}
}
