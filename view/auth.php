<?php if (empty($type)) {
    header("Location: /login");
} ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./view/styles/index.css">
    <link rel="stylesheet" href="./view/styles/normalise.css">
    <link rel="stylesheet" href="./view/styles/auth.css">

</head>

<body>
    <div id="wrapper">
        <p id="title"><?php echo ucfirst(substr($type, 1)) ?></p>
        <form action="<?php echo $type ?>" method="post">
            <p class="label">Email</p> <input type="email" name="email" value="<?php if (isset($email)) {
                                                                                    echo $email;
                                                                                } ?>">
            <p class="label">Password</p> <input type="password" name="password" value="<?php if (isset($password)) {
                                                                                            echo $password;
                                                                                        } ?>">
            <div id="captcha_wrapper">
                <div class="captcha">
                    <p>Captcha</p><input id="new" type="submit" value="New">
                    <img id="captcha_img" src="./controller/captcha.php" alt="captcha">
                </div>
                <div class="captcha">
                    <p>Enter Captcha</p><input type="text" name="captcha">
                </div>
            </div>
            <input id="submit" type="submit" value="<?php echo ucfirst(substr($type, 1)) ?>">
        </form>
        <?php if ($type == "/login") {
            echo "<p>No account? <a href='/signup'>Sign Up</a></p>";
        } else {
            echo "<p>Have an account? <a href='/login'>Login</a></p>";
        } ?>
    </div>
</body>

</html>