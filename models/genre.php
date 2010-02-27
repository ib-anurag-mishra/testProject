<?php
 /*
 File Name : genre.php
 File Description : Genre page.
 Author : maycreate
 */
class Genre extends AppModel {
	var $name = 'Genre';
	var $useTable = 'Genre';
	
	var $belongsTo = array(
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdID'
		)
	);
}