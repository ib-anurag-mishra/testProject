<?php
class dataencodeView extends View {

  function getTextEncode($text) {

    mb_internal_encoding("UTF-8");
    $text = @iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    return @iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  }



}

?>