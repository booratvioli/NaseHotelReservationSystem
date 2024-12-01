<?php
// send_status_email.php

session_start();
require_once('C:\xampp\htdocs\MARIANNE HOTEL\db_config.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
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
    echo json_encode(["error" => "User not found"]);
    exit();
}

// Fetch reservation statistics for the user
$stats_query = "SELECT
    COUNT(*) AS total_reservations,
    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) AS accepted_reservations,
    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_reservations,
    SUM(CASE WHEN status = 'Pending' OR status IS NULL THEN 1 ELSE 0 END) AS pending_reservations,
    SUM(CASE WHEN status = 'Accepted' THEN hours_reserved ELSE 0 END) AS total_hours_accepted,
    SUM(CASE WHEN status = 'Accepted' THEN reservation_cost ELSE 0 END) AS total_cost_accepted,
    SUM(CASE WHEN payment_method = 'gcash' THEN 1 ELSE 0 END) AS gcash_count,
    SUM(CASE WHEN payment_method = 'paymaya' THEN 1 ELSE 0 END) AS paymaya_count,
    SUM(CASE WHEN payment_method = 'visa' THEN 1 ELSE 0 END) AS visa_count
FROM reservations
WHERE user_id = $user_id";

$stats_result = $conn->query($stats_query);

// Check if the statistics are found
if ($stats_result->num_rows > 0) {
    $stats = $stats_result->fetch_assoc();
} else {
    // Set default values if no statistics are found
    $stats = [
        'total_reservations' => 0,
        'accepted_reservations' => 0,
        'rejected_reservations' => 0,
        'pending_reservations' => 0,
        'total_hours_accepted' => 0,
        'total_cost_accepted' => 0,
        'gcash_count' => 0,
        'paymaya_count' => 0,
        'visa_count' => 0,
    ];
}

// Send user statistics via email
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
    $mail->Subject = 'Your Reservation Statistics';
    $mail->Body = '
        <h2>Your Reservation Statistics</h2>
        <p>Total Reservations: ' . $stats['total_reservations'] . '</p>
        <p>Accepted Reservations: ' . $stats['accepted_reservations'] . '</p>
        <p>Rejected Reservations: ' . $stats['rejected_reservations'] . '</p>
        <p>Pending Reservations: ' . $stats['pending_reservations'] . '</p>
        <p>Total Hours (Accepted): ' . $stats['total_hours_accepted'] . '</p>
        <p>Total Cost (Accepted): ' . $stats['total_cost_accepted'] . '</p>
        <p>GCash Count: ' . $stats['gcash_count'] . '</p>
        <p>PayMaya Count: ' . $stats['paymaya_count'] . '</p>
        <p>Visa Count: ' . $stats['visa_count'] . '</p>
    ';

    // Send the email
    $mail->send();
    
} catch (Exception $e) {
    ;
}
?>;
