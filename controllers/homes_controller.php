<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array('Html','Ajax','Javascript','Form' );
    //var $components = array('RequestHandler','ValidatePatron','Download');
    var $components = array('RequestHandler');
    var $uses = array('Home','Physicalproduct','Featuredartist','Artist','Library','Metadata');
    var $beforeFilter = array('validatePatron');
 
   /*function beforeFilter()
    {        
        $this->validatePatron();
    }*/
    
    function index()
    {        
        $this->Physicalproduct->Behaviors->attach('Containable');	
		$songDetails = $this->Physicalproduct->find('all', array('conditions' => 
                                array('Physicalproduct.ReferenceID <> Physicalproduct.ProdID'), 
                                'contain' => 
                                array('Audio' => array('fields' => 
                                                                        array('Audio.FileID'),
                                                                        'Files' => array('fields' => array('Files.CdnPath', 'Files.SaveAsName'))
                                                                ),
                                        'Metadata' => array('fields' => array('Metadata.Title', 'Metadata.Artist'))
                                ),'order'=> 'rand()','limit' => '8'
                )
        );
        $this->set('songs',$songDetails);
        //$this->set('songs',$this->Home->getSongs());
        $this->set('distinctArtists', $this->Physicalproduct->getallartist());
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
    
    function autoComplete()
    {      
        $this->Physicalproduct->recursive = -1;
        $albumResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array('Physicalproduct.Title LIKE'=>$_GET['q'].'%'						
	   ),
           'fields' => array(
			  'Title'
		  ), 
		  'group' => array(
			  'Title',
		  ),
                  'limit' => '6'));            
	$this->set('albumResults', $albumResults);        
        $artistResults = $this->Physicalproduct->find('all', array(
	   'conditions'=>array('Physicalproduct.ArtistText LIKE'=>$_GET['q'].'%'
	   ),
           'fields' => array(
			  'ArtistText'
		  ), 
		  'group' => array(
			  'ArtistText',
		  ),
                  'limit' => '6'));        
	$this->set('artistResults', $artistResults);
        $songResults = $this->Home->find('all', array(
	   'conditions'=>array('Title LIKE'=>$_GET['q'].'%'
	   ),
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
        $searchKey = $this->data['Home']['search'];
        $this->Physicalproduct->Behaviors->attach('Containable');
        $this -> paginate = array('conditions' =>
                                array('and' =>
                                        array(                                                      
                                                array('Physicalproduct.ProdID <> Physicalproduct.ReferenceID'),                                                
                                                array('Physicalproduct.TrackBundleCount' => 0),
                                                array('Physicalproduct.DownloadStatus' => 1)
                                            )
                                        ,
                                    'or' =>
                                            array(
                                                    array('Physicalproduct.ArtistText LIKE' => $searchKey.'%'),
                                                    array('Physicalproduct.Title LIKE' => $searchKey.'%'),
                                                    array('Metadata.Title LIKE' => $searchKey.'%')
                                                )
                                    ),
                                    'contain' => array(                                                                       
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
        $this->Physicalproduct->recursive = 2;        
        $searchResults = $this->paginate('Physicalproduct');       
        $this->set('searchResults', $searchResults);
        $this->layout = 'home';
    }
    
    function userDownload()
    {          
        $libId = $_REQUEST['libId'];
        $patId = $_REQUEST['patId'];
        $prodId = $_REQUEST['prodId'];       
        $trackDetails = $this->Metadata->gettrackdata($prodId);
        $artist = $trackDetails['Metadata']['Artist'];
        $track = $trackDetails['Metadata']['Title'];
    }    
    
}
?>