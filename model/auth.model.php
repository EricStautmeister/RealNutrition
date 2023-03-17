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
    public function checkUserExistence(string $email): bool {
        return $this->checkDataExistence("email", $email);
    }
    public function addUser(string $email, string $password, string $token): TempAuthTable {
        $this->insert(["email", "password", "token"], [$email, $password, $token])->execute();
        return $this;
    }

    public function getUser(string $email) {
        $user = $this->select("email", $email)[0];
        unset($user["id"]);
        return $user;
    }

    public function deleteUser(string $email): TempAuthTable {
        $this->delete("email", $email);
        return $this;
    }

    public function updateUser(
        string $oldemail,
        string $oldpassword,
        string $newemail,
        string $newpassword
    ): TempAuthTable {
        $this->update("email", $oldemail, ["email", "password"], [$newemail, $newpassword]);
        return $this;
    }
    public function validateUser(string $email, string $token): TempAuthTable {
        $this->checkUserExistence($email);
        $user = $this->getUser($email);
        if ($user["token"] === $token) {
            return $this;
        } else {
            throw new Exception("Invalid password or token.");
        }
    }
}

// TODO: Rewrite this class to use the new model class.

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

    public function checkUserExistence(string $email): bool {
        try {

            return $this->checkDataExistence("email", $email);
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Checking existence of user failed: " . $msg);
        }
    }

    public function newUserToVerify(string $email, string $password, string $token): AuthModelWrapper {
        $this->TempAuthTable->addUser($email, $password, $token);
        return $this;
    }

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

    public function addUser(string $email, string $password, string $uid): AuthModelWrapper {
        try {
            $this->insert(["email", "password", "uid"], [$email, $password, $uid])->execute();
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Adding user failed: " . $msg);
        }
    }

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

    public function validateUser(string $email, string $password): AuthModelWrapper {
        try {
            if (!$this->checkUserExistence($email)) throw new Exception("User does not exist or password was passed incorrectly.");
            if ($password != $this->getUser($email)[0]["password"]) throw new Exception("User does not exist or password was passed incorrectly.");
            return $this;
        } catch (PDOException $error) {
            $msg = $error->getMessage();
            throw new Exception("Validating user credentials failed.");
        }
    }
}
