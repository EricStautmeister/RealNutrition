<?php
ini_set('display_errors', 0);
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

function dbVersion($db)
{
    try {
        $stm = $db->query("SELECT VERSION()");
        $version = $stm->fetch();

        return $version[0] . PHP_EOL;
    } catch (PDOException $error) {
        return $error;
    }
}

try {
    $dsn = "{$_ENV['DRIVER']}:host={$_ENV['HOST']};dbname={$_ENV['DATABASE']}";
    $db = new PDO(
        $dsn,
        $_ENV['USERNAME'],
        $_ENV['PASSWORD'],
        array(
            // PDO::MYSQL_ATTR_SSL_KEY    =>'/path/to/client-key.pem',
            // PDO::MYSQL_ATTR_SSL_CERT=>'/path/to/client-cert.pem',
            
            // PDO::MYSQL_ATTR_SSL_CA    => $_ENV['MYSQL_ATTR_SSL_CA']
        )
    );
    echo dbVersion($db);
    echo "Connected to the database" . PHP_EOL;
} catch (PDOException $error) {
    echo $error;
}
