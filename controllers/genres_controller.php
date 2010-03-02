<?php
/*
 File Name : genres_controller.php
 File Description : Genre controller page
 Author : maycreate
 */
Class GenresController extends AppController
{
	var $uses = array('Metadata');
	function view( $Genre = null )
	{
		$this -> layout = 'home';		
		if( !$Genre )
		{
			$this->Session ->setFlash( __( 'Invalid Genre.', true ) );
			$this->redirect( array( 'controller' => '/', 'action' => 'index' ) );
		}//$this->Genre->recursive = 0;
		
		
/*$joinArray = array('joins' => array(
		    array(
			'table' => 'PRODUCT_OFFER',
			'alias' => 'PRODUCT_OFFER',
			'type' => 'LEFT',
			'foreignKey' => false,
			'conditions'=> array('Physicalproduct.ProdID = PRODUCT_OFFER.ProdID')
		    ),
		    array(
			'table' => 'SALES_TERRITORY',
			'alias' => 'SALES_TERRITORY',
			'type' => 'LEFT',
			'foreignKey' => false,
			'conditions'=> array('PRODUCT_OFFER.PRODUCT_OFFER_ID = SALES_TERRITORY.PRODUCT_OFFER_ID')
		    )
		));*/
		if($Genre != "all")
		{
		 // $this -> paginate = array('conditions' => array( 'Genre.Genre' => $Genre ) );
		  $this->paginate = array('conditions' => array('and' =>array(array('Genre.Genre' => $Genre),array('Availability.AvailabilityType' => "PERMANENT"),array('Availability.AvailabilityStatus' => "I"),array('Physicalproduct.ReferenceID <>' => 'Physicalproduct.ProdID'),array('Physicalproduct.TrackBundleCount' => 0))));
		}else{
		    $this->paginate = array('conditions' => array('and' =>array(array('Availability.AvailabilityType' => "PERMANENT"),array('Availability.AvailabilityStatus' => "I"),array('Physicalproduct.ReferenceID <>' => 'Physicalproduct.ProdID'),array('Physicalproduct.TrackBundleCount' => 0))));
		}
		$this->Genre->recursive = 1;
		$this->set('genre',$Genre);
		$data = $this->paginate('Metadata');
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

