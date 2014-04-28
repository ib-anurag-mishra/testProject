<?php
/*
 File Name : siteconfig.php
File Description : Models page for the  site setting functionality.
Author : m68interactive
*/

class Siteconfig extends AppModel
{
	var $name = 'Siteconfig';
	
	public function fetchSiteconfigDataBySoption() {
		
		$options = array(
					'conditions' => array('`Siteconfig`.`soption`' => 'maintain_ldt')
				);
		
		return $this->find('first', $options);
	}
}
?>