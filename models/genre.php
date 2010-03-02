<?php
 /*
 File Name : genre.php
 File Description : Genre page.
 Author : maycreate
 */
class Genre extends AppModel {
	var $name = 'Genre';
	var $useTable = 'Genre';
	var $primaryKey = 'ProdId';
	var $actsAs = 'Containable';
	
	var $belongsTo = array(
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdID'
		),
		'PhysicalProduct' => array(
			'className' => 'PhysicalProduct',
			'foreignKey' => 'ProdId'
		),
		'ProductOffer' => array(
			'className' => 'ProductOffer',
			'foreignKey' => 'ProdId'
		)
	);
	
	// var $hasMany = array(
	// 		'Availability' => array(
	// 			'className' => 'Availability',
	// 			'foreignKey' => 'ProdId',
	// 			'conditions' => array(
	// 				'Availability.AvailabilityType' => 'PERMANENT',
	// 				'Availability.AvailabilityStatus' => 'I'
	// 			)
	// 		)
	// 	);
}