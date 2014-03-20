 Dear
<?php echo $Patron['User']['first_name']. ' ' . $Patron['User']['last_name'] ?>
,\n\n Your patron account password has been reset. Please find your new
password as below:\n\n Email:
<?php echo $Patron['User']['email']; ?>
\n Password:
<?php echo $password; ?>
\n\n

<?php
$libraryDetails = $library->getLibraryDetails($Patron['User']['library_id']);
echo 'https://'.$libraryDetails['Library']['library_subdomain'].'.'.Configure::read('App.name').'/users/login';
?>
click here to login to the website.\n\n Thanks\n
<?php echo Configure::read('App.name'); ?>
