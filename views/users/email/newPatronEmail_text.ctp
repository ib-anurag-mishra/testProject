 Dear <?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>,\n\n
   Your patron account for "<?php echo $library->getLibraryName($Patron['User']['library_id']); ?>" Library has been created successfully. Please find the login credentials as below:\n\n
   Email: <?php echo $Patron['User']['email']; ?>\n
   Password: <?php echo $password; ?>\n\n
   
   Please copy the link and paste in the address bar to login to the site.\n
   <?php echo Configure::read('App.base_url')."users/login"; ?>\n\n
   
 Thanks\n
 <?php echo Configure::read('App.name'); ?>
