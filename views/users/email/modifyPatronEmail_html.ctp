 <p>Dear <?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>,</p>
 <p>  Your patron account password for "<?php echo $library->getLibraryName($Patron['User']['library_id']); ?>" Libray has been changed. Please find the login credentials as below:</p>
 <p>  Email: <?php echo $Patron['User']['email']; ?><br />
   Password: <?php echo $password; ?></p>
   
 <p> <a href="<?php echo $webroot.'users/login'; ?>">Click Here</a> to login to the website.</p>
   
 <p>Thanks<br />
 FreegalMusic.com</p>
