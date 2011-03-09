<?php
/*
 File Name : library_purchase.php
 File Description : Models page for the  library purchase functionality.
 Author : maycreate
*/
 
class ContractLibraryPurchase extends AppModel
{
    var $name = 'ContractLibraryPurchase';
    
    var $belongsTo = array(
      'LibraryPurchase' => array(
      'className' => 'LibraryPurchase',
      'foreignKey' => 'id_contract'
      )
    );
}
?>