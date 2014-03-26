<?php
/*
 File Name : wishlist.php
 File Description : Models page for the  Wishlist functionality.
 Author : m68interactive
*/

class Country extends AppModel
{
  var $name = 'Country';

  var $usetable = 'countries';
  
  var $tablePrefix = '';
  
  function setTablePrefix($prefix){  

    $this->tablePrefix = $prefix;
  }
}
?>