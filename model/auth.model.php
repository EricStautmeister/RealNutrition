<?php
require_once "model.php";

interface TempAuthInterface {
    public function checkUserExistence(string $email): bool;
    public function addUser(string $email, string $password, string $token): ModelFactory;
    public function getUser(string $email);
    public function deleteUser(string $email): ModelFactory;
    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): ModelFactory;
    public function validateUser(string $email, string $token): ModelFactory;
}

interface AuthInterface {
    public function checkUserExistence(string $email): bool;
    public function newUserToVerify(string $email, string $password, string $token): ModelFactory;
    public function verifyNewUser(string $email, string $token): AuthModelWrapper;
    public function addUser(string $email, string $password, string $uid): ModelFactory;
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

/**
 * The TempAuthTable class is a model class that handles all the database operations for the signup process.
 * It is a wrapper class for the ModelFactory class.
 * It implements the TempAuthInterface interface.
 * 
 * The methods in this class are:
 * 1. __construct(): The constructor for the TempAuthTable class.
 * 2. checkUserExistence(string $email): Checks if the user exists in the database.
 * 3. addUser(string $email, string $password, string $token): Adds a new user to the database.
 * 4. getUser(string $email): Gets the user from the database.
 * 5. deleteUser(string $email): Deletes the user from the database.
 * 6. updateUser(string $oldemail, string $oldpassword, string $newemail, string $newpassword): Updates the user in the database.
 * 7. validateUser(string $email, string $token): Validates the user in the database.
 */
class TempAuthTable extends ModelFactory implements TempAuthInterface {
    public function __construct() {
        parent::__construct("temp_auth");
        $this->createTable(
            [
                "id",
                "email",
                "password",
                "token",
                "created_at",
                "updated_at"
            ],
            [
                "INT(11) NOT NULL AUTO_INCREMENT",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",
                "VARCHAR(255) NOT NULL",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
            ]
        );
    }

    /**
     * Checks if the user exists in the database.
     * 
     * @param string $email The email of the user.
     * @return bool Returns true if the user exists in the database, false otherwise.
     */
    public function checkUserExistence(string $email): bool {
        return $this->checkDataExistence("email", $email);
    }

    /**
     * Adds a new user to the database.
     * 
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param string $token The token of the user.
     * @return TempAuthTable Returns the TempAuthTable object.
     */
    public function addUser(string $email, string $password, string $token): TempAuthTable {
        $this->insert(["email", "password", "token"], [$email, $password, $token])->execute();
        return $this;
    }

    /**
     * Gets the user from the database.
     * 
     * @param string $email The email of the user.
     * @return array Returns the user from the database.
     */
    public function getUser(string $email) {
        $user = $this->select("email", $email)[0];
        unset($user["id"]);
        return $user;
    }

    /**
     * Deletes the user from the database.
     * 
     * @param string $email The email of the user.
     * @return TempAuthTable Returns the TempAuthTable object.
     */
    public function deleteUser(string $email): TempAuthTable {
        $this->delete("email", $email);
        return $this;
    }

    /**
     * Updates the user in the database.
     * 
     * @param string $oldemail The old email of the user.
     * @param string $oldpassword The old password of the user.
     * @param string $newemail The new email of the user.
     * @param string $newpassword The new password of the user.
     * @return TempAuthTable Returns the TempAuthTable object.
     */
    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): TempAuthTable {
        $this->update("email", $oldemail, ["email", "password"], [$newemail, $newpassword]);
        return $this;
    }

    /**
     * Validates the user in the database.
     * 
     * @param string $email The email of the user.
     * @param string $token The token of the user.
     * @return TempAuthTable Returns the TempAuthTable object.
     */
    public function validateUser(string $email, string $token): TempAuthTable {
        $this->checkUserExistence($email);
        $user = $this->getUser($email);
        if ($user["token"] === $token) {
            return $this;
        } else {
            throw new Exception("Invalid email or token.");
        }
    }
}


