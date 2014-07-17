<?php
 /*
 File Name : genre.php
 File Description : Models page for the  Genre table.
 Author : m68interactive
*/
 
class Genre extends AppModel {
	var $name = 'Genre';
	var $useTable = 'Genre';
	var $uses = array('Featuredartist','Artist');
	var $primaryKey = 'ProdId';
  
	var $belongsTo = array(
		'Download' => array(
		    'className'    => 'Download',
		    'foreignKey' => 'ProdID'
		)
	);
	
	var $hasMany = array(
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		)
	);
	
	var $hasOne = array(
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'ProdID'
		),	
		
		'Songs' => array(
			'className' => 'Song',
			'foreignKey' => 'Genre'
		),	
		
	);
        
     /*
     * Function Name : getGenres
     * Function Description : This function is used to get all genres.
     */

    function getGenres($territory)
    {
        set_time_limit(0);

        $this->unBindModel(array('belongsTo' => array('Download'), 'hasOne' => array('Song', 'Country')));
        
        $this->recursive = 2;
        $this->Behaviors->attach('Containable');        
        
        $options = array(
				'fields' => array( 'Genre.expected_genre', 'Country.Territory'),
				'conditions' => array('and' => array( array('Country.Territory' => $territory,'Country.DownloadStatus' => 1, "Genre.Genre NOT IN('Porn Groove')"))),
				'joins' => array(
						array(
								'table' => strtolower($territory) . '_countries',
								'alias' => 'Country',
								'type' 	=> 'LEFT',
								'conditions' => array('Genre.ProdID = Country.ProdID')
						)						
                                            ),
                                 'group' => array('Genre.Genre')
		);
        
        
        $genreAll = $this->find('all', $options);
        
        $this->log("Each Genre Artist value checked finished for $territory", "genreLogs");      
        
        $combine_genre  =   array();
        
        if ((count($genreAll) > 0) && ($genreAll !== false))
        {                
            for($count=0; $count<count($genreAll);$count++)
            {                               
                array_push($combine_genre, str_replace("\\", "", $genreAll[$count]['Genre']['expected_genre']));
            }
            $combine_genre  = array_unique($combine_genre);
            sort($combine_genre);
            
            Cache::write("genre" . $territory, $combine_genre,'GenreCache');
            $this->log("cache written for genre for $territory", "cache");

        }      
        
        return $combine_genre;
         
    }
	
}
?>