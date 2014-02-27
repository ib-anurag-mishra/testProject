<?php
/*
 File Name : library.php
 File Descr : Models page for the  libraries functionality.
 Author : m68interactive
*/
 
class Library extends AppModel
{
    var $name = 'Library';
    
    var $actsAs = array('Multivalidatable', 'Containable');
    
    var $belongsTo = array(
      'User' => array(
      'className' => 'User',
      'foreignKey' => 'library_admin_id',
      'condition' => 'User.type_id = 4'
      )
    );
    
    var $hasMany = array(
      'LibraryPurchase' => array(
          'className'    => 'LibraryPurchase',
          'dependent'    => false,
          'foreignKey' => 'library_id'
      )
    );
    
    var $validate = array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  true, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.'),
      'library_download_limit' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Download Limit.'),
      'library_download_type' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Download Type.'),
      'library_user_download_limit' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library User\'s Download Limit.')
    );
    
    var $validationSets = array(
     'library_step1' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
	  'library_domain_name[]' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  true, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_referral_url' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  false, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  true, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_user_account' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  true, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_innovative' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_mdlogin' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_mndlogin' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_soap' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_soap_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library SOAP URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	
     'library_step1_curl' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_curl_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Curl URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	 
     'library_step1_innovative_https' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	 
     'library_step1_innovative_var' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
    'library_step1_capita' => array(
          'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
          'library_subdomain' => array(
              'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' => true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
              'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' => true, 'message' => 'This Library Subdomain is already taken please try another.')
          ),
          'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
          'library_domain_name' => array('rule' => 'url', 'allowEmpty' => true, 'message' => 'Please provide a valid Library Domain Name.'),
          'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' => false, 'message' => 'Please provide a Library Authentication URL.'),
          'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
          'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
          'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
      ),
    'library_step1_symws' => array(
          'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
          'library_subdomain' => array(
              'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' => true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
              'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' => true, 'message' => 'This Library Subdomain is already taken please try another.')
          ),
          'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
          'library_domain_name' => array('rule' => 'url', 'allowEmpty' => true, 'message' => 'Please provide a valid Library Domain Name.'),
          'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' => false, 'message' => 'Please provide a Library Authentication URL.'),
          'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
          'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
          'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
      ),        
     'library_step1_innovative_var_name' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_innovative_var_https_name' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),		 
     'library_step1_innovative_var_https' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
     'library_step1_innovative_var_https_wo_pin' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_authentication_url' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a Library Authentication URL.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	 
	 'library_step1_sip2' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_host_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide a Library Host Name.'),
	  'library_port_no' => array('rule' => array('custom', '/\S+/'),  'message' => 'Please provide a Library Port No.'),
	  'library_sip_login' => array('allowEmpty' => true),
	  'library_sip_password' => array('allowEmpty' => true),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
	 'library_step1_sip2_wo_pin' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_host_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide a Library Host Name.'),
	  'library_port_no' => array('rule' => array('custom', '/\S+/'),  'message' => 'Please provide a Library Port No.'),
	  'library_sip_login' => array('allowEmpty' => true),
	  'library_sip_password' => array('allowEmpty' => true),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
	 'library_step1_sip2_var' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_host_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide a Library Host Name.'),
	  'library_port_no' => array('rule' => array('custom', '/\S+/'),  'message' => 'Please provide a Library Port No.'),
	  'library_sip_login' => array('allowEmpty' => true),
	  'library_sip_password' => array('allowEmpty' => true),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),
	 'library_step1_sip2_var_wo_pin' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_domain_name' => array('rule' => 'url', 'allowEmpty' =>  true, 'message' => 'Please provide a valid Library Domain Name.'),
      'library_host_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide a Library Host Name.'),
	  'library_port_no' => array('rule' => array('custom', '/\S+/'),  'message' => 'Please provide a Library Port No.'),
	  'library_sip_login' => array('allowEmpty' => true),
	  'library_sip_password' => array('allowEmpty' => true),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	 
     'library_step1_ezproxy' => array(
      'library_name' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Name.'),
	   'library_subdomain' => array(
                                     'library_subdomain-1' => array('rule' => array('custom', '/^[a-zA-Z0-9]*$/'), 'allowEmpty' =>  true, 'message' => 'Please use only alphanumeric characters.', 'last' => true),
                                     'library_subdomain-2' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This Library Subdomain is already taken please try another.')
									),
      'library_authentication_method' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Authentication Method.'),
      'library_ezproxy_referral' => array('rule' => 'url', 'allowEmpty' =>  false, 'message' => 'Please provide a valid EZProxy Referral URL.'),
      'library_ezproxy_name' => array('rule' => 'isUnique', 'allowEmpty' =>  true, 'message' => 'This EZProxy Library Name already exists in our database.'),
	'library_ezproxy_secret' => array('rule' => array('custom', '/\S+/'), 'allowEmpty' =>  false, 'message' => 'Please provide a EZProxy Secret.'),
      'library_contact_fname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact First Name.'),
      'library_contact_lname' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please provide Library Contact Last Name.'),
      'library_contact_email' => array('rule' => 'email', 'message' => 'Please enter a valid email address for Library Contact Email.')
     ),	 
	 
     'library_step3' => array(
       'library_download_limit' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Download Limit.'),
       'library_download_type' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library Download Type.')
      ),
     'library_step4' => array(
       'library_user_download_limit' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library User\'s Download Limit.')
      ),
     'library_step_date' => array(
       'library_contract_start_date' => array(
        'library_contract_start_dateRule-1' => array('rule' => array('minLength', 1), 'allowEmpty' =>  false, 'message' => 'Please select a Library Contract Start Date.', 'last' => true),
        'library_contract_start_dateRule-2' => array('rule' => array('date', 'ymd'), 'allowEmpty' =>  false, 'message' => 'Enter the Library Contract Start Date in a valid date format.') 
       )
      )
    );
    
    /*
     Function Name : getalllibraries
     Desc : gets all the library details from the db
    */
    function getalllibraries() {
        $getLibraries = $this->find('all');
        return $getLibraries;
    }
    
    /*
     Function Name :  getlibrarydata
     Desc : gets the details for a library
    */
    function getlibrarydata($id) {
       $getLibraryData = $this->find('first', array('conditions' => array('Library.id' => $id)));
       return $getLibraryData;
    }
    
    /*
     Function Name : getalllibraries
     Desc : get library authentication type
    */
    function getAuthenticationType($id) {
        $libraryInstance = ClassRegistry::init('Library');
        $libraryInstance->recursive = -1;
        $libraryDetails = $libraryInstance->find('first', array('conditions' => array('library_admin_id' => $id), 'fields' => 'library_authentication_method'));
        return $libraryDetails['Library']['library_authentication_method'];
    }
    
    /*
    Function Name : checkusername
    Desc : Checks the presence of username
    */
    function checkusername($username,$id = ' ') {
        if($id == ' '){
          $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username)));
        }else{
          $getUsernameCount = $this->find('count', array('conditions' => array('username' => $username,'Library.id !=' => $id)));
        }
        if($getUsernameCount == 0){
             return 1;
        }else{
             return 0;
        }
    }
    
    /*
    Function Name : arrayremovekey
    Desc : removes the elements from an array based on keys
    */
    function arrayremovekey() {
        $args = func_get_args();
        $arr = $args[0];
        $keys = array_slice($args,1);     
        foreach($arr as $k=>$v){
            if(in_array($k, $keys))
                unset($arr[$k]);
        }
       return $arr;
    }
	
    /*
    Function Name : paginateCount
    Desc : To Fix the issue with pagination cache
    */	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	    $results = $this->find('count', compact('conditions','recursive', 'group'));
	    return $results;
	}	
}
?>