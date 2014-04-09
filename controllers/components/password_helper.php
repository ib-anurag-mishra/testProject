<?php
 /**
 * Password generator function
 *
 * This function will randomly generate a password from a given set of characters
 *
 * @param int = 8, length of the password you want to generate
 * @return string, the password
 */

class PasswordHelperComponent extends Object
{
    function generatePassword ($length = 8)
    {
        // initialize variables
        $password = "";
        $i = 0;
        $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
 
        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
 
            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) { 
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    function generatePasswordWithout10 ($length = 8)
    {
        // initialize variables
        $password = "";
        $i = 0;
        $possible = "23456789bcdfghjkmnpqrstvwxyz"; 
 
        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
 
            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) { 
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }
}