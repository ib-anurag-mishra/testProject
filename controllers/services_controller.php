<?php
class ServicesController extends AppController {
    var $name = 'Services';
    var $autoLayout = false;
    var $uses = array('Library', 'Song', 'Country', 'Genre', 'Files', 'Album','Currentpatron', 'Download','Variable','Url','Language','Consortium');
	var $components = array('RequestHandler');
	var $helpers = array('Xml'); // helpers used	
	
    function search() {
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
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
				$artist =  $this->params['named']['artist'];
				$composer = $this->params['named']['composer'];
				$song =  $this->params['named']['song'];
				$album =  $this->params['named']['album'];
				$genre =  $this->params['named']['genre'];

				$artist = str_replace("^", " ", $artist);
				$composer = str_replace("^", " ", $composer);
				$song = str_replace("^", " ", $song);
				$album = str_replace("^", " ", $album);
				$genre = str_replace("^", " ", $genre);
						
				$artist = str_replace("$", " ", $artist);
				$composer = str_replace("$", " ", $composer);
				$song = str_replace("$", " ", $song);
				$album = str_replace("$", " ", $album);
				$genre = str_replace("$", " ", $genre);
				if($this->params['named']['condition'] == 'or'){
					$sphinxCheckCondition = "|";
				} 
				else {
					$sphinxCheckCondition = "&";
				}

				if($artist != '') {
					$artistSearch = array('match(Song.ArtistText) against ("+'.$artist.'*" in boolean mode)');
					$sphinxArtistSearch = '@ArtistText "'.addslashes($artist).'" '.$sphinxCheckCondition.' ';
				}
				else {
					$artistSearch = '';
					$sphinxArtistSearch = '';
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
					$result[$k]['Song']['Composer'] = str_replace('"','',$v['Song']['Composer']);
					$result[$k]['Song']['Genre'] = str_replace('"','',$v['Song']['Genre']);
					$result[$k]['Song']['freegal_url'] = "http://".$_SERVER['HTTP_HOST']."/service/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$v['Song']['ReferenceID']."/".base64_encode($v['Song']['ArtistText']);
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
				if(count($result) > 0){
					$result = $result;
				}
				else{
					$result = array('message' => 'No Records');
				}		
				$this->set('result', $result);
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}
	}
	
