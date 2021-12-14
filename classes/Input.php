<?php

/**
 * Created to manage all user input
 */

class Input
{
    /**
     * check if post or get or files variables are empty or not
     * returns true or false accordingly
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            case 'files':
                return (!empty($_FILES)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * if an item exists in $_POST or $_GET $_FILES return it else return  ' '
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        }else if (isset($_FILES[$item])) {
            return $_FILES[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}
