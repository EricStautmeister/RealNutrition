<?php

// echo htmlspecialchars(); 

function postReqHandler() {
    $meal = htmlspecialchars($_POST['meal']);
    $calories = htmlspecialchars((int)$_POST['calories']);
    return "{$meal}: {$calories}";
}
echo postReqHandler();
