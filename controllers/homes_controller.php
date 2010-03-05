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
	   'conditions'=>array( "OR" => array ('Physicalproduct.Title LIKE'=>$this->data['autoComplete'].'%'						
	   )),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  )));        
	$this->set('albumResults', $albumResults);       
        $artistResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array( "OR" => array ('Physicalproduct.ArtistText LIKE'=>$this->data['autoComplete'].'%'
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
	   'conditions'=>array( "OR" => array ('Title LIKE'=>$this->data['autoComplete'].'%'
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
    
    function search()
    {
	$search = $_POST['search'];
	$this->Physicalproduct->recursive = -1;
	$this->set('distinctArtists', $this->Physicalproduct->searchArtist($search));  	
    }
}
?>