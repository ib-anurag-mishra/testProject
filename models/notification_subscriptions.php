<?php
/*
 File Name : libraries_timezone.php
File Description : Models page for the libraries timezone functionality.
Author : m68interactive
*/

class NotificationSubscriptions extends AppModel {

	var $name = 'NotificationSubscriptions';
	var $useTable = 'notification_subscriptions';
	var $primaryKey = 'id';
	var $validate = array(
			'email' => array('rule' => 'email', 'message' => 'Please provide valid email address.')
	);
}