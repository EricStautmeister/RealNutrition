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
    <link rel="stylesheet" href="view/styles/normalise.css">
</head>

<body>

    <?php
    $userdata = [
        [0] => [
            "breakfast" => [["name" => "Egg", "calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3]],
            "lunch" => ["food" => "Chicken", "calories" => 165, "protein" => 31, "carbs" => 0, "fat" => 3.6],
            "dinner" => ["food" => "Salad", "calories" => 15, "protein" => 1.4, "carbs" => 2.4, "fat" => 0.4],
            "snack" => ["food" => "Apple", "calories" => 52, "protein" => 0.3, "carbs" => 13.8, "fat" => 0.2]
        ],
        [1] => [
            "breakfast" => ["food" => "Egg", "calories" => 78, "protein" => 6.3, "carbs" => 0.6, "fat" => 5.3],
            "lunch" => ["food" => "Chicken", "calories" => 165, "protein" => 31, "carbs" => 0, "fat" => 3.6],
            "dinner" => ["food" => "Salad", "calories" => 15, "protein" => 1.4, "carbs" => 2.4, "fat" => 0.4],
            "snack" => ["food" => "Apple", "calories" => 52, "protein" => 0.3, "carbs" => 13.8, "fat" => 0.2]
        ],

    ];

    for ($i = 0; $i < count($userdata); $i++) {
        $day = $userdata[$i];

        var_dump($day);

        // echo "<div class='user-data'>";
        // echo "<h2>Day " . ($i + 1) . "</h2>";
        // echo "<table>";
        // echo "<tr>";
        // echo "<th>Meal</th>";
        // echo "<th>Food</th>";
        // echo "<th>Calories</th>";
        // echo "<th>Protein</th>";
        // echo "<th>Carbs</th>";
        // echo "<th>Fat</th>";
        // echo "</tr>";
        // foreach ($day as $meal) {
        //     foreach ($meal as $fooditem) {
        //         echo "<tr>";
        //         echo "<td>" . $meal . "</td>";
        //         echo "<td>" . $data[0]["food"] . "</td>";
        //         echo "<td>" . $data[0]["calories"] . "</td>";
        //         echo "<td>" . $data[0]["protein"] . "</td>";
        //         echo "<td>" . $data[0]["carbs"] . "</td>";
        //         echo "<td>" . $data[0]["fat"] . "</td>";
        //         echo "</tr>";
        //     }
        // }
        // echo "</table>";
        // echo "</div>";
    }



    ?>

</body>

<footer>
    Kantonsschule Büelrain
    RosenStrasse 1
    8400 Winterthur
    Copyright ©-All rights are reserved
</footer>

</html>
