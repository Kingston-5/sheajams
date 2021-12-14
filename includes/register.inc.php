<?php

/**
 * allows users to register
 */

require_once __DIR__ . '/../core/init.php';


if (Input::exists()) { // - access exists method of input - if the form input exists
    if (Token::check(Input::get('token'))) { //check token - fetched from $_GET variable - if it has been removed
        $validate = new Validate();
        $validate->check($_POST, array( // validate the form inputs
            'first_name' => array(
                'name' => 'First Name',
                'required' => true,
                'min' => 2,
                'max' => 25
            ),
            'last_name' => array(
                'name' => 'Last Name',
                'required' => true,
                'min' => 2,
                'max' => 25
            ),
            'email' => array(
                'name' => 'Email',
                'required' => true,
                'email' => true,
                'unique' => 'users'
            ),
            'password' => array(
                'name' => 'Password',
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
        ));

        if ($validate->passed()) { //if inputs have passed validation
            $user = new User(); // new user instance
            $salt = Hash::salt(32); //create a 32-char long salt
            try {
                $user->create(array(
                    'first_name' => Input::get('first_name'), //get $_POST['name']
                    'last_name' => Input::get('last_name'), //get $_POST['username']
                    'email' => Input::get('email'),
                    'password' => Hash::make(Input::get('password'), $salt), //generate passord hash from $_POST['password']
                    'salt' => $salt, //store password salt
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ));

                //display message
                Session::flash('home', 'Welcome ' . Input::get('username') . '! Your account has been registered. You may now log in.');
                echo 'success';
                // Redirect::to('index.php'); //redirect to index
            } catch (Exception $e) {
                echo $e->getTraceAsString(), '<br>'; //print out the stack trace
            }
        } else { //if for inputs have failed validation print out the errors
            foreach ($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }else {
        echo ' Sorry there was a Token error';
    }
} else {
    echo 'Sorry the input does not exist';
}
?>