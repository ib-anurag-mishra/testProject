 Dear <?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>,\n\n
   Your patron account password for "<?php echo $library->getLibraryName($Patron['User']['library_id']); ?>" Library has been changed. Please find the login credentials as below:\n\n
   Email: <?php echo $Patron['User']['email']; ?>\n
   Password: <?php echo $password; ?>\n\n
   
   <?php echo Configure::read('App.base_url')."users/login"; ?> click here to login to the website.\n\n
   
 Thanks\n
 <?php echo Configure::read('App.name'); ?>
