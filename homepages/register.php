<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <title>Registration Page</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        header img {
            width: 60px;
            margin: 5px;
        }

        header .title {
            display: flex;
            align-items: center;
            background-color: white;
            z-index: 1;
        }

        header .title h1 {
            color: black;
            text-transform: uppercase;
            margin-left: 20px;
            font-size: 2rem;
        }

        header .navLinks ul {
            display: flex;
            justify-content: right;
            align-items: right;
            list-style: none;
            background-color: white;
            margin-top: -20px;
            height: 102px;
            box-shadow: none;
        }

        header .navLinks ul li {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 20px;
            background-color: white;
        }

        header .navLinks ul li a {
            color: black;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 400;
            background-color: white;
        }

        .container {
            width: 100%;
            background-color: white;
            padding: 28px;
            margin: 0 auto;
            border-radius: 10px;
            backdrop-filter: blur(2px);
        }

        .formTitle {
            font-size: 40px;
            text-align: center;
            color: black;
            font-weight: 900;
        }

        .mainuserInfo {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 10px 0;
        }

        .userinputBox {
            width: 48%;
            margin-bottom: 20px;
        }

        .userinputBox label {
            display: block;
            color: black;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .userinputBox input {
            height: 40px;
            width: 100%;
            border-radius: 7px;
            outline: none;
            border: 1px solid #ccc;
            padding: 0 10px;
        }
        .userinputBox button{
            background-color: #86654B;
        }

        .formsubmitBtn {
            text-align: center;
        }

        .formsubmitBtn button {
            width: 100%;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #86654B;
            outline: none;
            cursor: pointer;
        }

        .formsubmitBtn a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: black;
        }

        .log1 {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
            float: right;
        }

        .centered-image {
            max-width: 80%;
            max-height: 80%;
            margin-left: 20%;
            object-fit: contain;
        }

        .placeholder {
            width: 55%;
            display: flex;
            margin: 1% auto;
            justify-content: space-between;
            background-color: white;
            padding: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }

        footer {
            position: fixed;
            bottom: 3%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            text-align: center;
            color: black;
            font-family: 'Montserrat', sans-serif;
        }

        footer hr {
            border: 1px solid #ccc;
        }

        /* Add this style to your existing CSS or in the head section of your HTML */
.code-sent-message {
    opacity: 0;
    color: green;
    font-size: 16px;
    margin-top: 10px;
    transition: opacity 1s ease;
}

    </style>


<script>
    function validateNameInput(input) {
        var regex = /^[A-Za-z\s]+$/;
        if (!regex.test(input.value)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter only alphabetical characters and spaces.'
            });
            input.value = input.value.replace(/[^A-Za-z\s]/g, '');
        }
    }
    
</script>


    <script>

function isValidEmail(email) {
    // Regular expression to check if the email address has a valid format
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
function sendVerificationCode() {
        var emailInput = document.getElementById("emailaddress");

        if (emailInput && emailInput.value.trim() !== "") {
            var email = emailInput.value;

            if (isValidEmail(email)) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: xhr.responseText
                            });
                            showCodeSentAnimation();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error: ' + xhr.status
                            });
                        }
                    }
                };
                xhr.open("POST", "send_verification_code.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("email=" + email);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please input a valid email address.'
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please input a valid email address.'
            });
        }
    }

function isValidEmail(email) {
    // Regular expression to check if the email address has a valid format
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showCodeSentAnimation() {
        var codeSentMessage = document.getElementById("code-sent-message");
        var emailInput = document.getElementById("emailaddress");

        if (emailInput && isValidEmail(emailInput.value)) {
            codeSentMessage.style.opacity = "1";
            setTimeout(function () {
                codeSentMessage.style.opacity = "0";
            }, 10000);
        }
    }

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

        if (!passwordRegex.test(password)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 8 characters long and include at least one capital letter, one small letter, one number, and one special character.'
            });
            return false;
        }

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password and Confirm Password do not match.'
            });
            return false;
        }

        return true;
    }
</script>
</head>

<body>

    <div class="placeholder">
        <div class="container">
            <h2 class="formTitle">REGISTER</h2>
            <form action="register.php" method="post" class="register-form" onsubmit="return validatePassword()">
                <div class="mainuserInfo">
                    <!-- Example for the first name input -->
<div class="userinputBox">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" oninput="validateNameInput(this)" required>
</div>

<!-- Example for the first name input -->
<div class="userinputBox">
    <label for="middlename">Middle Name:</label>
    <input type="text" id="middlename" name="middlename" oninput="validateNameInput(this)" required>
</div>

<!-- Example for the first name input -->
<div class="userinputBox">
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" oninput="validateNameInput(this)" required>
</div>
            
                    <div class="userinputBox">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="userinputBox" onclick="togglePasswordVisibility('password', 'password-icon')">
                        <label for="password">Password:</label>
                        <div class="password-input">
                            <input type="password" id="password" name="password" required>
                            <span class="material-icons" id="password-icon">visibility_off</span>
                        </div>
                    </div>
                    <div class="userinputBox" onclick="togglePasswordVisibility('confirmPassword', 'confirmPassword-icon')">
                        <label for="confirmPassword">Confirm Password:</label>
                        <div class="password-input">
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                            <span class="material-icons" id="confirmPassword-icon">visibility_off</span>
                        </div>
                    </div>
                    <div class="userinputBox">
                        <label for="emailaddress">Email Address:</label>
                        <input type="email" id="emailaddress" name="emailaddress" required>
                    </div>

