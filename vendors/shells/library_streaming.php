<?php
class LibraryDownloadShell extends Shell {
/**
 *
 * @return void
 * @access public
 */
    function main() {

         App::import('Model', 'Library');
         $this->library = &new Library();
         $result= $this->library->updateLibraryStreamingStatus(); 
    }

}

?>