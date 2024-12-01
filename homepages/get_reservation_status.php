<?php
// get_reservation_status.php - Fetch reservation status summary

session_start();
require_once('C:\xampp\htdocs\Nase Hotel\db_config.php');

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch reservation status summary
$status_query = "SELECT
    COUNT(CASE WHEN status = 'Rejected' THEN 1 END) AS rejected_count,
    COUNT(CASE WHEN status = 'Accepted' THEN 1 END) AS accepted_count,
    COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pending_count,
    SUM(CASE WHEN status = 'Accepted' THEN reservation_cost ELSE 0 END) AS total_cost
FROM reservations WHERE user_id = $user_id";

$status_result = $conn->query($status_query);

if ($status_result->num_rows > 0) {
    $status_data = $status_result->fetch_assoc();
    echo "Rejected: " . $status_data['rejected_count'] . "<br>";
    echo "Accepted: " . $status_data['accepted_count'] . "<br>";
    echo "Pending: " . $status_data['pending_count'] . "<br>";
    echo "Total Cost of Accepted Reservations: $" . $status_data['total_cost'];
} else {
    echo "No reservations found.";
}

// Close the database connection
$conn->close();
?>;
