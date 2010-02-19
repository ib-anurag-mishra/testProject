<?php
class Home extends AppModel
{
  var $name = 'Home';
  var $useTable = 'METADATA';
  
  //Function to get random songs
  public function getSongs()
  {
     $randomSongs = $this->find('all', array('order' => 'rand()',
	                                              'limit' => 8));
     return $randomSongs;
  }
}  
?>