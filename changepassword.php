<?php

/**
 * Created by Chris on 9/29/2014 3:53 PM.
 */

require_once __DIR__ . '/core/init.php';
require_once __DIR__ . '/includes/header.inc.php';


?>

<body>
    <div class="wrapper">
        <section class="form signup">
            <header>Realtime Chat App</header>
            <form action="" method="post">

                <div class="error-text"></div>
                <div class="field input">
                    <div class="field">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password">
                    </div>

                    <div class="field">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" id="new_password">
                    </div>

                    <div class="field">
                        <label for="new_password_again">New Password Again</label>
                        <input type="password" name="new_password_again" id="new_password_again">
                    </div>

                    <div class="field button">
                    <input type="hidden" name="token" id="token" value="<?php echo escape(Token::generate()); ?>">
                    <input type="submit" value="Change Password" id="submit">

                    </div>
            </form>
        </section>
    </div>
</body>
<script>
    $(document).ready(function(){

$('#submit').on('click', function(){
    event.preventDefault();

    var form = new FormData();
    form.append('current_password', $('#current_password').val());
    form.append('new_password', $('#new_password').val());
    form.append('new_password_again', $('#new_password_again').val());
    form.append('token', $('#token').val());

    $.ajax({
        method: 'POST',
        url: 'includes/changepassword.inc.php',
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
            if(response == 'success'){
                window.location.href = 'index.php';
            }else{
                $(".error-text").html(response);
            }
            
        },
    });

});

});
</script>