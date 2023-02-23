<?php if (empty($type)) {
    header("Location: /login");
} ?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Testing Form</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/normalise.css">

</head>

<body>

    <p>Auth <?php echo substr($type, 1) ?></p>

    <form action="<?php $type ?>" method="post">

        Input: <input type="email" name="name" value="<?php $name ?>"> <?php if ($res == "Name required") {
                                                                            echo $res;
                                                                        } ?>

        Pass: <input type="password" name="pwd"> <?php if ($res == "Password required") {
                                                        echo $res;
                                                    } ?>

        <input type="submit" name="submit" value="text_submit">

    </form>

    <?php if ($type == "/login") {
        echo "<a href='/signup'>Sign Up</a>";
    } ?>

    <?php
    echo $err;
    ?>

    <script src="" async defer></script>

</body>

</html>