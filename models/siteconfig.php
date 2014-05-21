<?php
/*
 File Name : siteconfig.php
File Description : Models page for the  site setting functionality.
Author : m68interactive
*/

class Siteconfig extends AppModel
{
	var $name = 'Siteconfig';
	
	public function fetchSiteconfigDataBySoption( $soption ) {
		
		$options = array(
					'conditions' => array('`Siteconfig`.`soption`' => $soption )
				);
		
		return $this->find('first', $options);
	}
}
?>