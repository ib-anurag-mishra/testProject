<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array('Html','Ajax','Javascript','Form' );
    var $components = array('RequestHandler');
    var $uses = array('Home','Physicalproduct','Featuredartist','Artist');
    
    
    function index()
    {
        $this->set('songs',$this->Home->getSongs());
        $this->set('distinctArtists', $this->Physicalproduct->getallartist());
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
    
    function autoComplete()
    {       
        $albumResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array( "OR" => array ('Physicalproduct.Title LIKE'=>$this->data['Home']['autoComplete'].'%'						
	   )),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  )));        
	$this->set('albumResults', $albumResults);       
        $artistResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array( "OR" => array ('Physicalproduct.ArtistText LIKE'=>$this->data['Home']['autoComplete'].'%'
	   )),
           'fields' => array(
			  'ArtistText'
		  ), 
		  'group' => array(
			  'ArtistText',
		  ),
                  'limit' => '6'));        
	$this->set('artistResults', $artistResults);
        $songResults = $this->Home->find('all', array(
	   'conditions'=>array( "OR" => array ('Title LIKE'=>$this->data['Home']['autoComplete'].'%'
	   )),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  ),
                  'limit' => '6'));        
	$this->set('songResults', $songResults);        
        $this->layout = 'ajax';
    }
    
    function artistSearch()
    {
	$search = $_POST['search'];
	$this->Physicalproduct->recursive = -1;
	$this->set('distinctArtists', $this->Physicalproduct->searchArtist($search));  	
    }
    
    function search()
    {        
        $searchKey = $this->data['Home']['autoComplete'];       
        $this -> paginate = array('conditions' =>
                                array('and' =>
                                        array(                                                      
                                                array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID'),
                                                array('Availability.AvailabilityType' => "PERMANENT"),
                                                array('Availability.AvailabilityStatus' => "I"),
                                                array('ProductOffer.PRODUCT_OFFER_ID >' => 0),
                                                array('ProductOffer.PURCHASE' => 'T')
                                            )
                                        ,
                                    'or' =>
                                            array(
                                                    array('Physicalproduct.ArtistText LIKE' => $searchKey.'%'),
                                                    array('Physicalproduct.Title LIKE' => $searchKey.'%'),
                                                    array('Metadata.Title LIKE' => $searchKey.'%')
                                                )
                                    )
                                );
        $this->Physicalproduct->recursive = 2;
        $searchResults = $this->paginate('Physicalproduct');        
        $this->set('searchResults', $searchResults);
        $this->layout = 'home';
    }
}
?>