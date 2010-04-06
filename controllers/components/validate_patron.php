<?php
Class ValidatePatronComponent extends Object
{
    var $components = array('Session');

    function validatepatron()
    {
        if(!isset($_SESSION['library']) && !isset($_SESSION['patron']))
        {
			echo 'no session set';
			return 0;
        }
        else
        {
           if($_SESSION['library'] != '' && $_SESSION['patron'] != '')
           {
                return 1;
           }
           else{
				echo 'session set';
                return 0;
           }
        }
    }
}