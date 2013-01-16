<?php
/*
 File Name : download.php
 File Description : Models page for the  downloads table.
 Author : m68interactive
*/

class LatestDownload extends AppModel
{
  var $name = 'LatestDownload';
  //var $usetable = 'downloads';

  var $belongsTo = array(
    'Genre' => array(
    'className' => 'Genre',
    'foreignKey' => 'ProdID',
    'provider_type' => 'provider_type'
    )
  );
}
?>