<?php
/*
 File Name : Participant.php
 File Description : Models page for the  Participant table.
 Author : m68interactive
 */

class Participant extends AppModel
{
  var $name = 'Participant';  
  var $useTable = 'Participant';
  var $primaryKey = 'ProdID';
  var $actsAs = array('Containable');
  var $uses = array('Featuredartist','Artist','Song');

 var $belongsTo = array(
		'Song' => array(
			'className' => 'Song',
			'foreignKey' => 'ProdID'			
		)		
	); 
}
?>