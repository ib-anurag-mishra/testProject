<?php
class GenreCronShell extends Shell {
/**
 * this funciton set Genre page cron
 *
 * @return void
 * @access public
 */
    function main() {
		 set_time_limit(0); 
         App::import('Component', 'Common');
         $this->common = &new CommonComponent();
         $result= $this->common->runGenreCacheFromShell(); 
    }

}

?>