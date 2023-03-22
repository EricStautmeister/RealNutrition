<?php

class DashboardController {
    public function handleRequest() {
        include "./view/dash.php";
    }

    public function loginUser($newuser, $uid) {
        session_start();
        $_SESSION["user"] = $newuser;
        $_SESSION["uid"] = $uid;
    }
}