/**
 * The AuthModelWrapper class is a model class that handles all the database operations for the login process.
 * It is a wrapper class for the ModelFactory class.
 * It implements the AuthInterface interface.
 * 
 * The methods in this class are:
 * 1. __construct(): The constructor for the AuthModelWrapper class.
 * 2. checkUserExistence(string $email): Checks if the user exists in the database.
 * 3. newUserToVerify(string $email, string $password, string $token): Adds a new user to the database.
 * 4. verifyNewUser(string $email, string $token): Validates the user in the database.
 * 5. addUser(string $email, string $password, string $uid): Adds a new user to the database.
 * 6. getUser(string $email): Gets the user from the database.
 * 7. deleteUser(string $email): Deletes the user from the database.
 * 8. updateUser(string $oldemail, string $oldpassword, string $newemail, string $newpassword): Updates the user in the database.
 * 9. validateUser(string $email, string $password): Validates the user in the database.
 */
class AuthModelWrapper extends ModelFactory implements AuthInterface {
    private $TempAuthTable;
    public function __construct() {
        try {
            $this->TempAuthTable = new TempAuthTable();
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
                    "INT(11) NOT NULL AUTO_INCREMENT",
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
     * Checks if the user exists in the database.
     * 
     * @param string $email The email of the user.
     * @return bool Returns true if the user exists in the database, false otherwise.
     */
    public function checkUserExistence(string $email): bool {
        try {

            return $this->checkDataExistence("email", $email);
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Checking existence of user failed: " . $msg);
        }
    }

    /**
     * Adds a new user to the database, who has yet to be verified though the provided token.
     * 
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param string $token The token of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function newUserToVerify(string $email, string $password, string $token): AuthModelWrapper {
        $userAlreadyExists = $this->checkUserExistence($email);
        if ($userAlreadyExists) {
            throw new Exception("User already exists.");
        }
        $this->TempAuthTable->addUser($email, $password, $token);
        return $this;
    }

    /**
     * Validates the user in the database temporary table, to make the user a verified user in the real auth table.
     * 
     * @param string $email The email of the user.
     * @param string $token The token of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function verifyNewUser(string $email, string $token): AuthModelWrapper {
        try {
            $user = $this->TempAuthTable->getUser($email);
            $this->TempAuthTable->validateUser($email, $token)
                ->deleteUser($email)->execute();
            $this->addUser($email, $user["password"], uniqid())->execute();
        } catch (Exception $error) {
            throw new Exception("Verifying User failed: " . $error->getMessage());
        }
        return $this;
    }

    /**
     * Adds a new user to the database.
     * 
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param string $uid The uid of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function addUser(string $email, string $password, string $uid): AuthModelWrapper {
        try {
            $this->insert(["email", "password", "uid"], [$email, $password, $uid])->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Adding user failed: " . $msg);
        }
    }

    /**
     * Gets the user from the database.
     * 
     * @param string $email The email of the user.
     * @return array Returns the user from the database.
     */
    public function getUser(string $email) {
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
     * Deletes the user from the database.
     * 
     * @param string $email The email of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function deleteUser(string $email): AuthModelWrapper {
        try {
            $this->delete("email", $email)
                ->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Deleting user failed: " . $msg);
        }
    }

    /**
     * Updates the user in the database.
     * 
     * @param string $oldemail The old email of the user.
     * @param string $oldpassword The old password of the user.
     * @param string $newemail The new email of the user.
     * @param string $newpassword The new password of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): AuthModelWrapper {
        try {
            $this->update("email", $oldemail, ["email", "password"], [$newemail, $newpassword])->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Updating user failed: " . $msg);
        }
    }

    /**
     * Validates the user in the database, 
     * by checking if the email and password match the ones in the database.
     * 
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return AuthModelWrapper Returns the AuthModelWrapper object.
     */
    public function validateUser(string $email, string $password): AuthModelWrapper {
        try {
            if (!$this->checkUserExistence($email)) throw new Exception("User or password incorrect");
            if (!password_verify($password, $this->getUser($email)[0]["password"])) throw new Exception("User or password incorrect");
            return $this;
        } catch (PDOException $error) {
            throw new Exception("Validating user credentials failed");
        }
    }
}
