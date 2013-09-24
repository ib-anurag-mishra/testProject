<?php
class ServicesController extends AppController {
    var $name = 'Services';
    var $autoLayout = false;
    var $uses = array('Library', 'Song', 'Country', 'Genre', 'Files', 'Album','Currentpatron', 'Download','Variable','Url','Language','Consortium');
	var $components = array('Solr', 'RequestHandler');
	var $helpers = array('Xml'); // helpers used	
	
    function search() {
        set_time_limit(0);
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
					$condSolr = "Advisory:F AND Territory:".addslashes($country);
				}
				else {
					$condSolr = "Territory:".addslashes($country);
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
					$solrCheckCondition = "OR";
				} 
				else {
					$solrCheckCondition = "AND";
				}

				if($artist != '') {
					$artistSearch = array('match(Song.ArtistText) against ("+'.$artist.'*" in boolean mode)');
					$solrArtistSearch = 'CArtistText:'.strtolower(addslashes($artist)).' '.$solrCheckCondition.' ';
				}
				else {
					$artistSearch = '';
					$solrArtistSearch = '';
				}
				
                if($composer != '') {
					$composerSearch = array('match(Song.Composer) against ("+'.$composer.'*" in boolean mode)');    
					$this->set('composer', $composer);
					$preCondition4 = array('Participant.Role' => 'Composer'); 
					$solrComposerSearch = 'CComposer:'.strtolower(addslashes($composer)).' '.$solrCheckCondition.' ';
					$role = '2';
				}
				else {
					$composerSearch = '';
					$preCondition4 = "";
					$solrComposerSearch = '';
					$role = '';
				}
				
                if($song != '') {
					$songSearch = array('match(Song.SongTitle) against ("+'.$song.'*" in boolean mode)');
					$solrSongSearch = 'CSongTitle:'.strtolower(addslashes($song)).'" '.$solrCheckCondition.' ';
				}
				else {
					$songSearch = '';
					$solrSongSearch = '';
				}
				
                if($album != '') {
					$albumSearch = array('match(Song.Title) against ("+'.$album.'*" in boolean mode)');
					$solrAlbumSearch = 'CTitle:'.strtolower(addslashes($album)).' '.$solrCheckCondition.' ';
				}
				else {
					$albumSearch = '';
					$solrAlbumSearch = '';
				}
				
                if($genre != '') {
					$genreSearch = array('match(Song.Genre) against ("+'.$genre.'*" in boolean mode)'); 
					$solrGenreSearch = 'CGenre:'.strtolower(addslashes($genre)).' '.$solrCheckCondition.' ';	
				}
				else {
					$genreSearch = '';
					$solrGenreSearch = '';
				}
				
                /* if($country != '') {
					$operator = '&';
					$territorySearch = array('match(Song.Territory) against ("+'.$country.'*" in boolean mode)'); 
					$solrTerritorySearch = 'Territory:'.addslashes($country).' '.$operator.' ';
				}
				else {
					$territorySearch = '';
					$solrTerritorySearch = '';
				}*/				
				
                $solrTempCondition = $solrArtistSearch.''.$solrComposerSearch.''.$solrSongSearch.''.$solrAlbumSearch.''.$solrGenreSearch.'';
				$solrFinalCondition = substr($solrTempCondition, 0, -5);
				$solrFinalCondition = $solrFinalCondition.' AND DownloadStatus:1 AND '.$condSolr;
				//print $solrFinalCondition;exit;
				
                if ($condSolr == "") {
					$solrFinalCondition = substr($solrFinalCondition, 0, -5);
				}

				// App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
				
                if (isset($this->passedArgs['sort'])){
					$solrSort = $this->passedArgs['sort'];
				} else {
					$solrSort = "";
				}
                
				if (isset($this->passedArgs['direction'])){
					$solrDirection = $this->passedArgs['direction'];
				} else {
					$solrDirection = "";
				}

				/*$this->paginate = array('Song' => array(
							'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'webservice' => 1
						));
				
				$searchResults = $this->paginate('Song');*/
                
				$reference = '';
                
                // echo $solrFinalCondition; die;

                $response = SolrComponent::$solr->search($solrFinalCondition,'0','10000');
                
                if ($response->getHttpStatus() == 200) {
                    if ($response->response->numFound > 0) {
                        foreach ($response->response->docs as $doc) {
                            $docs[] = $doc;
                        }
                    } else {
                        $docs = array();
                    }
                } else {
                    $docs = array();
                }
                
