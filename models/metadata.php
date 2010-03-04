<?php
 /*
 File Name : metadata.php
 File Description : Metadata Model
 Author : maycreate
 */
class Metadata extends AppModel {
	var $name = 'Metadata';
	var $useTable = 'METADATA';
	var $primaryKey = 'ProdId';
	
	 var $belonsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'ProdID'
		)
  );
	
}
