<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */
ini_set('memory_limit', '1024M');
Class GenresController extends AppController
{
	var $uses = array('Metadata','Product','Category','Files','Physicalproduct');
	
	function index() {
		$this->layout = 'home';
		$this->Genre->recursive = -1;
		$this->set('genresAll', $this->Genre->find('all', array('fields' => 'DISTINCT Genre','order' => 'Genre')));		
		$categories = $this->Category->find('all', array('fields' => 'Genre','order' => 'rand()','limit' => '4'));		
		$i = 0;
		$j = 0;
		foreach ($categories as $category)
		{
			$genreName = $category['Category']['Genre'];			
			//$this->Genre->recursive = '2';
			//$genreDetails = $this->Genre->find('all',array('conditions' => array('Genre' => $genreName),'order'=> 'rand()','limit' => '3'));
			$this->Physicalproduct->Behaviors->attach('Containable');	
			$genreDetails = $this->Physicalproduct->find('all',array('conditions' =>
					  array('and' =>
						array(
							array('Genre.Genre' => $genreName),							
							array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
							array('Physicalproduct.TrackBundleCount' => 0),
							array("Physicalproduct.UpdateOn >" => date('Y-m-d', strtotime("-1 week")))
						      )
						),
					  'fields' => array(
							'Physicalproduct.ProdID',
							'Physicalproduct.ReferenceID',
							'Physicalproduct.Title',
							'Physicalproduct.ArtistText',
							'Physicalproduct.DownloadStatus',
							'Physicalproduct.SalesDate'
							),
					  'contain' => array(							
						'Genre' => array(
							'fields' => array(
								'Genre.Genre'								
								)
							),
						'Graphic' => array(
							'fields' => array(
							'Graphic.ProdID',
							'Graphic.FileID'
							),
							'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName',
								'Files.SourceURL'
								)
							)
							),						
						'Metadata' => array(							
							'fields' => array(
								'Metadata.Title',
								'Metadata.Artist',
								'Metadata.Advisory'
								)
							),
						'Audio' => array(
							'fields' => array(
								'Audio.FileID',                                                    
								),
							'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName'
								)
							)
							)                                    
						),'limit' => '30'));			//'order'=> 'rand()',		 
			$finalArr = Array();
			$rand_keys = array_rand($genreDetails,3);
			$songArr = Array();
			$songArr[0] = $genreDetails[$rand_keys[0]];
			$songArr[1] = $genreDetails[$rand_keys[1]];
			$songArr[2] = $genreDetails[$rand_keys[2]];			
			//$
			foreach($songArr as $genre)
			{
				$albumArtwork = shell_exec('perl files/tokengen ' . $genre['Graphic']['Files']['CdnPath']."/".$genre['Graphic']['Files']['SourceURL']);
				$songUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['1']['Files']['CdnPath']."/".$genre['Audio']['1']['Files']['SaveAsName']);
				$sampleSongUrl = shell_exec('perl files/tokengen ' . $genre['Audio']['0']['Files']['CdnPath']."/".$genre['Audio']['0']['Files']['SaveAsName']);
				$finalArr[$i]['Album'] = $genre['Physicalproduct']['Title'];
				$finalArr[$i]['Song'] = $genre['Metadata']['Title'];
				$finalArr[$i]['Artist'] = $genre['Metadata']['Artist'];
				$finalArr[$i]['AlbumArtwork'] = $albumArtwork;
				$finalArr[$i]['SongUrl'] = $songUrl;
				//$finalArr[$i]['SaleStartDate'] = $genre['ProductOffer']['SalesTerritory']['SALES_START_DATE'];
				$finalArr[$i]['ProdId'] = $genre['Physicalproduct']['ProdID'];
				$finalArr[$i]['SalesDate'] = $genre['Physicalproduct']['SalesDate'];
				$finalArr[$i]['SampleSong'] = $sampleSongUrl;
				$i++;				
			}			
			$finalArray[$j] = $finalArr;
			$finalArray[$j]['Genre'] = $genreName;
			$j++;
		}		
		$this->set('categories',$finalArray);		
	}
	
	function view( $Genre = null )
	{
		$this -> layout = 'home';		
		if( !base64_decode($Genre) )
		{
			$this->Session ->setFlash( __( 'Invalid Genre.', true ) );
			$this->redirect( array( 'controller' => '/', 'action' => 'index' ) );
		}
		  $this->Physicalproduct->Behaviors->attach('Containable');	
		  $this->paginate = array('conditions' =>
					  array('and' =>
						array(
							array('Genre.Genre' => base64_decode($Genre)),							
							array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
							array('Physicalproduct.TrackBundleCount' => 0),
							array('Physicalproduct.DownloadStatus' => 1)
						      )
						),
					  'fields' => array(
							'Physicalproduct.ProdID',
							'Physicalproduct.Title',
							'Physicalproduct.ReferenceID',
							'Physicalproduct.ArtistText',
							'Physicalproduct.DownloadStatus',
							'Physicalproduct.SalesDate'
							),
					  'contain' => array(
						'Genre' => array(
							'fields' => array(
								'Genre.Genre'								
								)
							),						
						'Metadata' => array(
							'fields' => array(
								'Metadata.Title',
								'Metadata.Artist',
								'Metadata.Advisory'
								)
							),
						'Audio' => array(
							'fields' => array(
								'Audio.FileID',                                                    
								),
							'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName'
								)
							)
							)                                    
						)
					  );		
		
		$this->set('genre',base64_decode($Genre));		
		$this->Physicalproduct->recursive = 2;
		$data = $this->paginate('Physicalproduct');		
		if(count($data) > 0)
		{
		  $album = array();
		  foreach($data as $distinctData)
		  {
		    $albumData = $this->Metadata->find('first', array('conditions' => array('Metadata.ProdID' => $distinctData['Physicalproduct']['ReferenceID'])));
		    $album[$albumData['Metadata']['ProdID']]  =  $albumData['Metadata']['Title'];
		  }	
		  $this->set('genres', $data);
		  $this->set('albumData', $album);
		}else{
		  $this->set('genres', 0);
		}
	}
	
	function admin_managegenre()
	{
			
		if($this->data)
		{
			$this->Category->deleteAll(array('1 = 1'), false);
			$selectedGenres = Array();
			$i = 0;		
			foreach ($this->data['Genre']['Genre'] as $k => $v)
			{	  
			  if($i < '8')
			  {			
				if($v != '0')
				{
				      $selectedGenres['Genre'] = $v;			      
				      $this->Category->save($selectedGenres);
				      $this->Category->id = false ;
				      $i++;
				}			
			  }
			}
		}
		
				
		$this->Genre->recursive = -1;
		$allGenres = $this->Genre->find('all', array(	
			'fields' => 'DISTINCT Genre', 
			'order' => 'Genre')
		    );
		
		$this->set('allGenres', $allGenres);
		$this->Category->recursive = -1;
		$selectedGenres = $this->Category->find('all',array(	
			'fields' => 'Genre'));
		foreach ($selectedGenres as $selectedGenre)
		{
			$selArray[] = $selectedGenre['Category']['Genre'];
		}		
		$this->set('selectedGenres', $selArray);
		$this->layout = 'admin';
	}
}

