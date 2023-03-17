<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Real Nutrition</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="view/styles/index.css">
    <link rel="stylesheet" href="view/styles/normalise.css">
</head>

<body>
    <header>
        <form action="/" method="post">
            <input type="text" name="weight">
            <select name="mes">
                <option value="gramms">gramms</option>
                <option value="milliliters">milliliters</option>
            </select>
            <input list="food" name="foods">
            <datalist id="food">
                <?php foreach ($foods as $food) {
                    echo "<option value='$food' >";
                } ?>
            </datalist>

            <input type="submit">
        </form>
    </header>
    <button type="button" onclick="location.href='/login'">Login</button>
</body>

</html>