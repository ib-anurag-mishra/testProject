<?php
/* Admintype Fixture generated on: 2010-02-23 17:02:20 : 1266945140 */
class AdmintypeFixture extends CakeTestFixture {
	var $name = 'Admintype';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'type' => 'Super Admin'
		),
		array(
			'id' => 2,
			'type' => 'Finance'
		),
		array(
			'id' => 3,
			'type' => 'Content Editor'
		),
	);
}
?>