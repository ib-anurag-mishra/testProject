<?php
/* Admin Fixture generated on: 2010-02-23 17:02:18 : 1266945138 */
class AdminFixture extends CakeTestFixture {
	var $name = 'Admin';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'type_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'first_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'last_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'username' => 'admin',
			'password' => 'e74c0908549755dd7e3d95622387d9000267d058',
			'type_id' => 1,
			'first_name' => 'Dibya',
			'last_name' => 'Das',
			'email' => 'dibyasaktidas@gmail.com'
		),
		array(
			'id' => 4,
			'username' => 'test',
			'password' => 'e74c0908549755dd7e3d95622387d9000267d058',
			'type_id' => 2,
			'first_name' => 'dibya',
			'last_name' => 'dibya',
			'email' => 'dibyasaktidas@gmail.com'
		),
	);
}
?>