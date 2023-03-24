<?php

ini_set('display_errors', 0);
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require_once "./controller/controller.home.php";
require_once "./controller/controller.auth.php";
require_once "./controller/controller.dashboard.php";
require_once "./controller/controller.email.php";

require "./vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "./vendor/phpmailer/phpmailer/src/SMTP.php";
require "./vendor/phpmailer/phpmailer/src/Exception.php";

require_once './model/model.php';
require_once "./model/auth.model.php";
require_once "./model/food.model.php";
require_once "./model/user.model.php";

try {

    // sets a switch for the different routes
    // and assigns the correct controller
    $request = $_SERVER["PATH_INFO"];
    switch ($request) {
        case "/dashboard":
            $controller = new DashboardController();
            break;
        case "/login":
            $controller = new AuthController();
            break;
        case "/signup":
            $controller = new AuthController();
            break;
        case "/email":
            $controller = new EmailController();
            break;
        case "/home":
            $controller = new HomeController();
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


    // controller now handles request and displays page base on route
    if (isset($controller)) {
        $controller->handleRequest();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
