<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */
Class GenresController extends AppController
{
	var $uses = array('Metadata','Product');
	
	function view( $Genre = null )
	{
		$this -> layout = 'home';		
		if( !base64_decode($Genre) )
		{
			$this->Session ->setFlash( __( 'Invalid Genre.', true ) );
			$this->redirect( array( 'controller' => '/', 'action' => 'index' ) );
		}
		
		if(base64_decode($Genre) != "all")
		{
		 // $this -> paginate = array('conditions' => array( 'Genre.Genre' => $Genre ) );
		  $this->paginate = array('conditions' =>
					  array('and' =>
						array(
							array('Genre.Genre' => base64_decode($Genre)),
							array('Availability.AvailabilityType' => "PERMANENT"),
							array('Availability.AvailabilityStatus' => "I"),
							array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
							array('Physicalproduct.TrackBundleCount' => 0),
							array('ProductOffer.PRODUCT_OFFER_ID >' => 1)
						      )
						)
					  );
		}else{
		  $this->paginate = array('conditions' =>
					  array('and' =>
						array(
							array('Availability.AvailabilityType' => "PERMANENT"),
							array('Availability.AvailabilityStatus' => "I"),
							array("Physicalproduct.ReferenceID <> Physicalproduct.ProdID"),
							array('Physicalproduct.TrackBundleCount' => 0),
							array('ProductOffer.PRODUCT_OFFER_ID >' => 0)
						      )
						)
					  );
		}
		
		$this->set('genre',base64_decode($Genre));
		//$this->Product->contain();
		$this->Product->recursive = 2;
		$data = $this->paginate('Product');
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
}

