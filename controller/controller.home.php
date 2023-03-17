<?php

class HomeController
{
    private $model;

    public function __construct()
    {
        // $this->model = new FoodModel();
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
        var_dump($args);
        $foods = array("apple", "banana", "orange", "pear", "cookies", "apple", "banana", "orange", "juice", "cookies", "meats", "sausage", "rice", "pasta", "tomato", "onion", "cucumber", "garlic", "carrot", "carrotpie", "canned tuna", "oil", "peanut", "peanut oil", "peanutbutter");
        // $foods = $this->model->getFoodNames();
        include "home.testing.php";
    }

    private function handlePost()
    {
        $this->displayPage($_POST);
    }
}
