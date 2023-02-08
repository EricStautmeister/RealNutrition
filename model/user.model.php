<?php
require_once "model.php";

class UserModel extends Model 
{
    public function __construct()
    {
        parent::__construct("users");
    }

    public function checkUserExistence($email)
    {
        $exists = $this->checkDataExistence("email", $email);
        return $exists;
    }

    public function addUser($email, $password)
    {
        $dbtables = ["email", "password"];
        $dbvars = [$email, $password];
        $addedUser = $this->addDataToTable(["dbtables" => $dbtables, "dbvars" => $dbvars]);
        return $addedUser;
    }

    public function getUser($email)
    {
        echo nl2br("getUser() called with email: $email\n\n");
        $user = $this->getDataFromTable("email", $email);
        return $user;
    }

    public function deleteUser($email)
    {
        $deletedUser = $this->deleteDataFromTable("email", $email);
        return $deletedUser;
    }

    public function updateUser($email, $password)
    {
        $updatedUser = $this->updateDataFromTable("email", $email, "password", $password);
        return $updatedUser;
    }

    public function validateUser($email, $password)
    {
        $emailexists = $this->checkUserExistence($email);
        if ($emailexists) {
            $user = $this->getUser($email);
            // password_verify() is a PHP function that verifies a password against a hash.
            if ($password == $user["password"]) {
                return true;
            }
        }
        return false;
    }
}
