<?php
/**
 * Created to handle user redirection for better UX
 */

class Redirect {
    public static function to($location = null) {
        if($location) {//if locationis not null 
            if(is_numeric($location)) {//of location is numeric
                switch($location) {
                    case 404://if location is 404 include error 404 page
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        break;
                }
            }
            // echo 'redirect to:' . __DIR__ . '/../' . $location;
            header('Location: '. __DIR__ . '/../' . $location);//redirect to location
            exit();
        }
    }
}