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
        $this->set('songs',$this->Home->getSongs());
        $this->set('distinctArtists', $this->Physicalproduct->getallartist());
        $this->set('featuredArtists', $this->Featuredartist->getallartists());
        $this->set('newArtists', $this->Newartist->getallnewartists());
        $this->set('artists', $this->Artist->getallartists());
        $this->layout = 'home';
    }
}
?>