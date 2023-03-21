<?php if (empty($email)) {
    header("Location: /");
} ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/normalise.css">
</head>

<body>
    an email has been sent to <?php echo $email ?>

    <script src="" async defer></script>
</body>

</html>