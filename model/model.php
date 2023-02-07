<?php
/* DB is a class that connects to a database and 
has a few methods that allow you to query the database. */
class Model

{
    private $db;
    private $table;

    /* Table name is taken as a parameter on model instantiation. 
        It is stored into the table class variable. 
        A PHP Data Object stores the Data Source Name in order to establish
        connections with the MySQL Database. */
    function __construct($table)
    {
        $this->table = $table;
        try {
            $dsn = "{$_ENV['DRIVER']}:dbname={$_ENV['DATABASE']};
            host={$_ENV['HOST']};port={$_ENV['PORT']}";
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
            echo nl2br("Connected to the database\n");
            $this->db = $db;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
        echo nl2br("db class works\r\n\n");
    }

    private function prepareQueryStringFromArgs($argslen, $strarry, $mode)
    {
        if ($mode == "columns") {
            $res = "";
            for ($i = 0; $i < $argslen; $i++) {
                $res .= $strarry[$i] . ", ";
            }
            $res = substr($res, 0, -2);
            return $res;
        }
        if ($mode == "values") {
            $res = "";
            for ($i = 0; $i < $argslen; $i++) {
                $res .= ":" . $strarry[$i] . ", ";
            }
            $res = substr($res, 0, -2);
            return $res;
        }
    }

    /* This method is used to add users to the users table. */
    public function addUser($args = [])
    //arg syntax: ["result" => $result, "username" => $username, "password" => $password]
    //TODO: Add a parameter for the column names to make class more generic
    {
        extract($args);
        // var_dump($args);

        $columns = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "columns");
        $values = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "values");
        $sql_query = "INSERT INTO $this->table ($columns) VALUES ($values)";

        echo nl2br("columns: $columns\r\nvalues: $values\r\n\nsql_query: $sql_query\r\n\n");

        try {
            $stm = $this->db->prepare($sql_query);
            // The below is potentially securer
            // $stm->bindParam(':table', $this->table);
            for ($i = 0; $i < count($dbvars); $i++) {
                echo nl2br(":$dbtables[$i] || $dbvars[$i] -> ") . gettype($dbvars[$i]) . nl2br("\n\n");
                $stm->bindParam(":$dbtables[$i]", $dbvars[$i]);
            }
            echo nl2br("\n\n") . var_dump($stm);
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            return $error->getMessage() . PHP_EOL;
        }
    }   

    /* This method is used to fetch all users from the users table. */
    public function getAllFromTable()
    {
        try {
            $stm = $this->db->prepare("SELECT * FROM :table");
            $stm->bindParam(':table', $this->table);
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }
}
