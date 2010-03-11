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
			$this->Genre->recursive = '2';
			$genreDetails = $this->Genre->find('all',array('conditions' => array('Genre' => $genreName),'order'=> 'rand()','limit' => '3'));			
			$finalArr = Array();
			foreach($genreDetails as $genre)
			{				
				$fileID = $this->Files->find('all',array('conditions' => array('FileID' => $genre['Physicalproduct']['Graphic']['FileID'])));				
				$albumArtwork = shell_exec('perl files/tokengen ' . $fileID[0]['Files']['CdnPath']."/".$fileID[0]['Files']['SourceURL']);				
				$finalArr[$i]['Album'] = $genre['Physicalproduct']['Title'];
				$finalArr[$i]['Song'] = $genre['Metadata']['Title'];
				$finalArr[$i]['Artist'] = $genre['Metadata']['Artist'];
				$finalArr[$i]['AlbumArtwork'] = $albumArtwork;
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
							array('Availability.AvailabilityType' => "PERMANENT"),
							array('Availability.AvailabilityStatus' => "I"),
							array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
							array('Physicalproduct.TrackBundleCount' => 0),
							array('ProductOffer.PRODUCT_OFFER_ID >' => 1),
							array('ProductOffer.PURCHASE' => 'T')
						      )
						),
					  'contain' => array(
						'Genre' => array(
							'fields' => array(
								'Genre.Genre'								
								)
							),
						'Availability' => array(
							'fields' => array(
								'Availability.AvailabilityType',
								'Availability.AvailabilityStatus'
								)
							),
						'ProductOffer' => array(
							'fields' => array(
								'ProductOffer.PRODUCT_OFFER_ID',
								'ProductOffer.PURCHASE'
								),
							'SalesTerritory' => array(
							'fields' => array(
								'SalesTerritory.SALES_START_DATE'                                                    
								)
							)
							),
						'Metadata' => array(
							'fields' => array(
								'Metadata.Title',
								'Metadata.Artist'
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
		/*$data = $this->paginate('Product',array('joins' => array(
							    array(
								'table' => 'PRODUCT_OFFER',
								'alias' => 'Productoffer',
								'type' => 'left',
								'foreignKey' => false,
								'conditions'=> array('Productoffer.ProdID = Product.ProdID')
							    ),
							    array(
								'table' => 'SALES_TERRITORY',
								'alias' => 'Salesterritory',
								'type' => 'left',
								'foreignKey' => false,
								'conditions'=> array(
								    'Salesterritory.PRODUCT_OFFER_ID = Productoffer.PRODUCT_OFFER_ID'
								)
							    )
							)));*/
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

