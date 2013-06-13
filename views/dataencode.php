<?php
class dataencodeView extends View {

  function getTextEncode($text) {

    // approach1
    $text = iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
    return iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
  
    //approach2
   // return iconv('Windows-1252', 'UTF-8', $text);

    //approach3
    //return iconv('ISO-8859-15', 'UTF-8', $text);

    //approach3
   // return iconv('ISO-8859-1', 'UTF-8', $text);

  }



}

?>
