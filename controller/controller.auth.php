<?php

class AuthController
{
    public function handleRequest()
    {
        if (empty($_POST)) {
            include "form.testing.php";
        } else {
            $this->handlePost();
        }
    }

    private function handlePost()
    {
        var_dump($_POST);
    }
}
