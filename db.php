<?php
ini_set('display_errors', 0);
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

function dbVersion($db) {
    try {
        $stm = $db->query("SELECT VERSION()");
        $version = $stm->fetch();

        return $version[0] . PHP_EOL;
    } catch (PDOException $error) {
        return $error;
    }
}

$dsn = "{$_ENV['DRIVER']}:host={$_ENV['HOST']};dbname={$_ENV['DATABASE']}";
$options = array(PDO::MYSQL_ATTR_SSL_CA => $_ENV['MYSQL_ATTR_SSL_CA'],);
try {
    $db = new PDO($dsn, $_ENV['USERNAME'], $_ENV['PASSWORD'], $options);
    echo dbVersion($db);
    
    return $db;
} catch (PDOException $error) {
    echo $error;
}
