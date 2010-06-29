<?php
/*
 File Name : site_settings_controller.php
 File Description : Site Settings controller page
 Author : maycreate
 */

Class SiteSettingsController extends AppController
{
    var $name = 'SiteSettings';
    var $layout = 'admin';
    var $helpers = array( 'Html', 'Ajax', 'Javascript', 'Form', 'Session');
    var $components = array( 'Session', 'Auth', 'Acl', 'RequestHandler' );
    var $uses = array( 'Siteconfig', 'Physicalproduct', 'Metadata' );
    
    /*
     Function Name : admin_index
     Desc : actions for site settings page
    */
    function admin_index() {
        if (!empty($this->data)) {
            foreach($this->data['SiteSetting'] as $k => $v) {
                if($k == "") {
                    $this->Session -> setFlash( 'Please enter the fields correctly for Site settings!', 'modal', array( 'class' => 'modal problem' ) );
                    break;
                }
                else {
                    $this->Siteconfig->updateAll(array('Siteconfig.svalue' => "$v"), array('Siteconfig.soption' => "$k"));
                }
            }
            $this->Session -> setFlash( 'Site Settings updated successfully!', 'modal', array( 'class' => 'modal success' ) );
        }
        
        $siteConfig = $this->Siteconfig->find('list', array('fields' => array('Siteconfig.soption', 'Siteconfig.svalue')));
        $this -> set( 'siteConfig', $siteConfig );
        $this -> set( 'formAction', 'admin_index' );
    }
    
    /*
     Function Name : admin_generateXML
     Desc : actions for generating XML of suggestion songs
    */
    function admin_generateXML() {
        $suggestionCounter = $this->Siteconfig->find('all', array('fields' => array('Siteconfig.svalue'), 'conditions' => array('Siteconfig.soption' => 'suggestion_counter')));
        $this->Physicalproduct->Behaviors->attach('Containable');
		$suggestionSongs_ids = $this->Physicalproduct->find('list', array('fields' => 'ProdID'));
		$rand_keys = array_rand($suggestionSongs_ids, $suggestionCounter[0]['Siteconfig']['svalue']);
		$rand_val = implode(",", $rand_keys);
        $suggestionSongs = $this->Physicalproduct->find('all',
            array('conditions' => 
                array('Physicalproduct.ReferenceID <> Physicalproduct.ProdID','Physicalproduct.DownloadStatus' => 1,'Physicalproduct.TrackBundleCount' => 0, 'Metadata.Advisory' => 'F','Physicalproduct.ProdID IN ('.$rand_val.')'),
                'fields' => array(
                                    'Physicalproduct.ProdID',
                                    'Physicalproduct.Title',
                                    'Physicalproduct.ReferenceID',
                                    'Physicalproduct.ArtistText',
                                    'Physicalproduct.DownloadStatus',
                                    'Physicalproduct.SalesDate'
                                    ),
                'contain' => 
                array('Audio' => array('fields' => 
                                                        array('Audio.FileID'),
                                                        'Files' => array('fields' => array('Files.CdnPath', 'Files.SaveAsName'))
                                                ),
                        'Metadata' => array('fields' => array('Metadata.Title', 'Metadata.Artist','Metadata.Advisory'))
                )
            )
        );
        if(!file_exists(WWW_ROOT."/suggestion_xml")) {
		mkdir(WWW_ROOT."/suggestion_xml");
	}
        
        $doc = new DomDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElement('suggestionsongs');
        $root = $doc->appendChild($root);
        
        if(count($suggestionSongs) > 0) {
            foreach($suggestionSongs as $suggestionSong) {
                $child = $doc->createElement("songdetails");
                $child = $root->appendChild($child);
                
                $sub_child = $doc->createElement("ProdID");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Physicalproduct']['ProdID']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("Title");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Metadata']['Title']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("ReferenceID");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Physicalproduct']['ReferenceID']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("Artist");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Metadata']['Artist']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("ArtistText");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Physicalproduct']['ArtistText']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("CdnPath");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Audio'][0]['Files']['CdnPath']);
                $value = $sub_child->appendChild($value);
                
                $sub_child = $doc->createElement("SaveAsName");
                $sub_child = $child->appendChild($sub_child);
                $value = $doc->createTextNode($suggestionSong['Audio'][0]['Files']['SaveAsName']);
                $value = $sub_child->appendChild($value);
            }
        }
        
        if($doc->save(WWW_ROOT."/suggestion_xml/suggestion_songs.xml")) {
            $this->Session -> setFlash( 'Suggestion songs XML generated/updated successfully!', 'modal', array( 'class' => 'modal success' ) );
        }
        else {
            $this->Session -> setFlash( 'There is some occurred while creating/updating the suggestion songs XML !', 'modal', array( 'class' => 'modal success' ) );
        }
        $this->redirect('index');
    }
}
?>