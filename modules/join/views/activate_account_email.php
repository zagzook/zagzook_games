<p>Dear <?= out($first_name); ?> <?= out($last_name); ?>,</p>

<p>Thank you for creating an account with <?= OUR_NAME; ?>.</p>
<p>To activate your account, please click the link below:</p>
<p><?= anchor($activation_link); ?></a></p>

<p>If you did not create an account with us, please ignore this email.</p>

<p>Best regards,</p>
<p>The <?= OUR_NAME; ?> Team</p>