	function genre(){
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
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
				$this->Genre->Behaviors->attach('Containable');
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
				foreach($genreAll as $k=>$v){
					$result[$k]['Genre'] = $v['Genre']['Genre'];
				}
				if(count($result) > 0){
					$result = $result;
				}
				else{
					$result = array('message' => 'No Records');
				}		
				$this->set('result', $result);
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}		
	}
	
	function genreSong(){
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
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
				$searchString =  base64_decode($this->params['pass'][3]);	
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
					$result[$k]['Song']['Composer'] = str_replace('"','',$v['Song']['Composer']);
					$result[$k]['Song']['Genre'] = str_replace('"','',$v['Song']['Genre']);
					$result[$k]['Song']['freegal_url'] = "http://".$_SERVER['HTTP_HOST']."/service/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$v['Song']['ReferenceID']."/".$v['Song']['ArtistText'];
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
				if(count($result) > 0){
					$result = $result;
				}
				else{
					$result = array('message' => 'No Records');
				}
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}			
	}
	function login(){
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
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
				$patronId = $this->params['pass'][2];
				$currentPatron = $this->Currentpatron->find('all', array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'], 'patronid' => $patronId)));
				if(count($currentPatron) > 0){
				// do nothing
				} else {
					$insertArr['libid'] = $existingLibraries['0']['Library']['id'];
					$insertArr['patronid'] = $patronId;
					$insertArr['session_id'] = session_id();
					$this->Currentpatron->save($insertArr);						
				}					
				if (($currentPatron = Cache::read("login_".$existingLibraries['0']['Library']['id'].$patronId)) === false) {
					$date = time();
					$values = array(0 => $date, 1 => session_id());			
					Cache::write("login_".$existingLibraries['0']['Library']['id'].$patronId, $values);
				} else {
					$userCache = Cache::read("login_".$existingLibraries['0']['Library']['id'].$patronId);
					$date = time();
					$modifiedTime = $userCache[0];
					if(!($this->Session->read('patron'))){
						if(($date-$modifiedTime) > 60){
							$values = array(0 => $date, 1 => session_id());	
							Cache::write("login_".$existingLibraries['0']['Library']['id'].$patronId, $values);
						}
						else{
							$this->Session->destroy('user');
							$this -> Session -> setFlash("This account is already active.");                              
							$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
						}
					} else {
						//nothing needs to be done
					}
					
				}
				$this->Session->write("library", $existingLibraries['0']['Library']['id']);
				$this->Session->write("patron", $patronId);
				$this->Session->write("consortium", $consortium[0]['Consortium']['consortium_name']);
				$this->Session->write("territory", $existingLibraries['0']['Library']['library_territory']);
				if($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative'){
					$this->Session->write("innovative","innovative");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var'){
					$this->Session->write("innovative_var","innovative_var");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var_name'){
					$this->Session->write("innovative_var_name","innovative_var_name");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_wo_pin'){
					$this->Session->write("innovative_wo_pin","innovative_wo_pin");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var_wo_pin'){
					$this->Session->write("innovative_var_wo_pin","innovative_var_wo_pin");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'sip2'){
					$this->Session->write("sip2","sip2");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'sip2_wo_pin'){
					$this->Session->write("sip","sip");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'sip2_var'){
					$this->Session->write("sip2_var","sip2_var");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'sip2_var_wo_pin'){
					$this->Session->write("sip2_var_wo_pin","sip2_var_wo_pin");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'ezproxy'){
					$this->Session->write("ezproxy","ezproxy");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_https'){
					$this->Session->write("innovative_https","innovative_https");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var_https'){
					$this->Session->write("innovative_var_https","innovative_var_https");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var_https_wo_pin'){
					$this->Session->write("innovative_var_https_wo_pin","innovative_var_https_wo_pin");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'soap'){
					$this->Session->write("soap","soap");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'innovative_var_https_name'){
					$this->Session->write("innovative_var_https_name","innovative_var_name");
				}elseif($existingLibraries['0']['Library']["library_authentication_method"] == 'referral_url'){
					$this->Session->write("referral_url",$existingLibraries['0']['Library']['library_domain_name']);
				}
				$this->Session->write($existingLibraries['0']['Library']["library_authentication_method"],$existingLibraries['0']['Library']["library_authentication_method"]);
				if($existingLibraries['0']['Library']['library_logout_url'] != '' && $this->Session->read('referral') != ''){
					$this->Session->write("referral",$existingLibraries['0']['Library']['library_logout_url']);
				}
				if(!$this->Session->read('Config.language') && $this->Session->read('Config.language') == ''){
					$this->Session->write('Config.language', $existingLibraries['0']['Library']['library_language']);
				}
				$isApproved = $this->Currentpatron->find('first',array('conditions' => array('libid' => $existingLibraries['0']['Library']['id'],'patronid' => $patronId)));            
				$this->Session->write("approved", $isApproved['Currentpatron']['is_approved']);
				$this->Download->recursive = -1;
				$this->Session->write("downloadsAllotted", $existingLibraries['0']['Library']['library_user_download_limit']);
				$results =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $patronId,'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
				$this ->Session->write("downloadsUsed", $results);
				if($existingLibraries['0']['Library']['library_block_explicit_content'] == '1'){
					$this ->Session->write("block", 'yes');
				}
				else{
					$this ->Session->write("block", 'no');
				}
				$this->redirect(array('controller' => 'artists', 'action' => 'view', $this->params['pass'][4], $this->params['pass'][3]));			
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}			
	}
	function downloadCount(){
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
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
					$available = $existingLibraries['0']['Library']['library_user_download_limit'];
					$downloadsUsed =  $this->Download->find('count',array('conditions' => array('library_id' => $existingLibraries['0']['Library']['id'],'patron_id' => $this->params['pass'][2],'created BETWEEN ? AND ?' => array(Configure::read('App.curWeekStartDate'), Configure::read('App.curWeekEndDate')))));
					$result = array('remaining_downloads' => $available-$downloadsUsed);
					$this->set('result', $result);
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}	
	}

	function libraries(){
		$consortium = $this->Consortium->find('all',array(
                                                'conditions' => 
												array('consortium_key' => $this->params['pass'][0])
												)
                                            );
		if(count($consortium) > 0){
			$this->Library->recursive = -1;
			$existingLibraries = $this->Library->find('all',array(
													'conditions' => 
													array('library_apikey' => $consortium[0]['Consortium']['consortium_name'],
														  'library_status' => 'active')
													,'fields' => array('id','library_name'))
												);
			if(count($existingLibraries) == 0){
				$result = array('message' => 'No Records');
				$this->set('result', $result);
				return;
			}
			else{
					$this->set('result', $existingLibraries);
			}
		}
		else{
			$result = array('status' => 0 , 'message' => 'Access Denied');
			$this->set('result', $result);
			return;		
		}	
	}
}
?>