<?php
 /*
 File Name : genre.php
 File Description : Models page for the  Genre table.
 Author : maycreate
*/
 
class Genre extends AppModel {
	var $name = 'Genre';
	var $useTable = 'Genre';
	var $uses = array('Physicalproduct','Featuredartist','Artist','Metadata');
	var $primaryKey = 'ProdId';
  
	var $belongsTo = array(
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdID'
		),
		'Physicalproduct' => array(
			'className' => 'Physicalproduct',
			'foreignKey' => 'ProdID'
		),'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdID'
		),
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