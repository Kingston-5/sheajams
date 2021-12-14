<?php
/**
 * Created to handle hashing of strings
 */

class Hash {
    /**
     * make a password hash
     */
    public static function make($string, $salt = '') {
        return password_hash($string, PASSWORD_DEFAULT);
        // return hash('sha256', $string . $salt);//create a password hash
    }

    /**
     * return cryptographically secure pseudo-random bytes 
     */
    public static function salt($length) {
        return random_bytes($length);
    }

    /**
     * return a unique identifier based on the current time in microseconds
     */
    public static function unique() {
        return self::make(uniqid());
    }
}