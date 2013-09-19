 <p>Dear <?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>,</p>
 <p>  Your patron account for "<?php echo $library->getLibraryName($Patron['User']['library_id']); ?>" Library has been created successfully. Please find the login credentials as below:</p>
 <p>  Email: <?php echo $Patron['User']['email']; ?><br />
   Password: <?php echo $password; ?></p>
   
 <p>Please copy the link and paste in the address bar to login to the site.</p> 
  <p> 
 <?php
 if($library_subdomain){
     echo 'https://'.$library_subdomain.'.'.Configure::read('App.name').'/users/login';
 }else{
    echo 'https://www.'.Configure::read('App.name').'/users/login';
 }
 ?>
  </p>
 
 
 
 
 
 
   
 <p>Thanks<br />
 <?php echo Configure::read('App.name'); ?></p>
