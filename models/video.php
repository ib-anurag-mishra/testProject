<?
/*
 File Name : video.php
 File Description : Models page for the  videos table.
 Author : m68interactive
 */

class Video extends AppModel
{
	var $name = 'Videos';
	var $useTable = 'video';
	var $primaryKey = 'ProdID';

	var $actsAs = array('Containable','Sphinx');
	var $uses = array('Featuredartist','Country');

	var $hasOne = array(
		'Participant' => array(
			'className' => 'Participant',
			'conditions' => array('Participant.Role' => 'Composer'),
			'foreignKey' => 'ProdID'			
		),	
		'Genre' => array(
			'className' => 'Genre',
			'foreignKey' => 'ProdID'
		),
		'Country' => array(
					'className' => 'Country',
					'foreignKey' => 'ProdID'
		),		
	);
	
	var $belongsTo = array(
		'Sample_Files' => array(
			'className' => 'Files',
			'foreignKey' => 'Sample_FileID'
		),
		'Full_Files' => array(
			'className' => 'Files',
			'foreignKey' => 'FullLength_FileID'
		)		
	);

  
  /*
    Function Name : getVideoData
    Desc : This would returna the video song data
  */
  function getVideoData($id , $provider) {
    
    $this->recursive = 2;
    $this->Behaviors->attach('Containable');
    $data = $this->find('all', array(
		'conditions'=>array('Videos.ProdID' => $id , 'Videos.provider_type' => $provider),
		'fields' => array(
			'Videos.ProdID',
			'Videos.ProductID',
			'Videos.Title',
			'Videos.SongTitle',
			'Videos.Artist',
			'Videos.ISRC'
		),
		'contain' => array(										
			'Full_Files' => array(
				'fields' => array(
					'Full_Files.CdnPath',
					'Full_Files.SaveAsName'
        ),                             
			)
    )
    ));
    return $data;
  }
  	
  
}