<?php
ini_set('display_errors', 0);
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

phpinfo();
$dsn = "mysql:host={$_ENV['HOST']};dbname={$_ENV['DATABASE']}";
$options = array(PDO::MYSQL_ATTR_SSL_CA => $_ENV['MYSQL_ATTR_SSL_CA'],);

try {
    $pdo = new PDO($dsn, $_ENV['USERNAME'], $_ENV['PASSWORD'], $options);
    echo "Connected";
} catch (PDOException $error) {
    echo $error;
}