				foreach($docs as $k=>$v){
					$result[$k]['Song']['ProdID'] = $v->ProdID;
					$result[$k]['Song']['ProductID'] = $v->ProductID;
					$result[$k]['Song']['ReferenceID'] = $v->ReferenceID;
					$result[$k]['Song']['Title'] = $v->Title;
					$result[$k]['Song']['SongTitle'] = $v->SongTitle;
					$result[$k]['Song']['ArtistText'] = $v->ArtistText;
					$result[$k]['Song']['provider_type'] = $v->provider_type;
					$result[$k]['Song']['Artist'] = $v->Artist;
					$result[$k]['Song']['Advisory'] = $v->Advisory;
					$result[$k]['Song']['Composer'] = str_replace('"','',$v->Composer);
					$result[$k]['Song']['Genre'] = str_replace('"','',$v->Genre);
					
                    if(isset($this->params['pass'][3])){
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$this->params['pass'][3]."/".$v['Song']['ReferenceID']."/".base64_encode($v['Song']['ArtistText'])."/".base64_encode($v['Song']['provider_type']);
					}
					else{
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$v['Song']['ReferenceID']."/".base64_encode($v['Song']['ArtistText'])."/".base64_encode($v['Song']['provider_type']);					
					}
                    
					if($reference != $v['Song']['ReferenceID']){ 
						$albumData = $this->Album->find('all', array(
							'conditions'=>array('Album.ProdID' => $v->ReferenceID),
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
						$reference = $v->ReferenceID;
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
        set_time_limit(0);
		if($this->params['pass'][3] == ''){
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
	else
	{
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
					$condSolr = "Advisory:F AND Territory:".addslashes($country);
				}
				else {
					$condSolr = "Territory:".addslashes($country);
				}
				if($this->params['pass'][4] != ''){
					$genre = $this->params['pass'][4];
				}else{
					$genre = $this->params['pass'][3];
				}
	//			print $genre;exit;
				$searchString =  base64_decode($genre);	
				$searchString = str_replace("^", " ", $searchString);					
				$searchString = str_replace("$", " ", $searchString);
				$solrCheckCondition = "AND";
				if($genre != '') {
					$solrGenreSearch = 'CGenre:'.strtolower(addslashes($searchString)).'* '.$sphinxCheckCondition.' ';	
				}
				else {
					$solrGenreSearch = '';
				}			
				$solrTempCondition = $solrGenreSearch;
				$solrFinalCondition = substr($solrTempCondition, 0, -5);
				$solrFinalCondition = $solrFinalCondition.' AND DownloadStatus:1 AND '.$condSolr;
				if ($condSolr == "") {
					$solrFinalCondition = substr($solrFinalCondition, 0, -2);
				}
			
				// App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
				if (isset($this->passedArgs['sort'])){
					$solrSort = $this->passedArgs['sort'];
				} else {
					$solrSort = "";
				}
				if (isset($this->passedArgs['direction'])){
					$solrDirection = $this->passedArgs['direction'];
				} else {
					$solrDirection = "";
				}
				
				/*$this->paginate = array('Song' => array(
							'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'webservice' => 1
						));
				
				$searchResults = $this->paginate('Song');*/
               
				$response = SolrComponent::$solr->search($solrFinalCondition,'0','10000');
                
                if ($response->getHttpStatus() == 200) {
                    if ($response->response->numFound > 0) {
                        foreach ($response->response->docs as $doc) {
                            $docs[] = $doc;
                        }
                    } else {
                        $docs = array();
                    }
                } else {
                    $docs = array();
                }
                
                //print_r($docs); die;
                
                $reference = '';
				foreach($docs as $k=>$v){
                    $result[$k]['Song']['ProdID'] = $v->ProdID;
					$result[$k]['Song']['ProductID'] = $v->ProductID;
					$result[$k]['Song']['ReferenceID'] = $v->ReferenceID;
					$result[$k]['Song']['Title'] = $v->Title;
					$result[$k]['Song']['SongTitle'] = $v->SongTitle;
					$result[$k]['Song']['ArtistText'] = $v->ArtistText;
					$result[$k]['Song']['provider_type'] = $v->provider_type;
					$result[$k]['Song']['Artist'] = $v->Artist;
					$result[$k]['Song']['Advisory'] = $v->Advisory;
					$result[$k]['Song']['Composer'] = str_replace('"','',$v->Composer);
					$result[$k]['Song']['Genre'] = str_replace('"','',$v->Genre);
					
                    if(isset($this->params['pass'][4])){
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$this->params['pass'][3]."/".$v->ReferenceID."/".base64_encode($v->ArtistText)."/".base64_encode($v->provider_type);
					}
					else{
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$v->ReferenceID."/".base64_encode($v->ArtistText)."/".base64_encode($v->provider_type);					
					}
					
                    if($reference != $v->ReferenceID){ 
						$albumData = $this->Album->find('all', array(
							'conditions'=>array('Album.ProdID' => $v->ReferenceID),
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
						$reference = $v->ReferenceID;
						$albumArtWork = Configure::read('App.Music_Path').shell_exec('perl files/tokengen ' . $albumData[0]['Files']['CdnPath']."/".$albumData[0]['Files']['SourceURL']);
					//	print_r($result);exit;
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
					$condSolr = "Advisory:F AND Territory:".addslashes($country);
				}
				else {
					$condSolr = "Territory:".addslashes($country);
				}
				$searchString =  base64_decode($this->params['pass'][3]);	
				$searchString = str_replace("^", " ", $searchString);					
				$searchString = str_replace("$", " ", $searchString);
				$solrCheckCondition = "AND";
				if($this->params['pass'][3] != '') {
					$solrGenreSearch = 'Genre:'.addslashes($searchString).' '.$sphinxCheckCondition.' ';	
				}
				else {
					$solrGenreSearch = '';
				}			
				$solrTempCondition = $solrGenreSearch;
				$solrFinalCondition = substr($solrTempCondition, 0, -2);
				$solrFinalCondition = $solrFinalCondition.' AND DownloadStatus:1 AND '.$condSphinx;
				if ($condSolr == "") {
					$solrFinalCondition = substr($solrFinalCondition, 0, -2);
				}
			
				// App::import('vendor', 'sphinxapi', array('file' => 'sphinxapi.php'));
				
                if (isset($this->passedArgs['sort'])){
					$solrSort = $this->passedArgs['sort'];
				} else {
					$solrSort = "";
				}
				if (isset($this->passedArgs['direction'])){
					$solrDirection = $this->passedArgs['direction'];
				} else {
					$solrDirection = "";
				}
				
				/*$this->paginate = array('Song' => array(
							'sphinx' => 'yes', 'sphinxcheck' => $sphinxFinalCondition, 'sphinxsort' => $sphinxSort, 'sphinxdirection' => $sphinxDirection, 'webservice' => 1
						));
				
				$searchResults = $this->paginate('Song');*/
				$reference = '';
				foreach($searchResults as $k=>$v){
					$result[$k]['Song']['ProdID'] = $v['Song']['ProdID'];
					$result[$k]['Song']['ProductID'] = $v['Song']['ProductID'];
					$result[$k]['Song']['ReferenceID'] = $v['Song']['ReferenceID'];
					$result[$k]['Song']['Title'] = $v['Song']['Title'];
					$result[$k]['Song']['SongTitle'] = $v['Song']['SongTitle'];
					$result[$k]['Song']['ArtistText'] = $v['Song']['ArtistText'];
					$result[$k]['Song']['provider_type'] = $v['Song']['provider_type'];
					$result[$k]['Song']['Artist'] = $v['Song']['Artist'];
					$result[$k]['Song']['Advisory'] = $v['Song']['Advisory'];
					$result[$k]['Song']['Composer'] = str_replace('"','',$v['Song']['Composer']);
					$result[$k]['Song']['Genre'] = str_replace('"','',$v['Song']['Genre']);
					if(isset($this->params['pass'][3])){
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$this->params['pass'][3]."/".$v['Song']['ReferenceID']."/".base64_encode($v['Song']['ArtistText'])."/".base64_encode($v['Song']['provider_type']);
					}
					else{
						$result[$k]['Song']['freegal_url'] = "https://".$_SERVER['HTTP_HOST']."/services/login/".$this->params['pass'][0]."/".$this->params['pass'][1]."/".$this->params['pass'][2]."/".$v['Song']['ReferenceID']."/".base64_encode($v['Song']['ArtistText'])."/".base64_encode($v['Song']['provider_type']);					
					}
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
					//	print_r($result);exit;
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
				//verification with auth server
				$card = $this->params['pass'][2];
				$card = str_replace(" ","",$card);
				$card = strtolower($card);			
				$data['card'] = $card;
				$data['card_orig'] = $card;
				$data['pin'] = @$this->params['pass'][3];
				$data['name'] = @$this->params['pass'][3];

				$patronId = $card;
				$data['patronId'] = $patronId;
				$cardNo = substr($card,0,5);
				$data['cardNo'] = $cardNo;
				$data['library_cond'] = $this->params['pass'][1];
				$url = $this->Url->find('all', array('conditions' => array('library_id' => $this->params['pass'][1])));
				if(count($url) > 0){
					$data['referral']= @$url[0]['Url']['domain_name'];
				}
		
				$data['referral']= @$urlArr[0]['domain_name'];
				$data['subdomain']= $existingLibraries['0']['Library']['library_subdomain'];
				$data['database'] = 'freegal';
				if($existingLibraries['0']['Library']['library_authentication_method'] != 'ezproxy' && $existingLibraries['0']['Library']['library_authentication_method'] != 'user_account') {
					if($existingLibraries['0']['Library']['library_authentication_method'] == 'referral_url') {
						//do nothing
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
						$authUrl = "https://auth.libraryideas.com/Authentications/ilogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
						$authUrl = "https://auth.libraryideas.com/Authentications/idlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var_name') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/dump";
						$authUrl = "https://auth.libraryideas.com/Authentications/ildlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var_https_name') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/dump";
						$authUrl = "https://auth.libraryideas.com/Authentications/ilhdlogin_validation";
					}			
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var_https') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
						$authUrl = "https://auth.libraryideas.com/Authentications/ihdlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var_https_wo_pin') {
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/dump";
						$authUrl = "https://auth.libraryideas.com/Authentications/inhdlogin_validation";
					}			
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_https'){
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/".$v['pin']."/pintest";
						$authUrl = "https://auth.libraryideas.com/Authentications/inhlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_wo_pin') {
						$nopin = 1;
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/dump";
						$authUrl = "https://auth.libraryideas.com/Authentications/inlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'innovative_var_wo_pin') {
						$nopin = 1;
						$data['url'] = $existingLibraries['0']['Library']['library_authentication_url']."/PATRONAPI/".$card."/dump";
						$authUrl = "https://auth.libraryideas.com/Authentications/indlogin_validation";
					}		
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'sip2'){            
						$authUrl = "https://auth.libraryideas.com/Authentications/slogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'sip2_wo_pin'){ 
						$nopin = 1;
						$authUrl = "https://auth.libraryideas.com/Authentications/snlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'sip2_var'){            
						$authUrl = "https://auth.libraryideas.com/Authentications/sdlogin_validation";
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'sip2_var_wo_pin'){
						$nopin = 1;
						$authUrl = "https://auth.libraryideas.com/Authentications/sndlogin_validation";
					}			
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'ezproxy'){ 
						$nopin = 1;
						//do nothing
					}
					elseif($existingLibraries['0']['Library']['library_authentication_method'] == 'soap'){            
						$authUrl = "https://auth.libraryideas.com/Authentications/plogin_validation";
					}			
					else {
						$nopin = 1;
					   //do nothing
					}
					if(!empty($data))
					{
						$str = '<data ';
						foreach($data as $key=>$value)
						{
							$str = $str.$key.'="'.htmlentities($value).'" ';
						}
						$str = $str."></data>";
					}
					$post_data = array('xml'=>$str);
					$url = $authUrl;
					$ch=curl_init();
					// tell curl target url
					curl_setopt($ch, CURLOPT_URL, $url);
					// tell curl we will be sending via POST
					curl_setopt($ch, CURLOPT_POST, true);
					// tell it not to validate ssl cert
					curl_setopt($ch, CURLOPT_SSLVERSION, 3);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt"); 
					// tell it where to get POST variables from
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					// make the connection
					$result = curl_exec($ch);
					if(strpos($result,"successful") != false){
						//	Valid credentials

					}
					else{
						$result = array('status' => 0 , 'message' => 'Invalid Credentials');
						$this->set('result', $result);
						return;	
					}
				}

			
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
				Cache::write("login_".$existingLibraries['0']['Library']['library_territory']."_".$existingLibraries['0']['Library']['id']."_".$patronId, $values);
				/*if (($currentPatron = Cache::read("login_".$existingLibraries['0']['Library']['id'].$patronId)) === false) {
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
					
				}*/
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
				}else{
					$nopin = 1;
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
				if(isset($nopin)){
					$this->redirect(array('controller' => 'artists', 'action' => 'view', $this->params['pass'][4], $this->params['pass'][3], $this->params['pass']['5']));
				}else{
					$this->redirect(array('controller' => 'artists', 'action' => 'view', $this->params['pass'][5], $this->params['pass'][4], $this->params['pass']['6']));				
				}
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
					$this->Download->recursive = -1;
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
