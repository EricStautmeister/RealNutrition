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
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/home.css">
    <link rel="stylesheet" href="view/styles/normalise.css">
    <script src="" async defer></script>

</head>

<body>
    <header>
        <form action="" method="post"></form>
    </header>
    <div class="hero hero-section">
        <div class="hero-text-wrapper">
            <h1>Real Nutrition</h1>
            <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
            <button onclick="location.href='/login'">
                Login
            </button>
        </div>
    </div>

    <div class="content">
        <div name="use" class="function-preview">
            <label for="preview" class="use">
                <h3>Test our functionality</h3>
            </label>
            <form class="preview" action="/" method="post">
                <input name="food" list="food-selection" class="input" placeholder="Select a food" />
                <datalist id="food-selection" class="selection food-selection">
                    <?php
                    for ($i = 0; $i < count($foods); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $foods[$i] . "' />";
                    }
                    ?>
                </datalist>

                <select name="meal" id="measure-selection" placeholder="unit">
                    <?php
                    $measures = array("g", "ml", "oz", "fl oz", "kg", "L", "lbs");
                    for ($i = 0; $i < count($measures); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $measures[$i] . "' />";
                    }
                    ?>
                </select>

                <input name="amount" list="amount-selection" class="input" placeholder="Select amount" />
                <datalist id="amount-selection" class="selection amount-selection">
                    <?php
                    $amounts = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
                    for ($i = 0; $i < count($amounts); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $amounts[$i] . "' />";
                    }
                    ?>
                </datalist>
                <input type="submit" value="Calculate">
            </form>
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