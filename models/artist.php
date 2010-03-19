<?php
class Artist extends AppModel
{
    var $name = 'Artist';

    /*
    Function Name : insert
    Desc : save an artist imahe in the db
    */

    public function insert($data)
    {
        if($this->save($data))
        {
            return true;
        }else{
            return false;
        }
    }
    
    /*
    Function Name : getallartists
    Desc : lists all the artists for slideshow in the homepage
    */
    public function getallartists()
    {
    $getArtists = $this->find('all');
    return $getArtists;
    }
    
     /*
    Function Name : getartistdata
    Desc : 
    */

    public function getartistdata($id)
    {
        $getArtistData = $this->find('first', array('conditions' => array('id' => $id)));       
        return $getArtistData;
    }

    /*
    Function Name : del
    Desc : 
    */
    public function del($id)
    {
        if($this->delete($id))
        {
            return true;
        }else{
            return false;
        }
    }
    
}
?>