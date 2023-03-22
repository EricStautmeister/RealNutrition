<?php

class AuthController {
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModelWrapper();
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
        include "./view/auth.php";
    }

    private function handlePost() {
        if (!empty($_POST["new"])) {
            $this->displayAuthPage(["email" => $_POST["email"], "password" => $_POST["password"]]);
            return;
        }
        $isclear = $this->checkRequirements();
        switch ($isclear) {
            case "Captcha incorrect":
                $this->displayAuthPage(["err" => $isclear, "email" => $_POST["email"], "password" => $_POST["password"]]);
                return;
            case "Password required":
                $this->displayAuthPage(["err" => $isclear, "email" => $_POST["email"]]);
                return;
            case "Name required":
                $this->displayAuthPage(["err" => $isclear]);
                return;
        }

        if ($_SERVER["PATH_INFO"] == "/login") {
            try {
                $this->authModel->validateUser($_POST["email"], $_POST["password"]);
                $this->loginDashboard($_POST["email"]);
            } catch (Exception $error) {
                $this->displayAuthPage(["err" => $error->getMessage(), "email" => $_POST["email"]]);
                return;
            }
        } else if ($_SERVER["PATH_INFO"] == "/signup") {
            if (!$this->checkUserExistence($_POST["email"])) {
                try {
                    $token = substr(md5(random_bytes(64)), 10, 22);
                    $this->authModel->newUserToVerify($_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT), $token);
                } catch (Exception $error) {
                    $this->displayAuthPage(["err" => $error->getMessage(), "email" => $_POST["email"], "password" => $_POST["password"]]);
                    return;
                }
                EmailController::sendMail($_POST["email"], $token);
                EmailController::displayEmailPage($_POST["email"]);
            }
        }
    }
    private function checkRequirements() {
        session_start();
        if ($_POST["captcha"] != $_SESSION["captcha"]) {
            return "Captcha incorrect";
        } else if (empty($_POST["email"])) {
            return "Name required";
        } else if (empty($_POST["password"])) {
            return "Password required";
        } else {
            return "clear";
        }
    }

    private function checkUserExistence($email) {
        try {
            $userexists = $this->authModel->checkUserExistence($email);
            if ($userexists) {
                $this->displayAuthPage(["err" => "This user already exists"]);
                return True;
            } else {
                return False;
            }
        } catch (Exception $error) {
            $this->displayAuthPage(["err" => $error]);
            return True;
        }
    }

    public function loginDashboard($email) {
        $uid = $this->authModel->getUser($email)[0]["uid"];
        $e = new DashboardController();
        $e->loginUser($email, $uid);
        header("Location: /dashboard");
    }
}
