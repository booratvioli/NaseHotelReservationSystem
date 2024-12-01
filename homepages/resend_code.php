<?php
session_start();

require 'vendor/autoload.php';  // Path to the Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Resend the code...
$_SESSION["code"] = rand(1000, 9999);

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 0;  // Set to 2 for detailed debugging
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nasehotel@gmail.com';  // Your Gmail email address
    $mail->Password   = 'yotb ghwi orqs npzx';  // The app password you generated
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('nasehotel@gmail.com', 'Nase Hotel');
    $mail->addAddress($_SESSION['email']);  // Add a recipient

    // Content
    $mail->isHTML(true);  // Set email format to HTML
    $mail->Subject = 'Your Verification Code';
    $mail->Body = "
    <html>
    <head>
        <title>Resend Verification Code - Nase Hotel</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
            }
            .container {
                width: 80%;
                margin: auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            h2 {
                color: #444;
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <img src='https://i.imgur.com/LrNesCy.png' alt='Nase Hotel' style='width: 50%; height: auto;' />
            <h2>Resend Verification Code</h2>
            <p>Dear User,</p>
            <p>We received a request to resend your verification code. Your new verification code is: " . $_SESSION["code"] . "</p>
            <p>Please enter this code in the verification field to complete your registration.</p>
            <p>If you did not request this code, please ignore this email or contact support if you have any questions.</p>
            <p>Best regards,<br>Nase Hotel</p>
        </div>
    </body>
    </html>";
    $mail->send();
    echo 'Code resent successfully!';
} catch (Exception $e) {
    echo "Failed to resend code. Mailer Error: {$mail->ErrorInfo}";
}
