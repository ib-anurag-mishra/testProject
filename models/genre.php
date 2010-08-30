<?php
 /*
 File Name : genre.php
 File Description : Models page for the  Genre table.
 Author : maycreate
*/
 
class Genre extends AppModel {
	var $name = 'Genre';
	var $useTable = 'Genre';
	var $uses = array('Featuredartist','Artist');
	var $primaryKey = 'ProdId';
  
	var $belongsTo = array(
		'Download' => array(
		    'className'    => 'Download',
		    'foreignKey' => 'Genre.ProdID'
		)
	);
	
	var $hasMany = array(
	  'Genre' => array(
	  'className' => 'Genre',
	  'foreignKey' => 'ProdID'
	  )
	);
}
?>