<?php

class HomeController {
    private $model;

    public function __construct() {
        $this->model = new FoodModelWrapper();
    }
    public function handleRequest() {
        if (empty($_POST)) {
            $this->displayPage();
        } else {
            $this->handlePost();
        }
    }

    private function displayPage($args = []) {
        extract($args);
        var_dump($args);
        $foods = $this->model->getFoodNames();
        include "./view/home.php";
    }

    private function handlePost() {
        $this->displayPage($_POST);
    }
}
