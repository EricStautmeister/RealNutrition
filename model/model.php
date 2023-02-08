<?php
/* DB is a class that connects to a database and 
has a few methods that allow you to query the database. */

/**
 * This class is used to connect to a database and query it.
 * @package Model
 * @version 1.0.0
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
            throw new Exception($error->getMessage());
        }
    }

    /**
     * This method is used to prepare the query string for the addDataToTable method.
     * @param int $argslen The length of the array of column names.
     * @param array $strarry The array of column names.
     * @param string $mode The mode of the query string. Either "columns" or "values".
     * @return string The prepared query substring.
     * @throws Exception If the number of arguments is less than 1 or the mode is not "columns" or "values".
     * @throws Exception If the mode is "columns" and the number of arguments is less than 1.
     */
    private function prepareQueryStringFromArgs($argslen, $strarry, $mode)
    {

        if ($argslen < 1) {
            throw new Exception("The number of arguments must be greater than 0.");
        }
        if ($mode != "columns" && $mode != "values") {
            throw new Exception("The mode must be either 'columns' or 'values'.");
        }
        try {
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
        } catch (Exception $error) {
            $msg = $error->getMessage();
            throw new Exception("Query string preparation failed: $msg");
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
            $msg = $error->getMessage();
            throw new Exception("Checking if data exists in database failed: $msg");
        }
    }

    /**
     * This method is used to add users to the users table.
     * @param array $args An array of ["dbtables" => $dbtables, "dbvars" => $dbvars].
     * @param array $dbtables An array of the column names.
     * @param array $dbvars An array of the values to be inserted into the columns.
     * @return PDOStatement The PDOStatement object.
     * @throws PDOException The PDOException object if the query fails.
     */
    public function addDataToTable($args = [])
    {
        try {
            extract($args);
            $columns = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "columns");
            $values = $this->prepareQueryStringFromArgs(count($dbtables), $dbtables, "values");
            $sql_query = "INSERT INTO $this->table ($columns) VALUES ($values)";
            $stm = $this->db->prepare($sql_query);
            for ($i = 0; $i < count($dbvars); $i++) {
                $stm->bindParam(":$dbtables[$i]", $dbvars[$i]);
            }
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Adding data to database failed: $msg");
        }
    }

    /**
     * This method is used to fetch all data from the table.
     * @return PDOStatement Returns a PDOStatement object.
     * @return PDOException If the query fails to execute.
     */

    public function getAllDataFromTable()
    {
        try {
            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();
            $data = $stm->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Fetching all data from database failed: $msg");
        }
    }

    /**
     * This method is used to fetch data from the table.
     * @param $column The column to check for data in.
     * @param $data The data to check for in the column.
     * @return PDOStatement Returns a PDOStatement object.
     * @throws PDOException If the query fails to execute.
     */
    public function getDataFromTable($column, $data)
    {
        try {
            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE $column = :hasData");
            // $stm->bindParam(':column', $column);
            $stm->bindParam(':hasData', $data);
            $stm->execute();
            $data = $stm->fetchAll(PDO::FETCH_ASSOC);
            return $stm;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Fetching data from database failed: $msg");
        }
    }

    /**
     * This method is used to delete data from the table.
     * @param $column The column to check for data in.
     * @param $data The data to check for in the column.
     * @return PDOStatement Returns a PDOStatement object.
     * @throws PDOException If the query fails to execute.
     */
    public function deleteDataFromTable($column, $data)
    {
        try {
            $stm = $this->db->prepare("DELETE FROM $this->table WHERE :column = :hasData");
            $stm->bindParam(':column', $column);
            $stm->bindParam(':hasData', $data);
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Deleting data from database failed: $msg");
        }
    }

    /**
     * This method is used to update data in the table.
     * @param $column The column to check for data in.
     * @param $data The data to check for in the column.
     * @param $newData The new data to update the column with.
     * @return PDOStatement Returns a PDOStatement object.
     * @throws PDOException If the query fails to execute.
     */
    public function updateDataFromTable($column, $data, $newData)
    {
        try {
            $stm = $this->db->prepare("UPDATE $this->table SET :column = :newData WHERE :column = :hasData");
            $stm->bindParam(':column', $column);
            $stm->bindParam(':hasData', $data);
            $stm->bindParam(':newData', $newData);
            $stm->execute();
            return $stm;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Updating data in database failed: $msg");
        }
    }
}
