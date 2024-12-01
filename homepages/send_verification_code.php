<?php
// send_verification_code.php

require 'vendor/autoload.php'; // Include PHPMailer autoloader
require_once('C:\xampp\htdocs\NASE HOTEL\db_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random verification code
function generateVerificationCode($length = 6)
{
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, $length));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Generate a verification code
    $verificationCode = generateVerificationCode();

    // Clear previous verification codes for the user
    $clearVerificationCodesQuery = "DELETE FROM verification_codes WHERE username = ?";
    $stmt = $conn->prepare($clearVerificationCodesQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    // Set verification code expiration time (adjust the time as needed)
    $expirationTime = time() + (24 * 60 * 60); // 24 hours

    // Insert the new verification code with expiration time
    $insertVerificationCodeQuery = "INSERT INTO verification_codes (username, code, expiration_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertVerificationCodeQuery);
    $stmt->bind_param("ssi", $email, $verificationCode, $expirationTime);
    $stmt->execute();
    $stmt->close();

    // Send the verification email
    $to = $email;
    $subject = 'NASE HOTEL - Email Verification';
    $message = "
    <html>
    <head>
        <title>Email Verification - NASE HOTEL</title>
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
            <img src='https://i.imgur.com/LrNesCy.png' alt='NASE HOTEL' style='width: 50%; height: auto;' />
            <h2>Email Verification</h2>
            <p>Dear User,</p>
            <p>Thank you for registering at NASE HOTEL. Your verification code is: $verificationCode</p>
            <p>Please enter this code in the verification field to complete your registration.</p>
            <p>If you did not request this code, please ignore this email or contact support if you have any questions.</p>
            <p>Best regards,<br>NASE HOTEL</p>
        </div>
    </body>
    </html>";

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;  // Set to 2 for detailed debugging
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'itstest14342@gmail.com';  // Your Gmail email address
        $mail->Password   = 'app password';  // The app password you generated
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('itstest14342@gmail.com', 'NASE HOTEL');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Send the verification email
        $mail->send();

        echo "Verification code sent to $email.";
    } catch (Exception $e) {
        // Log the error (you can customize this based on your logging mechanism)
        error_log("Error in send_verification_code.php: " . $e->getMessage());
        
        // Respond with a generic error message to the user
        echo "An error occurred while processing your request. Please try again later.";
    }

    // Close the database connection
    $conn->close();
}

