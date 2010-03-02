<?php
class Productoffer extends AppModel
{
  var $name = 'Productoffer';
  var $useTable = 'PRODUCT_OFFER';
  
  var $hasOne = array(
		'Salesterritory' => array(
			'className' => 'Salesterritory',
			'foreignKey' => 'PRODUCT_OFFER_ID'
		)
	);
}
?>