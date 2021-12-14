<?php

/**
 * Created by Chris on 9/29/2014 3:52 PM.
 */

require_once __DIR__ . '/core/init.php';
require_once __DIR__ . '/includes/header.inc.php';

if (!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);

    if (!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
?>

        <body>
            <div class="wrapper">
                <section class="main">
                    <header>Profile</header>
                </section>
                <h3>Name: <?php echo escape($data->first_name) . ' ' . escape($data->last_name); ?></h3>
                <p>Email: <?php echo escape($data->email); ?></p>
                <img style="width: 250px;height: 250px;" src="media/<?php echo escape($data->uniqueid). '/' .escape($data->profile_image)?>" alt="profilepicture">
                <ul>
                <li><a href="update.php?user=<?php echo escape($user->data()->email); ?>">Update Profile</a></li>
                <li><a href="changepassword.php">Change Password</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
            </div>
        </body>

<?php
    }
}
