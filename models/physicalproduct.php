<?php
/*
 File Name : physicalproduct.php
 File Description : Models page for the  physicalproduct table.
 Author : maycreate
 */

class Physicalproduct extends AppModel
{
  var $name = 'Physicalproduct';
  var $useTable = 'PhysicalProduct';
  var $primaryKey = 'ProdID';
  var $actsAs = array('Containable');
  var $uses = array('Physicalproduct','Featuredartist','Artist','Productoffer');

 var $hasOne = array(
		'Participant' => array(
			'className' => 'Participant',
			'foreignKey' => 'ProdID'			
		),
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdID'
		),
		'Graphic' => array(
			'className' => 'Graphic',
			'foreignKey' => 'ProdID'
		),
		
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		)

  );
 
  var $hasMany = array('Audio' => array(
			'className' => 'Audio',
			'foreignKey' => 'TrkID'
		)		
  );
  
  /*
   Function Name : getallartist
   Desc : gets the list of all the artists
  */
  function getallartist() {
	$this->recursive = -1;
	$allArtists = $this->find('all', array(
		'fields' => array(
			'ArtistText'
		), 
		'group' => array(
			'ArtistText',
		),
		'order' => array(
			'ArtistText ASC'		
		),
		'cache' => 'ArtistText'
		
	));    
      return $allArtists;
  }
  
  /*
   Function Name : getallartistname
   Desc : This would returna a set of featured artist which does not have images associated with them.
  */
  function getallartistname($condition,$artistName) {
    $this->recursive = -1;
    $allArtists = $this->find('all', array(	
	'fields' => 'DISTINCT ArtistText', 
	'order' => 'ArtistText')
    );
    $featuredArtistArr = array();
    $featuredArtistObj = new Featuredartist();
    $featuredArtistList = $featuredArtistObj->getallartists();
    foreach($featuredArtistList as $featuredArtist){
      array_push($featuredArtistArr,$featuredArtist['Featuredartist']['artist_name']);
    }    
    $resultArr = array();
    foreach($allArtists as $allArtistsNames){
      if($condition == 'add'){
	if(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$featuredArtistArr)){
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
      else{
	if($allArtistsNames['Physicalproduct']['ArtistText'] == $artistName && $allArtistsNames['Physicalproduct']['ArtistText']!= ''){
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}	
	elseif(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$featuredArtistArr))	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
    }
    return $resultArr;
  }


  
  /*
   Function Name : allartistname
   Desc : This would returna a set of artist which does not have images associated with them.
  */
  function allartistname($condition,$artistName) {
    $this->recursive = -1;
    $allArtists = $this->find('all', array(	
	'fields' => 'DISTINCT ArtistText', 
	'order' => 'ArtistText')
    );
    $artistArr = array();
    $artistObj = new Artist();
    $artistList = $artistObj->getallartists();
    foreach($artistList as $artist){
      array_push($artistArr,$artist['Artist']['artist_name']);
    }    
    $resultArr = array();
    foreach($allArtists as $allArtistsNames){
      if($condition == 'add'){
	if(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$artistArr)){
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
      else{
	 if($allArtistsNames['Physicalproduct']['ArtistText'] == $artistName && $allArtistsNames['Physicalproduct']['ArtistText']!= '')	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}	
	elseif(!in_array($allArtistsNames['Physicalproduct']['ArtistText'], $artistArr)){
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
    }
    return $resultArr;
  }
  
  /*
   Function Name : searchArtist
   Desc : This would returna a artist which is searched
  */
  function searchArtist($search) {
      if($search == 'special'){
	$allArtists = $this->find('all', array(
	      'fields' => array(
		      'ArtistText'
	      ), 
	      'group' => array(
		      'ArtistText',
	      ),
	      'order' => array(
		      'ArtistText ASC',			
	      ),
	      'conditions' => array("ArtistText REGEXP '^[^A-Za-z]'")
	));
      }
      else{
	$allArtists = $this->find('all', array(
	      'fields' => array(
		      'ArtistText'
	      ), 
	      'group' => array(
		      'ArtistText',
	      ),
	      'order' => array(
		      'ArtistText ASC',			
	      ),
	      'conditions' => array('ArtistText LIKE' => $search.'%')
	));
      }      
      return $allArtists;
  }
  
  /*
   Function Name : allartistname
   Desc : This would returna the download data for the patron
  */
  function getdownloaddata($id) {
    $this->recursive = 2;
    $this->Behaviors->attach('Containable');
    $downloadData = $this->find('all', array(
		'conditions'=>array('Physicalproduct.ProdID' => $id),
		'fields' => array(
			'ProdID','ProductID','Title'
		),
		'contain' => array(
											
						'Metadata' => array(
							'fields' => array(
								'Metadata.Title',
								'Metadata.Artist',
								'Metadata.ISRC'
								)
							),
						'Audio' => array(
							'fields' => array(
								'Audio.FileID',
								                                                
								),
							'Files' => array(
							'fields' => array(
								'Files.CdnPath'
							)
							)                                  
						)
	)));
    return $downloadData;
  }
  
  /*
   Function Name : allartistname
   Desc : This would returna a set of artist.
  */
  function selectArtist() {
      $this->recursive = -1;
      $allArtists = $this->find('all', array(
	      'fields' => array(
		      'ArtistText'
	      ), 
	      'group' => array(
		      'ArtistText',
	      ),
	      'order' => array(
		      'ArtistText ASC',			
	      ),
	      'conditions' => array('ArtistText LIKE' => 'A%'),
	      'cache' => 'ArtistText'
      ));
      return $allArtists;
  }
}
?>