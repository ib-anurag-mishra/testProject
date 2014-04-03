<?php
/*
 File Name : video.php
 File Description : Models page for the  videos table.
 Author : m68interactive
 */

class QueueList extends AppModel
{
	var $name = 'QueueList';
	var $useTable = 'queue_lists';
    var $primaryKey = 'queue_id';
        
        var $hasMany = array(
		'QueueDetail' => array(
			'className' => 'QueueDetail',
			'foreignKey' => 'queue_id',
                        'type'  => 'INNER'
		)
	);
        
	var $belongsTo = array(
		'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'patron_id'
		)
	);        
}