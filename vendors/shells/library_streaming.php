<?php
class LibraryDownloadShell extends Shell {
/**
 *
 * @return void
 * @access public
 */
    function main() {

         
         App::import('Component', 'Common');
         $this->Common = &new CommonComponent();
         $this->Common->callLibraryStreamingStatusCron(); 
    }

}

?>