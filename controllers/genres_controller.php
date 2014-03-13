<?php

/*
  File Name : genres_controller.php
  File Description : Genre controller page
  Author : m68interactive
 */

ini_set('memory_limit', '2048M');

Class GenresController extends AppController
{

    var $uses = array('Category', 'Files', 'Album', 'Song', 'Download');
    var $components = array('Session', 'Auth', 'Acl', 'RequestHandler', 'Downloads', 'ValidatePatron', 'Common', 'Streaming','Solr');
    var $helpers = array('Cache', 'Library', 'Page', 'Wishlist', 'Language', 'Queue');

    /*
      Function Name : beforeFilter
      Desc : actions that needed before other functions are getting called
     */

    function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allowedActions = array('view', 'index', 'ajax_view', 'ajax_view_pagination', 'callToAllFunctions', 'album');
        $libraryCheckArr = array("view", "index");
//		if(in_array($this->action,$libraryCheckArr)) {
//		  $validPatron = $this->ValidatePatron->validatepatron();
//			if($validPatron == '0') {
//				//$this->Session->destroy();
//				//$this -> Session -> setFlash("Sorry! Your session has expired.  Please log back in again if you would like to continue using the site.");
//				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
//			}
//			else if($validPatron == '2') {
//				//$this->Session->destroy();
//				$this -> Session -> setFlash("Sorry! Your Library or Patron information is missing. Please log back in again if you would like to continue using the site.");
//				$this->redirect(array('controller' => 'homes', 'action' => 'aboutus'));
//			}
//		}
    }

    /*
      Function Name : index
      Desc : actions on landing page
     */

    function index()
    {
        /**
         * Fix for Genre page other than view() method is called
         * 
         */
        
        //echo '<pre>';
        $url = explode('/', $this->params['url']['url']);
        if ($url[1] != 'view')
        {
            $this->redirect('/genres/view/');
        }


        $country = $this->Session->read('territory');

        //$country = "'".$country."'";
        $this->layout = 'home';
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);
        $this->Genre->Behaviors->attach('Containable');
        $this->Genre->recursive = 2;

        if (($genre = Cache::read("genre" . $country)) === false)
        {
            $genreAll = $this->Genre->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Country.Territory' => $country)
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
                ), 'group' => 'Genre.Genre'
            ));
            Cache::write("genre" . $country, $genreAll);
        }
        $genreAll = Cache::read("genre" . $country);

        $this->set('genresAll', $genreAll);

        $category_ids = $this->Category->find('list', array('conditions' => array('Language' => Configure::read('App.LANGUAGE')), 'fields' => 'id'));
        $rand_keys = array_rand($category_ids, 4);
        $rand_val = implode(",", $rand_keys);
        $categories = $this->Category->find('all', array(
            'conditions' => array('id IN (' . $rand_val . ')'),
            'fields' => 'Genre'));
        $i = 0;
        $j = 0;
        foreach ($categories as $category)
        {
            $genreName = $category['Category']['Genre'];
            if ($this->Session->read('block') == 'yes')
            {
                $block = 'yes';
                $cond = array('Song.Advisory' => 'F');
            }
            else
            {
                $cond = "";
                $block = 'no';
            }
            if (($genres = Cache::read($genreName . $block)) === false)
            {
                $this->Song->recursive = 2;
                $this->Song->Behaviors->attach('Containable');
                $genreDetails = $this->Song->find('all', array('conditions' =>
                    array('and' =>
                        array(
                            array('Genre.Genre' => $genreName),
                            array("Song.ReferenceID <> Song.ProdID"),
                            array('Song.DownloadStatus' => 1),
                            //	array('Song.TrackBundleCount' => 0),
                            array("Song.Sample_FileID != ''"),
                            array("Song.FullLength_FIleID != ''"),
                            array('Song.provider_type = Genre.provider_type'),
                            array('Song.provider_type = Country.provider_type'),
                            array('Country.Territory' => $country),
                            array("Song.UpdateOn >" => date('Y-m-d', strtotime("-1 week"))), $cond
                        )
                    ),
                    'fields' => array(
                        'DISTINCT Song.ProdID',
                        'Song.ReferenceID',
                        'Song.Title',
                        'Song.ArtistText',
                        'Song.DownloadStatus',
                        'Song.SongTitle',
                        'Song.Artist',
                        'Song.Advisory',
                        'Song.provider_type'
                    ),
                    'contain' => array(
                        'Genre' => array(
                            'fields' => array(
                                'Genre.Genre'
                            )
                        ),
                        'Country' => array(
                            'fields' => array(
                                'Country.Territory',
                                'Country.SalesDate',
                            )
                        ),
                        'Sample_Files' => array(
                            'fields' => array(
                                'Sample_Files.CdnPath',
                                'Sample_Files.SaveAsName',
                                'Sample_Files.SourceURL'
                            ),
                        ),
                        'Full_Files' => array(
                            'fields' => array(
                                'Full_Files.CdnPath',
                                'Full_Files.SaveAsName',
                                'Full_Files.SourceURL'
                            ),
                        ),
                    ), 'limit' => '50'));
                Cache::write($genreName . $block, $genreDetails);
            }
            $genreDetails = Cache::read($genreName . $block);
            $finalArr = Array();
            $songArr = Array();
            if (count($genreDetails) > 3)
            {
                $rand_keys = array_rand($genreDetails, 3);
                $songArr[0] = $genreDetails[$rand_keys[0]];
                $songArr[1] = $genreDetails[$rand_keys[1]];
                $songArr[2] = $genreDetails[$rand_keys[2]];
            }
            else
            {
                $songArr = $genreDetails;
            }
            $this->Download->recursive = -1;
            foreach ($songArr as $genre)
            {
                $this->Song->recursive = 2;
                $this->Song->Behaviors->attach('Containable');
                $downloadData = $this->Album->find('all', array(
                    'conditions' => array('Album.ProdID' => $genre['Song']['ReferenceID'], 'Song.provider_type = Genre.provider_type', 'Song.provider_type = Country.provider_type'),
                    'fields' => array(
                        'Album.ProdID',
                    ),
                    'contain' => array(
                        'Files' => array(
                            'fields' => array(
                                'Files.CdnPath',
                                'Files.SaveAsName',
                                'Files.SourceURL',
                            ),
                        )
                )));
                $albumArtwork = shell_exec(Configure::read('App.tokengen') . $downloadData[0]['Files']['CdnPath'] . "/" . $downloadData[0]['Files']['SourceURL']);
                $sampleSongUrl = shell_exec(Configure::read('App.tokengen') . $genre['Sample_Files']['CdnPath'] . "/" . $genre['Sample_Files']['SaveAsName']);
                $songUrl = shell_exec(Configure::read('App.tokengen') . $genre['Full_Files']['CdnPath'] . "/" . $genre['Full_Files']['SaveAsName']);
                $finalArr[$i]['Album'] = $genre['Song']['Title'];
                $finalArr[$i]['Song'] = $genre['Song']['SongTitle'];
                $finalArr[$i]['Artist'] = $genre['Song']['Artist'];
                $finalArr[$i]['ProdArtist'] = $genre['Song']['ArtistText'];
                $finalArr[$i]['Advisory'] = $genre['Song']['Advisory'];
                $finalArr[$i]['AlbumArtwork'] = $albumArtwork;
                $finalArr[$i]['SongUrl'] = $songUrl;
                $finalArr[$i]['ProdId'] = $genre['Song']['ProdID'];
                $finalArr[$i]['ReferenceId'] = $genre['Song']['ReferenceID'];
                $finalArr[$i]['provider_type'] = $genre['Song']['provider_type'];
                $finalArr[$i]['SalesDate'] = $genre['Country']['SalesDate'];
                $finalArr[$i]['SampleSong'] = $sampleSongUrl;
                $downloadsUsed = $this->Download->find('all', array('conditions' => array('ProdID' => $genre['Song']['ProdID'], 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate'))), 'limit' => '1'));
                if (count($downloadsUsed) > 0)
                {
                    $finalArr[$i]['status'] = 'avail';
                }
                else
                {
                    $finalArr[$i]['status'] = 'not';
                }
                $i++;
            }
            $finalArray[$j] = $finalArr;
            $finalArray[$j]['Genre'] = $genreName;
            $j++;
        }

        // print_r($finalArray);
        $this->set('categories', $finalArray);
    }

    /*
      Function Name : view
      Desc : actions on view all genres page
     */

    function view($Genre = null, $Artist = null)
    {
//        /Configure::write('debug' ,2 );
        //login redirect issue fix        
        if (!base64_decode($this->Session->read('calledGenre')))
        {
            $Genre = '';
        }
        else
        {
            $Genre = $this->Session->read('calledGenre');
            $this->Session->write('calledGenre', $Genre);
        }

        if ($Genre == '')
        {
            $Genre = "QWxs";
        }


        $this->layout = 'home';
        $country = $this->Session->read('territory');
        if (!base64_decode($Genre))
        {
            $this->Session->setFlash(__('Invalid Genre.', true));
            $this->redirect(array('controller' => '/', 'action' => 'index'));
        }

        $this->Genre->Behaviors->attach('Containable');
        $this->Genre->recursive = 2;
        if (($genre = Cache::read("genre" . $country)) === false)
        {
            $genreAll = $this->Genre->find('all', array(
                'conditions' =>
                array('and' =>
                    array(
                        array('Country.Territory' => $country, "Genre.Genre NOT IN( 'Caribbean','Downtempo','Dub','Fusion','House','Indie' ,'Progressive Rock','Psychedelic Rock', 'Symphony' ,'World' ,'Porn Groove')"
                        )
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
                ), 'group' => 'Genre.Genre'
            ));
            Cache::write("genre" . $country, $genreAll);
        }
        $genreAll = Cache::read("genre" . $country);

        $this->set('genresAll', $genreAll);
        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $country = $this->Session->read('territory');
        $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
        $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
        $this->set('libraryDownload', $libraryDownload);
        $this->set('patronDownload', $patronDownload);
        if ($this->Session->read('block') == 'yes')
        {
            $cond = array('Song.Advisory' => 'F');
        }
        else
        {
            $cond = "";
        }



        //login redirect fix if selected 
        if ($this->Session->read('selectedAlpha') == 'All')
        {
            $Artist = null;
        }
        elseif ($Artist != $this->Session->read('selectedAlpha'))
        {
            $Artist = $this->Session->read('selectedAlpha');
        }

        if ($Artist == 'spl')
        {
            $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
        }
        elseif ($Artist != '' && $Artist != 'img')
        {
            $condition = array('Song.ArtistText LIKE' => $Artist . '%');
        }
        else
        {
            $condition = "";
        }


        $this->Song->recursive = 0;
        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);

        if ($genre != 'All')
        {
            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $gcondition = array(
                "Song.provider_type = Genre.provider_type",
                "Genre.Genre = '$genre'",
                "find_in_set('\"$country\"',Song.Territory) > 0",
                'Song.DownloadStatus' => 1,        
                "Song.Sample_FileID != ''",
                "TRIM(Song.ArtistText) != ''",
                "Song.ArtistText IS NOT NULL",
                "Song.FullLength_FIleID != ''", 
                $condition, 
                '1 = 1'
                );
            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                            'Genre.Genre'
                        )),
                ),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'extra' => array('chk' => 1),
                'limit' => '60', 'cache' => 'yes', 'check' => 2
            );
        }
        else
        {
            //this run
            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('hasOne' => array('Genre')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $this->Song->recursive = 0;
            $gcondition = array(
                "find_in_set('\"$country\"',
                Song.Territory) > 0",
                'Song.DownloadStatus' => 1,
                "Song.Sample_FileID != ''",
                "Song.FullLength_FIleID != ''",
                "TRIM(Song.ArtistText) != ''",
                "Song.ArtistText IS NOT NULL",
                $condition,
                '1 = 1 '
            );

            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'extra' => array('chk' => 1),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'limit' => '60',
                'cache' => 'yes',
                'check' => 2,
                'all_query' => true,
                'all_country' => "find_in_set('\"$country\"',Song.Territory) > 0",
                'all_condition' => ((is_array($condition) && isset($condition['Song.ArtistText LIKE'])) ? "Song.ArtistText LIKE '" . $condition['Song.ArtistText LIKE'] . "'" : (is_array($condition) ? $condition[0] : $condition))
            );
        }


        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $allArtists = $this->paginate('Song');


        $allArtistsNew = $allArtists;

        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            if ($allArtistsNew[$i]['Song']['ArtistText'] != "")
            {
                $allArtists[$i] = $allArtistsNew[$i];
            }
        }

        $tempArray = array();
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            $tempArray[] = trim($allArtistsNew[$i]['Song']['ArtistText']);
        }
        sort($tempArray, SORT_STRING);

        for ($i = 0; $i < count($tempArray); $i++)
        {
            $allArtists[$i]['Song']['ArtistText'] = trim($tempArray[$i]);
        }

        $this->set('totalPages', $this->params['paging']['Song']['pageCount']);
        $this->set('genres', $allArtists);
        $this->set('genre', $genre);
        $this->set('selectedAlpha', $this->Session->read('selectedAlpha'));
    }

    function ajax_view($Genre = null, $Artist = null)
    {
        $this->layout = 'ajax';

        $patId = $this->Session->read('patron');
        $libId = $this->Session->read('library');
        $country = $this->Session->read('territory');

        //login re-direct issue
        $this->Session->write('calledGenre', $Genre);
        $this->Session->write('selectedAlpha', $Artist);

        $this->Session->delete('calledArtist');
        $this->Session->delete('calledAlbum');
        $this->Session->delete('calledProvider');
        $this->Session->delete('page');

        if ($Genre == '')
        {
            $Genre = "QWxs";
        }
        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);

        if ($Artist != $this->Session->read('selectedAlpha'))
        {
            $Artist = $this->Session->read('selectedAlpha');
        }


        if ($Artist == 'spl')
        {
            $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
        }
        elseif ($Artist != '' && $Artist != 'img' && $Artist != 'All')
        {
            $condition = array('Song.ArtistText LIKE' => $Artist . '%');
        }
        else
        {
            $condition = "";
        }


        $this->set('selectedCallFlag', 0);
        if (isset($_REQUEST['ajax_genre_name']))
        {
            $this->set('selectedCallFlag', 1);
        }

        if ($this->Session->read('block') == 'yes')
        {
            $cond = array('Song.Advisory' => 'F');
        }
        else
        {
            $cond = "";
        }

        $this->Song->recursive = 0;

        if ($genre != 'All')
        {

            //this one
            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $gcondition = array("Song.provider_type = Genre.provider_type", "Genre.Genre = '$genre'", "find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", "Song.FullLength_FIleID != ''", $condition, '1 = 1 ');
            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                            'Genre.Genre'
                        )),
                ),
                'extra' => array('chk' => 1),
                'limit' => '60', 'cache' => 'yes', 'check' => 2
            );
        }
        else
        {

            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('hasOne' => array('Genre')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $this->Song->recursive = 0;
            $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", $condition, '1 = 1 ');

            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'extra' => array('chk' => 1),
                'limit' => '60',
                'cache' => 'yes',
                'check' => 2,
                'all_query' => true,
                'all_country' => "find_in_set('\"$country\"',Song.Territory) > 0",
                'all_condition' => ((is_array($condition) && isset($condition['Song.ArtistText LIKE'])) ? "Song.ArtistText LIKE '" . $condition['Song.ArtistText LIKE'] . "'" : (is_array($condition) ? $condition[0] : $condition))
            );
        }



        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $allArtists = $this->paginate('Song');
        $allArtistsNew = $allArtists;
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            if ($allArtistsNew[$i]['Song']['ArtistText'] != "")
            {
                $allArtists[$i] = $allArtistsNew[$i];
            }
        }


        $tempArray = array();
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            $tempArray[] = trim($allArtistsNew[$i]['Song']['ArtistText']);
        }
        sort($tempArray, SORT_STRING);

        for ($i = 0; $i < count($tempArray); $i++)
        {
            $allArtists[$i]['Song']['ArtistText'] = trim($tempArray[$i]);
        }
        $this->set('totalPages', $this->params['paging']['Song']['pageCount']);
        $this->set('genres', $allArtists);
        $this->set('selectedAlpha', $Artist);
        $this->set('genre', base64_decode($Genre));
    }

    function ajax_view_pagination($Genre = null, $Artist = null)
    {
        //pagination value for login redirect issue on genre page
        $this->Session->write('page', $this->params['named']['page']);

        $this->layout = 'ajax';

        if ($Genre == '')
        {
            $Genre = "QWxs";
        }
        $country = $this->Session->read('territory');


        if ($this->Session->read('block') == 'yes')
        {
            $cond = array('Song.Advisory' => 'F');
        }
        else
        {
            $cond = "";
        }
        if ($Artist == 'spl')
        {
            $condition = array("Song.ArtistText REGEXP '^[^A-Za-z]'");
        }
        elseif ($Artist != '' && $Artist != 'img' && $Artist != 'All')
        {
            $condition = array('Song.ArtistText LIKE' => $Artist . '%');
        }
        else
        {
            $condition = "";
        }
        $this->Song->recursive = 0;

        $genre = base64_decode($Genre);
        $genre = mysql_escape_string($genre);

        if ($genre != 'All')
        {

            //this one 
            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $this->Song->recursive = 0;
            $gcondition = array("Song.provider_type = Genre.provider_type", "Genre.Genre = '$genre'", "find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", "Song.FullLength_FIleID != ''", $condition, '1 = 1 ');
            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'contain' => array(
                    'Genre' => array(
                        'fields' => array(
                            'Genre.Genre'
                        )),
                ),
                'extra' => array('chk' => 1),
                'limit' => '60', 'cache' => 'yes', 'check' => 2,
            );
        }
        else
        {


            $this->Song->unbindModel(array('hasOne' => array('Participant')));
            $this->Song->unbindModel(array('hasOne' => array('Country')));
            $this->Song->unbindModel(array('hasOne' => array('Genre')));
            $this->Song->unbindModel(array('belongsTo' => array('Sample_Files', 'Full_Files')));
            $this->Song->Behaviors->attach('Containable');
            $gcondition = array("find_in_set('\"$country\"',Song.Territory) > 0", 'Song.DownloadStatus' => 1, "Song.Sample_FileID != ''", "Song.FullLength_FIleID != ''", "TRIM(Song.ArtistText) != ''", "Song.ArtistText IS NOT NULL", $condition, '1 = 1 ');

            $this->paginate = array(
                'conditions' => $gcondition,
                'fields' => array('DISTINCT Song.ArtistText'),
                'order' => 'TRIM(Song.ArtistText) ASC',
                'extra' => array('chk' => 1),
                'limit' => '60',
                'cache' => 'yes',
                'check' => 2,
                'all_query' => true,
                'all_country' => "find_in_set('\"$country\"',Song.Territory) > 0",
                'all_condition' => ((is_array($condition) && isset($condition['Song.ArtistText LIKE'])) ? "Song.ArtistText LIKE '" . $condition['Song.ArtistText LIKE'] . "'" : (is_array($condition) ? $condition[0] : $condition))
            );
        }
        $this->Song->unbindModel(array('hasOne' => array('Participant')));
        $allArtists = $this->paginate('Song');


        $allArtistsNew = $allArtists;
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            if ($allArtistsNew[$i]['Song']['ArtistText'] != "")
            {
                $allArtists[$i] = $allArtistsNew[$i];
            }
        }

        $tempArray = array();
        for ($i = 0; $i < count($allArtistsNew); $i++)
        {
            $tempArray[] = trim($allArtistsNew[$i]['Song']['ArtistText']);
        }
        sort($tempArray, SORT_STRING);

        for ($i = 0; $i < count($tempArray); $i++)
        {
            $allArtists[$i]['Song']['ArtistText'] = trim($tempArray[$i]);
        }


        $this->set('totalPages', $this->params['paging']['Song']['pageCount']);
        $this->set('genres', $allArtists);
        $this->set('genre', base64_decode($Genre));
    }

    /*
      Function Name : admin_managegenre
      Desc : actions for admin end manage genre to add/edit genres
     */

    function admin_managegenre()
    {
        if ($this->data)
        {
            $this->Category->deleteAll(array('Language' => Configure::read('App.LANGUAGE')), false);
            $selectedGenres = Array();
            $i = 0;
            foreach ($this->data['Genre']['Genre'] as $k => $v)
            {
                if ($i < '8')
                {
                    if ($v != '0')
                    {
                        $selectedGenres['Genre'] = $v;
                        $selectedGenres['Language'] = Configure::read('App.LANGUAGE');
                        $this->Category->save($selectedGenres);
                        $this->Category->id = false;
                        $i++;
                    }
                }
            }
            $this->Session->setFlash('Your selection saved successfully!!', 'modal', array('class' => 'modal success'));
        }
        $this->Genre->recursive = -1;
        $allGenres = $this->Genre->find('all', array(
            'fields' => 'DISTINCT Genre',
            'order' => 'Genre')
        );
        $this->set('allGenres', $allGenres);
        $this->Category->recursive = -1;
        $selectedGenres = array();
        $selectedGenres = $this->Category->find('all', array('fields' => array('Genre'), 'conditions' => array('Language' => Configure::read('App.LANGUAGE'))));
        foreach ($selectedGenres as $selectedGenre)
        {
            $selArray[] = $selectedGenre['Category']['Genre'];
        }
        $this->set('selectedGenres', $selArray);
        $this->layout = 'admin';
    }

	/*
      Function Name : album
      Desc : actions for genre albums 
    */
	
	function album($page = 1, $facetPage = 1)
    {
        //set_time_limit(0);
        //echo "<br>Started at ".date("Y-m-d H:i:s");
        // reset page parameters when serach keyword changes
        // to check if the search is made from search bar or click on search page
        $layout = $_GET['layout'];

        if (('' == trim($_GET['q'])) || ('' == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
        }// unset session when no params
        if ((isset($_SESSION['SearchReq'])) && ($_SESSION['SearchReq']['word'] != trim($_GET['q'])) && ($_SESSION['SearchReq']['type'] == trim($_GET['type'])))
        {
            unset($_SESSION['SearchReq']);
            $this->redirect(array('controller' => 'search', 'action' => 'index?q=' . $_GET['q'] . '&type=' . $_GET['type']));
        }//reset session & redirect to 1st page
        if (('' != trim($_GET['q'])) && ('' != trim($_GET['type'])))
        {
            $_SESSION['SearchReq']['word'] = $_GET['q'];
            $_SESSION['SearchReq']['type'] = $_GET['type'];
        }//sets values in session

        $queryVar = null;
        $check_all = null;
        $sortVar = 'ArtistText';
        $sortOrder = 'asc';

        if (isset($_GET['q']))
        {
            $queryVar = $_GET['q']; // html_entity_decode();
        }
        if (isset($_GET['type']))
        {
            $type = $_GET['type'];
            $typeVar = (($_GET['type'] == 'all' || $_GET['type'] == 'song' || $_GET['type'] == 'album' || $_GET['type'] == 'genre' || $_GET['type'] == 'label' || $_GET['type'] == 'artist' || $_GET['type'] == 'composer' || $_GET['type'] == 'video') ? $_GET['type'] : 'all');
        }
        else
        {
            $typeVar = 'all';
        }
        $this->set('type', $typeVar);

        if (isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
            $sort = (($sort == 'song' || $sort == 'album' || $sort == 'artist' || $sort == 'composer') ? $sort : 'artist');
            switch ($sort)
            {
                case 'song':
                    $sortVar = 'SongTitle';
                    break;
                case 'album':
                    $sortVar = 'Title';
                    break;
                case 'genre':
                    $sortVar = 'Genre';
                    break;
                case 'label':
                    $sortVar = 'Label';
                    break;
                case 'video':
                    $sortVar = 'VideoTitle';
                    break;
                case 'artist':
                    $sortVar = 'ArtistText';
                    break;
                case 'composer':
                    $sortVar = 'Composer';
                    break;
                default:
                    $sortVar = 'ArtistText';
                    break;
            }
        }
        else
        {
            $sort = 'artist';
        }

        $this->set('sort', $sort);

        if (isset($_GET['sortOrder']))
        {
            $sortOrder = $_GET['sortOrder'];
            $sortOrder = (($sortOrder == 'asc' || $sortOrder == 'desc') ? $sortOrder : 'asc');
        }
        else
        {
            $sortOrder = 'asc';
        }

        $this->set('sortOrder', $sortOrder);


        if (!empty($queryVar))
        {
            //Added code for log search data
            $insertArr[] = $this->searchrecords($typeVar, $queryVar);
            $this->Searchrecord->saveAll($insertArr);
            //End Added code for log search data

            $patId = $this->Session->read('patron');
            $libId = $this->Session->read('library');
            $libraryDownload = $this->Downloads->checkLibraryDownload($libId);
            $patronDownload = $this->Downloads->checkPatronDownload($patId, $libId);
            $docs = array();

            $total = 0;
            $limit = 10;

            if (!isset($page) || $page < 1)
            {
                $page = 1;
            }
            else
            {
                $page = $page;
            }

            if (!isset($facetPage) || $facetPage < 1)
            {
                $facetPage = 1;
            }
            else
            {
                $facetPage = $facetPage;
            }

            $country = $this->Session->read('territory');
            //echo "<br>Search for Songs Started at ".date("Y-m-d H:i:s");
            $songs = $this->Solr->search($queryVar, $typeVar, $sortVar, $sortOrder, $page, $limit, $country);
            //echo "<br>Search for Songs Ended at ".date("Y-m-d H:i:s");

            $total = $this->Solr->total;
            $totalPages = ceil($total / $limit);

            if ($total != 0)
            {
                /* if($page > $totalPages){
                  $page = $totalPages;
                  $this->redirect();
                  } */
            }

            /* echo "Microtime : ".microtime();
              echo "Time : ".date('h:m:s'); */

            $songArray = array();
            foreach ($songs as $key => $song)
            {
                $songArray[] = $song->ProdID;
            }
            
            if($type == 'video'){
                $downloadsUsed = $this->LatestVideodownload->find('all', array('conditions' => array('LatestVideodownload.ProdID in (' . implode(',', $songArray) . ')', 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
            } else {
                $downloadsUsed = $this->LatestDownload->find('all', array('conditions' => array('LatestDownload.ProdID in (' . implode(',', $songArray) . ')', 'library_id' => $libId, 'patron_id' => $patId, 'history < 2', 'created BETWEEN ? AND ?' => array(Configure::read('App.twoWeekStartDate'), Configure::read('App.twoWeekEndDate')))));
            }
            foreach ($songs as $key => $song)
            {
                $set = 0;
                foreach ($downloadsUsed as $downloadKey => $downloadData)
                {
		   if($type == video)
                   {
                    if ($downloadData['LatestVideodownload']['ProdID'] == $song->ProdID)
                    {
                        $songs[$key]->status = 'avail';
                        $set = 1;
                        break;
                    } 
                   } else {
                   if ($downloadData['LatestDownload']['ProdID'] == $song->ProdID)
                    {
                        $songs[$key]->status = 'avail';
                        $set = 1;
                        break;
                    }
                   }
                }
                if ($set == 0)
                {
                    $songs[$key]->status = 'not';
                }
            }
            /* echo "Microtime : ".microtime();
              echo "Time : ".date('h:m:s'); */

            $this->set('songs', $songs);
            // print_r($songs);
            // Added code for all functionality
            // print_r($songs);

            if (!empty($type) && !($type == 'all'))
            {

                switch ($typeVar)
                {
                    case 'album':
                        $limit = 12;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'album');
			//$totalAlbums = $totalFacetCount;
                        // echo "Group Search for Albums Started at ".time();
                        $albums = $this->Solr->groupSearch($queryVar, 'album', $facetPage, $limit);

                        // echo "Group Search for Albums Ended at ".time();

                        $arr_albumStream = array();

                        foreach ($albums as $objKey => $objAlbum)
                        {
                            $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                                    array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                            );
                        }
                          echo "<pre>"; print_r($albums); exit;
                        $this->set('albumData', $albums);
                        $this->set('arr_albumStream', $arr_albumStream);

                        break;

                    case 'genre':
                        $limit = 30;
			
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'genre');
                        $genres = $this->Solr->groupSearch($queryVar, 'genre', $facetPage, $limit);
                        //print_r($genres); die;
                        $this->set('genres', $genres);
                        break;

                    case 'label':
                        $limit = 18;
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'label');
                        $labels = $this->Solr->groupSearch($queryVar, 'label', $facetPage, $limit);
                        $this->set('labels', $labels);
                        break;

                    case 'artist':
                        $limit = 18;
			
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'artist');
                        $artists = $this->Solr->groupSearch($queryVar, 'artist', $facetPage, $limit);
                        $this->set('artists', $artists);
                        break;

                    case 'composer':
                        $limit = 18;
			
                        $totalFacetCount = $this->Solr->getFacetSearchTotal($queryVar, 'composer');
                        $composers = $this->Solr->groupSearch($queryVar, 'composer', $facetPage, $limit);
                        $this->set('composers', $composers);
                        break;
                }

                $this->set('totalFacetFound', $totalFacetCount);
                if (!empty($totalFacetCount))
                {
                    $this->set('totalFacetPages', ceil($totalFacetCount / $limit));
                }
                else
                {
                    $this->set('totalFacetPages', 0);
                }
            }
            else
            {

                //echo "<br>Group Search for Albums Started at ".date("Y-m-d H:i:s");
                $albums = $this->Solr->groupSearch($queryVar, 'album', 1, 15);
		//$totalAlbums = $this->Solr->getFacetSearchTotal($queryVar, 'album');
                //echo "<br>Group Search for Albums Ended at ".date("Y-m-d H:i:s");
                $queryArr = null;
                $albumData = array();
                $albumsCheck = array_keys($albums);
                for ($i = 0; $i <= count($albumsCheck) - 1; $i++)
                {
                    $queryArr = $this->Solr->query('Title:"' . utf8_decode(str_replace(array(' ', '(', ')', '"', ':', '!', '{', '}', '[', ']', '^', '~', '*', '?'), array('\ ', '\(', '\)', '\"', '\:', '\!', '\{', '\}', '\[', '\]', '\^', '\~', '\*', '\?'), $albumsCheck[$i])) . '"', 1);
                    $albumData[] = $queryArr[0];
                }
                

                $arr_albumStream = array();

                foreach ($albums as $objKey => $objAlbum)
                {
                    $arr_albumStream[$objKey]['albumSongs'] = $this->requestAction(
                            array('controller' => 'artists', 'action' => 'getAlbumSongs'), array('pass' => array(base64_encode($objAlbum->ArtistText), $objAlbum->ReferenceID, base64_encode($objAlbum->provider_type), 1))
                    );
                }

                //echo "<br>Group Search for Artists Started at ".date("Y-m-d H:i:s");
                $artists = $this->Solr->groupSearch($queryVar, 'artist', 1, 5);
                
                //echo "<br>Group Search for Artists Ended at ".date("Y-m-d H:i:s");
                //echo "<br>Group Search for Genres Started at ".date("Y-m-d H:i:s");
                $genres = $this->Solr->groupSearch($queryVar, 'genre', 1, 5);
		
                //echo "<br>Group Search for Genres Ended at ".date("Y-m-d H:i:s");;
                //echo "<br>Group Search for Composers Started at ".date("Y-m-d H:i:s");
                $composers = $this->Solr->groupSearch($queryVar, 'composer', 1, 5);
		
                //echo "<br>Group Search for Composers Ended at ".date("Y-m-d H:i:s");
                // $labels = $this->Solr->groupSearch($queryVar, 'label', 1, 5);
                //echo "<br>Group Search for Video Started at ".date("Y-m-d H:i:s");
                $videos = $this->Solr->groupSearch($queryVar, 'video', 1, 5);
                //echo "<br>Group Search for Video ended at ".date("Y-m-d H:i:s");
                // print_r($videos); die;
                $this->set('albums', $albums);
                $this->set('arr_albumStream', $arr_albumStream);
                //$this->set('albumData',$albumData);
                $this->set('albumData', $albums);
                $this->set('artists', $artists);
                $this->set('genres', $genres);
                //print_r($genres);die;
                $this->set('composers', $composers);
                //$this->set('labels', $labels);
                $this->set('videos', $videos);
		
            }
	   // $totalAlbums = $this->Solr->getFacetSearchTotal($queryVar, 'album');
            $this->set('libraryDownload', $libraryDownload);
            $this->set('patronDownload', $patronDownload);
            $this->set('total', $total);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('facetPage', $facetPage);
	  //  $this->set('totalAlbums',$totalAlbums);
   	   // $this->set('totalArtists',18);
	  //  $this->set('totalComposers',18);
	  //  $this->set('totalGenres',30);
	  //  $this->set('totalSongs', $totalPages*10);
        }
        $this->set('keyword', htmlspecialchars($queryVar));
        //echo "<br>search end- ".date("Y-m-d H:i:s");

        if (isset($this->params['isAjax']) && $this->params['isAjax'] && $layout == 'ajax')
        {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo $this->render();
            die;
        }
        else
        {
            $this->layout = 'home';
        }
    }
}

?>
