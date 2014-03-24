<?

/*
  File Name : song.php
  File Description : Models page for the  Songs table.
  Author : m68interactive
 */

class Song extends AppModel
{

    var $name = 'Song';
    var $useTable = 'Songs';
    var $primaryKey = 'ProdID';
    var $actsAs = array('Containable', 'Sphinx');
    var $uses = array('Featuredartist', 'Country');
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
      Function Name : getallartist
      Desc : gets the list of all the artists
     */

    function getallartist()
    {
        $this->recursive = -1;
        $allArtists = $this->find('all', array(
            'fields' => array(
                'ArtistText'
            ),
            'group' => array(
                'ArtistText',
            ),
            'order' => array(
                'ArtistText ASC'
            ),
            'cache' => 'ArtistText'
        ));
        return $allArtists;
    }

    /*
      Function Name : getallartistname
      Desc : This would returna a set of featured artist which does not have images associated with them.
     */

    function getallartistname($condition, $artistName, $country)
    {
        $this->recursive = 2;
        $allArtists = $this->find('all', array(
            'conditions' =>
            array('and' =>
                array(
                    array('Country.Territory' => $country)
                )
            ),
            'fields' => array(
                'DISTINCT Song.ArtistText',
            ),
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.Territory'
                    )
                ),
            ),
            'order' => 'Song.ArtistText'
        ));
        $featuredArtistArr = array();
        $featuredArtistObj = new Featuredartist();
        $featuredArtistList = $featuredArtistObj->find('all');
        foreach ($featuredArtistList as $featuredArtist)
        {
            array_push($featuredArtistArr, $featuredArtist['Featuredartist']['artist_name']);
        }
        $resultArr = array();
        foreach ($allArtists as $allArtistsNames)
        {
            if ($condition == 'add')
            {
                if (!in_array($allArtistsNames['Song']['ArtistText'], $featuredArtistArr))
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
            }
            else
            {
                if ($allArtistsNames['Song']['ArtistText'] == $artistName && $allArtistsNames['Song']['ArtistText'] != '')
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
                elseif (!in_array($allArtistsNames['Song']['ArtistText'], $featuredArtistArr))
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
            }
        }
        return $resultArr;
    }

    /*
      Function Name : allartistname
      Desc : This would returna a set of artist which does not have images associated with them.
     */

    function allartistname($condition, $artistName)
    {
        $this->recursive = -1;
        $allArtists = $this->find('all', array(
            'fields' => 'DISTINCT ArtistText',
            'order' => 'ArtistText')
        );
        $artistArr = array();
        $artistObj = new Artist();
        $artistList = $artistObj->getallartists();
        foreach ($artistList as $artist)
        {
            array_push($artistArr, $artist['Artist']['artist_name']);
        }
        $resultArr = array();
        foreach ($allArtists as $allArtistsNames)
        {
            if ($condition == 'add')
            {
                if (!in_array($allArtistsNames['Song']['ArtistText'], $artistArr))
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
            }
            else
            {
                if ($allArtistsNames['Song']['ArtistText'] == $artistName && $allArtistsNames['Song']['ArtistText'] != '')
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
                elseif (!in_array($allArtistsNames['Song']['ArtistText'], $artistArr))
                {
                    $resultArr[$allArtistsNames['Song']['ArtistText']] = $allArtistsNames['Song']['ArtistText'];
                }
            }
        }
        return $resultArr;
    }

    /*
      Function Name : searchArtist
      Desc : This would returna a artist which is searched
     */

    function searchArtist($search)
    {
        if ($search == 'special')
        {
            $allArtists = $this->find('all', array(
                'fields' => array(
                    'ArtistText'
                ),
                'group' => array(
                    'ArtistText',
                ),
                'order' => array(
                    'ArtistText ASC',
                ),
                'conditions' => array("ArtistText REGEXP '^[^A-Za-z]'")
            ));
        }
        else
        {
            $allArtists = $this->find('all', array(
                'fields' => array(
                    'ArtistText'
                ),
                'group' => array(
                    'ArtistText',
                ),
                'order' => array(
                    'ArtistText ASC',
                ),
                'conditions' => array('ArtistText LIKE' => $search . '%')
            ));
        }
        return $allArtists;
    }

    /*
      Function Name : allartistname
      Desc : This would returna the download data for the patron
     */

    function getdownloaddata($id, $provider)
    {
        $this->recursive = 2;
        $this->Behaviors->attach('Containable');
        $downloadData = $this->find('all', array(
            'conditions' => array(
                'Song.ProdID' => $id,
                'Song.provider_type' => $provider
            ),
            'fields' => array(
                'Song.ProdID',
                'Song.ProductID',
                'Song.Title',
                'Song.SongTitle',
                'Song.Artist',
                'Song.ISRC'
            ),
            'contain' => array(
                'Full_Files' => array(
                    'fields' => array(
                        'Full_Files.CdnPath',
                        'Full_Files.SaveAsName'
                    )
                ),
                'Country' => array(
                    'fields' => array(
                        'Country.Territory',
                        'Country.provider_type'
                    )
                ),
            ),
        ));
        return $downloadData;
    }

    /*
      Function Name : allartistname
      Desc : This would returna a set of artist.
     */

    function selectArtist()
    {
        $this->recursive = -1;
        $allArtists = $this->find('all', array(
            'fields' => array(
                'ArtistText'
            ),
            'group' => array(
                'ArtistText',
            ),
            'order' => array(
                'ArtistText ASC',
            ),
            'conditions' => array('ArtistText LIKE' => 'A%'),
            'cache' => 'ArtistText'
        ));
        return $allArtists;
    }

    function getArtistAlbums($artist_text , $country, $cond)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'fields' => array(
                'DISTINCT Song.ReferenceID',
                'Song.provider_type',
                'Country.SalesDate'),
            'conditions' => array('Song.ArtistText' => base64_decode($artist_text),
                'Country.DownloadStatus' => 1, /* Changed on 16/01/2014 from Song.DownloadStatus to Country.DownloadStatus */
                "Song.Sample_FileID != ''",
                "Song.FullLength_FIleID != ''",
                'Country.Territory' => $country, $cond,
                'Song.provider_type = Country.provider_type'),
            'contain' => array(
                'Country' => array(
                    'fields' => array('Country.Territory')
                )),
            'recursive' => 0,
            'order' => array(
                'Country.SalesDate DESC'
            ))
        );
    }
    
    function getArtistView($id , $country, $cond, $query_id)
    {
        $this->Behaviors->attach('Containable');
        
        if($query_id==1)
        {
            return $this->find('all', array(
                        'fields' => array(
                            'DISTINCT Song.ReferenceID',
                            'Song.provider_type'),
                        'conditions' => array(
                            'Song.ArtistText' => base64_decode($id),
                            'Country.DownloadStatus' => 1,
                            "Song.Sample_FileID != ''",
                            "Song.FullLength_FIleID != ''",
                            'Country.Territory' => $country, 
                            $cond),
                        'contain' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.Territory')
                            )
                        ),
                        'recursive' => 0,
                        'limit' => 1)
            );
        }
        else if($query_id==2)
        {
            return $this->find('all', array(
                    'fields' => array(
                        'DISTINCT Song.ReferenceID',
                        'Song.provider_type'),
                    'conditions' => array(
                        'Song.ArtistText' => base64_decode($id),
                        "Song.Sample_FileID != ''",
                        "Song.FullLength_FIleID != ''",
                        'Country.Territory' => $country,
                        'Country.DownloadStatus' => 1,
                        array('or' =>
                            array(
                                array('Country.StreamingStatus' => 1)
                            )),
                        $cond
                    ), 'contain' => array(
                        'Country' => array(
                            'fields' => array('Country.Territory')
                        )),
                    'recursive' => 0, 'limit' => 1)
            );
        }
        
        
        
    }
    

}

?>