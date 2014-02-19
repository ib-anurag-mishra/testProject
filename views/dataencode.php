<?php

class dataencodeView extends View
{

    function getTextEncode($text)
    {   
        // Function used only in Front End
        $text = @iconv(mb_detect_encoding($text), "WINDOWS-1252//IGNORE", $text);
        return @iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $text);
    }

    /**
     *
     * @Utf8_decode
     *
     * @Replace accented chars with latin
     *
     * @param string $string The string to convert
     *
     * @return string The corrected string
     *
     */
    function decode_utf8($string)
    {
        $accented = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ă', 'Ą',
            'Ç', 'Ć', 'Č', 'Œ',
            'Ď', 'Đ',
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ă', 'ą',
            'ç', 'ć', 'č', 'œ',
            'ď', 'đ',
            'È', 'É', 'Ê', 'Ë', 'Ę', 'Ě',
            'Ğ',
            'Ì', 'Í', 'Î', 'Ï', 'İ',
            'Ĺ', 'Ľ', 'Ł',
            'è', 'é', 'ê', 'ë', 'ę', 'ě',
            'ğ',
            'ì', 'í', 'î', 'ï', 'ı',
            'ĺ', 'ľ', 'ł',
            'Ñ', 'Ń', 'Ň',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ő',
            'Ŕ', 'Ř',
            'Ś', 'Ş', 'Š',
            'ñ', 'ń', 'ň',
            'ò', 'ó', 'ô', 'ö', 'ø', 'ő',
            'ŕ', 'ř',
            'ś', 'ş', 'š',
            'Ţ', 'Ť',
            'Ù', 'Ú', 'Û', 'Ų', 'Ü', 'Ů', 'Ű',
            'Ý', 'ß',
            'Ź', 'Ż', 'Ž',
            'ţ', 'ť',
            'ù', 'ú', 'û', 'ų', 'ü', 'ů', 'ű',
            'ý', 'ÿ',
            'ź', 'ż', 'ž',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'р',
            'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        );

        $replace = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A',
            'C', 'C', 'C', 'CE',
            'D', 'D',
            'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a',
            'c', 'c', 'c', 'ce',
            'd', 'd',
            'E', 'E', 'E', 'E', 'E', 'E',
            'G',
            'I', 'I', 'I', 'I', 'I',
            'L', 'L', 'L',
            'e', 'e', 'e', 'e', 'e', 'e',
            'g',
            'i', 'i', 'i', 'i', 'i',
            'l', 'l', 'l',
            'N', 'N', 'N',
            'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'R', 'R',
            'S', 'S', 'S',
            'n', 'n', 'n',
            'o', 'o', 'o', 'o', 'o', 'o',
            'r', 'r',
            's', 's', 's',
            'T', 'T',
            'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y',
            'Z', 'Z', 'Z',
            't', 't',
            'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y',
            'z', 'z', 'z',
            'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P',
            'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p',
            'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R',
            'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r'
        );

        return str_replace($accented, $replace, $string);
    }

    function getAdminTextEncode($text)                    // Function used only in Admin
    {
        //return $this->decode_utf8($text);
        return $this->decode_utf8($this->getTextEncode($text));
    }

    function getValidText($text)                    // Replace Single, Double Quotes, & with HTML entities in Text
    {
        return htmlentities($this->getAdminTextEncode($text));
    }

}

?>