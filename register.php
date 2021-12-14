
<?php 
require_once __DIR__ . '/core/init.php';
require_once __DIR__ . '/includes/header.inc.php';
?>
<body>
    <div class="wrapper">
        <section class="form signup">
            <header>Realtime Chat App</header>
            <form action="" method="post">
                <div class="error-text"></div>
                <div class="name-details">
                    <div class="field input">
                        <label for="name">First Name</label>
                        <input type="text" name="first_name" value="<?php echo escape(Input::get('first_name')); ?>" id="first_name">
                    </div>

                    <div class="field input">
                        <label for="name">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo escape(Input::get('last_name')); ?>" id="last_name">
                    </div>

                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="field input">
                    <label for="password_again">Password Again</label>
                    <input type="password" name="password_again" id="password_again" value="">
                </div>

                <div class="field button">
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" id="token">
                <input type="submit" id="submit" value="Register To Chat">
                </div>
                
            </form>
            <div class="link">Already signed up? <a href="login.php">Login now</a></div>
        </section>
    </div>

    <script>
        $(document).ready(function(){

            $('#submit').on('click', function(){
                event.preventDefault();

                var form = new FormData();
                form.append('first_name', $('#first_name').val());
                form.append('last_name', $('#last_name').val());
                form.append('email', $('#email').val());
                form.append('password', $('#password').val());
                form.append('password_again', $('#password_again').val());
                form.append('token', $('#token').val());

                $.ajax({
					method: 'POST',
					url: 'includes/register.inc.php',
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
</body>