<?php
/**
 * Created to handle the the session variables
 */

class Session {
    /**
     * check if the session variable exists and return true or false accordingly
     */
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     * returns the new value of the session variable
     * $name - name of the variable to change
     * $value - new value to assign 
     */
    public static function put($name, $value) { 
        return $_SESSION[$name] = $value;
    }

    /** 
     * return the value of session variable
     */
    public static function get($name) {
        return $_SESSION[$name];
    }

    /** delete session variable */
    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Used to flash messages to the user.
     * if the desired session varible exists destroy its value and return it empty
     * else just create a new, empty variable
     */
    public static function flash ($name, $string = 'null') {
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
}