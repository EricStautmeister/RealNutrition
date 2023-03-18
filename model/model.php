<?php

/**
 * This class represents a query object.
 * It contains the query string and the bind parameters.
 */
class Query {
    public $string = "";
    public $bindParams = array();
}

/**
 * This class is a factory for creating models.
 * It contains methods for creating tables, inserting data, getting data, updating data, and deleting data.
 * 
 * To use this class, create a new instance of it and pass the name of the table to be created.
 * You can then chain the methods to create queries, and by ending the chain with an execute() you execute the queries
 * in the most efficient way by making use of SQL Transactions.
 * 
 * Example:
 * $model = new ModelFactory("table_name");
 * $model->createTable(TABLE_NAME)
 *      ->insertData(MOCK_DATA)
 *     ->execute()
 * 
 * The above example creates a table named "table_name" and inserts mock data into it.
 * 
 * The methods implemented in this class are:
 *  1. createTable(): Creates a table with the specified name.
 *  2. dropTable(): Drops the table with the specified name.
 *  3. checkConnection(): Checks if the database connection is established.
 *  4. checkDataExistence(): Checks if the data exists in the table.
 *  5. insert(): Inserts data into the table.
 *  6. 1. select(): Selects specific data from the table.
 *  6. 2. selectAll(): Selects all data from the table.
 *  7. update(): Updates data in the table.
 *  8. delete(): Deletes data from the table.
 *  9. execute(): Executes the queries.
 * 
 * @param string $table The name of the table to be created.
 */
class ModelFactory {

    /**
     * The database connection.
     * @var PDO
     * @access private
     */
    private $db;

    /**
     * The name of the table to be created.
     * @var string
     * @access private
     */
    private $table;
    /**
     * The queries to be executed.
     * @var array of Query objects
     * @access private
     */
    private $queries = array();
    /**
     * An execution incrementer to differentiate between bind parameters.
     * @var int 
     * @access private
     */
    private $ValueIncrementer = 0;

