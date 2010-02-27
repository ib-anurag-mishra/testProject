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
	
	var $hasMany = array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdId'
		)
	);
	
}
