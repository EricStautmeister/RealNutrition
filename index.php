<?php
// echo get_current_user() . PHP_EOL . '<br>';
// echo exec('whoami'); 
ini_set('display_errors', 0);
require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include "./pages/home.php";
