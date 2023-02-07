<?php

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
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

    private function login()
    {
        $res = $this->model->checkUser($_POST["name"], $_POST["pwd"]);
        if ($res == "clear") {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "/account");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("name" => $_POST["name"], "loggedin" => "true")));
            curl_exec($curl);
        } else {
            return $res;
        }
    }

    private function signup()
    {
        if ($this->model->isUser($_POST["name"])) {
            return "User already exists";
        } else {
            $setuser = $this->model->setUser($_POST["name"], $_POST["pwd"]);
            if ($setuser != "set") {
                return $setuser;
            } else {
                header("Location: /");
            }
        }
    }
}

class AuthModel
{
    public function isUser($user)
    {
        return true || false;
    }

    public function setUser($name, $pwd)
    {
        return "set" || new Exception("error message");
    }

    public function checkUser($user, $pwd)
    {
        return "clear" || new Exception("error message");
    }
}
