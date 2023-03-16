<?php
require_once "model.php";

interface AuthInterface
{
    public function checkUserExistence(string $email): ModelFactory;
    public function addUser(string $email, string $password): ModelFactory;
    public function getUser(string $email);
    public function deleteUser(string $email): ModelFactory;
    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): ModelFactory;
    public function validateUser(string $email, string $password): ModelFactory;
}

class TempAuthTable extends ModelFactory implements AuthInterface
{
    public function __construct()
    {
        parent::__construct("temp_auth");
        $this->createTable(
            [
                "id",
                "email",
                "password",
                "uid",
                "created_at",
                "updated_at"
            ],

            [
                "INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
            ]
        );
    }
    public function checkUserExistence(string $email): ModelFactory
    {
        return $this->checkDataExistence("email", $email);
    }
    public function addUser(string $email, string $password): ModelFactory
    {
        $this->insert(["email", "password"], [$email, $password]);
        return $this;
    }

    public function getUser(string $email)
    {
        $user = $this->select("email", $email);
        unset($user["id"]);
        return $user;
    }

    public function deleteUser(string $email): ModelFactory
    {
        $this->delete("email", $email);
        return $this;
    }

    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): ModelFactory {
        $this->update("email", $oldemail, ["email", "password"], [$newemail, $newpassword]);
        return $this;
    }
    public function validateUser(string $email, string $password): ModelFactory
    {
        $this->checkUserExistence($email);
        $user = $this->getUser($email);
        if ($user["password"] === $password) {
            return $this;
        } else {
            throw new Exception("Invalid password");
        }
    }
}

// TODO: Rewrite this class to use the new model class.

/**
 * This class is the model for the user table.
 * It extends the model class and uses the methods from the model class.
 * It also has its own methods that are specific to the user table.
 * The following methods are available:
 * - va$lidateUser() - validates a users inputed data against the database.
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
class AuthModelWrapper extends ModelFactory implements AuthInterface
{
    /**
     * This method is used to instantiate the UserModel class.
     * It passed  the table name to the parent class, that it inherits from. 
     * @return void
     */
    public function __construct()
    {
        try {

            parent::__construct("auth");
            $this->createTable(
                [
                    "id",
                    "email",
                    "password",
                    "uid",
                    "created_at",
                    "updated_at"
                ],

                [
                    "INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY",
                    "VARCHAR(255) NOT NULL",
                    "VARCHAR(255) NOT NULL",
                    "VARCHAR(255) NOT NULL",
                    "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                    "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
                ]
            )->execute();
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Creating Auth failed: " . $msg);
        }
    }

    /**
     * This method is used to check if a user exists in the database.
     * @param string $email The email of the user.
     * @return bool True if the user exists, false if the user does not exist.
     * @return PDOException on failure to connect to the database.
     */
    public function checkUserExistence(string $email): ModelFactory
    {
        try {

            return $this->checkDataExistence("email", $email);
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Checking existence of user failed: " . $msg);
        }
    }



    /**
     * This method is used to add a user to the database.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return bool True if the user was added, false if the user was not added.
     */
    public function addUser(string $email, string $password): ModelFactory
    {
        try {
            $this->insert(["email", "password"], [$email, $password])
                ->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Adding user failed: " . $msg);
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
            $user = $this->select("email", $email);
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
    public function deleteUser(string $email): ModelFactory
    {
        try {
            $this->delete("email", $email)
                ->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Deleting user failed: " . $msg);
        }
    }
    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): ModelFactory {
        try {
            $this->update("email", $oldemail, ["email", "password"], [$newemail, $newpassword])->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Updating user failed: " . $msg);
        }
    }

    public function validateUser(string $email, string $password): ModelFactory
    {
        try {
            if (!$this->checkUserExistence($email)) throw new Exception("User does not exist or was passed incorrectly.");
            if ($password != $this->getUser($email)[0]["password"]) throw new Exception("Password is incorrect.");
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Validating user credentials failed: " . $msg);
        }
    }
}
