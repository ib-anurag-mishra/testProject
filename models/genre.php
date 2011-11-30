<?php
 /*
 File Name : genre.php
 File Description : Models page for the  Genre table.
 Author : m68interactive
*/
 
class Genre extends AppModel {
	var $name = 'Genre';
//	var $useDbConfig = 'freegal';	
	var $useTable = 'Genre';
	var $uses = array('Featuredartist','Artist');
	var $primaryKey = 'ProdId';
  
	var $belongsTo = array(
		'Download' => array(
		    'className'    => 'Download',
		    'foreignKey' => 'ProdID'
		)
	);
	
	var $hasMany = array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		)
	);
	
	var $hasOne = array(
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'ProdID'
		),	
		
		'Songs' => array(
			'className' => 'Song',
			'foreignKey' => 'Genre'
		),	
		
	);
	
}
?>