<?php
class CacheCronShell extends Shell {
/**
 * this funciton set Genre page log
 *
 * @return void
 * @access public
 */
    function main() {
         App::import('Component', 'Common');
         $this->common = &new CommonComponent();
         $result= $this->common->runGlobalCacheFromShell(); 
    }
}
?>