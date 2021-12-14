<?php

require_once __DIR__ . '/../core/init.php';

if (Input::exists()) { // if form inputs exist
    if (Token::check(Input::get('token'))) { //?

        $validate = new Validate();
        $validate->check($_POST, array( // chack if username and password adhere to the rules
            'email' => array('required' => true),
            'password' => array('required' => true),
        ));

        if ($validate->passed()) { //if the inputs passed the validation
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false; //check if remember me is on or off
            // var_dump(Input::get('username'), Input::get('password'), $remember);
            $login = $user->login(Input::get('email'), Input::get('password'), $remember); // attempt to login user

            if ($login) {
                // Redirect::to('index.php');
                echo 'success';
            } else {
                echo '<p>Incorrect username or password - </p>';
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>