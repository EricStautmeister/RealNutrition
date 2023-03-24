<?php

class DashboardController {

    private $foodModel;

    public function __construct() {
        $this->foodModel = new FoodModelWrapper();
    }
    public function handleRequest() {
        if (empty($_POST)) {
            $this->displayPage();
        } else {
            $this->handlePost();
        }
    }

    public function displayPage($args = []) {
        extract($args);
        include "./view/dash.php";
    }

    public function loginUser($newuser, $uid) {
        session_start();
        $_SESSION["user"] = explode("@", htmlspecialchars($newuser))[0];
        $_SESSION["uid"] = $uid;
    }

    private function handlePost() {
        $this->foodModel->addFood($_POST["food"], floatval($_POST["calories"]));
        $food = $this->foodModel->getFoodNames();
        $this->displayPage(["data" => $food]);
    }
}
