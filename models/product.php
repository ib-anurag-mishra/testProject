<?php
 /*
 File Name : product.php
 File Description : product model
 Author : maycreate
 */
class Product extends AppModel {
	var $name = 'Product';
	var $useTable = 'PRODUCT';
	var $primaryKey = 'ProdId';
	
	var $hasMany = array(
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdId'
		),
		'Availability' => array(
			'className' => 'Availability',
			'foreignKey' => 'ProdId',
			'conditions' => array(
				'Availability.AvailabilityType' => 'PERMANENT',
				'Availability.AvailabilityStatus' => 'I'
			)
		),
		'ProductOffer' => array(
			'className' => 'ProductOffer',
			'foreignKey' => 'ProdId'
		)	
	);
	
}