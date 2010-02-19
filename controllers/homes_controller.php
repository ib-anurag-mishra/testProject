<?php
/* File Name: homes_controller.php
   File Description: Displays the home page for each patron 
   Author: Maycreate
*/   

class HomesController extends AppController
{
    var $name = 'Homes';
    var $helpers = array('Html','Ajax','Javascript','Form' );
    var $components = array('RequestHandler');
    var $uses = array('Home','Physicalproduct','Featuredartist','Artist');
    
    function index()
    {
        //This process to fetch random songs for suggestion list
        $homeObj = new Home();
        $randomSongs = $homeObj->getSongs();
        $this->set('songs',$randomSongs);
        //This process to fetch distinct artists for artist list
        $distinctArtistObj = new Physicalproduct();
        $distinctArtists = $distinctArtistObj->getallartist();       
        $this->set('distinctArtists',$distinctArtists);
        //This process is to fetch all the featured artist images for featured artist slideshow
        $featuredArtistObj = new Featuredartist();
        $featuredArtists = $featuredArtistObj->getallartists();       
        $this->set('featuredArtists',$featuredArtists);        
        $this->layout = 'home';
        //This process is to fetch all the selected artist images for artist slideshow
        $artistObj = new Artist();
        $artists = $artistObj->getallartists();        
        $this->set('artists',$artists);        
        $this->layout = 'home';
    }
}
?>