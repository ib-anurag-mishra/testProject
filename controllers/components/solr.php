<?php
class SolrComponent extends Object {

    var $components = array('Session');

    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusicprod/');//108.166.39.24//192.168.100.24//192.168.100.24

    /**
     * Used for runtime configuration of model
     */
    static $_defaults2 = array('server' => '192.168.100.24', 'port' => 8080, 'solrpath' => '/solr/freegalmusicvideosprod/');//108.166.39.24//192.168.100.24//192.168.100.24
    
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
    static $solr2 = null;

    /**
     * Solr client object
     *
     * @var SolrClient
     */
    var $total = null;

    function initialize($config = array(), $config2 = array()){
        $settings = array_merge((array)$config,self::$_defaults);
        $settings2 = array_merge((array)$config2,self::$_defaults2);
        App::import("Vendor","solr",array('file' => "Apache".DS."Solr".DS."Service.php"));
        self::$solr = new Apache_Solr_Service( $settings['server'], $settings['port'], $settings['solrpath']);
        
        if ( ! self::$solr->ping() ) {
          throw new SolrException();
        }
        
        self::$solr2 = new Apache_Solr_Service( $settings2['server'], $settings2['port'], $settings2['solrpath']);
        
        if ( ! self::$solr2->ping() ) {
          throw new SolrException();
        }
    }

