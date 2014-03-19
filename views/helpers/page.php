<?php
/*
	 File Name : page.php
	 File Description : helper file for getting page detail
	 Author : m68interactive
 */
class PageHelper extends AppHelper {
    var $uses = array('Page');
    var $helpers = array('Session');
    
    function getPageContent($type) {
		if($this->Session->read('Config.language') == ''){
			$page = 'en';
		} 
		else {
			$page = $this->Session->read('Config.language');
		}
        $pageInstance = ClassRegistry::init('Page');
        $pageInstance = ClassRegistry::init('Page');
		if (($pageData = Cache::read("page".$page.$type)) === false) {
			$pageDetails = $pageInstance->find('all', array('conditions' => array('page_name' => $type, 'language' => $page)));
			Cache::write("page".$page.$type, $pageDetails);
		}
        $pageDetails = Cache::read("page".$page.$type);
		if(count($pageDetails) != 0) {
            return $pageDetails[0]['Page']['page_content'];
        }
        else {
            return "Coming Soon....";
        }
    }
	function isImage($url){
		$params = array('http' => array(
			  'method' => 'HEAD'
		   ));
		$ctx = stream_context_create($params);
		$fp = fopen($url, 'rb', false, $ctx);
		if (!$fp){ 
			return false;  // Problem with url
		}
		$meta = stream_get_meta_data($fp);
		if ($meta === false){
			fclose($fp);
			return false;  // Problem reading data from url
		}

		$wrapper_data = $meta["wrapper_data"];
		if(is_array($wrapper_data)){
			foreach(array_keys($wrapper_data) as $hh){
				if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image"){ // strlen("Content-Type: image") == 19 
					fclose($fp);
					return true;
				}
			}
		}

		fclose($fp);
		return false;
	}	
}

?>