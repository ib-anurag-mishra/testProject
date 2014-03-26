<?php
/**
  File Name : header.php
  File Description : View page for header of the application
  Author : m68interactive
 * */
/**
 * Header file for home page
 * */
/* if($this->Session->read('library') && $this->Session->read('library') != '')
  {
  $libraryInfo = $library->getLibraryDetails($this->Session->read('library'));
  $downloadCount = $download->getDownloadDetails($this->Session->read('library'),$this->Session->read('patron'));
  ?>
  <div id="header">
  <meta http-equiv="X-UA-Compatible" content="IE=8" />
  <?php
  if($libraryInfo['Library']['library_image_name'] != "") {
  ?>
  <div id="lib_image">
  <?php
  if($libraryInfo['Library']['library_home_url'] != "") {
  ?>
  <a href="<?php echo $libraryInfo['Library']['library_home_url']; ?>" target="_blank"><img src="<?php echo $cdnPath; ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>"></a>
  <?php
  }else{
  ?>
  <img src="<?php echo $cdnPath; ?>libraryimg/<?php echo $libraryInfo['Library']['library_image_name']; ?>" alt="<?php echo $libraryInfo['Library']['library_name']; ?>" title="<?php echo $libraryInfo['Library']['library_name']; ?>">
  <?php
  }
  ?>
  </div>
  <?php
  }
  ?>
  <?php
  if(!$libraryInfo['Library']['show_library_name']) {
  ?>
  <div id="lib_name"><?php echo $libraryInfo['Library']['library_name']; ?></div>
  <?php
  }
  ?>
  <div id="header_right">
  <ul>
  <li>
  <div class="footerlinks">
  <div class="headerLink" ><?php __('Weekly Downloads'); ?> <span id="downloads_used"><?php echo $downloadCount; ?></span>/<?php echo $libraryInfo['Library']['library_user_download_limit']; ?></div>
  <div class="headerLink" ><?php
  echo $html->image("question.png", array("alt" => "Download Limits", "width" => 12, "height" => 14, "id" => 'qtip', "title" => $page->getPageContent('limits'))); ?></div>
  <div class="navbarheader" >|</div>
  <div class="headerLink" ><?php echo $html->link(__('FAQ', true), array('controller' => 'questions', 'action' => 'index')); ?></div>
  <div class="navbarheader" >|</div>

  <?php if($libraryInfo['Library']['library_unlimited'] != 1){?>
  <div class="headerLink" ><?php echo $html->link(__('My Wishlist', true), array('controller' => 'homes', 'action' => 'my_wishlist')); ?></div>
  <div class="navbarheader" >|</div>
  <?php } ?>
  <div class="headerLink" ><?php echo $html->link(__('Recent Downloads', true), array('controller' => 'homes', 'action' => 'my_history')); ?></div>
  <?php if ($this->Session->read('Auth.User')) { ?>
  <div class="navbarheader" >|</div>
  <div class="headerLink" ><?php echo $html->link(__('My Account', true), array('controller' => 'users', 'action' => 'my_account'));?></div>
  <?php
  }?>
  <div class="navbarheader" >|</div>
  <div class="headerLink" ><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));?></div>
  </div>
  </li>
  <li><div  style="float:right;margin-left:15%;"><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></div></li>
  </ul>
  </div>
  </div>
  <?php
  }
  else {
  ?>
  <div id="header">
  <div id="lib_name">FreegalMusic.Com</div>
  <div id="header_right">
  <ul>
  <li></li>
  <li><img src="<?php echo $this->webroot; ?>img/freegal_logo.png"></li>
  </ul>
  </div>
  </div>
  <?php
  } */
?>
