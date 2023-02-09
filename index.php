<?php

// includes all controllers and models
require_once "./controller/controller.home.php";
require_once "./controller/controller.auth.php";
require_once "./controller/controller.dashboard.php";

// controller router uses a different controller for different routes
try {

    // sets a switch for the different routes
    // and assigns the correct controller
    $request = $_SERVER["REQUEST_URI"];
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
    echo "Exeption" . $e;
}
