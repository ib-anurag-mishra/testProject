<?php
 /*
 File Name : metadata.php
 File Description : Metadata page.
 Author : maycreate
 */
class Metadata extends AppModel {
	var $name = 'Metadata';
	var $useTable = 'METADATA';
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
		),
		'Availability' => array(
			'className' => 'Availability',
			'foreignKey' => 'ProdId',
			'conditions' => array(
				'Availability.AvailabilityType' => 'PERMANENT',
				'Availability.AvailabilityStatus' => 'I'
			)
		)
	);
	
	var $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdId'
		)
	);
	
}