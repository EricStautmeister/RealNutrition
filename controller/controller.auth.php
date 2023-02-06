<?php

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new Model();
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
        include "auth.php";
    }

    private function handlePost()
    {
        var_dump($_POST);

        $isclear = $this->checkRequirements();
        if ($isclear != "clear") {
            $this->displayPage(["res" => $isclear]);
            return;
        }

        $isuser = $this->model->isUser($_POST["name"]);
        if (!$isuser) {
            $this->displayPage(["res" => "User already Exists"]);
            return;
        }

        try {
            if (!$this->model->setUser($_POST["name"], $_POST["pwd"])) {
                throw new Exception("could not set user");
            } else {
                header("Location: /");
            }
        } catch (Exception $e) {
            $this->displayPage(["res" => $e->getMessage()]);
        }
    }

    private function checkRequirements()
    {
        $name = "name";
        $pwd = "pwd";
        if (empty($_POST[$name])) {
            return "Name required";
        } else if (empty($_POST[$pwd])) {
            return "Password requierd";
        } else {
            return "clear";
        }
    }
}

class Model
{
    public function isUser($user)
    {
        return false;
    }

    public function setUser($name, $pwd)
    {
        return false;
    }
}
