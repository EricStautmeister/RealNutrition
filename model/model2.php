<?php

/**
 * Summary of SQLFactory
 */
class SQLFactory
{
    private $db;
    private $table;
    private $queries = array();
    private $bindParams = array();
    private $ValueIncrementer = 0;

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
            $this->db = $db;
        } catch (PDOException $error) {
            throw new Exception($error->getMessage());
        }
    }

    //UTILITY METHODS
    function checkConnection(): bool
    {
        if ($this->db) {
            return true;
        }
        return false;
    }

    // public static function getInstance($table)
    // {
    //     return new SQLFactory($table);
    // }

    private function prepareQueryStringFromArgs(array $args, string $mode, array $vals): string
    {
        if (count($args) < 1) {
            throw new Exception("The number of arguments must be greater than 0.");
        }
        if ($mode != "columns" && $mode != "values") {
            throw new Exception("The mode must be either 'columns' or 'values'.");
        }
        try {
            $res = "";
            for ($i = 0; $i < count($args); $i++) {
                $uuid = "_" . $this->table . "_" . $this->ValueIncrementer;
                if ($mode == "columns") {
                    $res .= $args[$i] . ", ";
                } else if ($mode == "values") {
                    $res_str = ":" . $args[$i] . $uuid . ", ";
                    $res .= $res_str;
                    array_push($this->bindParams, array($res_str, $vals[$i]));
                    $this->ValueIncrementer++;
                }
            }
            $res = substr($res, 0, -2);
            return $res;
        } catch (Exception $error) {
            throw new Exception("Query string preparation failed: " . $error->getMessage());
        }
    }

    //QUERY METHODS
    public function createTable(array $columns, array $types): SQLFactory
    {
        $this->checkConnection();
        $query = "CREATE TABLE IF NOT EXISTS `{$this->table}` (";
        for ($i = 0; $i < count($columns); $i++) {
            $query .= "`{$columns[$i]}` {$types[$i]},";
        }
        $query .= "PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        array_push($this->queries, $query);
        return $this;
    }


    public function dropTable(): SQLFactory
    {
        //This method is special because it is the only one that executes the query immediately.
        $this->checkConnection();
        $query = "DROP TABLE IF EXISTS `{$this->table}`;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $this;
    }

    public function insert(array $columns, array $values): SQLFactory
    {
        $this->checkConnection();
        $query = "INSERT INTO `{$this->table}` (";
        $query .= $this->prepareQueryStringFromArgs($columns, "columns", $values);
        $query .= ") VALUES (";
        $query .= $this->prepareQueryStringFromArgs($columns, "values", $values);
        $query .= ");";
        array_push($this->queries, $query);
        return $this;
    }

    public function select(string $column, string $data): SQLFactory
    {
        $this->checkConnection();
        $query = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :{$column}_{$this->table}_{$this->ValueIncrementer};";
        array_push($this->queries, $query);
        array_push($this->bindParams, array(":{$column}_{$this->table}_{$this->ValueIncrementer}", $data));
        $this->ValueIncrementer++;
        return $this;
    }

    public function selectAll(): SQLFactory
    {
        $this->checkConnection();
        $query = "SELECT * FROM `{$this->table}`;";
        array_push($this->queries, $query);
        return $this;
    }

    public function printQueries(): SQLFactory
    {
        foreach ($this->queries as $query) {
            echo nl2br($query . PHP_EOL);
        }
        echo nl2br("\n\n\n");
        foreach ($this->bindParams as $bps) {
            echo nl2br($bps[0] . " || " . $bps[1] . PHP_EOL);
        }
        return $this;
    }

    public function execute(): SQLFactory
    {
        // $this->checkConnection();
        // $hasStarted = $this->db->beginTransaction();

        try {
            $finalQuery = "";

            foreach ($this->queries as $query) {
                $finalQuery .= $query;
            }
            $stmt = $this->db->prepare($finalQuery);
            foreach ($this->bindParams as $bps) {
                $pm = substr($bps[0], 0, -2);
                $stmt->bindParam($pm, $bps[1]);
            }

            $stmt->execute();
            $isActive = $this->db->inTransaction();

            // var_dump($stmt);
            // echo nl2br("\n\n\n{$hasStarted} + {$isActive}\n\n\n");
            // $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $this->db->commit();
        } catch (Exception $error) {            
            // $this->db->rollBack();
            throw new Exception("Transaction failed: " . $error->getMessage());
        }
        return $this;
    }

    // abstract function update($columns, $values);
    // abstract function delete();
    // abstract function where($where);

    function __destruct()
    {
        $this->db = null;
    }
}
