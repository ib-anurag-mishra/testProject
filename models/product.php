<?php
 /*
 File Name : product.php
 File Description : product model
 Author : maycreate
 */
class Product extends AppModel {
	var $name = 'Product';
	var $useTable = 'PRODUCT';
	var $primaryKey = 'ProdID';
	
       /* var $hasMany = array(
		'Availability' => array(
			'className' => 'Availability',
			'foreignKey' => 'ProdID'
		)
       	);*/
	var $hasOne = array(
		/*'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdID'
		)*/'ProductOffer' => array(
			'className' => 'ProductOffer',
			'foreignKey' => 'ProdID'
		),
		'Physicalproduct' => array(
			'className' => 'Physicalproduct',
			'foreignKey' => 'ProdID'
		),'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		),'Availability' => array(
			'className' => 'Availability',
			'foreignKey' => 'ProdID'
		)
       	);
}
