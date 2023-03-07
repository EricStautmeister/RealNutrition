<?php

require_once "./controller/controller.dashboard.php";
require_once "./model/auth.model.php";

class AuthController
{
    private $model;
    private $name;
    private $pwd;

    public function __construct()
    {
        $this->model = new AuthModelWrapper();
        $this->name = "name";
        $this->pwd = "pwd";
    }
    public function handleRequest()
    {
        if (empty($_POST)) {
            $this->displayPage();
        } else {
            $this->handlePost();
        }
    }

    private function displayPage($args = [])
    {
        extract($args);
        $type = $_SERVER["REQUEST_URI"];
        include "form.testing.php";
    }

    private function handlePost()
    {
        $isclear = $this->checkRequirements();
        if ($isclear != "clear") {
            $this->displayPage(["res" => $isclear]);
            return;
        }

        if ($_SERVER["REQUEST_URI"] == "/login") {
            $isloggedin = $this->login();
            if (!empty($isloggedin)) {
                $this->displayPage(["err" => $isloggedin]);
                return;
            }
        } else if ($_SERVER["REQUEST_URI"] == "/signup") {
            $issignedup = $this->signup();
            if (!empty($issignedup)) {
                $this->displayPage(["err" => $issignedup]);
                return;
            }
        }
    }
    private function checkRequirements()
    {
        if (empty($_POST[$this->name])) {
            return "Name required";
        } else if (empty($_POST[$this->pwd])) {
            return "Password required";
        } else {
            return "clear";
        }
    }

    private function login()
    {
        $loginres = $this->model->validateUser($_POST[$this->name], $_POST[$this->pwd]);
        if (is_bool($loginres)) {
            $e = new DashboardController();
            $e->loginUser($_POST[$this->name]);
            header("Location: /dashboard");
        } else {
            return $loginres;
        }
    }

    private function signup()
    {
        $existinguser = $this->model->checkUserExistence($_POST[$this->name]);
        if ($existinguser) {
            return "User already exists";
        } else {
            $setuser = $this->model->addUser($_POST["name"], $_POST["pwd"]);
            if (!$setuser) {
                return "Could not create User";
            } else {
                $this->login();
            }
        }
    }
}
