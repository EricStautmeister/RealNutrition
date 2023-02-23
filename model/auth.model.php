<?php
require_once "model.php";

/**
 * This class is the model for the user table.
 * It extends the model class and uses the methods from the model class.
 * It also has its own methods that are specific to the user table.
 * The following methods are available:
 * - validateUser() - validates a users inputed data against the database.
 *                    It makes use of the following methods:
 *                    - checkUserExistence()
 *                    - getUser()
 * - checkUserExistence() - checks if a user exists in the database.
 * - addUser() - adds a user to the database.
 * - getUser() - gets a user from the database.
 * - deleteUser() - deletes a user from the database.
 * - updateUser() [incomplete] - updates a users data in the database.
 * @package model
 * @subpackage auth.model
 * @version 1.0.0
 */
class AuthModelWrapper extends Model
{
    /**
     * This method is used to instantiate the UserModel class.
     * It passed  the table name to the parent class, that it inherits from. 
     * @return void
     */
    public function __construct()
    {
        parent::__construct("auth");
    }

    /**
     * This method is used to check if a user exists in the database.
     * @param string $email The email of the user.
     * @return bool True if the user exists, false if the user does not exist.
     * @return PDOException on failure to connect to the database.
     */
    public function checkUserExistence(string $email): bool|string
    {
        try {
            $exists = $this->checkDataExistence("email", $email);

            return $exists;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    /**
     * This method is used to add a user to the database.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return bool True if the user was added, false if the user was not added.
     */
    public function addUser(string $email, string $password) : bool
    {
        try {
            $dbtables = ["email", "password"];
            $dbvars = [$email, $password];
            $this->addDataToTable(["dbtables" => $dbtables, "dbvars" => $dbvars]);
            return true;
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * This method is used to get a user from the database.
     * @param string $email The email of the user.
     * @return array The user data.
     */
    public function getUser(string $email)
    {
        try {
            $user = $this->getDataFromTable("email", $email);
            unset($user["id"]);
            return $user;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Getting user failed: " . $msg);
        }
    }

    /**
     * This method is used to delete a user from the database.
     * @param string $email The email of the user.
     * @return bool True if the user was deleted, false if the user was not deleted.
     */
    public function deleteUser(string $email)
    {
        try {
            $this->deleteDataFromTable("email", $email);
            return true;
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * This method is used to update a users data in the database. 
     * @param string $email The email of the user 
     * @param string $password The hashed password of the user. 
     * @return bool True if the user was updated, false if the user was not updated.
     */
    public function updateUser(string $email, string $password): bool
    {
        try {
            $updatedUser = $this->updateDataFromTable("email", $email, "password", $password);
            if (!$updatedUser) {
                throw new PDOException("There was an error updating the user.");
            }
            return $updatedUser;
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * This method is used to validate a users inputed data against the database. 
     * @param string $email The users email
     * @param string $password The hashed password of the user
     * @return bool True if the users data matches the data in the database, else false. 
     */
    public function validateUser(string $email, string $password): bool|string
    {
        try {
            $emailexists = $this->checkUserExistence($email);
            echo "emailexists:" . var_dump($emailexists) . PHP_EOL;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
        if (!$emailexists) {
            return "Email does not exist.";
        }
        $user = $this->getUser($email);
        if ($password != $user[0]["password"]) {
            return "Password is incorrect.";
        }
        return true;
    }
}
