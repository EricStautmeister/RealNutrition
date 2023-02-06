<?php

ini_set('display_errors', 0);
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

define('EOL', '<br>');
// include './db_connection.php';

/* DB is a class that connects to a database and 
has a few methods that allow you to query the database. */
class DB
{
    private $db;
    function __construct()
    {
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
            echo "Connected to the database" . PHP_EOL;
            $this->db = $db;
        } catch (PDOException $error) {
            echo $error;
        }
        echo "db class works";
    }

    public function getUsers()
    {
        try {
            $stm = $this->db->query("SELECT * FROM users");

            return $stm;
        } catch (PDOException $error) {
            return $error;
        }
    }

    public function addUser($username, $password)
    {
        try {
            return $this->db->query("INSERT INTO users (username, password) VALUES ($username, $password)");
        } catch (PDOException $error) {
            return $error;
        }
    }
}
