<?php

ini_set('display_errors', 0);
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require_once './model/model.php';
require_once "./controller/controller.home.php";
require_once "./controller/controller.auth.php";



try {
    $request = $_SERVER["REQUEST_URI"];
    switch ($request) {
        case "/login":
            $controller = new AuthController();
            break;
        case "/signup":
            $controller = new AuthController();
            break;
        case "/":
            $controller = new HomeController();
            break;
        case "":
            $controller = new HomeController();
            break;
        default:
            echo "This page does not exist!";
            echo "<a href='/'>Go back Home</a>";
            break;
    }
    if (isset($controller)) {
        $controller->handleRequest();
    }
} catch (Exception $e) {
    echo "Exeption" . $e;
}
