<?php
class dataencodeView extends View {

  function getTextEncode($text) {

    $text = @iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    $text = @iconv(mb_detect_encoding($text), "US-ASCII//IGNORE", $text);
    $text = @iconv(mb_detect_encoding($text), "ISO-8859-1//IGNORE", $text);
    $text = @iconv(mb_detect_encoding($text), "UTF-16//IGNORE", $text);
    return @iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  }



}

?>