<?php
 /*
 File Name : library_purchase.php
 File Description : Models page for the  library purchase functionality.
 Author : maycreate
 */
class LibraryPurchase extends AppModel
{
    var $name = 'LibraryPurchase';
    var $actsAs = array('Multivalidatable');
    
    var $belongsTo = array(
      'Library' => array(
      'className' => 'Library',
      'foreignKey' => 'id'
      )
    );
    
    var $validationSets = array(
                          'library_step5' => array(
                           'purchased_order_num' => array(
                                                         'purchased_order_num-1' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please provide a purchase order number.', 'last' => true),
                                                         'purchased_order_num-2' => array('rule' => 'isUnique', 'allowEmpty' =>  false, 'message' => 'This purchase order number already exists in our database.')
                                                    ),
                           'purchased_tracks' => array(
                                                       'purchased_tracks-1' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please provide the total number of purchased tracks.', 'last' => true),
                                                       'purchased_tracks-2' => array('rule' => 'numeric', 'allowEmpty' =>  false, 'message' => 'Please provide the total number of purchased tracks as a numeric value.')
                                                 ),
                           'purchased_amount' => array(
                                                       'purchased_amount-1' => array('rule' => 'notEmpty', 'allowEmpty' =>  false, 'message' => 'Please provide the total amount for purchased tracks.', 'last' => true),
                                                       'purchased_amount-2' => array('rule' => 'numeric', 'allowEmpty' =>  false, 'message' => 'Please provide the total amount for purchased tracks as a numeric value.')
                                                 )
                          ),
                          'library_step5_edit' => array(
                           'purchased_order_num' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This purchase order number already exists in our database.'),
                           'purchased_tracks' => array('rule' => 'numeric', 'allowEmpty' =>  true, 'message' => 'Please provide the total number of purchased tracks as a numeric value.'),
                           'purchased_amount' => array('rule' => 'numeric', 'allowEmpty' =>  true, 'message' => 'Please provide the total amount for purchased tracks as a numeric value.')
                          )
    );
    
}
?>