<!-- Add this inside the form where you want the verification code input -->
<div class="userinputBox">
    <label for="verificationCode">Verification Code:</label>
    <input type="text" id="verificationCode" name="verificationCode" required>
</div>

<div class="userinputBox">
    <button type="button" onclick="sendVerificationCode()" class="btn">Send Code</button>
</div>
<div id="code-sent-message" class="code-sent-message">Verification code sent!</div>


                    
        
                </div>
                <div class="formsubmitBtn">
                    <button class="btn" type="submit">Register</button>
                    <a href="login.php">Back to Login</a>
                </div>
            </form>
        </div>
        <div class="log1">
            <img class="centered-image" src="logoyata.svg" alt="Nase Hotel">
        </div>
    </div>

    <footer>
        <hr>
        <p>&copy; 2023 Nase Hotel. All Rights Reserved.</p>
    </footer>

    <script>

function validateRegistration() {
        // Add your existing validation logic here

        // Validate the verification code
        var verificationCodeInput = document.getElementById("verificationCode");

        if (!isValidVerificationCode(verificationCodeInput.value)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Invalid verification code. Please enter the correct code.'
            });
            return false;
        }

        return true;
    }

    function isValidVerificationCode(code) {
        // You can add a regular expression or any other validation logic for the verification code
        // For now, let's assume a simple length check
        return code.length === 6; // Change this according to your verification code logic
    }

function sendVerificationCode() {
        var emailInput = document.getElementById("emailaddress");

        if (emailInput && emailInput.value.trim() !== "") {
            var email = emailInput.value;

            if (isValidEmail(email)) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: xhr.responseText
                            });
                            showCodeSentAnimation();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error: ' + xhr.status
                            });
                        }
                    }
                };
                xhr.open("POST", "send_verification_code.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("email=" + email);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please input a valid email address.'
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please input a valid email address.'
            });
        }
    }




</script>
</body>
</html>

</body>

</html>



<?php
// register.php - User registration with email verification

require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');
require 'vendor/autoload.php'; // Include PHPMailer autoloader
include 'C:\xampp\htdocs\Nase Hotel\homepages\faqsbot.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random verification code
function generateVerificationCode($length = 6)
{
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, $length));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $middlename = $_POST["middlename"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $emailaddress = $_POST["emailaddress"];
    $enteredVerificationCode = $_POST["verificationCode"];

// Retrieve the verification code from the database based on the provided email
$verificationCodeQuery = "SELECT code FROM verification_codes WHERE username = ?";
$stmt = $conn->prepare($verificationCodeQuery);
$stmt->bind_param("s", $emailaddress);
$stmt->execute();
$stmt->bind_result($storedVerificationCode);
$stmt->fetch();
$stmt->close();

// Check if the entered verification code matches the stored one
if ($enteredVerificationCode !== $storedVerificationCode) {
    echo "<script>Swal.fire({ icon: 'error', title: 'Oops...', text: 'Invalid verification code. Please enter the correct code.' });</script>";
    exit; // Exit registration process if verification code is invalid
}

// After successful registration, you might want to remove the verification code from the database
$deleteVerificationCodeQuery = "DELETE FROM verification_codes WHERE username = ?";
$stmt = $conn->prepare($deleteVerificationCodeQuery);
$stmt->bind_param("s", $emailaddress);
$stmt->execute();
$stmt->close();


    // Validate and sanitize your input data here...

    // Check if the email address has valid DNS records
    list($user, $domain) = explode('@', $emailaddress);
    if (checkdnsrr($domain)) {
        // The domain has valid DNS records, proceed with sending verification email
        // ... (rest of your code)
    } else {
        // Display a message for invalid email domain
        echo "<script>alert('Invalid email address. Please input a valid email address.');</script>";
    }

    // Generate a verification code
    $verificationCode = generateVerificationCode();

    $to = $emailaddress;
    $subject = 'Nase Hotel - Email Verification';

    $message = "
    <html>
    <head>
        <title>Welcome to Nase Hotel</title>
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
            <img src='https://i.imgur.com/KmbtY1r.png' alt='Nase Hotel' style='width: 50%; height: auto;' />
            <h2>Welcome to Nase Hotel, $username!</h2>
            <p>Dear $username,</p>
            <p>Thank you for registering at Nase Hotel. Your account has been created successfully.</p>
            <p>To get started, please log in to your account and explore our services. If you have any questions or need assistance, please do not hesitate to contact our customer service at support@nasehotel.com.</p>
            <p>We look forward to serving you at Nase Hotel.</p>
            <p>Best regards,<br>Nase Hotel</p>
        </div>
    </body>
    </html>";

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Nase Hotel <noreply@nasehotel.com>';

    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
    
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
        $mail->addAddress($_POST['emailaddress'], $_POST['emailaddress']);

    // ...

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;



        // Add the verification code to the database
  // Add the verification code to the database
$insert_verification_query = "INSERT INTO verification_codes (username, code) VALUES ('$username', '$verificationCode')";
$conn->query($insert_verification_query);


// Insert the new user into the users table (with the password)
$insert_user_query = "INSERT INTO users (firstname, middlename, lastname, username, password, emailaddress) VALUES ('$firstname', '$middlename', '$lastname', '$username', '$password', '$emailaddress')";
$conn->query($insert_user_query);



        // Send the verification email
        $mail->send();
        echo "<script>Swal.fire({ icon: 'success', title: 'Registration successful.' });</script>";
    } catch (Exception $e) {
        echo "<script>Swal.fire({ icon: 'error', title: 'Error', text: 'Error: " . $e->getMessage() . "' });</script>";
    }
}

$conn->close();
?>
