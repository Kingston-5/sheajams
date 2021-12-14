<?php

/**
 * Created to handle user actions
 */

class User
{
    private $_db,
        $_data,
        $_sessionName,
        $_cookieName,
        $isLoggedIn;


    /**
     * User contructor.
     * first get the Config variables, then attempt to identify the user data either using the 
     * session data or from the database 
     * if the user has been identified and if the session exists get the user data from the session data
     * if the user data can be found in the db log them in else log them out
     * But if the user has not been identified do so by getting their data from the database
     */
    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('sessions/session_name'); 
        $this->_cookieName = Config::get('remember/cookie_name'); 

        if (!$user) { 
            if (Session::exists($this->_sessionName)) { 
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) { 
                    $this->isLoggedIn = true;
                } else {
                    $this->isLoggedIn = false;
                }
            }
        } else {
            $this->find($user); 
        }
    }

    /**
     * Create a new user by inserting the user data into the database table users
     * 
     */
    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('Sorry, there was a problem creating your account;');
        }
    }

    /**
     * Update the user credentials
     * if the user id is null and user is logged in then fetch the id from db
     * if the db update fails then throw a exception
     */
    public function update($fields = array(), $id = null)
    {

        if (!$id && $this->isLoggedIn()) { 
            $id = $this->data()->id;
        }

        if (!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating'); 
        }
    }

    /**
     * find the user in the database using either their id or email
     * if user is not null and is numeric use the id to get their data from the database else use their email
     * and use the first match in the db
     */
    public function find($user = null)
    {
        if ($user) { 
            $field = (is_numeric($user)) ? 'id' : 'email'; 
            $data = $this->_db->get('users', array($field, '=', $user)); 

            if ($data->count()) { 
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    /**
     * Log the user in 
     * 
     * if the email & password are not null and the user exists/has been identified then put his id into the session name
     * because theyre already logged in else find them in the db, verify the password and initialize the session
     * 
     * if the user has chosen to be remembered checck for any existing sessions in the db or create one and use them to 
     * create the remember me cookie
     * 
     */
    public function login($email = null, $password = null, $remember = false)
    {

        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($email); 
            if ($user) {
                if (password_verify($password, $this->data()->password)) { 
                    Session::put($this->_sessionName, $this->data()->id); 

                    if ($remember) {
                        $hash = Hash::unique(); 
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id)); 

                        if (!$hashCheck->count()) { 
                            $this->_db->insert('users_session', array( 
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash; 
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry')); 
                    }

                    return true;
                }
            }
        }
        return false;
    }

    /**
     * check the users group and return the permissions associated with that group 
     */
    public function hasPermission($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->group));

        if ($group->count()) {
            $permissions = json_decode($group->first()->permissions, true);

            return !empty($permissions[$key]);
        }

        return false;
    }

    /**
     * check if user data has been identified meaning that they exist
     */
    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    /**
     * log the user out by deleting the session and cookies
     */
    public function logout()
    {
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    /**
     * return the users data
     */
    public function data()
    {
        return $this->_data;
    }

    /**
     * return the users logged in status
     */
    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }
}
