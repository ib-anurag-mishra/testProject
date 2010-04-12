<?php
class Home extends AppModel
{
  var $name = 'Home';
  var $useTable = 'METADATA';
  
  //Function to get random songs
  function getSongs() {
     $randomSongs = $this->find('all', array('limit' => 8));
     return $randomSongs;
  }
}  
?>