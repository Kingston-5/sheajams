<?php

/**
 * Created by Chris on 9/29/2014 3:53 PM.
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
        <section class="form signup">
            <header>Realtime Chat App</header>
            <form action="" method="post">

                <div class="error-text"></div>
                <div class="field input">
                    <label for="name">First Name</label>
                    <input type="text" name="first_name" value="<?php echo escape($user->data()->first_name); ?>" id="first_name">
                </div>

                <div class="field input">
                    <label for="name">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo escape($user->data()->last_name); ?>" id="last_name">
                </div>

                <div class="field image">
                    <label>Profile picture</label>
                    <input type="file" name="image" id="image" value="<?php echo escape($user->data()->profile_image); ?>">
                </div>
                <div class="field button">

                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Update" id='submit'>
                </div>
            </form>
        </section>
    </div>
    <script>
        $(document).ready(function() {

            $('#submit').on('click', function() {
                event.preventDefault();

                var form = new FormData();
                form.append('first_name', $('#first_name').val());
                form.append('last_name', $('#last_name').val());
                form.append('image', $('#image').prop('files')[0]);
                form.append('token', $('#token').val());

                $.ajax({
                    method: 'POST',
                    url: 'includes/update.inc.php',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: form,
                    beforeSend: function() {
                        console.log('sending form');
                    },
                    complete: function() {
                        console.log('form sent:');
                    },
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'profile.php';
                        } else {
                            $(".error-text").html(response);
                        }

                    },
                });

            });

        });
    </script>
</body>

<?php }  }?>