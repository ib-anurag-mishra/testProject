<?php
/* Session Fixture generated on: 2010-02-23 17:02:50 : 1266945170 */
class SessionFixture extends CakeTestFixture {
	var $name = 'Session';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'key' => 'primary'),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 'e6f003edee0c3428e388fed1df6736c6',
			'data' => 'Config|a:3:{s:9:\"userAgent\";s:32:\"8536c3cc34a54b6f5c6601c90ddfbba3\";s:4:\"time\";i:1266949794;s:7:\"timeout\";i:10;}username|s:3:\"abc\";Message|a:0:{}',
			'expires' => 1266949794
		),
	);
}
?>