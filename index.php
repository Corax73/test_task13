<?php

require_once  __DIR__ . '/vendor/autoload.php';
require_once 'config/const.php';
include 'src/main.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret santa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Secret santa</h1>
        <form class="form-inline" method="POST" action="">
            <div class="form-group mx-sm-3 mb-2">
                <label for="inputPassword2" class="sr-only">Enter your email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="email">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Confirm your entry</button>
        </form>
        <?php if (isset($message['no_santa'])) {?><span class="text-danger"><?= $message['no_santa']; ?></span><?php } ?>
        <?php if (isset($message['santa'])) {?><span class="text-success"><?= $message['santa']; ?></span><?php } ?>
    </div>
</body>

</html>