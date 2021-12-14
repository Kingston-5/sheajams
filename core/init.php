<?php
/**
 * created to initialize the values of the global variables - such as the mysql connections and session names
 */

session_start();

//global variables
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db' => 'chat'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'sessions' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'ftp' => array(
        'servername' => 'localhost',
        'username' => 'TestUser',
        'password' => ''
    )
);

// register the given class in the autoload queue as _autoload() implementation
spl_autoload_register(function($class) {
    require_once __DIR__ . '/../classes/' . $class . '.php';
});

require_once __DIR__ . '/../functions/sanitize.php';
require_once __DIR__ . '/../functions/upload.php';

// check if the hash exists in the database
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('sessions/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    /** if the is more than one users_session, get the first one 
     * and create a new user instance based on that user session 
     * and then log that user in
     */
    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}