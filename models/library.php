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
     'library_stream_step4' => array(
       'library_user_download_limit' => array('rule' => array('custom', '/\S+/'), 'message' => 'Please select a Library User\'s Download Limit.'),  
       'library_streaming_hours' => array('rule' => array('custom', '/[1-9]\d*/'), 'message' => 'Please select a Library User\'s streaming Limit.')
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
        
    /*
    Function Name : getLibHavingLessThan100Downloads
    Desc : Get Library having less than 100 downloads
    */	
        
        function getLibHavingLessThan100Downloads(){ 
            
         $this->unBindModel(array('belongsTo' => array('User'), 'hasMany' => array('LibraryPurchase')));   
            
         return $this->find('all', array('conditions' => 
                                                    array('library_available_downloads <= 100',
                                                          'library_status' => 'active',
                                                         ),
                                        'fields' => array(
                                                            'Library.id',
                                                            'Library.library_name',
                                                            'Library.library_available_downloads',
                                                            'Library.library_status',
                                                        ),
                                         'order' => array('Library.library_available_downloads ASC')
                                       )                                        
                                        
                          );
    }
    
    
    
        /*
	Function Name : checkLibraryStreamingContract
	Desc : function used for checking library information for streaming
        *  and if contract date ends then turned off the streaming status 
	*	 
	*
	* @return void
	*/
	function updateLibraryStreamingStatus() {

            $selectedLibraryInfo = array();
            $libraryPurchasesStreamingInstance = ClassRegistry::init('LibraryPurchasesStreaming');
            $contractLibraryStreamingPurchase = ClassRegistry::init('ContractLibraryStreamingPurchase');
            $this->recursive = -1;
            $results = $this->find('all',array('conditions' => array('library_type = "2"','library_status'=>'active'),'fields' => array('id','library_type','library_name')));
           
            if(!empty($results)) {
                $libraryPurchasesStreamingInstance->recursive = -1;
                //fetch last records id from library streaming purchas for fetching contract dates
                foreach($results as $libArray) {                   
                   
                    $libPurchaseStreamingArr = $libraryPurchasesStreamingInstance->find('first',
                       array(
                        'conditions' =>array('library_id="'.$libArray['Library']['id'].'"'),
                        'fields' => array('id'),
                        'order' => array('id desc'),
                        'limit' => 1
                    ));
                     
                    //fetch streaming contract date end
                    if(!empty($libPurchaseStreamingArr)) {
                        foreach($libPurchaseStreamingArr as $libPurchaseStreamingValue) {
                                                     
                            $libContractStreamingInfo = $contractLibraryStreamingPurchase->find('first',
                               array(
                                'conditions' =>array('id_library_purchases_streaming="'.$libPurchaseStreamingValue['id'].'"'),
                                'fields' => array('library_contract_end_date')
                            ));
                            
                           
                            //check the library contract dates
                            if(isset($libContractStreamingInfo['ContractLibraryStreamingPurchase']['library_contract_end_date']) &&
                                    ($libContractStreamingInfo['ContractLibraryStreamingPurchase']['library_contract_end_date'] != '0000-00-00')){                                    
                                
                                $currDate = strtotime(date("Y-m-d"));
                                $contractEndDate = strtotime($libContractStreamingInfo['ContractLibraryStreamingPurchase']['library_contract_end_date']);

                                //check if library streaming contract end
                                if($contractEndDate < $currDate) {
                                    
                                    $updateArr = Array();
                                    $updateArr['id'] = $libArray['Library']['id'];
                                    $updateArr['library_type'] = 1;                                    

                                    $this->setDataSource('master');
                                    
                                    //update the date and reset the consumed time as the day start
                                    if($this->save($updateArr)){
                                    //if( 1 ) {
                                    
                                        $selectedLibraryInfo[$libArray['Library']['id']]['lib_id'] = $libArray['Library']['id'];
                                        $selectedLibraryInfo[$libArray['Library']['id']]['lib_name'] = $libArray['Library']['library_name'];
                                        $selectedLibraryInfo[$libArray['Library']['id']]['contract_end_date'] = $libContractStreamingInfo['ContractLibraryStreamingPurchase']['library_contract_end_date'];                                                                               
                                    }
                                    
                                    $this->setDataSource('default');
                                    
                                }                                                                     
                            }
                        }
                    }
                }
            }
            
            if(!empty($selectedLibraryInfo)){
                $this->sendStreamingStatusChangeAlert($selectedLibraryInfo);
            }
        }
        
    
       /*
	Function Name : sendStreamingStatusChangeAlert
	Desc : send email alert to all responsible
	*	 
	*
	* @return void
	*/
	function sendStreamingStatusChangeAlert($selectedLibraryInfo) {           
   
            
            $emailTemplate = 'Hi'.'\n\n';
            $emailTemplate .= 'This is the automated email contain list of libraries which streaming contract end today.';
            $emailTemplate .= 'We have turned off streaming status of these libraries.'.'\n';            
            $emailTemplate .='Library ID'.'\t'.'Library Name'.'\t'.'Streaming Contract End Date'.'\n';            
            
            foreach($selectedLibraryInfo as $key => $libInfo) {            
                
                $emailTemplate .= $libInfo['lib_id'] .'\t';
                $emailTemplate .= $libInfo['lib_name'] .'\t';
                $emailTemplate .= $libInfo['contract_end_date'] .'\t';
                $emailTemplate .= '\n';
            }        
            
            $emailTemplate .= '\n\n';
            $emailTemplate .= 'Thanks'.'\n';
            $emailTemplate .= 'FreegalMusic'.'\n\n';
           
            //$to = "tech@libraryideas.com";
            $to = "libraryideas@infobeans.com,tech@libraryideas.com";
            $subject = "FreegalMusic - CRON job for streaming status turn off if contract over.";

           

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <no-reply@freegalmusic.com>' . "\r\n";
            //$headers .= 'Cc: libraryideas@infobeans.com' . "\r\n";
            $this->sendNormalEmails($to,$subject,$emailTemplate,$headers);
            
            /*            
            $this->Email->delivery = 'debug';            
            $this->Email->to = 'narendra.nagesh@infobeans.com';
            $this->Email->from = Configure::read('App.adminEmail');
            $this->Email->fromName = Configure::read('App.fromName');
            $this->Email->subject = 'FreegalMusic - Streaming status turned off';
            $this->Email->smtpHostNames = Configure::read('App.SMTP');
            $this->Email->smtpAuth = Configure::read('App.SMTP_AUTH');
            $this->Email->smtpUserName = Configure::read('App.SMTP_USERNAME');
            $this->Email->smtpPassword = Configure::read('App.SMTP_PASSWORD');
            $result = $this->Email->send($emailTemplate);
            */
             
        }
    
}