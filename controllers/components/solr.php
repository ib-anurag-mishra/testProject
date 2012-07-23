<?php
class SolrComponent extends Object {

    var $components = array('Session');

    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => '108.166.39.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusic/');//108.166.39.24

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    static $solr = null;

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    var $total = null;

    function initialize($config = array()){
        $settings = array_merge((array)$config,self::$_defaults);
        App::import("Vendor","solr",array('file' => "Apache".DS."Solr".DS."Service.php"));
        self::$solr = new Apache_Solr_Service( $settings['server'], $settings['port'], $settings['solrpath']);
        if ( ! self::$solr->ping() ) {
          throw new SolrException();
        }
    }

    function search($keyword, $type = 'song', $page = 1, $limit = 10){
        $query = '';
        $docs = array();
        $country = $this->Session->read('territory');
        if(!empty($country)){
          if(!isset(self::$solr)){
              self::initialize(null);
          }
          switch($type){
            case 'song':
              $query = 'CSongTitle:(*'.strtolower($keyword).'*)';
              break;
            case 'genre':
              $query = 'CGenre:(*'.strtolower($keyword).'*)';
              break;
            case 'album':
              $query = 'CTitle:(*'.strtolower($keyword).'*)';
              break;
            case 'artist':
              $query = 'CArtistText:(*'.strtolower($keyword).'*)';
              break;
            case 'label':
              $query = 'CLabel:(*'.strtolower($keyword).'*)';
              break;
            case 'composer':
              $query = 'CComposer:(*'.strtolower($keyword).'*)';
              break;
            default:
              $query = 'CSongTitle:(*'.strtolower($keyword).'*)';
              break;
          }

          $query = $query.' AND Territory:'.$country;

          //echo $query; die;

          if($page == 1){
            $start = 0;
          } else {
            $start = (($page - 1) * $limit);
          }
          $response = self::$solr->search( $query, $start, $limit);
          if ( $response->getHttpStatus() == 200 ) {
            if ( $response->response->numFound > 0 ) {
              $this->total = $response->response->numFound;
              foreach ( $response->response->docs as $doc ) {
                $docs[] = $doc;
              }
            } else {
              return array();
            }
          }
          else {
            return array();
          }
          return $docs;
        } else {
            return array();
        }
    }

    function facetSearch($keyword, $type='song', $page=1, $limit = 5){
      $query = '';
      $country = $this->Session->read('territory');
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = 'CSongTitle:(*'.strtolower($keyword).'*)';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = 'CGenre:(*'.strtolower($keyword).'*)';
            $field = 'Genre';
            break;
          case 'album':
            $query = 'CTitle:(*'.strtolower($keyword).'*)';
            $field = 'Title';
            break;
          case 'artist':
            $query = 'CArtistText:(*'.strtolower($keyword).'*)';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = 'CLabel:(*'.strtolower($keyword).'*)';
            $field = 'Label';
            break;
          case 'composer':
            $query = 'CComposer:(*'.strtolower($keyword).'*)';
            $field = 'Composer';
            break;
          default:
            $query = 'CSongTitle:(*'.strtolower($keyword).'*)';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country;

        if($page == 1){
            $start = 0;
        } else {
          $start = (($page - 1) * $limit);
        }

        $additionalParams = array(
          'facet' => 'true',
          'facet.field' => array(
            $field
          ),
          'facet.query' => $query,
          'facet.mincount' => 1,
          'facet.limit' => $limit
        );


        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          if (!empty($response->facet_counts->facet_fields->$field)) {
            return $response->facet_counts->facet_fields->$field;
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
      } else {
        return array();
      }
    }

    function query($query, $limit){
      $response = self::$solr->search($query, 0, $limit);
      if ( $response->getHttpStatus() == 200 ) {
        if ( $response->response->numFound > 0 ) {
          foreach ( $response->response->docs as $doc ) {
            $docs[] = $doc;
          }
        } else {
          return array();
        }
      }
      else {
        return array();
      }
      return $docs;
    }
}


class SolrException extends Exception  { }
?>