<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];

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
                $mail->addAddress($email); // Add user's email address here

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Thank you for your feedback';
                $mail->Body    = '
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
                    <div class="container">
                        <img src="https://i.imgur.com/LrNesCy.png" alt="Nase Hotel" style="width: 50%; height: auto;" />
                        <h2>Welcome to Nase Hotel, ' . $name . '</h2>
                        
                        <p>Thank you for taking the time to provide us with your feedback. We value your input and will use it to improve our services.</p>
                        <p>We appreciate your support and look forward to serving you in the future.</p>
                        <p>Best regards,<br>Nase Hotel</p>
                    </div>
                </body>
                </html>';
                $mail->send();
                $_SESSION['email_sent'] = true;
            } catch (Exception $e) {
                $_SESSION['email_sent'] = false;
                $_SESSION['error_message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
}    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Montserrat";
}
body{
    padding: 0 10% 0 10%;
    background-image: url('bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 100px;
    top: 0;
    border-bottom: 2px solid grey;
}

header img{
    width: 60px;
}
header .title{
    display: flex;
    align-items: center;
}

header .title h1{
    color: black;
    text-transform:uppercase;
    margin-left: 10px;
    font-size: 2rem;
}

header .navLinks ul {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none; /* Remove default list styles */
}

header .navLinks ul li {
    display: flex; /* Make list items flex containers */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    margin: 0 20px; /* Add spacing between list items if desired */
}

header .navLinks ul li a {
    color: black;
    text-decoration: none; /* Remove underline from links */
    font-size: 1.5rem;
    font-weight: 400;
}
.mainSec{
    padding: 5%;
    width: 100%;
    height: 85vh;
    display: flex;
    background-color: white;
    margin-top: 2%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}
.left{
    padding: 10px;
    width: 50%;
}
.mainSec .left .text{
    width: 100%;
    height: 25%;
}
.mainSec .left .text h1{
    font-size: 2rem;
    margin-bottom: 10px;
}
.mainSec .left .text p{
    text-align: justify;
}
.mainSec .left .loc{
    width: 100%;
    height: 65%;
}

.mainSec .right{
    padding: 10px;
    width: 50%;
}
.mainSec .right .contact{
    width: 100%;
    height: 20vh;
}
.mainSec .right .contact h1{
    font-size: 2rem;
    margin-bottom: 10px;
}
.mainSec .right form{
    width: 100%;
    display: flex;
    flex-direction: column;
}
.mainSec .right form input{
    margin-bottom: 10px;
    width: 100%;
    height: 50px;
    padding: 10px;
    color: black;
    border-radius: 5px;
    border: 1px solid grey;
}
.mainSec .right form button{
    width: 30%;
    padding: 10px;
    color: white;
    background-color: #86654B;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    font-size: 20px;
}
.mainSec .right form .msg{
    margin-bottom: 10px;
    width: 100%;
    height: 150px;
}
.btn {
    background-color: white;
    width: 10px;
}
#links {
    border: 2px black;
    padding: 15px;
    padding-left: 18px;
    padding-right: 18px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    float: right;
    margin-left: 30px;
    color: white;
    text-decoration: none;
    border-radius: 35px;
    font-weight: 900;
    font-size: 20px;  
    background-color: #86654B; 
}
/* CONTACT STYLE */

    </style>
    <title>Contact Us</title>
</head>
<body>
<header>
        <div class="title">
            <img src="logo.png" alt="logo" class="logo" />
            <h1>Nase</h1>
        </div>

        <nav class="navLinks">
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="About.php">About</a></li>
                <li><a href="pricing-table.php">Rooms</a></li>
                <li><a href="ContactUs.php">Contacts</a></li>
                <li><a href="login.php"><i class="fa-regular fa-user"></i></a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="mainSec">
            <div class="left">
                <div class="text">
                    <h1>Location <i class="fa-solid fa-location-dot"></i></h1>
                    <p>You'll find Nase Hotel right next to Puregold in the Tomas de Leon compound on Molino Rd in Bacoor, Cavite, Philippines. We're all about offering you a super convenient location for exploring the city and a cozy, welcoming stay for your trip. Come and be our guest!</p>
                </div>
                <div class="loc">
                <!-- Embedding the map with coordinates directly -->
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    style="border:0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.8685461837427!2d120.97767141484618!3d14.410634889845373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9598a13d13b%3A0x29ec67d4224b2e8d!2sMolino%20Road%2C%20Bacoor%2C%20Cavite%2C%20Philippines!5e0!3m2!1sen!2sph!4v1637987490363!5m2!1sen!2sph"
                    allowfullscreen
                ></iframe>
            </div>
            </div>

            <div class="right">
                <div class="contact">
                    <h1>Contacts</h1>
                    <p>Please fill out the form to send us an email.</p>
                </div>
<!-- Your existing HTML form -->
<form action="" method="post">
    <input type="text" name="name" id="" placeholder="Name">
    <input type="email" name="email" id="" placeholder="Email">
    <input type="text" name="subject" id="" placeholder="Subject">
    <textarea class="msg" name="message" id="" placeholder="Message"></textarea>
    <div class="social">
        <button type="submit">Submit</button>
        <a id="links" href="#" class="fa fa-facebook"></a>
        <a id="links" href="#" class="fa fa-twitter"></a> 
        <a id="links" href="#" class="fa fa-instagram"></a> 
    </div>
</form>

<script>
    window.onload = function() {
        <?php
        if (isset($_SESSION['email_sent'])) {
            if ($_SESSION['email_sent']) {
                echo 'Swal.fire("Feedback Submitted Successfully", "We appreciate your feedback. Our team will review your comments to improve our services. Thank you for your time.", "success");';
            } else {
                echo 'Swal.fire("Error!", "'.$_SESSION['error_message'].'", "error");';
            }
            // Unset the session variables for the next request
            unset($_SESSION['email_sent']);
            unset($_SESSION['error_message']);
        }
        ?>
    }
    </script>
            </div>
        </section>
       
    </main>


    <footer style="position: fixed; bottom: 3%; left: 50%; transform: translateX(-50%); width: 60%; text-align: center; color: black; font-family: 'Montserrat';">
<hr><br>
    &copy; 2024 Nase Hotel. All Rights Reserved.
</footer>



    <script src="https://kit.fontawesome.com/fb68db4d3c.js" crossorigin="anonymous"></script>
</body>
</html>