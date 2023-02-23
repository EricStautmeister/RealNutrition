<?php

require_once "./controller/controller.dashboard.php";
require_once "./model/user.model.php";

class AuthController
{
    private $model;
    private $name;
    private $pwd;

    public function __construct()
    {
        $this->model = new UserModel();
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
        // sets php variables and displays auth page
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
                $this->displayPage(["res" => $isloggedin]);
                return;
            }
        } else if ($_SERVER["REQUEST_URI"] == "/signup") {
            $issignedup = $this->signup();
            if (!empty($issignedup)) {
                $this->displayPage(["res" => $issignedup]);
                return;
            }
        }
    }

    private function checkRequirements()
    {
        if (empty($_POST[$this->name])) {
            return "Name required";
        } else if (empty($_POST[$this->pwd])) {
            return "Password requierd";
        } else {
            return "clear";
        }
    }

    private function login()
    {
        // $this->model->validateUser($_POST[$this->name], $_POST[$this->pwd])
        if ($this->name == "name") {
            $e = new DashboardController();
            $e->loginUser($_POST[$this->name]);
            header("Location: /dashboard");
        } else {
            return;
        }
    }

    private function signup()
    {
        // $this->model->isUser($_POST[$this->name])
        if ($this->pwd == "heya") {
            return "User already exists";
        } else {
            // $setuser = $this->model->setUser($_POST["name"], $_POST["pwd"]);
            $setuser = "set";
            if ($setuser != "set") {
                return $setuser;
            } else {
                $this->login();
            }
        }
    }
}
