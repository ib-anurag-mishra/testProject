<?php
/*
 File Name : wishlist.php
 File Description : Models page for the  Wishlist functionality.
 Author : m68interactive
*/

class Country extends AppModel
{
  var $name = 'Country';
//  var $useDbConfig = 'freegal';
  var $usetable = 'countries';
  
  var $tablePrefix = '';
  
  function setTablePrefix($prefix){  

    //App::import('Core', 'CakeSession');
    //$CakeSession = new CakeSession();
    //$this->tablePrefix = $CakeSession->read('multiple_countries');
    $this->tablePrefix = $prefix;
    

  }
}
?>