<?php if (empty($type)) {
    header("Location: /login");
} ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition | <?php echo ucfirst(substr($type, 1)) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="view/styles/normalise.css">
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/auth.css">
    <link rel="stylesheet" href="view/styles/navbar.css">
</head>

<body>
    <div class="overlay-container">
        <nav class="navbar">
            <div class="navbar-container container">
                <input type="checkbox" name="" id="">
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <ul class="menu-items">
                    <li><a onclick="location.href='/home'">Home</a></li>
                    <li><a onclick="location.href='/dashboard'">Dashboard</a></li>
                    <li><a onclick="location.href='/view/food.php'">About Us</a></li>
                    <li><a onclick="location.href='/login'">Login</a></li>
                    <li><a onclick="location.href='/signup'">Signup</a></li>
                </ul>
            </div>
        </nav>

    </div>
    <div id="content">
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
                        <p id="cp">Captcha</p><input id="new" name="new" type="submit" value="New">
                        <img id="captcha_img" src="./controller/captcha.php" alt="captcha">
                    </div>
                    <div class="captcha">
                        <p>Enter Captcha</p><input type="text" name="captcha">
                    </div>
                </div>
                <p id="err"><?php if (isset($err)) {
                                echo $err;
                            } ?></p>
                <input id="submit" type="submit" value="<?php echo ucfirst(substr($type, 1)) ?>">
            </form>
            <?php if ($type == "/login") {
                echo "<p>No account? <a href='/signup'>Sign Up</a></p>";
            } else {
                echo "<p>Have an account? <a href='/login'>Login</a></p>";
            } ?>
        </div>
    </div>
</body>

</html>