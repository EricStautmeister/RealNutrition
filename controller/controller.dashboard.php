<?php

class DashboardController {
    public function handleRequest() {
        include "./view/dash.php";
    }

    public function loginUser($newuser) {
        session_start();
        $_SESSION["user"] = $newuser;
    }
}
