<?php
/*
 File Name : Participant.php
 File Description : Models page for the  Participant table.
 Author : maycreate
 */

class Participant extends AppModel
{
  var $name = 'Participant';
  var $useTable = 'Participant';
  var $primaryKey = 'ProdID';
  var $actsAs = array('Containable');
  var $uses = array('Physicalproduct','Featuredartist','Artist','Productoffer');

 var $belongsTo = array(
		'Physicalproduct' => array(
			'className' => 'Physicalproduct',
			'foreignKey' => 'ProdID'			
		)
	); 
}
?>