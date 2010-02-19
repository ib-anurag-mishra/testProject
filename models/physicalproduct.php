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
  var $uses = array('Physicalproduct','Featuredartist','Artist');
  
   /*
   Function Name : getallartist
   Desc : gets the list of all the artists
  */
  public function getallartist()
  {
    $allArtists = $this->find('all', array(	
	'fields' => 'DISTINCT ArtistText', 
	'order' => 'ArtistText')
    );   
    return $allArtists;
  }
  
  /*
   Function Name : getallartistname
   Desc : This would returna a set of featured artist which does not have images associated with them.
  */

  public function getallartistname($condition,$artistName)
  {
    $allArtists = $this->find('all', array(	
	'fields' => 'DISTINCT ArtistText', 
	'order' => 'ArtistText')
    );
    $featuredArtistArr = array();
    $featuredArtistObj = new Featuredartist();
    $featuredArtistList = $featuredArtistObj->getallartists();
    foreach($featuredArtistList as $featuredArtist)
    {
      array_push($featuredArtistArr,$featuredArtist['Featuredartist']['artist_name']);
    }    
    $resultArr = array();
    foreach($allArtists as $allArtistsNames)
    {
      if($condition == 'add')
      {
	if(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$featuredArtistArr))
	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
      else
      {
	if($allArtistsNames['Physicalproduct']['ArtistText'] == $artistName && $allArtistsNames['Physicalproduct']['ArtistText']!= '')
	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}	
	elseif(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$featuredArtistArr))
	{
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
  public function allartistname($condition,$artistName)
  {
    $allArtists = $this->find('all', array(	
	'fields' => 'DISTINCT ArtistText', 
	'order' => 'ArtistText')
    );
    $artistArr = array();
    $artistObj = new Artist();
    $artistList = $artistObj->getallartists();
    foreach($artistList as $artist)
    {
      array_push($artistArr,$artist['Artist']['artist_name']);
    }    
    $resultArr = array();
    foreach($allArtists as $allArtistsNames)
    {
      if($condition == 'add')
      {
	if(!in_array($allArtistsNames['Physicalproduct']['ArtistText'],$artistArr))
	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
      else
      {
	 if($allArtistsNames['Physicalproduct']['ArtistText'] == $artistName && $allArtistsNames['Physicalproduct']['ArtistText']!= '')
	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}	
	elseif(!in_array($allArtistsNames['Physicalproduct']['ArtistText'], $artistArr))
	{
	  $resultArr[$allArtistsNames['Physicalproduct']['ArtistText']] = $allArtistsNames['Physicalproduct']['ArtistText'];
	}
      }
    }
    return $resultArr;
  }
}
?>