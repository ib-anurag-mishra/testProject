 <p>Dear <?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>,</p>
 <p>  Your patron account password has been reset. Please find your new password as below:</p>
 <p>  Email: <?php echo $Patron['User']['email']; ?><br />
   Password: <?php echo $password; ?></p>
   
 <p> <a href="<?php  $libraryDetails = $library->getLibraryDetails($Patron['User']['library_id']);
 echo 'https://'.$libraryDetails['Library']['library_subdomain'].'.'.Configure::read('App.name').'/users/login'; 
 ?>">Click Here</a> to login to the website.</p>
   
 <p>Thanks<br />
 <?php echo Configure::read('App.name'); ?></p>
