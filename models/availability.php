<?php
/*
 File Name : availability.php
 File Description : Availability model
 Author : maycreate
*/
 
class Availability extends AppModel {
	var $name = 'Availability';
	var $useTable = 'Availability';
	var $primaryKey = 'ProdId';
	
	var $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdId'
		)
	);
}