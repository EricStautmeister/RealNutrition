<?php

function connectDB()
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
        return $db;
    } catch (PDOException $error) {
        echo $error;
        return null;
    }
}
