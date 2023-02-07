<?php
/* DB is a class that connects to a database and 
has a few methods that allow you to query the database. */

/**
 * This class is used to connect to a database and query it.
 * @package Model
 * @version 1.0.0
 * @access public
 * @license MIT
 */
class Model

{
    private $db;
    private $table;
    
    /**
     * This method is used to instantiate the Model class.
     * @param string $table The name of the table to be used.
     * @return void
     * @throws PDOException on failure to connect to the database.
     */
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
            throw $error->getMessage();
        }
    }

    /**
     * This method is used to prepare the query string for the addDataToTable method.
     * @param int $argslen The length of the array of column names.
     * @param array $strarry The array of column names.
     * @param string $mode The mode of the query string. Either "columns" or "values".
     * @return string The prepared query substring.
     */
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

    /**
     * This method is used to add users to the users table.
     * @param array $args An array of ["dbtables" => $dbtables, "dbvars" => $dbvars].
     * @param array $dbtables An array of the column names.
     * @param array $dbvars An array of the values to be inserted into the columns.
     * @return PDOStatement The PDOStatement object.
     * @return PDOException The PDOException object if the query fails.
     */
    public function addDataToTable($args = [])
    //arg syntax: ["result" => $result, "username" => $username, "password" => $password]
    //TODO: Add a parameter for the column names to make class more generic
    {
        extract($args);
        $columns = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "columns");
        $values = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "values");
        $sql_query = "INSERT INTO $this->table ($columns) VALUES ($values)";
        try {
            $stm = $this->db->prepare($sql_query);
            // The below is potentially securer
            // $stm->bindParam(':table', $this->table);
            for ($i = 0; $i < count($dbvars); $i++) {
                $stm->bindParam(":$dbtables[$i]", $dbvars[$i]);
            }
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            return $error->getMessage() . PHP_EOL;
        }
    }   

    /**
     * This method is used to fetch all data from the table.
     * @return PDOStatement Returns a PDOStatement object.
     * @return PDOException If the query fails to execute.
     */
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
    
    /**
     * This method is used to check if certain data in a column exists in a table.
     * @param $column The column to check for data in.
     * @param $hasData The data to check for in the column.
     * @return bool Returns true if the data exists in the column, false otherwise.
     * @throws PDOException If the query fails to execute.
     */
    public function checkDataExistence($column, $hasData)
    {
        try {
            $stm = $this->db->prepare("SELECT EXISTS (SELECT * FROM $this->table 
                                                    WHERE :column = :hasData)");
            $stm->bindParam(':column', $column);
            $stm->bindParam(':hasData', $hasData);
            $res = $stm->execute();
            return ($res == 1 ? true : false);
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }
}
