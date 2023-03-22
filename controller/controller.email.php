<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController {
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModelWrapper();
    }
    public function handleRequest() {
        try {
            $this->authModel->verifyNewUser($_REQUEST["email"], $_REQUEST["token"]);
            $auth = new AuthController();
            $auth->loginDashboard($_REQUEST["email"]);
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    public static function displayEmailPage($email) {
        include "./view/mail.php";
    }

    public static function sendMail($email, $token) {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV["EMAIL"];
        $mail->Password   = $_ENV["EMAIL_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($_ENV["EMAIL"]);

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = "Realnutrition Email Auth";

        $mail->Body = "<html>
        <head>
            <title>Email Validation</title>
        </head>
    
        <body
            style=\"
                padding: 0;
                margin: 0;
                overflow: hidden;
                /* align-items: center; */
                height: 100%;
                width: 100%;
            \"
        >
            <style>
                @import url(\"https://fonts.googleapis.com/css?family=Work Sans\");
                p {
                    font-family: Work Sans;
                }
            </style>
            
            <p style=\"color: black; font-size: 15px; position: absolute; left: 45%; top: 36%;\">
                Click this Button to Authenticate your Email
            </p>
            <button
                style=\"
                    color: black;
                    position: absolute;
                    left: 45%;
                    top: 45%;
                    font-size: 20px;
                    width: fit-content;
                    border-radius: 10px;
                    padding: 10px 15px;
                    border: none;
                    background: linear-gradient(to right, #c53b44, #fb9e61);
                    cursor: pointer;
                \"
            >
                <a href=\"http://localhost:8000/email?email=$email&token=$token\" style=\"color: white; text-decoration: none;\">Authenticate</a>
            </button>
            <p style=\"color: black; font-size: 15px; position: absolute; left: 45%; top: 52%; width: 300px;\">
                If you did not sign up to RealNutrition please ignore this email
            </p>
            <img
                src=\"https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1032&q=80;
        \"
                alt=\"\"
                style=
                    width: 800px;
                    height: 500px;
                    object-fit: cover;
                    position: absolute;
                    z-index: -1;
                \"
            />
        </body>
    </html>
    ";
        $mail->send();
    }
}
