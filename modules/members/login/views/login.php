<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= BASE_URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/trongate.css">
    <link rel="stylesheet" href="members-login_module/css/login.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <h1>Login Page</h1>
        <div class="text-center mt-3">
            <?php
            echo validation_errors();
            echo form_open('members-login/submit_login', ['class' => 'highlight-errors']);

            echo form_label('Username or Email Address');
            $username_attributes = [
                'placeholder' => 'Enter your username or email address',
                'autocomplete' => 'off'
            ];
            echo form_input('username', '', $username_attributes);

            echo form_label('Password');
            $password_attributes = [
                'placeholder' => 'Enter your password',
                'autocomplete' => 'off'
            ];
            echo form_password('password', '', $password_attributes);
            ?>

            <div class="text-center">
                <?= anchor(BASE_URL, 'Cancel', ['class' => 'button alt']) ?>
                <?= form_submit('submit', 'Submit', ['class' => 'button']) ?>
            </div>


            <?= form_close() ?>
        </div>
        <p class="text-center">Not a member? <?= anchor('join', 'Join Now!') ?></p>
    </div>

</body>

</html>