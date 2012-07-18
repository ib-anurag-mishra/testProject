<?php
class SolrComponent extends Object {

    var $components = array('Session');

    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => '192.168.2.178', 'port' => 8080, 'solrpath' => '/solr/freegalmusic/');

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    static $solr = null;

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
              $query = 'CSongTitle:*'.$keyword.'*';
              break;
            case 'genre':
              $query = 'CGenre:*'.$keyword.'*';
              break;
            case 'album':
              $query = 'CTitle:*'.$keyword.'*';
              break;
            case 'artist':
              $query = 'CArtistText:*'.$keyword.'*';
              break;
            case 'label':
              $query = 'CLabel:*'.$keyword.'*';
              break;
            case 'composer':
              $query = 'CComposer:*'.$keyword.'*';
              break;
            default:
              $query = 'CSongTitle:*'.$keyword.'*';
              break;
          }

          $query = $query.' AND Territory:'.$country;

          if($page == 1){
            $start = 0;
          } else {
            $start = (($page - 1) * $limit);
          }
          $response = self::$solr->search( $query, $start, $limit);
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
            $query = 'CSongTitle:*'.$keyword.'*';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = 'CGenre:*'.$keyword.'*';
            $field = 'Genre';
            break;
          case 'album':
            $query = 'CTitle:*'.$keyword.'*';
            $field = 'Title';
            break;
          case 'artist':
            $query = 'CArtistText:*'.$keyword.'*';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = 'CLabel:*'.$keyword.'*';
            $field = 'Label';
            break;
          case 'composer':
            $query = 'CComposer:*'.$keyword.'*';
            $field = 'Composer';
            break;
          default:
            $query = 'CSongTitle:*'.$keyword.'*';
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
      echo $query;
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