<?php
Class ValidatePatronComponent extends Object
{    
    var $components = array( 'Session');

    function validatepatron()
    { 
        
        if(!isset($_SESSION['library']) && !isset($_SESSION['patron']))
        {
            
            return 0;
        }
        else
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
    
}