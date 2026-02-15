<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h1>Members Dashboard</h1>
    <p>Welcome, <?= $data['member_obj']->first_name ?> <?= $data['member_obj']->last_name ?>!</p>
    <p>Your member ID is <?= $data['member_obj']->id ?></p>
    <p>You are logged in as a <?= $data['member_level'] ?> member.</p>

    <?= anchor($data['logout_url'], 'Logout', ['class' => 'button']) ?>
</body>

</html>