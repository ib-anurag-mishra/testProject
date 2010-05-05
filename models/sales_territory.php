<?php
/*
 File Name : sales_territory.php
 File Description : Sales Territory page.
 Author : maycreate
*/
 
class SalesTerritory extends AppModel {
	var $name = 'SALES_TERRITORY';
	var $useTable = 'SALES_TERRITORY';
	var $primaryKey = 'SALES_TERRITORY_ID';
	
	var $belongsTo = array(
		'ProductOffer' => array(
			'className' => 'ProductOffer',
			'foreignKey' => 'ProdId'
		)
	);
}