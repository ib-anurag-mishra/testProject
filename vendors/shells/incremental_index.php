<?php

App::import('Core', 'HttpSocket');

class IncrementalIndexShell extends Shell {

    var $songsIndexUrl =  "http://192.168.100.24:8080/solr/freegalmusic/dataimport";
    
    var $videosIndexUrl =  "http://192.168.100.24:8080/solr/freegalmusic/dataimport";
    
    var $query = "command=delta-import&clean=false";
    
    var $statusQuery = "command=status";
    
    function main(){
        
        $httpSocket = new HttpSocket();
        
        $response = $httpSocket->get($this->songsIndexUrl,$this->query);
        
        echo $response;
        
        // $response = $httpSocket->get($this->videosIndexUrl,$this->query);
    }
    
    

}
