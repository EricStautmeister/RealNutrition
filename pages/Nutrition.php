<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/normalise.css">
</head>

<body>
    <div class="container">
        <h1>Real Nutrition</h1>
        <form action="datahandler.php" method="post">
            <div class="hwrapper">
                <section class="colsec">
                    <label for="meal">Meal</label>
                    <label for="calories">Calories</label>
                </section>
                <section class="colsec">
                    <input type="text" id="meal" name="meal">
                    <input type="text" id="calories" name="calories">
                </section>
            </div>
            <button type="submit" class="button">Submit</button>
        </form>
    </div>
    <div>
        <?php
        echo "Hello World";
        include '../Api/db.php';
        ?>
    </div>
</body>

</html>