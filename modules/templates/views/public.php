<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= BASE_URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>images/favicon.png" type="image/x-icon">
    <?= $additional_includes_top ?? '' ?>
    <title>Welcome to <?= OUR_NAME ?></title>
</head>
<body>
    <div class="container">
        <div class="text-center"><?= display($data) ?></div>
    </div>
<?= $additional_includes_btm ?? '' ?>
</body>
</html>