<?php

class DashboardController
{
    public function handleRequest()
    {
        include "dashboard.testing.php";
    }

    public function loginUser($newuser)
    {
        session_start();
        $_SESSION["user"] = $newuser;
    }
}
