<?php
session_start(); // Start the session at the beginning of your script

// Rest of your code...
require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');
require 'vendor/autoload.php'; // Assuming you installed PHPMailer using composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$errors = []; // Array to store error messages

function sendEmail($to, $subject, $message) {
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
        $mail->setFrom('itstest14342@gmail.com', 'Nase Hotel');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
        $sql = "SELECT * FROM users WHERE emailaddress=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            
        } else {
            $code = rand(1000, 9999);
            $_SESSION["code"] = $code;
            $_SESSION["email"] = $email;
            $message = "
            <html>
            <head>
                <title>Password Reset Verification - Nase Hotel</title>
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
                    <h2>Password Reset Verification</h2>
                    <p>Dear User,</p>
                    <p>We received a request to reset your password. Your verification code is: $code</p>
                    <p>Please enter this code in the verification field to proceed with password reset.</p>
                    <p>If you did not request this code, please ignore this email or contact support if you have any questions.</p>
                    <p>Best regards,<br>Nase Hotel</p>
                </div>
            </body>
            </html>";

            sendEmail($email, "Password Reset Verification", $message);            header("Location: verify_code.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert2 library -->

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
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="logo no bg.png" alt="Nase Hotel" style="width:350px; height:350px;">
        </div>
        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn">Send Verification Code</button>
        </form>
        <div class="links">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="/homepages/home.php">Home</a>
        </div>
    </div>

    <script>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
                $sql = "SELECT * FROM users WHERE emailaddress=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    echo "Swal.fire('Error', 'Email not found', 'error');"; // Display SweetAlert
                } else {
                    $code = rand(1000, 9999);
                    $_SESSION["code"] = $code;
                    $_SESSION["email"] = $email;
                    sendEmail($email, "Verification Code", "Your verification code is: " . $code);
                    echo "Swal.fire('Success', 'Message has been sent', 'success');"; // Display SweetAlert
                    header("Location: verify_code.php");
                    exit;
                }
            }
        }
        ?>
    </script>
</body>
</html>