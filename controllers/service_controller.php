<?php
class ServiceController extends AppController {
    var $name = 'Service';
    var $autoLayout = false;
    var $uses = array('Library', 'Song', 'Country', 'Genre', 'Files', 'Album');
	var $components = array('RequestHandler');
	var $helpers = array('Xml'); // helpers used	
	
    function search() {
        $this->Library->recursive = -1;
        $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => 
												array('library_consortium' => $this->params['pass'][0],
													  'id' => $this->params['pass'][1],
													  'library_status' => 'active')
                                                )
                                            );
		if(count($existingLibraries) == 0){
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;
		}
		else{			
			$country = $existingLibraries['0']['Library']['library_territory'];
			if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
				$condSphinx = "@Advisory F";
			}
			else {
				$condSphinx = "";
			}
			$searchString =  $this->params['pass'][4];	
			$searchString = str_replace("^", " ", $searchString);					
			$searchString = str_replace("$", " ", $searchString);
			$sphinxCheckCondition = "&";
			if($this->params['pass'][3] == 'artist') {
				$sphinxArtistSearch = '@ArtistText "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxArtistSearch = '';
			}
			if($this->params['pass'][3] == 'composer') {
				$sphinxComposerSearch = '@Composer "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxComposerSearch = '';
			}
			if($this->params['pass'][3] == 'song') {
				$sphinxSongSearch = '@SongTitle "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxSongSearch = '';
			}
			if($this->params['pass'][3] == 'album') {
				$sphinxAlbumSearch = '@Title "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxAlbumSearch = '';
			}
			if($this->params['pass'][3] == 'genre') {
				$sphinxGenreSearch = '@Genre "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';	
			}
			else {
				$sphinxGenreSearch = '';
			}
			if($country != '') {
				$sphinxTerritorySearch = '@Territory "'.addslashes($country).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxTerritorySearch = '';
			}
			$sphinxTempCondition = $sphinxArtistSearch.''.$sphinxComposerSearch.''.$sphinxSongSearch.''.$sphinxAlbumSearch.''.$sphinxGenreSearch.''.$sphinxTerritorySearch;
			$sphinxFinalCondition = substr($sphinxTempCondition, 0, -2);
			$sphinxFinalCondition = $sphinxFinalCondition.' & @DownloadStatus 1 & '.$condSphinx;
			if ($condSphinx == "") {
				$sphinxFinalCondition = substr($sphinxFinalCondition, 0, -2);
			}
		
			App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
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
						'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'webservice' => 1
					));
			
			$searchResults = $this->paginate('Song');
			$reference = '';
			foreach($searchResults as $k=>$v){
				$result[$k]['Song']['ProdID'] = $v['Song']['ProdID'];
				$result[$k]['Song']['ProductID'] = $v['Song']['ProductID'];
				$result[$k]['Song']['ReferenceID'] = $v['Song']['ReferenceID'];
				$result[$k]['Song']['Title'] = $v['Song']['Title'];
				$result[$k]['Song']['SongTitle'] = $v['Song']['SongTitle'];
				$result[$k]['Song']['ArtistText'] = $v['Song']['ArtistText'];
				$result[$k]['Song']['Artist'] = $v['Song']['Artist'];
				$result[$k]['Song']['Advisory'] = $v['Song']['Advisory'];
				$result[$k]['Song']['ISRC'] = $v['Song']['ISRC'];
				$result[$k]['Song']['Composer'] = str_replace('"','',$v['Song']['Composer']);
				$result[$k]['Song']['Genre'] = str_replace('"','',$v['Song']['Genre']);
				$result[$k]['Song']['Territory'] = str_replace('"','',$v['Song']['Territory']);
				$result[$k]['Song']['DownloadStatus'] = $v['Song']['DownloadStatus'];
				$result[$k]['Song']['TrackBundleCount'] = $v['Song']['TrackBundleCount'];
				if($reference != $v['Song']['ReferenceID']){ 
					$albumData = $this->Album->find('all', array(
						'conditions'=>array('Album.ProdID' => $v['Song']['ReferenceID']),
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
					$reference = $v['Song']['ReferenceID'];
					$albumArtWork = Configure::read('App.Music_Path').shell_exec('perl files/tokengen ' . $albumData[0]['Files']['CdnPath']."/".$albumData[0]['Files']['SourceURL']);
				}
				$result[$k]['Song']['Album_Artwork'] = $albumArtWork;				
			}
			$this->set('result', $result);
		}
	}
	function genre(){
        $this->Library->recursive = -1;
        $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => 
												array('library_consortium' => $this->params['pass'][0],
													  'id' => $this->params['pass'][1],
													  'library_status' => 'active')
                                                )
                                            );
		if(count($existingLibraries) == 0){
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;
		}
		else{			
			$country = $existingLibraries['0']['Library']['library_territory'];	
			$this->Genre->recursive = 2;
			if (($genre = Cache::read("genre".$country)) === false) {
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
							'group' => 'Genre.Genre'
						));
				Cache::write("genre".$country, $genreAll);
			}
			$genreAll = Cache::read("genre".$country);
			foreach($genreAll as $k=>$v){
				$result[$k]['Genre'] = $v['Genre']['Genre'];
			}
			$this->set('genresAll', $result);	
		}
	}
	
	function genreSong(){
        $this->Library->recursive = -1;
        $existingLibraries = $this->Library->find('all',array(
                                                'conditions' => 
												array('library_consortium' => $this->params['pass'][0],
													  'id' => $this->params['pass'][1],
													  'library_status' => 'active')
                                                )
                                            );
		if(count($existingLibraries) == 0){
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;
		}
		else{			
			$country = $existingLibraries['0']['Library']['library_territory'];
			if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
				$condSphinx = "@Advisory F";
			}
			else {
				$condSphinx = "";
			}
			$searchString =  $this->params['pass'][3];	
			$searchString = str_replace("^", " ", $searchString);					
			$searchString = str_replace("$", " ", $searchString);
			$sphinxCheckCondition = "&";
			if($this->params['pass'][3] != '') {
				$sphinxGenreSearch = '@Genre "'.addslashes($searchString).'" '.$sphinxCheckCondition.' ';	
			}
			else {
				$sphinxGenreSearch = '';
			}
			if($country != '') {
				$sphinxTerritorySearch = '@Territory "'.addslashes($country).'" '.$sphinxCheckCondition.' ';
			}
			else {
				$sphinxTerritorySearch = '';
			}			
			$sphinxTempCondition = $sphinxGenreSearch.''.$sphinxTerritorySearch;
			$sphinxFinalCondition = substr($sphinxTempCondition, 0, -2);
			$sphinxFinalCondition = $sphinxFinalCondition.' & @DownloadStatus 1 & '.$condSphinx;
			if ($condSphinx == "") {
				$sphinxFinalCondition = substr($sphinxFinalCondition, 0, -2);
			}
		
			App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
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
						'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'webservice' => 1
					));
			
			$searchResults = $this->paginate('Song');
			$reference = '';
			foreach($searchResults as $k=>$v){
				$result[$k]['Song']['ProdID'] = $v['Song']['ProdID'];
				$result[$k]['Song']['ProductID'] = $v['Song']['ProductID'];
				$result[$k]['Song']['ReferenceID'] = $v['Song']['ReferenceID'];
				$result[$k]['Song']['Title'] = $v['Song']['Title'];
				$result[$k]['Song']['SongTitle'] = $v['Song']['SongTitle'];
				$result[$k]['Song']['ArtistText'] = $v['Song']['ArtistText'];
				$result[$k]['Song']['Artist'] = $v['Song']['Artist'];
				$result[$k]['Song']['Advisory'] = $v['Song']['Advisory'];
				$result[$k]['Song']['ISRC'] = $v['Song']['ISRC'];
				$result[$k]['Song']['Composer'] = str_replace('"','',$v['Song']['Composer']);
				$result[$k]['Song']['Genre'] = str_replace('"','',$v['Song']['Genre']);
				$result[$k]['Song']['Territory'] = str_replace('"','',$v['Song']['Territory']);
				$result[$k]['Song']['DownloadStatus'] = $v['Song']['DownloadStatus'];
				$result[$k]['Song']['TrackBundleCount'] = $v['Song']['TrackBundleCount'];
				if($reference != $v['Song']['ReferenceID']){ 
					$albumData = $this->Album->find('all', array(
						'conditions'=>array('Album.ProdID' => $v['Song']['ReferenceID']),
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
					$reference = $v['Song']['ReferenceID'];
					$albumArtWork = Configure::read('App.Music_Path').shell_exec('perl files/tokengen ' . $albumData[0]['Files']['CdnPath']."/".$albumData[0]['Files']['SourceURL']);
				}
				$result[$k]['Song']['Album_Artwork'] = $albumArtWork;				
			}
			$this->set('genresAll', $result);	
		}
	}

}
?>