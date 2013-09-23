<?php
 /*
 File Name : common.php
 File Description : Component page for all functionalities.
 Author : m68interactive
 */
 
Class CommonComponent extends Object
{

    /*
     * Function Name : callToAllFunctions
     * Function Description : This function is used to call all functions
     */
    
    function callToAllFunctions(){
        
    }
    
    /*
     * Function Name : getGenres
     * Function Description : This function is used to get all genres.
     */
    
    function getGenres(){

        $this->Genre->Behaviors->attach('Containable');
        $this->Genre->recursive = 2;
        $genreAll = $this->Genre->find('all',array(
                                'conditions' =>
                                        array('and' =>
                                                array(
                                                        array('Country.Territory' => $territory, "Genre.Genre NOT IN('Porn Groove')")
                                                )
                                        ),
                                'fields' => array(
                                                'Genre.Genre'
                                                ),
                                'contain' => array(
                                        'Country' => array(
                                                        'fields' => array(
                                                                        'Country.Territory'
                                                                )
                                                        ),
                                ),'group' => 'Genre.Genre'
                        ));

          $this->log("cache written for genre for $territory",'debug');      

          if( (count($genreAll) > 0) && ($genreAll !== false) )
          {
            Cache::delete("genre".$territory);
            Cache::write("genre".$territory, $genreAll);
            $this->log( "cache written for genre for $territory", "cache");
            echo "cache written for genre for $territory";
          }
          else
          {                                  

            Cache::write("genre".$territory, Cache::read("genre".$territory) );
            $this->log( "no data available for genre".$territory, "cache");
            echo "no data available for genre".$territory;
          }        
    }

}
?>