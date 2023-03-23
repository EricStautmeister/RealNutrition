<?php

class DashboardController {
    public function handleRequest() {
        include "./view/dash.php";
    }

    public function loginUser($newuser, $uid) {
        session_start();
        $_SESSION["user"] = explode("@", htmlspecialchars($newuser))[0];
        $_SESSION["uid"] = $uid;
    }
}
