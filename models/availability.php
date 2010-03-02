<?php
 /*
 File Name : availability.php
 File Description : Availability page.
 Author : maycreate
 */
class Availability extends AppModel {
	var $name = 'Availability';
	var $useTable = 'Availability';
	var $primaryKey = 'ProdId';
	
	var $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdId'
		)
	);
}