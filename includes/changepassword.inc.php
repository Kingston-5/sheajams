<?php
require_once __DIR__ . '/../core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {//if user is not logged in redirect to index
    Redirect::to('index.php');
}

if(Input::exists()) {//if form input exists, check if session exists before validating input
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'current_password' => array(
                'required' => true,
                'min' => 6
            ),
            'new_password' => array(
                'required' => true,
                'min' => 6
            ),
            'new_password_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'new_password'
            )
        ));
    }

    if($validate->passed()) {//if validation passed  verify current password
        if(!password_verify(Input::get('current_password'), $user->data()->password)) {
            echo 'Your current password is wrong.';
        } else {
            $salt = Hash::salt(32);
            $user->update(array(//update password
                'password' => Hash::make(Input::get('new_password'), $salt),
                'salt' => $salt
            ));

            Session::flash('home', 'Your password has been changed!');//flash message
            // Redirect::to('index.php');//return to index
            echo 'success';
        }
    } else {
        foreach($validate->errors() as $error) {
            echo $error, '<br>';//if there are any erros echo them out
        }
    }
}

?>