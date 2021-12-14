<?php

/**
 * Home page
 */

require_once __DIR__ . '/core/init.php';
require_once __DIR__ . '/includes/header.inc.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User(); 
?>

<body>
    <div class="wrapper">
        <section class="main">
        <header>SheaJams.com</header>
        <?php
        if ($user->isLoggedIn()) {
        ?>

            <p>Hello, <a href="profile.php?user=<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->first_name); ?></p>

            
        <?php

            if ($user->hasPermission('admin')) {
                echo '<p>You are a Administrator!</p>';
            }
        } else {
            echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register.</a></p>';
        }
        ?>
        </section>
    
    </div>
</body>
