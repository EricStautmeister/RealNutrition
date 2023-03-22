<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition</title>
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
    <?php session_start();
    var_dump($_SESSION["uid"]); ?>
    <div class="hero hero-section">
        <div class="hero-text-wrapper">
            <h1>Real Nutrition</h1>
            <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
            <button>
                <a href="view/login.php">Login</a>
            </button>
        </div>
    </div>

    <div class="content">
        <div name="use" class="function-preview">
            <label for="preview" class="use">
                <h3>Test our functionality</h3>
            </label>
            <div class="preview">
                <input list="food-selection" class="input" placeholder="Select a food" />
                <datalist name="food" id="food-selection" class="selection food-selection">
                    <?php
                    for ($i = 0; $i < count($foods); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $foods[$i] . "' />";
                    }
                    ?>
                </datalist>

                <input list="meal-selection" class="input" placeholder="Select a meal" />
                <datalist name="meal" id="meal-selection" class="selection meal-selection">
                    <?php
                    $meals = array("breakfast", "lunch", "dinner", "snack");
                    for ($i = 0; $i < count($meals); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $meals[$i] . "' />";
                    }
                    ?>
                </datalist>

                <input list="amount-selection" class="input" placeholder="Select amount" />
                <datalist name="amount" id="amount-selection" class="selection amount-selection">
                    <?php
                    $amounts = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
                    for ($i = 0; $i < count($amounts); $i++) {
                        echo "<option class='food-option-" . $i . "' value='" . $amounts[$i] . "' />";
                    }
                    ?>
                </datalist>
            </div>
        </div>
        <div class="actual-content">
            <div class="content-section">
                <h2>What is Real Nutrition?</h2>
                <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
            </div>
            <div class="content-section">
                <h2>How does it work?</h2>
                <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
            </div>
            <div class="content-section">
                <h2>Why should I use it?</h2>
                <p>Real Nutrition is a website that helps you to keep track of your nutrition. You can add your own food and meals and keep track of your daily nutrition.</p>
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