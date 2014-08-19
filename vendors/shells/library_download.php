<?php
class LibraryDownloadShell extends Shell {
/**
 *
 * @return void
 * @access public
 */
    function main() {

         App::import('Component', 'Common');
         $this->common = &new CommonComponent();
         $result= $this->common->runGenreCacheFromShell(); 
    }

}

?>