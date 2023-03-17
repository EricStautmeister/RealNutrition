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
            AuthController::loginDashboard($_REQUEST["email"]);
        } catch (Exception $error) {
            print_r($error);
        }
    }

    public static function displayEmailPage($email) {
        include "email.testing.php";
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
        
        <body>
            <p>Click this Button to Authenticate your Email</p>
            <a href='http://localhost:8000/email?mail=$email&token=$token'>Authenticate</a>
            <p>If you did not sign up to RealNutrition please ignore this email</p>
        </body>
        
        </html>";

        $mail->send();
    }
}
