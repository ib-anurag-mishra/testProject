<?php
Class ValidatePatronComponent extends Component
{    
    var $components = array( 'Session');

    function validatepatron()
    {        
        if($_SESSION['library'] != '' && $_SESSION['patron'] != '')
        {
            return 1;
        }
        else{
            return 0;
        }
    }
    
}