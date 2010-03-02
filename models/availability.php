<?php
 /*
 File Name : availability.php
 File Description : Availability model
 Author : maycreate
 */
class Availability extends AppModel {
	var $name = 'Availability';
	var $useTable = 'Availability';
        
        var $hasOne = array(
		'Metadata' => array(
			'className' => 'Metadata',
			'foreignKey' => 'ProdId'
		)
	);
}
