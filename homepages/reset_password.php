<?php
session_start();

require_once('C:\xampp\htdocs\NASE HOTEL\db_config.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $message) {
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
        $mail->addAddress($to);     

        // Content
        $mail->isHTML(true);                                  
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
        $sql = "UPDATE users SET password=? WHERE emailaddress=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $_SESSION["email"]);
        $stmt->execute();
        $message = "
        <html>
        <head>
            <title>Password Reset Successful - Nase Hotel</title>
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
                <h2>Password Reset Successful</h2>
                <p>Dear User,</p>
                <p>Your password has been successfully updated. You can now log in with your new password.</p>
                <p>If you did not request this change or if you have any questions, please contact support.</p>
                <p>Best regards,<br>Nase Hotel Team</p>
            </div>
        </body>
        </html>";       sendEmail($_SESSION["email"], "Password Updated", $message);
        echo "Password updated successfully";
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        body {
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
        }
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 50%;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 18px;
        }
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var passwordIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.innerHTML = "visibility";
            } else {
                passwordInput.type = "password";
                passwordIcon.innerHTML = "visibility_off";
            }
        }

        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Passwords do not match.'
                });
                return false;
            }

            if (!passwordRegex.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password must be at least 8 characters long and include at least one capital letter, one small letter, one number, and one special character.'
                });
                return false;
            }

        console.log('Password is valid');

            return true;
        }

        function confirmReset(event) {
        event.preventDefault();  // Prevent the form from submitting
        if (!validatePassword()) {
            console.log('Validation failed');
            return;
        }
        console.log('Validation passed');

        Swal.fire({
            title: "Are you sure?",
            text: "Once reset, you will not be able to recover your old password!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("resetForm").submit();  // Submit the form
            }
        });
    }
    </script>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <!-- Add your logo here -->
                <img src="logo no bg.png" alt="Nase Hotel" style="width:350px; height:350px;">
            </div>
            <form id="resetForm" action="reset_password.php" method="post" onsubmit="confirmReset(event);">                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Reset Password" class="btn">
                </div>
            </form>
        </div>
    </body>
</html>