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
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/dash.css">
    <link rel="stylesheet" href="styles/normalise.css">
</head>

<body>
    <button>
        <a href="/">Home</a>
    </button>
    <div class="user-data-wrapper">
        <?php
        $days = array(
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            ),
            array(
                [
                    "breakfast" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "lunch" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "dinner" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
                    "snack" => ["name" => "E", "meta" => ["calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]]
                ],
                "timestamp" => "2020-01-01"
            )
        );
        for ($i = 0; $i < count($days); $i++) {
            $day = $days[$i];

            echo "<div class='user-data'>";
            echo "<h2>Day " . $day["timestamp"] . count($days) . "</h2>";
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