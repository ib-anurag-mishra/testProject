<?php
class Featuredartist extends AppModel
{
    var $name = 'Featuredartist';
    
    /*
    Function Name : insert
    Desc : updatesa admin user data
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
    Desc : gets all the artists
    */
    public function getallartists()
    {
        $getArtists = $this->find('all');
        return $getArtists;
    }
    
     /*
    Function Name : getartistdata
    Desc : gets data for the specified artist
    */
    public function getartistdata($id)
    {
       $getArtistData = $this->find('first', array('conditions' => array('Featuredartist.id' => $id)));
       return $getArtistData;
    }
   
    /*
    Function Name : del
    Desc : deletes a featured artist
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