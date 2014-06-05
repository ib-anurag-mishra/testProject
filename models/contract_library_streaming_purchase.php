<?php
/*
 File Name : library_purchase.php
 File Description : Models page for the  library purchase functionality.
 Author : m68interactive
*/
 
class ContractLibraryStreamingPurchase extends AppModel
{
    var $name = 'ContractLibraryStreamingPurchase';
    public $useTable = 'contract_library_streaming_purchases';
    
    var $belongsTo = array(
      'LibraryPurchasesStreaming' => array(
      'className' => 'LibraryPurchasesStreaming',
      'foreignKey' => 'id_library_purchases_streaming'
      )
    );
}