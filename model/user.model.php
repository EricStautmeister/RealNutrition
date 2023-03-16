<?php
require_once "model.php";

// TODO: Rewrite this class to use the new model class.
// TODO: Add all necessary CRUD methods.

class UserModel extends Model
{
    public function __construct(string $name)
    {
        $SQLSAFENAME = str_replace("@", "_", $name);
        $SQLSAFENAME = str_replace(".", "_", $SQLSAFENAME);
        parent::__construct($SQLSAFENAME);
        $tableColums = ["id", "name", "email", "created_at", "updated_at"];
        $columnTypes = ["INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY", 
                        "VARCHAR(255) NOT NULL", 
                        "VARCHAR(255) NOT NULL", 
                        "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP", 
                        "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"];
        $this->createTable($tableColums, $columnTypes);
    }
}
