<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: /login");
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        Real Nutrition | Dashboard
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/dash.css">
    <link rel="stylesheet" href="view/styles/normalise.css">
</head>

<body>
    <div class="semi-header">
        <button style="position: relative; left: 6.5rem;" onclick="location.href='/'">
            Home
        </button>
        <p class="UserName"><?php echo $_SESSION["user"] ?></p>
    </div>
    <div class="user-data-wrapper">
        <?php
        $days = array(
            array(
                [
                    "breakfast" => ["name" => "Eggs", "meta" => ["calories" => 78, "protein" => 9, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "Fish", "meta" => ["calories" => 234, "protein" => 40, "carbs" => 3, "fat" => 0.4]],
                    "dinner" => ["name" => "Curry with Naaan", "meta" => ["calories" => 708, "protein" => 20, "carbs" => 87.6, "fat" => 15.3]],
                    "snack" => ["name" => "Bard", "meta" => ["calories" => 20, "protein" => 3, "carbs" => 5, "fat" => 2003]]
                ],
                "timestamp" => "2023-07-21"
            ),
            array(
                [
                    "breakfast" => ["name" => "Apple", "meta" => ["calories" => 30, "protein" => 0.3, "carbs" => 30, "fat" => 0.5]],
                    "lunch" => ["name" => "Donut", "meta" => ["calories" => 260, "protein" => 63, "carbs" => 40.6, "fat" => 53]],
                    "dinner" => ["name" => "Airplane", "meta" => ["calories" => 780000, "protein" => 600.3, "carbs" => -200.6, "fat" => 540.3]],
                    "snack" => ["name" => "Efeel", "meta" => ["calories" => 1208, "protein" => 3.3, "carbs" => 0.6, "fat" => 8.3]]
                ],
                "timestamp" => "1998-32-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "tatiana", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "red hering", "meta" => ["calories" => 798, "protein" => 56.3, "carbs" => 12.6, "fat" => 5.3]],
                    "dinner" => ["name" => "Pussy", "meta" => ["calories" => -213, "protein" => 6, "carbs" => 3, "fat" => 1.23456321]],
                    "snack" => ["name" => "Eee", "meta" => ["calories" => 58, "protein" => 9.4, "carbs" => 0.3, "fat" => 3]]
                ],
                "timestamp" => "2030-02-30"
            ),
            array(
                [
                    "breakfast" => ["name" => "Eggs", "meta" => ["calories" => 78, "protein" => 9, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "Donut", "meta" => ["calories" => 260, "protein" => 63, "carbs" => 40.6, "fat" => 53]],
                    "dinner" => ["name" => "Airplane", "meta" => ["calories" => 780000, "protein" => 600.3, "carbs" => -200.6, "fat" => 540.3]],
                    "snack" => ["name" => "Eee", "meta" => ["calories" => 58, "protein" => 9.4, "carbs" => 0.3, "fat" => 3]]
                ],
                "timestamp" => "2023-04-05"
            ),
            array(
                [
                    "breakfast" => ["name" => "tatiana", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "Fish", "meta" => ["calories" => 234, "protein" => 40, "carbs" => 3, "fat" => 0.4]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "Eee", "meta" => ["calories" => 58, "protein" => 9.4, "carbs" => 0.3, "fat" => 3]]
                ],
                "timestamp" => "2024-11-31"
            ),
            array(
                [
                    "breakfast" => ["name" => "Apple", "meta" => ["calories" => 30, "protein" => 0.3, "carbs" => 30, "fat" => 0.5]],
                    "lunch" => ["name" => "Fish", "meta" => ["calories" => 234, "protein" => 40, "carbs" => 3, "fat" => 0.4]],
                    "dinner" => ["name" => "Pussy", "meta" => ["calories" => -213, "protein" => 6, "carbs" => 3, "fat" => 1.23456321]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            )
        );
        for ($i = 0; $i < count($days); $i++) {
            $day = $days[$i];

            echo "<div class='user-data'>";
            echo "<h2>Day " . $day["timestamp"] . "</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Meal</th>";
            echo "<th>Food</th>";
            echo "<th>Calories</th>";
            echo "<th>Protein</th>";
            echo "<th>Carbs</th>";
            echo "<th>Fat</th>";
            echo "</tr>";

            $mealObject = $day[0];
            $meals = array_keys($mealObject);

            for ($j = 0; $j < count($mealObject); $j++) {
                $meal = $meals[$j];
                $foodItem = $mealObject[$meal];

                echo "<tr>";
                echo "<td>" . $meal . "</td>";
                echo "<td>" . $foodItem["name"] . "</td>";
                echo "<td>" . $foodItem["meta"]["calories"] . "</td>";
                echo "<td>" . $foodItem["meta"]["protein"] . "</td>";
                echo "<td>" . $foodItem["meta"]["carbs"] . "</td>";
                echo "<td>" . $foodItem["meta"]["fat"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";
        }
        ?>
    </div>

    <footer>
        Kantonsschule Büelrain
        RosenStrasse 1
        8400 Winterthur
        Copyright ©-All rights are reserved
    </footer>
</body>

</html>