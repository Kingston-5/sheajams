<?php
/**
 * Created to handle all Cookie transactions
 */

class Cookie {
    /**
     * check if a cookie exists and return true or false accordingly
     */
    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /** returns the name of the desired cookie */
    public static function get($name) {
        return $_COOKIE[$name];
    }

    /** create/set a cookie
     * $name - name of the cookie
     * $value - value of the cookie
     * $expiry - expiry of the cookie
     * return true or false according to the success status of creating the cookie
     */
    public static function put($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/')) {//if cookie is create return true
            return true;
        }
        return false;
    }

    /**delete a cookie by recreating it but with a negative expiry date
     * thus it will instantly expire
     */
    public static function delete($name) {
        self::put($name, '', time() -1);
    }
}