<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en" />
<title>Freegal Music</title>
<?php
    echo $html->css('default');
    echo $html->css('colorbox');    
    echo $javascript->link('jquery-1');
    echo $javascript->link('jquery');        
    echo $javascript->link('admin_functions');
    echo $javascript->link('jquery-1.3.2.min');
    echo $javascript->link('jquery.colorbox'); 
?>               

</head>
<body>
  <div id="witti">
  
    <div id="header">
      <a href="#" class="logo"><span>Admin Area</span></a>
      <p class="userinfo"><strong></strong> <?php echo "<b>welcome : ".$username ."</b>"; ?>|<?php echo $html->link('Logout', array('controller'=>'Admins','action'=>'logout'));?></p>
      <div class="cleaner"></div>
    </div><!-- header -->
    
    <div id="main">
      <div id="menu">
        <ul>
          <li><a href="#" class="active">NAVIGATION MENU</a></li>
          <li><a href="#">User</a>
            <ul>
              <li><?php echo $html->link('Add User', array('controller' => 'admin_homes','action'=>'userform'));?></li>
              <li><?php echo $html->link('Manage User', array('controller' => 'admin_homes','action'=>'manageuser'));?></li>
            </ul>
          </li>
          <li><a href="#">Artist Management</a>
           <ul>
              <li><?php echo $html->link('Add Featured Artist', array('controller' => 'Artists','action'=>'artistform'));?></li>
              <li><?php echo $html->link('Manage Featured Artist', array('controller' => 'Artists','action'=>'managefeaturedartist'));?></li>
               <li><?php echo $html->link('Create Artist', array('controller' => 'Artists','action'=>'createartist'));?></li>  
              <li><?php echo $html->link('Artist SlideShow', array('controller' => 'Artists','action'=>'manageartist'));?></li>  
              
            </ul>
           </li>
          <li><a href="#">Finance</a></li>
         <li><a href="#">Library Management</a>
             <ul>
              <li><?php echo $html->link('Add Library', array('controller' => 'Libraries','action'=>'libraryform'));?></li>
              <li><?php echo $html->link('Manage Library', array('controller' => 'Libraries','action'=>'managelibrary'));?></li>              
            </ul>
          </li>
          <li><a href="#">Reports</a></li>
        </ul>
      </div><!-- content -->
      <div id="content">
          <!-- Main contents start here -->
          <?php echo $content_for_layout; ?>
          <!-- Main contents end here -->
      </div><!-- content -->
      <div class="cleaner"></div>
    </div><!-- main -->
    
    <div id="footer">
      &copy Copyright Freegal Music
    </div><!-- footer -->
  
  </div><!-- witti -->
</body>
</html>
