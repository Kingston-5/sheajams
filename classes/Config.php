<?php
/**
 * handles the global variable configuration
 */

class Config {

    /**
     * gets the values of the global configuration variables e.g mysql host, mysql password , session_name e.t.c
     * these are the variables set in init.php and will be needed all throughout the app
     * returns the value of a global variable or false if none has been given
     */
    public static function get($path = null) {
        if ($path){
            $config = $GLOBALS['config']; // - init.php -set config to array of 'mysql', 'remember', 'sessions'
            $path = explode('/', $path); //split path e.g mysql/password to 'mysql' , 'password' in-order to acces the mysql password

            foreach($path as $bit) { 
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;
        }

        return false;
    }
}
