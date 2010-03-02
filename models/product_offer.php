<?php
 /*
 File Name : product_offer.php
 File Description : Product Offer page.
 Author : maycreate
 */
class ProductOffer extends AppModel {
	var $name = 'PRODUCT_OFFER';
	var $useTable = 'PRODUCT_OFFER';
	var $primaryKey = 'PRODUCT_OFFER_ID';
	
	var $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdId'
		)
	);
	
	var $hasOne = array(
		'SalesTerritory' => array(
			'className' => 'SalesTerritory',
			'foreignKey' => 'SALES_TERRITORY_ID'
		)
	);
}