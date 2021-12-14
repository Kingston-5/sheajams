<?php

/**
 * Created by Chris on 9/29/2014 3:52 PM.
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
                    <label for='email'>email</label>
                    <input type="text" name="email" id="email">
                </div>

                <div class="field input">
                    <label for='password'>Password</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="field input">
                    <label for="remember">Remember me</label>
                    <input type="checkbox" name="remember" id="remember">
                </div>

                <div class="field button">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" id="token">
                    <input type="submit" value="Login" id="submit">
                </div>
            </form>
            <div class="link">Not Signed up? <a href="register.php">SignUp Now</a></div>
        </section>
    </div>
</body>
<script>
    $(document).ready(function(){

$('#submit').on('click', function(){
    event.preventDefault();

    var form = new FormData();
    form.append('email', $('#email').val());
    form.append('password', $('#password').val());
    form.append('remember', $('#remember').val());
    form.append('token', $('#token').val());

    $.ajax({
        method: 'POST',
        url: 'includes/login.inc.php',
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
