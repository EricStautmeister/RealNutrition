<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition | Food</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="view/styles/normalise.css">
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/mail.css">
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
                    <li><a onclick="location.href='/about-us'">About Us</a></li>
                    <li><a onclick="location.href='/login'">Login</a></li>
                    <li><a onclick="location.href='/signup'">Signup</a></li>
                </ul>
            </div>
        </nav>

    </div>
    <div id="container">
        <form action="/dashboard" method="post" autocomplete="off">
            <input type="text" name="food">
            <input type="number" step="0.1" name="calories">
            <input type="submit" value="Upload">
        </form>
    </div>
</body>

</html>