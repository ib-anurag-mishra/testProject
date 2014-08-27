<?php
class LibraryDownloadShell extends Shell {
/**
 *
 * @return void
 * @access public
 */
    function main() {

         App::import('Component', 'Downloads');
         $this->download = &new DownloadsComponent();
         $result= $this->download->generateReportLibraryLT100Downloads(); 
    }

}

?>