    function search($keyword, $type = 'song', $sort="SongTitle", $sortOrder="asc", $page = 1, $limit = 10, $country, $perfect=false){
        $query = '';
        $docs = array();
        $cond="";

        if($this->Session->read('block') == 'yes') {
          $cond = " AND Advisory:F";
        }
        $searchkeyword = $this->escapeSpace($keyword);
        if(!empty($country)){
          if(!isset(self::$solr)){
              self::initialize(null);
          }
          if($perfect == false){
            switch($type){
              case 'song':
                //$query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
                $query = '((CSongTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CTitle:(*'.strtolower($keyword).'*) OR CArtistText:(*'.strtolower($keyword).'*) OR CComposer:(*'.strtolower($keyword).'*)) OR (SongTitle:('.$searchkeyword.')^400 OR Title:('.$searchkeyword.')^200 OR ArtistText:('.$searchkeyword.')^200 OR Composer:('.$searchkeyword.')^200))';
                break;
              case 'genre':
                $query = '(CGenre:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Genre:'.$searchkeyword.'^200)';
                break;
              case 'album':
                $query = '(CTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Title:'.$searchkeyword.'^400 OR CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR ArtistText:'.$searchkeyword.'^200 OR CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Composer:'.$searchkeyword.'^200)';
                break;
              case 'artist':
                //$query = '(CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.'^200 OR ArtistText:*'.$searchkeyword.'*)';
		$query = '(CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR ArtistText:'.$searchkeyword.'^200)';
                break;
              case 'label':
                $query = '(CLabel:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Label:'.$searchkeyword.'^200)';
                break;
              case 'video':
                $query = '(CVideoTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR VideoTitle:'.$searchkeyword.'^200)';
                break;
              case 'composer':
                //$query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.'^200 OR Composer:*'.$searchkeyword.'*)';
		$query = '(CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Composer:'.$searchkeyword.'^200)';
		break;
              case 'all':
                $query = '((CSongTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CGenre:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CLabel:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*)) OR (SongTitle:('.$searchkeyword.'^400) OR Title:('.$searchkeyword.'^200) OR ArtistText:('.$searchkeyword.'^200) OR Composer:('.$searchkeyword.'^200)))';
                break;
              default:
                $query = '((CSongTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CGenre:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CLabel:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*)) OR (SongTitle:('.$searchkeyword.'^400) OR Title:('.$searchkeyword.'^200) OR ArtistText:('.$searchkeyword.'^200) OR Composer:('.$searchkeyword.'^200)))';
                break;
            }
          } else {
            switch($type){
              case 'song':
                //$query = 'SongTitle:'.$searchkeyword;
                $query = '(SongTitle:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                break;
              case 'genre':
                $query = 'Genre:*'.$searchkeyword.'*';
                break;
              case 'album':
                $query = 'Title:'.$searchkeyword;
                break;
              case 'artist':
                $query = 'ArtistText:'.$searchkeyword;
                break;
              case 'label':
                $query = 'Label:'.$searchkeyword;
                break;
             case 'video':
                $query = 'VideoTitle:'.$searchkeyword;
                break;
              case 'composer':
                $query = 'Composer:'.$searchkeyword;
                break;
              case 'all':
                $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                break;
              default:
                $query = '(SongTitle:'.$searchkeyword.' OR Genre:'.$searchkeyword.' OR Title:'.$searchkeyword.' OR ArtistText:'.$searchkeyword.' OR Label:'.$searchkeyword.' OR Composer:'.$searchkeyword.')';
                break;
            }
          }

          $query = $query.' AND Territory:'.$country.$cond;

          //echo '<br /> Rows :'.$query.'<br />'; //die;

          if($page == 1){
            $start = 0;
          } else {
            $start = (($page - 1) * $limit);
          }
          $additionalParams = array();

          $additionalParams = array(
            //'sort' => 'provider_type desc, '.$sort." ".$sortOrder
          );

 //-------------------------------------------------------------------------------------------------------------------------------------------------

        if(1 == $page) { unset($_SESSION['pagebreak']); unset($_SESSION['combine_page']); unset($_SESSION['ioda_cons']); unset($_SESSION['sony_total']); }
        if(!(isset($_SESSION['pagebreak']))) { 
          $provider_query .= ' AND provider_type:sony';
          if($type != 'video'){
          $response = self::$solr->search( $query.$provider_query, 0, 1 );
          } else {
          $response = self::$solr2->search( $query.$provider_query, 0, 1 );    
          }
          $num_found = $response->response->numFound;

          //echo 'num_found :' . $num_found . '<br />';
          if(0 == $num_found){
            // ioda call
            $_SESSION['pagebreak'] = 0;
            $_SESSION['combine_page'] = 0;
            $_SESSION['ioda_cons'] = 0;
          } else {
            $tot_pages = $num_found/$limit;
            if(is_float($tot_pages)){
              $_SESSION['pagebreak'] = intval($tot_pages) + 1;	  
              $_SESSION['combine_page'] = 1; 
            } else {
              $_SESSION['pagebreak'] = $tot_pages;
              $_SESSION['combine_page'] = 0;
            }
          }	
        }
           

        if( $page < $_SESSION['pagebreak'] ){ //echo '<br />SONY<br />';
          $provider_query = ' AND provider_type:sony'; $tmp_start = ($page - 1) * $limit; $start = $tmp_start;
          if($type != 'video'){
          $response = self::$solr->search( $query.$provider_query, $tmp_start, $limit);
          } else {
          $response = self::$solr2->search( $query.$provider_query, $tmp_start, $limit);
          }
          
        }//sony		
        if( $page == $_SESSION['pagebreak'] ){ //echo '<br />SONY & IODA<br />';
          $provider_query = ' AND provider_type:sony'; $tmp_start = ($page - 1) * $limit; $start = $tmp_start;
          if($type != 'video'){
          $response = self::$solr->search( $query.$provider_query, $tmp_start, $limit);
          } else {
          $response = self::$solr2->search( $query.$provider_query, $tmp_start, $limit);
          }
          
	  $_SESSION['sony_total'] = $response->response->numFound ;
          $fetched_result_count = count($response->response->docs);
          $_SESSION['ioda_cons'] = ($limit-$fetched_result_count) ;

          if( 1 == $_SESSION['combine_page'] ){
            $provider_query = ' AND provider_type:ioda'; $start = 0;
            if($type != 'video'){
          $sec_response = self::$solr->search( $query.$provider_query, 0, ($limit-$fetched_result_count));
          } else {
          $sec_response = self::$solr2->search( $query.$provider_query, 0, ($limit-$fetched_result_count));
          }
            
            if ( $sec_response->response->numFound > 0 ) { 
              $sec_response->response->docs = array_merge($response->response->docs, $sec_response->response->docs);
              $response = $sec_response;
            }
          }else{
            $provider_query = ' AND provider_type:ioda'; $start = 0;
            if($type != 'video'){
          $sec_response = self::$solr->search( $query.$provider_query, 0, 1 );
          } else {
          $sec_response = self::$solr2->search( $query.$provider_query, 0, 1 );
          }
            
            if ( $sec_response->response->numFound > 0 ) {
              $response->response->numFound = $sec_response->response->numFound;
            }  
          }
        }//sony & ioda
        if( $page > $_SESSION['pagebreak'] ){ //echo '<br />IODA<br />';
          $provider_query = ' AND provider_type:ioda'; $tmp_start = ((($page-$_SESSION['pagebreak'])-1)*$limit)+$_SESSION['ioda_cons']; $start = $tmp_start;
          if($type != 'video'){
          $response = self::$solr->search( $query.$provider_query, $tmp_start, $limit);
          } else {
          $response = self::$solr2->search( $query.$provider_query, $tmp_start, $limit);
          }
          
	  $response->response->numFound = $response->response->numFound + $_SESSION['sony_total'];
        }//ioda

        //echo 'pagebreak : ' . $_SESSION['pagebreak'] . '<br />';
        //echo 'combine_page : ' . $_SESSION['combine_page'] . '<br />';
        //echo 'ioda_cons : ' . $_SESSION['ioda_cons'] . '<br />';

//---------------------------------------------------------------------------------------------------------------------------------------//



        //ho '<pre>';
        //ho '<br /> QUERY > '.$query.' START > '.$start.' LIMIT > '.$limit.' PAGE > '.$page.' PROVIDER QUERY > '.$provider_query .'<br />'; 
        //int_r($response->response);
        //ho '</pre>'; 

       // $response = self::$solr->search( $query, $start, $limit, $additionalParams); 
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
      $cond="";

      if($this->Session->read('block') == 'yes') {
        $cond = " AND Advisory:F";
      }

      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:('.strtolower($searchkeyword).') OR Genre:'.$searchkeyword.')';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:('.strtolower($searchkeyword).') OR Title:'.$searchkeyword.' OR CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.' OR CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Title';
            break;
          case 'artist':
            $query = '(CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.')';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:('.strtolower($searchkeyword).') OR Label:'.$searchkeyword.')';
            $field = 'Label';
            break;
          case 'video':
            $query = '(CVideoTitle:('.strtolower($searchkeyword).') OR VideoTitle:'.$searchkeyword.')';
            $field = 'VideoTitle';
            break;
          case 'composer':
            $query = '(CComposer:('.strtolower($keyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country;
        //echo $query; die;

        if($page == 1){
            $start = 0;
        } else {
          $start = (($page - 1) * $limit);
        }

        $additionalParams = array(
          'facet' => 'true',
          'facet.field' => array(
            $field,
          ),
          'facet.query' => $query,
          'facet.mincount' => 1,
          'facet.limit' => $limit,
        );
        if($type != 'video'){
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
            $response = self::$solr2->search( $query, $start, $limit, $additionalParams);
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
        }
      } else {
        return array();
      }
    }

    function getFacetSearchTotal($keyword, $type='song'){
      $query = '';
      $country = $this->Session->read('territory');
      $cond = "";

      if($this->Session->read('block') == 'yes') {
        $cond = " AND Advisory:F";
      }
      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:('.strtolower($searchkeyword).') OR Genre:'.$searchkeyword.')';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:('.strtolower($searchkeyword).') OR Title:'.$searchkeyword.' OR CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.' OR CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Title';
            break;
          case 'artist':
            $query = '(CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.')';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:('.strtolower($searchkeyword).') OR Label:'.$searchkeyword.')';
            $field = 'Label';
            break;
          case 'video':
            $query = '(CVideoTitle:('.strtolower($searchkeyword).') OR VideoTitle:'.$searchkeyword.')';
            $field = 'VideoTitle';
            break;
          case 'composer':
            $query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country.$cond;

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
          'facet.limit' => -1
        );

        if($type != 'video'){
        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          if (!empty($response->facet_counts->facet_fields->$field)) {
            return count($response->facet_counts->facet_fields->$field);
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
        } else {
            $response = self::$solr2->search( $query, $start, $limit, $additionalParams);
            if ( $response->getHttpStatus() == 200 ) {
              if (!empty($response->facet_counts->facet_fields->$field)) {
                return count($response->facet_counts->facet_fields->$field);
              } else {
                return array();
              }
            }
            else {
              return array();
            }
            return array();
        }
      } else {
        return array();
      }
    }

    function groupSearch($keyword, $type='song', $page=1, $limit = 5){
      $query = '';
      $country = $this->Session->read('territory');
      $cond="";

      if($this->Session->read('block') == 'yes') {
        $cond = " AND Advisory:F";
      }

      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR SongTitle:'.$searchkeyword.'^200)';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Genre:'.$searchkeyword.'^200)';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Title:('.$searchkeyword.')^400 OR CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR ArtistText:('.$searchkeyword.')^200 OR CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Composer:('.$searchkeyword.')^200)';
            //$field = 'Title';
            $field = 'rpjoin';
            break;
          case 'artist':
            $query = '(CArtistText:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR ArtistText:('.$searchkeyword.')^200)';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Label:('.$searchkeyword.')^200)';
            $field = 'Label';
            break;
          case 'video':
            $query = '(CVideoTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR VideoTitle:('.$searchkeyword.')^200)';
            $field = 'VideoTitle';
            break;
          case 'composer':
            //$query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $query = '(CComposer:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR Composer:'.$searchkeyword.'^200)';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:(*'.strtolower(str_replace('!','\!',$keyword)).'*) OR SongTitle:'.$searchkeyword.'^200)';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country;
        // echo $query; // die;
        if($page == 1){
            $start = 0;
        } else {
          $start = (($page - 1) * $limit);
        }
        
        $additionalParams = array(
          'group' => 'true',
          'group.field' => $field,
          'group.query' => $query,
          'group.sort' => 'provider_type desc',
	    );
	
	/*$query = '(
			CArtistText: (britney spears)  OR 
			CArtistText: (britney spears*) OR
			CArtistText: (*britney spears) OR
			CArtistText: (*britney*)       OR
			CArtistText: (*spears*)        OR
			ArtistText:Britney\ spears^200
		) AND Territory:US';  */
	//$query = '(CArtistText:(*britney* *spears*) OR ArtistText:Britney\ spears^200) AND Territory:US';
       // echo '<br /> Boxs : '.$query.'<br />';

        if($type != 'video'){
        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          //print_r($response->grouped); die; 
          if (!empty($response->grouped->$field->groups)) {
            $docs = array();
            foreach($response->grouped->$field->groups as $group){
              $group->doclist->docs[0]->numFound = $group->doclist->numFound;
              $docs[] = $group->doclist->docs[0];
            } //echo '<pre>'; print_r($docs); echo '</pre>';
             // print_r($docs); die;
            return $docs;
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
        } else {
            $response = self::$solr2->search( $query, $start, $limit, $additionalParams);
            if ( $response->getHttpStatus() == 200 ) {
              //print_r($response->grouped); die; 
              if (!empty($response->grouped->$field->groups)) {
                $docs = array();
                foreach($response->grouped->$field->groups as $group){
                  $group->doclist->docs[0]->numFound = $group->doclist->numFound;
                  $docs[] = $group->doclist->docs[0];
                } //echo '<pre>'; print_r($docs); echo '</pre>';
                // print_r($docs); die;
                return $docs;
              } else {
                return array();
              }
            }
            else {
              return array();
            }
            return array();
        }
      } else {
        return array();
      }
    }

    function getGroupSearchTotal($keyword, $type='song'){
      $query = '';
      $country = $this->Session->read('territory');
      $cond = "";

      if($this->Session->read('block') == 'yes') {
        $cond = " AND Advisory:F";
      }

      $searchkeyword = $this->escapeSpace($keyword);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }

        switch($type){
          case 'song':
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
          case 'genre':
            $query = '(CGenre:('.strtolower($searchkeyword).') OR Genre:'.$searchkeyword.')';
            $field = 'Genre';
            break;
          case 'album':
            $query = '(CTitle:('.strtolower($searchkeyword).') OR Title:'.$searchkeyword.' OR CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.' OR CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Title';
            break;
          case 'artist':
            $query = '(CArtistText:('.strtolower($searchkeyword).') OR ArtistText:'.$searchkeyword.')';
            $field = 'ArtistText';
            break;
          case 'label':
            $query = '(CLabel:('.strtolower($searchkeyword).') OR Label:'.$searchkeyword.')';
            $field = 'Label';
            break;
          case 'video':
            $query = '(CVideoTitle:('.strtolower($searchkeyword).') OR VideoTitle:'.$searchkeyword.')';
            $field = 'VideoTitle';
            break;
          case 'composer':
            $query = '(CComposer:('.strtolower($searchkeyword).') OR Composer:'.$searchkeyword.')';
            $field = 'Composer';
            break;
          default:
            $query = '(CSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
        }

        $query = $query.' AND Territory:'.$country.$cond;

        if($page == 1){
            $start = 0;
        } else {
          $start = (($page - 1) * $limit);
        }

        $additionalParams = array(
          'group' => 'true',
          'fl' => array(
            $field
          ),
          'group.query' => $query,
        );
        
        if($type != 'video'){
        $response = self::$solr->search( $query, $start, $limit, $additionalParams);
        if ( $response->getHttpStatus() == 200 ) {
          if (!empty($response->grouped->$query->doclist->numFound)) {
            return count($response->grouped->$query->doclist->docs);
          } else {
            return array();
          }
        }
        else {
          return array();
        }
        return array();
        } else {
            $response = self::$solr2->search( $query, $start, $limit, $additionalParams);
            if ( $response->getHttpStatus() == 200 ) {
              if (!empty($response->grouped->$query->doclist->numFound)) {
                return count($response->grouped->$query->doclist->docs);
              } else {
                return array();
              }
            }
            else {
              return array();
            }
            return array();
        }
      } else {
        return array();
      }
    }

    function getAutoCompleteData($keyword, $type, $limit=10, $allmusic=0){

      $query = '';
      $country = $this->Session->read('territory');
      $cond = "";

      if($this->Session->read('block') == 'yes') {
        $cond = " AND Advisory:F";
      }
      $searchkeyword = $this->escapeSpace($keyword);
      $char = substr($keyword,0,1);
      if(!empty($country)){
        if(!isset(self::$solr)){
          self::initialize(null);
        }
        //echo '/'.$type.'/';
        if($type != 'all'){
          switch($type){
            case 'song':
              $query = '(CSongTitle:(*'.strtolower($keyword).'*) OR TSongTitle:(*'.$keyword.'*) OR SongTitle:('.$searchkeyword.'^200) )';
              $field = 'SongTitle';
              break;
            case 'genre':
              $query = '(CGenre:(*'.strtolower($keyword).'*) OR TGenre:(*'.$keyword.'*) OR Genre:('.$searchkeyword.'^200))';
              $field = 'Genre';
              break;
            case 'album':
              $query = '(CTitle:(*'.strtolower($keyword).'*) OR TTitle:(*'.$keyword.'*) OR Title:('.$searchkeyword.'^200))';
              $field = 'Title';
              break;
            case 'artist':
              $query = '(CArtistText:(*'.strtolower($keyword).'*) OR TArtistText:(*'.$keyword.'*) OR ArtistText:('.$searchkeyword.'^200))';
              $field = 'ArtistText';
              break;
            case 'label':
              $query = '(CLabel:(*'.strtolower($keyword).'*) OR TLabel:(*'.$keyword.'*) OR Label:('.$searchkeyword.'^200))';
              $field = 'Label';
              break;
            case 'video':
              $query = '(CVideoTitle:(*'.strtolower($keyword).'*) OR TVideoTitle:(*'.$keyword.'*) OR VideoTitle:('.$searchkeyword.'^200))';
              $field = 'VideoTitle';
              break;
            case 'composer':
              //$query = '(CComposer:('.strtolower($searchkeyword).'*) OR TComposer:('.$searchkeyword.'*) OR Composer:('.$searchkeyword.'*))';
              $query = '(CComposer:(*'.strtolower($keyword).'*) OR TComposer:(*'.$keyword.'*) OR Composer:('.$searchkeyword.'^200))';
              $field = 'Composer';
              break;
            default:
              $query = '(CSongTitle:(*'.strtolower($keyword).'*) OR TSongTitle:(*'.$keyword.'*) OR SongTitle:('.$searchkeyword.'^200))';
              $field = 'SongTitle';
              break;
          }

          $query = $query.' AND Territory:'.$country.$cond;

	  //echo $query.'<br />'; //die;

          $additionalParams = array(
            'facet' => 'true',
            'facet.field' => array(
              $field
            ),
            'facet.query' => $query,
            'facet.mincount' => 1,
            'facet.limit' => $limit
          );

          
          if($type != 'video'){     
              $response = self::$solr->search( $query, 0, 0, $additionalParams);
	 if ( $response->getHttpStatus() == 200 ) {
            if (!empty($response->facet_counts->facet_fields->$field)) {
              
	     	if(1 == $allmusic){
			$arr_result = array();
	     		$arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field; 
	     		return $arr_result;
		}else{
			return $response->facet_counts->facet_fields->$field;
		}
            } else {
              return array();
            }
          }
          else {
            return array();
          }
          return array();
          } else {
              $response = self::$solr2->search( $query, 0, 0, $additionalParams);
              if ( $response->getHttpStatus() == 200 ) {
            if (!empty($response->facet_counts->facet_fields->$field)) {
              
	     	if(1 == $allmusic){
			$arr_result = array();
	     		$arr_result[$response->response->numFound][$type] = $response->facet_counts->facet_fields->$field; 
	     		return $arr_result;
		}else{
			return $response->facet_counts->facet_fields->$field;
		}
            } else {
              return array();
            }
          }
          else {
            return array();
          }
          return array();
          }
        }
        else{
            
        }
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

    function escapeSpace($keyword){
      //$keyword = mb_strtolower($keyword, 'UTF-8');
      $keyword = str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $keyword);
      //$keyword = utf8_decode($keyword);
      //$keyword = utf8_decode(str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $keyword));
      return $keyword;
    }
}


class SolrException extends Exception  { }
?>
