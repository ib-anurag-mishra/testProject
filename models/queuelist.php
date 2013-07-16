<?
/*
 File Name : video.php
 File Description : Models page for the  videos table.
 Author : m68interactive
 */

class Queuelist extends AppModel
{
	var $name = 'Queuelist';
	var $useTable = 'Queuelists';
        var $primaryKey = 'Plid';
        
        var $hasMany = array(
		'QueuelistDetails' => array(
			'className' => 'QueuelistDetails',
			'foreignKey' => 'Plid',
                        'type'  => 'INNER'
		)
	);
        
	var $belongsTo = array(
		'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'patronID'
		)
	);        

  

  	
  
}