<?php
class dataencodeView extends View {

  function getTextEncode($text) {

//    $text = @iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
//    return @iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
      
      return mb_convert_encoding($text, "HTML-ENTITIES", "UTF-8"); 
  }



}

?>