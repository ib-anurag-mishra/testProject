<?php
   header('Content-Type: image/jpeg');
   include('SimpleImage.php');
   $image = new SimpleImage();
   $image->load('100042.jpg');
   $image->resizeToWidth(50);
   $image->output();
?>