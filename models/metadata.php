<?php
 /*
 File Name : metadata.php
 File Description : Metadata page.
 Author : maycreate
 */
class Metadata extends AppModel {
	var $name = 'Metadata';
	var $useTable = 'Metadata';
	var $primaryKey = 'ProdId';
	
	var $hasOne = array(
		'Physicalproduct' => array(
			'className' => 'Physicalproduct',
			'foreignKey' => 'ProdId'
		)
	);
	
	var $hasMany = array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdId'
		)
	);
	
	var $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdId'
		)
	);
	
}