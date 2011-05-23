<?php
class ServiceController extends AppController {
    var $name = 'Service';
    var $autoLayout = false;
    var $uses = array('Library', 'Song', 'Country', 'Genre', 'Files');
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
			foreach($searchResults as $k=>$v){
				$result[$k]['Song'] = $v['Song'];
			}
			$output = array_slice($result, 0, 100);
			$this->set('result', $output);
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
			$this->set('genresAll', $genreAll);	
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
			print $country;exit;
			$this->Song->recursive = 2;
			$genreDetails = $this->Song->find('all',array('conditions' =>
										array('and' =>
											array(
												array('Genre.Genre' => $this->params['pass'][4]),							
												array('Song.DownloadStatus' => 1),
												array("Song.Sample_FileID != ''"),
												array("Song.FullLength_FIleID != ''"),													
												array('Country.Territory' => $country),
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
											'Song.Advisory'
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
										)));
			print "<pre>";print_r($genreDetails);exit;
			$this->set('genresAll', $genreAll);	
		}
	}

}
?>