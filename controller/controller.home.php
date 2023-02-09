<?php

class HomeController
{
    public function handleRequest()
    {
        // sets php variables and displays homepage
        $title = "brahhello";
        include "view/home.php";
    }
}
