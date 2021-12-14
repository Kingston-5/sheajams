<?php

require_once __DIR__ . '/../core/init.php';
require_once __DIR__ . '/../includes/header.inc.php';

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(!Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validate->check($_POST, array(
            'first_name' => array(
                'name' => 'First Name',
                'min' => 2,
                'max' => 25
            ),
            'last_name' => array(
                'name' => 'Last Name',
                'min' => 2,
                'max' => 25
            )
        ));

        $validate->check($_FILES, array(
            'image' => array(
                'name' => 'Profile Picture',
                'file' => true
            ), 
        ));

        // var_dump(Config::get('ftp/username'));
        // exit();
        if(empty($validate->errors())) {
            try {
                $time = time();
                $new_img_name = $time.Input::get('image')['name'];

                $user->update(array(
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'profile_image' => $new_img_name
                ));

               
                if(ftp_file(Config::get('ftp/servername'), 
                    Config::get('ftp/username'), 
                    Config::get('ftp/password'), 
                    Input::get('image')['tmp_name'], 
                    $user->data()->uniqueid, 
                    $new_img_name )
                ){
                    Session::flash('home', 'Your details have been updated.');
                    // Redirect::to('index.php');
                    echo 'success';
                } else {
                    echo 'Sorry an upload error ocured';
                }

                
            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validate->errors() as $error) {
                echo $error, '<br>';
            }
        }


        

       
    }else {
        echo ' Sorry there was a Token error';
    }
} else {
    echo 'Sorry the input does not exist';
}

?>