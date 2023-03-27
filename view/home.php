<?php if (!isset($foods)) {
    header("Location: /");
} ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition | Home</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="view/styles/normalise.css">
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/home.css">
    <link rel="stylesheet" href="view/styles/navbar.css">
    <link rel="stylesheet" href="view/styles/toggle.css">
</head>

<body>
    <div class="overlay-container">
        <nav class="navbar">
            <p class="user-name"><?php echo ucfirst($_SESSION["user"]) ?></p>

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
                    <!-- <li><a onclick="location.href='/about-us'">About Us</a></li> -->
                    <?php if (isset($_SESSION["user"])) {
                        echo "<li><a onclick=\"location.href='/logout'\">Logout</a></li>";
                    } else {
                        echo "<li><a onclick=\"location.href='/login'\">Login</a></li>";
                        echo "<li><a onclick=\"location.href='/signup'\">Signup</a></li>";
                    }?>
                    <!-- TODO: Session Token non existent on home, only on dashboard -->
                </ul>
            </div>
        </nav>

    </div>

    <div class="hero hero-section">
        <div class="hero-text-wrapper">
            <h1>Real Nutrition</h1>
            <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
            <button onclick="location.href='/login'">
                Login
            </button>
        </div>
    </div>
    </div>



    <div class="content">
        <div name="use" class="function-preview">
            <label for="preview" class="use">
                <h3>Test our functionality</h3>
            </label>
            <form class="preview" action="/home" method="post" autocomplete="off">
                <input name="food" list="food-selection" class="input" placeholder="Select a food" />
                <datalist id="food-selection" class="selection food-selection">
                    <?php
                    for ($i = 0; $i < count($foods); $i++) {
                        echo "<option class=\"food-option-$i\" value=\"$foods[$i]\"/>";
                    }
                    ?>
                </datalist>

                <input type="number" class="input" name="amount" placeholder="Enter amount" />

                <!-- <input name="amount" list="amount-selection" class="input" placeholder="Enter amount" /> -->
                <!-- <datalist id="amount-selection" class="selection amount-selection">
                    <?php
                    $amounts = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
                    for ($i = 0; $i < count($amounts); $i++) {
                        echo "<option class=\"food-option-$i\" value=\"$amounts[$i]\"/>";
                    }
                    ?>
                </datalist> -->

                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>

                <input type="submit" value="Calculate">
            </form>
            <div><?php if (isset($err)) {
                        echo $err;
                    } ?></div>
            <div>
                <?php var_dump($fooddata); ?>
            </div>
        </div>
        <div class="actual-content">
            <div class="content-section">
                <h2>What is Real Nutrition?</h2>
                <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily intake.</p>
            </div>
            <div class="content-section">
                <h2>How does it work?</h2>
                <p>We have a database full of various foodstuffs and the nutrients of which they are comprised. With our calculator and mealplanner you can plan and track what you eat.</p>
            </div>
            <div class="content-section">
                <h2>When can I use it?</h2>
                <p>Our calculator is already finished however our database still need to be populated and the planner has to be styled. However you can sign up via e-mail already and we'll keep you updated!</p>
                <button style="margin-top: 3rem;" onclick="location.href='/signup'">Sign Up</button>
            </div>
        </div>
    </div>

    <footer>
        Kantonsschule Büelrain
        RosenStrasse 1
        8400 Winterthur
        Copyright ©-All rights are reserved
    </footer>

</body>

</html>