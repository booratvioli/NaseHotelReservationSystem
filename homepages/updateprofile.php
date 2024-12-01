
<?php
$messagee = "";
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
        $mail->Username   = 'itstest14342@gmail.com';  // Your Gmail email address
        $mail->Password   = 'app password';  // The app password you generated
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('itstest14342@gmail.com', 'Nase Hotel');
        $mail->addAddress($to);     

        // Content
        $mail->isHTML(true);                                  
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo '';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);

// Check if the user details are found
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Handle form submission for updating the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_lastname = $_POST["new_lastname"];
    $new_firstname = $_POST["new_firstname"];
    $new_middlename = $_POST["new_middlename"];
    $new_username = $_POST["new_username"];
    $new_emailaddress = $_POST["new_emailaddress"];
    $new_password = $_POST["new_password"];


    // Validate and update the user's profile in the database
    // You should add more validation and error handling as needed

    $update_query = "UPDATE users SET 
                    lastname = '$new_lastname', 
                    firstname = '$new_firstname', 
                    middlename = '$new_middlename', 
                    username = '$new_username',
                    emailaddress = '$new_emailaddress',
                    password = '$new_password' 
                    WHERE user_id = $user_id";

    $stmt = $conn->prepare("UPDATE users SET 
                    lastname = ?, 
                    firstname = ?, 
                    middlename = ?, 
                    username = ?,
                    emailaddress = ?,
                    password = ? 
                    WHERE user_id = ?");

    $stmt->bind_param("ssssssi", $new_lastname, $new_firstname, $new_middlename, $new_username, $new_emailaddress, $new_password, $user_id);

    if ($stmt->execute()) { 
        $messagee = "Profile updated successfully";
        // Refresh user details after update
        $user_result = $conn->query($user_query);
        $user = $user_result->fetch_assoc();

        // Get the username from the fetched user details
        $username = $user['username'];

        echo "<script>localStorage.setItem('message', '$messagee'); localStorage.setItem('messageType', 'success');</script>";

        $emailSubject = "Profile Updated";  // Define the email subject

        $emailMessage = "
        <html>
            <head>
                <title>Profile Updated - Nase Hotel</title>
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
                    <h2>Profile Updated</h2>
                    <p>Dear $username,</p>
                    <p>Your profile has been successfully updated.</p>
                    <p>If you did not request this change or if you have any questions, please contact support.</p>
                    <p>Best regards,<br>Nase Hotel</p>
                </div>
            </body>
        </html>
        ";
        sendEmail($new_emailaddress, $emailSubject, $emailMessage);
    } else {
        $messagee = "Error updating profile: " . $conn->error;
        echo "<script>localStorage.setItem('message', '$messagee'); localStorage.setItem('messageType', 'error');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <title>Update Profile</title>
    <!-- Add your styles or include external stylesheets here -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat';
        }

        body {
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        header {
            display: flex;
            align-items: center;
            width: 60%;
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
            width: 60%;
            float: left;
        }

        h2,h3 {
            color: black;
            font-size: 30px;
            text-align: center;
            font-weight: 800;
        }

        form {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: black;
        }

        input {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
        }

        button {
            margin-top: 20px;
            width: 100%;
            font-family: "Montserrat";
        }

        .back {
            position: absolute;
            left: 0;
            bottom: 0;
            margin: 10px;
        }

        .log1 {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .centered-image {
            max-width: 80%;
            max-height: 80%;
            margin-left: 20%;
            margin-top: 50%;
            object-fit: contain;
        }
        .placeholder {
            width: 60%;
            margin-top: 7%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            background-color: white;
            padding-bottom: 50px;
            padding-right: 90px;
            padding-left: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: white;
            overflow: hidden;
        }

        a {
            width: 100%;
            margin-top: 20px;
        }
        .mess{
            margin-top: 10px;
            color: green;
            font-weight: bolder;
        }
    </style>
</head>
<body>

    <div class="placeholder">

    <div class="container">

        <h2>Update Your Profile, <?php echo $user["username"]; ?>!</h2>

            <form class="col s12" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirmReset(event)">
                <label for="new_lastname">New Last Name:</label>
                <input type="text" id="new_lastname" name="new_lastname" value="<?php echo $user["lastname"]; ?>" required>

                <label for="new_firstname">New First Name:</label>
                <input type="text" id="new_firstname" name="new_firstname" value="<?php echo $user["firstname"]; ?>" required>

                <label for="new_middlename">New Middle Name:</label>
                <input type="text" id="new_middlename" name="new_middlename" value="<?php echo $user["middlename"]; ?>">

                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" value="<?php echo $user["username"]; ?>" required>

                <label for="new_emailaddress">New Email Address:</label>
                <input type="text" id="new_emailaddress" name="new_emailaddress" value="<?php echo $user["emailaddress"]; ?>" required>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>

                
                <button class="brown btn" type="submit">Update Profile</button>
                <a class="brown btn" href="customerprofile.php">Back to Profile></a>
                <div class="mess"><?php echo $messagee; ?></div>
            </form>
    </div>
    <div class="log1">
        <img class="centered-image" src="logoyata.svg" alt="Nase Hotel">
    </div>
    </div>
</div>


<script>
function validatePassword() {
    var password = document.getElementById("new_password").value;
    var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

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
    if (!validatePassword()) {
        console.log('Validation failed');
        event.preventDefault();  // Prevent the form from submitting
    } else {
        console.log('Validation passed');
        // Form will be submitted
    }
}





    window.onload = function() {
        var message = localStorage.getItem('message');
        var messageType = localStorage.getItem('messageType');
        if (message && messageType) {
            Swal.fire({
                icon: messageType,
                title: messageType.charAt(0).toUpperCase() + messageType.slice(1),
                text: message
            });
            localStorage.removeItem('message');
            localStorage.removeItem('messageType');
        }
    };
</script>

<footer style="position: fixed; bottom: 3%; left: 50%; transform: translateX(-50%); width: 50%; text-align: center; color: black; font-family: 'Montserrat';">
<hr><br>
    &copy; 2023 Nase Hotel. All Rights Reserved.
</footer>
</body>
</html>