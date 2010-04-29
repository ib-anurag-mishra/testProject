<?php
Class SuggestionSongComponent extends Object
{    
    var $components = array('Session');

    function readSuggestionSongsXML() {
        if(file_exists(WWW_ROOT."/suggestion_xml/suggestion_songs.xml"))
	{
		$rsp = file_get_contents(WWW_ROOT."/suggestion_xml/suggestion_songs.xml");
		$object = simplexml_load_string($rsp);
		$array = $this->object_to_array($object);
		foreach($array as $arr) {
		    shuffle($arr);
		    return $arr;
		}
	}
    }
    
    function object_to_array($mixed) {
	if(is_object($mixed))
	    $mixed = (array) $mixed;
	if(is_array($mixed)) {
	    $new = array();
	    foreach($mixed as $key => $val) {
		$new[$key] = $this->object_to_array($val);
	    }
	}
	else $new = $mixed;
	return $new;
    }
}
?>