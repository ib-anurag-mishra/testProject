<?php
class AppView extends View { 

  function getTextEncode($text) {
    echo '<br />in<br />';
    $text = iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    return iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  }

    
    
}
?>