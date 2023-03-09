<?php

class Query
{
    public $string = "";
    public $bindParams = array();
}


/**
 * Summary of ModelFactory
 */
class ModelFactory
{
    private $db;
    private $table;
    private $queries = array();
    private $ValueIncrementer = 0;
    private $selectQueryRes = array();

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
    //     return new ModelFactory($table);
    // }

    private function prepareQueryStringFromArgs(Query $query, array $args, string $mode, array $vals): string
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
                    array_push($query->bindParams, array(substr($res_str, 0, -2), $vals[$i]));
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
    public function createTable(array $columns, array $types): ModelFactory
    {
        $this->checkConnection();
        $query = "CREATE TABLE IF NOT EXISTS `{$this->table}` (";
        for ($i = 0; $i < count($columns); $i++) {
            $query .= "`{$columns[$i]}` {$types[$i]},";
        }
        $query .= "PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($query);
        return $this;
    }


    public function dropTable(): ModelFactory
    {
        //This method is special because it is the only one that executes the query immediately.
        $this->checkConnection();
        $this->db->exec("DROP TABLE IF EXISTS `{$this->table}`;");
        return $this;
    }

    public function insert(array $columns, array $values): ModelFactory
    {
        $this->checkConnection();
        $query = new Query;
        $query->string = "INSERT INTO `{$this->table}` (";
        $query->string = "INSERT INTO `{$this->table}` (";
        $query->string .= $this->prepareQueryStringFromArgs($query, $columns, "columns", $values);
        $query->string .= ") VALUES (";
        $query->string .= $this->prepareQueryStringFromArgs($query, $columns, "values", $values);
        $query->string .= ");";
        array_push($this->queries, $query);
        return $this;
    }

    public function select(string $column, string $data): array
    //TODO: Add limits and offsets
    {
        $this->checkConnection();
        $this->execute();
        $query = new Query;
        $query->string = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :{$column}_{$this->table}_{$this->ValueIncrementer};";
        $stmt = $this->db->prepare($query->string);
        $stmt->execute(array(":{$column}_{$this->table}_{$this->ValueIncrementer}" => $data));
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function selectAll(): array
    {
        $this->checkConnection();
        $this->execute();
        $query = "SELECT * FROM `{$this->table}`;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function update(string $controlColumn, string $controlData, array $columns, array $values): ModelFactory
    {
        $this->checkConnection();
        $query = new Query;
        $query->string = "UPDATE `{$this->table}` SET ";
        for ($i = 0; $i < count($columns); $i++) {
            $query->string .= "`{$columns[$i]}` = :{$columns[$i]}_{$this->table}_{$this->ValueIncrementer}, ";
            array_push($query->bindParams, array(":{$columns[$i]}_{$this->table}_{$this->ValueIncrementer}", $values[$i]));
            $this->ValueIncrementer++;
        }
        $query->string = substr($query->string, 0, -2);
        $query->string .= " WHERE `{$controlColumn}` = :{$controlColumn}_{$this->table}_{$this->ValueIncrementer};";
        array_push($query->bindParams, array(":{$controlColumn}_{$this->table}_{$this->ValueIncrementer}", $controlData));

        array_push($this->queries, $query);
        $this->ValueIncrementer++;
        return $this;
    }

    public function delete(string $column, string $data): ModelFactory
    {
        $this->checkConnection();
        $query = new Query;
        $query->string = "DELETE FROM `{$this->table}` 
            WHERE `{$column}` = :{$column}_{$this->table}_{$this->ValueIncrementer};";
        array_push($query->bindParams, array(":{$column}_{$this->table}_{$this->ValueIncrementer}", $data));

        array_push($this->queries, $query);
        $this->ValueIncrementer++;
        return $this;
    }

    public function printQueries(): ModelFactory
    {
        foreach ($this->queries as $query) {
            echo nl2br($query->string . PHP_EOL);
            foreach ($query->bindParams as $bps) {
                echo nl2br($bps[0] . " || " . $bps[1] . PHP_EOL);
            }
            echo nl2br("\n\n");
        }
        return $this;
    }

    public function execute(): ModelFactory
    //TODO: Implement Transactions
    {

        $connected = $this->checkConnection();
        $transactionStarted = $this->db->beginTransaction();
        try {
            if (!$connected) throw new Exception("Database connection failed.");
            if (!$transactionStarted) throw new Exception("Transaction failed to start.");

            foreach ($this->queries as $query) {
                $stmt = $this->db->prepare($query->string);
                foreach ($query->bindParams as $bps) {
                    $stmt->bindParam($bps[0], $bps[1]);
                }
                $stmt->execute();
            }
            $this->db->commit();
        } catch (Exception $error) {
            $this->db->rollBack();
            throw new Exception("Execution failed: " . $error->getMessage());
        }
        return $this;
    }

    function __destruct()
    {
        $this->db = null;
    }
}
