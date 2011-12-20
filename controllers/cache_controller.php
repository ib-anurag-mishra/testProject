<?php
class CacheController extends AppController {
    var $name = 'Cache';
    var $autoLayout = false;
    var $uses = array('Song', 'Country', 'Genre');

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
		$country = $_REQUEST['country'];
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
					  'limit' => '60', 'cache' => 'yes','mycache' => 3
					  ); 
				$this->Song->unbindModel(array('hasOne' => array('Participant')));
				$allArtists = $this->paginate('Song');
				echo count($allArtists)." ".$genres[$j]['Genre']." ".$alphabet."US<BR>";
				sleep(4);
			}
		}
		$this->Genre->Behaviors->attach('Containable');
		$this->Genre->recursive = 2;		
		$genreAll = $this->Genre->find('all',array(
					'conditions' =>
						array('and' =>
							array(
								array('Country.Territory' => $country)
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
		echo $country;
		exit();
	}	
}
