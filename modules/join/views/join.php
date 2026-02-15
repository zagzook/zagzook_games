<h1 class="mt-1">Join <?= OUR_NAME ?></h1>
<p>To join <?= OUR_NAME ?>, please fill out the form below and then click the "Submit" button.</p>

<div class="container-xs">

    <?php
    echo form_open('join/submit', ['class' => 'highlight-errors']);

    echo form_label('Username');
    echo validation_errors('username');
    $username_attributes = [
        'placeholder' => 'Enter your username',
        'autocomplete' => 'off',

    ];
    echo form_input('username', $username, $username_attributes);

    echo form_label('First Name');
    echo validation_errors('first_name');
    $first_name_attributes = [
        'placeholder' => 'Enter your first name',
        'autocomplete' => 'off'
    ];
    echo form_input('first_name', $first_name, $first_name_attributes);

    echo form_label('Last Name');
    echo validation_errors('last_name');
    $last_name_attributes = [
        'placeholder' => 'Enter your last name',
        'autocomplete' => 'off'
    ];
    echo form_input('last_name', $last_name, $last_name_attributes);

    echo form_label('Email Address');
    echo validation_errors('email_address');
    $email_attributes = [
        'placeholder' => 'Enter your email address',
        'autocomplete' => 'off'
    ];
    echo form_input('email_address', $email_address, $email_attributes);



    ?>

    <div class="text-center">
        <?= anchor(BASE_URL, 'Cancel', ['class' => 'button alt']) ?>
        <?= form_submit('submit', 'Submit', ['class' => 'button']) ?>
    </div>


    <?= form_close() ?>
</div>