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
		'Availability' => array(
			'className' => 'Availability',
			'foreignKey' => 'ProdId'
		)
       	);
}
