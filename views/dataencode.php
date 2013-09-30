<?php
class dataencodeView extends View {

  function getTextEncode($text) {

    $text = @iconv(mb_detect_encoding($text), "WINDOWS-1252//TRANSLIT//IGNORE", $text);
    $text = @iconv(mb_detect_encoding($text), "ISO-8859-1//TRANSLIT//IGNORE", $text);
    return @iconv(mb_detect_encoding($text), "UTF-8//TRANSLIT//IGNORE", $text);
  }



}

?>