    function __construct($table) {
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

    /******************** UTILITY METHODS ********************************/

    /**
     * This method checks if the database connection is established.
     * @return bool Returns true if the database connection is established, false otherwise.
     */
    function checkConnection(): bool {
        if ($this->db) {
            return true;
        }
        return false;
    }

    /**
     * This method checks if specific data in a table exists.
     * @return bool Returns true if the data exists, false otherwise.
     */
    public function checkDataExistence(string $column, string $hasData): bool {
        $this->checkConnection();
        $query = "SELECT EXISTS (SELECT * FROM $this->table WHERE $column = :hasData)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':hasData', $hasData);
        $stmt->execute();
        $res = $stmt->fetch();
        return ($res[0] == 1 ? true : false);
    }

    /**
     * This method prepares the query string from the arguments.
     * @param Query $query The query object.
     * @param array $args The arguments to be used in the query string.
     * @param string $mode The mode of the query string. It can be either "columns" or "values".
     * @param array $vals The values to be used in the query string.
     * 
     * @return string Returns the prepared query string.
     */
    private function prepareQueryStringFromArgs(Query $query, array $args, string $mode, array $vals): string {
        if (count($args) < 1) throw new Exception("The number of arguments must be greater than 0.");
        // if (count($args) != count($vals)) throw new Exception("The number of arguments must be equal to the number of values.");
        if ($mode != "columns" && $mode != "values") throw new Exception("The mode must be either 'columns' or 'values'.");
        try {
            $res = "";
            for ($i = 0; $i < count($args); $i++) {
                $uuid = "_" . $this->table . "_" . $this->ValueIncrementer;
                $valuePlaceholder = ":" . $args[$i] . $uuid;
                if ($mode == "columns") {
                    $res .= $args[$i] . ", ";
                } else if ($mode == "values") {
                    $res_str = $valuePlaceholder . ", ";
                    $res .= $res_str;
                    array_push($query->bindParams, array(substr($res_str, 0, -2), $vals[$i]));
                    $this->ValueIncrementer++;
                }
            }
            return substr($res, 0, -2);
        } catch (Exception $error) {
            throw new Exception("Query string preparation failed: " . $error->getMessage());
        }
    }

    /************************************** QUERY METHODS ****************************************/
    
    /**
     * This method creates a table.
     * @param array $columns The columns to be created.
     * @param array $types The types of the columns to be created.
     * 
     * @return ModelFactory Returns the ModelFactory object.
     * @throws Exception Throws an exception if the number of columns and types are not equal.
     */
    public function createTable(array $columns, array $types): ModelFactory {
        if (count($columns) != count($types)) throw new Exception("The number of columns and types must be equal.");
        $this->checkConnection();
        $query = "CREATE TABLE IF NOT EXISTS `{$this->table}` (";
        for ($i = 0; $i < count($columns); $i++) {
            $query .= "`{$columns[$i]}` {$types[$i]},";
        }
        $query .= "PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($query);
        return $this;
    }


    /**
     * This method drops a table.
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function dropTable(): ModelFactory {
        //This method is special because it is the only one that executes the query immediately.
        $this->checkConnection();
        $this->db->exec("DROP TABLE IF EXISTS `{$this->table}`;");
        return $this;
    }

    /**
     * This method inserts data into a table.
     * @param string $column The column to be used in the INSERT INTO clause.
     * @param string $data The data to be used in the INSERT INTO clause.
     * 
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function insert(array $columns, array $values): ModelFactory {
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

    /**
     * This method returns specific data from a table.
     * @param string $column The column to be used in the SELECT clause.
     * @param string $data The data to be used in the SELECT clause.
     */
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

    /**
     * This method returns all data from a table.
     * @return array Returns an array of associative arrays.
     * @throws Exception Throws an exception if the query fails.
     */
    public function selectAll(): array {
        $this->checkConnection();
        $this->execute();
        $query = "SELECT * FROM `{$this->table}`;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    /**
     * This method updates data in a table.
     * @param string $column The column to be used in the UPDATE clause.
     * @param string $data The data to be used in the UPDATE clause.
     * 
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function update(string $controlColumn, string $controlData, array $columns, array $values): ModelFactory {
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

    /**
     * This method deletes data from a table.
     * @param string $column The column to be used in the DELETE FROM clause.
     * @param string $data The data to be used in the DELETE FROM clause.
     * 
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function delete(string $column, string $data): ModelFactory {
        $this->checkConnection();
        $query = new Query;
        $query->string = "DELETE FROM `{$this->table}` 
            WHERE `{$column}` = :{$column}_{$this->table}_{$this->ValueIncrementer};";
        array_push($query->bindParams, array(":{$column}_{$this->table}_{$this->ValueIncrementer}", $data));

        array_push($this->queries, $query);
        $this->ValueIncrementer++;
        return $this;
    }

    /**
     * This method executes all queries in the queries array.
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function printQueries(): ModelFactory {
        foreach ($this->queries as $query) {
            echo nl2br($query->string . PHP_EOL);
            foreach ($query->bindParams as $bps) {
                echo nl2br($bps[0] . " || " . $bps[1] . PHP_EOL);
            }
            echo nl2br("\n\n");
        }
        return $this;
    }

    /**
     * This method executes all queries in the queries array.
     * @return ModelFactory Returns the ModelFactory object.
     */
    public function execute(): ModelFactory {

        $connected = $this->checkConnection();
        $transactionStarted = $this->db->beginTransaction();
        try {
            if (!$connected) throw new Exception("Database connection failed.");
            if (!$transactionStarted) throw new Exception("Transaction failed to start.");

            foreach ($this->queries as $query) {
                $stmt = $this->db->prepare($query->string);
                // echo nl2br("\n" . $query->string . PHP_EOL);
                foreach ($query->bindParams as $bps) {
                    // echo nl2br($bps[0] . " -->binds--> " . $bps[1] . PHP_EOL);
                    $stmt->bindParam($bps[0], $bps[1]);
                }
                $this->printQueries();
                $stmt->execute();
            }
            $this->db->commit();
        } catch (Exception $error) {
            $this->db->rollBack();
            throw new Exception("Execution failed: " . $error->getMessage());
        }
        $this->queries = array();
        $this->ValueIncrementer = 0;
        return $this;
    }

    function __destruct() {
        $this->db = null;
    }
}
