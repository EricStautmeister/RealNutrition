<?php

class AuthController {
    private $authModel;
    private $name;
    private $pwd;

    public function __construct() {
        $this->authModel = new AuthModelWrapper();
        $this->name = "name";
        $this->pwd = "pwd";
    }
    public function handleRequest() {
        if (empty($_POST)) {
            $this->displayAuthPage();
        } else {
            $this->handlePost();
        }
    }

    private function displayAuthPage($args = []) {
        extract($args);
        $type = $_SERVER["PATH_INFO"];
        include "form.testing.php";
    }

    private function handlePost() {
        $isclear = $this->checkRequirements();
        if ($isclear != "clear") {
            $this->displayAuthPage(["res" => $isclear]);
            return;
        }

        if ($_SERVER["PATH_INFO"] == "/login") {
            try {
                $this->authModel->validateUser($_POST[$this->name], $_POST[$this->pwd]);
                AuthController::loginDashboard($_POST[$this->name]);
            } catch (Exception $error) {
                $this->displayAuthPage(["err" => $error, "email" => $_POST[$this->name]]);
                return;
            }
        } else if ($_SERVER["PATH_INFO"] == "/signup") {
            if (!$this->checkUserExistence($_POST[$this->name])) {
                try {
                    $token = substr(md5(random_bytes(64)), 10, 22);
                    $this->authModel->newUserToVerify($_POST[$this->name], password_hash($_POST[$this->pwd], PASSWORD_DEFAULT), $token);
                } catch (Exception $error) {
                    $this->displayAuthPage(["err" => $error, "email" => $_POST[$this->name], "pwd" => $_POST[$this->pwd]]);
                    return;
                }
                EmailController::sendMail($_POST[$this->name], $token);
                EmailController::displayEmailPage($_POST[$this->name]);
            }
        }
    }
    private function checkRequirements() {
        session_start();
        if ($_POST["captcha"] != $_SESSION["captcha"]) {
            return "Captcha incorrect";
        } else if (empty($_POST[$this->name])) {
            return "Name required";
        } else if (empty($_POST[$this->pwd])) {
            return "Password required";
        } else {
            return "clear";
        }
    }

    private function checkUserExistence($email) {
        try {
            $userexists = $this->authModel->checkUserExistence($email);
            if ($userexists) {
                $this->displayAuthPage(["err" => "This user already has an account"]);
                return True;
            } else {
                return False;
            }
        } catch (Exception $error) {
            $this->displayAuthPage(["err" => $error]);
            return True;
        }
    }

    public static function loginDashboard($email) {
        $e = new DashboardController();
        $e->loginUser($email);
        header("Location: /dashboard");
    }
}
