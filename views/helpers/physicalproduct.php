<?php
/*
	 File Name : physicalproduct.php
	 File Description : helper file for getting physicalproduct detail
	 Author : m68interactive
 */
class PhysicalproductHelper extends AppHelper {
    var $uses = array('Physicalproduct');
    
    function getDownloadData($id) {
        $physicalproductInstance = ClassRegistry::init('Physicalproduct');
        $physicalproductDetails = $physicalproductInstance->find('all',array('conditions' =>
					  array('Physicalproduct.ProdID' => $id	),
						'fields' => array(
							'Physicalproduct.ProdID'							
							),
						'contain' => array(						
						'Audio' => array(
							'fields' => array(
								'Audio.FileID',
								'Audio.Duration'                                                    
								),
							'Files' => array(
							'fields' => array(
								'Files.CdnPath' ,
								'Files.SaveAsName'
								)
							)
							)                                  
						)
						/*,
                                                 'group' => 'Physicalproduct.ReferenceID'*/
					  ));
        return  $physicalproductDetails;
    }
}

?>