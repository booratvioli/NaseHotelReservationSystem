<?php
session_start();
require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');

// Composer autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST["reservation_id"];
    $password = $_POST["password"];

    // Fetch user details to get the stored password
    $user_id = $_SESSION["user_id"];
    $user_query = "SELECT * FROM users WHERE user_id = $user_id";
    $user_result = $conn->query($user_query);

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $stored_password = $user["password"];

        // Verify the entered password against the stored password
        if ($password === $stored_password) {
            // Check if the reservation is still pending or has a null status before attempting to cancel it
            $reservation_query = "SELECT * FROM reservations WHERE reservation_id = $reservation_id AND (status = 'Pending' OR status IS NULL)";
            $reservation_result = $conn->query($reservation_query);

            if ($reservation_result->num_rows > 0) {
                // Reservation is still pending or has a null status, proceed with cancellation
                $reservation = $reservation_result->fetch_assoc();

                $delete_query = "DELETE FROM reservations WHERE reservation_id = $reservation_id";

                if ($conn->query($delete_query) === TRUE) {
                    echo "Reservation canceled successfully.";

                    // Fetch user's registered email address
                    $emailQuery = "SELECT emailaddress FROM users WHERE user_id = $user_id";
                    $emailResult = $conn->query($emailQuery);

                    if ($emailResult->num_rows > 0) {
                        $emailRow = $emailResult->fetch_assoc();
                        $to = $emailRow["emailaddress"];

                        // Send email notification with HTML formatting
                        $subject = "Reservation Cancellation Confirmation";

                        $message = "
                            <html>
                            <head>
                                <style>
                                    body {
                                        font-family: 'Arial', sans-serif;
                                        background-color: #f4f4f4;
                                        margin: 0;
                                        padding: 0;
                                    }

                                    .container {
                                        max-width: 600px;
                                        margin: 30px auto;
                                        background-color: #ffffff;
                                        padding: 20px;
                                        border-radius: 8px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                    }

                                    h2 {
                                        color: #333;
                                    }

                                    p {
                                        color: #555;
                                    }

                                    .details {
                                        margin-top: 20px;
                                    }

                                    .details table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-top: 10px;
                                    }

                                    .details table, .details th, .details td {
                                        border: 1px solid #ddd;
                                    }

                                    .details th, .details td {
                                        padding: 10px;
                                        text-align: left;
                                    }

                                    .details th {
                                        background-color: #f2f2f2;
                                    }
                                    
                                    
                                </style>
                            </head>
                            <body>
                                <div class='container'>
                                <img src='https://i.imgur.com/KmbtY1r.png' alt='Nase Hotel' style='width: 50%; height: auto;' />
                                <h2>Reservation Cancellation Confirmation</h2>
                                    <p>Dear " . $user["username"] . ",</p>
                                    <p>Your reservation with ID $reservation_id has been successfully canceled.</p>
                                    <div class='details'>
                                        <h3>Canceled Reservation Details</h3>
                                        <table>
                                            <tr>
                                                <th>Reservation Date</th>
                                                <td>" . $reservation["reservation_date"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Reservation Time</th>
                                                <td>" . $reservation["reservation_time"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Room Category</th>
                                                <td>" . $reservation["room_category"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Hours Reserved</th>
                                                <td>" . $reservation["hours_reserved"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Room Number</th>
                                                <td>" . $reservation["room_number"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Reservation Cost</th>
                                                <td>" . $reservation["reservation_cost"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Method</th>
                                                <td>" . $reservation["payment_method"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Advance Payment</th>
                                                <td>" . $reservation["advance_payment"] . "</td>
                                            </tr>
                                            <tr>
                                                <th>Reference Number</th>
                                                <td>" . $reservation["reference_number"] . "</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <p>Thank you for using our reservation system.</p>
                                    <p>Best regards,<br>Nase Hotel</p>
                                </div>
                            </body>
                            </html>
                        ";

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
                    $mail->addAddress($to); // Add recipient address here

                // ...

                            // Content
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body    = $message;

                            // Send the email
                            $mail->send();
                            echo ' Email sent.';
                        } catch (Exception $e) {
                            echo ' Error sending email: ' . $mail->ErrorInfo;
                        }
                    } else {
                        echo "Error fetching user's email address.";
                        // You might want to handle this error appropriately.
                    }
                } else {
                    echo "Error canceling reservation: " . $conn->error;
                }
            } else {
                // Reservation is not pending and status is not null, cannot be canceled
                echo "Reservation is not pending and cannot be canceled.";
            }
        } else {
            // Password is incorrect
            echo "Incorrect password. Reservation not canceled.";
        }
    } else {
        // User not found
        echo "User not found. Reservation not canceled.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();

